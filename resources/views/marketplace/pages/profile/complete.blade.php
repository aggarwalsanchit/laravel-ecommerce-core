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
                                    <span class="badge bg-primary fs-13">Profile Completion: <span
                                            id="completionPercentage">{{ $completionPercentage ?? 0 }}</span>%</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form id="profileForm" method="POST" action="{{ route('vendor.profile.save-tab') }}"
                                enctype="multipart/form-data" novalidate>
                                @csrf
                                <input type="hidden" name="tab" id="currentTab" value="personal">

                                {{-- Progress Bar --}}
                                <div class="mb-4">
                                    <div class="progress" style="height: 10px;">
                                        <div class="progress-bar bg-success" id="progressBar"
                                            style="width: {{ $completionPercentage ?? 0 }}%"></div>
                                    </div>
                                </div>

                                {{-- Step Indicators --}}
                                <div class="d-flex justify-content-between mb-4 step-indicators">
                                    <div class="step-item text-center flex-grow-1" data-tab="personal">
                                        <div class="step-circle">1</div>
                                        <div class="step-label">Personal</div>
                                    </div>
                                    <div class="step-line flex-grow-1"></div>
                                    <div class="step-item text-center flex-grow-1" data-tab="shop">
                                        <div class="step-circle">2</div>
                                        <div class="step-label">Shop</div>
                                    </div>
                                    <div class="step-line flex-grow-1"></div>
                                    <div class="step-item text-center flex-grow-1" data-tab="tax">
                                        <div class="step-circle">3</div>
                                        <div class="step-label">Tax</div>
                                    </div>
                                    <div class="step-line flex-grow-1"></div>
                                    <div class="step-item text-center flex-grow-1" data-tab="bank">
                                        <div class="step-circle">4</div>
                                        <div class="step-label">Bank</div>
                                    </div>
                                    <div class="step-line flex-grow-1"></div>
                                    <div class="step-item text-center flex-grow-1" data-tab="documents">
                                        <div class="step-circle">5</div>
                                        <div class="step-label">Documents</div>
                                    </div>
                                </div>

                                {{-- Tabs --}}
                                <ul class="nav nav-tabs" id="profileTabs" role="tablist">
                                    <li class="nav-item">
                                        <button class="nav-link active" id="personal-tab" data-bs-toggle="tab"
                                            data-bs-target="#personal" type="button">
                                            <i class="ti ti-user"></i> Personal
                                        </button>
                                    </li>
                                    <li class="nav-item">
                                        <button class="nav-link" id="shop-tab" data-bs-toggle="tab" data-bs-target="#shop"
                                            type="button">
                                            <i class="ti ti-building-store"></i> Shop
                                        </button>
                                    </li>
                                    <li class="nav-item">
                                        <button class="nav-link" id="tax-tab" data-bs-toggle="tab" data-bs-target="#tax"
                                            type="button">
                                            <i class="ti ti-file-invoice"></i> Tax
                                        </button>
                                    </li>
                                    <li class="nav-item">
                                        <button class="nav-link" id="bank-tab" data-bs-toggle="tab" data-bs-target="#bank"
                                            type="button">
                                            <i class="ti ti-wallet"></i> Bank
                                        </button>
                                    </li>
                                    <li class="nav-item">
                                        <button class="nav-link" id="documents-tab" data-bs-toggle="tab"
                                            data-bs-target="#documents" type="button">
                                            <i class="ti ti-file"></i> Documents
                                        </button>
                                    </li>
                                </ul>

                                <div class="tab-content mt-4">

                                    {{-- ========== PERSONAL INFO TAB ========== --}}
                                    <div class="tab-pane fade show active" id="personal" role="tabpanel">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="alert alert-info mb-3">
                                                    <i class="ti ti-info-circle"></i> Only Name and Email are required.
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Full Name <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" name="name" class="form-control"
                                                        value="{{ old('name', $vendor->name) }}" data-required="true">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Email <span
                                                            class="text-danger">*</span></label>
                                                    <input type="email" name="email" class="form-control" readonly
                                                        value="{{ old('email', $vendor->email) }}" data-required="true">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- ========== SHOP INFO TAB ========== --}}
                                    <div class="tab-pane fade" id="shop" role="tabpanel">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Shop Name <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" name="shop_name" id="shop_name"
                                                        class="form-control"
                                                        value="{{ old('shop_name', $shop->shop_name) }}"
                                                        data-required="true">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Shop Slug</label>
                                                    <input type="text" name="shop_slug" id="shop_slug"
                                                        class="form-control" readonly
                                                        value="{{ old('shop_slug', $shop->shop_slug) }}">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label class="form-label">Shop Description</label>
                                                    <textarea name="shop_description" class="form-control" rows="3">{{ old('shop_description', $shop->shop_description) }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Shop Logo <span
                                                            class="text-danger">*</span></label>
                                                    <input type="file" name="shop_logo" class="form-control"
                                                        accept="image/*" onchange="previewImage(this, 'logo-preview')"
                                                        data-required="true"
                                                        data-has-existing="{{ $shop->shop_logo ? 'true' : 'false' }}">
                                                    <div id="logo-preview" class="mt-2">
                                                        @if ($shop->shop_logo)
                                                            <img src="{{ Storage::url($shop->shop_logo) }}"
                                                                width="80" class="rounded border">
                                                            <input type="hidden" name="existing_shop_logo"
                                                                value="{{ $shop->shop_logo }}">
                                                        @endif
                                                    </div>
                                                    <small class="text-muted">Leave empty to keep existing logo</small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Shop Banner</label>
                                                    <input type="file" name="shop_banner" class="form-control"
                                                        accept="image/*" onchange="previewImage(this, 'banner-preview')">
                                                    <div id="banner-preview" class="mt-2">
                                                        @if ($shop->shop_banner)
                                                            <img src="{{ Storage::url($shop->shop_banner) }}"
                                                                width="120" class="rounded border">
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Shop Email <span
                                                            class="text-danger">*</span></label>
                                                    <input type="email" name="shop_email" class="form-control"
                                                        value="{{ old('shop_email', $shop->shop_email) }}"
                                                        data-required="true">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="mb-3">
                                                    <label class="form-label">Country <span
                                                            class="text-danger">*</span></label>
                                                    <select name="country_id" data-choices id="country_id"
                                                        class="form-select" data-required="true">
                                                        <option value="">Select Country</option>
                                                        @foreach ($countries ?? [] as $country)
                                                            <option value="{{ $country->id }}"
                                                                data-phonecode="{{ $country->phonecode }}"
                                                                {{ old('country_id', $shop->shop_country) == $country->id ? 'selected' : '' }}>
                                                                {{ $country->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="mb-3">
                                                    <label class="form-label">Phone Code <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" name="shop_phone_code" id="shop_phone_code"
                                                        class="form-control"
                                                        value="{{ old('shop_phone_code', $shop->shop_phone_code) }}"
                                                        readonly data-required="true">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Shop Phone <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" name="shop_phone" class="form-control"
                                                        value="{{ old('shop_phone', $shop->shop_phone) }}"
                                                        data-required="true">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Shop WhatsApp</label>
                                                    <input type="text" name="shop_whatsapp" class="form-control"
                                                        value="{{ old('shop_whatsapp', $shop->shop_whatsapp) }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Shop Website</label>
                                                    <input type="url" name="shop_website" class="form-control"
                                                        value="{{ old('shop_website', $shop->shop_website) }}">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Business Type <span
                                                            class="text-danger">*</span></label>
                                                    <select name="business_type" id="business_type" class="form-select"
                                                        data-required="true">
                                                        <option value="">Select Business Type</option>
                                                        @foreach ($businessTypes ?? [] as $type)
                                                            <option value="{{ $type->slug }}"
                                                                {{ old('business_type', $shop->business_type) == $type->slug ? 'selected' : '' }}>
                                                                {{ $type->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">State <span
                                                            class="text-danger">*</span></label>
                                                    <select name="state_id" data-choices id="state_id"
                                                        class="form-select" data-required="true">
                                                        <option value="">Select State</option>
                                                        @foreach ($states ?? [] as $state)
                                                            <option value="{{ $state->id }}"
                                                                {{ old('state_id', $shop->shop_state) == $state->id ? 'selected' : '' }}>
                                                                {{ $state->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">City <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" name="shop_city" class="form-control"
                                                        value="{{ old('shop_city', $shop->shop_city) }}"
                                                        data-required="true">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label class="form-label">Shop Address <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" name="shop_address" class="form-control"
                                                        value="{{ old('shop_address', $shop->shop_address) }}"
                                                        data-required="true">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label">Postal Code <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" name="shop_postal_code" class="form-control"
                                                        value="{{ old('shop_postal_code', $shop->shop_postal_code) }}"
                                                        data-required="true">
                                                </div>
                                            </div>

                                            {{-- Categories Multi-Select --}}
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label class="form-label">Business Categories <span
                                                            class="text-danger">*</span></label>
                                                    <select name="categories[]" data-choices id="categories_select"
                                                        class="form-control" multiple data-required="true">
                                                        @foreach ($mainCategories ?? [] as $category)
                                                            <option value="{{ $category->id }}"
                                                                {{ in_array($category->id, $selectedCategories ?? []) ? 'selected' : '' }}>
                                                                {{ $category->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <small class="text-muted">Select one or more categories that best
                                                        describe your business (You can select multiple)</small>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-check mb-3">
                                                    <input type="checkbox" class="form-check-input" id="accepts_cod"
                                                        name="accepts_cod" value="1"
                                                        {{ old('accepts_cod', $shop->accepts_cod) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="accepts_cod">Accept Cash on
                                                        Delivery (COD)</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- ========== TAX INFO TAB ========== --}}
                                    <div class="tab-pane fade" id="tax" role="tabpanel">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="alert alert-info mb-3">
                                                    <i class="ti ti-info-circle"></i> PAN Number, PAN Holder Name, and
                                                    Business Registration Date are mandatory.
                                                </div>
                                            </div>
                                            <h6 class="border-bottom pb-2 mb-3">GST Information</h6>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">GST Number</label>
                                                    <input type="text" name="gst_number" class="form-control"
                                                        value="{{ old('gst_number', $shop->taxInfo->gst_number ?? '') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">GST Type</label>
                                                    <select name="gst_type" class="form-select">
                                                        <option value="">Select</option>
                                                        <option value="regular"
                                                            {{ ($shop->taxInfo->gst_type ?? '') == 'regular' ? 'selected' : '' }}>
                                                            Regular</option>
                                                        <option value="composition"
                                                            {{ ($shop->taxInfo->gst_type ?? '') == 'composition' ? 'selected' : '' }}>
                                                            Composition</option>
                                                        <option value="casual"
                                                            {{ ($shop->taxInfo->gst_type ?? '') == 'casual' ? 'selected' : '' }}>
                                                            Casual</option>
                                                        <option value="unregistered"
                                                            {{ ($shop->taxInfo->gst_type ?? '') == 'unregistered' ? 'selected' : '' }}>
                                                            Unregistered</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">GST Registration Date</label>
                                                    <input type="date" name="gst_registration_date"
                                                        class="form-control"
                                                        value="{{ old('gst_registration_date', $shop->taxInfo->gst_registration_date ? \Carbon\Carbon::parse($shop->taxInfo->gst_registration_date)->format('Y-m-d') : '') }}">
                                                </div>
                                            </div>

                                            <h6 class="border-bottom pb-2 mb-3 mt-3">PAN Information</h6>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">PAN Number <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" name="pan_number" class="form-control"
                                                        value="{{ old('pan_number', $shop->taxInfo->pan_number ?? '') }}"
                                                        data-required="true">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">PAN Holder Name <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" name="pan_holder_name" class="form-control"
                                                        value="{{ old('pan_holder_name', $shop->taxInfo->pan_holder_name ?? '') }}"
                                                        data-required="true">
                                                </div>
                                            </div>

                                            <h6 class="border-bottom pb-2 mb-3 mt-3">International Tax</h6>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label">VAT Number</label>
                                                    <input type="text" name="vat_number" class="form-control"
                                                        value="{{ old('vat_number', $shop->taxInfo->vat_number ?? '') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label">EIN Number</label>
                                                    <input type="text" name="ein_number" class="form-control"
                                                        value="{{ old('ein_number', $shop->taxInfo->ein_number ?? '') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label">Tax ID</label>
                                                    <input type="text" name="tax_id" class="form-control"
                                                        value="{{ old('tax_id', $shop->taxInfo->tax_id ?? '') }}">
                                                </div>
                                            </div>

                                            <h6 class="border-bottom pb-2 mb-3 mt-3">Business Registration</h6>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Business Registration Number</label>
                                                    <input type="text" name="business_registration_number"
                                                        class="form-control"
                                                        value="{{ old('business_registration_number', $shop->taxInfo->business_registration_number ?? '') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Business License Number</label>
                                                    <input type="text" name="business_license_number"
                                                        class="form-control"
                                                        value="{{ old('business_license_number', $shop->taxInfo->business_license_number ?? '') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Business Registration Date <span
                                                            class="text-danger">*</span></label>
                                                    <input type="date" name="business_registration_date"
                                                        class="form-control @error('business_registration_date') is-invalid @enderror"
                                                        value="{{ old('business_registration_date', $shop->taxInfo->business_registration_date ? \Carbon\Carbon::parse($shop->taxInfo->business_registration_date)->format('Y-m-d') : '') }}"
                                                        data-required="true">
                                                    @error('business_registration_date')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="alert alert-info mt-2">
                                            <i class="ti ti-info-circle"></i> Your tax information will be verified by our
                                            team
                                        </div>
                                    </div>

                                    {{-- ========== BANK INFO TAB ========== --}}
                                    <div class="tab-pane fade" id="bank" role="tabpanel">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="alert alert-info mb-3">
                                                    <i class="ti ti-info-circle"></i> All fields in Bank Account Details
                                                    section are mandatory.
                                                </div>
                                            </div>
                                            <h6 class="border-bottom pb-2 mb-3">Bank Account Details</h6>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Account Holder Name <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" name="account_holder_name" class="form-control"
                                                        value="{{ old('account_holder_name', $shop->bankInfo->account_holder_name ?? '') }}"
                                                        data-required="true">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Account Number <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" name="account_number" class="form-control"
                                                        value="{{ old('account_number', $shop->bankInfo->account_number ?? '') }}"
                                                        data-required="true">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Bank Name <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" name="bank_name" class="form-control"
                                                        value="{{ old('bank_name', $shop->bankInfo->bank_name ?? '') }}"
                                                        data-required="true">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Bank Branch <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" name="bank_branch" class="form-control"
                                                        value="{{ old('bank_branch', $shop->bankInfo->bank_branch ?? '') }}"
                                                        data-required="true">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label">IFSC Code <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" name="ifsc_code" class="form-control"
                                                        value="{{ old('ifsc_code', $shop->bankInfo->ifsc_code ?? '') }}"
                                                        data-required="true">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label">SWIFT Code</label>
                                                    <input type="text" name="swift_code" class="form-control"
                                                        value="{{ old('swift_code', $shop->bankInfo->swift_code ?? '') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label">Routing Number</label>
                                                    <input type="text" name="routing_number" class="form-control"
                                                        value="{{ old('routing_number', $shop->bankInfo->routing_number ?? '') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">IBAN Number</label>
                                                    <input type="text" name="iban_number" class="form-control"
                                                        value="{{ old('iban_number', $shop->bankInfo->iban_number ?? '') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Bank Address <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" name="bank_address" class="form-control"
                                                        value="{{ old('bank_address', $shop->bankInfo->bank_address ?? '') }}"
                                                        data-required="true">
                                                </div>
                                            </div>

                                            <h6 class="border-bottom pb-2 mb-3 mt-3">Digital Payments</h6>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">UPI ID</label>
                                                    <input type="text" name="upi_id" class="form-control"
                                                        value="{{ old('upi_id', $shop->bankInfo->upi_id ?? '') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">PayPal Email</label>
                                                    <input type="email" name="paypal_email" class="form-control"
                                                        value="{{ old('paypal_email', $shop->bankInfo->paypal_email ?? '') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Stripe Account ID</label>
                                                    <input type="text" name="stripe_account_id" class="form-control"
                                                        value="{{ old('stripe_account_id', $shop->bankInfo->stripe_account_id ?? '') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Razorpay Account ID</label>
                                                    <input type="text" name="razorpay_account_id" class="form-control"
                                                        value="{{ old('razorpay_account_id', $shop->bankInfo->razorpay_account_id ?? '') }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="alert alert-warning mt-2">
                                            <i class="ti ti-alert-triangle"></i> All payouts will be sent to this bank
                                            account
                                        </div>
                                    </div>

                                    {{-- ========== DOCUMENTS TAB ========== --}}
                                    <div class="tab-pane fade" id="documents" role="tabpanel">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="alert alert-danger mb-3">
                                                    <i class="ti ti-alert-circle"></i> PAN Card, Cancelled Cheque, Bank
                                                    Statement, Address Proof, and Identity Proof are mandatory.
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">PAN Card Copy <span
                                                            class="text-danger">*</span></label>
                                                    <input type="file" name="documents[pan_card]"
                                                        class="form-control document-input" accept=".pdf,.jpg,.jpeg,.png"
                                                        onchange="previewDocument(this, 'pan_card_preview')"
                                                        data-required="true">
                                                    <div id="pan_card_preview" class="mt-2">
                                                        @php $panDoc = $shop->documents->where('document_type', 'pan_card')->first(); @endphp
                                                        @if ($panDoc)
                                                            @if (pathinfo($panDoc->document_path, PATHINFO_EXTENSION) == 'pdf')
                                                                <a href="{{ Storage::url($panDoc->document_path) }}"
                                                                    target="_blank"
                                                                    class="btn btn-sm btn-outline-danger"><i
                                                                        class="ti ti-file-pdf"></i> View PDF</a>
                                                            @else
                                                                <img src="{{ Storage::url($panDoc->document_path) }}"
                                                                    width="80" class="rounded border">
                                                            @endif
                                                            <span class="badge bg-success ms-2">Uploaded</span>
                                                            <input type="hidden" name="existing_documents[pan_card]"
                                                                value="{{ $panDoc->id }}">
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">GST Certificate</label>
                                                    <input type="file" name="documents[gst_certificate]"
                                                        class="form-control document-input" accept=".pdf,.jpg,.jpeg,.png"
                                                        onchange="previewDocument(this, 'gst_certificate_preview')">
                                                    <div id="gst_certificate_preview" class="mt-2">
                                                        @php $gstDoc = $shop->documents->where('document_type', 'gst_certificate')->first(); @endphp
                                                        @if ($gstDoc)
                                                            @if (pathinfo($gstDoc->document_path, PATHINFO_EXTENSION) == 'pdf')
                                                                <a href="{{ Storage::url($gstDoc->document_path) }}"
                                                                    target="_blank"
                                                                    class="btn btn-sm btn-outline-danger"><i
                                                                        class="ti ti-file-pdf"></i> View PDF</a>
                                                            @else
                                                                <img src="{{ Storage::url($gstDoc->document_path) }}"
                                                                    width="80" class="rounded border">
                                                            @endif
                                                            <span class="badge bg-success ms-2">Uploaded</span>
                                                            <input type="hidden"
                                                                name="existing_documents[gst_certificate]"
                                                                value="{{ $gstDoc->id }}">
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Cancelled Cheque / Bank Proof <span
                                                            class="text-danger">*</span></label>
                                                    <input type="file" name="documents[cancelled_cheque]"
                                                        class="form-control document-input" accept=".pdf,.jpg,.jpeg,.png"
                                                        onchange="previewDocument(this, 'cancelled_cheque_preview')"
                                                        data-required="true">
                                                    <div id="cancelled_cheque_preview" class="mt-2">
                                                        @php $chequeDoc = $shop->documents->where('document_type', 'cancelled_cheque')->first(); @endphp
                                                        @if ($chequeDoc)
                                                            @if (pathinfo($chequeDoc->document_path, PATHINFO_EXTENSION) == 'pdf')
                                                                <a href="{{ Storage::url($chequeDoc->document_path) }}"
                                                                    target="_blank"
                                                                    class="btn btn-sm btn-outline-danger"><i
                                                                        class="ti ti-file-pdf"></i> View PDF</a>
                                                            @else
                                                                <img src="{{ Storage::url($chequeDoc->document_path) }}"
                                                                    width="80" class="rounded border">
                                                            @endif
                                                            <span class="badge bg-success ms-2">Uploaded</span>
                                                            <input type="hidden"
                                                                name="existing_documents[cancelled_cheque]"
                                                                value="{{ $chequeDoc->id }}">
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Bank Statement <span
                                                            class="text-danger">*</span></label>
                                                    <input type="file" name="documents[bank_statement]"
                                                        class="form-control document-input" accept=".pdf,.jpg,.jpeg,.png"
                                                        onchange="previewDocument(this, 'bank_statement_preview')"
                                                        data-required="true">
                                                    <div id="bank_statement_preview" class="mt-2">
                                                        @php $bankDoc = $shop->documents->where('document_type', 'bank_statement')->first(); @endphp
                                                        @if ($bankDoc)
                                                            @if (pathinfo($bankDoc->document_path, PATHINFO_EXTENSION) == 'pdf')
                                                                <a href="{{ Storage::url($bankDoc->document_path) }}"
                                                                    target="_blank"
                                                                    class="btn btn-sm btn-outline-danger"><i
                                                                        class="ti ti-file-pdf"></i> View PDF</a>
                                                            @else
                                                                <img src="{{ Storage::url($bankDoc->document_path) }}"
                                                                    width="80" class="rounded border">
                                                            @endif
                                                            <span class="badge bg-success ms-2">Uploaded</span>
                                                            <input type="hidden"
                                                                name="existing_documents[bank_statement]"
                                                                value="{{ $bankDoc->id }}">
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Business Registration Certificate</label>
                                                    <input type="file" name="documents[business_registration]"
                                                        class="form-control document-input" accept=".pdf,.jpg,.jpeg,.png"
                                                        onchange="previewDocument(this, 'business_registration_preview')">
                                                    <div id="business_registration_preview" class="mt-2">
                                                        @php $regDoc = $shop->documents->where('document_type', 'business_registration')->first(); @endphp
                                                        @if ($regDoc)
                                                            @if (pathinfo($regDoc->document_path, PATHINFO_EXTENSION) == 'pdf')
                                                                <a href="{{ Storage::url($regDoc->document_path) }}"
                                                                    target="_blank"
                                                                    class="btn btn-sm btn-outline-danger"><i
                                                                        class="ti ti-file-pdf"></i> View PDF</a>
                                                            @else
                                                                <img src="{{ Storage::url($regDoc->document_path) }}"
                                                                    width="80" class="rounded border">
                                                            @endif
                                                            <span class="badge bg-success ms-2">Uploaded</span>
                                                            <input type="hidden"
                                                                name="existing_documents[business_registration]"
                                                                value="{{ $regDoc->id }}">
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Business License</label>
                                                    <input type="file" name="documents[business_license]"
                                                        class="form-control document-input" accept=".pdf,.jpg,.jpeg,.png"
                                                        onchange="previewDocument(this, 'business_license_preview')">
                                                    <div id="business_license_preview" class="mt-2">
                                                        @php $licenseDoc = $shop->documents->where('document_type', 'business_license')->first(); @endphp
                                                        @if ($licenseDoc)
                                                            @if (pathinfo($licenseDoc->document_path, PATHINFO_EXTENSION) == 'pdf')
                                                                <a href="{{ Storage::url($licenseDoc->document_path) }}"
                                                                    target="_blank"
                                                                    class="btn btn-sm btn-outline-danger"><i
                                                                        class="ti ti-file-pdf"></i> View PDF</a>
                                                            @else
                                                                <img src="{{ Storage::url($licenseDoc->document_path) }}"
                                                                    width="80" class="rounded border">
                                                            @endif
                                                            <span class="badge bg-success ms-2">Uploaded</span>
                                                            <input type="hidden"
                                                                name="existing_documents[business_license]"
                                                                value="{{ $licenseDoc->id }}">
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Address Proof <span
                                                            class="text-danger">*</span></label>
                                                    <input type="file" name="documents[address_proof]"
                                                        class="form-control document-input" accept=".pdf,.jpg,.jpeg,.png"
                                                        onchange="previewDocument(this, 'address_proof_preview')"
                                                        data-required="true">
                                                    <div id="address_proof_preview" class="mt-2">
                                                        @php $addressDoc = $shop->documents->where('document_type', 'address_proof')->first(); @endphp
                                                        @if ($addressDoc)
                                                            @if (pathinfo($addressDoc->document_path, PATHINFO_EXTENSION) == 'pdf')
                                                                <a href="{{ Storage::url($addressDoc->document_path) }}"
                                                                    target="_blank"
                                                                    class="btn btn-sm btn-outline-danger"><i
                                                                        class="ti ti-file-pdf"></i> View PDF</a>
                                                            @else
                                                                <img src="{{ Storage::url($addressDoc->document_path) }}"
                                                                    width="80" class="rounded border">
                                                            @endif
                                                            <span class="badge bg-success ms-2">Uploaded</span>
                                                            <input type="hidden" name="existing_documents[address_proof]"
                                                                value="{{ $addressDoc->id }}">
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Identity Proof <span
                                                            class="text-danger">*</span></label>
                                                    <input type="file" name="documents[identity_proof]"
                                                        class="form-control document-input" accept=".pdf,.jpg,.jpeg,.png"
                                                        onchange="previewDocument(this, 'identity_proof_preview')"
                                                        data-required="true">
                                                    <div id="identity_proof_preview" class="mt-2">
                                                        @php $identityDoc = $shop->documents->where('document_type', 'identity_proof')->first(); @endphp
                                                        @if ($identityDoc)
                                                            @if (pathinfo($identityDoc->document_path, PATHINFO_EXTENSION) == 'pdf')
                                                                <a href="{{ Storage::url($identityDoc->document_path) }}"
                                                                    target="_blank"
                                                                    class="btn btn-sm btn-outline-danger"><i
                                                                        class="ti ti-file-pdf"></i> View PDF</a>
                                                            @else
                                                                <img src="{{ Storage::url($identityDoc->document_path) }}"
                                                                    width="80" class="rounded border">
                                                            @endif
                                                            <span class="badge bg-success ms-2">Uploaded</span>
                                                            <input type="hidden"
                                                                name="existing_documents[identity_proof]"
                                                                value="{{ $identityDoc->id }}">
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Trade License</label>
                                                    <input type="file" name="documents[trade_license]"
                                                        class="form-control document-input" accept=".pdf,.jpg,.jpeg,.png"
                                                        onchange="previewDocument(this, 'trade_license_preview')">
                                                    <div id="trade_license_preview" class="mt-2">
                                                        @php $tradeDoc = $shop->documents->where('document_type', 'trade_license')->first(); @endphp
                                                        @if ($tradeDoc)
                                                            @if (pathinfo($tradeDoc->document_path, PATHINFO_EXTENSION) == 'pdf')
                                                                <a href="{{ Storage::url($tradeDoc->document_path) }}"
                                                                    target="_blank"
                                                                    class="btn btn-sm btn-outline-danger"><i
                                                                        class="ti ti-file-pdf"></i> View PDF</a>
                                                            @else
                                                                <img src="{{ Storage::url($tradeDoc->document_path) }}"
                                                                    width="80" class="rounded border">
                                                            @endif
                                                            <span class="badge bg-success ms-2">Uploaded</span>
                                                            <input type="hidden" name="existing_documents[trade_license]"
                                                                value="{{ $tradeDoc->id }}">
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Other Documents</label>
                                                    <input type="file" name="documents[other]"
                                                        class="form-control document-input" accept=".pdf,.jpg,.jpeg,.png"
                                                        onchange="previewDocument(this, 'other_preview')">
                                                    <div id="other_preview" class="mt-2"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="alert alert-danger mt-2">
                                            <i class="ti ti-alert-circle"></i> All documents must be clear and valid. Fake
                                            documents will lead to rejection.
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between gap-2 mt-4 pt-3 border-top">
                                    <button type="button" class="btn btn-secondary" id="prevBtn"
                                        style="display: none;">
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
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    <script>
        // Initialize Choices.js for categories multi-select
        const categoriesSelect = document.getElementById('categories_select');
        if (categoriesSelect) {
            new Choices(categoriesSelect, {
                removeItemButton: true,
                placeholder: 'Select categories...',
                placeholderValue: 'Select categories...',
                searchEnabled: true,
                searchChoices: true,
                searchResultLimit: 10,
                shouldSort: false,
                itemSelectText: '',
            });
        }

        function previewImage(input, previewId) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#' + previewId).html('<img src="' + e.target.result + '" width="80" class="rounded border">');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function previewDocument(input, previewId) {
            if (input.files && input.files[0]) {
                var file = input.files[0];
                var fileType = file.type;
                var fileName = file.name;

                if (fileType === 'application/pdf') {
                    $('#' + previewId).html('<a href="' + URL.createObjectURL(file) +
                        '" target="_blank" class="btn btn-sm btn-outline-danger"><i class="ti ti-file-pdf"></i> View PDF: ' +
                        fileName + '</a>');
                } else if (fileType.startsWith('image/')) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#' + previewId).html('<img src="' + e.target.result +
                            '" width="80" class="rounded border">');
                    };
                    reader.readAsDataURL(file);
                }
                $(input).data('has-new-file', true);
            }
        }

        // Auto-generate slug from shop name
        $('#shop_name').on('keyup', function() {
            let slug = $(this).val().toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
            $('#shop_slug').val(slug);
        });

        // Country change - update phone code and load states
        $('#country_id').on('change', function() {
            let selectedOption = $(this).find(':selected');
            let phonecode = selectedOption.data('phonecode');

            if (phonecode) {
                $('#shop_phone_code').val(phonecode);
            }

            let countryId = $(this).val();
            if (countryId) {
                $.ajax({
                    url: '{{ route('get.states') }}',
                    type: 'GET',
                    data: {
                        country_id: countryId
                    },
                    success: function(response) {
                        let stateSelect = $('#state_id');
                        stateSelect.html('<option value="">Select State</option>');
                        $.each(response, function(key, state) {
                            stateSelect.append('<option value="' + state.id + '">' + state
                                .name + '</option>');
                        });
                    },
                    error: function(xhr) {
                        console.error('Error loading states:', xhr);
                    }
                });
            }
        });

        // Trigger on page load to set initial phone code
        if ($('#country_id').val()) {
            let initialPhonecode = $('#country_id').find(':selected').data('phonecode');
            if (initialPhonecode) {
                $('#shop_phone_code').val(initialPhonecode);
            }
        }

        $(document).ready(function() {
            let formSubmitting = false;
            let tabs = ['personal', 'shop', 'tax', 'bank', 'documents'];
            let currentPercentage = parseInt($('#completionPercentage').text()) || 0;
            updateButtonText(currentPercentage);
            // Function to update button text based on completion percentage
            function updateButtonText(percentage) {
                if (percentage >= 65) {
                    $('#submitBtn').html('<i class="ti ti-check"></i> Submit for Approval');
                } else {
                    $('#submitBtn').html('<i class="ti ti-save"></i> Save & Continue');
                }
            }

            $('.nav-link').on('click', function() {
                var tabId = $(this).attr('id').replace('-tab', '');
                $('#currentTab').val(tabId);
            });

            $('.step-item').on('click', function() {
                var tab = $(this).data('tab');
                $('#currentTab').val(tab);
            });

            function getCurrentTabIndex() {
                let activeTab = $('.tab-pane.active').attr('id');
                return tabs.indexOf(activeTab);
            }

            function showTabByIndex(index) {
                if (index >= 0 && index < tabs.length) {
                    let tabId = tabs[index];
                    $('.tab-pane').removeClass('show active');
                    $('#' + tabId).addClass('show active');
                    $('.nav-link').removeClass('active');
                    $('#' + tabId + '-tab').addClass('active');
                    $('#currentTab').val(tabId);

                    // Update button text based on current percentage
                    let currentPercentage = parseInt($('#completionPercentage').text()) || 0;
                    updateButtonText(currentPercentage);

                    $('#prevBtn').toggle(index > 0);
                }
            }

            $('#prevBtn').on('click', function() {
                let currentIndex = getCurrentTabIndex();
                if (currentIndex > 0) {
                    showTabByIndex(currentIndex - 1);
                }
            });

            $('.document-input').each(function() {
                let hasExisting = $(this).siblings('.mt-2').find('.badge.bg-success').length > 0;
                if (hasExisting) {
                    $(this).data('uploaded', true);
                }
            });

            $('#profileForm').on('submit', function(e) {
                e.preventDefault();
                if (formSubmitting) return false;

                let currentTabValue = $('#currentTab').val();
                let isValid = true;
                let firstInvalid = null;

                $('#' + currentTabValue + ' [data-required="true"]').each(function() {
                    let $field = $(this);
                    let fieldType = $field.attr('type');
                    let fieldValue = $field.val();
                    let tagName = $field.prop('tagName');

                    $field.removeClass('is-invalid');
                    $field.next('.invalid-feedback').remove();

                    if (fieldType === 'file') {
                        let hasExisting = $field.data('has-existing') === true || $field.data(
                            'has-existing') === 'true';
                        let hasNewFile = $field.data('has-new-file') === true;

                        // Check if there's an existing file or a new file uploaded
                        if (!hasExisting && !hasNewFile && (!fieldValue || fieldValue === '')) {
                            $field.addClass('is-invalid');
                            $field.after(
                                '<div class="invalid-feedback d-block">This field is required</div>'
                            );
                            isValid = false;
                            if (!firstInvalid) firstInvalid = $field;
                        }
                    } else if (tagName === 'SELECT' && $field.prop('multiple')) {
                        if (!fieldValue || fieldValue.length === 0) {
                            $field.addClass('is-invalid');
                            $field.after(
                                '<div class="invalid-feedback d-block">Please select at least one category</div>'
                            );
                            isValid = false;
                            if (!firstInvalid) firstInvalid = $field;
                        }
                    } else {
                        if (!fieldValue || fieldValue.toString().trim() === '') {
                            $field.addClass('is-invalid');
                            $field.after(
                                '<div class="invalid-feedback d-block">This field is required</div>'
                            );
                            isValid = false;
                            if (!firstInvalid) firstInvalid = $field;
                        }
                    }
                });

                if (!isValid) {
                    if (firstInvalid) {
                        $('html, body').animate({
                            scrollTop: firstInvalid.offset().top - 150
                        }, 500);
                        firstInvalid.focus();
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        text: 'Please fill all required fields',
                        confirmButtonColor: '#d33'
                    });
                    return false;
                }

                formSubmitting = true;
                let formData = new FormData(this);
                let btn = $('#submitBtn');
                let originalText = btn.html();
                btn.html('<span class="spinner-border spinner-border-sm me-1"></span> Saving...');
                btn.prop('disabled', true);
                formData.set('tab', $('#currentTab').val());

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#progressBar').css('width', response.completion_percentage +
                                '%');
                            $('#completionPercentage').text(response.completion_percentage);

                            // Update button text based on new percentage
                            updateButtonText(response.completion_percentage);

                            $('input[type="file"][data-required="true"]').each(function() {
                                if ($(this).val()) $(this).data('uploaded', true);
                            });

                            Swal.fire({
                                    icon: 'success',
                                    title: 'Success!',
                                    text: response.message,
                                    timer: 1500,
                                    showConfirmButton: false
                                })
                                .then(() => {
                                    if (response.is_complete) {
                                        window.location.href = response.redirect_url;
                                    } else if (response.next_tab) {
                                        let nextTabIndex = tabs.indexOf(response.next_tab);
                                        if (nextTabIndex !== -1) showTabByIndex(
                                            nextTabIndex);
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
                                    input.after(
                                        '<div class="invalid-feedback d-block">' +
                                        messages[0] + '</div>');
                                }
                            });
                            Swal.fire({
                                icon: 'error',
                                title: 'Validation Error',
                                text: 'Please check the form for errors',
                                confirmButtonColor: '#d33'
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: xhr.responseJSON?.message ||
                                    'Something went wrong.',
                                confirmButtonColor: '#d33'
                            });
                        }
                    }
                });
            });

            $('input, select, textarea').on('input change', function() {
                $(this).removeClass('is-invalid');
                $(this).next('.invalid-feedback').remove();
            });
        });
    </script>
