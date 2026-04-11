{{-- resources/views/admin/pages/vendors/staff.blade.php --}}

@extends('management.layouts.app')

@section('title', 'Staff Members - ' . $shop->shop_name)

@section('content')
    <div class="page-content">
        <div class="page-container">

            <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                <div class="flex-grow-1">
                    <h4 class="fs-18 text-uppercase fw-bold mb-0">Staff Members</h4>
                    <p class="text-muted mb-0">{{ $shop->shop_name }}</p>
                </div>
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.vendors.index') }}">Vendors</a></li>
                        <li class="breadcrumb-item"><a
                                href="{{ route('admin.vendors.show', $shop->id) }}">{{ $shop->shop_name }}</a></li>
                        <li class="breadcrumb-item active">Staff</li>
                    </ol>
                </div>
            </div>

            {{-- Statistics Cards - 4 Boxes --}}
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
                                    <h5 class="text-muted fs-13 text-uppercase">Roles</h5>
                                    <h3 class="mb-0 fw-bold text-warning">{{ $stats['roles'] ?? 0 }}</h3>
                                </div>
                                <div class="avatar-sm bg-warning-subtle rounded-circle p-2">
                                    <i class="ti ti-briefcase fs-24 text-warning"></i>
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
                            <div>
                                <a href="{{ route('admin.vendors.show', $shop->id) }}" class="btn btn-secondary me-2">
                                    <i class="ti ti-arrow-left"></i> Back to Shop
                                </a>
                                @can('create_staff')
                                    <a href="{{ route('admin.vendors.staff.create', $shop->id) }}" class="btn btn-primary">
                                        <i class="ti ti-plus me-1"></i> Add New Staff
                                    </a>
                                @endcan
                            </div>
                        </div>
                        <div class="card-body">

                            {{-- Search and Filter --}}
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <form action="{{ route('admin.vendors.staff', $shop->id) }}" method="GET"
                                        class="d-flex gap-2" id="searchForm">
                                        <input type="text" name="search" class="form-control" id="searchInput"
                                            placeholder="Search by name or email..." value="{{ request('search') }}">
                                        <button type="submit" class="btn btn-primary" id="searchBtn">
                                            <i class="ti ti-search"></i>
                                        </button>
                                        @if (request('search'))
                                            <a href="{{ route('admin.vendors.staff', $shop->id) }}"
                                                class="btn btn-secondary" id="clearSearch">Clear</a>
                                        @endif
                                    </form>
                                </div>
                                <div class="col-md-6 text-end">
                                    <div class="btn-group me-2">
                                        <button type="button" class="btn btn-outline-secondary dropdown-toggle"
                                            data-bs-toggle="dropdown">
                                            Filter by Role
                                        </button>
                                        <ul class="dropdown-menu" id="roleFilter">
                                            <li><a class="dropdown-item {{ !request('role') ? 'active' : '' }}"
                                                    href="#" data-role="">All Roles</a></li>
                                            <li><a class="dropdown-item {{ request('role') == 'vendor' ? 'active' : '' }}"
                                                    href="#" data-role="vendor">Vendor</a></li>
                                            <li><a class="dropdown-item {{ request('role') == 'store_owner' ? 'active' : '' }}"
                                                    href="#" data-role="store_owner">Store Owner</a></li>
                                            <li><a class="dropdown-item {{ request('role') == 'manager' ? 'active' : '' }}"
                                                    href="#" data-role="manager">Manager</a></li>
                                            <li><a class="dropdown-item {{ request('role') == 'inventory' ? 'active' : '' }}"
                                                    href="#" data-role="inventory">Inventory</a></li>
                                            <li><a class="dropdown-item {{ request('role') == 'fulfillment' ? 'active' : '' }}"
                                                    href="#" data-role="fulfillment">Fulfillment</a></li>
                                            <li><a class="dropdown-item {{ request('role') == 'support' ? 'active' : '' }}"
                                                    href="#" data-role="support">Support</a></li>
                                            <li><a class="dropdown-item {{ request('role') == 'staff' ? 'active' : '' }}"
                                                    href="#" data-role="staff">Staff</a></li>
                                        </ul>
                                    </div>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-outline-secondary dropdown-toggle"
                                            data-bs-toggle="dropdown">
                                            Filter by Status
                                        </button>
                                        <ul class="dropdown-menu" id="statusFilter">
                                            <li><a class="dropdown-item {{ !request('status') ? 'active' : '' }}"
                                                    href="#" data-status="">All</a></li>
                                            <li><a class="dropdown-item {{ request('status') == 'active' ? 'active' : '' }}"
                                                    href="#" data-status="active">Active</a></li>
                                            <li><a class="dropdown-item {{ request('status') == 'inactive' ? 'active' : '' }}"
                                                    href="#" data-status="inactive">Inactive</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            {{-- Bulk Actions --}}
                            @canany(['activate_staff', 'deactivate_staff', 'delete_staff'])
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <div class="btn-group">
                                            @can('activate_staff')
                                                <button type="button" class="btn btn-outline-success btn-sm"
                                                    onclick="bulkAction('activate')">
                                                    <i class="ti ti-check"></i> Activate Selected
                                                </button>
                                            @endcan
                                            @can('deactivate_staff')
                                                <button type="button" class="btn btn-outline-warning btn-sm"
                                                    onclick="bulkAction('deactivate')">
                                                    <i class="ti ti-x"></i> Deactivate Selected
                                                </button>
                                            @endcan
                                            @can('delete_staff')
                                                <button type="button" class="btn btn-outline-danger btn-sm"
                                                    onclick="bulkAction('delete')">
                                                    <i class="ti ti-trash"></i> Delete Selected
                                                </button>
                                            @endcan
                                        </div>
                                    </div>
                                </div>
                            @endcanany

                            {{-- Staff Table --}}
                            <div class="table-responsive" id="staffTableContainer">
                                @include('admin.pages.vendors.partials.staff-table', [
                                    'staffMembers' => $staffMembers,
                                ])
                            </div>

                            {{-- Pagination --}}
                            <div class="mt-3" id="paginationContainer">
                                <div class="d-flex justify-content-end">
                                    {{ $staffMembers->appends(request()->query())->links('pagination::bootstrap-5') }}
                                </div>
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

    {{-- Status Form --}}
    <form id="statusForm" method="POST" style="display: none;">
        @csrf
    </form>

    {{-- Bulk Action Form --}}
    <form id="bulkActionForm" method="POST" action="{{ route('admin.vendors.staff.bulk-action', $shop->id) }}"
        style="display: none;">
        @csrf
        <input type="hidden" name="action" id="bulkAction">
        <input type="hidden" name="staff_ids" id="bulkStaffIds">
    </form>
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
                page: {{ request('page', 1) }}
            };

            // Search with debounce
            let searchTimer;
            $('#searchInput').on('keyup', function() {
                clearTimeout(searchTimer);
                searchTimer = setTimeout(() => {
                    currentFilters.search = $(this).val();
                    currentFilters.page = 1;
                    loadStaff();
                }, 500);
            });

            $('#searchForm').on('submit', function(e) {
                e.preventDefault();
                currentFilters.search = $('#searchInput').val();
                currentFilters.page = 1;
                loadStaff();
            });

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
                $('#roleFilter').closest('.btn-group').find('.dropdown-toggle').html(buttonText +
                    ' <i class="ti ti-chevron-down"></i>');
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
                $('#statusFilter').closest('.btn-group').find('.dropdown-toggle').html(buttonText +
                    ' <i class="ti ti-chevron-down"></i>');
                currentFilters.status = status;
                currentFilters.page = 1;
                loadStaff();
            });

            function loadStaff() {
                $.ajax({
                    url: '{{ route('admin.vendors.staff', $shop->id) }}',
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
                updateBulkActionButtons();
            });

            $(document).on('change', '.staff-checkbox', function() {
                let allChecked = $('.staff-checkbox:checked').length === $('.staff-checkbox').length;
                $('#selectAll').prop('checked', allChecked);
                updateBulkActionButtons();
            });

            function updateBulkActionButtons() {
                let selectedCount = $('.staff-checkbox:checked').length;
                if (selectedCount > 0) {
                    $('.btn-outline-success, .btn-outline-warning, .btn-outline-danger').prop('disabled', false);
                } else {
                    $('.btn-outline-success, .btn-outline-warning, .btn-outline-danger').prop('disabled', true);
                }
            }

            updateBulkActionButtons();
            $('[data-bs-toggle="tooltip"]').tooltip();
        });

        // View Staff Details
        function viewStaff(staffId, staffName) {
            Swal.fire({
                title: 'Staff Details',
                text: `Viewing details of ${staffName}`,
                icon: 'info',
                confirmButtonText: 'OK'
            });
            // You can redirect to a show page or open a modal
            // window.location.href = '{{ url('admin/vendors/staff') }}/' + staffId;
        }

        // Activate Staff
        function activateStaff(staffId, staffName) {
            Swal.fire({
                title: 'Activate Staff',
                text: `Are you sure you want to activate "${staffName}"?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, activate!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Processing...',
                        allowOutsideClick: false,
                        didOpen: () => Swal.showLoading()
                    });
                    $.ajax({
                        url: '{{ url('admin/vendors/staff') }}/' + staffId + '/activate',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Activated!',
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
            });
        }

        // Deactivate Staff
        function deactivateStaff(staffId, staffName) {
            Swal.fire({
                title: 'Deactivate Staff',
                text: `Are you sure you want to deactivate "${staffName}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ffc107',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, deactivate!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Processing...',
                        allowOutsideClick: false,
                        didOpen: () => Swal.showLoading()
                    });
                    $.ajax({
                        url: '{{ url('admin/vendors/staff') }}/' + staffId + '/deactivate',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deactivated!',
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
            });
        }

        // Delete Staff
        function deleteStaff(staffId, staffName) {
            Swal.fire({
                title: 'Delete Staff',
                text: `Are you sure you want to delete "${staffName}"? This action cannot be undone!`,
                icon: 'error',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    let form = $('#deleteForm');
                    form.attr('action', '{{ url('admin/vendors/staff') }}/' + staffId);
                    form.submit();
                }
            });
        }

        // Bulk Action
        function bulkAction(action) {
            let selectedStaff = [];
            $('.staff-checkbox:checked').each(function() {
                let staffId = $(this).val();
                let staffName = $(this).closest('tr').find('.fw-semibold').first().text() || 'Staff';
                selectedStaff.push({
                    id: staffId,
                    name: staffName
                });
            });

            if (selectedStaff.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'No Selection',
                    text: 'Please select at least one staff member.',
                    timer: 2000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
                return;
            }

            let actionText = '',
                actionColor = '',
                icon = '',
                confirmText = '';
            switch (action) {
                case 'activate':
                    actionText = 'activate';
                    actionColor = '#198754';
                    icon = 'question';
                    confirmText = 'Yes, activate them!';
                    break;
                case 'deactivate':
                    actionText = 'deactivate';
                    actionColor = '#ffc107';
                    icon = 'warning';
                    confirmText = 'Yes, deactivate them!';
                    break;
                case 'delete':
                    actionText = 'delete';
                    actionColor = '#d33';
                    icon = 'error';
                    confirmText = 'Yes, delete them!';
                    break;
            }

            let staffNames = selectedStaff.map(s => s.name).join(', ');
            let message = `Are you sure you want to ${actionText} ${selectedStaff.length} selected staff member(s)?`;
            if (action === 'delete') message =
                `Are you sure you want to ${actionText} ${selectedStaff.length} selected staff member(s)? This action cannot be undone!`;

            Swal.fire({
                title: `${actionText.charAt(0).toUpperCase() + actionText.slice(1)} Staff`,
                html: `${message}<br><br><strong>Selected staff:</strong><br>${staffNames}`,
                icon: icon,
                showCancelButton: true,
                confirmButtonColor: actionColor,
                cancelButtonColor: '#6c757d',
                confirmButtonText: confirmText,
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Processing...',
                        text: `Please wait while we ${actionText} ${selectedStaff.length} staff member(s)`,
                        allowOutsideClick: false,
                        didOpen: () => Swal.showLoading()
                    });
                    $('#bulkAction').val(action);
                    $('#bulkStaffIds').val(JSON.stringify(selectedStaff.map(s => s.id)));
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
                                }).then(() => location.reload());
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: response.message
                                });
                            }
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: xhr.responseJSON?.message || 'Something went wrong.'
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
        .empty-state {
            text-align: center;
            padding: 40px 20px;
        }

        .avatar-md {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
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

        .badge.bg-primary-subtle {
            background-color: rgba(13, 110, 253, 0.1);
            border: 1px solid rgba(13, 110, 253, 0.2);
        }

        .badge.bg-info-subtle {
            background-color: rgba(13, 202, 240, 0.1);
            border: 1px solid rgba(13, 202, 240, 0.2);
        }

        .btn-soft-primary:hover {
            background-color: #0d6efd;
            color: white;
        }

        .btn-soft-success:hover {
            background-color: #198754;
            color: white;
        }

        .btn-soft-danger:hover {
            background-color: #dc3545;
            color: white;
        }

        .btn-soft-warning:hover {
            background-color: #ffc107;
            color: #000;
        }

        .btn-soft-info:hover {
            background-color: #0dcaf0;
            color: #000;
        }

        .btn-soft-danger[disabled] {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .pagination {
            margin-bottom: 0;
        }

        .page-link {
            cursor: pointer;
        }
    </style>
@endpush
