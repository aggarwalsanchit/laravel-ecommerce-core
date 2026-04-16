{{-- resources/views/admin/pages/brands/requests.blade.php --}}
@extends('management.layouts.app')

@section('title', 'Brand Requests')

@section('content')
    <div class="page-content">
        <div class="page-container">
            <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                <div class="flex-grow-1">
                    <h4 class="fs-18 text-uppercase fw-bold mb-0">Brand Requests from Vendors</h4>
                </div>
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.brands.index') }}">Brands</a></li>
                        <li class="breadcrumb-item active">Requests</li>
                    </ol>
                </div>
            </div>

            {{-- Statistics Cards --}}
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-warning text-dark">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">Pending</h6>
                                    <h2 class="mb-0">{{ $statistics['total_pending'] ?? 0 }}</h2>
                                </div>
                                <i class="ti ti-clock" style="font-size: 40px; opacity: 0.5;"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">Approved</h6>
                                    <h2 class="mb-0">{{ $statistics['total_approved'] ?? 0 }}</h2>
                                </div>
                                <i class="ti ti-check-circle" style="font-size: 40px; opacity: 0.5;"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-danger text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">Rejected</h6>
                                    <h2 class="mb-0">{{ $statistics['total_rejected'] ?? 0 }}</h2>
                                </div>
                                <i class="ti ti-x-circle" style="font-size: 40px; opacity: 0.5;"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">Total Requests</h6>
                                    <h2 class="mb-0">{{ $statistics['total_requests'] ?? 0 }}</h2>
                                </div>
                                <i class="ti ti-file" style="font-size: 40px; opacity: 0.5;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <h3 class="card-title mb-0">Brand Requests</h3>
                            <div class="d-flex gap-2">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-outline-secondary dropdown-toggle"
                                        data-bs-toggle="dropdown">
                                        <i class="ti ti-filter me-1"></i> Filter:
                                        @if ($status == 'pending')
                                            Pending
                                        @elseif($status == 'approved')
                                            Approved
                                        @elseif($status == 'rejected')
                                            Rejected
                                        @else
                                            All
                                        @endif
                                    </button>
                                    <ul class="dropdown-menu" id="statusFilter">
                                        <li><a class="dropdown-item" href="#" data-status="pending">Pending</a></li>
                                        <li><a class="dropdown-item" href="#" data-status="approved">Approved</a></li>
                                        <li><a class="dropdown-item" href="#" data-status="rejected">Rejected</a></li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li><a class="dropdown-item" href="#" data-status="all">All Requests</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">

                            {{-- Search --}}
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="searchInput"
                                            placeholder="Search by brand name or code..." value="{{ request('search') }}">
                                        <button class="btn btn-primary" type="button" id="searchBtn">
                                            <i class="ti ti-search"></i>
                                        </button>
                                        <button class="btn btn-secondary" type="button" id="clearSearch"
                                            style="display: none;">
                                            <i class="ti ti-x"></i> Clear
                                        </button>
                                    </div>
                                </div>
                            </div>

                            {{-- Bulk Actions for Requests --}}
                            @can('edit_brands')
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <div class="btn-group flex-wrap gap-2">
                                            <button type="button" class="btn btn-outline-success btn-sm"
                                                onclick="bulkRequestAction('approve')">
                                                <i class="ti ti-check"></i> Approve Selected
                                            </button>
                                            <button type="button" class="btn btn-outline-danger btn-sm"
                                                onclick="bulkRequestAction('reject')">
                                                <i class="ti ti-x"></i> Reject Selected
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary btn-sm"
                                                onclick="bulkRequestAction('delete')">
                                                <i class="ti ti-trash"></i> Delete Selected
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endcan

                            {{-- Requests Table --}}
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="bg-dark-subtle">
                                        <tr>
                                            <th style="width: 50px;"><input type="checkbox" class="form-check-input"
                                                    id="selectAll"></th>
                                            <th>ID</th>
                                            <th>Requested Brand</th>
                                            <th>Code</th>
                                            <th>Requested By</th>
                                            <th>Request Date</th>
                                            <th>Status</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($requests as $request)
                                            @php
                                                $statusBadge =
                                                    [
                                                        'pending' =>
                                                            '<span class="badge bg-warning"><i class="ti ti-clock"></i> Pending</span>',
                                                        'approved' =>
                                                            '<span class="badge bg-success"><i class="ti ti-check"></i> Approved</span>',
                                                        'rejected' =>
                                                            '<span class="badge bg-danger"><i class="ti ti-x"></i> Rejected</span>',
                                                    ][$request->status] ??
                                                    '<span class="badge bg-secondary">Unknown</span>';
                                            @endphp
                                            <tr>
                                                <td><input type="checkbox" class="form-check-input request-checkbox"
                                                        value="{{ $request->id }}"></td>
                                                <td>#{{ $request->id }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <i class="ti ti-brand-airbnb text-primary"></i>
                                                        <strong>{{ $request->requested_name }}</strong>
                                                    </div>
                                                    @if ($request->description)
                                                        <small
                                                            class="text-muted">{{ Str::limit($request->description, 50) }}</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    <code class="small">{{ $request->requested_code ?? 'N/A' }}</code>
                                                </td>
                                                <td>
                                                    @if ($request->vendor)
                                                        Vendor #{{ $request->vendor_id }}<br>
                                                        <small
                                                            class="text-muted">{{ $request->vendor->name ?? 'N/A' }}</small>
                                                    @else
                                                        <span class="text-muted">Unknown Vendor</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ $request->created_at->format('M d, Y H:i') }}<br>
                                                    <small
                                                        class="text-muted">{{ $request->created_at->diffForHumans() }}</small>
                                                </td>
                                                <td>{!! $statusBadge !!}</td>
                                                <td class="text-center">
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="{{ route('admin.brands.requests.show', $request->id) }}"
                                                            class="btn btn-info" title="View Details">
                                                            <i class="ti ti-eye"></i>
                                                        </a>
                                                        @if ($request->status === 'pending')
                                                            @can('edit_brands')
                                                                <button type="button" class="btn btn-success"
                                                                    onclick="approveRequest({{ $request->id }})"
                                                                    title="Approve">
                                                                    <i class="ti ti-check"></i>
                                                                </button>
                                                                <button type="button" class="btn btn-warning"
                                                                    onclick="showRejectModal({{ $request->id }}, '{{ $request->requested_name }}')"
                                                                    title="Reject">
                                                                    <i class="ti ti-x"></i>
                                                                </button>
                                                            @endcan
                                                        @endif
                                                        @if ($request->status !== 'approved')
                                                            @can('delete_brands')
                                                                <button type="button" class="btn btn-danger"
                                                                    onclick="deleteRequest({{ $request->id }}, '{{ $request->requested_name }}')"
                                                                    title="Delete">
                                                                    <i class="ti ti-trash"></i>
                                                                </button>
                                                            @endcan
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center py-5">
                                                    <i class="ti ti-inbox" style="font-size: 48px; opacity: 0.5;"></i>
                                                    <h5 class="mt-3">No Brand Requests Found</h5>
                                                    <p class="text-muted">When vendors request new brands, they will
                                                        appear here.</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            {{-- Pagination --}}
                            <div class="mt-3">
                                {{ $requests->appends(request()->query())->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Bulk Action Form --}}
    <form id="bulkRequestForm" method="POST" action="{{ route('admin.brands.requests.bulk-action') }}"
        style="display: none;">
        @csrf
        <input type="hidden" name="action" id="bulkAction">
        <input type="hidden" name="request_ids" id="bulkRequestIds">
    </form>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // Search functionality
            $('#searchBtn').on('click', function() {
                let search = $('#searchInput').val();
                let status = '{{ $status }}';
                window.location.href = '{{ route('admin.brands.requests') }}?search=' + search +
                    '&status=' + status;
            });

            $('#searchInput').on('keypress', function(e) {
                if (e.which === 13) {
                    $('#searchBtn').click();
                }
            });

            $('#clearSearch').on('click', function() {
                window.location.href =
                    '{{ route('admin.brands.requests') }}?status={{ $status }}';
            });

            if ($('#searchInput').val()) {
                $('#clearSearch').show();
            }

            // Status filter
            $('#statusFilter .dropdown-item').on('click', function(e) {
                e.preventDefault();
                let status = $(this).data('status');
                let search = $('#searchInput').val();
                window.location.href = '{{ route('admin.brands.requests') }}?status=' + status +
                    '&search=' + search;
            });

            // Select All
            $('#selectAll').on('change', function() {
                $('.request-checkbox').prop('checked', $(this).prop('checked'));
            });

            $('.request-checkbox').on('change', function() {
                let allChecked = $('.request-checkbox:checked').length === $('.request-checkbox').length;
                $('#selectAll').prop('checked', allChecked);
            });
        });

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
                                }).then(() => location.reload());
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
                        reason: reason
                    };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ url('admin/brands/requests') }}/' + requestId + '/reject',
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
                                }).then(() => location.reload());
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

        function deleteRequest(requestId, requestName) {
            Swal.fire({
                title: 'Delete Request?',
                text: `Are you sure you want to delete the request for "${requestName}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ url('admin/brands/requests') }}/' + requestId,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: response.message,
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => location.reload());
                            }
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: xhr.responseJSON?.message || 'Failed to delete request.',
                                confirmButtonColor: '#d33'
                            });
                        }
                    });
                }
            });
        }

        function bulkRequestAction(action) {
            let selectedRequests = [];
            $('.request-checkbox:checked').each(function() {
                selectedRequests.push($(this).val());
            });

            if (selectedRequests.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'No Selection',
                    text: 'Please select at least one request.',
                    confirmButtonColor: '#6c757d'
                });
                return;
            }

            let actionText = '';
            let confirmColor = '#28a745';

            switch (action) {
                case 'approve':
                    actionText = 'approve';
                    break;
                case 'reject':
                    actionText = 'reject';
                    confirmColor = '#dc3545';
                    break;
                case 'delete':
                    actionText = 'delete';
                    confirmColor = '#d33';
                    break;
            }

            Swal.fire({
                title: `${actionText.toUpperCase()} Requests?`,
                text: `Are you sure you want to ${actionText} ${selectedRequests.length} selected request(s)?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: confirmColor,
                cancelButtonColor: '#6c757d',
                confirmButtonText: `Yes, ${actionText} them!`
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#bulkAction').val(action);
                    $('#bulkRequestIds').val(JSON.stringify(selectedRequests));

                    $.ajax({
                        url: $('#bulkRequestForm').attr('action'),
                        type: 'POST',
                        data: $('#bulkRequestForm').serialize(),
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success!',
                                    text: response.message,
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => location.reload());
                            }
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: xhr.responseJSON?.message ||
                                    'Failed to process bulk action.',
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
        .btn-group-sm .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }
    </style>
@endpush
