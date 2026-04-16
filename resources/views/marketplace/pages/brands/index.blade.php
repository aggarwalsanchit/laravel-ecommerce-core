{{-- resources/views/marketplace/pages/brands/index.blade.php --}}
@extends('management.layouts.app')

@section('title', 'Product Brands')

@section('content')
    <div class="page-content">
        <div class="page-container">
            <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                <div class="flex-grow-1">
                    <h4 class="fs-18 text-uppercase fw-bold mb-0">Product Brands</h4>
                    <p class="text-muted mb-0">Browse available brands for your products</p>
                </div>
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('vendor.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Brands</li>
                    </ol>
                </div>
            </div>

            {{-- Info Alert --}}
            <div class="alert alert-info mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <i class="ti ti-info-circle me-2"></i>
                        These are the available brands for your products.
                        @if ($pendingRequestsCount > 0)
                            <strong>You have {{ $pendingRequestsCount }} pending brand request(s).</strong>
                        @endif
                    </div>
                    <div>
                        <a href="{{ route('vendor.brands.request.create') }}" class="btn btn-sm btn-warning">
                            <i class="ti ti-plus"></i> Request Brand
                        </a>
                        <a href="{{ route('vendor.brands.requests.index') }}" class="btn btn-sm btn-secondary">
                            <i class="ti ti-list"></i> My Requests
                            @if ($pendingRequestsCount > 0)
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
                        <div class="col-md-5">
                            <div class="input-group">
                                <input type="text" class="form-control" id="searchInput"
                                    placeholder="Search brands by name or code..." value="{{ request('search') }}">
                                <button class="btn btn-primary" id="searchBtn">
                                    <i class="ti ti-search"></i>
                                </button>
                                <button class="btn btn-secondary" id="clearSearch" style="display: none;">
                                    <i class="ti ti-x"></i> Clear
                                </button>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <select class="form-select" id="categoryFilter">
                                <option value="">All Categories</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ str_repeat('— ', $category->depth ?? 0) }}{{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="featuredFilter">
                                <option value="">All Brands</option>
                                <option value="yes" {{ request('featured') == 'yes' ? 'selected' : '' }}>Featured Brands
                                </option>
                                <option value="no" {{ request('featured') == 'no' ? 'selected' : '' }}>Non-Featured
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Brands Grid --}}
            <div class="row">
                @forelse($brands as $brand)
                    <div class="col-md-4 col-lg-3 mb-4">
                        <div class="card h-100 brand-card">
                            <div class="card-body text-center">
                                <div class="brand-logo mb-3">
                                    @if ($brand->logo)
                                        <img src="{{ asset('storage/brands/' . $brand->logo) }}" alt="{{ $brand->name }}"
                                            style="max-height: 80px; max-width: 100%; object-fit: contain;">
                                    @else
                                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto"
                                            style="width: 80px; height: 80px;">
                                            <i class="ti ti-brand-airbnb fs-1 text-primary"></i>
                                        </div>
                                    @endif
                                </div>
                                <h5 class="card-title">{{ $brand->name }}</h5>
                                <p class="card-text text-muted small">
                                    <code>{{ $brand->code }}</code>
                                </p>
                                @if ($brand->categories->count() > 0)
                                    <div class="mb-2">
                                        @foreach ($brand->categories->take(2) as $category)
                                            <span class="badge bg-info-subtle text-info me-1" style="font-size: 10px;">
                                                {{ $category->name }}
                                            </span>
                                        @endforeach
                                        @if ($brand->categories->count() > 2)
                                            <span class="badge bg-secondary-subtle text-secondary" style="font-size: 10px;">
                                                +{{ $brand->categories->count() - 2 }} more
                                            </span>
                                        @endif
                                    </div>
                                @endif
                                @if ($brand->is_featured)
                                    <span class="badge bg-warning">
                                        <i class="ti ti-star"></i> Featured
                                    </span>
                                @endif
                            </div>
                            <div class="card-footer bg-transparent text-center">
                                <a href="{{ route('vendor.brands.show', $brand->id) }}" class="btn btn-sm btn-primary">
                                    <i class="ti ti-eye"></i> View Details
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-center py-5">
                            <i class="ti ti-brand-airbnb" style="font-size: 64px; opacity: 0.5;"></i>
                            <h4 class="mt-3">No Brands Found</h4>
                            <p class="text-muted">No brands are available at the moment.</p>
                            <a href="{{ route('vendor.brands.request.create') }}" class="btn btn-primary mt-2">
                                <i class="ti ti-plus"></i> Request a Brand
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div class="mt-4">
                {{ $brands->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#searchBtn').on('click', function() {
                let search = $('#searchInput').val();
                let categoryId = $('#categoryFilter').val();
                let featured = $('#featuredFilter').val();
                window.location.href = '{{ route('vendor.brands.index') }}?search=' + search +
                    '&category_id=' + categoryId + '&featured=' + featured;
            });

            $('#searchInput').on('keypress', function(e) {
                if (e.which === 13) $('#searchBtn').click();
            });

            $('#clearSearch').on('click', function() {
                $('#searchInput').val('');
                $('#searchBtn').click();
            });

            $('#categoryFilter, #featuredFilter').on('change', function() {
                $('#searchBtn').click();
            });

            if ($('#searchInput').val()) $('#clearSearch').show();
        });
    </script>
@endpush

@push('styles')
    <style>
        .brand-card {
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .brand-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .brand-logo {
            height: 100px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
@endpush
