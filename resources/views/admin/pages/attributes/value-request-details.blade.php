{{-- resources/views/admin/pages/attributes/value-request-details.blade.php --}}
@extends('management.layouts.app')

@section('title', 'Attribute Value Request Details')

@section('content')
<div class="page-content">
    <div class="page-container">
        <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
            <div class="flex-grow-1">
                <h4 class="fs-18 text-uppercase fw-bold mb-0">Attribute Value Request Details</h4>
            </div>
            <div class="text-end">
                <ol class="breadcrumb m-0 py-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.attributes.index') }}">Attributes</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.attributes.value-requests') }}">Value Requests</a></li>
                    <li class="breadcrumb-item active">Request #{{ $valueRequest->id }}</li>
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
                                <td>#{{ $valueRequest->id }}</td>
                            </tr>
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td>{!! $valueRequest->status_badge !!}</td>
                            </tr>
                            <tr>
                                <td><strong>Requested By:</strong></td>
                                <td>
                                    Vendor #{{ $valueRequest->vendor_id }}<br>
                                    <small class="text-muted">{{ $valueRequest->vendor->name ?? 'N/A' }}</small>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Request Date:</strong></td>
                                <td>{{ $valueRequest->created_at->format('F d, Y H:i:s') }}</td>
                            </tr>
                            @if($valueRequest->approved_at)
                            <tr>
                                <td><strong>Processed Date:</strong></td>
                                <td>{{ $valueRequest->approved_at->format('F d, Y H:i:s') }}</td>
                            </tr>
                            @endif
                            <tr>
                                <td><strong>Processed By:</strong></td>
                                <td>
                                    @if($valueRequest->approvedBy)
                                        Admin #{{ $valueRequest->approved_by }}
                                    @else
                                        <span class="text-muted">Not processed yet</span>
                                    @endif
                                 </td>
                            </tr>
                        </table>
                    </div>
                </div>

                {{-- Attribute Information Card --}}
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ti ti-input"></i> Attribute Information</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <td width="120"><strong>Attribute Name:</strong></td>
                                <td>{{ $valueRequest->attribute->name ?? 'Unknown' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Attribute Type:</strong></td>
                                <td>
                                    <span class="badge bg-info">{{ $valueRequest->attribute->type_label ?? $valueRequest->attribute->type ?? 'Unknown' }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Attribute Slug:</strong></td>
                                <td><code>{{ $valueRequest->attribute->slug ?? 'N/A' }}</code></td>
                            </tr>
                            @if($valueRequest->attribute && $valueRequest->attribute->group)
                            <tr>
                                <td><strong>Group:</strong></td>
                                <td>{{ $valueRequest->attribute->group->name }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>

                {{-- Value Preview Card --}}
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ti ti-eye"></i> Value Preview</h5>
                    </div>
                    <div class="card-body text-center">
                        @if($valueRequest->requested_color_code)
                            <div style="width: 80px; height: 80px; background-color: {{ $valueRequest->requested_color_code }}; border-radius: 50%; margin: 0 auto 15px; border: 2px solid #ddd;"></div>
                        @elseif($valueRequest->requested_image)
                            <img src="{{ asset('storage/' . $valueRequest->requested_image) }}" alt="Preview" style="max-width: 100px; max-height: 100px; margin-bottom: 15px;">
                        @else
                            <div style="width: 80px; height: 80px; background-color: #f0f0f0; border-radius: 50%; margin: 0 auto 15px; display: flex; align-items: center; justify-content: center;">
                                <i class="ti ti-tag fs-1 text-muted"></i>
                            </div>
                        @endif
                        <h4>{{ $valueRequest->requested_value }}</h4>
                        @if($valueRequest->requested_label)
                            <p class="text-muted">{{ $valueRequest->requested_label }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                {{-- Value Details Card --}}
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ti ti-details"></i> Requested Value Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="text-muted small">Value</label>
                                    <p class="fw-semibold mb-0">{{ $valueRequest->requested_value }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="text-muted small">Label (Display Name)</label>
                                    <p class="fw-semibold mb-0">{{ $valueRequest->requested_label ?? '—' }}</p>
                                </div>
                            </div>
                            @if($valueRequest->requested_color_code)
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="text-muted small">Color Code</label>
                                    <div class="d-flex align-items-center gap-2">
                                        <div style="width: 30px; height: 30px; background-color: {{ $valueRequest->requested_color_code }}; border-radius: 50%; border: 1px solid #ddd;"></div>
                                        <code>{{ $valueRequest->requested_color_code }}</code>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @if($valueRequest->requested_image)
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="text-muted small">Image</label>
                                    <div>
                                        <img src="{{ asset('storage/' . $valueRequest->requested_image) }}" alt="Requested Image" style="max-width: 100px; max-height: 100px; border-radius: 4px;">
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Reason for Request Card --}}
                @if($valueRequest->reason)
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ti ti-message"></i> Reason for Request</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">{{ $valueRequest->reason }}</p>
                    </div>
                </div>
                @endif

                {{-- Admin Notes Card --}}
                @if($valueRequest->admin_notes)
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ti ti-notes"></i> Admin Notes</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">{{ $valueRequest->admin_notes }}</p>
                    </div>
                </div>
                @endif

                {{-- Rejection Reason Card --}}
                @if($valueRequest->rejection_reason)
                <div class="card mb-3">
                    <div class="card-header bg-danger text-white">
                        <h5 class="card-title mb-0"><i class="ti ti-alert-circle"></i> Rejection Reason</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-0 text-danger">{{ $valueRequest->rejection_reason }}</p>
                    </div>
                </div>
                @endif

                {{-- Created Value Card (if approved) --}}
                @if($valueRequest->status === 'approved' && $valueRequest->createdValue)
                <div class="card mb-3">
                    <div class="card-header bg-success text-white">
                        <h5 class="card-title mb-0"><i class="ti ti-check-circle"></i> Created Value</h5>
                    </div>
                    <div class="card-body">
                        <p>The following value has been created from this request:</p>
                        <div class="d-flex align-items-center gap-3 p-3 bg-light rounded">
                            @if($valueRequest->createdValue->color_code)
                                <div style="width: 50px; height: 50px; background-color: {{ $valueRequest->createdValue->color_code }}; border-radius: 50%; border: 1px solid #ddd;"></div>
                            @elseif($valueRequest->createdValue->image)
                                <img src="{{ asset('storage/' . $valueRequest->createdValue->image) }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                            @else
                                <i class="ti ti-tag text-primary fs-1"></i>
                            @endif
                            <div>
                                <h5 class="mb-1">{{ $valueRequest->createdValue->value }}</h5>
                                <div class="text-muted small">
                                    @if($valueRequest->createdValue->label)
                                        <span>Label: {{ $valueRequest->createdValue->label }}</span><br>
                                    @endif
                                    @if($valueRequest->createdValue->price_adjustment != 0)
                                        <span>Price: ${{ number_format($valueRequest->createdValue->price_adjustment, 2) }}</span>
                                    @endif
                                    @if($valueRequest->createdValue->weight_adjustment != 0)
                                        <span>Weight: {{ $valueRequest->createdValue->weight_adjustment }} kg</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('admin.attributes.values', $valueRequest->attribute_id) }}" class="btn btn-sm btn-primary">
                                <i class="ti ti-eye"></i> View All Values
                            </a>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Action Buttons --}}
                <div class="card">
                    <div class="card-footer d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.attributes.value-requests') }}" class="btn btn-secondary">
                            <i class="ti ti-arrow-left me-1"></i> Back to Requests
                        </a>
                        @if($valueRequest->status === 'pending')
                            @can('approve_attributes')
                                <button type="button" class="btn btn-success" onclick="approveValueRequest({{ $valueRequest->id }})">
                                    <i class="ti ti-check me-1"></i> Approve Request
                                </button>
                                <button type="button" class="btn btn-warning" onclick="showRejectModal({{ $valueRequest->id }}, '{{ $valueRequest->requested_value }}')">
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
function approveValueRequest(requestId) {
    Swal.fire({
        title: 'Approve Value Request?',
        text: 'This will create a new attribute value and make it available to vendors.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, approve it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '{{ url("admin/attributes/value-requests") }}/' + requestId + '/approve',
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

function showRejectModal(requestId, requestValue) {
    Swal.fire({
        title: 'Reject Value Request',
        html: `
            <p>Are you sure you want to reject <strong>"${requestValue}"</strong>?</p>
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
                url: '{{ url("admin/attributes/value-requests") }}/' + requestId + '/reject',
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