<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\VendorTaxInfo;
use App\Models\VendorBankInfo;
use App\Models\VendorDocument;
use App\Models\VendorCategory;
use App\Models\Category;
use App\Services\ImageCompressionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Middleware\PermissionMiddleware;

class VendorApprovedController extends Controller
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
            new PermissionMiddleware('complete_profile', only: ['showCompleteForm', 'saveTab']),
        ];
    }

    public function showCompleteForm()
    {
        $vendor = Auth::guard('vendor')->user();
        $vendor->load(['taxInfo', 'bankInfo', 'documents', 'categories']);
        
        // Get all categories for selection
        $categories = Category::where('status', true)->orderBy('name')->get();
        
        // Get selected category IDs (all levels)
        $selectedCategories = $vendor->categories->pluck('id')->toArray();
        
        // Calculate completion percentage
        $completionPercentage = $this->calculateCompletion($vendor);
        $vendor->update(['profile_completed' => $completionPercentage]);
        
        // If already approved, redirect to dashboard
        if ($vendor->account_status === 'active' && $completionPercentage >= 99) {
            return redirect()->route('vendor.dashboard')->with('info', 'Your profile is already complete!');
        }
        
        return view('marketplace.pages.profile.complete', compact('vendor', 'completionPercentage', 'categories', 'selectedCategories'));
    }

    public function saveTab(Request $request)
    {
        $vendor = Auth::guard('vendor')->user();
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
                    $this->saveShopTab($vendor, $request);
                    break;
                case 'tax':
                    $this->saveTaxTab($vendor, $request);
                    break;
                case 'bank':
                    $this->saveBankTab($vendor, $request);
                    break;
                case 'documents':
                    $this->saveDocumentsTab($vendor, $request);
                    break;
                case 'categories':
                    $this->saveCategoriesTab($vendor, $request);
                    break;
            }
            
            // Calculate new completion percentage
            $vendor->load(['taxInfo', 'bankInfo', 'documents', 'categories']);
            $completionPercentage = $this->calculateCompletion($vendor);
            $vendor->update(['profile_completed' => $completionPercentage]);
            
            DB::commit();
            
            // Determine next tab
            $nextTab = $this->getNextTab($tab);
            $isComplete = $completionPercentage >= 99;
            
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
                'email' => 'required|email|unique:vendors,email,' . Auth::guard('vendor')->id(),
                'phone' => 'nullable|string|max:20',
                'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ],
            'shop' => [
                'shop_name' => 'required|string|max:255',
                'shop_email' => 'required|email',
                'shop_phone' => 'required|string|max:20',
                'shop_whatsapp' => 'nullable|string|max:20',
                'shop_website' => 'nullable|url',
                'shop_description' => 'required|string',
                'shop_address' => 'required|string',
                'shop_city' => 'required|string',
                'shop_state' => 'required|string',
                'shop_country' => 'required|string',
                'shop_postal_code' => 'required|string',
                'vendor_type' => 'required|in:own_store,third_party',
                'business_type' => 'nullable|string',
                'shop_logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'shop_banner' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ],
            'tax' => [
                'gst_number' => 'required|string',
                'gst_type' => 'nullable|string',
                'gst_registration_date' => 'nullable|date',
                'pan_number' => 'required|string',
                'pan_holder_name' => 'required|string',
                'vat_number' => 'nullable|string',
                'ein_number' => 'nullable|string',
                'tax_id' => 'nullable|string',
                'business_registration_number' => 'nullable|string',
                'business_license_number' => 'nullable|string',
                'business_registration_date' => 'nullable|date',
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
                'bank_address' => 'nullable|string',
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
            'email' => $request->email,
            'phone' => $request->phone,
        ]);
        
        if ($request->hasFile('avatar')) {
            if ($vendor->avatar && Storage::disk('public')->exists($vendor->avatar)) {
                Storage::disk('public')->delete($vendor->avatar);
            }
            $compressed = $this->imageCompressor->compress($request->file('avatar'), 'vendor/avatars', 200, 85);
            if ($compressed['success']) {
                $vendor->update(['avatar' => 'vendor/avatars/' . $compressed['filename']]);
            }
        }
    }
    
    protected function saveShopTab($vendor, $request)
    {
        $vendor->update([
            'shop_name' => $request->shop_name,
            'shop_email' => $request->shop_email,
            'shop_phone' => $request->shop_phone,
            'shop_whatsapp' => $request->shop_whatsapp,
            'shop_website' => $request->shop_website,
            'shop_description' => $request->shop_description,
            'shop_address' => $request->shop_address,
            'shop_city' => $request->shop_city,
            'shop_state' => $request->shop_state,
            'shop_country' => $request->shop_country,
            'shop_postal_code' => $request->shop_postal_code,
            'vendor_type' => $request->vendor_type,
            'business_type' => $request->business_type,
        ]);
        
        if ($request->hasFile('shop_logo')) {
            if ($vendor->shop_logo && Storage::disk('public')->exists($vendor->shop_logo)) {
                Storage::disk('public')->delete($vendor->shop_logo);
            }
            $compressed = $this->imageCompressor->compress($request->file('shop_logo'), 'vendor/logos', 300, 85);
            if ($compressed['success']) {
                $vendor->update(['shop_logo' => 'vendor/logos/' . $compressed['filename']]);
            }
        }
        
        if ($request->hasFile('shop_banner')) {
            if ($vendor->shop_banner && Storage::disk('public')->exists($vendor->shop_banner)) {
                Storage::disk('public')->delete($vendor->shop_banner);
            }
            $compressed = $this->imageCompressor->compress($request->file('shop_banner'), 'vendor/banners', 1200, 80);
            if ($compressed['success']) {
                $vendor->update(['shop_banner' => 'vendor/banners/' . $compressed['filename']]);
            }
        }
    }
    
    protected function saveTaxTab($vendor, $request)
    {
        VendorTaxInfo::updateOrCreate(
            ['vendor_id' => $vendor->id],
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
    
    protected function saveBankTab($vendor, $request)
    {
        VendorBankInfo::updateOrCreate(
            ['vendor_id' => $vendor->id],
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
    
    protected function saveDocumentsTab($vendor, $request)
    {
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $type => $file) {
                $documentType = $this->mapDocumentType($type);
                
                // Check if document already exists and delete old
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
    }
    
    protected function saveCategoriesTab($vendor, $request)
    {
        // Get all selected category IDs from the request
        $selectedCategories = $request->categories;

        if (empty($selectedCategories)) {
            throw new \Exception('Please select at least one category');
        }
        
        // Optional: Get only leaf categories (categories that have no children)
        // This ensures you're selecting the deepest level categories
        $leafCategories = Category::whereIn('id', $selectedCategories)
            ->whereDoesntHave('children')
            ->pluck('id')
            ->toArray();
        
        // If you want to store all selected categories (including parents), use:
        $vendor->categories()->sync($selectedCategories);
        
        // If you want to store only leaf categories (recommended), use:
        // $vendor->categories()->sync($leafCategories);
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
        ];
        
        return $mapping[$type] ?? 'other';
    }
    
    protected function calculateCompletion($vendor)
    {
        $completed = 0;
        $totalRequired = 30; // Total mandatory fields
        
        // Personal Info (3 fields)
        if ($vendor->name) $completed++;
        if ($vendor->email) $completed++;
        if ($vendor->phone) $completed++;
        
        // Shop Info (12 fields)
        if ($vendor->shop_name) $completed++;
        if ($vendor->shop_email) $completed++;
        if ($vendor->shop_phone) $completed++;
        if ($vendor->shop_description) $completed++;
        if ($vendor->shop_address) $completed++;
        if ($vendor->shop_city) $completed++;
        if ($vendor->shop_state) $completed++;
        if ($vendor->shop_country) $completed++;
        if ($vendor->shop_postal_code) $completed++;
        if ($vendor->shop_logo) $completed++;
        if ($vendor->vendor_type) $completed++;
        if ($vendor->business_type) $completed++;
        
        // Tax Info (5 fields)
        if ($vendor->taxInfo) {
            if ($vendor->taxInfo->gst_number) $completed++;
            if ($vendor->taxInfo->pan_number) $completed++;
            if ($vendor->taxInfo->pan_holder_name) $completed++;
            if ($vendor->taxInfo->business_registration_number) $completed++;
            if ($vendor->taxInfo->ein_number) $completed++;
        }
        
        // Bank Info (6 fields)
        if ($vendor->bankInfo) {
            if ($vendor->bankInfo->account_holder_name) $completed++;
            if ($vendor->bankInfo->account_number) $completed++;
            if ($vendor->bankInfo->bank_name) $completed++;
            if ($vendor->bankInfo->bank_branch) $completed++;
            if ($vendor->bankInfo->ifsc_code) $completed++;
            if ($vendor->bankInfo->iban_number) $completed++;
        }
        
        // Documents (3 mandatory documents)
        if ($vendor->documents) {
            $mandatoryDocs = ['pan_card', 'gst_certificate', 'cancelled_cheque'];
            $uploadedDocs = $vendor->documents->pluck('document_type')->toArray();
            foreach ($mandatoryDocs as $doc) {
                if (in_array($doc, $uploadedDocs)) {
                    $completed++;
                }
            }
        }
        
        // Categories (at least 1 category)
        if ($vendor->categories && $vendor->categories->count() > 0) {
            $completed++;
        }
        
        $percentage = round(($completed / $totalRequired) * 100);
        
        // If 99% complete and still pending, mark for admin approval
        if ($percentage >= 99 && $vendor->account_status === 'pending') {
            $vendor->update([
                'profile_completed' => $percentage,
                'verification_status' => 'pending'
            ]);
        }
        
        return $percentage;
    }
}