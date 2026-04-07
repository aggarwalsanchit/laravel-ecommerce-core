{{-- resources/views/admin/pages/users/index.blade.php --}}

@extends('management.layouts.app')

@section('title', ($websiteSettings->website_name ?? 'Boron') . ' - Users')

@section('content')
    <!-- ============================================================== -->
    <!-- Start Page Content here -->
    <!-- ============================================================== -->

    <div class="page-content">

        <!-- Start Content-->
        <div class="page-container">

            <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                <div class="flex-grow-1">
                    <h4 class="fs-18 text-uppercase fw-bold mb-0">Users</h4>
                </div>

                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a
                                href="{{ route('admin.dashboard') }}">{{ $websiteSettings->website_name ?? 'Boron' }}</a>
                        </li>
                        <li class="breadcrumb-item active">Users</li>
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
                                    <h5 class="text-muted fs-13 text-uppercase">Total Users</h5>
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
                                    <h5 class="text-muted fs-13 text-uppercase">Active Users</h5>
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
                                    <h5 class="text-muted fs-13 text-uppercase">Inactive Users</h5>
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
                                    <h5 class="text-muted fs-13 text-uppercase">Total Roles</h5>
                                    <h3 class="mb-0 fw-bold text-warning">{{ $stats['roles'] ?? 0 }}</h3>
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
                            <h3 class="card-title">User Management</h3>
                            @can('create_users')
                                <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                                    <i class="ti ti-plus me-1"></i> Add New User
                                </a>
                            @endcan
                        </div>
                        <div class="card-body">

                            {{-- Search and Filter --}}
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <form action="{{ route('admin.users.index') }}" method="GET" class="d-flex gap-2"
                                        id="searchForm">
                                        <input type="text" name="search" class="form-control" id="searchInput"
                                            placeholder="Search by name or email..." value="{{ request('search') }}">
                                        <button type="submit" class="btn btn-primary" id="searchBtn">
                                            <i class="ti ti-search"></i>
                                        </button>
                                        @if (request('search'))
                                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary"
                                                id="clearSearch">Clear</a>
                                        @endif
                                    </form>
                                </div>
                                <div class="col-md-6 text-end">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-outline-secondary dropdown-toggle"
                                            data-bs-toggle="dropdown">
                                            Filter by Role
                                        </button>
                                        <ul class="dropdown-menu" id="roleFilter">
                                            <li><a class="dropdown-item {{ !request('role') ? 'active' : '' }}"
                                                    href="#" data-role="">All Users</a></li>
                                            @foreach ($roles ?? [] as $role)
                                                <li><a class="dropdown-item {{ request('role') == $role->name ? 'active' : '' }}"
                                                        href="#"
                                                        data-role="{{ $role->name }}">{{ $role->name }}</a></li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    <div class="btn-group ms-2">
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
                            @canany(['activate_users', 'deactivate_users', 'delete_users'])
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <div class="btn-group">
                                            @can('activate_users')
                                                <button type="button" class="btn btn-outline-success btn-sm"
                                                    onclick="bulkAction('activate')">
                                                    <i class="ti ti-check"></i> Activate Selected
                                                </button>
                                            @endcan
                                            @can('deactivate_users')
                                                <button type="button" class="btn btn-outline-warning btn-sm"
                                                    onclick="bulkAction('deactivate')">
                                                    <i class="ti ti-x"></i> Deactivate Selected
                                                </button>
                                            @endcan
                                            @can('delete_users')
                                                <button type="button" class="btn btn-outline-danger btn-sm"
                                                    onclick="bulkAction('delete')">
                                                    <i class="ti ti-trash"></i> Delete Selected
                                                </button>
                                            @endcan
                                        </div>
                                    </div>
                                </div>
                            @endcanany

                            {{-- Users Table --}}
                            <div class="table-responsive">
                                @include('admin.pages.users.partials.users-table', ['users' => $users])
                            </div>

                            {{-- Pagination --}}
                            <div class="mt-3">
                                <div class="d-flex justify-content-end">
                                    {{ $users->appends(request()->query())->links('pagination::bootstrap-5') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div> <!-- container -->
    </div> <!-- page-content -->

    {{-- Delete Form --}}
    <form id="deleteForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    {{-- Activate/Deactivate Form --}}
    <form id="statusForm" method="POST" style="display: none;">
        @csrf
    </form>

    {{-- Bulk Action Form --}}
    <form id="bulkActionForm" method="POST" action="{{ route('admin.users.bulk-action') }}" style="display: none;">
        @csrf
        <input type="hidden" name="action" id="bulkAction">
        <input type="hidden" name="user_ids" id="bulkUserIds">
    </form>
@endsection

@push('scripts')
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
                    loadUsers();
                }, 500);
            });

            // Prevent form submission
            $('#searchForm').on('submit', function(e) {
                e.preventDefault();
                currentFilters.search = $('#searchInput').val();
                currentFilters.page = 1;
                loadUsers();
            });

            // Clear search
            $('#clearSearch').on('click', function(e) {
                e.preventDefault();
                $('#searchInput').val('');
                currentFilters.search = '';
                currentFilters.page = 1;
                loadUsers();
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
                loadUsers();
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
                loadUsers();
            });

            // Load users via AJAX
            function loadUsers() {
                $.ajax({
                    url: '{{ route('admin.users.index') }}',
                    type: 'GET',
                    data: currentFilters,
                    beforeSend: function() {
                        $('#usersTableContainer').html(
                            '<div class="text-center py-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>'
                            );
                    },
                    success: function(response) {
                        $('#usersTableContainer').html(response.table);
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
                        console.error('Error loading users:', xhr);
                        $('#usersTableContainer').html(
                            '<div class="alert alert-danger">Error loading users. Please try again.</div>'
                            );
                    }
                });
            }

            // Select All Checkbox
            $(document).on('change', '#selectAll', function() {
                $('.user-checkbox').prop('checked', $(this).prop('checked'));
                updateBulkActionButtons();
            });

            // Individual checkbox change
            $(document).on('change', '.user-checkbox', function() {
                let allChecked = $('.user-checkbox:checked').length === $('.user-checkbox').length;
                $('#selectAll').prop('checked', allChecked);
                updateBulkActionButtons();
            });

            // Update bulk action buttons state
            function updateBulkActionButtons() {
                let selectedCount = $('.user-checkbox:checked').length;
                if (selectedCount > 0) {
                    $('.btn-outline-success, .btn-outline-warning, .btn-outline-danger').prop('disabled', false);
                } else {
                    $('.btn-outline-success, .btn-outline-warning, .btn-outline-danger').prop('disabled', true);
                }
            }

            // Initialize
            updateBulkActionButtons();
            $('[data-bs-toggle="tooltip"]').tooltip();
        });

        // ========== USER ACTIONS ==========

        // Delete User
        function confirmDelete(userId, userName) {
            Swal.fire({
                title: 'Delete User',
                text: `Are you sure you want to delete "${userName}"? This action cannot be undone!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    let form = $('#deleteForm');
                    form.attr('action', '{{ url('admin/users') }}/' + userId);
                    form.submit();
                }
            });
        }

        // Activate User
        function confirmActivate(userId, userName) {
            Swal.fire({
                title: 'Activate User',
                text: `Are you sure you want to activate "${userName}"? They will be able to login.`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, activate!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    let form = $('#statusForm');
                    form.attr('action', '{{ url('admin/users') }}/' + userId + '/activate');
                    form.submit();
                }
            });
        }

        // Deactivate User
        function confirmDeactivate(userId, userName) {
            Swal.fire({
                title: 'Deactivate User',
                text: `Are you sure you want to deactivate "${userName}"? They will not be able to login.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ffc107',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, deactivate!',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    let form = $('#statusForm');
                    form.attr('action', '{{ url('admin/users') }}/' + userId + '/deactivate');
                    form.submit();
                }
            });
        }

        // Impersonate User
        function impersonateUser(userId, userName) {
            Swal.fire({
                title: 'Impersonate User',
                html: `You are about to login as <strong>${userName}</strong>.<br><br>You can revert back from the user dashboard.`,
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#0d6efd',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, login!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '{{ url('admin/impersonate') }}/' + userId;
                }
            });
        }

        // Bulk Action
        function bulkAction(action) {
            let selectedUsers = [];
            $('.user-checkbox:checked').each(function() {
                let userId = $(this).val();
                let userName = $(this).closest('tr').find('.fw-semibold').first().text() || 'User';
                selectedUsers.push({
                    id: userId,
                    name: userName
                });
            });

            if (selectedUsers.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'No Selection',
                    text: 'Please select at least one user.',
                    timer: 2000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
                return;
            }

            let actionText = '';
            let actionColor = '';
            let icon = '';
            let confirmText = '';

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

            let userNames = selectedUsers.map(u => u.name).join(', ');
            let message = `Are you sure you want to ${actionText} ${selectedUsers.length} selected user(s)?`;
            if (action === 'delete') {
                message =
                    `Are you sure you want to ${actionText} ${selectedUsers.length} selected user(s)? This action cannot be undone!`;
            }

            Swal.fire({
                title: `${actionText.charAt(0).toUpperCase() + actionText.slice(1)} Users`,
                html: `${message}<br><br><strong>Selected users:</strong><br>${userNames}`,
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
                        text: `Please wait while we ${actionText} ${selectedUsers.length} user(s)`,
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    $('#bulkAction').val(action);
                    $('#bulkUserIds').val(JSON.stringify(selectedUsers.map(u => u.id)));

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
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: response.message || 'Error processing bulk action'
                                });
                            }
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: xhr.responseJSON?.message ||
                                    'Something went wrong. Please try again.'
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
