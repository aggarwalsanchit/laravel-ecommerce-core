{{-- resources/views/admin/vendors/index.blade.php --}}

@extends('management.layouts.app')

@section('title', 'Vendors')

@section('content')
    <div class="page-content">
        <div class="page-container">
            <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                <div class="flex-grow-1">
                    <h4 class="fs-18 text-uppercase fw-bold mb-0">Vendors</h4>
                    <p class="text-muted mb-0">Manage all marketplace vendors</p>
                </div>
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Vendors</li>
                    </ol>
                </div>
            </div>

            {{-- Stats Cards --}}
            <div class="row">
                <div class="col-md-6 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted mb-2">Total Vendors</p>
                                    <h3 class="mb-0">{{ $stats['total'] ?? 0 }}</h3>
                                </div>
                                <div class="avatar-sm bg-primary-subtle rounded">
                                    <i class="ti ti-users fs-24 text-primary"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted mb-2">Pending Approval</p>
                                    <h3 class="mb-0 text-warning">{{ $stats['pending_approval'] ?? 0 }}</h3>
                                </div>
                                <div class="avatar-sm bg-warning-subtle rounded">
                                    <i class="ti ti-hourglass-empty fs-24 text-warning"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted mb-2">Active Vendors</p>
                                    <h3 class="mb-0 text-success">{{ $stats['active'] ?? 0 }}</h3>
                                </div>
                                <div class="avatar-sm bg-success-subtle rounded">
                                    <i class="ti ti-check-circle fs-24 text-success"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted mb-2">Suspended</p>
                                    <h3 class="mb-0 text-danger">{{ $stats['suspended'] ?? 0 }}</h3>
                                </div>
                                <div class="avatar-sm bg-danger-subtle rounded">
                                    <i class="ti ti-ban fs-24 text-danger"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="card-title">Vendor Management</h3>
                            @can('manage_vendors')
                                <a href="{{ route('admin.vendors.create') }}" class="btn btn-primary">
                                    <i class="ti ti-plus me-1"></i> Add New Vendor
                                </a>
                            @endcan
                        </div>
                        <div class="card-body">

                            {{-- Search and Filter --}}
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <form id="searchForm" class="d-flex gap-2">
                                        <input type="text" name="search" class="form-control" id="searchInput"
                                            placeholder="Search by shop name, email, owner..." value="{{ request('search') }}">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="ti ti-search"></i>
                                        </button>
                                        <button type="button" class="btn btn-secondary" id="clearSearch"
                                            style="display: none;">
                                            Clear
                                        </button>
                                    </form>
                                </div>
                                <div class="col-md-8">
                                    <div class="d-flex gap-2 justify-content-end">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-outline-secondary dropdown-toggle"
                                                data-bs-toggle="dropdown">
                                                Role Status
                                            </button>
                                            <ul class="dropdown-menu" id="roleFilter">
                                                <li><a class="dropdown-item" href="#" data-role="">All Roles</a></li>
                                                <li><a class="dropdown-item" href="#" data-role="pending">Pending (Vendor Role)</a></li>
                                                <li><a class="dropdown-item" href="#" data-role="approved">Approved (Store Owner)</a></li>
                                            </ul>
                                        </div>

                                        <div class="btn-group">
                                            <button type="button" class="btn btn-outline-secondary dropdown-toggle"
                                                data-bs-toggle="dropdown">
                                                Account Status
                                            </button>
                                            <ul class="dropdown-menu" id="statusFilter">
                                                <li><a class="dropdown-item" href="#" data-status="">All Status</a></li>
                                                <li><a class="dropdown-item" href="#" data-status="pending">Pending</a></li>
                                                <li><a class="dropdown-item" href="#" data-status="active">Active</a></li>
                                                <li><a class="dropdown-item" href="#" data-status="suspended">Suspended</a></li>
                                                <li><a class="dropdown-item" href="#" data-status="rejected">Rejected</a></li>
                                            </ul>
                                        </div>

                                        <div class="btn-group">
                                            <button type="button" class="btn btn-outline-secondary dropdown-toggle"
                                                data-bs-toggle="dropdown">
                                                Verification
                                            </button>
                                            <ul class="dropdown-menu" id="verificationFilter">
                                                <li><a class="dropdown-item" href="#" data-verification="">All</a></li>
                                                <li><a class="dropdown-item" href="#" data-verification="verified">Verified</a></li>
                                                <li><a class="dropdown-item" href="#" data-verification="pending">Pending</a></li>
                                                <li><a class="dropdown-item" href="#" data-verification="rejected">Rejected</a></li>
                                            </ul>
                                        </div>

                                        <div class="btn-group">
                                            <button type="button" class="btn btn-outline-secondary dropdown-toggle"
                                                data-bs-toggle="dropdown">
                                                Sort By
                                            </button>
                                            <ul class="dropdown-menu" id="sortFilter">
                                                <li><a class="dropdown-item" href="#" data-sort="latest">Latest First</a></li>
                                                <li><a class="dropdown-item" href="#" data-sort="oldest">Oldest First</a></li>
                                                <li><a class="dropdown-item" href="#" data-sort="name_asc">Name (A-Z)</a></li>
                                                <li><a class="dropdown-item" href="#" data-sort="name_desc">Name (Z-A)</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Bulk Actions --}}
                            @can('manage_vendors')
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-outline-danger btn-sm"
                                                onclick="bulkAction('delete')">
                                                <i class="ti ti-trash"></i> Delete Selected
                                            </button>
                                            <button type="button" class="btn btn-outline-success btn-sm"
                                                onclick="bulkAction('approve')">
                                                <i class="ti ti-check"></i> Approve Selected
                                            </button>
                                            <button type="button" class="btn btn-outline-warning btn-sm"
                                                onclick="bulkAction('suspend')">
                                                <i class="ti ti-pause"></i> Suspend Selected
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endcan

                            <div class="table-responsive" id="vendorsTableContainer">
                                @include('admin.pages.vendors.partials.vendors-table', [
                                    'vendors' => $vendors,
                                ])
                            </div>

                            <div class="card-footer" id="paginationContainer">
                                <div class="d-flex justify-content-end">
                                    {{ $vendors->appends(request()->query())->links('pagination::bootstrap-5') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Delete Form --}}
        <form id="deleteForm" method="POST" style="display: none;">
            @csrf
            @method('DELETE')
        </form>

        {{-- Bulk Action Form --}}
        <form id="bulkActionForm" method="POST" action="{{ route('admin.vendors.bulk-action') }}" style="display: none;">
            @csrf
            <input type="hidden" name="action" id="bulkAction">
            <input type="hidden" name="vendor_ids" id="bulkVendorIds">
        </form>

        {{-- Suspend Modal --}}
        <div class="modal fade" id="suspendModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="suspendForm" method="POST">
                        @csrf
                        <div class="modal-header bg-warning">
                            <h5 class="modal-title">Suspend Vendor</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to suspend <strong id="suspendVendorName"></strong>?</p>
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
                    <form id="rejectForm" method="POST">
                        @csrf
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title">Reject Vendor Application</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to reject <strong id="rejectVendorName"></strong>'s application?</p>
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
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            let currentFilters = {
                search: '{{ request('search') }}',
                role: '{{ request('role') }}',
                status: '{{ request('status') }}',
                verification: '{{ request('verification') }}',
                sort: '{{ request('sort', 'latest') }}',
                page: 1
            };

            // Search with debounce
            let searchTimer;
            $('#searchInput').on('keyup', function(e) {
                clearTimeout(searchTimer);
                searchTimer = setTimeout(() => {
                    currentFilters.search = $(this).val();
                    currentFilters.page = 1;
                    loadVendors();
                    $('#clearSearch').toggle($(this).val() !== '');
                }, 500);
            });

            // Clear search
            $('#clearSearch').on('click', function() {
                $('#searchInput').val('');
                currentFilters.search = '';
                currentFilters.page = 1;
                loadVendors();
                $(this).hide();
            });

            // Role filter
            $('#roleFilter .dropdown-item').on('click', function(e) {
                e.preventDefault();
                let role = $(this).data('role');

                $('#roleFilter .dropdown-item').removeClass('active');
                $(this).addClass('active');

                let buttonText = role ? (role === 'pending' ? 'Pending (Vendor)' : 'Approved (Store Owner)') : 'Role Status';
                $(this).closest('.btn-group').find('.dropdown-toggle').html(buttonText + ' <i class="ti ti-chevron-down"></i>');

                currentFilters.role = role;
                currentFilters.page = 1;
                loadVendors();
            });

            // Status filter
            $('#statusFilter .dropdown-item').on('click', function(e) {
                e.preventDefault();
                let status = $(this).data('status');

                $('#statusFilter .dropdown-item').removeClass('active');
                $(this).addClass('active');

                let buttonText = status ? status.toUpperCase() : 'Account Status';
                $(this).closest('.btn-group').find('.dropdown-toggle').html(buttonText + ' <i class="ti ti-chevron-down"></i>');

                currentFilters.status = status;
                currentFilters.page = 1;
                loadVendors();
            });

            // Verification filter
            $('#verificationFilter .dropdown-item').on('click', function(e) {
                e.preventDefault();
                let verification = $(this).data('verification');

                $('#verificationFilter .dropdown-item').removeClass('active');
                $(this).addClass('active');

                let buttonText = verification ? verification.toUpperCase() : 'Verification';
                $(this).closest('.btn-group').find('.dropdown-toggle').html(buttonText + ' <i class="ti ti-chevron-down"></i>');

                currentFilters.verification = verification;
                currentFilters.page = 1;
                loadVendors();
            });

            // Sort filter
            $('#sortFilter .dropdown-item').on('click', function(e) {
                e.preventDefault();
                let sort = $(this).data('sort');

                $('#sortFilter .dropdown-item').removeClass('active');
                $(this).addClass('active');

                let buttonText = $(this).text();
                $(this).closest('.btn-group').find('.dropdown-toggle').html(buttonText + ' <i class="ti ti-chevron-down"></i>');

                currentFilters.sort = sort;
                currentFilters.page = 1;
                loadVendors();
            });

            // Pagination click handler
            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault();
                let page = $(this).attr('href').split('page=')[1];
                currentFilters.page = page;
                loadVendors();
            });

            // Load vendors via AJAX
            function loadVendors() {
                $.ajax({
                    url: '{{ route('admin.vendors.index') }}',
                    type: 'GET',
                    data: currentFilters,
                    beforeSend: function() {
                        $('#vendorsTableContainer').html(
                            '<div class="text-center py-5"><div class="spinner-border text-primary" role="status"></div></div>'
                        );
                        $('#paginationContainer').html('');
                    },
                    success: function(response) {
                        $('#vendorsTableContainer').html(response.table);
                        $('#paginationContainer').html(response.pagination);

                        let url = new URL(window.location);
                        url.searchParams.set('search', currentFilters.search || '');
                        url.searchParams.set('role', currentFilters.role || '');
                        url.searchParams.set('status', currentFilters.status || '');
                        url.searchParams.set('verification', currentFilters.verification || '');
                        url.searchParams.set('sort', currentFilters.sort || '');
                        url.searchParams.set('page', currentFilters.page);
                        window.history.pushState({}, '', url);

                        $('[data-bs-toggle="tooltip"]').tooltip();

                        $('#selectAll').off('change').on('change', function() {
                            $('.vendor-checkbox').prop('checked', $(this).prop('checked'));
                        });

                        $('.vendor-checkbox').off('change').on('change', function() {
                            let allChecked = $('.vendor-checkbox:checked').length === $('.vendor-checkbox').length;
                            $('#selectAll').prop('checked', allChecked);
                        });
                    },
                    error: function() {
                        $('#vendorsTableContainer').html(
                            '<div class="alert alert-danger">Error loading vendors</div>');
                    }
                });
            }

            // Select All functionality
            $(document).on('change', '#selectAll', function() {
                $('.vendor-checkbox').prop('checked', $(this).prop('checked'));
            });

            $(document).on('change', '.vendor-checkbox', function() {
                let allChecked = $('.vendor-checkbox:checked').length === $('.vendor-checkbox').length;
                $('#selectAll').prop('checked', allChecked);
            });

            if ($('#searchInput').val()) {
                $('#clearSearch').show();
            }
        });

        // Confirm Delete
        function confirmDelete(vendorId) {
            Swal.fire({
                title: 'Delete Vendor?',
                text: "Are you sure you want to delete this vendor? This action cannot be undone.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    let form = $('#deleteForm');
                    form.attr('action', '{{ url("admin/vendors") }}/' + vendorId);

                    $.ajax({
                        url: form.attr('action'),
                        type: 'POST',
                        data: form.serialize(),
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
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
                                text: xhr.responseJSON?.message || 'Failed to delete vendor.',
                                confirmButtonColor: '#d33'
                            });
                        }
                    });
                }
            });
        }

        // Show Suspend Modal
        function showSuspendModal(vendorId, vendorName) {
            $('#suspendVendorName').text(vendorName);
            $('#suspendForm').attr('action', '{{ url("admin/vendors") }}/' + vendorId + '/suspend');
            $('#suspendModal').modal('show');
        }

        // Show Reject Modal
        function showRejectModal(vendorId, vendorName) {
            $('#rejectVendorName').text(vendorName);
            $('#rejectForm').attr('action', '{{ url("admin/vendors") }}/' + vendorId + '/reject');
            $('#rejectModal').modal('show');
        }

        // Bulk Action
        function bulkAction(action) {
            let selectedVendors = [];
            $('.vendor-checkbox:checked').each(function() {
                selectedVendors.push($(this).val());
            });

            if (selectedVendors.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'No Selection',
                    text: 'Please select at least one vendor.',
                    confirmButtonColor: '#6c757d'
                });
                return;
            }

            let title, text, confirmButtonColor;
            
            if (action === 'delete') {
                title = 'Delete Vendors?';
                text = `Are you sure you want to delete ${selectedVendors.length} selected vendor(s)? This action cannot be undone.`;
                confirmButtonColor = '#d33';
            } else if (action === 'approve') {
                title = 'Approve Vendors?';
                text = `Are you sure you want to approve ${selectedVendors.length} selected vendor(s)?`;
                confirmButtonColor = '#28a745';
            } else if (action === 'suspend') {
                title = 'Suspend Vendors?';
                text = `Are you sure you want to suspend ${selectedVendors.length} selected vendor(s)?`;
                confirmButtonColor = '#ffc107';
            }

            Swal.fire({
                title: title,
                text: text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: confirmButtonColor,
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, proceed!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#bulkAction').val(action);
                    $('#bulkVendorIds').val(JSON.stringify(selectedVendors));

                    $.ajax({
                        url: $('#bulkActionForm').attr('action'),
                        type: 'POST',
                        data: $('#bulkActionForm').serialize(),
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success!',
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
                                text: xhr.responseJSON?.message || 'Failed to perform action.',
                                confirmButtonColor: '#d33'
                            });
                        }
                    });
                }
            });
        }
    </script>
@endpush