{{-- resources/views/admin/products/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Products')

@section('content')
<div class="page-content">
    <div class="page-container">
        <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
            <div class="flex-grow-1">
                <h4 class="fs-18 text-uppercase fw-bold mb-0">Product Management</h4>
            </div>
            <div class="text-end">
                <ol class="breadcrumb m-0 py-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Products</li>
                </ol>
            </div>
        </div>

        {{-- Statistics Cards --}}
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6>Total Products</h6>
                                <h2 class="mb-0">{{ $statistics['total'] ?? 0 }}</h2>
                            </div>
                            <i class="ti ti-package fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6>Active Products</h6>
                                <h2 class="mb-0">{{ $statistics['active'] ?? 0 }}</h2>
                            </div>
                            <i class="ti ti-circle-check fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-dark">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6>Featured Products</h6>
                                <h2 class="mb-0">{{ $statistics['featured'] ?? 0 }}</h2>
                            </div>
                            <i class="ti ti-star fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6>On Sale</h6>
                                <h2 class="mb-0">{{ $statistics['on_sale'] ?? 0 }}</h2>
                            </div>
                            <i class="ti ti-discount fs-1 opacity-50"></i>
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
                            <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
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
                                    <button class="btn btn-secondary" type="button" id="clearSearch" style="display: none;">
                                        <i class="ti ti-x"></i> Clear
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="d-flex gap-2 justify-content-end flex-wrap">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                            <i class="ti ti-filter me-1"></i> Category
                                        </button>
                                        <ul class="dropdown-menu" id="categoryFilter">
                                            <li><a class="dropdown-item" href="#" data-category="">All Categories</a></li>
                                            @foreach($categories as $category)
                                                <li><a class="dropdown-item" href="#" data-category="{{ $category->id }}">{{ $category->name }}</a></li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                            <i class="ti ti-filter me-1"></i> Status
                                        </button>
                                        <ul class="dropdown-menu" id="statusFilter">
                                            <li><a class="dropdown-item" href="#" data-status="">All</a></li>
                                            <li><a class="dropdown-item" href="#" data-status="active">Active</a></li>
                                            <li><a class="dropdown-item" href="#" data-status="inactive">Inactive</a></li>
                                        </ul>
                                    </div>
                                    
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                            <i class="ti ti-star me-1"></i> Featured
                                        </button>
                                        <ul class="dropdown-menu" id="featuredFilter">
                                            <li><a class="dropdown-item" href="#" data-featured="">All</a></li>
                                            <li><a class="dropdown-item" href="#" data-featured="yes">Featured</a></li>
                                            <li><a class="dropdown-item" href="#" data-featured="no">Not Featured</a></li>
                                        </ul>
                                    </div>
                                    
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                            <i class="ti ti-arrows-sort me-1"></i> Sort By
                                        </button>
                                        <ul class="dropdown-menu" id="sortFilter">
                                            <li><a class="dropdown-item" href="#" data-sort="created_at">Latest First</a></li>
                                            <li><a class="dropdown-item" href="#" data-sort="name">Name (A-Z)</a></li>
                                            <li><a class="dropdown-item" href="#" data-sort="price">Price (Low to High)</a></li>
                                            <li><a class="dropdown-item" href="#" data-sort="price_desc">Price (High to Low)</a></li>
                                            <li><a class="dropdown-item" href="#" data-sort="view_count">Most Viewed</a></li>
                                            <li><a class="dropdown-item" href="#" data-sort="order_count">Best Selling</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Bulk Actions --}}
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="btn-group gap-2">
                                    <button type="button" class="btn btn-outline-success btn-sm" onclick="bulkAction('activate')">
                                        <i class="ti ti-check"></i> Activate Selected
                                    </button>
                                    <button type="button" class="btn btn-outline-warning btn-sm" onclick="bulkAction('deactivate')">
                                        <i class="ti ti-x"></i> Deactivate Selected
                                    </button>
                                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="bulkAction('feature')">
                                        <i class="ti ti-star"></i> Mark as Featured
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="bulkAction('unfeature')">
                                        <i class="ti ti-star-off"></i> Remove Featured
                                    </button>
                                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="bulkAction('delete')">
                                        <i class="ti ti-trash"></i> Delete Selected
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Products Table Container --}}
                        <div id="productsTableContainer">
                            @include('admin.pages.products.partials.products-table', ['products' => $products])
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

