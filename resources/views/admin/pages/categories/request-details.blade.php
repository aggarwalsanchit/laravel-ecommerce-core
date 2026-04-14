{{-- resources/views/admin/pages/categories/request-details.blade.php --}}
@extends('management.layouts.app')

@section('title', 'Category Request Details')

@section('content')
    <div class="page-content">
        <div class="page-container">
            <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                <div class="flex-grow-1">
                    <h4 class="fs-18 text-uppercase fw-bold mb-0">Category Request Details</h4>
                </div>
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">Categories</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.categories.requests') }}">Requests</a></li>
                        <li class="breadcrumb-item active">Request #{{ $categoryRequest->id }}</li>
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
                                    <td>#{{ $categoryRequest->id }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>{!! $categoryRequest->status !!}</td>
                                </tr>
                                <tr>
                                    <td><strong>Requested By:</strong></td>
                                    <td>Vendor
                                        #{{ $categoryRequest->vendor_id }}<br><small>{{ $categoryRequest->vendor->name ?? 'N/A' }}</small>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Request Date:</strong></td>
                                    <td>{{ $categoryRequest->created_at->format('F d, Y H:i:s') }}</td>
                                </tr>
                                @if ($categoryRequest->approved_at)
                                    <tr>
                                        <td><strong>Processed Date:</strong></td>
                                        <td>{{ $categoryRequest->approved_at->format('F d, Y H:i:s') }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <td><strong>Processed By:</strong></td>
                                    <td>
                                        @if ($categoryRequest->approvedBy)
                                            Admin #{{ $categoryRequest->approved_by }}
                                        @else
                                            Not processed yet
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    @if ($categoryRequest->description)
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5 class="card-title mb-0"><i class="ti ti-align-left"></i> Description</h5>
                            </div>
                            <div class="card-body">
                                <p>{{ $categoryRequest->description }}</p>
                            </div>
                        </div>
                    @endif

                    @if ($categoryRequest->reason)
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5 class="card-title mb-0"><i class="ti ti-message"></i> Reason for Request</h5>
                            </div>
                            <div class="card-body">
                                <p>{{ $categoryRequest->reason }}</p>
                            </div>
                        </div>
                    @endif

                    @if ($categoryRequest->rejection_reason)
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5 class="card-title mb-0 text-danger"><i class="ti ti-alert-circle"></i> Rejection Reason
                                </h5>
                            </div>
                            <div class="card-body">
                                <p class="text-danger">{{ $categoryRequest->rejection_reason }}</p>
                            </div>
                        </div>
                    @endif

                    @if ($categoryRequest->admin_notes)
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5 class="card-title mb-0"><i class="ti ti-notes"></i> Admin Notes</h5>
                            </div>
                            <div class="card-body">
                                <p>{{ $categoryRequest->admin_notes }}</p>
                            </div>
                        </div>
                    @endif

                    @if ($categoryRequest->status === 'approved' && $categoryRequest->createdCategory)
                        <div class="card mb-3">
                            <div class="card-header bg-success text-white">
                                <h5 class="card-title mb-0"><i class="ti ti-check-circle"></i> Created Category</h5>
                            </div>
                            <div class="card-body">
                                <p>The following category has been created from this request:</p>
                                <div class="d-flex align-items-center gap-3">
                                    <i class="ti ti-folder text-primary fs-1"></i>
                                    <div>
                                        <strong>{{ $categoryRequest->createdCategory->name }}</strong><br>
                                        <small class="text-muted">Slug:
                                            {{ $categoryRequest->createdCategory->slug }}</small>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <a href="{{ route('admin.categories.show', $categoryRequest->createdCategory->id) }}"
                                        class="btn btn-sm btn-primary">
                                        <i class="ti ti-eye"></i> View Category
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="card">
                        <div class="card-footer d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.categories.requests') }}" class="btn btn-secondary">
                                <i class="ti ti-arrow-left me-1"></i> Back to Requests
                            </a>
                            @if ($categoryRequest->status === 'pending')
                                @can('approve_categories')
                                    <button type="button" class="btn btn-success"
                                        onclick="approveRequest({{ $categoryRequest->id }})">
                                        <i class="ti ti-check me-1"></i> Approve Request
                                    </button>
                                    <button type="button" class="btn btn-warning"
                                        onclick="showRejectModal({{ $categoryRequest->id }}, '{{ $categoryRequest->requested_name }}')">
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
                title: 'Approve Category Request?',
                text: 'This will create a new category and make it available to all vendors.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                confirmButtonText: 'Yes, approve it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ url('admin/categories/requests') }}/' + requestId + '/approve',
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
                                    })
                                    .then(() => location.reload());
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
                title: 'Reject Category Request',
                html: `<p>Reject "${requestName}"?</p><textarea id="rejectionReason" class="swal2-textarea" placeholder="Provide rejection reason..." rows="3"></textarea>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'Yes, reject it!',
                preConfirm: () => {
                    const reason = document.getElementById('rejectionReason').value;
                    if (!reason) {
                        Swal.showValidationMessage('Please provide a reason');
                        return false;
                    }
                    return {
                        reason: reason
                    };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ url('admin/categories/requests') }}/' + requestId + '/reject',
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
                                    })
                                    .then(() => location.reload());
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