@endpush

@push('styles')
    <style>
        .nav-tabs .nav-link {
            color: #6c757d;
            border: none;
            padding: 10px 20px;
        }

        .nav-tabs .nav-link.active {
            color: #0d6efd;
            border-bottom: 2px solid #0d6efd;
            background: transparent;
        }

        .progress {
            border-radius: 10px;
        }

        .progress-bar {
            transition: width 0.3s ease;
        }

        .step-indicators {
            margin-bottom: 30px;
        }

        .step-item {
            cursor: pointer;
            position: relative;
        }

        .step-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e9ecef;
            border: 2px solid #dee2e6;
            color: #6c757d;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 8px;
            font-weight: bold;
            transition: all 0.3s;
        }

        .step-circle.completed {
            background: #198754;
            border-color: #198754;
            color: white;
        }

        .step-label {
            font-size: 12px;
            color: #6c757d;
        }

        .step-line {
            height: 2px;
            background: #dee2e6;
            margin: 0 -10px;
            margin-top: 20px;
        }

        .choices {
            margin-bottom: 0;
        }

        .choices__inner {
            background-color: #fff;
            border-radius: 0.375rem;
            min-height: 38px;
        }

        .choices__input {
            background-color: #fff;
        }

        .form-control.is-invalid,
        .choices.is-invalid .choices__inner {
            border-color: #dc3545;
        }
    </style>
@endpush