{{-- Bulk Action Form --}}
<form id="bulkActionForm" method="POST" action="{{ route('admin.products.bulk-action') }}" style="display: none;">
    @csrf
    <input type="hidden" name="action" id="bulkAction">
    <input type="hidden" name="product_ids" id="bulkProductIds">
</form>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    let currentFilters = {
        search: '{{ request('search') }}',
        category_id: '{{ request('category_id') }}',
        status: '{{ request('status') }}',
        featured: '{{ request('featured') }}',
        sort_by: '{{ request('sort_by', 'created_at') }}',
        sort_order: '{{ request('sort_order', 'desc') }}',
        page: 1
    };

    // Search
    $('#searchBtn').on('click', function() {
        currentFilters.search = $('#searchInput').val();
        currentFilters.page = 1;
        loadProducts();
        $('#clearSearch').toggle(currentFilters.search !== '');
    });

    $('#searchInput').on('keypress', function(e) {
        if (e.which === 13) {
            currentFilters.search = $(this).val();
            currentFilters.page = 1;
            loadProducts();
            $('#clearSearch').toggle(currentFilters.search !== '');
        }
    });

    $('#clearSearch').on('click', function() {
        $('#searchInput').val('');
        currentFilters.search = '';
        currentFilters.page = 1;
        loadProducts();
        $(this).hide();
    });

    // Category filter
    $('#categoryFilter .dropdown-item').on('click', function(e) {
        e.preventDefault();
        let categoryId = $(this).data('category');
        currentFilters.category_id = categoryId;
        currentFilters.page = 1;
        loadProducts();
    });

    // Status filter
    $('#statusFilter .dropdown-item').on('click', function(e) {
        e.preventDefault();
        let status = $(this).data('status');
        currentFilters.status = status;
        currentFilters.page = 1;
        loadProducts();
    });

    // Featured filter
    $('#featuredFilter .dropdown-item').on('click', function(e) {
        e.preventDefault();
        let featured = $(this).data('featured');
        currentFilters.featured = featured;
        currentFilters.page = 1;
        loadProducts();
    });

    // Sort filter
    $('#sortFilter .dropdown-item').on('click', function(e) {
        e.preventDefault();
        let sortBy = $(this).data('sort');
        let sortOrder = 'asc';
        
        if (sortBy === 'price') sortOrder = 'asc';
        else if (sortBy === 'price_desc') { sortBy = 'price'; sortOrder = 'desc'; }
        else if (sortBy === 'created_at') sortOrder = 'desc';
        else if (sortBy === 'view_count') sortOrder = 'desc';
        else if (sortBy === 'order_count') sortOrder = 'desc';
        
        currentFilters.sort_by = sortBy;
        currentFilters.sort_order = sortOrder;
        currentFilters.page = 1;
        loadProducts();
    });

    // Pagination
    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        let page = $(this).attr('href').split('page=')[1];
        if (page) {
            currentFilters.page = page;
            loadProducts();
        }
    });

    function loadProducts() {
    $.ajax({
        url: '{{ route("admin.products.index") }}',
        type: 'GET',
        data: currentFilters,
        beforeSend: function() {
            $('#productsTableContainer').html('<div class="text-center py-5"><div class="spinner-border text-primary" role="status"></div><p class="mt-2">Loading products...</p></div>');
            $('#paginationContainer').html('');
        },
        success: function(response) {
            $('#productsTableContainer').html(response.table);
            $('#paginationContainer').html(response.pagination);
            
            if (response.statistics) {
                updateStatistics(response.statistics);
            }
            
            // Update URL without reload
            let url = new URL(window.location);
            url.searchParams.set('search', currentFilters.search || '');
            url.searchParams.set('category_id', currentFilters.category_id || '');
            url.searchParams.set('status', currentFilters.status || '');
            url.searchParams.set('featured', currentFilters.featured || '');
            url.searchParams.set('sort_by', currentFilters.sort_by || 'created_at');
            url.searchParams.set('page', currentFilters.page);
            window.history.pushState({}, '', url);
            
            // Reinitialize tooltips
            $('[data-bs-toggle="tooltip"]').tooltip();
            
            // Reinitialize select all
            $('#selectAll').off('change').on('change', function() {
                $('.product-checkbox').prop('checked', $(this).prop('checked'));
            });
            
            $('.product-checkbox').off('change').on('change', function() {
                let allChecked = $('.product-checkbox:checked').length === $('.product-checkbox').length;
                $('#selectAll').prop('checked', allChecked);
            });
            
            // Reinitialize status toggle
            $('.toggle-status').off('change').on('change', function() {
                let productId = $(this).data('id');
                toggleStatus(productId, this);
            });
        },
        error: function(xhr) {
            console.error('Error loading products:', xhr);
            $('#productsTableContainer').html('<div class="alert alert-danger">Error loading products. Please try again.</div>');
        }
    });
}

    function updateStatistics(statistics) {
        $('.bg-primary .h2').text(statistics.total || 0);
        $('.bg-success .h2').text(statistics.active || 0);
        $('.bg-warning .h2').text(statistics.featured || 0);
        $('.bg-info .h2').text(statistics.on_sale || 0);
    }

    if ($('#searchInput').val()) {
        $('#clearSearch').show();
    }
});

