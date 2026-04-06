{{-- resources/views/marketplace/pages/profile/complete.blade.php --}}

@extends('management.layouts.app')

@section('title', 'Complete Your Store Profile')

@section('content')
<div class="page-content">
    <div class="page-container">
        <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
            <div class="flex-grow-1">
                <h4 class="fs-18 text-uppercase fw-bold mb-0">Complete Your Store Profile</h4>
                <p class="text-muted mb-0">Provide your store details to start selling</p>
            </div>
            <div class="text-end">
                <ol class="breadcrumb m-0 py-0">
                    <li class="breadcrumb-item"><a href="{{ route('vendor.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Complete Profile</li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 mx-auto">
                <div class="card">
                    <div class="card-header border-bottom border-dashed">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="card-title">Store Information</h4>
                                <p class="text-muted mb-0">Complete all required fields to get verified</p>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-primary fs-13">Profile Completion: <span id="completionPercentage">{{ $completionPercentage ?? 0 }}</span>%</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form id="profileForm" method="POST" action="{{ route('vendor.profile.save-tab') }}" enctype="multipart/form-data" novalidate>
                            @csrf
                            <input type="hidden" name="tab" id="currentTab" value="personal">

                            {{-- Progress Bar --}}
                            <div class="mb-4">
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar bg-success" id="progressBar" style="width: {{ $completionPercentage ?? 0 }}%"></div>
                                </div>
                            </div>

                            {{-- Step Indicators --}}
                            <div class="d-flex justify-content-between mb-4 step-indicators">
                                <div class="step-item text-center flex-grow-1" data-tab="personal">
                                    <div class="step-circle {{ $completionPercentage >= 10 ? 'completed' : '' }}">1</div>
                                    <div class="step-label">Personal</div>
                                </div>
                                <div class="step-line flex-grow-1"></div>
                                <div class="step-item text-center flex-grow-1" data-tab="shop">
                                    <div class="step-circle {{ $completionPercentage >= 20 ? 'completed' : '' }}">2</div>
                                    <div class="step-label">Shop</div>
                                </div>
                                <div class="step-line flex-grow-1"></div>
                                <div class="step-item text-center flex-grow-1" data-tab="tax">
                                    <div class="step-circle {{ $completionPercentage >= 35 ? 'completed' : '' }}">3</div>
                                    <div class="step-label">Tax</div>
                                </div>
                                <div class="step-line flex-grow-1"></div>
                                <div class="step-item text-center flex-grow-1" data-tab="bank">
                                    <div class="step-circle {{ $completionPercentage >= 50 ? 'completed' : '' }}">4</div>
                                    <div class="step-label">Bank</div>
                                </div>
                                <div class="step-line flex-grow-1"></div>
                                <div class="step-item text-center flex-grow-1" data-tab="documents">
                                    <div class="step-circle {{ $completionPercentage >= 70 ? 'completed' : '' }}">5</div>
                                    <div class="step-label">Documents</div>
                                </div>
                                <div class="step-line flex-grow-1"></div>
                                <div class="step-item text-center flex-grow-1" data-tab="categories">
                                    <div class="step-circle {{ $completionPercentage >= 80 ? 'completed' : '' }}">6</div>
                                    <div class="step-label">Categories</div>
                                </div>
                            </div>

                            {{-- Tabs --}}
                            <ul class="nav nav-tabs" id="profileTabs" role="tablist">
                                <li class="nav-item">
                                    <button class="nav-link active" id="personal-tab" data-bs-toggle="tab" data-bs-target="#personal" type="button">
                                        <i class="ti ti-user"></i> Personal
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button class="nav-link" id="shop-tab" data-bs-toggle="tab" data-bs-target="#shop" type="button">
                                        <i class="ti ti-building-store"></i> Shop
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button class="nav-link" id="tax-tab" data-bs-toggle="tab" data-bs-target="#tax" type="button">
                                        <i class="ti ti-file-invoice"></i> Tax
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button class="nav-link" id="bank-tab" data-bs-toggle="tab" data-bs-target="#bank" type="button">
                                        <i class="ti ti-wallet"></i> Bank
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button class="nav-link" id="documents-tab" data-bs-toggle="tab" data-bs-target="#documents" type="button">
                                        <i class="ti ti-file"></i> Documents
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button class="nav-link" id="categories-tab" data-bs-toggle="tab" data-bs-target="#categories" type="button">
                                        <i class="ti ti-category"></i> Categories
                                    </button>
                                </li>
                            </ul>

                            <div class="tab-content mt-4">

                                {{-- ========== PERSONAL INFO TAB ========== --}}
                                <div class="tab-pane fade show active" id="personal" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                                <input type="text" name="name" class="form-control" value="{{ old('name', $vendor->name) }}" data-required="true">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                                <input type="email" name="email" class="form-control" value="{{ old('email', $vendor->email) }}" data-required="true">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Phone</label>
                                                <input type="text" name="phone" class="form-control" value="{{ old('phone', $vendor->phone) }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Profile Avatar</label>
                                                <input type="file" name="avatar" class="form-control" accept="image/*" onchange="previewImage(this, 'avatar-preview')">
                                                <div id="avatar-preview" class="mt-2">
                                                    @if($vendor->avatar)
                                                        <img src="{{ Storage::url($vendor->avatar) }}" width="60" class="rounded border">
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- ========== SHOP INFO TAB ========== --}}
                                <div class="tab-pane fade" id="shop" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Shop Name <span class="text-danger">*</span></label>
                                                <input type="text" name="shop_name" class="form-control" value="{{ old('shop_name', $vendor->shop_name) }}" data-required="true">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Shop Email <span class="text-danger">*</span></label>
                                                <input type="email" name="shop_email" class="form-control" value="{{ old('shop_email', $vendor->shop_email) }}" data-required="true">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Shop Phone <span class="text-danger">*</span></label>
                                                <input type="text" name="shop_phone" class="form-control" value="{{ old('shop_phone', $vendor->shop_phone) }}" data-required="true">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Shop WhatsApp</label>
                                                <input type="text" name="shop_whatsapp" class="form-control" value="{{ old('shop_whatsapp', $vendor->shop_whatsapp) }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Shop Website</label>
                                                <input type="url" name="shop_website" class="form-control" value="{{ old('shop_website', $vendor->shop_website) }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Vendor Type</label>
                                                <select name="vendor_type" class="form-select">
                                                    <option value="third_party" {{ $vendor->vendor_type == 'third_party' ? 'selected' : '' }}>Third Party</option>
                                                    <option value="own_store" {{ $vendor->vendor_type == 'own_store' ? 'selected' : '' }}>Own Store</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Business Type</label>
                                                <select name="business_type" class="form-select">
                                                    <option value="">Select</option>
                                                    <option value="sole_proprietorship" {{ $vendor->business_type == 'sole_proprietorship' ? 'selected' : '' }}>Sole Proprietorship</option>
                                                    <option value="partnership" {{ $vendor->business_type == 'partnership' ? 'selected' : '' }}>Partnership</option>
                                                    <option value="llc" {{ $vendor->business_type == 'llc' ? 'selected' : '' }}>LLC</option>
                                                    <option value="private_limited" {{ $vendor->business_type == 'private_limited' ? 'selected' : '' }}>Private Limited</option>
                                                    <option value="public_limited" {{ $vendor->business_type == 'public_limited' ? 'selected' : '' }}>Public Limited</option>
                                                    <option value="trust" {{ $vendor->business_type == 'trust' ? 'selected' : '' }}>Trust</option>
                                                    <option value="other" {{ $vendor->business_type == 'other' ? 'selected' : '' }}>Other</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="mb-3">
                                                <label class="form-label">Shop Description <span class="text-danger">*</span></label>
                                                <textarea name="shop_description" class="form-control" rows="3" data-required="true">{{ old('shop_description', $vendor->shop_description) }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Shop Logo</label>
                                                <input type="file" name="shop_logo" class="form-control" accept="image/*" onchange="previewImage(this, 'logo-preview')">
                                                <div id="logo-preview" class="mt-2">
                                                    @if($vendor->shop_logo)
                                                        <img src="{{ Storage::url($vendor->shop_logo) }}" width="80" class="rounded border">
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Shop Banner</label>
                                                <input type="file" name="shop_banner" class="form-control" accept="image/*" onchange="previewImage(this, 'banner-preview')">
                                                <div id="banner-preview" class="mt-2">
                                                    @if($vendor->shop_banner)
                                                        <img src="{{ Storage::url($vendor->shop_banner) }}" width="120" class="rounded border">
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Shop Address <span class="text-danger">*</span></label>
                                                <input type="text" name="shop_address" class="form-control" value="{{ old('shop_address', $vendor->shop_address) }}" data-required="true">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label class="form-label">City <span class="text-danger">*</span></label>
                                                <input type="text" name="shop_city" class="form-control" value="{{ old('shop_city', $vendor->shop_city) }}" data-required="true">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label class="form-label">State <span class="text-danger">*</span></label>
                                                <input type="text" name="shop_state" class="form-control" value="{{ old('shop_state', $vendor->shop_state) }}" data-required="true">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label class="form-label">Country <span class="text-danger">*</span></label>
                                                <input type="text" name="shop_country" class="form-control" value="{{ old('shop_country', $vendor->shop_country) }}" data-required="true">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label class="form-label">Postal Code <span class="text-danger">*</span></label>
                                                <input type="text" name="shop_postal_code" class="form-control" value="{{ old('shop_postal_code', $vendor->shop_postal_code) }}" data-required="true">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- ========== TAX INFO TAB ========== --}}
                                <div class="tab-pane fade" id="tax" role="tabpanel">
                                    <div class="row">
                                        <h6 class="border-bottom pb-2 mb-3">GST Information</h6>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">GST Number <span class="text-danger">*</span></label>
                                                <input type="text" name="gst_number" class="form-control" value="{{ old('gst_number', $vendor->taxInfo->gst_number ?? '') }}" data-required="true">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">GST Type</label>
                                                <select name="gst_type" class="form-select">
                                                    <option value="">Select</option>
                                                    <option value="regular" {{ ($vendor->taxInfo->gst_type ?? '') == 'regular' ? 'selected' : '' }}>Regular</option>
                                                    <option value="composition" {{ ($vendor->taxInfo->gst_type ?? '') == 'composition' ? 'selected' : '' }}>Composition</option>
                                                    <option value="casual" {{ ($vendor->taxInfo->gst_type ?? '') == 'casual' ? 'selected' : '' }}>Casual</option>
                                                    <option value="unregistered" {{ ($vendor->taxInfo->gst_type ?? '') == 'unregistered' ? 'selected' : '' }}>Unregistered</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">GST Registration Date</label>
                                                <input type="date" name="gst_registration_date" class="form-control" value="{{ old('gst_registration_date', $vendor->taxInfo->gst_registration_date ?? '') }}">
                                            </div>
                                        </div>

                                        <h6 class="border-bottom pb-2 mb-3 mt-3">PAN Information</h6>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">PAN Number <span class="text-danger">*</span></label>
                                                <input type="text" name="pan_number" class="form-control" value="{{ old('pan_number', $vendor->taxInfo->pan_number ?? '') }}" data-required="true">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">PAN Holder Name <span class="text-danger">*</span></label>
                                                <input type="text" name="pan_holder_name" class="form-control" value="{{ old('pan_holder_name', $vendor->taxInfo->pan_holder_name ?? '') }}" data-required="true">
                                            </div>
                                        </div>

                                        <h6 class="border-bottom pb-2 mb-3 mt-3">International Tax</h6>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">VAT Number</label>
                                                <input type="text" name="vat_number" class="form-control" value="{{ old('vat_number', $vendor->taxInfo->vat_number ?? '') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">EIN Number</label>
                                                <input type="text" name="ein_number" class="form-control" value="{{ old('ein_number', $vendor->taxInfo->ein_number ?? '') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">Tax ID</label>
                                                <input type="text" name="tax_id" class="form-control" value="{{ old('tax_id', $vendor->taxInfo->tax_id ?? '') }}">
                                            </div>
                                        </div>

                                        <h6 class="border-bottom pb-2 mb-3 mt-3">Business Registration</h6>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Business Registration Number</label>
                                                <input type="text" name="business_registration_number" class="form-control" value="{{ old('business_registration_number', $vendor->taxInfo->business_registration_number ?? '') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Business License Number</label>
                                                <input type="text" name="business_license_number" class="form-control" value="{{ old('business_license_number', $vendor->taxInfo->business_license_number ?? '') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Business Registration Date</label>
                                                <input type="date" name="business_registration_date" class="form-control" value="{{ old('business_registration_date', $vendor->taxInfo->business_registration_date ?? '') }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="alert alert-info mt-2">
                                        <i class="ti ti-info-circle"></i> Your tax information will be verified by our team
                                    </div>
                                </div>

                                {{-- ========== BANK INFO TAB ========== --}}
                                <div class="tab-pane fade" id="bank" role="tabpanel">
                                    <div class="row">
                                        <h6 class="border-bottom pb-2 mb-3">Bank Account Details</h6>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Account Holder Name <span class="text-danger">*</span></label>
                                                <input type="text" name="account_holder_name" class="form-control" value="{{ old('account_holder_name', $vendor->bankInfo->account_holder_name ?? '') }}" data-required="true">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Account Number <span class="text-danger">*</span></label>
                                                <input type="text" name="account_number" class="form-control" value="{{ old('account_number', $vendor->bankInfo->account_number ?? '') }}" data-required="true">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Bank Name <span class="text-danger">*</span></label>
                                                <input type="text" name="bank_name" class="form-control" value="{{ old('bank_name', $vendor->bankInfo->bank_name ?? '') }}" data-required="true">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Bank Branch <span class="text-danger">*</span></label>
                                                <input type="text" name="bank_branch" class="form-control" value="{{ old('bank_branch', $vendor->bankInfo->bank_branch ?? '') }}" data-required="true">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">IFSC Code <span class="text-danger">*</span></label>
                                                <input type="text" name="ifsc_code" class="form-control" value="{{ old('ifsc_code', $vendor->bankInfo->ifsc_code ?? '') }}" data-required="true">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">SWIFT Code</label>
                                                <input type="text" name="swift_code" class="form-control" value="{{ old('swift_code', $vendor->bankInfo->swift_code ?? '') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">Routing Number</label>
                                                <input type="text" name="routing_number" class="form-control" value="{{ old('routing_number', $vendor->bankInfo->routing_number ?? '') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">IBAN Number</label>
                                                <input type="text" name="iban_number" class="form-control" value="{{ old('iban_number', $vendor->bankInfo->iban_number ?? '') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Bank Address</label>
                                                <input type="text" name="bank_address" class="form-control" value="{{ old('bank_address', $vendor->bankInfo->bank_address ?? '') }}">
                                            </div>
                                        </div>

                                        <h6 class="border-bottom pb-2 mb-3 mt-3">Digital Payments</h6>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">UPI ID</label>
                                                <input type="text" name="upi_id" class="form-control" value="{{ old('upi_id', $vendor->bankInfo->upi_id ?? '') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">PayPal Email</label>
                                                <input type="email" name="paypal_email" class="form-control" value="{{ old('paypal_email', $vendor->bankInfo->paypal_email ?? '') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Stripe Account ID</label>
                                                <input type="text" name="stripe_account_id" class="form-control" value="{{ old('stripe_account_id', $vendor->bankInfo->stripe_account_id ?? '') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Razorpay Account ID</label>
                                                <input type="text" name="razorpay_account_id" class="form-control" value="{{ old('razorpay_account_id', $vendor->bankInfo->razorpay_account_id ?? '') }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="alert alert-warning mt-2">
                                        <i class="ti ti-alert-triangle"></i> All payouts will be sent to this bank account
                                    </div>
                                </div>

                                {{-- ========== DOCUMENTS TAB ========== --}}
                                <div class="tab-pane fade" id="documents" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">PAN Card Copy <span class="text-danger">*</span></label>
                                                <input type="file" name="documents[pan_card]" class="form-control document-input" accept=".pdf,.jpg,.jpeg,.png" onchange="previewDocument(this, 'pan_card_preview')">
                                                <div id="pan_card_preview" class="mt-2">
                                                    @php $panDoc = $vendor->documents->where('document_type', 'pan_card')->first(); @endphp
                                                    @if($panDoc)
                                                        @if(pathinfo($panDoc->document_path, PATHINFO_EXTENSION) == 'pdf')
                                                            <a href="{{ Storage::url($panDoc->document_path) }}" target="_blank" class="btn btn-sm btn-outline-danger"><i class="ti ti-file-pdf"></i> View PDF</a>
                                                        @else
                                                            <img src="{{ Storage::url($panDoc->document_path) }}" width="80" class="rounded border">
                                                        @endif
                                                        <span class="badge bg-success ms-2">Uploaded</span>
                                                        <input type="hidden" name="existing_documents[pan_card]" value="{{ $panDoc->id }}">
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">GST Certificate <span class="text-danger">*</span></label>
                                                <input type="file" name="documents[gst_certificate]" class="form-control document-input" accept=".pdf,.jpg,.jpeg,.png" onchange="previewDocument(this, 'gst_certificate_preview')">
                                                <div id="gst_certificate_preview" class="mt-2">
                                                    @php $gstDoc = $vendor->documents->where('document_type', 'gst_certificate')->first(); @endphp
                                                    @if($gstDoc)
                                                        @if(pathinfo($gstDoc->document_path, PATHINFO_EXTENSION) == 'pdf')
                                                            <a href="{{ Storage::url($gstDoc->document_path) }}" target="_blank" class="btn btn-sm btn-outline-danger"><i class="ti ti-file-pdf"></i> View PDF</a>
                                                        @else
                                                            <img src="{{ Storage::url($gstDoc->document_path) }}" width="80" class="rounded border">
                                                        @endif
                                                        <span class="badge bg-success ms-2">Uploaded</span>
                                                        <input type="hidden" name="existing_documents[gst_certificate]" value="{{ $gstDoc->id }}">
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Cancelled Cheque / Bank Proof <span class="text-danger">*</span></label>
                                                <input type="file" name="documents[cancelled_cheque]" class="form-control document-input" accept=".pdf,.jpg,.jpeg,.png" onchange="previewDocument(this, 'cancelled_cheque_preview')">
                                                <div id="cancelled_cheque_preview" class="mt-2">
                                                    @php $chequeDoc = $vendor->documents->where('document_type', 'cancelled_cheque')->first(); @endphp
                                                    @if($chequeDoc)
                                                        @if(pathinfo($chequeDoc->document_path, PATHINFO_EXTENSION) == 'pdf')
                                                            <a href="{{ Storage::url($chequeDoc->document_path) }}" target="_blank" class="btn btn-sm btn-outline-danger"><i class="ti ti-file-pdf"></i> View PDF</a>
                                                        @else
                                                            <img src="{{ Storage::url($chequeDoc->document_path) }}" width="80" class="rounded border">
                                                        @endif
                                                        <span class="badge bg-success ms-2">Uploaded</span>
                                                        <input type="hidden" name="existing_documents[cancelled_cheque]" value="{{ $chequeDoc->id }}">
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Bank Statement</label>
                                                <input type="file" name="documents[bank_statement]" class="form-control document-input" accept=".pdf,.jpg,.jpeg,.png" onchange="previewDocument(this, 'bank_statement_preview')">
                                                <div id="bank_statement_preview" class="mt-2">
                                                    @php $bankDoc = $vendor->documents->where('document_type', 'bank_statement')->first(); @endphp
                                                    @if($bankDoc)
                                                        @if(pathinfo($bankDoc->document_path, PATHINFO_EXTENSION) == 'pdf')
                                                            <a href="{{ Storage::url($bankDoc->document_path) }}" target="_blank" class="btn btn-sm btn-outline-danger"><i class="ti ti-file-pdf"></i> View PDF</a>
                                                        @else
                                                            <img src="{{ Storage::url($bankDoc->document_path) }}" width="80" class="rounded border">
                                                        @endif
                                                        <span class="badge bg-success ms-2">Uploaded</span>
                                                        <input type="hidden" name="existing_documents[bank_statement]" value="{{ $bankDoc->id }}">
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Business Registration Certificate</label>
                                                <input type="file" name="documents[business_registration]" class="form-control document-input" accept=".pdf,.jpg,.jpeg,.png" onchange="previewDocument(this, 'business_registration_preview')">
                                                <div id="business_registration_preview" class="mt-2">
                                                    @php $regDoc = $vendor->documents->where('document_type', 'business_registration')->first(); @endphp
                                                    @if($regDoc)
                                                        @if(pathinfo($regDoc->document_path, PATHINFO_EXTENSION) == 'pdf')
                                                            <a href="{{ Storage::url($regDoc->document_path) }}" target="_blank" class="btn btn-sm btn-outline-danger"><i class="ti ti-file-pdf"></i> View PDF</a>
                                                        @else
                                                            <img src="{{ Storage::url($regDoc->document_path) }}" width="80" class="rounded border">
                                                        @endif
                                                        <span class="badge bg-success ms-2">Uploaded</span>
                                                        <input type="hidden" name="existing_documents[business_registration]" value="{{ $regDoc->id }}">
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Business License</label>
                                                <input type="file" name="documents[business_license]" class="form-control document-input" accept=".pdf,.jpg,.jpeg,.png" onchange="previewDocument(this, 'business_license_preview')">
                                                <div id="business_license_preview" class="mt-2">
                                                    @php $licenseDoc = $vendor->documents->where('document_type', 'business_license')->first(); @endphp
                                                    @if($licenseDoc)
                                                        @if(pathinfo($licenseDoc->document_path, PATHINFO_EXTENSION) == 'pdf')
                                                            <a href="{{ Storage::url($licenseDoc->document_path) }}" target="_blank" class="btn btn-sm btn-outline-danger"><i class="ti ti-file-pdf"></i> View PDF</a>
                                                        @else
                                                            <img src="{{ Storage::url($licenseDoc->document_path) }}" width="80" class="rounded border">
                                                        @endif
                                                        <span class="badge bg-success ms-2">Uploaded</span>
                                                        <input type="hidden" name="existing_documents[business_license]" value="{{ $licenseDoc->id }}">
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Address Proof</label>
                                                <input type="file" name="documents[address_proof]" class="form-control document-input" accept=".pdf,.jpg,.jpeg,.png" onchange="previewDocument(this, 'address_proof_preview')">
                                                <div id="address_proof_preview" class="mt-2">
                                                    @php $addressDoc = $vendor->documents->where('document_type', 'address_proof')->first(); @endphp
                                                    @if($addressDoc)
                                                        @if(pathinfo($addressDoc->document_path, PATHINFO_EXTENSION) == 'pdf')
                                                            <a href="{{ Storage::url($addressDoc->document_path) }}" target="_blank" class="btn btn-sm btn-outline-danger"><i class="ti ti-file-pdf"></i> View PDF</a>
                                                        @else
                                                            <img src="{{ Storage::url($addressDoc->document_path) }}" width="80" class="rounded border">
                                                        @endif
                                                        <span class="badge bg-success ms-2">Uploaded</span>
                                                        <input type="hidden" name="existing_documents[address_proof]" value="{{ $addressDoc->id }}">
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Identity Proof</label>
                                                <input type="file" name="documents[identity_proof]" class="form-control document-input" accept=".pdf,.jpg,.jpeg,.png" onchange="previewDocument(this, 'identity_proof_preview')">
                                                <div id="identity_proof_preview" class="mt-2">
                                                    @php $identityDoc = $vendor->documents->where('document_type', 'identity_proof')->first(); @endphp
                                                    @if($identityDoc)
                                                        @if(pathinfo($identityDoc->document_path, PATHINFO_EXTENSION) == 'pdf')
                                                            <a href="{{ Storage::url($identityDoc->document_path) }}" target="_blank" class="btn btn-sm btn-outline-danger"><i class="ti ti-file-pdf"></i> View PDF</a>
                                                        @else
                                                            <img src="{{ Storage::url($identityDoc->document_path) }}" width="80" class="rounded border">
                                                        @endif
                                                        <span class="badge bg-success ms-2">Uploaded</span>
                                                        <input type="hidden" name="existing_documents[identity_proof]" value="{{ $identityDoc->id }}">
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Trade License</label>
                                                <input type="file" name="documents[trade_license]" class="form-control document-input" accept=".pdf,.jpg,.jpeg,.png" onchange="previewDocument(this, 'trade_license_preview')">
                                                <div id="trade_license_preview" class="mt-2">
                                                    @php $tradeDoc = $vendor->documents->where('document_type', 'trade_license')->first(); @endphp
                                                    @if($tradeDoc)
                                                        @if(pathinfo($tradeDoc->document_path, PATHINFO_EXTENSION) == 'pdf')
                                                            <a href="{{ Storage::url($tradeDoc->document_path) }}" target="_blank" class="btn btn-sm btn-outline-danger"><i class="ti ti-file-pdf"></i> View PDF</a>
                                                        @else
                                                            <img src="{{ Storage::url($tradeDoc->document_path) }}" width="80" class="rounded border">
                                                        @endif
                                                        <span class="badge bg-success ms-2">Uploaded</span>
                                                        <input type="hidden" name="existing_documents[trade_license]" value="{{ $tradeDoc->id }}">
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="alert alert-danger mt-2">
                                        <i class="ti ti-alert-circle"></i> All documents must be clear and valid. Fake documents will lead to rejection.
                                    </div>
                                </div>

                                {{-- ========== CATEGORIES TAB ========== --}}
                                <div class="tab-pane fade" id="categories" role="tabpanel">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="mb-4">
                                                <label class="form-label">Select Product Categories <span class="text-danger">*</span></label>
                                                <p class="text-muted small">Select categories that best describe the products you sell</p>
                                                
                                                <div id="categories-container">
                                                    @if(isset($selectedCategories) && count($selectedCategories) > 0)
                                                        @foreach($selectedCategories as $selectedCatId)
                                                            @php 
                                                                $selectedCat = $categories->find($selectedCatId);
                                                                if(!$selectedCat) continue;
                                                            @endphp
                                                            <div class="category-group mb-3 border rounded p-3">
                                                                <div class="row">
                                                                    <div class="col-md-10">
                                                                        <label class="form-label fw-bold">Category</label>
                                                                        <select name="categories[]" class="form-select category-select">
                                                                            <option value="">Select Category</option>
                                                                            @foreach($categories->where('parent_id', null) as $mainCat)
                                                                                <option value="{{ $mainCat->id }}" {{ $selectedCat->id == $mainCat->id || $selectedCat->parent_id == $mainCat->id ? 'selected' : '' }}>
                                                                                    {{ $mainCat->name }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-2 d-flex align-items-end">
                                                                        <button type="button" class="btn btn-danger remove-category" style="{{ $loop->first ? 'display: none;' : '' }}">
                                                                            <i class="ti ti-trash"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @else
                                                        <div class="category-group mb-3 border rounded p-3">
                                                            <div class="row">
                                                                <div class="col-md-10">
                                                                    <label class="form-label fw-bold">Category</label>
                                                                    <select name="categories[]" class="form-select category-select">
                                                                        <option value="">Select Category</option>
                                                                        @foreach($categories->where('parent_id', null) as $category)
                                                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-2 d-flex align-items-end">
                                                                    <button type="button" class="btn btn-danger remove-category" style="display: none;">
                                                                        <i class="ti ti-trash"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                                
                                                <button type="button" class="btn btn-outline-primary mt-3" id="add-category-btn">
                                                    <i class="ti ti-plus"></i> Add Another Category
                                                </button>
                                            </div>
                                            <div id="category-error" class="text-danger mt-2" style="display: none;">Please select at least one category</div>
                                        </div>
                                    </div>
                                    <div class="alert alert-info mt-2">
                                        <i class="ti ti-info-circle"></i> Select categories that best describe your products.
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between gap-2 mt-4 pt-3 border-top">
                                <button type="button" class="btn btn-secondary" id="prevBtn" style="display: none;">
                                    <i class="ti ti-arrow-left"></i> Previous
                                </button>
                                <div class="ms-auto">
                                    <button type="submit" class="btn btn-primary" id="submitBtn">
                                        <i class="ti ti-save"></i> Save & Continue
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Image preview function
function previewImage(input, previewId) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#' + previewId).html('<img src="' + e.target.result + '" width="80" class="rounded border">');
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Document preview function
function previewDocument(input, previewId) {
    if (input.files && input.files[0]) {
        var file = input.files[0];
        var fileType = file.type;
        var fileName = file.name;
        
        if (fileType === 'application/pdf') {
            $('#' + previewId).html('<a href="' + URL.createObjectURL(file) + '" target="_blank" class="btn btn-sm btn-outline-danger"><i class="ti ti-file-pdf"></i> View PDF: ' + fileName + '</a>');
        } else if (fileType.startsWith('image/')) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#' + previewId).html('<img src="' + e.target.result + '" width="80" class="rounded border">');
            };
            reader.readAsDataURL(file);
        }
        
        $(input).data('has-new-file', true);
    }
}

