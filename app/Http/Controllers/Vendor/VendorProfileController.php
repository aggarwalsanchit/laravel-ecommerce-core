<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\VendorTaxInfo;
use App\Models\VendorBankInfo;
use App\Models\VendorDocument;
use App\Services\ImageCompressionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Middleware\PermissionMiddleware;

class VendorProfileController extends Controller
{
    protected $imageCompressor;

    public function __construct(ImageCompressionService $imageCompressor)
    {
        $this->imageCompressor = $imageCompressor;
    }

    public static function middleware(): array
    {
        return [
            'auth:vendor',
            new PermissionMiddleware('view_profile', only: ['showProfile']),
            new PermissionMiddleware('update_profile', only: ['editProfile', 'update']),
            new PermissionMiddleware('change_password', only: ['changePassword', 'updatePassword']),
            new PermissionMiddleware('upload_avatar', only: ['uploadAvatar']),
            new PermissionMiddleware('complete_profile', only: ['showCompleteForm', 'updateProfile']),
        ];
    }

    public function showCompleteForm()
    {
        $vendor = Auth::guard('vendor')->user();
        $vendor->load(['taxInfo', 'bankInfo', 'documents']);
        $completionPercentage = $this->calculateCompletion($vendor);

        return view('marketplace.pages.profile.complete', compact('vendor', 'completionPercentage'));
    }

