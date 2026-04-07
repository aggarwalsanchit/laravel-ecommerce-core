{{-- resources/views/vendor/staff/index.blade.php --}}

@extends('management.layouts.app')

@section('title', 'Staff Management')

@section('content')
    <!-- ============================================================== -->
    <!-- Start Page Content here -->
    <!-- ============================================================== -->

    <div class="page-content">

        <!-- Start Content-->
        <div class="page-container">

            <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                <div class="flex-grow-1">
                    <h4 class="fs-18 text-uppercase fw-bold mb-0">Staff Management</h4>
                </div>

                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Vendor</a></li>
                        <li class="breadcrumb-item active">Staff</li>
                    </ol>
                </div>
            </div>

            {{-- Statistics Cards --}}
            <div class="row">
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="text-muted fs-13 text-uppercase">Total Staff</h5>
                                    <h3 class="mb-0 fw-bold">{{ $stats['total'] ?? 0 }}</h3>
                                </div>
                                <div class="avatar-sm bg-primary-subtle rounded-circle p-2">
                                    <i class="ti ti-users fs-24 text-primary"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="text-muted fs-13 text-uppercase">Active</h5>
                                    <h3 class="mb-0 fw-bold text-success">{{ $stats['active'] ?? 0 }}</h3>
                                </div>
                                <div class="avatar-sm bg-success-subtle rounded-circle p-2">
                                    <i class="ti ti-circle-check fs-24 text-success"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="text-muted fs-13 text-uppercase">Inactive</h5>
                                    <h3 class="mb-0 fw-bold text-danger">{{ $stats['inactive'] ?? 0 }}</h3>
                                </div>
                                <div class="avatar-sm bg-danger-subtle rounded-circle p-2">
                                    <i class="ti ti-circle-x fs-24 text-danger"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="text-muted fs-13 text-uppercase">Administrators</h5>
                                    <h3 class="mb-0 fw-bold text-warning">{{ $stats['admins'] ?? 0 }}</h3>
                                </div>
                                <div class="avatar-sm bg-warning-subtle rounded-circle p-2">
                                    <i class="ti ti-shield fs-24 text-warning"></i>
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
                            <h3 class="card-title">Staff Management</h3>
                            {{-- @can('create_staff', 'vendor') --}}
                            <a href="{{ route('vendor.staff.create') }}" class="btn btn-primary">
                                <i class="ti ti-plus me-1"></i> Add New Staff
                            </a>
                            {{-- @endcan --}}
                        </div>
                        <div class="card-body">

                            {{-- Search and Advanced Filters --}}
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <form action="{{ route('vendor.staff.index') }}" method="GET" class="d-flex gap-2"
                                        id="searchForm">
                                        <input type="text" name="search" class="form-control" id="searchInput"
                                            placeholder="Search by name, email or phone..." value="{{ request('search') }}">
                                        <button type="submit" class="btn btn-primary" id="searchBtn">
                                            <i class="ti ti-search"></i>
                                        </button>
                                        @if (request('search'))
                                            <a href="{{ route('vendor.staff.index') }}" class="btn btn-secondary"
                                                id="clearSearch">Clear</a>
                                        @endif
                                    </form>
                                </div>
                                <div class="col-md-8 text-end">
                                    <div class="btn-group me-2">
                                        <button type="button" class="btn btn-outline-secondary dropdown-toggle"
                                            data-bs-toggle="dropdown">
                                            <i class="ti ti-role"></i> Role
                                        </button>
                                        <ul class="dropdown-menu" id="roleFilter">
                                            <li><a class="dropdown-item {{ !request('role') ? 'active' : '' }}"
                                                    href="#" data-role="">All Roles</a></li>
                                            <li><a class="dropdown-item {{ request('role') == 'admin' ? 'active' : '' }}"
                                                    href="#" data-role="admin">Administrator</a></li>
                                            <li><a class="dropdown-item {{ request('role') == 'manager' ? 'active' : '' }}"
                                                    href="#" data-role="manager">Store Manager</a></li>
                                            <li><a class="dropdown-item {{ request('role') == 'inventory' ? 'active' : '' }}"
                                                    href="#" data-role="inventory">Inventory Manager</a></li>
                                            <li><a class="dropdown-item {{ request('role') == 'fulfillment' ? 'active' : '' }}"
                                                    href="#" data-role="fulfillment">Fulfillment Executive</a></li>
                                            <li><a class="dropdown-item {{ request('role') == 'support' ? 'active' : '' }}"
                                                    href="#" data-role="support">Support Staff</a></li>
                                        </ul>
                                    </div>
                                    <div class="btn-group me-2">
                                        <button type="button" class="btn btn-outline-secondary dropdown-toggle"
                                            data-bs-toggle="dropdown">
                                            <i class="ti ti-status"></i> Status
                                        </button>
                                        <ul class="dropdown-menu" id="statusFilter">
                                            <li><a class="dropdown-item {{ !request('status') ? 'active' : '' }}"
                                                    href="#" data-status="">All Status</a></li>
                                            <li><a class="dropdown-item {{ request('status') == 'active' ? 'active' : '' }}"
                                                    href="#" data-status="active">Active</a></li>
                                            <li><a class="dropdown-item {{ request('status') == 'inactive' ? 'active' : '' }}"
                                                    href="#" data-status="inactive">Inactive</a></li>
                                        </ul>
                                    </div>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-outline-secondary dropdown-toggle"
                                            data-bs-toggle="dropdown">
                                            <i class="ti ti-calendar"></i> Date Joined
                                        </button>
                                        <ul class="dropdown-menu p-3" id="dateFilter" style="min-width: 250px;">
                                            <li>
                                                <div class="mb-2">
                                                    <label class="form-label">From Date</label>
                                                    <input type="date" name="from_date" class="form-control"
                                                        id="fromDate" value="{{ request('from_date') }}">
                                                </div>
                                                <div class="mb-2">
                                                    <label class="form-label">To Date</label>
                                                    <input type="date" name="to_date" class="form-control"
                                                        id="toDate" value="{{ request('to_date') }}">
                                                </div>
                                                <div class="d-flex gap-2">
                                                    <button type="button" class="btn btn-primary btn-sm w-100"
                                                        id="applyDateFilter">Apply</button>
                                                    <button type="button" class="btn btn-secondary btn-sm w-100"
                                                        id="clearDateFilter">Clear</button>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            {{-- Bulk Actions --}}
                            @canany(['edit_staff', 'delete_staff'], 'vendor')
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-outline-success btn-sm"
                                                onclick="bulkAction('activate')">
                                                <i class="ti ti-check"></i> Activate Selected
                                            </button>
                                            <button type="button" class="btn btn-outline-warning btn-sm"
                                                onclick="bulkAction('deactivate')">
                                                <i class="ti ti-x"></i> Deactivate Selected
                                            </button>
                                            <button type="button" class="btn btn-outline-danger btn-sm"
                                                onclick="bulkAction('delete')">
                                                <i class="ti ti-trash"></i> Delete Selected
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endcanany

                            <div class="table-responsive" id="staffTableContainer">
                                @include('marketplace.pages.staff.partials.staff-table', [
                                    'staffs' => $staffs,
                                ])
                            </div>

                            <div class="card-footer" id="paginationContainer">
                                <div class="d-flex justify-content-end">
                                    {{ $staffs->appends(request()->query())->links('pagination::bootstrap-5') }}
                                </div>
                            </div>
                        </div> <!-- end card-body -->
                    </div> <!-- end card -->
                </div> <!-- end col -->
            </div> <!-- end row -->

        </div> <!-- container -->

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->
    </div>

    {{-- Delete Form --}}
    <form id="deleteForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    {{-- Status Form --}}
    <form id="statusForm" method="POST" style="display: none;">
        @csrf
    </form>

    {{-- Bulk Action Form --}}
    <form id="bulkActionForm" method="POST" action="{{ route('vendor.staff.bulk-action') }}" style="display: none;">
        @csrf
        <input type="hidden" name="action" id="bulkAction">
        <input type="hidden" name="staff_ids" id="bulkStaffIds">
    </form>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            let currentFilters = {
                search: '{{ request('search') }}',
                role: '{{ request('role') }}',
                status: '{{ request('status') }}',
                from_date: '{{ request('from_date') }}',
                to_date: '{{ request('to_date') }}',
                page: 1
            };

            // Search with debounce
            let searchTimer;
            $('#searchInput').on('keyup', function(e) {
                clearTimeout(searchTimer);
                searchTimer = setTimeout(() => {
                    currentFilters.search = $(this).val();
                    currentFilters.page = 1;
                    loadStaff();
                }, 500);
            });

            // Prevent form submission
            $('#searchForm').on('submit', function(e) {
                e.preventDefault();
                currentFilters.search = $('#searchInput').val();
                currentFilters.page = 1;
                loadStaff();
            });

            // Clear search
            $('#clearSearch').on('click', function(e) {
                e.preventDefault();
                $('#searchInput').val('');
                currentFilters.search = '';
                currentFilters.page = 1;
                loadStaff();
            });

            // Role filter
            $('#roleFilter .dropdown-item').on('click', function(e) {
                e.preventDefault();
                let role = $(this).data('role');

                $('#roleFilter .dropdown-item').removeClass('active');
                $(this).addClass('active');

                let buttonText = $(this).text();
                $('#roleFilter').closest('.btn-group').find('.dropdown-toggle').html(
                    '<i class="ti ti-role"></i> ' + buttonText);

                currentFilters.role = role;
                currentFilters.page = 1;
                loadStaff();
            });

            // Status filter
            $('#statusFilter .dropdown-item').on('click', function(e) {
                e.preventDefault();
                let status = $(this).data('status');

                $('#statusFilter .dropdown-item').removeClass('active');
                $(this).addClass('active');

                let buttonText = $(this).text();
                $('#statusFilter').closest('.btn-group').find('.dropdown-toggle').html(
                    '<i class="ti ti-status"></i> ' + buttonText);

                currentFilters.status = status;
                currentFilters.page = 1;
                loadStaff();
            });

            // Date filter apply
            $('#applyDateFilter').on('click', function() {
                currentFilters.from_date = $('#fromDate').val();
                currentFilters.to_date = $('#toDate').val();
                currentFilters.page = 1;
                loadStaff();
            });

            // Date filter clear
            $('#clearDateFilter').on('click', function() {
                $('#fromDate').val('');
                $('#toDate').val('');
                currentFilters.from_date = '';
                currentFilters.to_date = '';
                currentFilters.page = 1;
                loadStaff();
            });

            // Pagination click handler
            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault();
                let page = $(this).attr('href').split('page=')[1];
                currentFilters.page = page;
                loadStaff();
            });

            // Load staff via AJAX
            function loadStaff() {
                $.ajax({
                    url: '{{ route('vendor.staff.index') }}',
                    type: 'GET',
                    data: currentFilters,
                    beforeSend: function() {
                        $('#staffTableContainer').html(
                            '<div class="text-center py-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>'
                        );
                    },
                    success: function(response) {
                        $('#staffTableContainer').html(response.table);
                        $('#paginationContainer').html(response.pagination);

                        $('[data-bs-toggle="tooltip"]').tooltip();

                        let url = new URL(window.location);
                        url.searchParams.set('search', currentFilters.search || '');
                        url.searchParams.set('role', currentFilters.role || '');
                        url.searchParams.set('status', currentFilters.status || '');
                        url.searchParams.set('from_date', currentFilters.from_date || '');
                        url.searchParams.set('to_date', currentFilters.to_date || '');
                        url.searchParams.set('page', currentFilters.page);
                        window.history.pushState({}, '', url);
                    },
                    error: function(xhr) {
                        console.error('Error loading staff:', xhr);
                        $('#staffTableContainer').html(
                            '<div class="alert alert-danger">Error loading staff. Please try again.</div>'
                        );
                    }
                });
            }

            // Select All Checkbox
            $(document).on('change', '#selectAll', function() {
                $('.staff-checkbox').prop('checked', $(this).prop('checked'));
            });

            // Individual checkbox change
            $(document).on('change', '.staff-checkbox', function() {
                let allChecked = $('.staff-checkbox:checked').length === $('.staff-checkbox').length;
                $('#selectAll').prop('checked', allChecked);
            });

            // Initialize tooltips
            $('[data-bs-toggle="tooltip"]').tooltip();
        });

        // Confirm Delete
        function confirmDelete(staffId) {
            if (confirm('Are you sure you want to delete this staff member? This action cannot be undone.')) {
                let form = $('#deleteForm');
                form.attr('action', '{{ url('vendor/staff') }}/' + staffId);
                form.submit();
            }
        }

        // Toggle Status
        function toggleStatus(staffId, currentStatus) {
            let action = currentStatus ? 'deactivate' : 'activate';
            let confirmMessage = currentStatus ?
                'Are you sure you want to deactivate this staff member?' :
                'Are you sure you want to activate this staff member?';

            if (confirm(confirmMessage)) {
                let form = $('#statusForm');
                form.attr('action', '{{ url('vendor/staff') }}/' + staffId + '/toggle-status');

                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: form.serialize(),
                    success: function(response) {
                        if (response.success) {
                            location.reload();
                        } else {
                            alert(response.message || 'Error updating status');
                        }
                    },
                    error: function() {
                        alert('Error updating status');
                    }
                });
            }
        }

        // Bulk Actions
        function bulkAction(action) {
            let selectedStaff = [];
            $('.staff-checkbox:checked').each(function() {
                selectedStaff.push($(this).val());
            });

            if (selectedStaff.length === 0) {
                alert('Please select at least one staff member.');
                return;
            }

            let confirmMessage = '';
            switch (action) {
                case 'activate':
                    confirmMessage = 'Are you sure you want to activate ' + selectedStaff.length +
                        ' selected staff members?';
                    break;
                case 'deactivate':
                    confirmMessage = 'Are you sure you want to deactivate ' + selectedStaff.length +
                        ' selected staff members?';
                    break;
                case 'delete':
                    confirmMessage = 'Are you sure you want to delete ' + selectedStaff.length +
                        ' selected staff members? This action cannot be undone.';
                    break;
            }

            if (confirm(confirmMessage)) {
                $('#bulkAction').val(action);
                $('#bulkStaffIds').val(JSON.stringify(selectedStaff));

                $.ajax({
                    url: $('#bulkActionForm').attr('action'),
                    type: 'POST',
                    data: $('#bulkActionForm').serialize(),
                    success: function(response) {
                        if (response.success) {
                            location.reload();
                        } else {
                            alert(response.message || 'Error processing bulk action');
                        }
                    },
                    error: function() {
                        alert('Error processing bulk action');
                    }
                });
            }
        }

        // Resend Invitation
        function resendInvitation(staffId) {
            if (confirm('Resend invitation email to this staff member?')) {
                $.ajax({
                    url: '{{ url('vendor/staff') }}/' + staffId + '/resend-invitation',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            alert('Invitation sent successfully!');
                        } else {
                            alert('Error sending invitation');
                        }
                    },
                    error: function() {
                        alert('Error sending invitation');
                    }
                });
            }
        }
    </script>