$(document).ready(function() {
    let formSubmitting = false;
    let tabs = ['personal', 'shop', 'tax', 'bank', 'documents', 'categories'];
    
    // ========== CRITICAL: Update hidden input when tabs are clicked ==========
    $('.nav-link').on('click', function() {
        var tabId = $(this).attr('id').replace('-tab', '');
        $('#currentTab').val(tabId);
        console.log('Tab clicked, currentTab set to:', tabId);
    });
    
    // Update hidden input when step indicators are clicked
    $('.step-item').on('click', function() {
        var tab = $(this).data('tab');
        $('#currentTab').val(tab);
        console.log('Step clicked, currentTab set to:', tab);
    });
    
    // Function to get current tab index
    function getCurrentTabIndex() {
        let activeTab = $('.tab-pane.active').attr('id');
        return tabs.indexOf(activeTab);
    }
    
    // Function to show tab by index
    function showTabByIndex(index) {
        if (index >= 0 && index < tabs.length) {
            let tabId = tabs[index];
            
            // Update tab panes
            $('.tab-pane').removeClass('show active');
            $('#' + tabId).addClass('show active');
            
            // Update nav links
            $('.nav-link').removeClass('active');
            $('#' + tabId + '-tab').addClass('active');
            
            // CRITICAL: Update hidden input
            $('#currentTab').val(tabId);
            console.log('showTabByIndex, currentTab set to:', tabId);
            
            // Update button text
            if (index === tabs.length - 1) {
                $('#submitBtn').html('<i class="ti ti-check"></i> Submit for Approval');
            } else {
                $('#submitBtn').html('<i class="ti ti-save"></i> Save & Continue');
            }
            
            // Show/hide previous button
            if (index > 0) {
                $('#prevBtn').show();
            } else {
                $('#prevBtn').hide();
            }
        }
    }
    
    // Previous button click
    $('#prevBtn').on('click', function() {
        let currentIndex = getCurrentTabIndex();
        if (currentIndex > 0) {
            showTabByIndex(currentIndex - 1);
        }
    });
    
    // Add category button
    $('#add-category-btn').on('click', function() {
        let container = $('#categories-container');
        let newHtml = `
            <div class="category-group mb-3 border rounded p-3">
                <div class="row">
                    <div class="col-md-10">
                        <label class="form-label fw-bold">Category</label>
                        <select name="categories[]" class="form-select category-select">
                            <option value="">Select Category</option>
                            @foreach($categories->where('parent_id', null) as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="button" class="btn btn-danger remove-category">
                            <i class="ti ti-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
        container.append(newHtml);
        
        // Bind remove button for new category
        container.find('.remove-category').last().on('click', function() {
            $(this).closest('.category-group').remove();
        });
    });
    
    // Bind remove button for existing categories
    $('.remove-category').on('click', function() {
        $(this).closest('.category-group').remove();
    });
    
    // Document required validation - check if already uploaded
    $('.document-input').each(function() {
        let hasExisting = $(this).siblings('.mt-2').find('.badge.bg-success').length > 0;
        if (hasExisting) {
            $(this).data('uploaded', true);
        }
    });
    
    // Validate categories
    function validateCategories() {
        let selectedCount = $('.category-select option:selected').filter(function() {
            return $(this).val() !== '';
        }).length;
        
        if (selectedCount === 0) {
            $('#category-error').show();
            return false;
        }
        $('#category-error').hide();
        return true;
    }
    
    // Form submission
    $('#profileForm').on('submit', function(e) {
        e.preventDefault();
        
        if (formSubmitting) return false;
        
        let activeTabId = $('.tab-pane.active').attr('id');
        let currentTabValue = $('#currentTab').val();
        
        console.log('Form submit - Active tab:', activeTabId);
        console.log('Form submit - Hidden input value:', currentTabValue);
        
        // Use the hidden input value for validation
        let tabToValidate = currentTabValue;
        let isValid = true;
        let firstInvalid = null;
        
        if (tabToValidate === 'categories') {
            isValid = validateCategories();
            if (!isValid) {
                firstInvalid = $('#category-error');
            }
        } else {
            $('#' + tabToValidate + ' [data-required="true"]').each(function() {
                let $field = $(this);
                let fieldType = $field.attr('type');
                let fieldValue = $field.val();
                
                $field.removeClass('is-invalid');
                $field.next('.invalid-feedback').remove();
                
                if (fieldType === 'file') {
                    let hasExisting = $field.data('uploaded') === true;
                    let hasNewFile = $field.data('has-new-file') === true;
                    
                    if (!hasExisting && !hasNewFile && !fieldValue) {
                        $field.addClass('is-invalid');
                        $field.after('<div class="invalid-feedback d-block">This field is required</div>');
                        isValid = false;
                        if (!firstInvalid) firstInvalid = $field;
                    }
                } else {
                    if (!fieldValue || fieldValue.trim() === '') {
                        $field.addClass('is-invalid');
                        $field.after('<div class="invalid-feedback d-block">This field is required</div>');
                        isValid = false;
                        if (!firstInvalid) firstInvalid = $field;
                    }
                }
            });
        }
        
        if (!isValid) {
            if (firstInvalid) {
                $('html, body').animate({ scrollTop: firstInvalid.offset().top - 150 }, 500);
            }
            Swal.fire({ icon: 'error', title: 'Validation Error', text: 'Please fill all required fields', confirmButtonColor: '#d33' });
            return false;
        }
        
        formSubmitting = true;
        let formData = new FormData(this);
        let btn = $('#submitBtn');
        let originalText = btn.html();
        btn.html('<span class="spinner-border spinner-border-sm me-1"></span> Saving...');
        btn.prop('disabled', true);
        
        // Make sure the tab value is set correctly
        formData.set('tab', $('#currentTab').val());
        
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(response) {
                if (response.success) {
                    $('#progressBar').css('width', response.completion_percentage + '%');
                    $('#completionPercentage').text(response.completion_percentage);
                    
                    // Mark uploaded files
                    $('input[type="file"][data-required="true"]').each(function() {
                        if ($(this).val()) {
                            $(this).data('uploaded', true);
                        }
                    });
                    
                    Swal.fire({ icon: 'success', title: 'Success!', text: response.message, timer: 1500, showConfirmButton: false })
                        .then(() => {
                            if (response.is_complete) {
                                window.location.href = response.redirect_url;
                            } else if (response.next_tab) {
                                let nextTabIndex = tabs.indexOf(response.next_tab);
                                if (nextTabIndex !== -1) {
                                    showTabByIndex(nextTabIndex);
                                }
                            }
                        });
                }
                formSubmitting = false;
                btn.html(originalText);
                btn.prop('disabled', false);
            },
            error: function(xhr) {
                formSubmitting = false;
                btn.html(originalText);
                btn.prop('disabled', false);
                
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    $.each(errors, function(field, messages) {
                        let input = $('[name="' + field + '"]');
                        input.addClass('is-invalid');
                        if (!input.next('.invalid-feedback').length) {
                            input.after('<div class="invalid-feedback d-block">' + messages[0] + '</div>');
                        }
                    });
                    Swal.fire({ icon: 'error', title: 'Validation Error', text: 'Please check the form for errors', confirmButtonColor: '#d33' });
                } else {
                    Swal.fire({ icon: 'error', title: 'Error!', text: xhr.responseJSON?.message || 'Something went wrong.', confirmButtonColor: '#d33' });
                }
            }
        });
    });
    
    // Remove error on input
    $('input, select, textarea').on('input change', function() {
        $(this).removeClass('is-invalid');
        $(this).next('.invalid-feedback').remove();
    });
});
</script>
@endpush

@push('styles')
<style>
    .nav-tabs .nav-link { color: #6c757d; border: none; padding: 10px 20px; }
    .nav-tabs .nav-link.active { color: #0d6efd; border-bottom: 2px solid #0d6efd; background: transparent; }
    .progress { border-radius: 10px; }
    .progress-bar { transition: width 0.3s ease; }
    .step-indicators { margin-bottom: 30px; }
    .step-item { cursor: pointer; position: relative; }
    .step-circle {
        width: 40px; height: 40px; border-radius: 50%; background: #e9ecef;
        border: 2px solid #dee2e6; color: #6c757d; display: flex;
        align-items: center; justify-content: center; margin: 0 auto 8px; font-weight: bold;
        transition: all 0.3s;
    }
    .step-circle.completed { background: #198754; border-color: #198754; color: white; }
    .step-label { font-size: 12px; color: #6c757d; }
    .step-line { height: 2px; background: #dee2e6; margin: 0 -10px; margin-top: 20px; }
    .category-group { border-left: 3px solid #0d6efd; }
</style>
@endpush