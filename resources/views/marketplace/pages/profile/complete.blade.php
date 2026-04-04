{{-- resources/views/vendor/profile/complete.blade.php --}}

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
                                    <span class="badge bg-primary fs-13">Profile Completion:
                                        {{ $completionPercentage ?? 0 }}%</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form id="profileForm" method="POST" action="{{ route('vendor.profile.update') }}"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                {{-- Progress Bar --}}
                                <div class="mb-4">
                                    <div class="progress" style="height: 10px;">
                                        <div class="progress-bar bg-success"
                                            style="width: {{ $completionPercentage ?? 0 }}%"></div>
                                    </div>
                                </div>

                                {{-- Tabs --}}
                                <ul class="nav nav-tabs" id="profileTabs" role="tablist">
                                    <li class="nav-item"><button class="nav-link active" id="personal-tab"
                                            data-bs-toggle="tab" data-bs-target="#personal" type="button"><i
                                                class="ti ti-user"></i> Personal Info</button></li>
                                    <li class="nav-item"><button class="nav-link" id="shop-tab" data-bs-toggle="tab"
                                            data-bs-target="#shop" type="button"><i class="ti ti-building-store"></i> Shop
                                            Info</button></li>
                                    <li class="nav-item"><button class="nav-link" id="tax-tab" data-bs-toggle="tab"
                                            data-bs-target="#tax" type="button"><i class="ti ti-file-invoice"></i> Tax
                                            Info</button></li>
                                    <li class="nav-item"><button class="nav-link" id="bank-tab" data-bs-toggle="tab"
                                            data-bs-target="#bank" type="button"><i class="ti ti-wallet"></i> Bank
                                            Info</button></li>
                                    <li class="nav-item"><button class="nav-link" id="documents-tab" data-bs-toggle="tab"
                                            data-bs-target="#documents" type="button"><i class="ti ti-file"></i>
                                            Documents</button></li>
                                </ul>

                                <div class="tab-content mt-4">

                                    {{-- ========== PERSONAL INFO TAB ========== --}}
                                    <div class="tab-pane fade show active" id="personal" role="tabpanel">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Full Name <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" name="name" class="form-control"
                                                        value="{{ old('name', $vendor->name) }}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Email <span
                                                            class="text-danger">*</span></label>
                                                    <input type="email" name="email" class="form-control"
                                                        value="{{ old('email', $vendor->email) }}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Phone</label>
                                                    <input type="text" name="phone" class="form-control"
                                                        value="{{ old('phone', $vendor->phone) }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Profile Avatar</label>
                                                    <input type="file" name="avatar" class="form-control"
                                                        accept="image/*">
                                                    @if ($vendor->avatar)
                                                        <img src="{{ Storage::url($vendor->avatar) }}" width="60"
                                                            class="mt-2 rounded">
                                                    @endif
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
                                                    <input type="text" name="shop_name" class="form-control"
                                                        value="{{ old('shop_name', $vendor->shop_name) }}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Shop Email <span
                                                            class="text-danger">*</span></label>
                                                    <input type="email" name="shop_email" class="form-control"
                                                        value="{{ old('shop_email', $vendor->shop_email) }}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Shop Phone <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" name="shop_phone" class="form-control"
                                                        value="{{ old('shop_phone', $vendor->shop_phone) }}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Shop WhatsApp</label>
                                                    <input type="text" name="shop_whatsapp" class="form-control"
                                                        value="{{ old('shop_whatsapp', $vendor->shop_whatsapp) }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Shop Website</label>
                                                    <input type="url" name="shop_website" class="form-control"
                                                        value="{{ old('shop_website', $vendor->shop_website) }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Vendor Type</label>
                                                    <select name="vendor_type" class="form-select">
                                                        <option value="third_party"
                                                            {{ $vendor->vendor_type == 'third_party' ? 'selected' : '' }}>
                                                            Third Party</option>
                                                        <option value="own_store"
                                                            {{ $vendor->vendor_type == 'own_store' ? 'selected' : '' }}>Own
                                                            Store</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Business Type</label>
                                                    <select name="business_type" class="form-select">
                                                        <option value="">Select</option>
                                                        <option value="sole_proprietorship"
                                                            {{ $vendor->business_type == 'sole_proprietorship' ? 'selected' : '' }}>
                                                            Sole Proprietorship</option>
                                                        <option value="partnership"
                                                            {{ $vendor->business_type == 'partnership' ? 'selected' : '' }}>
                                                            Partnership</option>
                                                        <option value="llc"
                                                            {{ $vendor->business_type == 'llc' ? 'selected' : '' }}>LLC
                                                        </option>
                                                        <option value="private_limited"
                                                            {{ $vendor->business_type == 'private_limited' ? 'selected' : '' }}>
                                                            Private Limited</option>
                                                        <option value="public_limited"
                                                            {{ $vendor->business_type == 'public_limited' ? 'selected' : '' }}>
                                                            Public Limited</option>
                                                        <option value="trust"
                                                            {{ $vendor->business_type == 'trust' ? 'selected' : '' }}>Trust
                                                        </option>
                                                        <option value="other"
                                                            {{ $vendor->business_type == 'other' ? 'selected' : '' }}>Other
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="mb-3">
                                                    <label class="form-label">Shop Description</label>
                                                    <textarea name="shop_description" class="form-control" rows="3">{{ old('shop_description', $vendor->shop_description) }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Shop Logo</label>
                                                    <input type="file" name="shop_logo" class="form-control"
                                                        accept="image/*">
                                                    @if ($vendor->shop_logo)
                                                        <img src="{{ Storage::url($vendor->shop_logo) }}" width="80"
                                                            class="mt-2">
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Shop Banner</label>
                                                    <input type="file" name="shop_banner" class="form-control"
                                                        accept="image/*">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Shop Address <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" name="shop_address" class="form-control"
                                                        value="{{ old('shop_address', $vendor->shop_address) }}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="mb-3">
                                                    <label class="form-label">City <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" name="shop_city" class="form-control"
                                                        value="{{ old('shop_city', $vendor->shop_city) }}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="mb-3">
                                                    <label class="form-label">State <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" name="shop_state" class="form-control"
                                                        value="{{ old('shop_state', $vendor->shop_state) }}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="mb-3">
                                                    <label class="form-label">Country <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" name="shop_country" class="form-control"
                                                        value="{{ old('shop_country', $vendor->shop_country) }}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="mb-3">
                                                    <label class="form-label">Postal Code <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" name="shop_postal_code" class="form-control"
                                                        value="{{ old('shop_postal_code', $vendor->shop_postal_code) }}"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Accepts COD</label>
                                                    <select name="accepts_cod" class="form-select">
                                                        <option value="1"
                                                            {{ $vendor->accepts_cod ? 'selected' : '' }}>Yes</option>
                                                        <option value="0"
                                                            {{ !$vendor->accepts_cod ? 'selected' : '' }}>No</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Commission Rate (%)</label>
                                                    <input type="number" name="commission_rate" class="form-control"
                                                        value="{{ old('commission_rate', $vendor->commission_rate) }}">
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
                                                    <label class="form-label">GST Number</label>
                                                    <input type="text" name="gst_number" class="form-control"
                                                        value="{{ old('gst_number', $vendor->taxInfo->gst_number ?? '') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">GST Type</label>
                                                    <select name="gst_type" class="form-select">
                                                        <option value="">Select</option>
                                                        <option value="regular"
                                                            {{ ($vendor->taxInfo->gst_type ?? '') == 'regular' ? 'selected' : '' }}>
                                                            Regular</option>
                                                        <option value="composition"
                                                            {{ ($vendor->taxInfo->gst_type ?? '') == 'composition' ? 'selected' : '' }}>
                                                            Composition</option>
                                                        <option value="casual"
                                                            {{ ($vendor->taxInfo->gst_type ?? '') == 'casual' ? 'selected' : '' }}>
                                                            Casual</option>
                                                        <option value="unregistered"
                                                            {{ ($vendor->taxInfo->gst_type ?? '') == 'unregistered' ? 'selected' : '' }}>
                                                            Unregistered</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">GST Registration Date</label>
                                                    <input type="date" name="gst_registration_date"
                                                        class="form-control"
                                                        value="{{ old('gst_registration_date', $vendor->taxInfo->gst_registration_date ?? '') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">GST Certificate</label>
                                                    <input type="file" name="gst_certificate" class="form-control"
                                                        accept=".pdf,.jpg,.jpeg,.png">
                                                </div>
                                            </div>

                                            <h6 class="border-bottom pb-2 mb-3 mt-3">PAN Information</h6>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">PAN Number</label>
                                                    <input type="text" name="pan_number" class="form-control"
                                                        value="{{ old('pan_number', $vendor->taxInfo->pan_number ?? '') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">PAN Holder Name</label>
                                                    <input type="text" name="pan_holder_name" class="form-control"
                                                        value="{{ old('pan_holder_name', $vendor->taxInfo->pan_holder_name ?? '') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">PAN Card Document</label>
                                                    <input type="file" name="pan_card_document" class="form-control"
                                                        accept=".pdf,.jpg,.jpeg,.png">
                                                </div>
                                            </div>

                                            <h6 class="border-bottom pb-2 mb-3 mt-3">International Tax</h6>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label">VAT Number</label>
                                                    <input type="text" name="vat_number" class="form-control"
                                                        value="{{ old('vat_number', $vendor->taxInfo->vat_number ?? '') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label">EIN Number</label>
                                                    <input type="text" name="ein_number" class="form-control"
                                                        value="{{ old('ein_number', $vendor->taxInfo->ein_number ?? '') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label">Tax ID</label>
                                                    <input type="text" name="tax_id" class="form-control"
                                                        value="{{ old('tax_id', $vendor->taxInfo->tax_id ?? '') }}">
                                                </div>
                                            </div>

                                            <h6 class="border-bottom pb-2 mb-3 mt-3">Business Registration</h6>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Business Registration Number</label>
                                                    <input type="text" name="business_registration_number"
                                                        class="form-control"
                                                        value="{{ old('business_registration_number', $vendor->taxInfo->business_registration_number ?? '') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Business License Number</label>
                                                    <input type="text" name="business_license_number"
                                                        class="form-control"
                                                        value="{{ old('business_license_number', $vendor->taxInfo->business_license_number ?? '') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Business Registration Date</label>
                                                    <input type="date" name="business_registration_date"
                                                        class="form-control"
                                                        value="{{ old('business_registration_date', $vendor->taxInfo->business_registration_date ?? '') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Business Registration Certificate</label>
                                                    <input type="file" name="business_registration_certificate"
                                                        class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- ========== BANK INFO TAB ========== --}}
                                    <div class="tab-pane fade" id="bank" role="tabpanel">
                                        <div class="row">
                                            <h6 class="border-bottom pb-2 mb-3">Bank Account Details</h6>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Account Holder Name <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" name="account_holder_name" class="form-control"
                                                        value="{{ old('account_holder_name', $vendor->bankInfo->account_holder_name ?? '') }}"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Account Number <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" name="account_number" class="form-control"
                                                        value="{{ old('account_number', $vendor->bankInfo->account_number ?? '') }}"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Bank Name <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" name="bank_name" class="form-control"
                                                        value="{{ old('bank_name', $vendor->bankInfo->bank_name ?? '') }}"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Bank Branch <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" name="bank_branch" class="form-control"
                                                        value="{{ old('bank_branch', $vendor->bankInfo->bank_branch ?? '') }}"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label">IFSC Code</label>
                                                    <input type="text" name="ifsc_code" class="form-control"
                                                        value="{{ old('ifsc_code', $vendor->bankInfo->ifsc_code ?? '') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label">SWIFT Code</label>
                                                    <input type="text" name="swift_code" class="form-control"
                                                        value="{{ old('swift_code', $vendor->bankInfo->swift_code ?? '') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label">Routing Number</label>
                                                    <input type="text" name="routing_number" class="form-control"
                                                        value="{{ old('routing_number', $vendor->bankInfo->routing_number ?? '') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">IBAN Number</label>
                                                    <input type="text" name="iban_number" class="form-control"
                                                        value="{{ old('iban_number', $vendor->bankInfo->iban_number ?? '') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Bank Address</label>
                                                    <input type="text" name="bank_address" class="form-control"
                                                        value="{{ old('bank_address', $vendor->bankInfo->bank_address ?? '') }}">
                                                </div>
                                            </div>

                                            <h6 class="border-bottom pb-2 mb-3 mt-3">Digital Payments</h6>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">UPI ID</label>
                                                    <input type="text" name="upi_id" class="form-control"
                                                        value="{{ old('upi_id', $vendor->bankInfo->upi_id ?? '') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">PayPal Email</label>
                                                    <input type="email" name="paypal_email" class="form-control"
                                                        value="{{ old('paypal_email', $vendor->bankInfo->paypal_email ?? '') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Stripe Account ID</label>
                                                    <input type="text" name="stripe_account_id" class="form-control"
                                                        value="{{ old('stripe_account_id', $vendor->bankInfo->stripe_account_id ?? '') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Razorpay Account ID</label>
                                                    <input type="text" name="razorpay_account_id" class="form-control"
                                                        value="{{ old('razorpay_account_id', $vendor->bankInfo->razorpay_account_id ?? '') }}">
                                                </div>
                                            </div>

                                            <h6 class="border-bottom pb-2 mb-3 mt-3">Bank Documents</h6>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Cancelled Cheque</label>
                                                    <input type="file" name="cancelled_cheque" class="form-control"
                                                        accept=".pdf,.jpg,.jpeg,.png">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Bank Statement</label>
                                                    <input type="file" name="bank_statement" class="form-control"
                                                        accept=".pdf,.jpg,.jpeg,.png">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- ========== DOCUMENTS TAB ========== --}}
                                    <div class="tab-pane fade" id="documents" role="tabpanel">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">PAN Card Copy</label>
                                                    <input type="file" name="documents[pan_card]" class="form-control"
                                                        accept=".pdf,.jpg,.jpeg,.png">
                                                    <small class="text-muted">PDF, JPG, PNG (Max 2MB)</small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">GST Certificate</label>
                                                    <input type="file" name="documents[gst_certificate]"
                                                        class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Cancelled Cheque</label>
                                                    <input type="file" name="documents[cancelled_cheque]"
                                                        class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Bank Statement</label>
                                                    <input type="file" name="documents[bank_statement]"
                                                        class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Business Registration</label>
                                                    <input type="file" name="documents[business_registration]"
                                                        class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Business License</label>
                                                    <input type="file" name="documents[business_license]"
                                                        class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Address Proof</label>
                                                    <input type="file" name="documents[address_proof]"
                                                        class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Identity Proof</label>
                                                    <input type="file" name="documents[identity_proof]"
                                                        class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Trade License</label>
                                                    <input type="file" name="documents[trade_license]"
                                                        class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Other Documents</label>
                                                    <input type="file" name="documents[other]" class="form-control"
                                                        accept=".pdf,.jpg,.jpeg,.png">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="alert alert-danger mt-2">
                                            <i class="ti ti-alert-circle"></i> All documents must be clear and valid. Fake
                                            documents will lead to rejection.
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                                    <a href="{{ route('vendor.dashboard') }}" class="btn btn-danger">Cancel</a>
                                    <button type="submit" class="btn btn-primary" id="submitBtn">Save &
                                        Continue</button>
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
        $(document).ready(function() {
            let formSubmitting = false;

            $('#profileForm').on('submit', function(e) {
                e.preventDefault();
                if (formSubmitting) return false;

                let formData = new FormData(this);
                formSubmitting = true;
                let btn = $('#submitBtn');
                let originalText = btn.html();
                btn.html('<span class="spinner-border spinner-border-sm me-1"></span> Saving...');
                btn.prop('disabled', true);

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
                            Swal.fire({
                                    icon: 'success',
                                    title: 'Success!',
                                    text: response.message,
                                    timer: 1500,
                                    showConfirmButton: false
                                })
                                .then(() => {
                                    window.location.href =
                                        '{{ route('vendor.dashboard') }}';
                                });
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function(field, messages) {
                                $('[name="' + field + '"]').addClass('is-invalid');
                                $('[name="' + field + '"]').after(
                                    '<div class="invalid-feedback d-block">' +
                                    messages[0] + '</div>');
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
                    },
                    complete: function() {
                        formSubmitting = false;
                        btn.html(originalText);
                        btn.prop('disabled', false);
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
    </style>
@endpush