@endpush

@push('styles')
    <style>
        .empty-state {
            text-align: center;
            padding: 40px 20px;
        }

        .avatar-sm {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .avatar-sm i {
            font-size: 24px;
        }

        .bg-primary-subtle {
            background-color: rgba(13, 110, 253, 0.1);
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

        .badge {
            font-weight: 500;
            letter-spacing: 0.3px;
        }

        .btn-icon {
            width: 32px;
            height: 32px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .table> :not(caption)>*>* {
            vertical-align: middle;
        }

        /* Role badges */
        .badge.bg-primary-subtle {
            background-color: rgba(13, 110, 253, 0.1);
            border: 1px solid rgba(13, 110, 253, 0.2);
            color: #0d6efd;
        }

        .badge.bg-info-subtle {
            background-color: rgba(13, 202, 240, 0.1);
            border: 1px solid rgba(13, 202, 240, 0.2);
            color: #0dcaf0;
        }

        .badge.bg-success-subtle {
            background-color: rgba(25, 135, 84, 0.1);
            border: 1px solid rgba(25, 135, 84, 0.2);
            color: #198754;
        }

        .badge.bg-warning-subtle {
            background-color: rgba(255, 193, 7, 0.1);
            border: 1px solid rgba(255, 193, 7, 0.2);
            color: #ffc107;
        }

        .badge.bg-danger-subtle {
            background-color: rgba(220, 53, 69, 0.1);
            border: 1px solid rgba(220, 53, 69, 0.2);
            color: #dc3545;
        }

        /* Pagination styles */
        .pagination {
            margin-bottom: 0;
        }

        .page-link {
            cursor: pointer;
        }

        /* Staff avatar */
        .staff-avatar {
            width: 40px;
            height: 40px;
            object-fit: cover;
        }

        .avatar-title {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }
    </style>
@endpush
