{{-- resources/views/admin/pages/colors/request-details.blade.php --}}
@extends('management.layouts.app')

@section('title', 'Color Request Details')

@section('content')
    <div class="page-content">
        <div class="page-container">
            <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                <div class="flex-grow-1">
                    <h4 class="fs-18 text-uppercase fw-bold mb-0">Color Request Details</h4>
                </div>
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.colors.index') }}">Colors</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.colors.requests') }}">Requests</a></li>
                        <li class="breadcrumb-item active">Request #{{ $colorRequest->id }}</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-4">
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="card-title mb-0"><i class="ti ti-palette"></i> Color Preview</h5>
                        </div>
                        <div class="card-body text-center">
                            <div class="rounded mb-3 mx-auto"
                                style="width: 150px; height: 150px; background-color: {{ $colorRequest->requested_code }}; border: 1px solid #ddd;">
                            </div>
                            <h3>{{ $colorRequest->requested_name }}</h3>
                            <code class="fs-4">{{ $colorRequest->requested_code }}</code>
                            @if ($colorRequest->requested_rgb)
                                <div class="text-muted mt-2">{{ $colorRequest->requested_rgb }}</div>
                            @endif
                            @if ($colorRequest->requested_hsl)
                                <div class="text-muted">{{ $colorRequest->requested_hsl }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="card-title mb-0"><i class="ti ti-info-circle"></i> Request Information</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="120"><strong>Request ID:</strong></td>
                                    <td>#{{ $colorRequest->id }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>{!! $colorRequest->status_badge !!}</td>
                                </tr>
                                <tr>
                                    <td><strong>Requested By:</strong></td>
                                    <td>Vendor
                                        #{{ $colorRequest->vendor_id }}<br><small>{{ $colorRequest->vendor->name ?? 'N/A' }}</small>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Request Date:</strong></td>
                                    <td>{{ $colorRequest->created_at->format('F d, Y H:i:s') }}</td>
                                </tr>
                                @if ($colorRequest->approved_at)
                                    <tr>
                                        <td><strong>Processed Date:</strong></td>
                                        <td>{{ $colorRequest->approved_at->format('F d, Y H:i:s') }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <td><strong>Processed By:</strong></td>
                                    <td>
                                        @if ($colorRequest->approvedBy)
                                            Admin #{{ $colorRequest->approved_by }}
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
                    @if ($colorRequest->description)
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5 class="card-title mb-0"><i class="ti ti-align-left"></i> Description</h5>
                            </div>
                            <div class="card-body">
                                <p>{{ $colorRequest->description }}</p>
                            </div>
                        </div>
                    @endif

                    @if ($colorRequest->reason)
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5 class="card-title mb-0"><i class="ti ti-message"></i> Reason for Request</h5>
                            </div>
                            <div class="card-body">
                                <p>{{ $colorRequest->reason }}</p>
                            </div>
                        </div>
                    @endif

                    @if ($colorRequest->rejection_reason)
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5 class="card-title mb-0 text-danger"><i class="ti ti-alert-circle"></i> Rejection Reason
                                </h5>
                            </div>
                            <div class="card-body">
                                <p class="text-danger">{{ $colorRequest->rejection_reason }}</p>
                            </div>
                        </div>
                    @endif

                    @if ($colorRequest->admin_notes)
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5 class="card-title mb-0"><i class="ti ti-notes"></i> Admin Notes</h5>
                            </div>
                            <div class="card-body">
                                <p>{{ $colorRequest->admin_notes }}</p>
                            </div>
                        </div>
                    @endif

                    @if ($colorRequest->status === 'approved' && $colorRequest->createdColor)
                        <div class="card mb-3">
                            <div class="card-header bg-success text-white">
                                <h5 class="card-title mb-0"><i class="ti ti-check-circle"></i> Created Color</h5>
                            </div>
                            <div class="card-body">
                                <p>The following color has been created from this request:</p>
                                <div class="d-flex align-items-center gap-3">
                                    <div
                                        style="width: 50px; height: 50px; background-color: {{ $colorRequest->createdColor->code }}; border-radius: 50%; border: 1px solid #ddd;">
                                    </div>
                                    <div>
                                        <strong>{{ $colorRequest->createdColor->name }}</strong><br>
                                        <code>{{ $colorRequest->createdColor->code }}</code>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <a href="{{ route('admin.colors.show', $colorRequest->createdColor->id) }}"
                                        class="btn btn-sm btn-primary">
                                        <i class="ti ti-eye"></i> View Color
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="card">
                        <div class="card-footer d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.colors.requests') }}" class="btn btn-secondary">
                                <i class="ti ti-arrow-left me-1"></i> Back to Requests
                            </a>
                            @if ($colorRequest->status === 'pending')
                                @can('approve_colors')
                                    <button type="button" class="btn btn-success"
                                        onclick="approveRequest({{ $colorRequest->id }})">
                                        <i class="ti ti-check me-1"></i> Approve Request
                                    </button>
                                    <button type="button" class="btn btn-warning"
                                        onclick="showRejectModal({{ $colorRequest->id }}, '{{ $colorRequest->requested_name }}')">
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
                title: 'Approve Color Request?',
                text: 'This will create a new color and make it available to all vendors.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                confirmButtonText: 'Yes, approve it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ url('admin/colors/requests') }}/' + requestId + '/approve',
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
                title: 'Reject Color Request',
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
                        url: '{{ url('admin/colors/requests') }}/' + requestId + '/reject',
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
