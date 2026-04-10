<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendor\Shop;
use App\Models\Vendor;
use App\Models\Country;
use App\Models\State;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminVendorController extends Controller
{
    public function index(Request $request)
    {
        // Get all shops with their owner (vendor)
        $query = Shop::with(['owner', 'taxInfo', 'bankInfo', 'documents', 'categories']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('shop_name', 'like', "%{$search}%")
                    ->orWhere('shop_email', 'like', "%{$search}%")
                    ->orWhere('shop_phone', 'like', "%{$search}%")
                    ->orWhereHas('owner', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        // Filter by verification status
        if ($request->filled('verification')) {
            $query->where('account_status', $request->verification);
        }

        // Filter by ready_for_approve
        if ($request->filled('ready_for_approve')) {
            if ($request->ready_for_approve === 'yes') {
                $query->where('ready_for_approve', true);
            } elseif ($request->ready_for_approve === 'no') {
                $query->where('ready_for_approve', false);
            }
        }

        $shops = $query->latest()->paginate(20);

        // Statistics
        $stats = [
            'total' => Shop::count(),
            'suspended' => Shop::where('account_status', 'suspended')->count(),
            'pending' => Shop::where('account_status', 'pending')->count(),
            'verified' => Shop::where('account_status', 'verified')->count(),
            'rejected' => Shop::where('account_status', 'rejected')->count(),
        ];

        // Get unique vendor types for filter
        $vendorTypes = Shop::distinct()->pluck('vendor_type');

        if ($request->ajax()) {
            $table = view('admin.pages.vendors.partials.vendors-table', compact('shops'))->render();
            $pagination = $shops->links('pagination::bootstrap-5')->render();

            return response()->json([
                'table' => $table,
                'pagination' => $pagination
            ]);
        }

        return view('admin.pages.vendors.index', compact('shops', 'stats', 'vendorTypes'));
    }

    public function show($id)
    {
        // Get shop with all related data
        $shop = Shop::with([
            'owner',
            'taxInfo',
            'bankInfo',
            'documents',
            'categories'
        ])->findOrFail($id);

        // Get all staff members for this shop
        $staffMembers = Vendor::where('shop_id', $shop->id)
            ->where('id', '!=', $shop->owner->id ?? 0)
            ->whereNotIn('vendor_role', ['vendor', 'store_owner'])
            ->get();

        $countries = Country::all();
        $states = State::where('country_id', $shop->country_id ?? 0)->get();
        $allCategories = Category::where('status', true)->orderBy('name')->get();

        return view('admin.pages.vendors.show', compact('shop', 'countries', 'states', 'allCategories', 'staffMembers'));
    }

    public function staff(Request $request, $id)
    {
        $shop = Shop::findOrFail($id);

        $query = Vendor::where('shop_id', $shop->id);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->where('vendor_role', $request->role);
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $staffMembers = $query->paginate(20);

        // Statistics
        $stats = [
            'total' => Vendor::where('shop_id', $shop->id)->count(),
            'active' => Vendor::where('shop_id', $shop->id)->where('is_active', true)->count(),
            'inactive' => Vendor::where('shop_id', $shop->id)->where('is_active', false)->count(),
            'roles' => Vendor::where('shop_id', $shop->id)->distinct('vendor_role')->count('vendor_role'),
        ];

        if ($request->ajax()) {
            $table = view('admin.pages.vendors.partials.staff-table', compact('staffMembers', 'shop'))->render();
            $pagination = $staffMembers->links('pagination::bootstrap-5')->render();

            return response()->json([
                'table' => $table,
                'pagination' => $pagination
            ]);
        }

        return view('admin.pages.vendors.staff', compact('shop', 'staffMembers', 'stats'));
    }

    public function staffBulkAction(Request $request, $shopId)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'staff_ids' => 'required|string',
        ]);

        $action = $request->action;
        $staffIds = json_decode($request->staff_ids);

        $count = 0;

        DB::beginTransaction();

        try {
            foreach ($staffIds as $id) {
                $staff = Vendor::find($id);
                if (!$staff) continue;

                switch ($action) {
                    case 'activate':
                        $staff->update(['is_active' => true]);
                        $count++;
                        break;
                    case 'deactivate':
                        $staff->update(['is_active' => false]);
                        $count++;
                        break;
                    case 'delete':
                        if ($staff->avatar && Storage::disk('public')->exists($staff->avatar)) {
                            Storage::disk('public')->delete($staff->avatar);
                        }
                        $staff->delete();
                        $count++;
                        break;
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "{$count} staff member(s) {$action}d successfully!"
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function activateStaff($id)
    {
        $staff = Vendor::findOrFail($id);

        $staff->update(['is_active' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Staff activated successfully!'
        ]);
    }

    public function deactivateStaff($id)
    {
        $staff = Vendor::findOrFail($id);

        $staff->update(['is_active' => false]);

        return response()->json([
            'success' => true,
            'message' => 'Staff deactivated successfully!'
        ]);
    }

    public function deleteStaff($id)
    {
        $staff = Vendor::findOrFail($id);

        if ($staff->avatar && Storage::disk('public')->exists($staff->avatar)) {
            Storage::disk('public')->delete($staff->avatar);
        }

        $staff->delete();

        return response()->json([
            'success' => true,
            'message' => 'Staff deleted successfully!'
        ]);
    }

    public function approve(Request $request, $id)
    {
        $shop = Shop::findOrFail($id);

        $request->validate([
            'verification_notes' => 'required|string|max:500'
        ]);

        DB::beginTransaction();

        try {
            // Update shop verification status
            $shop->update([
                'account_status' => 'verified',
                'verified_at' => now(),
                'verified_by' => auth('admin')->id(),
                'verification_notes' => $request->verification_notes,
            ]);

            if ($shop->owner) {
                // Update owner's role in vendors table
                $shop->owner->update([
                    'vendor_role' => 'store_owner',
                    'is_owner' => true,
                ]);

                // Remove 'vendor' role using Spatie permission
                $shop->owner->removeRole('vendor');

                // Assign 'store_owner' role using Spatie permission
                $shop->owner->assignRole('store_owner');
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Shop approved successfully! Vendor role changed to Store Owner.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function reject(Request $request, $id)
    {
        $shop = Shop::findOrFail($id);

        $request->validate([
            'verification_notes' => 'required|string|max:500'
        ]);

        DB::beginTransaction();

        try {
            $shop->update([
                'account_status' => 'rejected',
                'verified_at' => now(),
                'verified_by' => auth('admin')->id(),
                'verification_notes' => $request->verification_notes,
            ]);

            if ($shop->owner) {
                // Update owner's role in vendors table
                $shop->owner->update([
                    'vendor_role' => 'vendor',
                    'is_owner' => false,
                ]);

                // Remove 'store_owner' role using Spatie permission
                $shop->owner->removeRole('store_owner');

                // Assign 'vendor' role using Spatie permission
                $shop->owner->assignRole('vendor');
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Shop rejected successfully!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function changeType(Request $request, $id)
    {
        $shop = Shop::findOrFail($id);

        $request->validate([
            'vendor_type' => 'required|in:own_store,third_party'
        ]);

        DB::beginTransaction();

        try {
            $shop->update([
                'vendor_type' => $request->vendor_type,
                'commission_rate' => 0
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Vendor type changed to ' . ucfirst(str_replace('_', ' ', $request->vendor_type)) . ' successfully!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function suspend(Request $request, $id)
    {
        $shop = Shop::findOrFail($id);

        $request->validate([
            'reason' => 'required|string|max:500'
        ]);

        DB::beginTransaction();

        try {
            if ($shop->owner) {
                $shop->update([
                    'account_status' => 'suspended',
                    'suspension_reason' => $request->reason,
                    'suspended_at' => now(),
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Shop suspended successfully!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $shop = Shop::findOrFail($id);

        DB::beginTransaction();

        try {
            // Delete shop logo and banner if exist
            if ($shop->shop_logo && Storage::disk('public')->exists($shop->shop_logo)) {
                Storage::disk('public')->delete($shop->shop_logo);
            }
            if ($shop->shop_banner && Storage::disk('public')->exists($shop->shop_banner)) {
                Storage::disk('public')->delete($shop->shop_banner);
            }

            $shop->delete();

            DB::commit();

            return redirect()->route('admin.vendors.index')
                ->with('success', 'Shop deleted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
