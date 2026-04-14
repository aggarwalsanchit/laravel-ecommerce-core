{{-- resources/views/marketplace/pages/colors/index.blade.php --}}
@extends('management.layouts.app')

@section('title', 'Product Colors')

@section('content')
<div class="page-content">
    <div class="page-container">
        <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
            <div class="flex-grow-1">
                <h4 class="fs-18 text-uppercase fw-bold mb-0">Product Colors</h4>
                <p class="text-muted mb-0">Browse available colors for your products</p>
            </div>
            <div class="text-end">
                <ol class="breadcrumb m-0 py-0">
                    <li class="breadcrumb-item"><a href="{{ route('vendor.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Colors</li>
                </ol>
            </div>
        </div>

        {{-- Info Alert --}}
        <div class="alert alert-info mb-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <i class="ti ti-info-circle me-2"></i>
                    These are the available colors for your products.
                    @if($pendingRequestsCount > 0)
                        <strong>You have {{ $pendingRequestsCount }} pending color request(s).</strong>
                    @endif
                </div>
                <div>
                    <a href="{{ route('vendor.colors.request.create') }}" class="btn btn-sm btn-warning">
                        <i class="ti ti-plus"></i> Request New Color
                    </a>
                    <a href="{{ route('vendor.colors.requests.index') }}" class="btn btn-sm btn-secondary ms-2">
                        <i class="ti ti-list"></i> My Requests
                        @if($pendingRequestsCount > 0)
                            <span class="badge bg-light text-dark ms-1">{{ $pendingRequestsCount }}</span>
                        @endif
                    </a>
                </div>
            </div>
        </div>

        {{-- Search and Filter --}}
        <div class="card mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-5">
                        <div class="input-group">
                            <input type="text" class="form-control" id="searchInput" 
                                   placeholder="Search by color name or hex code..." 
                                   value="{{ request('search') }}">
                            <button class="btn btn-primary" id="searchBtn">
                                <i class="ti ti-search"></i>
                            </button>
                            <button class="btn btn-secondary" id="clearSearch" style="display: none;">
                                <i class="ti ti-x"></i> Clear
                            </button>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="featuredFilter">
                            <option value="">All Colors</option>
                            <option value="true" {{ request('featured') == 'true' ? 'selected' : '' }}>Featured Colors</option>
                            <option value="false" {{ request('featured') == 'false' ? 'selected' : '' }}>Not Featured</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select" id="popularFilter">
                            <option value="">All Colors</option>
                            <option value="true" {{ request('popular') == 'true' ? 'selected' : '' }}>Popular Colors</option>
                            <option value="false" {{ request('popular') == 'false' ? 'selected' : '' }}>Not Popular</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        {{-- Colors Grid --}}
        <div class="row">
            @forelse($colors as $color)
                <div class="col-md-3 col-lg-2 mb-4">
                    <div class="card h-100 color-card text-center">
                        <div class="card-body">
                            <div class="color-swatch mb-3 mx-auto" 
                                 style="background-color: {{ $color->code }}; 
                                        width: 70px; 
                                        height: 70px; 
                                        border-radius: 50%; 
                                        border: 2px solid #ddd;
                                        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
                                        margin: 0 auto 15px;">
                            </div>
                            <h6 class="card-title mb-1">{{ $color->name }}</h6>
                            <code class="text-muted small">{{ $color->code }}</code>
                            <div class="mt-2">
                                @if($color->is_featured)
                                    <span class="badge bg-warning text-dark"><i class="ti ti-star"></i> Featured</span>
                                @endif
                                @if($color->is_popular)
                                    <span class="badge bg-danger"><i class="ti ti-fire"></i> Popular</span>
                                @endif
                            </div>
                        </div>
                        <div class="card-footer bg-transparent text-center">
                            <a href="{{ route('vendor.colors.show', $color->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="ti ti-eye"></i> View Details
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="ti ti-palette-off" style="font-size: 64px; opacity: 0.5;"></i>
                        <h4 class="mt-3">No Colors Found</h4>
                        <p class="text-muted">No colors are available at the moment.</p>
                        <a href="{{ route('vendor.colors.request.create') }}" class="btn btn-primary mt-2">
                            <i class="ti ti-plus"></i> Request a Color
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $colors->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Search functionality
    $('#searchBtn').on('click', function() {
        applyFilters();
    });

    $('#searchInput').on('keypress', function(e) {
        if (e.which === 13) {
            applyFilters();
        }
    });

    $('#clearSearch').on('click', function() {
        $('#searchInput').val('');
        applyFilters();
    });

    $('#featuredFilter, #popularFilter').on('change', function() {
        applyFilters();
    });

    function applyFilters() {
        let search = $('#searchInput').val();
        let featured = $('#featuredFilter').val();
        let popular = $('#popularFilter').val();
        
        let url = '{{ route("vendor.colors.index") }}?';
        let params = [];
        
        if (search) params.push('search=' + encodeURIComponent(search));
        if (featured) params.push('featured=' + featured);
        if (popular) params.push('popular=' + popular);
        
        window.location.href = url + params.join('&');
    }

    // Show clear button if search exists
    if ($('#searchInput').val()) {
        $('#clearSearch').show();
    }
});
</script>
@endpush

@push('styles')
<style>
    .color-card {
        transition: transform 0.2s, box-shadow 0.2s;
        cursor: pointer;
    }
    .color-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    .color-swatch {
        transition: transform 0.2s;
    }
    .color-card:hover .color-swatch {
        transform: scale(1.05);
    }
    .card-footer {
        border-top: none;
        padding-top: 0;
    }
</style>
@endpush