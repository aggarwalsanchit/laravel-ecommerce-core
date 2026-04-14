{{-- resources/views/marketplace/pages/attributes/index.blade.php --}}
@extends('management.layouts.app')

@section('title', 'Product Attributes')

@section('content')
<div class="page-content">
    <div class="page-container">
        <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
            <div class="flex-grow-1">
                <h4 class="fs-18 text-uppercase fw-bold mb-0">Product Attributes</h4>
                <p class="text-muted mb-0">Browse available attributes for your products</p>
            </div>
            <div class="text-end">
                <ol class="breadcrumb m-0 py-0">
                    <li class="breadcrumb-item"><a href="{{ route('vendor.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Attributes</li>
                </ol>
            </div>
        </div>

        {{-- Info Alert --}}
        <div class="alert alert-info mb-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <i class="ti ti-info-circle me-2"></i>
                    These are the available attributes for your products. Attributes define product specifications like color, size, material, etc.
                    @if(($pendingRequestsCount ?? 0) > 0 || ($pendingValueRequestsCount ?? 0) > 0)
                        <strong>You have {{ ($pendingRequestsCount ?? 0) + ($pendingValueRequestsCount ?? 0) }} pending request(s).</strong>
                    @endif
                </div>
                <div class="d-flex gap-2">
                    <div class="btn-group">
                        <button type="button" class="btn btn-sm btn-warning dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="ti ti-plus"></i> Request New
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('vendor.attributes.request.create') }}">
                                <i class="ti ti-input"></i> Request New Attribute
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('vendor.attributes.value-request.create') }}">
                                <i class="ti ti-list"></i> Request New Attribute Value
                            </a></li>
                        </ul>
                    </div>
                    <div class="btn-group">
                        <button type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="ti ti-list"></i> My Requests
                            @if(($pendingRequestsCount ?? 0) > 0 || ($pendingValueRequestsCount ?? 0) > 0)
                                <span class="badge bg-light text-dark ms-1">{{ ($pendingRequestsCount ?? 0) + ($pendingValueRequestsCount ?? 0) }}</span>
                            @endif
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('vendor.attributes.requests.index') }}">
                                <i class="ti ti-input"></i> Attribute Requests
                                @if(($pendingRequestsCount ?? 0) > 0)
                                    <span class="badge bg-warning text-dark ms-1">{{ $pendingRequestsCount ?? 0 }}</span>
                                @endif
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('vendor.attributes.value-requests.index') }}">
                                <i class="ti ti-list"></i> Value Requests
                                @if(($pendingValueRequestsCount ?? 0) > 0)
                                    <span class="badge bg-warning text-dark ms-1">{{ $pendingValueRequestsCount ?? 0 }}</span>
                                @endif
                            </a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        {{-- Search and Filter --}}
        <div class="card mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="input-group">
                            <input type="text" class="form-control" id="searchInput" 
                                   placeholder="Search by name..." 
                                   value="{{ request('search') }}">
                            <button class="btn btn-primary" id="searchBtn">
                                <i class="ti ti-search"></i>
                            </button>
                            <button class="btn btn-secondary" id="clearSearch" style="display: none;">
                                <i class="ti ti-x"></i> Clear
                            </button>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" id="typeFilter">
                            <option value="">All Types</option>
                            <option value="text" {{ request('type') == 'text' ? 'selected' : '' }}>Text</option>
                            <option value="textarea" {{ request('type') == 'textarea' ? 'selected' : '' }}>Textarea</option>
                            <option value="number" {{ request('type') == 'number' ? 'selected' : '' }}>Number</option>
                            <option value="select" {{ request('type') == 'select' ? 'selected' : '' }}>Select</option>
                            <option value="multiselect" {{ request('type') == 'multiselect' ? 'selected' : '' }}>Multi-Select</option>
                            <option value="checkbox" {{ request('type') == 'checkbox' ? 'selected' : '' }}>Checkbox</option>
                            <option value="radio" {{ request('type') == 'radio' ? 'selected' : '' }}>Radio</option>
                            <option value="date" {{ request('type') == 'date' ? 'selected' : '' }}>Date</option>
                            <option value="color" {{ request('type') == 'color' ? 'selected' : '' }}>Color</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="categoryFilter">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ str_repeat('— ', $category->depth ?? 0) }}{{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" id="groupFilter">
                            <option value="">All Groups</option>
                            @foreach($groups as $group)
                                <option value="{{ $group->id }}" {{ request('group_id') == $group->id ? 'selected' : '' }}>
                                    {{ $group->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" id="sortFilter">
                            <option value="order" {{ request('sort_by') == 'order' ? 'selected' : '' }}>Default Order</option>
                            <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>Name (A-Z)</option>
                            <option value="type" {{ request('sort_by') == 'type' ? 'selected' : '' }}>Type</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        {{-- Attributes Grid --}}
        <div class="row">
            @forelse($attributes as $attribute)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 attribute-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div class="attribute-icon">
                                    @if($attribute->icon)
                                        <i class="{{ $attribute->icon }} fs-4 text-primary"></i>
                                    @else
                                        <i class="ti ti-input fs-4 text-primary"></i>
                                    @endif
                                </div>
                                <span class="badge bg-info">{{ $attribute->type_label }}</span>
                            </div>
                            <h5 class="card-title mb-1">{{ $attribute->name }}</h5>
                            <p class="card-text text-muted small">{{ Str::limit($attribute->description ?? 'No description', 80) }}</p>
                            
                            <div class="mt-2">
                                @if($attribute->is_required)
                                    <span class="badge bg-danger"><i class="ti ti-asterisk"></i> Required</span>
                                @endif
                                @if($attribute->is_filterable)
                                    <span class="badge bg-success"><i class="ti ti-filter"></i> Filterable</span>
                                @endif
                                @if($attribute->is_searchable)
                                    <span class="badge bg-info"><i class="ti ti-search"></i> Searchable</span>
                                @endif
                            </div>
                            
                            @if($attribute->unit)
                                <div class="mt-2 small text-muted">
                                    <i class="ti ti-ruler"></i> Unit: {{ $attribute->unit }}
                                </div>
                            @endif
                            
                            @if($attribute->group)
                                <div class="mt-2 small text-muted">
                                    <i class="ti ti-layout-sidebar"></i> Group: {{ $attribute->group->name }}
                                </div>
                            @endif
                            
                            @if($attribute->categories->count() > 0)
                                <div class="mt-2">
                                    @foreach($attribute->categories->take(2) as $category)
                                        <span class="badge bg-secondary">{{ $category->name }}</span>
                                    @endforeach
                                    @if($attribute->categories->count() > 2)
                                        <span class="badge bg-secondary">+{{ $attribute->categories->count() - 2 }}</span>
                                    @endif
                                </div>
                            @endif
                        </div>
                        <div class="card-footer bg-transparent text-center">
                            <a href="{{ route('vendor.attributes.show', $attribute->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="ti ti-eye"></i> View Details
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="ti ti-input-off" style="font-size: 64px; opacity: 0.5;"></i>
                        <h4 class="mt-3">No Attributes Found</h4>
                        <p class="text-muted">No attributes are available at the moment.</p>
                        <a href="{{ route('vendor.attributes.request.create') }}" class="btn btn-primary mt-2">
                            <i class="ti ti-plus"></i> Request an Attribute
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $attributes->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    function applyFilters() {
        let search = $('#searchInput').val();
        let type = $('#typeFilter').val();
        let categoryId = $('#categoryFilter').val();
        let groupId = $('#groupFilter').val();
        let sortBy = $('#sortFilter').val();
        
        let url = '{{ route("vendor.attributes.index") }}?';
        let params = [];
        
        if (search) params.push('search=' + encodeURIComponent(search));
        if (type) params.push('type=' + type);
        if (categoryId) params.push('category_id=' + categoryId);
        if (groupId) params.push('group_id=' + groupId);
        if (sortBy && sortBy !== 'order') params.push('sort_by=' + sortBy);
        
        window.location.href = url + params.join('&');
    }

    $('#searchBtn').on('click', applyFilters);
    $('#typeFilter, #categoryFilter, #groupFilter, #sortFilter').on('change', applyFilters);
    
    $('#searchInput').on('keypress', function(e) {
        if (e.which === 13) applyFilters();
    });

    $('#clearSearch').on('click', function() {
        $('#searchInput').val('');
        applyFilters();
    });

    if ($('#searchInput').val()) $('#clearSearch').show();
});
</script>
@endpush

@push('styles')
<style>
    .attribute-card {
        transition: transform 0.2s, box-shadow 0.2s;
        cursor: pointer;
    }
    .attribute-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    .attribute-icon {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: rgba(13, 110, 253, 0.1);
        border-radius: 10px;
    }
    .card-footer {
        border-top: none;
        padding-top: 0;
    }
    .badge {
        font-size: 0.7rem;
        padding: 0.25rem 0.5rem;
    }
</style>
@endpush