    public function updateProfile(Request $request)
    {
        $vendor = Auth::guard('vendor')->user();

        $validated = $request->validate([
            // ========== VENDORS TABLE ==========
            // Personal Information
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:vendors,email,' . $vendor->id,
            'phone' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',

            // Basic Business Information
            'shop_name' => 'required|string|max:255',
            'shop_description' => 'nullable|string',
            'shop_logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'shop_banner' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',

            // Contact Information
            'shop_email' => 'required|email',
            'shop_phone' => 'required|string|max:20',
            'shop_whatsapp' => 'nullable|string|max:20',
            'shop_website' => 'nullable|url',
            'shop_address' => 'required|string',
            'shop_city' => 'required|string',
            'shop_state' => 'required|string',
            'shop_country' => 'required|string',
            'shop_postal_code' => 'required|string',

            // Vendor Type & Business Type
            'vendor_type' => 'required|in:own_store,third_party',
            'business_type' => 'nullable|in:sole_proprietorship,partnership,llc,private_limited,public_limited,trust,other',

            // Settings
            'accepts_cod' => 'boolean',
            'commission_rate' => 'nullable|integer|min:0|max:100',

            // ========== VENDOR TAX INFOS TABLE ==========
            // GST Information
            'gst_number' => 'nullable|string',
            'gst_type' => 'nullable|in:regular,composition,casual,unregistered',
            'gst_registration_date' => 'nullable|date',
            'gst_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',

            // PAN Information
            'pan_number' => 'nullable|string',
            'pan_card_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'pan_holder_name' => 'nullable|string',

            // International Tax
            'vat_number' => 'nullable|string',
            'ein_number' => 'nullable|string',
            'tax_id' => 'nullable|string',

            // Business Registration
            'business_registration_number' => 'nullable|string',
            'business_license_number' => 'nullable|string',
            'business_registration_date' => 'nullable|date',
            'business_registration_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',

            // ========== VENDOR BANK INFOS TABLE ==========
            // Bank Account Details
            'account_holder_name' => 'required|string',
            'account_number' => 'required|string',
            'bank_name' => 'required|string',
            'bank_branch' => 'required|string',
            'ifsc_code' => 'nullable|string',
            'swift_code' => 'nullable|string',
            'routing_number' => 'nullable|string',
            'iban_number' => 'nullable|string',
            'bank_address' => 'nullable|string',

            // Digital Payments
            'upi_id' => 'nullable|string',
            'paypal_email' => 'nullable|email',
            'stripe_account_id' => 'nullable|string',
            'razorpay_account_id' => 'nullable|string',

            // Bank Documents
            'cancelled_cheque' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'bank_statement' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',

            // ========== VENDOR DOCUMENTS TABLE ==========
            'documents.pan_card' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'documents.gst_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'documents.cancelled_cheque' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'documents.bank_statement' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'documents.business_registration' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'documents.business_license' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'documents.address_proof' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'documents.identity_proof' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'documents.trade_license' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'documents.other' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        DB::beginTransaction();

        try {
            // ========== 1. UPDATE VENDORS TABLE ==========
            $vendor->update([
                // Personal Information
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,

                // Basic Business Information
                'shop_name' => $request->shop_name,
                'shop_description' => $request->shop_description,

                // Contact Information
                'shop_email' => $request->shop_email,
                'shop_phone' => $request->shop_phone,
                'shop_whatsapp' => $request->shop_whatsapp,
                'shop_website' => $request->shop_website,
                'shop_address' => $request->shop_address,
                'shop_city' => $request->shop_city,
                'shop_state' => $request->shop_state,
                'shop_country' => $request->shop_country,
                'shop_postal_code' => $request->shop_postal_code,

                // Vendor Type & Business Type
                'vendor_type' => $request->vendor_type,
                'business_type' => $request->business_type,

                // Settings
                'accepts_cod' => $request->accepts_cod ?? true,
                'commission_rate' => $request->commission_rate ?? 10,
            ]);

            // Upload Avatar
            if ($request->hasFile('avatar')) {
                if ($vendor->avatar && Storage::disk('public')->exists($vendor->avatar)) {
                    Storage::disk('public')->delete($vendor->avatar);
                }
                $compressed = $this->imageCompressor->compress($request->file('avatar'), 'vendor/avatars', 200, 85);
                if ($compressed['success']) {
                    $vendor->update(['avatar' => 'vendor/avatars/' . $compressed['filename']]);
                }
            }

            // Upload Shop Logo
            if ($request->hasFile('shop_logo')) {
                if ($vendor->shop_logo && Storage::disk('public')->exists($vendor->shop_logo)) {
                    Storage::disk('public')->delete($vendor->shop_logo);
                }
                $compressed = $this->imageCompressor->compress($request->file('shop_logo'), 'vendor/logos', 300, 85);
                if ($compressed['success']) {
                    $vendor->update(['shop_logo' => 'vendor/logos/' . $compressed['filename']]);
                }
            }

            // Upload Shop Banner
            if ($request->hasFile('shop_banner')) {
                if ($vendor->shop_banner && Storage::disk('public')->exists($vendor->shop_banner)) {
                    Storage::disk('public')->delete($vendor->shop_banner);
                }
                $compressed = $this->imageCompressor->compress($request->file('shop_banner'), 'vendor/banners', 1200, 80);
                if ($compressed['success']) {
                    $vendor->update(['shop_banner' => 'vendor/banners/' . $compressed['filename']]);
                }
            }

            // ========== 2. UPDATE OR CREATE TAX INFO ==========
            $taxData = [
                'gst_number' => $request->gst_number,
                'gst_type' => $request->gst_type,
                'gst_registration_date' => $request->gst_registration_date,
                'pan_number' => $request->pan_number,
                'pan_holder_name' => $request->pan_holder_name,
                'vat_number' => $request->vat_number,
                'ein_number' => $request->ein_number,
                'tax_id' => $request->tax_id,
                'business_registration_number' => $request->business_registration_number,
                'business_license_number' => $request->business_license_number,
                'business_registration_date' => $request->business_registration_date,
            ];

            // Upload GST Certificate
            if ($request->hasFile('gst_certificate')) {
                $compressed = $this->imageCompressor->compress($request->file('gst_certificate'), 'vendor/tax', 800, 85);
                if ($compressed['success']) {
                    $taxData['gst_certificate'] = 'vendor/tax/' . $compressed['filename'];
                }
            }

            // Upload PAN Card Document
            if ($request->hasFile('pan_card_document')) {
                $compressed = $this->imageCompressor->compress($request->file('pan_card_document'), 'vendor/tax', 800, 85);
                if ($compressed['success']) {
                    $taxData['pan_card_document'] = 'vendor/tax/' . $compressed['filename'];
                }
            }

            // Upload Business Registration Certificate
            if ($request->hasFile('business_registration_certificate')) {
                $compressed = $this->imageCompressor->compress($request->file('business_registration_certificate'), 'vendor/tax', 800, 85);
                if ($compressed['success']) {
                    $taxData['business_registration_certificate'] = 'vendor/tax/' . $compressed['filename'];
                }
            }

            VendorTaxInfo::updateOrCreate(
                ['vendor_id' => $vendor->id],
                $taxData
            );

            // ========== 3. UPDATE OR CREATE BANK INFO ==========
            $bankData = [
                'account_holder_name' => $request->account_holder_name,
                'account_number' => $request->account_number,
                'bank_name' => $request->bank_name,
                'bank_branch' => $request->bank_branch,
                'ifsc_code' => $request->ifsc_code,
                'swift_code' => $request->swift_code,
                'routing_number' => $request->routing_number,
                'iban_number' => $request->iban_number,
                'bank_address' => $request->bank_address,
                'upi_id' => $request->upi_id,
                'paypal_email' => $request->paypal_email,
                'stripe_account_id' => $request->stripe_account_id,
                'razorpay_account_id' => $request->razorpay_account_id,
            ];

            // Upload Cancelled Cheque
            if ($request->hasFile('cancelled_cheque')) {
                $compressed = $this->imageCompressor->compress($request->file('cancelled_cheque'), 'vendor/bank', 800, 85);
                if ($compressed['success']) {
                    $bankData['cancelled_cheque'] = 'vendor/bank/' . $compressed['filename'];
                }
            }

            // Upload Bank Statement
            if ($request->hasFile('bank_statement')) {
                $compressed = $this->imageCompressor->compress($request->file('bank_statement'), 'vendor/bank', 800, 85);
                if ($compressed['success']) {
                    $bankData['bank_statement'] = 'vendor/bank/' . $compressed['filename'];
                }
            }

            VendorBankInfo::updateOrCreate(
                ['vendor_id' => $vendor->id],
                $bankData
            );

            // ========== 4. UPDATE OR CREATE DOCUMENTS ==========
            if ($request->hasFile('documents')) {
                foreach ($request->file('documents') as $type => $file) {
                    $documentType = $this->mapDocumentType($type);

                    $oldDoc = VendorDocument::where('vendor_id', $vendor->id)
                        ->where('document_type', $documentType)
                        ->first();

                    if ($oldDoc && $oldDoc->document_path && Storage::disk('public')->exists($oldDoc->document_path)) {
                        Storage::disk('public')->delete($oldDoc->document_path);
                    }

                    $isImage = in_array($file->getClientOriginalExtension(), ['jpg', 'jpeg', 'png']);
                    $path = '';

                    if ($isImage) {
                        $compressed = $this->imageCompressor->compress($file, 'vendor/documents', 800, 85);
                        if ($compressed['success']) {
                            $path = 'vendor/documents/' . $compressed['filename'];
                        }
                    } else {
                        $originalName = time() . '_' . $file->getClientOriginalName();
                        $path = $file->storeAs('vendor/documents', $originalName, 'public');
                    }

                    VendorDocument::updateOrCreate(
                        [
                            'vendor_id' => $vendor->id,
                            'document_type' => $documentType,
                        ],
                        [
                            'document_name' => str_replace('_', ' ', ucfirst($type)),
                            'document_path' => $path,
                            'verification_status' => 'pending',
                        ]
                    );
                }
            }

            // Calculate completion percentage
            $vendor->load(['taxInfo', 'bankInfo', 'documents']);
            $completionPercentage = $this->calculateCompletion($vendor);
            $vendor->update(['profile_completed' => $completionPercentage]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully!',
                'completion_percentage' => $completionPercentage
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }

    protected function mapDocumentType($type)
    {
        $mapping = [
            'pan_card' => 'pan_card',
            'gst_certificate' => 'gst_certificate',
            'cancelled_cheque' => 'cancelled_cheque',
            'bank_statement' => 'bank_statement',
            'business_registration' => 'business_registration',
            'business_license' => 'business_license',
            'address_proof' => 'address_proof',
            'identity_proof' => 'identity_proof',
            'trade_license' => 'trade_license',
            'other' => 'other',
        ];

        return $mapping[$type] ?? 'other';
    }

    protected function calculateCompletion($vendor)
    {
        $completed = 0;
        $totalFields = 35;

        // Vendors table (15 fields)
        if ($vendor->name) $completed++;
        if ($vendor->email) $completed++;
        if ($vendor->phone) $completed++;
        if ($vendor->shop_name) $completed++;
        if ($vendor->shop_description) $completed++;
        if ($vendor->shop_email) $completed++;
        if ($vendor->shop_phone) $completed++;
        if ($vendor->shop_address) $completed++;
        if ($vendor->shop_city) $completed++;
        if ($vendor->shop_state) $completed++;
        if ($vendor->shop_country) $completed++;
        if ($vendor->shop_postal_code) $completed++;
        if ($vendor->shop_logo) $completed++;
        if ($vendor->avatar) $completed++;
        if ($vendor->shop_banner) $completed++;

        // Tax info (8 fields)
        if ($vendor->taxInfo) {
            if ($vendor->taxInfo->gst_number) $completed++;
            if ($vendor->taxInfo->pan_number) $completed++;
            if ($vendor->taxInfo->vat_number) $completed++;
            if ($vendor->taxInfo->ein_number) $completed++;
            if ($vendor->taxInfo->tax_id) $completed++;
            if ($vendor->taxInfo->business_registration_number) $completed++;
            if ($vendor->taxInfo->business_license_number) $completed++;
            if ($vendor->taxInfo->gst_certificate) $completed++;
        }

        // Bank info (12 fields)
        if ($vendor->bankInfo) {
            if ($vendor->bankInfo->account_holder_name) $completed++;
            if ($vendor->bankInfo->account_number) $completed++;
            if ($vendor->bankInfo->bank_name) $completed++;
            if ($vendor->bankInfo->bank_branch) $completed++;
            if ($vendor->bankInfo->ifsc_code) $completed++;
            if ($vendor->bankInfo->swift_code) $completed++;
            if ($vendor->bankInfo->routing_number) $completed++;
            if ($vendor->bankInfo->iban_number) $completed++;
            if ($vendor->bankInfo->upi_id) $completed++;
            if ($vendor->bankInfo->paypal_email) $completed++;
            if ($vendor->bankInfo->cancelled_cheque) $completed++;
            if ($vendor->bankInfo->bank_statement) $completed++;
        }

        return round(($completed / $totalFields) * 100);
    }
}
