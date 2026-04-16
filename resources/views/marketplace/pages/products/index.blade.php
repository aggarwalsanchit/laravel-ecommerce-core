{{-- resources/views/marketplace/pages/products/index.blade.php --}}
@extends('management.layouts.app')

@section('title', 'My Products')

@section('content')
    <div class="page-content">
        <div class="page-container">
            <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                <div class="flex-grow-1">
                    <h4 class="fs-18 text-uppercase fw-bold mb-0">My Products</h4>
                    <p class="text-muted mb-0">Manage your product listings</p>
                </div>
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('vendor.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Products</li>
                    </ol>
                </div>
            </div>

            {{-- Statistics Cards --}}
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">Total Products</h6>
                                    <h2 class="mb-0">{{ $statistics['total'] ?? 0 }}</h2>
                                </div>
                                <i class="ti ti-package" style="font-size: 40px; opacity: 0.5;"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">Active Products</h6>
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
                                    <h6 class="mb-0">Pending Approval</h6>
                                    <h2 class="mb-0">{{ $statistics['pending_approval'] ?? 0 }}</h2>
                                </div>
                                <i class="ti ti-clock" style="font-size: 40px; opacity: 0.5;"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-danger text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">Low Stock</h6>
                                    <h2 class="mb-0">{{ $statistics['low_stock'] ?? 0 }}</h2>
                                </div>
                                <i class="ti ti-alert-triangle" style="font-size: 40px; opacity: 0.5;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Second Row - Additional Stats --}}
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-secondary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">Approved</h6>
                                    <h2 class="mb-0">{{ $statistics['approved'] ?? 0 }}</h2>
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
                                    <h2 class="mb-0">{{ $statistics['rejected'] ?? 0 }}</h2>
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
                                    <h6 class="mb-0">Out of Stock</h6>
                                    <h2 class="mb-0">{{ $statistics['out_of_stock'] ?? 0 }}</h2>
                                </div>
                                <i class="ti ti-package-off" style="font-size: 40px; opacity: 0.5;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <h3 class="card-title mb-0">Product Management</h3>
                            <div class="d-flex gap-2">
                                <a href="{{ route('vendor.products.analytics') }}" class="btn btn-info">
                                    <i class="ti ti-chart-bar me-1"></i> Analytics
                                </a>
                                <a href="{{ route('vendor.products.create') }}" class="btn btn-primary">
                                    <i class="ti ti-plus me-1"></i> Add New Product
                                </a>

                            </div>
                        </div>
                        <div class="card-body">

                            {{-- Search and Filter --}}
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="searchInput"
                                            placeholder="Search by name or SKU..." value="{{ request('search') }}">
                                        <button class="btn btn-primary" type="button" id="searchBtn">
                                            <i class="ti ti-search"></i>
                                        </button>
                                        <button class="btn btn-secondary" type="button" id="clearSearch"
                                            style="display: none;">
                                            <i class="ti ti-x"></i> Clear
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-8">
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
                                                        data-approval="pending">Pending</a></li>
                                                <li><a class="dropdown-item" href="#"
                                                        data-approval="approved">Approved</a></li>
                                                <li><a class="dropdown-item" href="#"
                                                        data-approval="rejected">Rejected</a></li>
                                            </ul>
                                        </div>

                                        <div class="btn-group">
                                            <button type="button" class="btn btn-outline-secondary dropdown-toggle"
                                                data-bs-toggle="dropdown">
                                                <i class="ti ti-package me-1"></i> Stock Status
                                            </button>
                                            <ul class="dropdown-menu" id="stockFilter">
                                                <li><a class="dropdown-item" href="#" data-stock="">All</a></li>
                                                <li><a class="dropdown-item" href="#" data-stock="instock">In
                                                        Stock</a></li>
                                                <li><a class="dropdown-item" href="#" data-stock="low">Low
                                                        Stock</a></li>
                                                <li><a class="dropdown-item" href="#" data-stock="out">Out of
                                                        Stock</a></li>
                                            </ul>
                                        </div>

                                        <div class="btn-group">
                                            <button type="button" class="btn btn-outline-secondary dropdown-toggle"
                                                data-bs-toggle="dropdown">
                                                <i class="ti ti-arrows-sort me-1"></i> Sort By
                                            </button>
                                            <ul class="dropdown-menu" id="sortFilter">
                                                <li><a class="dropdown-item" href="#" data-sort="created_at"
                                                        data-order="desc">Newest First</a></li>
                                                <li><a class="dropdown-item" href="#" data-sort="created_at"
                                                        data-order="asc">Oldest First</a></li>
                                                <li><a class="dropdown-item" href="#" data-sort="name"
                                                        data-order="asc">Name (A-Z)</a></li>
                                                <li><a class="dropdown-item" href="#" data-sort="price"
                                                        data-order="asc">Price (Low to High)</a></li>
                                                <li><a class="dropdown-item" href="#" data-sort="price"
                                                        data-order="desc">Price (High to Low)</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Products Table Container --}}
                            <div id="productsTableContainer">
                                @include('marketplace.pages.products.partials.products-table', [
                                    'products' => $products,
                                ])
                            </div>

                            {{-- Pagination Container --}}
                            <div id="paginationContainer" class="mt-3">
                                {{ $products->appends(request()->query())->links('pagination::bootstrap-5') }}
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
                stock: '{{ request('stock') }}',
                sort_by: '{{ request('sort_by', 'created_at') }}',
                sort_order: '{{ request('sort_order', 'desc') }}',
                page: 1
            };

            // Search button click
            $('#searchBtn').on('click', function() {
                currentFilters.search = $('#searchInput').val();
                currentFilters.page = 1;
                loadProducts();
                $('#clearSearch').toggle(currentFilters.search !== '');
            });

            // Search on enter key
            $('#searchInput').on('keypress', function(e) {
                if (e.which === 13) {
                    currentFilters.search = $(this).val();
                    currentFilters.page = 1;
                    loadProducts();
                    $('#clearSearch').toggle(currentFilters.search !== '');
                }
            });

            // Clear search
            $('#clearSearch').on('click', function() {
                $('#searchInput').val('');
                currentFilters.search = '';
                currentFilters.page = 1;
                loadProducts();
                $(this).hide();
            });

            // Status filter
            $('#statusFilter .dropdown-item').on('click', function(e) {
                e.preventDefault();
                let status = $(this).data('status');
                currentFilters.status = status;
                currentFilters.page = 1;
                loadProducts();
            });

            // Approval filter
            $('#approvalFilter .dropdown-item').on('click', function(e) {
                e.preventDefault();
                let approvalStatus = $(this).data('approval');
                currentFilters.approval_status = approvalStatus;
                currentFilters.page = 1;
                loadProducts();
            });

            // Stock filter
            $('#stockFilter .dropdown-item').on('click', function(e) {
                e.preventDefault();
                let stock = $(this).data('stock');
                currentFilters.stock = stock;
                currentFilters.page = 1;
                loadProducts();
            });

            // Sort filter
            $('#sortFilter .dropdown-item').on('click', function(e) {
                e.preventDefault();
                let sortBy = $(this).data('sort');
                let sortOrder = $(this).data('order');
                currentFilters.sort_by = sortBy;
                currentFilters.sort_order = sortOrder;
                currentFilters.page = 1;
                loadProducts();
            });

            // Pagination click handler
            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault();
                let page = $(this).attr('href').split('page=')[1];
                if (page) {
                    currentFilters.page = page;
                    loadProducts();
                }
            });

            // Load products via AJAX
            function loadProducts() {
                $.ajax({
                    url: '{{ route('vendor.products.index') }}',
                    type: 'GET',
                    data: currentFilters,
                    beforeSend: function() {
                        $('#productsTableContainer').html(
                            '<div class="text-center py-5"><div class="spinner-border text-primary" role="status"></div></div>'
                        );
                        $('#paginationContainer').html('');
                    },
                    success: function(response) {
                        $('#productsTableContainer').html(response.table);
                        $('#paginationContainer').html(response.pagination);

                        if (response.statistics) {
                            updateStatistics(response.statistics);
                        }

                        let url = new URL(window.location);
                        url.searchParams.set('search', currentFilters.search || '');
                        url.searchParams.set('status', currentFilters.status || '');
                        url.searchParams.set('approval_status', currentFilters.approval_status || '');
                        url.searchParams.set('stock', currentFilters.stock || '');
                        url.searchParams.set('sort_by', currentFilters.sort_by || 'created_at');
                        url.searchParams.set('sort_order', currentFilters.sort_order || 'desc');
                        url.searchParams.set('page', currentFilters.page);
                        window.history.pushState({}, '', url);

                        $('[data-bs-toggle="tooltip"]').tooltip();

                        $('#selectAll').off('change').on('change', function() {
                            $('.product-checkbox').prop('checked', $(this).prop('checked'));
                        });

                        $('.product-checkbox').off('change').on('change', function() {
                            let allChecked = $('.product-checkbox:checked').length === $(
                                '.product-checkbox').length;
                            $('#selectAll').prop('checked', allChecked);
                        });
                    },
                    error: function() {
                        $('#productsTableContainer').html(
                            '<div class="alert alert-danger">Error loading products</div>');
                    }
                });
            }

            function updateStatistics(statistics) {
                $('.bg-primary .h2').text(statistics.total || 0);
                $('.bg-success .h2').text(statistics.active || 0);
                $('.bg-warning .h2').text(statistics.pending_approval || 0);
                $('.bg-danger .h2').text(statistics.low_stock || 0);
                $('.bg-secondary .h2').text(statistics.approved || 0);
                $('.bg-danger:eq(1) .h2').text(statistics.rejected || 0);
                $('.bg-info .h2').text(statistics.out_of_stock || 0);
            }

            if ($('#searchInput').val()) {
                $('#clearSearch').show();
            }
        });

        function toggleStatus(productId, element) {
            let isChecked = $(element).prop('checked');

            $.ajax({
                url: '{{ url('vendor/products') }}/' + productId + '/toggle-status',
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

        function confirmDelete(productId, productName) {
            Swal.fire({
                title: 'Delete Product?',
                text: `Are you sure you want to delete "${productName}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    let form = $('#deleteForm');
                    form.attr('action', '{{ url('vendor/products') }}/' + productId);

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
                                text: xhr.responseJSON?.message || 'Failed to delete product.',
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
