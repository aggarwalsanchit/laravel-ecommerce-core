{{-- resources/views/marketplace/permissions/index.blade.php --}}
@extends('management.layouts.app')

@section('title', 'Permissions')

@section('content')
    <div class="page-content">
        <div class="page-container">
            <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                <div class="flex-grow-1">
                    <h4 class="fs-18 text-uppercase fw-bold mb-0">Permissions</h4>
                </div>
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('vendor.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Permissions</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="card-title">Permission Management</h3>
                            @php $vendor = Auth::guard('vendor')->user(); @endphp
                            @if ($vendor->can('create_permissions'))
                                <a href="{{ route('vendor.permissions.create') }}" class="btn btn-primary">
                                    <i class="ti ti-plus me-1"></i> Add New Permission
                                </a>
                            @endif
                        </div>
                        <div class="card-body">

                            {{-- Search and Filter --}}
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <form id="searchForm" class="d-flex gap-2">
                                        <input type="text" name="search" class="form-control" id="searchInput"
                                            placeholder="Search by permission name..." value="{{ request('search') }}">
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
                                            Filter by Module
                                        </button>
                                        <ul class="dropdown-menu" id="moduleFilter">
                                            <li><a class="dropdown-item" href="#" data-module="">All Modules</a></li>
                                            @foreach ($modules as $module)
                                                <li><a class="dropdown-item" href="#"
                                                        data-module="{{ $module }}">{{ ucfirst($module) }}</a></li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            {{-- Bulk Actions --}}
                            @if ($vendor->can('delete_permissions'))
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

                            <div class="table-responsive" id="permissionsTableContainer">
                                @include('marketplace.pages.permissions.partials.permissions-table', [
                                    'permissions' => $permissions,
                                ])
                            </div>

                            <div class="card-footer" id="paginationContainer">
                                <div class="d-flex justify-content-end">
                                    {{ $permissions->appends(request()->query())->links('pagination::bootstrap-5') }}
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
        <form id="bulkActionForm" method="POST" action="{{ route('vendor.permissions.bulk-action') }}"
            style="display: none;">
            @csrf
            <input type="hidden" name="action" id="bulkAction">
            <input type="hidden" name="permission_ids" id="bulkPermissionIds">
        </form>
    @endsection

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            $(document).ready(function() {
                let currentFilters = {
                    search: '{{ request('search') }}',
                    module: '{{ request('module') }}',
                    page: 1
                };

                // Search with debounce
                let searchTimer;
                $('#searchInput').on('keyup', function(e) {
                    clearTimeout(searchTimer);
                    searchTimer = setTimeout(() => {
                        currentFilters.search = $(this).val();
                        currentFilters.page = 1;
                        loadPermissions();
                        $('#clearSearch').toggle($(this).val() !== '');
                    }, 500);
                });

                // Clear search
                $('#clearSearch').on('click', function() {
                    $('#searchInput').val('');
                    currentFilters.search = '';
                    currentFilters.page = 1;
                    loadPermissions();
                    $(this).hide();
                });

                // Module filter
                $('#moduleFilter .dropdown-item').on('click', function(e) {
                    e.preventDefault();
                    let module = $(this).data('module');

                    $('#moduleFilter .dropdown-item').removeClass('active');
                    $(this).addClass('active');

                    let buttonText = module ? module.toUpperCase() : 'All Modules';
                    $(this).closest('.btn-group').find('.dropdown-toggle').html(buttonText +
                        ' <i class="ti ti-chevron-down"></i>');

                    currentFilters.module = module;
                    currentFilters.page = 1;
                    loadPermissions();
                });

                // Pagination click handler
                $(document).on('click', '.pagination a', function(e) {
                    e.preventDefault();
                    let page = $(this).attr('href').split('page=')[1];
                    currentFilters.page = page;
                    loadPermissions();
                });

                // Load permissions via AJAX
                function loadPermissions() {
                    $.ajax({
                        url: '{{ route('vendor.permissions.index') }}',
                        type: 'GET',
                        data: currentFilters,
                        beforeSend: function() {
                            $('#permissionsTableContainer').html(
                                '<div class="text-center py-5"><div class="spinner-border text-primary" role="status"></div></div>'
                            );
                            $('#paginationContainer').html('');
                        },
                        success: function(response) {
                            $('#permissionsTableContainer').html(response.table);
                            $('#paginationContainer').html(response.pagination);

                            // Update URL without reload
                            let url = new URL(window.location);
                            url.searchParams.set('search', currentFilters.search || '');
                            url.searchParams.set('module', currentFilters.module || '');
                            url.searchParams.set('page', currentFilters.page);
                            window.history.pushState({}, '', url);

                            // Reinitialize tooltips
                            $('[data-bs-toggle="tooltip"]').tooltip();

                            // Reinitialize select all
                            $('#selectAll').off('change').on('change', function() {
                                $('.permission-checkbox').prop('checked', $(this).prop('checked'));
                            });

                            $('.permission-checkbox').off('change').on('change', function() {
                                let allChecked = $('.permission-checkbox:checked').length === $(
                                    '.permission-checkbox').length;
                                $('#selectAll').prop('checked', allChecked);
                            });
                        },
                        error: function() {
                            $('#permissionsTableContainer').html(
                                '<div class="alert alert-danger">Error loading permissions</div>');
                        }
                    });
                }

                // Select All functionality
                $(document).on('change', '#selectAll', function() {
                    $('.permission-checkbox').prop('checked', $(this).prop('checked'));
                });

                $(document).on('change', '.permission-checkbox', function() {
                    let allChecked = $('.permission-checkbox:checked').length === $('.permission-checkbox')
                        .length;
                    $('#selectAll').prop('checked', allChecked);
                });

                // Show clear button if search exists
                if ($('#searchInput').val()) {
                    $('#clearSearch').show();
                }
            });

            // Confirm Delete
            function confirmDelete(permissionId) {
                Swal.fire({
                    title: 'Delete Permission?',
                    text: "Are you sure you want to delete this permission? This action cannot be undone.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        let form = $('#deleteForm');
                        form.attr('action', '{{ url('/marketplace/permissions') }}/' + permissionId);

                        $.ajax({
                            url: form.attr('action'),
                            type: 'DELETE',
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
                                    text: xhr.responseJSON?.message ||
                                        'Failed to delete permission.',
                                    confirmButtonColor: '#d33'
                                });
                            }
                        });
                    }
                });
            }

            // Bulk Action
            function bulkAction(action) {
                let selectedPermissions = [];
                $('.permission-checkbox:checked').each(function() {
                    selectedPermissions.push($(this).val());
                });

                if (selectedPermissions.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'No Selection',
                        text: 'Please select at least one permission.',
                        confirmButtonColor: '#6c757d'
                    });
                    return;
                }

                Swal.fire({
                    title: 'Delete Permissions?',
                    text: `Are you sure you want to delete ${selectedPermissions.length} selected permission(s)? This action cannot be undone.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete them!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#bulkAction').val(action);
                        $('#bulkPermissionIds').val(JSON.stringify(selectedPermissions));

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
                                    text: xhr.responseJSON?.message ||
                                        'Failed to delete permissions.',
                                    confirmButtonColor: '#d33'
                                });
                            }
                        });
                    }
                });
            }
        </script>
    @endpush
