{{-- resources/views/admin/pages/brands/request-details.blade.php --}}
@extends('management.layouts.app')

@section('title', 'Brand Request Details')

@section('content')
    <div class="page-content">
        <div class="page-container">
            <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                <div class="flex-grow-1">
                    <h4 class="fs-18 text-uppercase fw-bold mb-0">Brand Request Details</h4>
                </div>
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.brands.index') }}">Brands</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.brands.requests') }}">Requests</a></li>
                        <li class="breadcrumb-item active">Request #{{ $brandRequest->id }}</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-4">
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="card-title mb-0"><i class="ti ti-info-circle"></i> Request Information</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="120"><strong>Request ID:</strong></td>
                                    <td>#{{ $brandRequest->id }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>{!! $brandRequest->status_badge !!}</td>
                                </tr>
                                <tr>
                                    <td><strong>Requested By:</strong></td>
                                    <td>
                                        Vendor #{{ $brandRequest->vendor_id }}<br>
                                        <small>{{ $brandRequest->vendor->name ?? 'N/A' }}</small>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Request Date:</strong></td>
                                    <td>{{ $brandRequest->created_at->format('F d, Y H:i:s') }}</td>
                                </tr>
                                @if ($brandRequest->approved_at)
                                    <tr>
                                        <td><strong>Processed Date:</strong></td>
                                        <td>{{ $brandRequest->approved_at->format('F d, Y H:i:s') }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <td><strong>Processed By:</strong></td>
                                    <td>
                                        @if ($brandRequest->approvedBy)
                                            Admin #{{ $brandRequest->approved_by }}
                                        @else
                                            <span class="text-muted">Not processed yet</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if ($brandRequest->logo)
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5 class="card-title mb-0"><i class="ti ti-photo"></i> Requested Logo</h5>
                            </div>
                            <div class="card-body text-center">
                                <img src="{{ asset('storage/brand-requests/' . $brandRequest->logo) }}"
                                    alt="Requested Logo" class="img-fluid rounded border" style="max-height: 150px;">
                            </div>
                        </div>
                    @endif
                </div>

                <div class="col-lg-8">
                    @if ($brandRequest->description)
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5 class="card-title mb-0"><i class="ti ti-align-left"></i> Description</h5>
                            </div>
                            <div class="card-body">
                                <p>{{ $brandRequest->description }}</p>
                            </div>
                        </div>
                    @endif

                    @if ($brandRequest->reason)
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5 class="card-title mb-0"><i class="ti ti-message"></i> Reason for Request</h5>
                            </div>
                            <div class="card-body">
                                <p>{{ $brandRequest->reason }}</p>
                            </div>
                        </div>
                    @endif

                    @if ($brandRequest->rejection_reason)
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5 class="card-title mb-0 text-danger"><i class="ti ti-alert-circle"></i> Rejection Reason
                                </h5>
                            </div>
                            <div class="card-body">
                                <p class="text-danger">{{ $brandRequest->rejection_reason }}</p>
                            </div>
                        </div>
                    @endif

                    @if ($brandRequest->admin_notes)
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5 class="card-title mb-0"><i class="ti ti-notes"></i> Admin Notes</h5>
                            </div>
                            <div class="card-body">
                                <p>{{ $brandRequest->admin_notes }}</p>
                            </div>
                        </div>
                    @endif

                    @if ($brandRequest->status === 'approved' && $brandRequest->createdBrand)
                        <div class="card mb-3">
                            <div class="card-header bg-success text-white">
                                <h5 class="card-title mb-0"><i class="ti ti-check-circle"></i> Created Brand</h5>
                            </div>
                            <div class="card-body">
                                <p>The following brand has been created from this request:</p>
                                <div class="d-flex align-items-center gap-3">
                                    @if ($brandRequest->createdBrand->logo)
                                        <img src="{{ asset('storage/brands/' . $brandRequest->createdBrand->logo) }}"
                                            alt="{{ $brandRequest->createdBrand->name }}"
                                            style="width: 60px; height: 60px; object-fit: cover;" class="rounded border">
                                    @else
                                        <i class="ti ti-brand-airbnb text-primary fs-1"></i>
                                    @endif
                                    <div>
                                        <strong>{{ $brandRequest->createdBrand->name }}</strong><br>
                                        <small class="text-muted">Code: {{ $brandRequest->createdBrand->code }}</small><br>
                                        <small class="text-muted">Slug: {{ $brandRequest->createdBrand->slug }}</small>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <a href="{{ route('admin.brands.show', $brandRequest->createdBrand->id) }}"
                                        class="btn btn-sm btn-primary">
                                        <i class="ti ti-eye"></i> View Brand
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="card">
                        <div class="card-footer d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.brands.requests') }}" class="btn btn-secondary">
                                <i class="ti ti-arrow-left me-1"></i> Back to Requests
                            </a>
                            @if ($brandRequest->status === 'pending')
                                @can('edit_brands')
                                    <button type="button" class="btn btn-success"
                                        onclick="approveRequest({{ $brandRequest->id }})">
                                        <i class="ti ti-check me-1"></i> Approve Request
                                    </button>
                                    <button type="button" class="btn btn-warning"
                                        onclick="showRejectModal({{ $brandRequest->id }}, '{{ $brandRequest->requested_name }}')">
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
                title: 'Approve Brand Request?',
                text: 'This will create a new brand and make it available to all vendors.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, approve it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ url('admin/brands/requests') }}/' + requestId + '/approve',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
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
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: response.message || 'Failed to approve request.',
                                    confirmButtonColor: '#d33'
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
                title: 'Reject Brand Request',
                html: `
                    <p>Are you sure you want to reject <strong>"${requestName}"</strong>?</p>
                    <textarea id="rejectionReason" class="swal2-textarea" placeholder="Please provide a reason for rejection..." rows="3"></textarea>
                    <div class="mt-2">
                        <label class="form-label small">Admin Notes (Optional)</label>
                        <textarea id="adminNotes" class="swal2-textarea" placeholder="Add any internal notes..." rows="2"></textarea>
                    </div>
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
                    return {
                        reason: reason,
                        admin_notes: document.getElementById('adminNotes').value
                    };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ url('admin/brands/requests') }}/' + requestId + '/reject',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            rejection_reason: result.value.reason,
                            admin_notes: result.value.admin_notes
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
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: response.message || 'Failed to reject request.',
                                    confirmButtonColor: '#d33'
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
        .table-borderless td,
        .table-borderless th {
            padding: 0.5rem 0;
        }

        .bg-success-subtle {
            background-color: rgba(25, 135, 84, 0.1);
        }

        .bg-danger-subtle {
            background-color: rgba(220, 53, 69, 0.1);
        }

        .bg-warning-subtle {
            background-color: rgba(255, 193, 7, 0.1);
        }
    </style>
@endpush
