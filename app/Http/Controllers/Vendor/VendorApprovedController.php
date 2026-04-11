<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\State;
use App\Models\Vendor\BusinessType;
use App\Models\Category;
use App\Models\Vendor\Shop;
use App\Models\Vendor\ShopTaxInfo;
use App\Models\Vendor\ShopBankInfo;
use App\Models\Vendor\ShopDocument;
use App\Models\Vendor;
use App\Services\ImageCompressionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class VendorApprovedController extends Controller implements HasMiddleware
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
            new Middleware('permission:complete_profile|vendor', only: ['showCompleteForm', 'saveTab']),
        ];
    }



    public function showCompleteForm()
    {
        $vendor = Auth::guard('vendor')->user();

        $vendor->load([
            'shop.taxInfo',
            'shop.bankInfo',
            'shop.documents',
            'shop.categories'
        ]);

        $shop = $vendor->shop;

        // Get all data for dropdowns
        $countries = Country::orderBy('name')->get();
        $states = State::where('country_id', $shop->shop_country)->orderBy('name')->get();
        $businessTypes = BusinessType::where('is_active', true)->orderBy('name')->get();
        $mainCategories = Category::with('children')->whereNull('parent_id')->where('status', true)->orderBy('name')->get();

        // Get selected category IDs
        $selectedCategories = $shop->categories->pluck('id')->toArray();

        // Calculate completion percentage
        $completionPercentage = $this->calculateCompletion($shop);
        $shop->update(['profile_completed' => $completionPercentage]);

        return view('marketplace.pages.profile.complete', compact('shop', 'vendor', 'completionPercentage', 'countries', 'states', 'businessTypes', 'mainCategories', 'selectedCategories'));
    }

    public function saveTab(Request $request)
    {
        $vendor = Auth::guard('vendor')->user();
        $shop = $vendor->shop;
        $tab = $request->tab;

        $rules = $this->getValidationRules($tab);
        $validated = $request->validate($rules);

        DB::beginTransaction();

        try {
            switch ($tab) {
                case 'personal':
                    $this->savePersonalTab($vendor, $request);
                    break;
                case 'shop':
                    $this->saveShopTab($shop, $request);
                    break;
                case 'tax':
                    $this->saveTaxTab($shop, $request);
                    break;
                case 'bank':
                    $this->saveBankTab($shop, $request);
                    break;
                case 'documents':
                    $this->saveDocumentsTab($shop, $request);
                    break;
            }

            // Calculate new completion percentage
            $shop->load(['taxInfo', 'bankInfo', 'documents', 'categories']);
            $completionPercentage = $this->calculateCompletion($shop);

            $shop->update(['profile_completed' => $completionPercentage]);
            if ($completionPercentage >= 70.0) {
                $shop->update(['ready_for_approve' => true]);
            }


            DB::commit();

            $nextTab = $this->getNextTab($tab);
            $isComplete = $completionPercentage >= 70; // 70% for mandatory fields

            return response()->json([
                'success' => true,
                'message' => ucfirst($tab) . ' information saved successfully!',
                'completion_percentage' => $completionPercentage,
                'next_tab' => $nextTab,
                'is_complete' => $isComplete,
                'redirect_url' => $isComplete ? route('vendor.dashboard') : null
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    protected function getValidationRules($tab)
    {
        $rules = [
            'personal' => [
                'name' => 'required|string|max:255',
                'email' => 'required|email',
                'phone' => 'nullable|string|max:20',
                'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ],
            'shop' => [
                'shop_name' => 'required|string|max:255',
                'shop_slug' => 'nullable|string',
                'shop_description' => 'nullable|string',
                'shop_logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'shop_banner' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'shop_email' => 'required|email',
                'country_id' => 'required|exists:countries,id',
                'shop_phone_code' => 'nullable|string',
                'shop_phone' => 'required|string|max:20',
                'shop_whatsapp' => 'nullable|string|max:20',
                'shop_website' => 'nullable|url',
                'business_type' => 'required|string',
                'shop_address' => 'required|string',
                'shop_city' => 'required|string',
                'state_id' => 'required|string',
                'country_id' => 'required|string',
                'shop_postal_code' => 'required|string',
                'accepts_cod' => 'nullable|boolean',
            ],
            'tax' => [
                'gst_number' => 'nullable|string',
                'gst_type' => 'nullable|string',
                'gst_registration_date' => 'nullable|date',
                'pan_number' => 'required|string',
                'pan_holder_name' => 'required|string',
                'vat_number' => 'nullable|string',
                'ein_number' => 'nullable|string',
                'tax_id' => 'nullable|string',
                'business_registration_number' => 'nullable|string',
                'business_license_number' => 'nullable|string',
                'business_registration_date' => 'required|date',
            ],
            'bank' => [
                'account_holder_name' => 'required|string',
                'account_number' => 'required|string',
                'bank_name' => 'required|string',
                'bank_branch' => 'required|string',
                'ifsc_code' => 'required|string',
                'swift_code' => 'nullable|string',
                'routing_number' => 'nullable|string',
                'iban_number' => 'nullable|string',
                'bank_address' => 'required|string',
                'upi_id' => 'nullable|string',
                'paypal_email' => 'nullable|email',
                'stripe_account_id' => 'nullable|string',
                'razorpay_account_id' => 'nullable|string',
            ],
            'documents' => [
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
            ],
            'categories' => [
                'categories' => 'required|array|min:1',
                'categories.*' => 'exists:categories,id',
            ],
        ];

        return $rules[$tab] ?? [];
    }

    protected function savePersonalTab($vendor, $request)
    {
        $vendor->update([
            'name' => $request->name,
        ]);
    }

    protected function saveShopTab($shop, $request)
    {
        // Generate slug if empty
        $slug = $request->shop_slug ?: \Illuminate\Support\Str::slug($request->shop_name);

        $shop->update([
            'shop_name' => $request->shop_name,
            'shop_slug' => $slug,
            'shop_description' => $request->shop_description,
            'shop_email' => $request->shop_email,
            'shop_phone' => $request->shop_phone,
            'shop_phone_code' => $request->shop_phone_code,
            'shop_whatsapp' => $request->shop_whatsapp,
            'shop_website' => $request->shop_website,
            'business_type' => $request->business_type,
            'shop_address' => $request->shop_address,
            'shop_city' => $request->shop_city,
            'shop_state' => $request->state_id,
            'shop_country' => $request->country_id,
            'shop_postal_code' => $request->shop_postal_code,
            'accepts_cod' => $request->accepts_cod ? true : false,
        ]);

        if ($request->hasFile('shop_logo')) {
            if ($shop->shop_logo && Storage::disk('public')->exists($shop->shop_logo)) {
                Storage::disk('public')->delete($shop->shop_logo);
            }
            $compressed = $this->imageCompressor->compress($request->file('shop_logo'), 'shop/logos', 300, 85);
            if ($compressed['success']) {
                $shop->update(['shop_logo' => 'shop/logos/' . $compressed['filename']]);
            }
        }

        if ($request->hasFile('shop_banner')) {
            if ($shop->shop_banner && Storage::disk('public')->exists($shop->shop_banner)) {
                Storage::disk('public')->delete($shop->shop_banner);
            }
            $compressed = $this->imageCompressor->compress($request->file('shop_banner'), 'shop/banners', 1200, 80);
            if ($compressed['success']) {
                $shop->update(['shop_banner' => 'shop/banners/' . $compressed['filename']]);
            }
        }

        $selectedCategories = $request->categories;

        if (empty($selectedCategories)) {
            throw new \Exception('Please select at least one category');
        }

        $shop->categories()->sync($selectedCategories);
    }

    protected function saveTaxTab($shop, $request)
    {
        ShopTaxInfo::updateOrCreate(
            ['shop_id' => $shop->id],
            [
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
            ]
        );
    }

    protected function saveBankTab($shop, $request)
    {
        ShopBankInfo::updateOrCreate(
            ['shop_id' => $shop->id],
            [
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
            ]
        );
    }

    protected function saveDocumentsTab($shop, $request)
    {
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $type => $file) {
                $documentType = $this->mapDocumentType($type);

                // Check if document already exists and delete old
                $oldDoc = ShopDocument::where('shop_id', $shop->id)
                    ->where('document_type', $documentType)
                    ->first();

                if ($oldDoc && $oldDoc->document_path && Storage::disk('public')->exists($oldDoc->document_path)) {
                    Storage::disk('public')->delete($oldDoc->document_path);
                }

                $isImage = in_array($file->getClientOriginalExtension(), ['jpg', 'jpeg', 'png']);
                $path = '';

                if ($isImage) {
                    $compressed = $this->imageCompressor->compress($file, 'shop/documents', 800, 85);
                    if ($compressed['success']) {
                        $path = 'shop/documents/' . $compressed['filename'];
                    }
                } else {
                    $originalName = time() . '_' . $file->getClientOriginalName();
                    $path = $file->storeAs('shop/documents', $originalName, 'public');
                }

                ShopDocument::updateOrCreate(
                    [
                        'shop_id' => $shop->id,
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
    }

    protected function getNextTab($currentTab)
    {
        $tabs = ['personal', 'shop', 'tax', 'bank', 'documents', 'categories'];
        $currentIndex = array_search($currentTab, $tabs);

        if ($currentIndex !== false && isset($tabs[$currentIndex + 1])) {
            return $tabs[$currentIndex + 1];
        }

        return null;
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

    /**
     * Calculate completion percentage based on mandatory fields only (70% total)
     * 70% of 100 = 70 total points from mandatory fields
     * Each mandatory field is worth 1 point
     */
    protected function calculateCompletion($shop)
    {
        $vendor = Auth::guard('vendor')->user();
        $totalMandatoryPoints = 28; // Total mandatory fields
        $earnedPoints = 0;

        // Personal Info (2 points)
        if ($vendor->name) $earnedPoints++;
        if ($vendor->email) $earnedPoints++;

        // Shop Info (14 points)
        if ($shop->shop_name) $earnedPoints++;
        if ($shop->shop_email) $earnedPoints++;
        if ($shop->shop_phone) $earnedPoints++;
        if ($shop->shop_address) $earnedPoints++;
        if ($shop->shop_city) $earnedPoints++;
        if ($shop->shop_state) $earnedPoints++;
        if ($shop->shop_country) $earnedPoints++;
        if ($shop->shop_postal_code) $earnedPoints++;
        if ($shop->shop_logo) $earnedPoints++;
        if ($shop->business_type) $earnedPoints++;
        if ($shop->country_id) $earnedPoints++;
        if ($shop->shop_phone_code) $earnedPoints++;

        // Tax Info (3 points)
        if ($shop->taxInfo) {
            if ($shop->taxInfo->pan_number) $earnedPoints++;
            if ($shop->taxInfo->pan_holder_name) $earnedPoints++;
            if ($shop->taxInfo->business_registration_date) $earnedPoints++;
        }

        // Bank Info (7 points)
        if ($shop->bankInfo) {
            if ($shop->bankInfo->account_holder_name) $earnedPoints++;
            if ($shop->bankInfo->account_number) $earnedPoints++;
            if ($shop->bankInfo->bank_name) $earnedPoints++;
            if ($shop->bankInfo->bank_branch) $earnedPoints++;
            if ($shop->bankInfo->ifsc_code) $earnedPoints++;
            if ($shop->bankInfo->bank_address) $earnedPoints++;
        }

        // Documents (5 points)
        if ($shop->documents) {
            $uploadedDocs = $shop->documents->pluck('document_type')->toArray();
            $mandatoryDocs = ['pan_card', 'cancelled_cheque', 'bank_statement', 'address_proof', 'identity_proof'];
            foreach ($mandatoryDocs as $doc) {
                if (in_array($doc, $uploadedDocs)) {
                    $earnedPoints++;
                }
            }
        }

        // Categories (1 point)
        if ($shop->categories && $shop->categories->count() > 0) {
            $earnedPoints++;
        }

        // Calculate percentage (earnedPoints / totalMandatoryPoints * 70)
        $percentage = round(($earnedPoints / $totalMandatoryPoints) * 70);

        // Cap at 100
        $percentage = min(100, $percentage);

        // Update profile completed status
        $shop->update(['profile_completed' => $percentage]);

        return $percentage;
    }
}
