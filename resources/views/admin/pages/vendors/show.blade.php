{{-- resources/views/admin/vendors/show.blade.php --}}

@extends('management.layouts.app')

@section('title', 'Vendor Details - ' . $vendor->shop_name)

@section('content')
<div class="page-content">
    <div class="page-container">
        <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
            <div class="flex-grow-1">
                <h4 class="fs-18 text-uppercase fw-bold mb-0">Vendor Details</h4>
                <p class="text-muted mb-0">View complete vendor information</p>
            </div>
            <div class="text-end">
                <ol class="breadcrumb m-0 py-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.vendors.index') }}">Vendors</a></li>
                    <li class="breadcrumb-item active">{{ $vendor->shop_name }}</li>
                </ol>
            </div>
        </div>

        {{-- Vendor Header Card --}}
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-auto">
                        @if($vendor->shop_logo)
                            <img src="{{ Storage::url($vendor->shop_logo) }}" alt="Logo" width="80" class="rounded">
                        @else
                            <div class="avatar-lg bg-light rounded d-flex align-items-center justify-content-center">
                                <i class="ti ti-building-store fs-40 text-muted"></i>
                            </div>
                        @endif
                    </div>
                    <div class="col">
                        <div class="d-flex flex-wrap align-items-center gap-2">
                            <h3 class="mb-0">{{ $vendor->shop_name }}</h3>
                            @if($vendor->hasRole('store_owner'))
                                <span class="badge bg-success">Store Owner (Approved)</span>
                            @elseif($vendor->hasRole('vendor'))
                                <span class="badge bg-warning">Vendor (Pending Approval)</span>
                            @endif
                            @if($vendor->account_status == 'active')
                                <span class="badge bg-success">Active</span>
                            @elseif($vendor->account_status == 'pending')
                                <span class="badge bg-warning">Pending</span>
                            @elseif($vendor->account_status == 'suspended')
                                <span class="badge bg-danger">Suspended</span>
                            @elseif($vendor->account_status == 'rejected')
                                <span class="badge bg-dark">Rejected</span>
                            @endif
                        </div>
                        <p class="text-muted mb-0 mt-2">
                            <i class="ti ti-mail"></i> {{ $vendor->shop_email }} &nbsp;|&nbsp;
                            <i class="ti ti-phone"></i> {{ $vendor->shop_phone }} &nbsp;|&nbsp;
                            <i class="ti ti-calendar"></i> Joined {{ $vendor->created_at->format('d M Y') }}
                        </p>
                    </div>
                    <div class="col-auto">
                        <div class="d-flex gap-2">
                            @if($vendor->hasRole('vendor') && $profileCompletion >= 80)
                                <a href="{{ route('admin.vendors.approve.form', $vendor->id) }}" class="btn btn-success">
                                    <i class="ti ti-check"></i> Approve & Upgrade
                                </a>
                            @endif
                            
                            @if($vendor->account_status == 'suspended')
                                <a href="{{ route('admin.vendors.activate', $vendor->id) }}" class="btn btn-success" onclick="return confirm('Activate this vendor?')">
                                    <i class="ti ti-player-play"></i> Activate
                                </a>
                            @endif
                            
                            @if($vendor->account_status == 'active')
                                <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#suspendModal">
                                    <i class="ti ti-pause"></i> Suspend
                                </button>
                            @endif
                            
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                <i class="ti ti-x"></i> Reject
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Profile Completion --}}
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Profile Completion</h5>
            </div>
            <div class="card-body">
                <div class="progress mb-2" style="height: 10px;">
                    <div class="progress-bar bg-success" style="width: {{ $profileCompletion }}%"></div>
                </div>
                <div class="d-flex justify-content-between">
                    <span>{{ $profileCompletion }}% Complete</span>
                    <span>
                        @if($profileCompletion >= 80)
                            <span class="text-success">✓ Ready for approval</span>
                        @else
                            <span class="text-warning">⚠ Need more information</span>
                        @endif
                    </span>
                </div>
            </div>
        </div>

        {{-- Tabs for Vendor Information --}}
        <ul class="nav nav-tabs" id="vendorTabs" role="tablist">
            <li class="nav-item">
                <button class="nav-link active" id="basic-tab" data-bs-toggle="tab" data-bs-target="#basic" type="button">
                    <i class="ti ti-building-store"></i> Basic Info
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="tax-tab" data-bs-toggle="tab" data-bs-target="#tax" type="button">
                    <i class="ti ti-file-invoice"></i> Tax Info
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="bank-tab" data-bs-toggle="tab" data-bs-target="#bank" type="button">
                    <i class="ti ti-wallet"></i> Bank Info
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="documents-tab" data-bs-toggle="tab" data-bs-target="#documents" type="button">
                    <i class="ti ti-file"></i> Documents
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="products-tab" data-bs-toggle="tab" data-bs-target="#products" type="button">
                    <i class="ti ti-package"></i> Products
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="categories-tab" data-bs-toggle="tab" data-bs-target="#categories" type="button">
                    <i class="ti ti-category"></i> Categories
                </button>
            </li>
        </ul>

        <div class="tab-content mt-4">
            
            {{-- Basic Info Tab --}}
            <div class="tab-pane fade show active" id="basic" role="tabpanel">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="border-bottom pb-2 mb-3">Personal Information</h6>
                                <table class="table table-borderless">
                                    <tr>
                                        <th width="35%">Owner Name:</th>
                                        <td>{{ $vendor->name ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Email:</th>
                                        <td>{{ $vendor->email ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Phone:</th>
                                        <td>{{ $vendor->phone ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Avatar:</th>
                                        <td>
                                            @if($vendor->avatar)
                                                <img src="{{ Storage::url($vendor->avatar) }}" width="50" class="rounded">
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h6 class="border-bottom pb-2 mb-3">Shop Information</h6>
                                <table class="table table-borderless">
                                    <tr>
                                        <th width="35%">Shop Name:</th>
                                        <td>{{ $vendor->shop_name ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Shop Slug:</th>
                                        <td>{{ $vendor->shop_slug ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Shop Email:</th>
                                        <td>{{ $vendor->shop_email ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Shop Phone:</th>
                                        <td>{{ $vendor->shop_phone ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Shop WhatsApp:</th>
                                        <td>{{ $vendor->shop_whatsapp ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Shop Website:</th>
                                        <td>{{ $vendor->shop_website ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Vendor Type:</th>
                                        <td>{{ ucfirst(str_replace('_', ' ', $vendor->vendor_type ?? 'N/A')) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Business Type:</th>
                                        <td>{{ ucfirst(str_replace('_', ' ', $vendor->business_type ?? 'N/A')) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Commission Rate:</th>
                                        <td>{{ $vendor->commission_rate ?? 10 }}%</td>
                                    </tr>
                                    <tr>
                                        <th>Accepts COD:</th>
                                        <td>{{ $vendor->accepts_cod ? 'Yes' : 'No' }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-12">
                                <h6 class="border-bottom pb-2 mb-3">Shop Description</h6>
                                <p>{{ $vendor->shop_description ?? 'No description provided' }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6 class="border-bottom pb-2 mb-3">Shop Address</h6>
                                <p>
                                    {{ $vendor->shop_address ?? 'N/A' }}<br>
                                    {{ $vendor->shop_city ?? '' }}, {{ $vendor->shop_state ?? '' }}<br>
                                    {{ $vendor->shop_country ?? '' }} - {{ $vendor->shop_postal_code ?? '' }}
                                </p>
                            </div>
                            <div class="col-md-6">
                                <h6 class="border-bottom pb-2 mb-3">Shop Media</h6>
                                <div class="d-flex gap-3">
                                    @if($vendor->shop_logo)
                                        <div>
                                            <label class="text-muted small">Shop Logo</label><br>
                                            <img src="{{ Storage::url($vendor->shop_logo) }}" width="80" class="rounded border">
                                        </div>
                                    @endif
                                    @if($vendor->shop_banner)
                                        <div>
                                            <label class="text-muted small">Shop Banner</label><br>
                                            <img src="{{ Storage::url($vendor->shop_banner) }}" width="150" class="rounded border">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tax Info Tab --}}
            <div class="tab-pane fade" id="tax" role="tabpanel">
                <div class="card">
                    <div class="card-body">
                        @if($vendor->taxInfo)
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="border-bottom pb-2 mb-3">GST Information</h6>
                                    <table class="table table-borderless">
                                        <tr>
                                            <th width="40%">GST Number:</th>
                                            <td>{{ $vendor->taxInfo->gst_number ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>GST Type:</th>
                                            <td>{{ ucfirst($vendor->taxInfo->gst_type ?? 'N/A') }}</td>
                                        </tr>
                                        <tr>
                                            <th>GST Registration Date:</th>
                                            <td>{{ $vendor->taxInfo->gst_registration_date ?? 'N/A' }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="border-bottom pb-2 mb-3">PAN Information</h6>
                                    <table class="table table-borderless">
                                        <tr>
                                            <th width="40%">PAN Number:</th>
                                            <td>{{ $vendor->taxInfo->pan_number ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>PAN Holder Name:</th>
                                            <td>{{ $vendor->taxInfo->pan_holder_name ?? 'N/A' }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="border-bottom pb-2 mb-3">International Tax</h6>
                                    <table class="table table-borderless">
                                        <tr>
                                            <th width="40%">VAT Number:</th>
                                            <td>{{ $vendor->taxInfo->vat_number ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>EIN Number:</th>
                                            <td>{{ $vendor->taxInfo->ein_number ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Tax ID:</th>
                                            <td>{{ $vendor->taxInfo->tax_id ?? 'N/A' }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="border-bottom pb-2 mb-3">Business Registration</h6>
                                    <table class="table table-borderless">
                                        <tr>
                                            <th width="40%">Registration Number:</th>
                                            <td>{{ $vendor->taxInfo->business_registration_number ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>License Number:</th>
                                            <td>{{ $vendor->taxInfo->business_license_number ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Registration Date:</th>
                                            <td>{{ $vendor->taxInfo->business_registration_date ?? 'N/A' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="ti ti-file-invoice fs-1 text-muted"></i>
                                <p class="mt-2">No tax information provided yet</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Bank Info Tab --}}
            <div class="tab-pane fade" id="bank" role="tabpanel">
                <div class="card">
                    <div class="card-body">
                        @if($vendor->bankInfo)
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="border-bottom pb-2 mb-3">Bank Account Details</h6>
                                    <table class="table table-borderless">
                                        <tr>
                                            <th width="40%">Account Holder:</th>
                                            <td>{{ $vendor->bankInfo->account_holder_name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Account Number:</th>
                                            <td>{{ $vendor->bankInfo->account_number ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Bank Name:</th>
                                            <td>{{ $vendor->bankInfo->bank_name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Bank Branch:</th>
                                            <td>{{ $vendor->bankInfo->bank_branch ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>IFSC Code:</th>
                                            <td>{{ $vendor->bankInfo->ifsc_code ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>SWIFT Code:</th>
                                            <td>{{ $vendor->bankInfo->swift_code ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>IBAN Number:</th>
                                            <td>{{ $vendor->bankInfo->iban_number ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Bank Address:</th>
                                            <td>{{ $vendor->bankInfo->bank_address ?? 'N/A' }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="border-bottom pb-2 mb-3">Digital Payments</h6>
                                    <table class="table table-borderless">
                                        <tr>
                                            <th width="40%">UPI ID:</th>
                                            <td>{{ $vendor->bankInfo->upi_id ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>PayPal Email:</th>
                                            <td>{{ $vendor->bankInfo->paypal_email ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Stripe Account ID:</th>
                                            <td>{{ $vendor->bankInfo->stripe_account_id ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Razorpay Account ID:</th>
                                            <td>{{ $vendor->bankInfo->razorpay_account_id ?? 'N/A' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="ti ti-wallet fs-1 text-muted"></i>
                                <p class="mt-2">No bank information provided yet</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Documents Tab --}}
            <div class="tab-pane fade" id="documents" role="tabpanel">
                <div class="card">
                    <div class="card-body">
                        @if($vendor->documents && $vendor->documents->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Document Type</th>
                                            <th>Document Name</th>
                                            <th>Status</th>
                                            <th>Uploaded</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($vendor->documents as $document)
                                            <tr>
                                                <td>{{ ucfirst(str_replace('_', ' ', $document->document_type)) }}</td>
                                                <td>{{ $document->document_name }}</td>
                                                <td>
                                                    @if($document->verification_status == 'verified')
                                                        <span class="badge bg-success">Verified</span>
                                                    @elseif($document->verification_status == 'rejected')
                                                        <span class="badge bg-danger">Rejected</span>
                                                    @else
                                                        <span class="badge bg-warning">Pending</span>
                                                    @endif
                                                </td>
                                                <td>{{ $document->created_at->format('d M Y') }}</td>
                                                <td>
                                                    @if(pathinfo($document->document_path, PATHINFO_EXTENSION) == 'pdf')
                                                        <a href="{{ Storage::url($document->document_path) }}" target="_blank" class="btn btn-sm btn-outline-danger">
                                                            <i class="ti ti-file-pdf"></i> View PDF
                                                        </a>
                                                    @else
                                                        <a href="{{ Storage::url($document->document_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                            <i class="ti ti-eye"></i> View Image
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="ti ti-file fs-1 text-muted"></i>
                                <p class="mt-2">No documents uploaded yet</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Products Tab --}}
            <div class="tab-pane fade" id="products" role="tabpanel">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Recent Products</h5>
                        <a href="{{ route('admin.products.index', ['vendor_id' => $vendor->id]) }}" class="btn btn-sm btn-primary">
                            View All Products
                        </a>
                    </div>
                    <div class="card-body p-0">
                        @if($vendor->products && $vendor->products->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Product</th>
                                            <th>Price</th>
                                            <th>Stock</th>
                                            <th>Status</th>
                                            <th>Created</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($vendor->products as $product)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @if($product->thumbnail)
                                                            <img src="{{ Storage::url($product->thumbnail) }}" width="40" class="rounded me-2">
                                                        @endif
                                                        <div>
                                                            <strong>{{ $product->name }}</strong><br>
                                                            <small class="text-muted">SKU: {{ $product->sku ?? 'N/A' }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>${{ number_format($product->price, 2) }}</td>
                                                <td>{{ $product->quantity ?? 0 }}</td>
                                                <td>
                                                    @if($product->status == 'active')
                                                        <span class="badge bg-success">Active</span>
                                                    @else
                                                        <span class="badge bg-secondary">Inactive</span>
                                                    @endif
                                                </td>
                                                <td>{{ $product->created_at->format('d M Y') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="ti ti-package fs-1 text-muted"></i>
                                <p class="mt-2">No products added yet</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Categories Tab --}}
            <div class="tab-pane fade" id="categories" role="tabpanel">
                <div class="card">
                    <div class="card-body">
                        @if($vendor->categories && $vendor->categories->count() > 0)
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($vendor->categories as $category)
                                    <span class="badge bg-primary fs-13 p-2">
                                        <i class="ti ti-category"></i> {{ $category->name }}
                                    </span>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="ti ti-category fs-1 text-muted"></i>
                                <p class="mt-2">No categories selected yet</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Suspend Modal --}}
<div class="modal fade" id="suspendModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.vendors.suspend', $vendor->id) }}">
                @csrf
                <div class="modal-header bg-warning">
                    <h5 class="modal-title">Suspend Vendor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to suspend <strong>{{ $vendor->shop_name }}</strong>?</p>
                    <div class="mb-3">
                        <label class="form-label">Suspension Reason <span class="text-danger">*</span></label>
                        <textarea name="suspension_reason" class="form-control" rows="3" required placeholder="Enter reason for suspension..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Suspend Vendor</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Reject Modal --}}
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.vendors.reject', $vendor->id) }}">
                @csrf
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Reject Vendor Application</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to reject <strong>{{ $vendor->shop_name }}</strong>'s application?</p>
                    <div class="mb-3">
                        <label class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                        <textarea name="rejection_reason" class="form-control" rows="3" required placeholder="Enter reason for rejection..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject Application</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .table-borderless tr td, .table-borderless tr th {
        padding: 8px 0;
    }
    .table-borderless tr th {
        font-weight: 600;
    }
</style>
@endpush