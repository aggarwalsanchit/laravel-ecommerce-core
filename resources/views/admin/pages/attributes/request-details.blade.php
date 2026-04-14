{{-- resources/views/admin/pages/attributes/request-details.blade.php --}}
@extends('management.layouts.app')

@section('title', 'Attribute Request Details')

@section('content')
<div class="page-content">
    <div class="page-container">
        <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
            <div class="flex-grow-1">
                <h4 class="fs-18 text-uppercase fw-bold mb-0">Attribute Request Details</h4>
            </div>
            <div class="text-end">
                <ol class="breadcrumb m-0 py-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.attributes.index') }}">Attributes</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.attributes.requests') }}">Requests</a></li>
                    <li class="breadcrumb-item active">Request #{{ $attributeRequest->id }}</li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4">
                {{-- Request Information Card --}}
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ti ti-info-circle"></i> Request Information</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <td width="120"><strong>Request ID:</strong></td>
                                <td>#{{ $attributeRequest->id }}</td>
                            </tr>
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td>{!! $attributeRequest->status_badge !!}</td>
                            </tr>
                            <tr>
                                <td><strong>Requested By:</strong></td>
                                <td>
                                    Vendor #{{ $attributeRequest->vendor_id }}<br>
                                    <small class="text-muted">{{ $attributeRequest->vendor->name ?? 'N/A' }}</small>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Request Date:</strong></td>
                                <td>{{ $attributeRequest->created_at->format('F d, Y H:i:s') }}</td>
                            </tr>
                            @if($attributeRequest->approved_at)
                            <tr>
                                <td><strong>Processed Date:</strong></td>
                                <td>{{ $attributeRequest->approved_at->format('F d, Y H:i:s') }}</td>
                            </tr>
                            @endif
                            <tr>
                                <td><strong>Processed By:</strong></td>
                                <td>
                                    @if($attributeRequest->approvedBy)
                                        Admin #{{ $attributeRequest->approved_by }}
                                    @else
                                        <span class="text-muted">Not processed yet</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                {{-- Requested Attribute Details Card --}}
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ti ti-input"></i> Requested Attribute Details</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <td width="120"><strong>Attribute Name:</strong></td>
                                <td>{{ $attributeRequest->requested_name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Type:</strong></td>
                                <td>
                                    <span class="badge bg-info">{{ ucfirst($attributeRequest->requested_type) }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Group:</strong></td>
                                <td>
                                    @if($attributeRequest->requested_group_id)
                                        Group #{{ $attributeRequest->requested_group_id }}
                                    @else
                                        <span class="text-muted">No group</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Description:</strong></td>
                                <td>{{ $attributeRequest->description ?? 'No description provided' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Is Required:</strong></td>
                                <td>
                                    @if($attributeRequest->is_required)
                                        <span class="badge bg-danger">Yes</span>
                                    @else
                                        <span class="badge bg-secondary">No</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Is Filterable:</strong></td>
                                <td>
                                    @if($attributeRequest->is_filterable)
                                        <span class="badge bg-success">Yes</span>
                                    @else
                                        <span class="badge bg-secondary">No</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                {{-- Categories Card --}}
                @if($attributeRequest->requested_category_ids && count($attributeRequest->requested_category_ids) > 0)
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ti ti-folder"></i> Requested Categories</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-1">
                            @foreach($attributeRequest->requestedCategories() as $category)
                                <span class="badge bg-primary">{{ $category->name }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <div class="col-lg-8">
                {{-- Reason for Request Card --}}
                @if($attributeRequest->reason)
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ti ti-message"></i> Reason for Request</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">{{ $attributeRequest->reason }}</p>
                    </div>
                </div>
                @endif

                {{-- Requested Values Card (for select/multiselect) --}}
                @if($attributeRequest->requested_values && count($attributeRequest->requested_values) > 0)
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ti ti-list"></i> Requested Values</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr><th>Value</th><th>Label</th><th>Color</th><th>Price Adj.</th><th>Weight Adj.</th></tr>
                                </thead>
                                <tbody>
                                    @foreach($attributeRequest->requested_values as $value)
                                    <tr>
                                        <td><code>{{ $value['value'] ?? $value }}</code></td>
                                        <td>{{ $value['label'] ?? $value }}</td>
                                        <td>@if(isset($value['color_code']))<div style="width: 25px; height: 25px; background-color: {{ $value['color_code'] }}; border-radius: 50%;"></div>@else—@endif</td>
                                        <td>${{ number_format($value['price_adjustment'] ?? 0, 2) }}</td>
                                        <td>{{ $value['weight_adjustment'] ?? 0 }} kg</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Admin Notes Card --}}
                @if($attributeRequest->admin_notes)
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ti ti-notes"></i> Admin Notes</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">{{ $attributeRequest->admin_notes }}</p>
                    </div>
                </div>
                @endif

                {{-- Rejection Reason Card --}}
                @if($attributeRequest->rejection_reason)
                <div class="card mb-3">
                    <div class="card-header bg-danger text-white">
                        <h5 class="card-title mb-0"><i class="ti ti-alert-circle"></i> Rejection Reason</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-0 text-danger">{{ $attributeRequest->rejection_reason }}</p>
                    </div>
                </div>
                @endif

                {{-- Created Attribute Card (if approved) --}}
                @if($attributeRequest->status === 'approved' && $attributeRequest->createdAttribute)
                <div class="card mb-3">
                    <div class="card-header bg-success text-white">
                        <h5 class="card-title mb-0"><i class="ti ti-check-circle"></i> Created Attribute</h5>
                    </div>
                    <div class="card-body">
                        <p>The following attribute has been created from this request:</p>
                        <div class="d-flex align-items-center gap-3 p-3 bg-light rounded">
                            <i class="ti ti-input text-primary fs-1"></i>
                            <div>
                                <h5 class="mb-1">{{ $attributeRequest->createdAttribute->name }}</h5>
                                <div class="text-muted small">
                                    <span class="badge bg-info">{{ $attributeRequest->createdAttribute->type_label }}</span>
                                    <code class="ms-2">{{ $attributeRequest->createdAttribute->slug }}</code>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('admin.attributes.show', $attributeRequest->createdAttribute->id) }}" class="btn btn-sm btn-primary">
                                <i class="ti ti-eye"></i> View Attribute
                            </a>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Action Buttons --}}
                <div class="card">
                    <div class="card-footer d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.attributes.requests') }}" class="btn btn-secondary">
                            <i class="ti ti-arrow-left me-1"></i> Back to Requests
                        </a>
                        @if($attributeRequest->status === 'pending')
                            @can('approve_attributes')
                                <button type="button" class="btn btn-success" onclick="approveRequest({{ $attributeRequest->id }})">
                                    <i class="ti ti-check me-1"></i> Approve Request
                                </button>
                                <button type="button" class="btn btn-warning" onclick="showRejectModal({{ $attributeRequest->id }}, '{{ $attributeRequest->requested_name }}')">
                                    <i class="ti ti-x me-1"></i> Reject Request
                                </button>
                            @endcan
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function approveRequest(requestId) {
    Swal.fire({
        title: 'Approve Attribute Request?',
        text: 'This will create a new attribute and make it available to vendors.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, approve it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '{{ url("admin/attributes/requests") }}/' + requestId + '/approve',
                type: 'POST',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Approved!',
                            text: response.message,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: xhr.responseJSON?.message || 'Failed to approve request.',
                        confirmButtonColor: '#d33'
                    });
                }
            });
        }
    });
}

function showRejectModal(requestId, requestName) {
    Swal.fire({
        title: 'Reject Attribute Request',
        html: `
            <p>Are you sure you want to reject <strong>"${requestName}"</strong>?</p>
            <textarea id="rejectionReason" class="swal2-textarea" placeholder="Please provide a reason for rejection..." rows="3"></textarea>
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, reject it!',
        preConfirm: () => {
            const reason = document.getElementById('rejectionReason').value;
            if (!reason) {
                Swal.showValidationMessage('Please provide a rejection reason');
                return false;
            }
            return { reason: reason };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '{{ url("admin/attributes/requests") }}/' + requestId + '/reject',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    rejection_reason: result.value.reason
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Rejected!',
                            text: response.message,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: xhr.responseJSON?.message || 'Failed to reject request.',
                        confirmButtonColor: '#d33'
                    });
                }
            });
        }
    });
}
</script>
@endpush

@push('styles')
<style>
    .table-borderless td, .table-borderless th {
        padding: 0.5rem 0;
    }
    .bg-light {
        background-color: #f8f9fa !important;
    }
</style>
@endpush