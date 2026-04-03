<?php
// app/Http/Controllers/Admin/VendorManagementController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\VendorTaxInfo;
use App\Models\VendorBankInfo;
use App\Models\VendorDocument;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Spatie\Permission\Models\Role;

class VendorManagementController extends Controller
{
    public function index()
    {
        $vendors = Vendor::with(['user', 'taxInfo', 'bankInfo'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $stats = [
            'total' => Vendor::count(),
            'pending' => Vendor::where('verification_status', 'pending')->count(),
            'verified' => Vendor::where('verification_status', 'verified')->count(),
            'rejected' => Vendor::where('verification_status', 'rejected')->count(),
            'active' => Vendor::where('account_status', 'active')->count(),
            'own_store' => Vendor::where('vendor_type', 'own_store')->count(),
            'third_party' => Vendor::where('vendor_type', 'third_party')->count(),
        ];

        return view('admin.vendors.index', compact('vendors', 'stats'));
    }

    public function show($id)
    {
        $vendor = Vendor::with([
            'user',
            'taxInfo',
            'bankInfo',
            'documents',
            'categories',
            'products'
        ])->findOrFail($id);

        return view('admin.vendors.show', compact('vendor'));
    }

    public function approve(Request $request, $id)
    {
        $vendor = Vendor::findOrFail($id);

        DB::beginTransaction();
        try {
            // Update vendor
            $vendor->update([
                'verification_status' => 'verified',
                'account_status' => 'active',
                'verified_at' => now(),
                'verified_by' => auth()->id(),
                'verification_notes' => $request->notes
            ]);

            // Update tax info
            if ($vendor->taxInfo) {
                $vendor->taxInfo->update([
                    'verification_status' => 'verified',
                    'verified_at' => now(),
                    'verified_by' => auth()->id()
                ]);
            }

            // Update bank info
            if ($vendor->bankInfo) {
                $vendor->bankInfo->update([
                    'verification_status' => 'verified',
                    'verified_at' => now(),
                    'verified_by' => auth()->id()
                ]);
            }

            // Update all documents
            foreach ($vendor->documents as $doc) {
                $doc->update([
                    'verification_status' => 'verified',
                    'verified_at' => now(),
                    'verified_by' => auth()->id()
                ]);
            }

            // Assign vendor role to user
            $vendorRole = Role::firstOrCreate(['name' => 'Vendor', 'guard_name' => 'web']);
            $vendor->user->assignRole($vendorRole);

            // Remove pending role
            $pendingRole = Role::where('name', 'Pending Vendor')->first();
            if ($pendingRole) {
                $vendor->user->removeRole($pendingRole);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Vendor approved successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to approve vendor: ' . $e->getMessage()
            ], 500);
        }
    }

    public function reject(Request $request, $id)
    {
        $vendor = Vendor::findOrFail($id);

        $vendor->update([
            'verification_status' => 'rejected',
            'account_status' => 'suspended',
            'verification_notes' => $request->reason
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Vendor application rejected'
        ]);
    }

    public function toggleStatus($id)
    {
        $vendor = Vendor::findOrFail($id);
        $newStatus = $vendor->account_status === 'active' ? 'suspended' : 'active';

        $vendor->update([
            'account_status' => $newStatus,
            'suspended_at' => $newStatus === 'suspended' ? now() : null
        ]);

        return response()->json([
            'success' => true,
            'message' => "Vendor {$newStatus} successfully"
        ]);
    }

    public function createOwnStore()
    {
        // Create your own store if not exists
        $adminUser = User::where('email', 'admin@example.com')->first();

        if (!$adminUser) {
            return response()->json(['error' => 'Admin user not found'], 404);
        }

        $ownStore = Vendor::updateOrCreate(
            ['vendor_type' => 'own_store'],
            [
                'user_id' => $adminUser->id,
                'shop_name' => 'Admin Store',
                'shop_slug' => 'admin-store',
                'shop_email' => $adminUser->email,
                'shop_phone' => $adminUser->phone ?? '0000000000',
                'shop_address' => 'Admin Address',
                'shop_city' => 'Admin City',
                'shop_state' => 'Admin State',
                'shop_country' => 'Admin Country',
                'shop_postal_code' => '000000',
                'vendor_type' => 'own_store',
                'account_status' => 'active',
                'verification_status' => 'verified',
                'verified_at' => now(),
                'commission_rate' => 0,
            ]
        );

        // Assign vendor role
        $vendorRole = Role::firstOrCreate(['name' => 'Vendor', 'guard_name' => 'web']);
        $adminUser->assignRole($vendorRole);

        return redirect()->route('admin.vendors.index')->with('success', 'Own store created');
    }
}
