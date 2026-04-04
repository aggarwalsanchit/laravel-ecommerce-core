<?php
// app/Http/Controllers/Admin/Vendor/VendorRegistrationController.php

namespace App\Http\Controllers\Admin\Vendor;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorTaxInfo;
use App\Models\VendorBankInfo;
use App\Models\VendorDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class VendorRegistrationController extends Controller
{
    public function showRegistrationForm()
    {
        $categories = Category::where('status', true)->get();
        return view('vendor.register', compact('categories'));
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            // Account Setup
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone' => 'required|string|max:20',
            'password' => 'required|min:8|confirmed',
            'whatsapp' => 'nullable|string|max:20',

            // Business Details
            'shop_name' => 'required|string|max:255|unique:vendors,shop_name',
            'shop_email' => 'required|email|unique:vendors,shop_email',
            'shop_phone' => 'required|string|max:20',
            'shop_description' => 'required|string',
            'shop_address' => 'required|string',
            'shop_city' => 'required|string',
            'shop_state' => 'required|string',
            'shop_country' => 'required|string',
            'shop_postal_code' => 'required|string',
            'business_type' => 'required|string',
            'business_categories' => 'required|array',

            // Tax Information
            'pan_number' => 'required|string|unique:vendor_tax_infos,pan_number',
            'gst_number' => 'nullable|string|unique:vendor_tax_infos,gst_number',

            // Bank Information
            'account_holder_name' => 'required|string',
            'account_number' => 'required|string',
            'confirm_account_number' => 'required|same:account_number',
            'bank_name' => 'required|string',
            'bank_branch' => 'required|string',
            'ifsc_code' => 'required|string',

            // Documents
            'pan_card_document' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'gst_certificate' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'cancelled_cheque' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'business_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        DB::beginTransaction();

        try {
            // 1. Create User
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'phone' => $validated['phone'],
                'is_active' => true,
            ]);

            // Assign pending vendor role
            $pendingRole = Role::firstOrCreate(['name' => 'Pending Vendor', 'guard_name' => 'web']);
            $user->assignRole($pendingRole);

            // 2. Create Vendor
            $vendor = Vendor::create([
                'user_id' => $user->id,
                'shop_name' => $validated['shop_name'],
                'shop_slug' => Str::slug($validated['shop_name']),
                'shop_description' => $validated['shop_description'],
                'shop_email' => $validated['shop_email'],
                'shop_phone' => $validated['shop_phone'],
                'shop_whatsapp' => $validated['whatsapp'] ?? null,
                'shop_address' => $validated['shop_address'],
                'shop_city' => $validated['shop_city'],
                'shop_state' => $validated['shop_state'],
                'shop_country' => $validated['shop_country'],
                'shop_postal_code' => $validated['shop_postal_code'],
                'business_type' => $validated['business_type'],
                'vendor_type' => 'third_party',
                'account_status' => 'pending',
                'verification_status' => 'pending',
                'commission_rate' => 10, // Default 10% commission
            ]);

            // 3. Attach Categories
            $vendor->categories()->sync($validated['business_categories']);

            // 4. Create Tax Info
            $taxInfo = VendorTaxInfo::create([
                'vendor_id' => $vendor->id,
                'pan_number' => $validated['pan_number'],
                'gst_number' => $validated['gst_number'] ?? null,
                'verification_status' => 'pending',
            ]);

            // 5. Create Bank Info
            $bankInfo = VendorBankInfo::create([
                'vendor_id' => $vendor->id,
                'account_holder_name' => $validated['account_holder_name'],
                'account_number' => $validated['account_number'],
                'bank_name' => $validated['bank_name'],
                'bank_branch' => $validated['bank_branch'],
                'ifsc_code' => $validated['ifsc_code'],
                'verification_status' => 'pending',
            ]);

            // 6. Upload Documents
            $documents = [
                'pan_card' => $request->file('pan_card_document'),
                'gst_certificate' => $request->file('gst_certificate'),
                'cancelled_cheque' => $request->file('cancelled_cheque'),
                'business_proof' => $request->file('business_proof'),
            ];

            foreach ($documents as $type => $file) {
                if ($file) {
                    $path = $file->store("vendor_documents/{$vendor->id}", 'public');
                    VendorDocument::create([
                        'vendor_id' => $vendor->id,
                        'document_type' => $type,
                        'document_name' => $file->getClientOriginalName(),
                        'document_path' => $path,
                        'verification_status' => 'pending',
                    ]);
                }
            }

            DB::commit();

            // Send notification to admin (you can implement this later)
            // Notification::send(Admin::all(), new NewVendorRegistered($vendor));

            return response()->json([
                'success' => true,
                'message' => 'Your application has been submitted. Admin will review and approve shortly.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Registration failed: ' . $e->getMessage()
            ], 500);
        }
    }
}
