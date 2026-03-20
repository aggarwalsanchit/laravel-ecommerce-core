@extends('admin.layouts.app')

@section('title', 'Users')

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
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Boron</a></li>

                        <li class="breadcrumb-item active">Users</li>
                    </ol>
                </div>
            </div>




            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="card-title">User Management</h3>
                            @can('create users')
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
                            @canany(['activate users', 'deactivate users', 'delete users'])
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

                            <div class="table-responsive" id="usersTableContainer">
                                @include('admin.pages.users.partials.users-table', ['users' => $users])
                            </div>

                            <div class="card-footer" id="paginationContainer">
                                <div class="d-flex justify-content-end">
                                    {{ $users->appends(request()->query())->links('pagination::bootstrap-5') }}
                                </div>
                            </div>
                        </div> <!-- end card-->
                    </div><!-- end col -->
                </div><!-- end row -->

            </div> <!-- container -->

            <!-- ============================================================== -->
            <!-- End Page content -->
            <!-- ============================================================== -->
        </div>
    </div>

    {{-- Delete Form --}}
    <form id="deleteForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    {{-- Activate Form --}}
    <form id="activateForm" method="POST" style="display: none;">
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            let currentFilters = {
                search: '{{ request('search') }}',
                role: '{{ request('role') }}',
                status: '{{ request('status') }}',
                page: 1
            };

            // Search with debounce
            let searchTimer;
            $('#searchInput').on('keyup', function(e) {
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

                // Update active class
                $('#roleFilter .dropdown-item').removeClass('active');
                $(this).addClass('active');

                // Update button text
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

                // Update active class
                $('#statusFilter .dropdown-item').removeClass('active');
                $(this).addClass('active');

                // Update button text
                let buttonText = $(this).text();
                $('#statusFilter').closest('.btn-group').find('.dropdown-toggle').html(buttonText +
                    ' <i class="ti ti-chevron-down"></i>');

                currentFilters.status = status;
                currentFilters.page = 1;
                loadUsers();
            });

            // Pagination click handler
            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault();
                let page = $(this).attr('href').split('page=')[1];
                currentFilters.page = page;
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

                        // Reinitialize tooltips
                        $('[data-bs-toggle="tooltip"]').tooltip();

                        // Update URL without reload
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
            $('#selectAll').on('change', function() {
                $('.user-checkbox').prop('checked', $(this).prop('checked'));
            });

            // Individual checkbox change
            $(document).on('change', '.user-checkbox', function() {
                let allChecked = $('.user-checkbox:checked').length === $('.user-checkbox').length;
                $('#selectAll').prop('checked', allChecked);
            });

            // Initialize tooltips
            $('[data-bs-toggle="tooltip"]').tooltip();
        });

        // Confirm Delete
        function confirmDelete(userId) {
            if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
                let form = $('#deleteForm');
                form.attr('action', '{{ url('admin/users') }}/' + userId);
                form.submit();
            }
        }

        // Confirm Activate
        function confirmActivate(userId) {
            if (confirm('Are you sure you want to activate this user?')) {
                let form = $('#activateForm');
                form.attr('action', '{{ url('admin/users') }}/' + userId + '/activate');

                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: form.serialize(),
                    success: function(response) {
                        if (response.success) {
                            // Reload users after activation
                            location.reload();
                        } else {
                            alert('Error activating user');
                        }
                    },
                    error: function() {
                        alert('Error activating user');
                    }
                });
            }
        }

        // Bulk Actions
        function bulkAction(action) {
            let selectedUsers = [];
            $('.user-checkbox:checked').each(function() {
                selectedUsers.push($(this).val());
            });

            if (selectedUsers.length === 0) {
                alert('Please select at least one user.');
                return;
            }

            let confirmMessage = '';
            switch (action) {
                case 'activate':
                    confirmMessage = 'Are you sure you want to activate ' + selectedUsers.length + ' selected users?';
                    break;
                case 'deactivate':
                    confirmMessage = 'Are you sure you want to deactivate ' + selectedUsers.length + ' selected users?';
                    break;
                case 'delete':
                    confirmMessage = 'Are you sure you want to delete ' + selectedUsers.length +
                        ' selected users? This action cannot be undone.';
                    break;
            }

            if (confirm(confirmMessage)) {
                $('#bulkAction').val(action);
                $('#bulkUserIds').val(JSON.stringify(selectedUsers));

                $.ajax({
                    url: $('#bulkActionForm').attr('action'),
                    type: 'POST',
                    data: $('#bulkActionForm').serialize(),
                    success: function(response) {
                        if (response.success) {
                            location.reload();
                        } else {
                            alert('Error processing bulk action');
                        }
                    },
                    error: function() {
                        alert('Error processing bulk action');
                    }
                });
            }
        }

        // Impersonate user
        function impersonateUser(userId) {
            if (confirm('Login as this user? You can revert back from the user dashboard.')) {
                window.location.href = '{{ url('admin/impersonate') }}/' + userId;
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

        .avatar-md {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
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

        /* Role and Permission badges */
        .badge.bg-primary-subtle {
            background-color: rgba(13, 110, 253, 0.1);
            border: 1px solid rgba(13, 110, 253, 0.2);
        }

        .badge.bg-info-subtle {
            background-color: rgba(13, 202, 240, 0.1);
            border: 1px solid rgba(13, 202, 240, 0.2);
        }

        /* Hover effects */
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

        /* Disabled button */
        .btn-soft-danger[disabled] {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Pagination styles */
        .pagination {
            margin-bottom: 0;
        }

        .page-link {
            cursor: pointer;
        }
    </style>
@endpush
