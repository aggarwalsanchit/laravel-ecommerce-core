{{-- resources/views/vendor/categories/index.blade.php --}}
@extends('management.layouts.app')

@section('title', 'Product Categories')

@section('content')
<div class="page-content">
    <div class="page-container">
        <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
            <div class="flex-grow-1">
                <h4 class="fs-18 text-uppercase fw-bold mb-0">Product Categories</h4>
                <p class="text-muted mb-0">Browse available categories for your products</p>
            </div>
            <div class="text-end">
                <ol class="breadcrumb m-0 py-0">
                    <li class="breadcrumb-item"><a href="{{ route('vendor.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Categories</li>
                </ol>
            </div>
        </div>

        {{-- Info Alert --}}
        <div class="alert alert-info mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <i class="ti ti-info-circle me-2"></i>
                    These are the available categories for your products. 
                    @if($pendingRequestsCount > 0)
                        <strong>You have {{ $pendingRequestsCount }} pending category request(s).</strong>
                    @endif
                </div>
                <div>
                    <a href="{{ route('vendor.categories.request.create') }}" class="btn btn-sm btn-warning">
                        <i class="ti ti-plus"></i> Request Category
                    </a>
                    <a href="{{ route('vendor.categories.requests.index') }}" class="btn btn-sm btn-secondary">
                        <i class="ti ti-list"></i> My Requests
                        @if($pendingRequestsCount > 0)
                            <span class="badge bg-light text-dark ms-1">{{ $pendingRequestsCount }}</span>
                        @endif
                    </a>
                </div>
            </div>
        </div>

        {{-- Filter Section --}}
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="input-group">
                            <input type="text" class="form-control" id="searchInput" placeholder="Search categories..." value="{{ request('search') }}">
                            <button class="btn btn-primary" id="searchBtn">
                                <i class="ti ti-search"></i>
                            </button>
                            <button class="btn btn-secondary" id="clearSearch" style="display: none;">
                                <i class="ti ti-x"></i> Clear
                            </button>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <select class="form-select" id="parentFilter">
                            <option value="">All Categories</option>
                            @foreach($parentCategories as $parent)
                                <option value="{{ $parent->id }}" {{ request('parent_id') == $parent->id ? 'selected' : '' }}>
                                    {{ $parent->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        {{-- Categories Grid --}}
        <div class="row">
            @forelse($categories as $category)
                <div class="col-md-4 col-lg-3 mb-4">
                    <div class="card h-100 category-card">
                        <div class="card-body text-center">
                            <div class="category-icon mb-3">
                                @if($category->icon)
                                    <i class="{{ $category->icon }} fs-1 text-primary"></i>
                                @else
                                    <i class="ti ti-folder fs-1 text-primary"></i>
                                @endif
                            </div>
                            <h5 class="card-title">{{ $category->name }}</h5>
                            <p class="card-text text-muted small">
                                {{ Str::limit($category->short_description ?? $category->description ?? 'No description', 80) }}
                            </p>
                            @if($category->children->count() > 0)
                                <span class="badge bg-info">{{ $category->children->count() }} subcategories</span>
                            @endif
                        </div>
                        <div class="card-footer bg-transparent text-center">
                            <a href="{{ route('vendor.categories.show', $category->id) }}" class="btn btn-sm btn-primary">
                                <i class="ti ti-eye"></i> View Details
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="ti ti-folder-off" style="font-size: 64px; opacity: 0.5;"></i>
                        <h4 class="mt-3">No Categories Found</h4>
                        <p class="text-muted">No categories are available at the moment.</p>
                    </div>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $categories->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#searchBtn').on('click', function() {
        let search = $('#searchInput').val();
        let parentId = $('#parentFilter').val();
        window.location.href = '{{ route("vendor.categories.index") }}?search=' + search + '&parent_id=' + parentId;
    });

    $('#searchInput').on('keypress', function(e) {
        if (e.which === 13) $('#searchBtn').click();
    });

    $('#clearSearch').on('click', function() {
        $('#searchInput').val('');
        $('#searchBtn').click();
    });

    $('#parentFilter').on('change', function() {
        $('#searchBtn').click();
    });

    if ($('#searchInput').val()) $('#clearSearch').show();
});
</script>
@endpush

@push('styles')
<style>
    .category-card {
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .category-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    .category-icon {
        height: 60px;
    }
</style>
@endpush