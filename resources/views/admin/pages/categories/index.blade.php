{{-- resources/views/admin/pages/categories/index.blade.php --}}
@extends('management.layouts.app')

@section('title', 'Categories')

@section('content')
    <div class="page-content">
        <div class="page-container">
            <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                <div class="flex-grow-1">
                    <h4 class="fs-18 text-uppercase fw-bold mb-0">Category Management</h4>
                </div>
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Categories</li>
                    </ol>
                </div>
            </div>

            {{-- Statistics Cards (Only from categories table, not analytics) --}}
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">Total Categories</h6>
                                    <h2 class="mb-0">{{ $statistics['total'] ?? 0 }}</h2>
                                </div>
                                <i class="ti ti-folder" style="font-size: 40px; opacity: 0.5;"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">Active Categories</h6>
                                    <h2 class="mb-0">{{ $statistics['active'] ?? 0 }}</h2>
                                </div>
                                <i class="ti ti-circle-check" style="font-size: 40px; opacity: 0.5;"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-dark">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">Featured Categories</h6>
                                    <h2 class="mb-0">{{ $statistics['featured'] ?? 0 }}</h2>
                                </div>
                                <i class="ti ti-star" style="font-size: 40px; opacity: 0.5;"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">Pending Approval</h6>
                                    <h2 class="mb-0">{{ $statistics['pending'] ?? 0 }}</h2>
                                </div>
                                <i class="ti ti-clock" style="font-size: 40px; opacity: 0.5;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Second Row - Additional Stats --}}
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-danger text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">Rejected</h6>
                                    <h2 class="mb-0">{{ $statistics['rejected'] ?? 0 }}</h2>
                                </div>
                                <i class="ti ti-x-circle" style="font-size: 40px; opacity: 0.5;"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-secondary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">Total Views</h6>
                                    <h2 class="mb-0">{{ number_format($statistics['total_views'] ?? 0) }}</h2>
                                </div>
                                <i class="ti ti-eye" style="font-size: 40px; opacity: 0.5;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <h3 class="card-title mb-0">Category Management</h3>
                            <div class="d-flex gap-2">
                                @php $admin = auth()->guard('admin')->user(); @endphp
                                <a href="{{ route('admin.categories.analytics') }}" class="btn btn-info">
                                    <i class="ti ti-chart-bar me-1"></i> Analytics
                                </a>

                                @if ($admin->can('create_categories'))
                                    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                                        <i class="ti ti-plus me-1"></i> Add New Category
                                    </a>
                                @endif
                                @if ($admin->can('request_categories'))
                                    <a href="{{ route('admin.categories.requests') }}" class="btn btn-success">
                                        <i class="ti ti-palette me-1"></i> Requested Categories
                                    </a>
                                @endif
                            </div>
                        </div>
                        <div class="card-body">

                            {{-- Search and Filter --}}
                            <div class="row mb-3">
                                <div class="col-md-5">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="searchInput"
                                            placeholder="Search by name or slug..." value="{{ request('search') }}">
                                        <button class="btn btn-primary" type="button" id="searchBtn">
                                            <i class="ti ti-search"></i>
                                        </button>
                                        <button class="btn btn-secondary" type="button" id="clearSearch"
                                            style="display: none;">
                                            <i class="ti ti-x"></i> Clear
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <div class="d-flex gap-2 justify-content-end flex-wrap">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-outline-secondary dropdown-toggle"
                                                data-bs-toggle="dropdown">
                                                <i class="ti ti-filter me-1"></i> Filter by Status
                                            </button>
                                            <ul class="dropdown-menu" id="statusFilter">
                                                <li><a class="dropdown-item" href="#" data-status="">All</a></li>
                                                <li><a class="dropdown-item" href="#"
                                                        data-status="active">Active</a></li>
                                                <li><a class="dropdown-item" href="#"
                                                        data-status="inactive">Inactive</a></li>
                                            </ul>
                                        </div>

                                        <div class="btn-group">
                                            <button type="button" class="btn btn-outline-secondary dropdown-toggle"
                                                data-bs-toggle="dropdown">
                                                <i class="ti ti-check-circle me-1"></i> Approval Status
                                            </button>
                                            <ul class="dropdown-menu" id="approvalFilter">
                                                <li><a class="dropdown-item" href="#" data-approval="">All</a></li>
                                                <li><a class="dropdown-item" href="#"
                                                        data-approval="approved">Approved</a></li>
                                                <li><a class="dropdown-item" href="#"
                                                        data-approval="pending">Pending</a></li>
                                                <li><a class="dropdown-item" href="#"
                                                        data-approval="rejected">Rejected</a></li>
                                            </ul>
                                        </div>

                                        <div class="btn-group">
                                            <button type="button" class="btn btn-outline-secondary dropdown-toggle"
                                                data-bs-toggle="dropdown">
                                                <i class="ti ti-layout-sidebar me-1"></i> Filter by Type
                                            </button>
                                            <ul class="dropdown-menu" id="typeFilter">
                                                <li><a class="dropdown-item" href="#" data-type="">All</a></li>
                                                <li><a class="dropdown-item" href="#" data-type="main">Main
                                                        Categories</a></li>
                                                <li><a class="dropdown-item" href="#" data-type="sub">Sub
                                                        Categories</a></li>
                                            </ul>
                                        </div>

                                        <div class="btn-group">
                                            <button type="button" class="btn btn-outline-secondary dropdown-toggle"
                                                data-bs-toggle="dropdown">
                                                <i class="ti ti-arrows-sort me-1"></i> Sort By
                                            </button>
                                            <ul class="dropdown-menu" id="sortFilter">
                                                <li><a class="dropdown-item" href="#" data-sort="order">Default
                                                        Order</a></li>
                                                <li><a class="dropdown-item" href="#" data-sort="name">Name
                                                        (A-Z)</a></li>
                                                <li><a class="dropdown-item" href="#" data-sort="created_at">Newest
                                                        First</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Bulk Actions --}}
                            @if ($admin->can('edit_categories') || $admin->can('delete_categories'))
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <div class="btn-group flex-wrap gap-2">
                                            @if ($admin->can('edit_categories'))
                                                <button type="button" class="btn btn-outline-success btn-sm"
                                                    onclick="bulkAction('activate')">
                                                    <i class="ti ti-check"></i> Activate Selected
                                                </button>
                                                <button type="button" class="btn btn-outline-warning btn-sm"
                                                    onclick="bulkAction('deactivate')">
                                                    <i class="ti ti-x"></i> Deactivate Selected
                                                </button>
                                                <button type="button" class="btn btn-outline-primary btn-sm"
                                                    onclick="bulkAction('feature')">
                                                    <i class="ti ti-star"></i> Mark as Featured
                                                </button>
                                                <button type="button" class="btn btn-outline-secondary btn-sm"
                                                    onclick="bulkAction('unfeature')">
                                                    <i class="ti ti-star-off"></i> Remove Featured
                                                </button>
                                                <button type="button" class="btn btn-outline-info btn-sm"
                                                    onclick="bulkAction('popular')">
                                                    <i class="ti ti-fire"></i> Mark as Popular
                                                </button>
                                                <button type="button" class="btn btn-outline-secondary btn-sm"
                                                    onclick="bulkAction('unpopular')">
                                                    <i class="ti ti-fire-off"></i> Remove Popular
                                                </button>
                                                <button type="button" class="btn btn-outline-success btn-sm"
                                                    onclick="bulkAction('approve')">
                                                    <i class="ti ti-check-circle"></i> Approve Selected
                                                </button>
                                                <button type="button" class="btn btn-outline-danger btn-sm"
                                                    onclick="bulkAction('reject')">
                                                    <i class="ti ti-x-circle"></i> Reject Selected
                                                </button>
                                            @endif
                                            @if ($admin->can('delete_categories'))
                                                <button type="button" class="btn btn-outline-danger btn-sm"
                                                    onclick="bulkAction('delete')">
                                                    <i class="ti ti-trash"></i> Delete Selected
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- Categories Table Container --}}
                            <div id="categoriesTableContainer">
                                @include('admin.pages.categories.partials.categories-table', [
                                    'categories' => $categories,
                                ])
                            </div>

                            {{-- Pagination Container --}}
                            <div id="paginationContainer" class="mt-3">
                                {{ $categories->appends(request()->query())->links('pagination::bootstrap-5') }}
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
    <form id="bulkActionForm" method="POST" action="{{ route('admin.categories.bulk-action') }}"
        style="display: none;">
        @csrf
        <input type="hidden" name="action" id="bulkAction">
        <input type="hidden" name="category_ids" id="bulkCategoryIds">
    </form>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            let currentFilters = {
                search: '{{ request('search') }}',
                status: '{{ request('status') }}',
                approval_status: '{{ request('approval_status') }}',
                type: '{{ request('type') }}',
                sort_by: '{{ request('sort_by', 'order') }}',
                page: 1
            };

            // Search button click
            $('#searchBtn').on('click', function() {
                currentFilters.search = $('#searchInput').val();
                currentFilters.page = 1;
                loadCategories();
                $('#clearSearch').toggle(currentFilters.search !== '');
            });

            // Search on enter key
            $('#searchInput').on('keypress', function(e) {
                if (e.which === 13) {
                    currentFilters.search = $(this).val();
                    currentFilters.page = 1;
                    loadCategories();
                    $('#clearSearch').toggle(currentFilters.search !== '');
                }
            });

            // Clear search
            $('#clearSearch').on('click', function() {
                $('#searchInput').val('');
                currentFilters.search = '';
                currentFilters.page = 1;
                loadCategories();
                $(this).hide();
            });

            // Status filter
            $('#statusFilter .dropdown-item').on('click', function(e) {
                e.preventDefault();
                let status = $(this).data('status');

                $('#statusFilter .dropdown-item').removeClass('active');
                $(this).addClass('active');

                currentFilters.status = status;
                currentFilters.page = 1;
                loadCategories();
            });

            // Approval filter
            $('#approvalFilter .dropdown-item').on('click', function(e) {
                e.preventDefault();
                let approvalStatus = $(this).data('approval');

                $('#approvalFilter .dropdown-item').removeClass('active');
                $(this).addClass('active');

                currentFilters.approval_status = approvalStatus;
                currentFilters.page = 1;
                loadCategories();
            });

            // Type filter
            $('#typeFilter .dropdown-item').on('click', function(e) {
                e.preventDefault();
                let type = $(this).data('type');

                $('#typeFilter .dropdown-item').removeClass('active');
                $(this).addClass('active');

                currentFilters.type = type;
                currentFilters.page = 1;
                loadCategories();
            });

            // Sort filter
            $('#sortFilter .dropdown-item').on('click', function(e) {
                e.preventDefault();
                let sortBy = $(this).data('sort');

                $('#sortFilter .dropdown-item').removeClass('active');
                $(this).addClass('active');

                currentFilters.sort_by = sortBy;
                currentFilters.page = 1;
                loadCategories();
            });

            // Pagination click handler
            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault();
                let page = $(this).attr('href').split('page=')[1];
                if (page) {
                    currentFilters.page = page;
                    loadCategories();
                }
            });

            // Load categories via AJAX
            function loadCategories() {
                $.ajax({
                    url: '{{ route('admin.categories.index') }}',
                    type: 'GET',
                    data: currentFilters,
                    beforeSend: function() {
                        $('#categoriesTableContainer').html(
                            '<div class="text-center py-5"><div class="spinner-border text-primary" role="status"></div></div>'
                        );
                        $('#paginationContainer').html('');
                    },
                    success: function(response) {
                        $('#categoriesTableContainer').html(response.table);
                        $('#paginationContainer').html(response.pagination);

                        if (response.statistics) {
                            updateStatistics(response.statistics);
                        }

                        let url = new URL(window.location);
                        url.searchParams.set('search', currentFilters.search || '');
                        url.searchParams.set('status', currentFilters.status || '');
                        url.searchParams.set('approval_status', currentFilters.approval_status || '');
                        url.searchParams.set('type', currentFilters.type || '');
                        url.searchParams.set('sort_by', currentFilters.sort_by || 'order');
                        url.searchParams.set('page', currentFilters.page);
                        window.history.pushState({}, '', url);

                        $('[data-bs-toggle="tooltip"]').tooltip();

                        $('#selectAll').off('change').on('change', function() {
                            $('.category-checkbox').prop('checked', $(this).prop('checked'));
                        });

                        $('.category-checkbox').off('change').on('change', function() {
                            let allChecked = $('.category-checkbox:checked').length === $(
                                '.category-checkbox').length;
                            $('#selectAll').prop('checked', allChecked);
                        });

                        $('.toggle-status').off('change').on('change', function() {
                            let categoryId = $(this).data('id');
                            toggleStatus(categoryId, this);
                        });
                    },
                    error: function() {
                        $('#categoriesTableContainer').html(
                            '<div class="alert alert-danger">Error loading categories</div>');
                    }
                });
            }

            function updateStatistics(statistics) {
                $('.bg-primary .h2').text(statistics.total || 0);
                $('.bg-success .h2').text(statistics.active || 0);
                $('.bg-warning .h2').text(statistics.featured || 0);
                $('.bg-info .h2').text(statistics.pending || 0);
                $('.bg-danger .h2').text(statistics.rejected || 0);
                $('.bg-secondary .h2').text(statistics.total_views || 0);
            }

            if ($('#searchInput').val()) {
                $('#clearSearch').show();
            }
        });

        function toggleStatus(categoryId, element) {
            let isChecked = $(element).prop('checked');

            $.ajax({
                url: '{{ url('admin/categories') }}/' + categoryId + '/toggle-status',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Updated!',
                            text: response.message,
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                },
                error: function() {
                    $(element).prop('checked', !isChecked);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Failed to update status.',
                        confirmButtonColor: '#d33'
                    });
                }
            });
        }

        function confirmDelete(categoryId) {
            Swal.fire({
                title: 'Delete Category?',
                text: "Are you sure you want to delete this category?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    let form = $('#deleteForm');
                    form.attr('action', '{{ url('admin/categories') }}/' + categoryId);

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
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Cannot Delete!',
                                    text: response.message,
                                    confirmButtonColor: '#d33'
                                });
                            }
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: xhr.responseJSON?.message || 'Failed to delete category.',
                                confirmButtonColor: '#d33'
                            });
                        }
                    });
                }
            });
        }

        function approveCategory(categoryId, categoryName) {
            Swal.fire({
                title: 'Approve Category?',
                text: `Are you sure you want to approve "${categoryName}"?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, approve it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ url('admin/categories') }}/' + categoryId + '/approve',
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
                                    'Failed to approve category.',
                                confirmButtonColor: '#d33'
                            });
                        }
                    });
                }
            });
        }

        function showRejectModal(categoryId, categoryName) {
            Swal.fire({
                title: 'Reject Category',
                html: `
                    <p>Are you sure you want to reject "${categoryName}"?</p>
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
                        url: '{{ url('admin/categories') }}/' + categoryId + '/reject',
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
                                }).then(() => {
                                    location.reload();
                                });
                            }
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: xhr.responseJSON?.message || 'Failed to reject category.',
                                confirmButtonColor: '#d33'
                            });
                        }
                    });
                }
            });
        }

        function bulkAction(action) {
            let selectedCategories = [];
            $('.category-checkbox:checked').each(function() {
                selectedCategories.push($(this).val());
            });

            if (selectedCategories.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'No Selection',
                    text: 'Please select at least one category.',
                    confirmButtonColor: '#6c757d'
                });
                return;
            }

            let actionText = '';
            let confirmColor = '#28a745';

            switch (action) {
                case 'activate':
                    actionText = 'activate';
                    break;
                case 'deactivate':
                    actionText = 'deactivate';
                    break;
                case 'feature':
                    actionText = 'mark as featured';
                    break;
                case 'unfeature':
                    actionText = 'remove featured';
                    break;
                case 'popular':
                    actionText = 'mark as popular';
                    break;
                case 'unpopular':
                    actionText = 'remove popular';
                    break;
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
                title: `${actionText.toUpperCase()} Categories?`,
                text: `Are you sure you want to ${actionText} ${selectedCategories.length} selected category(s)?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: confirmColor,
                cancelButtonColor: '#6c757d',
                confirmButtonText: `Yes, ${actionText} them!`
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#bulkAction').val(action);
                    $('#bulkCategoryIds').val(JSON.stringify(selectedCategories));

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
        .btn-icon {
            width: 32px;
            height: 32px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50% !important;
        }

        .btn-sm.rounded-circle {
            border-radius: 50% !important;
            width: 32px;
            height: 32px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .hstack {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .hstack .btn {
            margin: 0;
            flex-shrink: 0;
        }
    </style>
@endpush
