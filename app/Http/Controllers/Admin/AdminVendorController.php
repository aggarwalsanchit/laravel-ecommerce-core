<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\VendorTaxInfo;
use App\Models\VendorBankInfo;
use App\Models\VendorDocument;
use App\Traits\NotifiesVendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;

class AdminVendorController extends Controller
{
    // use NotifiesVendor;

    public static function middleware(): array
    {
        return [
            'auth:admin',
            new Middleware('permission:view_vendors'),
        ];
    }

    /**
     * Display list of all vendors
     */
    public function index(Request $request)
    {
        $query = Vendor::with(['taxInfo', 'bankInfo', 'documents', 'roles']);

        // Filter by role
        if ($request->filled('role')) {
            if ($request->role == 'pending') {
                $query->role('vendor');
            } elseif ($request->role == 'approved') {
                $query->role('store_owner');
            }
        }

        // Filter by account status
        if ($request->filled('status')) {
            $query->where('account_status', $request->status);
        }

        // Filter by verification status
        if ($request->filled('verification')) {
            $query->where('verification_status', $request->verification);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('shop_name', 'like', "%{$search}%")
                    ->orWhere('shop_email', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Sort
        switch ($request->get('sort', 'latest')) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'name_asc':
                $query->orderBy('shop_name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('shop_name', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $vendors = $query->paginate(20);

        // Stats for dashboard
        $stats = [
            'total' => Vendor::count(),
            'pending_approval' => Vendor::role('vendor')->where('profile_completed', '>=', 80)->count(),
            'pending_profile' => Vendor::role('vendor')->where('profile_completed', '<', 80)->count(),
            'active' => Vendor::role('store_owner')->where('account_status', 'active')->count(),
            'suspended' => Vendor::where('account_status', 'suspended')->count(),
        ];

        // For AJAX request
        if ($request->ajax()) {
            $table = view('admin.pages.vendors.partials.vendors-table', compact('vendors'))->render();
            $pagination = $vendors->appends(request()->query())->links('pagination::bootstrap-5')->render();

            return response()->json([
                'table' => $table,
                'pagination' => $pagination
            ]);
        }

        return view('admin.pages.vendors.index', compact('vendors', 'stats'));
    }

    /**
     * Show vendor details
     */
    public function show($id)
    {
        $vendor = Vendor::with([
            'taxInfo',
            'bankInfo',
            'documents',
            'categories',
            'products' => function ($q) {
                $q->latest()->limit(5);
            }
        ])->findOrFail($id);

        $profileCompletion = $this->calculateCompletion($vendor);

        return view('admin.pages.vendors.show', compact('vendor', 'profileCompletion'));
    }

    /**
     * Show vendor approval form
     */
    public function approveForm($id)
    {
        $vendor = Vendor::with(['taxInfo', 'bankInfo', 'documents'])->findOrFail($id);

        return view('admin.pages.vendors.approve', compact('vendor'));
    }

    /**
     * Approve vendor - Change role from 'vendor' to 'store_owner'
     */
    public function approve(Request $request, $id)
    {
        $request->validate([
            'verification_notes' => 'nullable|string',
            'commission_rate' => 'nullable|integer|min:0|max:100',
        ]);

        $vendor = Vendor::findOrFail($id);

        DB::beginTransaction();

        try {
            // Remove the 'vendor' role
            $vendor->removeRole('vendor');

            // Assign 'store_owner' role
            $vendor->assignRole('store_owner');

            // Grant all permissions
            $allPermissions = Permission::where('guard_name', 'vendor')->get();
            $vendor->syncPermissions($allPermissions);

            // Update vendor status
            $vendor->update([
                'account_status' => 'active',
                'verification_status' => 'verified',
                'verified_at' => now(),
                'verified_by' => auth()->guard('admin')->id(),
                'verification_notes' => $request->verification_notes,
                'commission_rate' => $request->commission_rate ?? $vendor->commission_rate,
                'approved_at' => now(),
                'approved_by' => auth()->guard('admin')->id(),
            ]);

            // Also verify tax info and bank info
            if ($vendor->taxInfo) {
                $vendor->taxInfo->update([
                    'verification_status' => 'verified',
                    'verified_at' => now(),
                    'verified_by' => auth()->guard('admin')->id(),
                ]);
            }

            if ($vendor->bankInfo) {
                $vendor->bankInfo->update([
                    'verification_status' => 'verified',
                    'verified_at' => now(),
                    'verified_by' => auth()->guard('admin')->id(),
                ]);
            }

            // Send approval notification
            // $this->sendApprovalNotification($vendor);

            DB::commit();

            return redirect()->route('admin.vendors.index')
                ->with('success', 'Vendor approved and upgraded to Store Owner successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Reject vendor application
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string',
        ]);

        $vendor = Vendor::findOrFail($id);

        $vendor->update([
            'account_status' => 'rejected',
            'verification_status' => 'rejected',
            'verification_notes' => $request->rejection_reason,
            'verified_by' => auth()->guard('admin')->id(),
            'verified_at' => now(),
        ]);

        // Send rejection notification
        $this->sendRejectionNotification($vendor, $request->rejection_reason);

        return redirect()->route('admin.vendors.index')
            ->with('error', 'Vendor application rejected!');
    }

    /**
     * Suspend vendor
     */
    public function suspend(Request $request, $id)
    {
        $request->validate([
            'suspension_reason' => 'required|string',
        ]);

        $vendor = Vendor::findOrFail($id);

        $vendor->update([
            'account_status' => 'suspended',
            'suspension_reason' => $request->suspension_reason,
            'suspended_at' => now(),
        ]);

        // Send suspension notification
        $this->sendSuspensionNotification($vendor, $request->suspension_reason);

        return redirect()->route('admin.vendors.show', $vendor->id)
            ->with('warning', 'Vendor suspended successfully!');
    }

    /**
     * Activate suspended vendor
     */
    public function activate($id)
    {
        $vendor = Vendor::findOrFail($id);

        $vendor->update([
            'account_status' => 'active',
            'suspension_reason' => null,
            'suspended_at' => null,
        ]);

        // Send activation notification
        $this->sendActivationNotification($vendor);

        return redirect()->route('admin.vendors.show', $vendor->id)
            ->with('success', 'Vendor activated successfully!');
    }

    /**
     * Delete vendor
     */
    public function destroy($id)
    {
        $vendor = Vendor::findOrFail($id);

        DB::beginTransaction();

        try {
            // Delete associated files
            if ($vendor->avatar && Storage::disk('public')->exists($vendor->avatar)) {
                Storage::disk('public')->delete($vendor->avatar);
            }
            if ($vendor->shop_logo && Storage::disk('public')->exists($vendor->shop_logo)) {
                Storage::disk('public')->delete($vendor->shop_logo);
            }
            if ($vendor->shop_banner && Storage::disk('public')->exists($vendor->shop_banner)) {
                Storage::disk('public')->delete($vendor->shop_banner);
            }

            // Delete documents
            foreach ($vendor->documents as $document) {
                if (Storage::disk('public')->exists($document->document_path)) {
                    Storage::disk('public')->delete($document->document_path);
                }
            }

            $vendor->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Vendor deleted successfully!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk action for vendors
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,approve,suspend',
            'vendor_ids' => 'required|json',
        ]);

        $vendorIds = json_decode($request->vendor_ids, true);

        if (empty($vendorIds)) {
            return response()->json([
                'success' => false,
                'message' => 'No vendors selected.'
            ], 400);
        }

        DB::beginTransaction();

        try {
            switch ($request->action) {
                case 'delete':
                    foreach ($vendorIds as $id) {
                        $vendor = Vendor::find($id);
                        if ($vendor) {
                            // Delete files
                            if ($vendor->avatar && Storage::disk('public')->exists($vendor->avatar)) {
                                Storage::disk('public')->delete($vendor->avatar);
                            }
                            if ($vendor->shop_logo && Storage::disk('public')->exists($vendor->shop_logo)) {
                                Storage::disk('public')->delete($vendor->shop_logo);
                            }
                            foreach ($vendor->documents as $document) {
                                if (Storage::disk('public')->exists($document->document_path)) {
                                    Storage::disk('public')->delete($document->document_path);
                                }
                            }
                            $vendor->delete();
                        }
                    }
                    $message = count($vendorIds) . ' vendor(s) deleted successfully.';
                    break;

                case 'approve':
                    foreach ($vendorIds as $id) {
                        $vendor = Vendor::find($id);
                        if ($vendor && $vendor->hasRole('vendor') && $vendor->profile_completed >= 80) {
                            $vendor->removeRole('vendor');
                            $vendor->assignRole('store_owner');
                            $allPermissions = Permission::where('guard_name', 'vendor')->get();
                            $vendor->syncPermissions($allPermissions);
                            $vendor->update([
                                'account_status' => 'active',
                                'verification_status' => 'verified',
                                'verified_at' => now(),
                                'verified_by' => auth()->guard('admin')->id(),
                                'approved_at' => now(),
                                'approved_by' => auth()->guard('admin')->id(),
                            ]);
                            // $this->sendApprovalNotification($vendor);
                        }
                    }
                    $message = count($vendorIds) . ' vendor(s) approved successfully.';
                    break;

                case 'suspend':
                    foreach ($vendorIds as $id) {
                        $vendor = Vendor::find($id);
                        if ($vendor && $vendor->account_status == 'active') {
                            $vendor->update([
                                'account_status' => 'suspended',
                                'suspension_reason' => 'Bulk suspension action',
                                'suspended_at' => now(),
                            ]);
                            $this->sendSuspensionNotification($vendor, 'Bulk suspension action');
                        }
                    }
                    $message = count($vendorIds) . ' vendor(s) suspended successfully.';
                    break;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Calculate profile completion percentage
     */
    protected function calculateCompletion($vendor)
    {
        $completed = 0;
        $totalRequired = 22;

        // Personal Info (3 fields)
        if ($vendor->name) $completed++;
        if ($vendor->email) $completed++;
        if ($vendor->phone) $completed++;

        // Shop Info (10 fields)
        if ($vendor->shop_name) $completed++;
        if ($vendor->shop_description) $completed++;
        if ($vendor->shop_phone) $completed++;
        if ($vendor->shop_address) $completed++;
        if ($vendor->shop_city) $completed++;
        if ($vendor->shop_state) $completed++;
        if ($vendor->shop_country) $completed++;
        if ($vendor->shop_postal_code) $completed++;
        if ($vendor->shop_logo) $completed++;
        if ($vendor->shop_email) $completed++;

        // Tax Info (4 fields)
        if ($vendor->taxInfo) {
            if ($vendor->taxInfo->gst_number) $completed++;
            if ($vendor->taxInfo->pan_number) $completed++;
            if ($vendor->taxInfo->pan_holder_name) $completed++;
            if ($vendor->taxInfo->business_registration_number) $completed++;
        }

        // Bank Info (5 fields)
        if ($vendor->bankInfo) {
            if ($vendor->bankInfo->account_holder_name) $completed++;
            if ($vendor->bankInfo->account_number) $completed++;
            if ($vendor->bankInfo->bank_name) $completed++;
            if ($vendor->bankInfo->bank_branch) $completed++;
            if ($vendor->bankInfo->ifsc_code) $completed++;
        }

        return round(($completed / $totalRequired) * 100);
    }
}
