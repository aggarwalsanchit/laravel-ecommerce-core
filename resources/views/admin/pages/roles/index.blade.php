{{-- resources/views/admin/roles/index.blade.php --}}
@extends('management.layouts.app')

@section('title', 'Roles')

@section('content')
    <div class="page-content">
        <div class="page-container">
            <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                <div class="flex-grow-1">
                    <h4 class="fs-18 text-uppercase fw-bold mb-0">Roles</h4>
                </div>
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Roles</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="card-title">Role Management</h3>
                            @php $admin = Auth::guard('admin')->user(); @endphp
                            @if ($admin->can('create_roles'))
                                <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
                                    <i class="ti ti-plus me-1"></i> Add New Role
                                </a>
                            @endif
                        </div>
                        <div class="card-body">

                            {{-- Search and Filter --}}
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <form id="searchForm" class="d-flex gap-2">
                                        <input type="text" name="search" class="form-control" id="searchInput"
                                            placeholder="Search by role name..." value="{{ request('search') }}">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="ti ti-search"></i>
                                        </button>
                                        <button type="button" class="btn btn-secondary" id="clearSearch"
                                            style="display: none;">
                                            Clear
                                        </button>
                                    </form>
                                </div>
                                <div class="col-md-6 text-end">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-outline-secondary dropdown-toggle"
                                            data-bs-toggle="dropdown">
                                            Filter by Guard
                                        </button>
                                        <ul class="dropdown-menu" id="guardFilter">
                                            <li><a class="dropdown-item" href="#" data-guard="">All Guards</a></li>
                                            <li><a class="dropdown-item" href="#" data-guard="web">Web</a></li>
                                            <li><a class="dropdown-item" href="#" data-guard="admin">Admin</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            {{-- Bulk Actions --}}
                            @if ($admin->can('delete_roles'))
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-outline-danger btn-sm"
                                                onclick="bulkAction('delete')">
                                                <i class="ti ti-trash"></i> Delete Selected
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="table-responsive" id="rolesTableContainer">
                                @include('admin.pages.roles.partials.roles-table', ['roles' => $roles])
                            </div>

                            <div class="card-footer" id="paginationContainer">
                                <div class="d-flex justify-content-end">
                                    {{ $roles->appends(request()->query())->links('pagination::bootstrap-5') }}
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
        <form id="bulkActionForm" method="POST" action="{{ route('admin.roles.bulk-action') }}" style="display: none;">
            @csrf
            <input type="hidden" name="action" id="bulkAction">
            <input type="hidden" name="role_ids" id="bulkRoleIds">
        </form>
    @endsection

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            $(document).ready(function() {
                let currentFilters = {
                    search: '{{ request('search') }}',
                    guard: '{{ request('guard') }}',
                    page: 1
                };

                // Search with debounce
                let searchTimer;
                $('#searchInput').on('keyup', function(e) {
                    clearTimeout(searchTimer);
                    searchTimer = setTimeout(() => {
                        currentFilters.search = $(this).val();
                        currentFilters.page = 1;
                        loadRoles();
                        $('#clearSearch').toggle($(this).val() !== '');
                    }, 500);
                });

                // Clear search
                $('#clearSearch').on('click', function() {
                    $('#searchInput').val('');
                    currentFilters.search = '';
                    currentFilters.page = 1;
                    loadRoles();
                    $(this).hide();
                });

                // Guard filter
                $('#guardFilter .dropdown-item').on('click', function(e) {
                    e.preventDefault();
                    let guard = $(this).data('guard');

                    $('#guardFilter .dropdown-item').removeClass('active');
                    $(this).addClass('active');

                    let buttonText = guard ? guard.toUpperCase() : 'All Guards';
                    $(this).closest('.btn-group').find('.dropdown-toggle').html(buttonText +
                        ' <i class="ti ti-chevron-down"></i>');

                    currentFilters.guard = guard;
                    currentFilters.page = 1;
                    loadRoles();
                });

                // Pagination click handler
                $(document).on('click', '.pagination a', function(e) {
                    e.preventDefault();
                    let page = $(this).attr('href').split('page=')[1];
                    currentFilters.page = page;
                    loadRoles();
                });

                // Load roles via AJAX
                function loadRoles() {
                    $.ajax({
                        url: '{{ route('admin.roles.index') }}',
                        type: 'GET',
                        data: currentFilters,
                        beforeSend: function() {
                            $('#rolesTableContainer').html(
                                '<div class="text-center py-5"><div class="spinner-border text-primary" role="status"></div></div>'
                            );
                            $('#paginationContainer').html('');
                        },
                        success: function(response) {
                            $('#rolesTableContainer').html(response.table);
                            $('#paginationContainer').html(response.pagination);

                            // Update URL without reload
                            let url = new URL(window.location);
                            url.searchParams.set('search', currentFilters.search || '');
                            url.searchParams.set('guard', currentFilters.guard || '');
                            url.searchParams.set('page', currentFilters.page);
                            window.history.pushState({}, '', url);

                            // Reinitialize tooltips
                            $('[data-bs-toggle="tooltip"]').tooltip();

                            // Reinitialize select all
                            $('#selectAll').off('change').on('change', function() {
                                $('.role-checkbox').prop('checked', $(this).prop('checked'));
                            });

                            $('.role-checkbox').off('change').on('change', function() {
                                let allChecked = $('.role-checkbox:checked').length === $(
                                    '.role-checkbox').length;
                                $('#selectAll').prop('checked', allChecked);
                            });
                        },
                        error: function() {
                            $('#rolesTableContainer').html(
                                '<div class="alert alert-danger">Error loading roles</div>');
                        }
                    });
                }

                // Select All functionality
                $(document).on('change', '#selectAll', function() {
                    $('.role-checkbox').prop('checked', $(this).prop('checked'));
                });

                $(document).on('change', '.role-checkbox', function() {
                    let allChecked = $('.role-checkbox:checked').length === $('.role-checkbox').length;
                    $('#selectAll').prop('checked', allChecked);
                });

                // Show clear button if search exists
                if ($('#searchInput').val()) {
                    $('#clearSearch').show();
                }
            });

            // Confirm Delete
            function confirmDelete(roleId, roleName) {
                if (roleName === 'Super Admin') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Cannot Delete!',
                        text: 'Super Admin role cannot be deleted.',
                        confirmButtonColor: '#d33'
                    });
                    return;
                }

                Swal.fire({
                    title: 'Delete Role?',
                    text: "Are you sure you want to delete this role? This action cannot be undone.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        let form = $('#deleteForm');
                        form.attr('action', '{{ url('admin/roles') }}/' + roleId);

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
                                    text: xhr.responseJSON?.message || 'Failed to delete role.',
                                    confirmButtonColor: '#d33'
                                });
                            }
                        });
                    }
                });
            }

            // Bulk Action
            function bulkAction(action) {
                let selectedRoles = [];
                $('.role-checkbox:checked').each(function() {
                    selectedRoles.push($(this).val());
                });

                if (selectedRoles.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'No Selection',
                        text: 'Please select at least one role.',
                        confirmButtonColor: '#6c757d'
                    });
                    return;
                }

                Swal.fire({
                    title: 'Delete Roles?',
                    text: `Are you sure you want to delete ${selectedRoles.length} selected role(s)? This action cannot be undone.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete them!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#bulkAction').val(action);
                        $('#bulkRoleIds').val(JSON.stringify(selectedRoles));

                        $.ajax({
                            url: $('#bulkActionForm').attr('action'),
                            type: 'POST',
                            data: $('#bulkActionForm').serialize(),
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
                                    text: xhr.responseJSON?.message || 'Failed to delete roles.',
                                    confirmButtonColor: '#d33'
                                });
                            }
                        });
                    }
                });
            }
        </script>
    @endpush
