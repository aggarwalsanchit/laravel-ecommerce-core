{{-- resources/views/admin/pages/vendors/show.blade.php --}}

@extends('management.layouts.app')

@section('title', 'Shop Details - ' . $shop->shop_name)

@section('content')
    <div class="page-content">
        <div class="page-container">

            {{-- Shop Banner --}}
            <div class="card mb-4">
                <div class="position-relative">
                    @if ($shop->shop_banner && Storage::disk('public')->exists($shop->shop_banner))
                        <img src="{{ Storage::url($shop->shop_banner) }}" alt="{{ $shop->shop_name }}" class="img-fluid w-100"
                            style="height: 200px; object-fit: cover; border-radius: 10px 10px 0 0;">
                    @else
                        <div class="bg-primary w-100 d-flex align-items-center justify-content-center"
                            style="height: 200px; border-radius: 10px 10px 0 0; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <h1 class="text-white">{{ $shop->shop_name }}</h1>
                        </div>
                    @endif

                    {{-- Shop Logo Overlay --}}
                    <div class="position-absolute bottom-0 start-0 translate-middle-y ms-4">
                        <div class="border border-4 border-white rounded-circle bg-white shadow-sm"
                            style="width: 100px; height: 100px;">
                            @if ($shop->shop_logo && Storage::disk('public')->exists($shop->shop_logo))
                                <img src="{{ Storage::url($shop->shop_logo) }}" alt="{{ $shop->shop_name }}"
                                    class="rounded-circle w-100 h-100" style="object-fit: cover;">
                            @else
                                <div
                                    class="bg-primary rounded-circle w-100 h-100 d-flex align-items-center justify-content-center text-white fs-1 fw-bold">
                                    {{ strtoupper(substr($shop->shop_name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Shop Header Info --}}
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h2 class="mb-2">{{ $shop->shop_name }}</h2>
                            <p class="text-muted mb-2">
                                <i class="ti ti-mail me-1"></i> {{ $shop->shop_email }} &nbsp;&nbsp;
                                <i class="ti ti-phone me-1"></i> +{{ $shop->shop_phone_code ?? '+91' }}
                                {{ $shop->shop_phone }}
                            </p>
                            <p class="text-muted mb-2">
                                <i class="ti ti-map-pin me-1"></i> {{ $shop->shop_address }}, {{ $shop->shop_city }},
                                {{ $shop->shop_state }}
                                @if ($shop->shop_postal_code)
                                    - {{ $shop->shop_postal_code }}
                                @endif
                            </p>
                            <p class="text-muted">
                                <i class="ti ti-world me-1"></i>
                                @if ($shop->shop_state)
                                    {{ $shop->state->name }},
                                @else
                                    {{ $shop->shop_state ?? 'N/A' }},
                                @endif
                                @if ($shop->shop_country)
                                    {{ $shop->country->name }}
                                @else
                                    {{ $shop->shop_country ?? 'N/A' }}
                                @endif
                            </p>
                            @if ($shop->shop_website)
                                <p><i class="ti ti-world me-1"></i> <a href="{{ $shop->shop_website }}"
                                        target="_blank">{{ $shop->shop_website }}</a></p>
                            @endif
                        </div>
                        <div class="col-md-4 text-md-end">
                            {{-- Action Buttons --}}
                            <div class="btn-group-vertical w-100">
                                <div class="btn-group mb-2">
                                    <button type="button" class="btn btn-success"
                                        onclick="showApproveModal({{ $shop->id }}, '{{ addslashes($shop->shop_name) }}')">
                                        <i class="ti ti-check"></i> Verify & Approve
                                    </button>
                                    <button type="button" class="btn btn-danger"
                                        onclick="showRejectModal({{ $shop->id }}, '{{ addslashes($shop->shop_name) }}')">
                                        <i class="ti ti-x"></i> Reject
                                    </button>
                                </div>
                                <button type="button" class="btn btn-warning"
                                    onclick="showSuspendModal({{ $shop->id }}, '{{ addslashes($shop->shop_name) }}')">
                                    <i class="ti ti-ban"></i> Suspend Shop
                                </button>
                                <a href="{{ route('admin.vendors.staff', $shop->id) }}" class="btn btn-info mt-2">
                                    <i class="ti ti-users"></i> View Staff Members
                                </a>
                                <a href="{{ route('admin.vendors.index') }}" class="btn btn-secondary mt-2">
                                    <i class="ti ti-arrow-left"></i> Back to Vendors
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                {{-- Left Column --}}
                <div class="col-md-4">
                    {{-- Shop Information --}}
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0"><i class="ti ti-building-store"></i> Shop Information</h5>
                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                data-bs-target="#vendorTypeModal">
                                <i class="ti ti-edit"></i> Change Type
                            </button>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <th width="45%">Shop Slug:</th>
                                    <td>{{ $shop->shop_slug ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Vendor Type:</th>
                                    <td>
                                        <span class="badge bg-secondary"
                                            id="vendorTypeBadge">{{ ucfirst(str_replace('_', ' ', $shop->vendor_type ?? 'N/A')) }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Business Type:</th>
                                    <td>{{ ucfirst(str_replace('_', ' ', $shop->business_type ?? 'N/A')) }}</td>
                                </tr>
                                <tr>
                                    <th>WhatsApp:</th>
                                    <td>{{ $shop->shop_whatsapp ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>COD Accepted:</th>
                                    <td>
                                        @if ($shop->accepts_cod)
                                            <span class="badge bg-success">Yes</span>
                                        @else
                                            <span class="badge bg-danger">No</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Commission Rate:</th>
                                    <td>{{ $shop->commission_rate ?? 10 }}%</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    {{-- Location Information --}}
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0"><i class="ti ti-map-pin"></i> Location Information</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <th width="45%">Country:</th>
                                    <td>
                                        @if ($shop->country)
                                            <span class="fw-semibold">{{ $shop->country->name }}</span>
                                            <small class="text-muted d-block">Code: {{ $shop->country->code }} | Phone:
                                                {{ $shop->country->phonecode }}</small>
                                        @else
                                            {{ $shop->shop_country ?? 'N/A' }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>State:</th>
                                    <td>
                                        @if ($shop->state)
                                            {{ $shop->state->name }}
                                        @else
                                            {{ $shop->shop_state ?? 'N/A' }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>City:</th>
                                    <td>{{ $shop->shop_city ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Postal Code:</th>
                                    <td>{{ $shop->shop_postal_code ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Phone Code:</th>
                                    <td>{{ $shop->shop_phone_code ?? '+91' }}</td>
                                </tr>
                                <tr>
                                    <th>Full Address:</th>
                                    <td>{{ $shop->shop_address ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    {{-- Owner Information --}}
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0"><i class="ti ti-user"></i> Owner Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="text-center mb-3">
                                @if ($shop->owner && $shop->owner->avatar)
                                    <img src="{{ Storage::url($shop->owner->avatar) }}" alt="{{ $shop->owner->name }}"
                                        class="rounded-circle" style="width: 80px; height: 80px; object-fit: cover;">
                                @else
                                    <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center text-white"
                                        style="width: 80px; height: 80px; font-size: 32px;">
                                        {{ substr($shop->owner->name ?? 'O', 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <th width="40%">Name:</th>
                                    <td>{{ $shop->owner->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td>{{ $shop->owner->email ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Phone:</th>
                                    <td>{{ $shop->owner->phone ?? 'N/A' }}
                                        (<strong>{{ $shop->owner->phone_code ?? '+91' }}</strong>)</td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        @if ($shop->owner && $shop->owner->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    {{-- Verification Status --}}
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0"><i class="ti ti-shield"></i> Verification Status</h5>
                        </div>
                        <div class="card-body">
                            <div class="text-center mb-3">
                                @if ($shop->account_status === 'verified')
                                    <span class="badge bg-success fs-14 p-2">✓ Verified</span>
                                @elseif($shop->account_status === 'pending')
                                    <span class="badge bg-warning fs-14 p-2">⏳ Pending Verification</span>
                                @elseif($shop->account_status === 'suspended')
                                    <span class="badge bg-warning fs-14 p-2">✗ Suspended</span>
                                @else
                                    <span class="badge bg-danger fs-14 p-2">✗ Rejected</span>
                                @endif
                            </div>
                            @if ($shop->verified_at)
                                <p class="text-muted mb-0"><small>Verified on:
                                        {{ $shop->verified_at->format('d M Y, h:i A') }}</small></p>
                            @endif
                            @if ($shop->verification_notes)
                                <p class="text-muted mt-2"><small>Notes: {{ $shop->verification_notes }}</small></p>
                            @endif
                        </div>
                    </div>

                    {{-- Profile Completion --}}
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0"><i class="ti ti-chart-line"></i> Profile Completion</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Completion Progress</span>
                                <span class="fw-bold">{{ $shop->profile_completed ?? 0 }}%</span>
                            </div>
                            <div class="progress mb-3" style="height: 10px;">
                                <div class="progress-bar bg-info" style="width: {{ $shop->profile_completed ?? 0 }}%">
                                </div>
                            </div>
                            @if ($shop->ready_for_approve)
                                <div class="alert alert-success mb-0">
                                    <i class="ti ti-check-circle"></i> This shop is ready for approval!
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Right Column --}}
                <div class="col-md-8">
                    {{-- Shop Description --}}
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0"><i class="ti ti-description"></i> Shop Description</h5>
                        </div>
                        <div class="card-body">
                            <p>{{ $shop->shop_description ?? 'No description provided.' }}</p>
                        </div>
                    </div>

                    {{-- Tax Information --}}
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0"><i class="ti ti-file-invoice"></i> Tax Information</h5>
                        </div>
                        <div class="card-body">
                            @if ($shop->taxInfo)
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6>GST Information</h6>
                                        <table class="table table-sm">
                                            <tr>
                                                <th width="45%">GST Number:</th>
                                                <td>{{ $shop->taxInfo->gst_number ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>GST Type:</th>
                                                <td>{{ ucfirst($shop->taxInfo->gst_type ?? 'N/A') }}</td>
                                            </tr>
                                            <tr>
                                                <th>GST Reg. Date:</th>
                                                <td>{{ $shop->taxInfo->gst_registration_date ? \Carbon\Carbon::parse($shop->taxInfo->gst_registration_date)->format('d M Y') : 'N/A' }}
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>PAN Information</h6>
                                        <table class="table table-sm">
                                            <tr>
                                                <th width="45%">PAN Number:</th>
                                                <td>{{ $shop->taxInfo->pan_number ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>PAN Holder:</th>
                                                <td>{{ $shop->taxInfo->pan_holder_name ?? 'N/A' }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6>International Tax</h6>
                                        <table class="table table-sm">
                                            <tr>
                                                <th width="45%">VAT Number:</th>
                                                <td>{{ $shop->taxInfo->vat_number ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>EIN Number:</th>
                                                <td>{{ $shop->taxInfo->ein_number ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Tax ID:</th>
                                                <td>{{ $shop->taxInfo->tax_id ?? 'N/A' }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>Business Registration</h6>
                                        <table class="table table-sm">
                                            <tr>
                                                <th width="45%">Reg. Number:</th>
                                                <td>{{ $shop->taxInfo->business_registration_number ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>License Number:</th>
                                                <td>{{ $shop->taxInfo->business_license_number ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Reg. Date:</th>
                                                <td>{{ $shop->taxInfo->business_registration_date ? \Carbon\Carbon::parse($shop->taxInfo->business_registration_date)->format('d M Y') : 'N/A' }}
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            @else
                                <p class="text-muted">No tax information provided.</p>
                            @endif
                        </div>
                    </div>

                    {{-- Bank Information --}}
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0"><i class="ti ti-wallet"></i> Bank Information</h5>
                        </div>
                        <div class="card-body">
                            @if ($shop->bankInfo)
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6>Account Details</h6>
                                        <table class="table table-sm">
                                            <tr>
                                                <th width="45%">Account Holder:</th>
                                                <td>{{ $shop->bankInfo->account_holder_name ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Account Number:</th>
                                                <td>{{ $shop->bankInfo->account_number ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Bank Name:</th>
                                                <td>{{ $shop->bankInfo->bank_name ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Bank Branch:</th>
                                                <td>{{ $shop->bankInfo->bank_branch ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>IFSC Code:</th>
                                                <td>{{ $shop->bankInfo->ifsc_code ?? 'N/A' }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>Additional Details</h6>
                                        <table class="table table-sm">
                                            <tr>
                                                <th width="45%">SWIFT Code:</th>
                                                <td>{{ $shop->bankInfo->swift_code ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>IBAN Number:</th>
                                                <td>{{ $shop->bankInfo->iban_number ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>UPI ID:</th>
                                                <td>{{ $shop->bankInfo->upi_id ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>PayPal Email:</th>
                                                <td>{{ $shop->bankInfo->paypal_email ?? 'N/A' }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            @else
                                <p class="text-muted">No bank information provided.</p>
                            @endif
                        </div>
                    </div>

                    {{-- Documents --}}
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0"><i class="ti ti-file"></i> Uploaded Documents</h5>
                        </div>
                        <div class="card-body">
                            @if ($shop->documents && $shop->documents->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Document Type</th>
                                                <th>Document Name</th>
                                                <th>Status</th>
                                                <th>Uploaded On</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($shop->documents as $document)
                                                <tr>
                                                    <td>
                                                        <span
                                                            class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $document->document_type)) }}</span>
                                                    </td>
                                                    <td>{{ $document->document_name }}</td>
                                                    <td>
                                                        @if ($document->verification_status === 'verified')
                                                            <span class="badge bg-success">Verified</span>
                                                        @elseif($document->verification_status === 'pending')
                                                            <span class="badge bg-warning">Pending</span>
                                                        @else
                                                            <span class="badge bg-danger">Rejected</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $document->created_at->format('d M Y') }}</td>
                                                    <td>
                                                        <a href="{{ Storage::url($document->document_path) }}"
                                                            target="_blank" class="btn btn-sm btn-outline-primary">
                                                            <i class="ti ti-eye"></i> View
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted">No documents uploaded.</p>
                            @endif
                        </div>
                    </div>

                    {{-- Categories --}}
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0"><i class="ti ti-category"></i> Business Categories</h5>
                        </div>
                        <div class="card-body">
                            @if ($shop->categories && $shop->categories->count() > 0)
                                @foreach ($shop->categories as $category)
                                    <span class="badge bg-primary fs-12 p-2 me-1 mb-1">{{ $category->name }}</span>
                                @endforeach
                            @else
                                <p class="text-muted">No categories selected.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Change Vendor Type Modal --}}
    <div class="modal fade" id="vendorTypeModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Change Vendor Type</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="vendorTypeShopId" value="{{ $shop->id }}">
                    <div class="mb-3">
                        <label class="form-label">Current Vendor Type</label>
                        <input type="text" class="form-control"
                            value="{{ ucfirst(str_replace('_', ' ', $shop->vendor_type ?? 'N/A')) }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">New Vendor Type</label>
                        <select id="newVendorType" class="form-select">
                            <option value="third_party" {{ $shop->vendor_type === 'third_party' ? 'selected' : '' }}>Third
                                Party</option>
                            <option value="own_store" {{ $shop->vendor_type === 'own_store' ? 'selected' : '' }}>Own Store
                            </option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="changeVendorType()">Update Vendor
                        Type</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Approve Modal with Verification Notes --}}
    <div class="modal fade" id="approveModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Verify & Approve Shop</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="approveShopId">
                    <div class="mb-3">
                        <label class="form-label">Shop Name</label>
                        <input type="text" id="approveShopName" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Verification Notes <span class="text-danger">*</span></label>
                        <textarea id="verificationNotes" class="form-control" rows="4"
                            placeholder="Enter verification notes (e.g., documents verified, business details confirmed, etc.)"></textarea>
                        <small class="text-muted">These notes will be saved and visible to the vendor</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" onclick="confirmApprove()">Approve Shop</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Reject Modal with Verification Notes --}}
    <div class="modal fade" id="rejectModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Reject Shop Application</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="rejectShopId">
                    <div class="mb-3">
                        <label class="form-label">Shop Name</label>
                        <input type="text" id="rejectShopName" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                        <textarea id="rejectionReason" class="form-control" rows="4"
                            placeholder="Enter reason for rejection (e.g., incomplete documents, invalid information, etc.)"></textarea>
                        <small class="text-muted">This reason will be shared with the vendor</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" onclick="confirmReject()">Reject Shop</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Suspend Modal with Suspension Reason --}}
    <div class="modal fade" id="suspendModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Suspend Shop</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="suspendShopId">
                    <div class="mb-3">
                        <label class="form-label">Shop Name</label>
                        <input type="text" id="suspendShopName" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Suspension Reason <span class="text-danger">*</span></label>
                        <textarea id="suspensionReason" class="form-control" rows="4"
                            placeholder="Enter reason for suspension (e.g., policy violation, fraudulent activity, etc.)"></textarea>
                        <small class="text-muted">This reason will be shared with the vendor</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-warning" onclick="confirmSuspend()">Suspend Shop</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Message Modal --}}
    <div class="modal fade" id="messageModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Send Message to Vendor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="messageShopId">
                    <div class="mb-3">
                        <label class="form-label">Message Type</label>
                        <select id="messageType" class="form-select">
                            <option value="general">General</option>
                            <option value="approval">Approval</option>
                            <option value="suspension">Suspension</option>
                            <option value="verification">Verification</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Message</label>
                        <textarea id="messageText" class="form-control" rows="4" placeholder="Enter your message..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="sendMessage()">Send Message</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Change Vendor Type
        function changeVendorType() {
            let shopId = $('#vendorTypeShopId').val();
            let newType = $('#newVendorType').val();
            let typeText = newType === 'own_store' ? 'Own Store' : 'Third Party';

            Swal.fire({
                title: 'Change Vendor Type',
                text: `Are you sure you want to change vendor type to "${typeText}"?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0d6efd',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, change it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Processing...',
                        text: 'Please wait',
                        allowOutsideClick: false,
                        didOpen: () => Swal.showLoading()
                    });
                    $.ajax({
                        url: '{{ url('admin/vendors') }}/' + shopId + '/change-type',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            vendor_type: newType
                        },
                        success: function(response) {
                            if (response.success) {
                                $('#vendorTypeBadge').text(typeText);
                                $('#vendorTypeModal').modal('hide');
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Updated!',
                                    text: response.message,
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: response.message
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Something went wrong.'
                            });
                        }
                    });
                }
            });
        }

        // Show Approve Modal
        function showApproveModal(shopId, shopName) {
            $('#approveShopId').val(shopId);
            $('#approveShopName').val(shopName);
            $('#verificationNotes').val('');
            $('#approveModal').modal('show');
        }

        // Confirm Approve
        function confirmApprove() {
            let shopId = $('#approveShopId').val();
            let notes = $('#verificationNotes').val();

            if (!notes) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Missing Notes',
                    text: 'Please enter verification notes.'
                });
                return;
            }

            Swal.fire({
                title: 'Processing...',
                text: 'Please wait',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            $.ajax({
                url: '{{ url('admin/vendors') }}/' + shopId + '/approve',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    verification_notes: notes
                },
                success: function(response) {
                    if (response.success) {
                        $('#approveModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Approved!',
                            text: response.message,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => location.reload());
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: response.message
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Something went wrong.'
                    });
                }
            });
        }

        // Show Reject Modal
        function showRejectModal(shopId, shopName) {
            $('#rejectShopId').val(shopId);
            $('#rejectShopName').val(shopName);
            $('#rejectionReason').val('');
            $('#rejectModal').modal('show');
        }

        // Confirm Reject
        function confirmReject() {
            let shopId = $('#rejectShopId').val();
            let reason = $('#rejectionReason').val();

            if (!reason) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Missing Reason',
                    text: 'Please enter rejection reason.'
                });
                return;
            }

            Swal.fire({
                title: 'Processing...',
                text: 'Please wait',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            $.ajax({
                url: '{{ url('admin/vendors') }}/' + shopId + '/reject',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    verification_notes: reason
                },
                success: function(response) {
                    if (response.success) {
                        $('#rejectModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Rejected!',
                            text: response.message,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => location.reload());
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: response.message
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Something went wrong.'
                    });
                }
            });
        }

        // Show Suspend Modal
        function showSuspendModal(shopId, shopName) {
            $('#suspendShopId').val(shopId);
            $('#suspendShopName').val(shopName);
            $('#suspensionReason').val('');
            $('#suspendModal').modal('show');
        }

        // Confirm Suspend
        function confirmSuspend() {
            let shopId = $('#suspendShopId').val();
            let reason = $('#suspensionReason').val();

            if (!reason) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Missing Reason',
                    text: 'Please provide a reason for suspension.'
                });
                return;
            }

            Swal.fire({
                title: 'Processing...',
                text: 'Please wait',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            $.ajax({
                url: '{{ url('admin/vendors') }}/' + shopId + '/suspend',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    reason: reason
                },
                success: function(response) {
                    if (response.success) {
                        $('#suspendModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Suspended!',
                            text: response.message,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => location.reload());
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: response.message
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Something went wrong.'
                    });
                }
            });
        }

        // Send Message
        function sendMessage() {
            let shopId = $('#messageShopId').val();
            let type = $('#messageType').val();
            let message = $('#messageText').val();

            if (!message) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Missing Message',
                    text: 'Please enter a message.'
                });
                return;
            }

            Swal.fire({
                title: 'Sending...',
                text: 'Please wait',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            $.ajax({
                url: '{{ url('admin/vendors') }}/' + shopId + '/send-message',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    type: type,
                    message: message
                },
                success: function(response) {
                    if (response.success) {
                        $('#messageModal').modal('hide');
                        $('#messageText').val('');
                        Swal.fire({
                            icon: 'success',
                            title: 'Sent!',
                            text: response.message,
                            timer: 1500,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: response.message
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Something went wrong.'
                    });
                }
            });
        }

        function showMessageModal(shopId, shopName) {
            $('#messageShopId').val(shopId);
            $('#messageModal').modal('show');
        }
    </script>
@endpush

@push('styles')
    <style>
        .bg-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
        }

        .table-sm td,
        .table-sm th {
            padding: 0.5rem;
        }

        .fs-14 {
            font-size: 14px;
        }

        .position-relative {
            position: relative;
        }

        .translate-middle-y {
            transform: translateY(-50%);
        }

        .ms-4 {
            margin-left: 1.5rem;
        }

        .border-white {
            border-color: #fff !important;
        }

        .shadow-sm {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
    </style>
@endpush