function toggleStatus(productId, element) {
    let isChecked = $(element).prop('checked');
    
    $.ajax({
        url: '{{ url("admin/products") }}/' + productId + '/toggle-status',
        type: 'POST',
        data: { _token: '{{ csrf_token() }}' },
        success: function(response) {
            if (response.success) {
                Swal.fire({ icon: 'success', title: 'Updated!', text: response.message, timer: 1500, showConfirmButton: false });
            }
        },
        error: function() {
            $(element).prop('checked', !isChecked);
            Swal.fire({ icon: 'error', title: 'Error!', text: 'Failed to update status.' });
        }
    });
}

function confirmDelete(productId) {
    Swal.fire({
        title: 'Delete Product?',
        text: "Are you sure you want to delete this product? This action cannot be undone.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            let form = $('#deleteForm');
            form.attr('action', '{{ url("admin/products") }}/' + productId);
            form.submit();
        }
    });
}

function bulkAction(action) {
    let selectedProducts = [];
    $('.product-checkbox:checked').each(function() {
        selectedProducts.push($(this).val());
    });
    
    if (selectedProducts.length === 0) {
        Swal.fire({ icon: 'warning', title: 'No Selection', text: 'Please select at least one product.' });
        return;
    }
    
    let actionText = action === 'activate' ? 'activate' : (action === 'deactivate' ? 'deactivate' : (action === 'feature' ? 'mark as featured' : (action === 'unfeature' ? 'remove featured' : 'delete')));
    let confirmColor = action === 'delete' ? '#d33' : '#28a745';
    
    Swal.fire({
        title: `${actionText.toUpperCase()} Products?`,
        text: `Are you sure you want to ${actionText} ${selectedProducts.length} selected product(s)?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: confirmColor,
        cancelButtonColor: '#6c757d',
        confirmButtonText: `Yes, ${actionText} them!`
    }).then((result) => {
        if (result.isConfirmed) {
            $('#bulkAction').val(action);
            $('#bulkProductIds').val(JSON.stringify(selectedProducts));
            $('#bulkActionForm').submit();
        }
    });
}
</script>
@endpush