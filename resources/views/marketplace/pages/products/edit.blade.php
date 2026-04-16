{{-- resources/views/marketplace/pages/products/edit.blade.php --}}
@extends('management.layouts.app')

@section('title', 'Edit Product - ' . $product->name)

@section('content')
    <div class="page-content">
        <div class="page-container">
            <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                <div class="flex-grow-1">
                    <h4 class="fs-18 text-uppercase fw-bold mb-0">Edit Product: {{ $product->name }}</h4>
                </div>
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('vendor.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('vendor.products.index') }}">Products</a></li>
                        <li class="breadcrumb-item active">Edit Product</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form id="productForm" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                {{-- Tabs --}}
                                <ul class="nav nav-tabs" id="productTabs" role="tablist">
                                    <li class="nav-item">
                                        <button class="nav-link active" id="basic-tab" data-bs-toggle="tab"
                                            data-bs-target="#basic" type="button">
                                            <i class="ti ti-info-circle"></i> Basic Info
                                        </button>
                                    </li>
                                    <li class="nav-item">
                                        <button class="nav-link" id="categories-tab" data-bs-toggle="tab"
                                            data-bs-target="#categories" type="button">
                                            <i class="ti ti-folder"></i> Categories
                                        </button>
                                    </li>
                                    <li class="nav-item">
                                        <button class="nav-link" id="pricing-tab" data-bs-toggle="tab"
                                            data-bs-target="#pricing" type="button">
                                            <i class="ti ti-currency-dollar"></i> Pricing & Stock
                                        </button>
                                    </li>
                                    <li class="nav-item">
                                        <button class="nav-link" id="media-tab" data-bs-toggle="tab" data-bs-target="#media"
                                            type="button">
                                            <i class="ti ti-photo"></i> Media
                                        </button>
                                    </li>
                                    <li class="nav-item">
                                        <button class="nav-link" id="variants-tab" data-bs-toggle="tab"
                                            data-bs-target="#variants" type="button">
                                            <i class="ti ti-color-swatch"></i> Variants
                                        </button>
                                    </li>
                                    <li class="nav-item">
                                        <button class="nav-link" id="attributes-tab" data-bs-toggle="tab"
                                            data-bs-target="#attributes" type="button">
                                            <i class="ti ti-list"></i> Attributes
                                        </button>
                                    </li>
                                    <li class="nav-item">
                                        <button class="nav-link" id="tiers-tab" data-bs-toggle="tab" data-bs-target="#tiers"
                                            type="button">
                                            <i class="ti ti-chart-line"></i> Tier Pricing
                                        </button>
                                    </li>
                                    <li class="nav-item">
                                        <button class="nav-link" id="seo-tab" data-bs-toggle="tab" data-bs-target="#seo"
                                            type="button">
                                            <i class="ti ti-meta-tag"></i> SEO & Social
                                        </button>
                                    </li>
                                </ul>

                                <div class="tab-content mt-3">
                                    {{-- Basic Info Tab --}}
                                    <div class="tab-pane fade show active" id="basic" role="tabpanel">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="name" class="form-label">Product Name <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="name" name="name"
                                                        value="{{ old('name', $product->name) }}">
                                                    <div class="invalid-feedback" id="name-error"></div>
                                                    <small class="text-muted">URL slug: <span id="slug-preview"
                                                            class="text-primary">{{ $product->slug }}</span></small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="sku" class="form-label">SKU</label>
                                                    <input type="text" class="form-control" id="sku"
                                                        name="sku" value="{{ old('sku', $product->sku) }}">
                                                    <div class="invalid-feedback" id="sku-error"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="barcode" class="form-label">Barcode</label>
                                                    <input type="text" class="form-control" id="barcode"
                                                        name="barcode" value="{{ old('barcode', $product->barcode) }}">
                                                    <div class="invalid-feedback" id="barcode-error"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="sort_order" class="form-label">Sort Order</label>
                                                    <input type="number" class="form-control" id="sort_order"
                                                        name="sort_order"
                                                        value="{{ old('sort_order', $product->sort_order) }}">
                                                    <div class="invalid-feedback" id="sort_order-error"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Product Flags</label>
                                                    <div class="d-flex gap-3">
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input"
                                                                id="is_featured" name="is_featured" value="1"
                                                                {{ $product->is_featured ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="is_featured"><i
                                                                    class="ti ti-star"></i> Featured</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input"
                                                                id="is_bestseller" name="is_bestseller" value="1"
                                                                {{ $product->is_bestseller ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="is_bestseller"><i
                                                                    class="ti ti-trending-up"></i> Bestseller</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input"
                                                                id="is_new" name="is_new" value="1"
                                                                {{ $product->is_new ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="is_new"><i
                                                                    class="ti ti-spark"></i> New</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input"
                                                                id="status" name="status" value="1"
                                                                {{ $product->status ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="status"><i
                                                                    class="ti ti-circle-check"></i> Active</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label class="form-label">Tags</label>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label class="text-muted small">Select Existing Tags</label>
                                                            <select class="form-control choices-multi" id="tags_select"
                                                                name="tags_select[]" multiple>
                                                                @foreach ($tags as $tag)
                                                                    <option value="{{ $tag->id }}"
                                                                        {{ in_array($tag->id, $selectedTags) ? 'selected' : '' }}>
                                                                        {{ $tag->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <div class="invalid-feedback" id="tags-error"></div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="text-muted small">Or Add New Tags (comma
                                                                separated)</label>
                                                            <input type="text" class="form-control" id="tags_input"
                                                                name="tags_input"
                                                                placeholder="e.g., summer, sale, new-arrival">
                                                            <small class="text-muted">Separate multiple tags with
                                                                commas</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="short_description" class="form-label">Short
                                                        Description</label>
                                                    <textarea class="form-control" id="short_description" name="short_description" rows="2">{{ old('short_description', $product->short_description) }}</textarea>
                                                    <div class="invalid-feedback" id="short_description-error"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="description" class="form-label">Full Description</label>
                                                    <textarea class="form-control" id="description" name="description" rows="5">{{ old('description', $product->description) }}</textarea>
                                                    <div class="invalid-feedback" id="description-error"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="highlights" class="form-label">Highlights</label>
                                                    <div id="highlights-container">
                                                        @php $highlights = is_array($product->highlights) ? $product->highlights : json_decode($product->highlights, true); @endphp
                                                        @if (!empty($highlights) && is_array($highlights))
                                                            @foreach ($highlights as $highlight)
                                                                <div class="input-group mb-2 highlight-row">
                                                                    <input type="text" class="form-control"
                                                                        name="highlights[]" value="{{ $highlight }}">
                                                                    <button type="button"
                                                                        class="btn btn-danger remove-highlight"><i
                                                                            class="ti ti-trash"></i></button>
                                                                </div>
                                                            @endforeach
                                                        @else
                                                            <div class="input-group mb-2 highlight-row">
                                                                <input type="text" class="form-control"
                                                                    name="highlights[]"
                                                                    placeholder="e.g., 16GB RAM, 512GB SSD">
                                                                <button type="button"
                                                                    class="btn btn-danger remove-highlight"><i
                                                                        class="ti ti-trash"></i></button>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <button type="button" class="btn btn-sm btn-secondary"
                                                        id="add-highlight"><i class="ti ti-plus"></i> Add
                                                        Highlight</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Categories Tab --}}
                                    <div class="tab-pane fade" id="categories" role="tabpanel">
                                        <div class="alert alert-info mb-3">
                                            <i class="ti ti-info-circle"></i>
                                            <strong>Primary Category:</strong> Only one (used for URL and breadcrumbs)<br>
                                            <strong>Additional Categories:</strong> Multiple (for better discoverability)
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="card mb-3">
                                                    <div
                                                        class="card-header bg-light d-flex justify-content-between align-items-center">
                                                        <h6 class="mb-0"><i class="ti ti-star text-warning"></i> Primary
                                                            Category <span class="text-danger">*</span></h6>
                                                        <button type="button" class="btn btn-sm btn-primary"
                                                            id="editPrimaryCategoryBtn">
                                                            <i class="ti ti-edit"></i> Edit
                                                        </button>
                                                    </div>
                                                    <div class="card-body">
                                                        <div id="primary-category-view">
                                                            @php
                                                                $primaryCategory = \App\Models\Category::find(
                                                                    $product->primary_category_id,
                                                                );
                                                            @endphp
                                                            @if ($primaryCategory)
                                                                <div class="d-flex align-items-center">
                                                                    <i class="ti ti-folder text-primary fs-4 me-2"></i>
                                                                    <div>
                                                                        <strong
                                                                            class="fs-5">{{ $primaryCategory->name }}</strong>
                                                                        @if ($primaryCategory->parent)
                                                                            <br><small class="text-muted">Parent:
                                                                                {{ $primaryCategory->parent->name }}</small>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @else
                                                                <div class="text-muted">No primary category selected</div>
                                                            @endif
                                                        </div>
                                                        <div id="primary-category-edit" style="display: none;">
                                                            <select class="form-control select2-single"
                                                                id="primary_category_id" name="primary_category_id"
                                                                style="width: 100%;">
                                                                <option value="">-- Select Primary Category --
                                                                </option>
                                                                @foreach ($parentCategories as $cat)
                                                                    <option value="{{ $cat->id }}"
                                                                        {{ $product->primary_category_id == $cat->id ? 'selected' : '' }}>
                                                                        {{ $cat->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <div class="invalid-feedback" id="primary_category_id-error">
                                                            </div>
                                                            <div class="mt-2">
                                                                <button type="button" class="btn btn-sm btn-success"
                                                                    id="savePrimaryCategoryBtn">
                                                                    <i class="ti ti-check"></i> Save
                                                                </button>
                                                                <button type="button" class="btn btn-sm btn-secondary"
                                                                    id="cancelPrimaryCategoryBtn">
                                                                    <i class="ti ti-x"></i> Cancel
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="card">
                                                    <div
                                                        class="card-header bg-light d-flex justify-content-between align-items-center">
                                                        <h6 class="mb-0"><i class="ti ti-folder-plus"></i> Additional
                                                            Categories</h6>
                                                        <button type="button" class="btn btn-sm btn-primary"
                                                            id="editAdditionalCategoriesBtn">
                                                            <i class="ti ti-edit"></i> Edit
                                                        </button>
                                                    </div>
                                                    <div class="card-body">
                                                        <div id="additional-categories-view">
                                                            @if ($additionalCategoriesList->count() > 0)
                                                                <div class="d-flex flex-wrap gap-2"
                                                                    id="additional-categories-list">
                                                                    @foreach ($additionalCategoriesList as $cat)
                                                                        <span class="badge bg-secondary fs-6 p-2"
                                                                            data-category-id="{{ $cat->id }}">
                                                                            <i class="ti ti-folder"></i>
                                                                            {{ $cat->name }}
                                                                            <input type="hidden"
                                                                                name="additional_categories[]"
                                                                                value="{{ $cat->id }}">
                                                                        </span>
                                                                    @endforeach
                                                                </div>
                                                            @else
                                                                <div class="text-muted" id="additional-categories-empty">
                                                                    No additional categories selected</div>
                                                            @endif
                                                        </div>
                                                        <div id="additional-categories-edit" style="display: none;">
                                                            <div id="category-selection-container">
                                                                <div class="alert alert-info">
                                                                    <i class="ti ti-info-circle"></i> Select a primary
                                                                    category first to add additional categories
                                                                </div>
                                                            </div>
                                                            <div class="mt-3">
                                                                <button type="button" class="btn btn-sm btn-success"
                                                                    id="saveAdditionalCategoriesBtn">
                                                                    <i class="ti ti-check"></i> Save Changes
                                                                </button>
                                                                <button type="button" class="btn btn-sm btn-secondary"
                                                                    id="cancelAdditionalCategoriesBtn">
                                                                    <i class="ti ti-x"></i> Cancel
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Pricing & Stock Tab --}}
                                    <div class="tab-pane fade" id="pricing" role="tabpanel">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="price" class="form-label">Regular Price <span
                                                            class="text-danger">*</span></label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">$</span>
                                                        <input type="number" step="0.01" class="form-control"
                                                            id="price" name="price"
                                                            value="{{ old('price', $product->price) }}">
                                                    </div>
                                                    <div class="invalid-feedback" id="price-error"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="compare_price" class="form-label">Compare at Price</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">$</span>
                                                        <input type="number" step="0.01" class="form-control"
                                                            id="compare_price" name="compare_price"
                                                            value="{{ old('compare_price', $product->compare_price) }}">
                                                    </div>
                                                    <div class="invalid-feedback" id="compare_price-error"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="cost" class="form-label">Cost</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">$</span>
                                                        <input type="number" step="0.01" class="form-control"
                                                            id="cost" name="cost"
                                                            value="{{ old('cost', $product->cost) }}">
                                                    </div>
                                                    <div class="invalid-feedback" id="cost-error"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="wholesale_price" class="form-label">Wholesale
                                                        Price</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">$</span>
                                                        <input type="number" step="0.01" class="form-control"
                                                            id="wholesale_price" name="wholesale_price"
                                                            value="{{ old('wholesale_price', $product->wholesale_price) }}">
                                                    </div>
                                                    <div class="invalid-feedback" id="wholesale_price-error"></div>
                                                    <div class="form-check mt-1">
                                                        <input type="checkbox" class="form-check-input" id="is_wholesale"
                                                            name="is_wholesale" value="1"
                                                            {{ $product->is_wholesale ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="is_wholesale">Enable
                                                            wholesale pricing</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="min_price" class="form-label">Minimum Price</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">$</span>
                                                        <input type="number" step="0.01" class="form-control"
                                                            id="min_price" name="min_price"
                                                            value="{{ old('min_price', $product->min_price) }}">
                                                    </div>
                                                    <div class="invalid-feedback" id="min_price-error"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="max_price" class="form-label">Maximum Price</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">$</span>
                                                        <input type="number" step="0.01" class="form-control"
                                                            id="max_price" name="max_price"
                                                            value="{{ old('max_price', $product->max_price) }}">
                                                    </div>
                                                    <div class="invalid-feedback" id="max_price-error"></div>
                                                    <div class="form-check mt-1">
                                                        <input type="checkbox" class="form-check-input" id="is_range"
                                                            name="is_range" value="1"
                                                            {{ $product->is_range ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="is_range">Price range</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <hr>
                                        <h6>Stock & Inventory</h6>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="mb-3">
                                                    <label for="stock_quantity" class="form-label">Stock Quantity</label>
                                                    <input type="number" class="form-control" id="stock_quantity"
                                                        name="stock_quantity"
                                                        value="{{ old('stock_quantity', $product->stock_quantity) }}">
                                                    <div class="invalid-feedback" id="stock_quantity-error"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="mb-3">
                                                    <label for="low_stock_threshold" class="form-label">Low Stock
                                                        Threshold</label>
                                                    <input type="number" class="form-control" id="low_stock_threshold"
                                                        name="low_stock_threshold"
                                                        value="{{ old('low_stock_threshold', $product->low_stock_threshold) }}">
                                                    <div class="invalid-feedback" id="low_stock_threshold-error"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="mb-3">
                                                    <label for="stock_status" class="form-label">Stock Status</label>
                                                    <select class="form-select" id="stock_status" name="stock_status">
                                                        <option value="instock"
                                                            {{ $product->stock_status == 'instock' ? 'selected' : '' }}>In
                                                            Stock</option>
                                                        <option value="outofstock"
                                                            {{ $product->stock_status == 'outofstock' ? 'selected' : '' }}>
                                                            Out of Stock</option>
                                                        <option value="backorder"
                                                            {{ $product->stock_status == 'backorder' ? 'selected' : '' }}>
                                                            Backorder</option>
                                                    </select>
                                                    <div class="invalid-feedback" id="stock_status-error"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="mt-4">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" id="track_stock"
                                                            name="track_stock" value="1"
                                                            {{ $product->track_stock ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="track_stock">Track
                                                            stock</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="allow_backorder" name="allow_backorder" value="1"
                                                            {{ $product->allow_backorder ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="allow_backorder">Allow
                                                            backorders</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <hr>
                                        <h6>Shipping Details</h6>
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="mb-3">
                                                    <label for="weight" class="form-label">Weight (kg)</label>
                                                    <input type="number" step="0.01" class="form-control"
                                                        id="weight" name="weight"
                                                        value="{{ old('weight', $product->weight) }}">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="mb-3">
                                                    <label for="length" class="form-label">Length (cm)</label>
                                                    <input type="number" step="0.01" class="form-control"
                                                        id="length" name="length"
                                                        value="{{ old('length', $product->length) }}">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="mb-3">
                                                    <label for="width" class="form-label">Width (cm)</label>
                                                    <input type="number" step="0.01" class="form-control"
                                                        id="width" name="width"
                                                        value="{{ old('width', $product->width) }}">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="mb-3">
                                                    <label for="height" class="form-label">Height (cm)</label>
                                                    <input type="number" step="0.01" class="form-control"
                                                        id="height" name="height"
                                                        value="{{ old('height', $product->height) }}">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-check mt-4">
                                                    <input type="checkbox" class="form-check-input" id="free_shipping"
                                                        name="free_shipping" value="1"
                                                        {{ $product->free_shipping ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="free_shipping">Free
                                                        Shipping</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Media Tab --}}
                                    <div class="tab-pane fade" id="media" role="tabpanel">
                                        <div class="alert alert-info mb-3"><i class="ti ti-info-circle"></i> Images are
                                            automatically compressed.</div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="card">
                                                    <div class="card-header bg-light">
                                                        <h6 class="mb-0">Existing Images</h6>
                                                    </div>
                                                    <div class="card-body">
                                                        <div id="existing-images-container" class="row g-3">
                                                            @foreach ($product->images as $image)
                                                                <div class="col-md-3 existing-image-item"
                                                                    data-id="{{ $image->id }}">
                                                                    <div class="border rounded p-2 text-center">
                                                                        <img src="{{ asset('storage/products/' . $image->image) }}"
                                                                            class="img-fluid rounded mb-2"
                                                                            style="height: 120px; object-fit: cover; width: 100%;">
                                                                        <div class="form-check">
                                                                            <input type="radio" name="main_image_id"
                                                                                value="{{ $image->id }}"
                                                                                class="form-check-input main-image-radio"
                                                                                {{ $image->is_main ? 'checked' : '' }}>
                                                                            <label class="form-check-label small">Main
                                                                                Image</label>
                                                                        </div>
                                                                        <input type="text"
                                                                            name="existing_images_alt[{{ $image->id }}]"
                                                                            class="form-control form-control-sm mt-2"
                                                                            value="{{ $image->alt_text }}"
                                                                            placeholder="Alt text">
                                                                        <button type="button"
                                                                            class="btn btn-sm btn-danger mt-2 remove-existing-image"
                                                                            data-id="{{ $image->id }}">Remove</button>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 mt-3">
                                                <div class="card">
                                                    <div class="card-header bg-light">
                                                        <h6 class="mb-0">Add New Images</h6>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="mb-3">
                                                            <input type="file" class="form-control" id="images_input"
                                                                name="images[]" accept="image/*" multiple>
                                                            <div class="invalid-feedback" id="images-error"></div>
                                                            <small class="text-muted">Select multiple images</small>
                                                        </div>
                                                        <div id="images-preview-container" class="row g-3 mt-2"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 mt-3">
                                                <div class="card">
                                                    <div class="card-header bg-light">
                                                        <h6 class="mb-0">Product Videos</h6>
                                                    </div>
                                                    <div class="card-body">
                                                        <div id="videos-container">
                                                            @if ($product->videos->count() > 0)
                                                                @foreach ($product->videos as $video)
                                                                    <div class="video-row row mb-2">
                                                                        <div class="col-md-5">
                                                                            <input type="url" class="form-control"
                                                                                name="existing_videos[{{ $video->id }}][url]"
                                                                                value="{{ $video->url }}"
                                                                                placeholder="Video URL">
                                                                        </div>
                                                                        <div class="col-md-5">
                                                                            <input type="text" class="form-control"
                                                                                name="existing_videos[{{ $video->id }}][title]"
                                                                                value="{{ $video->title }}"
                                                                                placeholder="Title">
                                                                        </div>
                                                                        <div class="col-md-2">
                                                                            <button type="button"
                                                                                class="btn btn-danger remove-existing-video"
                                                                                data-id="{{ $video->id }}">
                                                                                <i class="ti ti-trash"></i>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            @else
                                                                <div class="video-row row mb-2">
                                                                    <div class="col-md-5">
                                                                        <input type="url" class="form-control"
                                                                            name="videos[0][url]" placeholder="Video URL">
                                                                    </div>
                                                                    <div class="col-md-5">
                                                                        <input type="text" class="form-control"
                                                                            name="videos[0][title]" placeholder="Title">
                                                                    </div>
                                                                    <div class="col-md-2">
                                                                        <button type="button"
                                                                            class="btn btn-danger remove-video" disabled>
                                                                            <i class="ti ti-trash"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <button type="button" class="btn btn-sm btn-secondary"
                                                            id="add-video">
                                                            <i class="ti ti-plus"></i> Add Video
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Variants Tab --}}
                                    <div class="tab-pane fade" id="variants" role="tabpanel">
                                        <div class="alert alert-info mb-3"><i class="ti ti-info-circle"></i> Create
                                            product variants.</div>
                                        <div id="variants-container">
                                            <table class="table table-bordered" id="variants-table">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Color</th>
                                                        <th>Size</th>
                                                        <th>SKU</th>
                                                        <th>Price</th>
                                                        <th>Compare Price</th>
                                                        <th>Wholesale Price</th>
                                                        <th>Stock</th>
                                                        <th>Image</th>
                                                        <th>Alt Text</th>
                                                        <th style="width:50px"></th>
                                                    </tr>
                                                </thead>
                                                <tbody id="variants-tbody">
                                                    @foreach ($product->variants as $variant)
                                                        <tr class="variant-row existing-variant"
                                                            data-id="{{ $variant->id }}">
                                                            <td>
                                                                <select class="form-select form-select-sm"
                                                                    name="existing_variants[{{ $variant->id }}][color_id]">
                                                                    <option value="">-- Select --</option>
                                                                    @foreach ($colors as $color)
                                                                        <option value="{{ $color->id }}"
                                                                            {{ $variant->color_id == $color->id ? 'selected' : '' }}>
                                                                            {{ $color->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <select class="form-select form-select-sm"
                                                                    name="existing_variants[{{ $variant->id }}][size_id]">
                                                                    <option value="">-- Select --</option>
                                                                    @foreach ($sizes as $size)
                                                                        <option value="{{ $size->id }}"
                                                                            {{ $variant->size_id == $size->id ? 'selected' : '' }}>
                                                                            {{ $size->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control form-control-sm"
                                                                    name="existing_variants[{{ $variant->id }}][sku]"
                                                                    value="{{ $variant->sku }}" placeholder="SKU">
                                                            </td>
                                                            <td>
                                                                <input type="number" step="0.01"
                                                                    class="form-control form-control-sm"
                                                                    name="existing_variants[{{ $variant->id }}][price]"
                                                                    value="{{ $variant->price }}" placeholder="Price">
                                                            </td>
                                                            <td>
                                                                <input type="number" step="0.01"
                                                                    class="form-control form-control-sm"
                                                                    name="existing_variants[{{ $variant->id }}][compare_price]"
                                                                    value="{{ $variant->compare_price }}"
                                                                    placeholder="Compare price">
                                                            </td>
                                                            <td>
                                                                <input type="number" step="0.01"
                                                                    class="form-control form-control-sm"
                                                                    name="existing_variants[{{ $variant->id }}][wholesale_price]"
                                                                    value="{{ $variant->wholesale_price }}"
                                                                    placeholder="Wholesale">
                                                            </td>
                                                            <td>
                                                                <input type="number" class="form-control form-control-sm"
                                                                    name="existing_variants[{{ $variant->id }}][stock_quantity]"
                                                                    value="{{ $variant->stock_quantity }}"
                                                                    placeholder="Stock">
                                                            </td>
                                                            <td>
                                                                @if ($variant->image)
                                                                    <img src="{{ asset('storage/variants/' . $variant->image) }}"
                                                                        style="max-width: 50px; max-height: 50px;"
                                                                        class="rounded mb-1">
                                                                    <input type="file"
                                                                        class="form-control form-control-sm mt-1"
                                                                        name="existing_variants[{{ $variant->id }}][image]"
                                                                        accept="image/*">
                                                                    <input type="hidden"
                                                                        name="existing_variants[{{ $variant->id }}][current_image]"
                                                                        value="{{ $variant->image }}">
                                                                    <div class="form-check mt-1">
                                                                        <input type="checkbox"
                                                                            name="existing_variants[{{ $variant->id }}][remove_image]"
                                                                            value="1" class="form-check-input">
                                                                        <label class="form-check-label small">Remove
                                                                            image</label>
                                                                    </div>
                                                                @else
                                                                    <input type="file"
                                                                        class="form-control form-control-sm"
                                                                        name="existing_variants[{{ $variant->id }}][image]"
                                                                        accept="image/*">
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control form-control-sm"
                                                                    name="existing_variants[{{ $variant->id }}][image_alt]"
                                                                    value="{{ $variant->image_alt }}"
                                                                    placeholder="Alt text">
                                                            </td>
                                                            <td>
                                                                <button type="button"
                                                                    class="btn btn-sm btn-danger remove-existing-variant"
                                                                    data-id="{{ $variant->id }}">
                                                                    <i class="ti ti-trash"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td colspan="10">
                                                            <button type="button" class="btn btn-sm btn-primary"
                                                                id="add-variant">
                                                                <i class="ti ti-plus"></i> Add Variant
                                                            </button>
                                                        </td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>

                                    {{-- Attributes Tab --}}
                                    <div class="tab-pane fade" id="attributes" role="tabpanel">
                                        {{-- Brand Section --}}
                                        <div class="card mb-3">
                                            <div class="card-header bg-light">
                                                <h6 class="mb-0"><i class="ti ti-tag"></i> Brand Information</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="brand_id" class="form-label">Brand</label>
                                                            <select class="form-select select2-single" id="brand_id"
                                                                name="brand_id">
                                                                <option value="">-- Select Brand --</option>
                                                                @foreach ($brands as $brand)
                                                                    <option value="{{ $brand->id }}"
                                                                        {{ $product->brand_id == $brand->id ? 'selected' : '' }}>
                                                                        {{ $brand->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <div class="invalid-feedback" id="brand_id-error"></div>
                                                            <small class="text-muted">Brands will appear based on selected
                                                                primary category</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Dynamic Attributes Section --}}
                                        <div class="card">
                                            <div class="card-header bg-light">
                                                <h6 class="mb-0"><i class="ti ti-list"></i> Product Attributes</h6>
                                            </div>
                                            <div class="card-body">
                                                <div id="attributes-container">
                                                    @if (count($existingAttributeValues) > 0)
                                                        <div class="row" id="attributes-view-mode">
                                                            @foreach ($existingAttributeValues as $attrId => $attrData)
                                                                <div class="col-md-6 mb-3">
                                                                    <label
                                                                        class="form-label fw-semibold">{{ $attrData['attribute']->name }}</label>
                                                                    <div class="form-control bg-light">
                                                                        @if ($attrData['attribute']->type == 'multiselect')
                                                                            @php
                                                                                $values = is_array($attrData['value'])
                                                                                    ? $attrData['value']
                                                                                    : json_decode(
                                                                                        $attrData['value'],
                                                                                        true,
                                                                                    );
                                                                            @endphp
                                                                            {{ is_array($values) ? implode(', ', $values) : $attrData['value'] }}
                                                                        @else
                                                                            {{ $attrData['value'] }}
                                                                        @endif
                                                                    </div>
                                                                    <input type="hidden"
                                                                        name="attributes[{{ $attrId }}]"
                                                                        value="{{ is_array($attrData['value']) ? json_encode($attrData['value']) : $attrData['value'] }}">
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                        <div class="mt-3">
                                                            <button type="button" class="btn btn-sm btn-primary"
                                                                id="editAttributesBtn">
                                                                <i class="ti ti-edit"></i> Edit Attributes
                                                            </button>
                                                        </div>
                                                        <div id="attributes-edit-mode" style="display: none;"
                                                            class="mt-3">
                                                        </div>
                                                    @else
                                                        <div class="alert alert-secondary">No attributes assigned to this
                                                            product. Select categories and click "Load Attributes" to add
                                                            attributes.</div>
                                                        <div id="attributes-edit-mode" style="display: none;"></div>
                                                        <button type="button" class="btn btn-sm btn-primary"
                                                            id="loadAttributesBtn">
                                                            <i class="ti ti-refresh"></i> Load Attributes
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Tier Pricing Tab --}}
                                    <div class="tab-pane fade" id="tiers" role="tabpanel">
                                        <div class="alert alert-info mb-3">
                                            <i class="ti ti-info-circle"></i> Quantity-based discounts. Leave blank if not
                                            needed.
                                        </div>
                                        <div class="table-responsive">
                                            <div id="tiers-container">
                                                <table class="table table-bordered table-striped" id="tiers-table">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th style="width: 25%">Min Quantity</th>
                                                            <th style="width: 25%">Max Quantity</th>
                                                            <th style="width: 25%">Price</th>
                                                            <th style="width: 10%">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="tiers-tbody">
                                                        @foreach ($product->tierPrices as $tier)
                                                            <tr class="tier-row existing-tier"
                                                                data-id="{{ $tier->id }}">
                                                                <td>
                                                                    <input type="number"
                                                                        class="form-control form-control-sm"
                                                                        name="existing_tiers[{{ $tier->id }}][min_quantity]"
                                                                        value="{{ $tier->min_quantity }}"
                                                                        placeholder="Min" step="1" min="1">
                                                                </td>
                                                                <td>
                                                                    <input type="number"
                                                                        class="form-control form-control-sm"
                                                                        name="existing_tiers[{{ $tier->id }}][max_quantity]"
                                                                        value="{{ $tier->max_quantity }}"
                                                                        placeholder="Max (leave empty for unlimited)"
                                                                        step="1">
                                                                </td>
                                                                <td>
                                                                    <div class="input-group input-group-sm">
                                                                        <span class="input-group-text">$</span>
                                                                        <input type="number" step="0.01"
                                                                            class="form-control form-control-sm"
                                                                            name="existing_tiers[{{ $tier->id }}][price]"
                                                                            value="{{ $tier->price }}"
                                                                            placeholder="Price">
                                                                    </div>
                                                                </td>
                                                                <td class="text-center">
                                                                    <button type="button"
                                                                        class="btn btn-sm btn-danger remove-existing-tier"
                                                                        data-id="{{ $tier->id }}">
                                                                        <i class="ti ti-trash"></i>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td colspan="4">
                                                                <button type="button" class="btn btn-sm btn-primary"
                                                                    id="add-tier">
                                                                    <i class="ti ti-plus"></i> Add Tier
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- SEO & Social Tab --}}
                                    <div class="tab-pane fade" id="seo" role="tabpanel">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="meta_title" class="form-label">Meta Title</label>
                                                    <input type="text" class="form-control" id="meta_title"
                                                        name="meta_title"
                                                        value="{{ old('meta_title', $product->meta_title) }}">
                                                    <div class="invalid-feedback" id="meta_title-error"></div>
                                                    <small class="text-muted"
                                                        id="metaTitleCount">{{ strlen($product->meta_title ?? '') }}/70
                                                        characters</small>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="meta_description" class="form-label">Meta
                                                        Description</label>
                                                    <textarea class="form-control" id="meta_description" name="meta_description" rows="2">{{ old('meta_description', $product->meta_description) }}</textarea>
                                                    <div class="invalid-feedback" id="meta_description-error"></div>
                                                    <small class="text-muted"
                                                        id="metaDescCount">{{ strlen($product->meta_description ?? '') }}/160
                                                        characters</small>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="meta_keywords" class="form-label">Meta Keywords</label>
                                                    <input type="text" class="form-control" id="meta_keywords"
                                                        name="meta_keywords"
                                                        value="{{ old('meta_keywords', $product->meta_keywords) }}">
                                                    <div class="invalid-feedback" id="meta_keywords-error"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="focus_keyword" class="form-label">Focus Keyword</label>
                                                    <input type="text" class="form-control" id="focus_keyword"
                                                        name="focus_keyword"
                                                        value="{{ old('focus_keyword', $product->focus_keyword) }}">
                                                    <div class="invalid-feedback" id="focus_keyword-error"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="canonical_url" class="form-label">Canonical URL</label>
                                                    <input type="text" class="form-control" id="canonical_url"
                                                        name="canonical_url"
                                                        value="{{ old('canonical_url', $product->canonical_url) }}">
                                                    <div class="invalid-feedback" id="canonical_url-error"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <hr>
                                                <h6>Open Graph</h6>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="og_title" class="form-label">OG Title</label>
                                                    <input type="text" class="form-control" id="og_title"
                                                        name="og_title"
                                                        value="{{ old('og_title', $product->og_title) }}">
                                                    <div class="invalid-feedback" id="og_title-error"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="og_image" class="form-label">OG Image</label>
                                                    <input type="file" class="form-control" id="og_image"
                                                        name="og_image" accept="image/*">
                                                    <div id="ogImagePreview" class="mt-2">
                                                        @if ($product->og_image)
                                                            <img src="{{ asset('storage/products/og/' . $product->og_image) }}"
                                                                class="img-fluid rounded border"
                                                                style="max-height: 100px;">
                                                        @endif
                                                    </div>
                                                    <div class="invalid-feedback" id="og_image-error"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="og_description" class="form-label">OG Description</label>
                                                    <textarea class="form-control" id="og_description" name="og_description" rows="2">{{ old('og_description', $product->og_description) }}</textarea>
                                                    <div class="invalid-feedback" id="og_description-error"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card border mt-3">
                                            <div class="card-header bg-light">
                                                <h6 class="mb-0">SEO Preview</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="text-primary fw-bold fs-5" id="seo-preview-title">
                                                    {{ $product->meta_title ?? $product->name }}
                                                </div>
                                                <div class="text-muted small" id="seo-preview-url">
                                                    {{ url('/product') }}/{{ $product->slug }}
                                                </div>
                                                <div class="text-muted small mt-2" id="seo-preview-desc">
                                                    {{ Str::limit($product->meta_description ?? ($product->short_description ?? ($product->description ?? 'Product description will appear here...')), 160) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2 mt-4">
                                    <a href="{{ route('vendor.products.index') }}" class="btn btn-danger">
                                        <i class="ti ti-x"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary" id="submitBtn">
                                        <i class="ti ti-edit"></i> Update Product
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2-single').select2({
                theme: 'bootstrap-5',
                width: '100%'
            });

            // Initialize Choices.js for tags
            const tagsSelect = document.getElementById('tags_select');
            if (tagsSelect) {
                new Choices(tagsSelect, {
                    removeItemButton: true,
                    removeItems: true,
                    duplicateItemsAllowed: false,
                    placeholder: true,
                    placeholderValue: 'Select tags',
                    searchEnabled: true,
                    searchChoices: true,
                    searchResultLimit: 20,
                    shouldSort: true,
                    itemSelectText: '',
                    addItems: true,
                });
            }

            let choicesInstances = {};
            let tempSelectedCategories = [];
            let existingAttributeValues = @json($existingAttributeValues);

            // Function to load brands based on selected primary category
            function loadBrandsByPrimaryCategory() {
                let primaryId = $('#primary_category_id').val();

                if (!primaryId) {
                    return;
                }

                let currentBrandId = $('#brand_id').val();

                $.ajax({
                    url: '{{ url('marketplace/brands/by-category') }}/' + primaryId,
                    type: 'GET',
                    success: function(response) {
                        if (response.success && response.brands.length > 0) {
                            let options = '<option value="">-- Select Brand --</option>';
                            response.brands.forEach(brand => {
                                let selected = (currentBrandId == brand.id) ? 'selected' : '';
                                options +=
                                    `<option value="${brand.id}" ${selected}>${brand.name} ${brand.code ? '(' + brand.code + ')' : ''}</option>`;
                            });
                            $('#brand_id').html(options);
                        } else {
                            $('#brand_id').html('<option value="">-- No brands available --</option>');
                        }
                        $('#brand_id').trigger('change');
                    },
                    error: function(xhr) {
                        console.error('Failed to load brands:', xhr);
                    }
                });
            }

            // ========== PRIMARY CATEGORY FUNCTIONS ==========
            $('#editPrimaryCategoryBtn').click(function() {
                $('#primary-category-view').hide();
                $('#primary-category-edit').show();
            });

            $('#cancelPrimaryCategoryBtn').click(function() {
                $('#primary-category-edit').hide();
                $('#primary-category-view').show();
            });

            $('#savePrimaryCategoryBtn').click(function() {
                let selectedId = $('#primary_category_id').val();
                let selectedName = $('#primary_category_id option:selected').text();

                if (selectedId) {
                    $('#primary-category-view').html(`
                        <div class="d-flex align-items-center">
                            <i class="ti ti-folder text-primary fs-4 me-2"></i>
                            <div>
                                <strong class="fs-5">${selectedName}</strong>
                            </div>
                        </div>
                    `);
                    // Load brands when primary category changes
                    loadBrandsByPrimaryCategory();
                }
                $('#primary-category-edit').hide();
                $('#primary-category-view').show();
            });

            // Load brands on page load based on existing primary category
            loadBrandsByPrimaryCategory();

            // ========== ADDITIONAL CATEGORIES FUNCTIONS ==========
            $('#editAdditionalCategoriesBtn').click(function() {
                let primaryId = $('#primary_category_id').val();
                if (!primaryId) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Warning!',
                        text: 'Please select a primary category first.',
                        confirmButtonColor: '#3085d6'
                    });
                    return;
                }

                tempSelectedCategories = [];
                $('#additional-categories-list .badge').each(function() {
                    let id = $(this).data('category-id');
                    if (id) tempSelectedCategories.push(parseInt(id));
                });

                $('#additional-categories-view').hide();
                $('#additional-categories-edit').show();

                loadCategorySelectionInterface(primaryId);
            });

            $('#cancelAdditionalCategoriesBtn').click(function() {
                $('#additional-categories-edit').hide();
                $('#additional-categories-view').show();
            });

            $('#saveAdditionalCategoriesBtn').click(function() {
                let allSelectedIds = [];
                for (let level in choicesInstances) {
                    if (choicesInstances[level]) {
                        let selectedValues = $(`.category-multi-select-edit[data-level="${level}"]`)
                            .val() || [];
                        allSelectedIds.push(...selectedValues);
                    }
                }

                allSelectedIds = [...new Set(allSelectedIds)];

                if (allSelectedIds.length > 0) {
                    $.ajax({
                        url: '{{ route('vendor.categories.get-names') }}',
                        type: 'POST',
                        data: {
                            ids: allSelectedIds,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            let html =
                                '<div class="d-flex flex-wrap gap-2" id="additional-categories-list">';
                            response.categories.forEach(cat => {
                                html += `<span class="badge bg-secondary fs-6 p-2" data-category-id="${cat.id}">
                                    <i class="ti ti-folder"></i> ${cat.name}
                                    <input type="hidden" name="additional_categories[]" value="${cat.id}">
                                </span>`;
                            });
                            html += '</div>';
                            $('#additional-categories-view').html(html);
                            $('#additional-categories-edit').hide();
                            $('#additional-categories-view').show();

                            Swal.fire({
                                icon: 'success',
                                title: 'Saved!',
                                text: 'Additional categories updated successfully.',
                                timer: 1500,
                                showConfirmButton: false
                            });
                        }
                    });
                } else {
                    $('#additional-categories-view').html(
                        '<div class="text-muted" id="additional-categories-empty">No additional categories selected</div>'
                    );
                    $('#additional-categories-edit').hide();
                    $('#additional-categories-view').show();
                    $('input[name="additional_categories[]"]').remove();
                }
            });

            function loadCategorySelectionInterface(primaryId) {
                $('#category-selection-container').html(
                    '<div class="text-center p-4"><div class="spinner-border text-primary"></div><p>Loading categories...</p></div>'
                );

                for (let level in choicesInstances) {
                    if (choicesInstances[level]) {
                        try {
                            choicesInstances[level].destroy();
                        } catch (e) {}
                    }
                }
                choicesInstances = {};

                loadSubcategoriesForEdit(primaryId, 1);
            }

            function loadSubcategoriesForEdit(parentId, level) {
                if (!parentId) return;

                $.ajax({
                    url: '{{ url('marketplace/categories') }}/' + parentId + '/subcategories',
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.subcategories && response.subcategories.length > 0) {
                            $(`.category-level-edit[data-level="${level}"]`).remove();

                            let options = '<option value="">-- Select --</option>';
                            response.subcategories.forEach(cat => {
                                let selected = tempSelectedCategories.includes(cat.id) ?
                                    'selected' : '';
                                options +=
                                    `<option value="${cat.id}" ${selected}>${cat.name}</option>`;
                            });

                            let levelHtml = `
                                <div class="category-level-edit mt-3 p-3 bg-light rounded border" data-level="${level}">
                                    <label class="form-label fw-semibold">Level ${level} Categories (Select Multiple)</label>
                                    <select class="form-control category-multi-select-edit" data-level="${level}" multiple style="width: 100%;">
                                        ${options}
                                    </select>
                                    <small class="text-muted mt-1">You can select multiple categories</small>
                                </div>
                            `;

                            if (level === 1) {
                                $('#category-selection-container').html(levelHtml);
                            } else {
                                $('#category-selection-container').append(levelHtml);
                            }

                            const selectElement = $(
                                `.category-multi-select-edit[data-level="${level}"]`)[0];
                            if (selectElement) {
                                let choices = new Choices(selectElement, {
                                    removeItemButton: true,
                                    removeItems: true,
                                    duplicateItemsAllowed: false,
                                    placeholder: true,
                                    placeholderValue: `Select level ${level} categories`,
                                    searchEnabled: true,
                                    searchChoices: true,
                                    searchResultLimit: 50,
                                    shouldSort: true,
                                    itemSelectText: '',
                                });
                                choicesInstances[level] = choices;

                                let preselected = tempSelectedCategories.filter(id =>
                                    response.subcategories.some(cat => cat.id == id)
                                );
                                if (preselected.length > 0) {
                                    choices.setValue(preselected.map(String));
                                }

                                $(selectElement).on('change', function() {
                                    let selectedValues = $(this).val() || [];
                                    tempSelectedCategories = [...new Set([...
                                        tempSelectedCategories, ...selectedValues
                                        .map(Number)
                                    ])];

                                    $(`.category-level-edit[data-level="${level + 1}"]`)
                                        .remove();
                                    if (choicesInstances[level + 1]) {
                                        try {
                                            choicesInstances[level + 1].destroy();
                                        } catch (e) {}
                                        delete choicesInstances[level + 1];
                                    }

                                    if (selectedValues && selectedValues.length === 1) {
                                        loadSubcategoriesForEdit(selectedValues[0], level + 1);
                                    }
                                });
                            }
                        } else if (level === 1) {
                            $('#category-selection-container').html(
                                '<div class="alert alert-info">No subcategories available for this primary category.</div>'
                            );
                        }
                    },
                    error: function(xhr) {
                        console.error('Failed to load subcategories:', xhr);
                        $('#category-selection-container').html(
                            '<div class="alert alert-danger">Failed to load categories.</div>');
                    }
                });
            }

            // ========== ATTRIBUTES FUNCTIONS ==========
            let originalAttributesHtml = '';

            function loadAttributesForEdit() {
                let categoryIds = [];
                let primaryId = $('#primary_category_id').val();

                if (primaryId) {
                    categoryIds.push(parseInt(primaryId));
                }

                $('#additional-categories-list .badge').each(function() {
                    let catId = $(this).data('category-id');
                    if (catId && !categoryIds.includes(parseInt(catId))) {
                        categoryIds.push(parseInt(catId));
                    }
                });

                if (categoryIds.length === 0) {
                    $('#attributes-edit-mode').html(
                        '<div class="alert alert-warning">Please select a primary category first.</div>');
                    return;
                }

                $('#attributes-edit-mode').html(
                    '<div class="text-center p-4"><div class="spinner-border text-primary"></div><p>Loading attributes...</p></div>'
                );
                $('#attributes-edit-mode').show();
                if ($('#attributes-view-mode').length) $('#attributes-view-mode').hide();

                $.ajax({
                    url: '{{ route('vendor.attributes.by-categories') }}',
                    type: 'POST',
                    data: {
                        category_ids: categoryIds,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.attributes && response.attributes.length > 0) {
                            renderAttributesForEdit(response.attributes);
                        } else {
                            $('#attributes-edit-mode').html(
                                '<div class="alert alert-info">No attributes found for the selected categories.</div>'
                            );
                        }
                    },
                    error: function(xhr) {
                        console.error('Failed to load attributes:', xhr);
                        $('#attributes-edit-mode').html(
                            '<div class="alert alert-danger">Failed to load attributes. Please try again.</div>'
                        );
                    }
                });
            }

            function renderAttributesForEdit(attributes) {
                if ($('#attributes-view-mode').length) {
                    originalAttributesHtml = $('#attributes-view-mode').html();
                }

                let existingValues = {};
                $('#attributes-view-mode input[type="hidden"]').each(function() {
                    let name = $(this).attr('name');
                    let match = name.match(/attributes\[(\d+)\]/);
                    if (match) {
                        existingValues[match[1]] = $(this).val();
                    }
                });

                let html =
                    '<div class="row"><div class="col-12"><h6 class="mb-3">Edit Product Attributes</h6><p class="text-muted small mb-3">Modify the attribute values below. These will be saved when you click "Update Product".</p></div>';

                attributes.forEach(attr => {
                    let existingValue = existingValues[attr.id] || '';

                    if (existingValue && (existingValue.startsWith('[') || existingValue.startsWith('{'))) {
                        try {
                            let parsed = JSON.parse(existingValue);
                            if (Array.isArray(parsed)) {
                                existingValue = parsed;
                            }
                        } catch (e) {}
                    }

                    html +=
                        `<div class="col-md-6 mb-3" data-attr-id="${attr.id}">
                        <label class="form-label fw-semibold">${escapeHtml(attr.name)} ${attr.is_required ? '<span class="text-danger">*</span>' : ''}</label>`;

                    if (attr.type === 'text' || attr.type === 'textarea') {
                        html +=
                            `<textarea class="form-control attribute-field" name="attributes[${attr.id}]" rows="3" placeholder="Enter ${escapeHtml(attr.name)}">${escapeHtml(existingValue)}</textarea>`;
                    } else if (attr.type === 'number') {
                        html +=
                            `<input type="number" step="any" class="form-control attribute-field" name="attributes[${attr.id}]" value="${escapeHtml(existingValue)}" placeholder="Enter ${escapeHtml(attr.name)}">`;
                    } else if (attr.type === 'select') {
                        html += `<select class="form-select attribute-field" name="attributes[${attr.id}]">
                            <option value="">-- Select ${escapeHtml(attr.name)} --</option>`;
                        if (attr.values && attr.values.length) {
                            attr.values.forEach(v => {
                                let selected = (existingValue == v.value) ? 'selected' : '';
                                html +=
                                    `<option value="${escapeHtml(v.value)}" ${selected}>${escapeHtml(v.display_name || v.label || v.value)}</option>`;
                            });
                        }
                        html += `</select>`;
                    } else if (attr.type === 'multiselect') {
                        let existingArray = Array.isArray(existingValue) ? existingValue : (existingValue ?
                            [existingValue] : []);
                        html +=
                            `<select class="form-select attribute-field" name="attributes[${attr.id}][]" multiple style="height: auto; min-height: 100px;">`;
                        if (attr.values && attr.values.length) {
                            attr.values.forEach(v => {
                                let selected = existingArray.includes(v.value) ? 'selected' : '';
                                html +=
                                    `<option value="${escapeHtml(v.value)}" ${selected}>${escapeHtml(v.display_name || v.label || v.value)}</option>`;
                            });
                        }
                        html += `</select>`;
                        html +=
                            `<small class="text-muted">Hold Ctrl/Cmd to select multiple options</small>`;
                    } else if (attr.type === 'checkbox') {
                        let existingArray = Array.isArray(existingValue) ? existingValue : (existingValue ?
                            [existingValue] : []);
                        if (attr.values && attr.values.length) {
                            html +=
                                `<div class="border p-2 rounded" style="max-height: 150px; overflow-y: auto;">`;
                            attr.values.forEach(v => {
                                let checked = existingArray.includes(v.value) ? 'checked' : '';
                                html += `<div class="form-check">
                                    <input type="checkbox" class="form-check-input attribute-field" name="attributes[${attr.id}][]" value="${escapeHtml(v.value)}" id="attr_${attr.id}_${v.value}" ${checked}>
                                    <label class="form-check-label" for="attr_${attr.id}_${v.value}">${escapeHtml(v.display_name || v.label || v.value)}</label>
                                </div>`;
                            });
                            html += `</div>`;
                        }
                    } else if (attr.type === 'radio') {
                        if (attr.values && attr.values.length) {
                            attr.values.forEach(v => {
                                let checked = (existingValue == v.value) ? 'checked' : '';
                                html += `<div class="form-check">
                                    <input type="radio" class="form-check-input attribute-field" name="attributes[${attr.id}]" value="${escapeHtml(v.value)}" id="attr_${attr.id}_${v.value}" ${checked}>
                                    <label class="form-check-label" for="attr_${attr.id}_${v.value}">${escapeHtml(v.display_name || v.label || v.value)}</label>
                                </div>`;
                            });
                        }
                    } else if (attr.type === 'color') {
                        html += `<div class="d-flex gap-2">
                            <input type="color" class="form-control attribute-field" style="width: 60px; height: 38px;" name="attributes[${attr.id}]" value="${escapeHtml(existingValue) || '#000000'}">
                            <input type="text" class="form-control" value="${escapeHtml(existingValue) || '#000000'}" onchange="this.previousElementSibling.value=this.value">
                        </div>`;
                    } else if (attr.type === 'date') {
                        html +=
                            `<input type="date" class="form-control attribute-field" name="attributes[${attr.id}]" value="${escapeHtml(existingValue)}">`;
                    } else {
                        html +=
                            `<input type="text" class="form-control attribute-field" name="attributes[${attr.id}]" value="${escapeHtml(existingValue)}" placeholder="Enter ${escapeHtml(attr.name)}">`;
                    }

                    if (attr.help_text) {
                        html +=
                            `<small class="text-muted d-block mt-1">${escapeHtml(attr.help_text)}</small>`;
                    }
                    html += `</div>`;
                });

                html += `<div class="col-12 mt-3">
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-success" id="saveAttributesBtn">
                            <i class="ti ti-check"></i> Save Changes
                        </button>
                        <button type="button" class="btn btn-secondary" id="cancelAttributesBtn">
                            <i class="ti ti-x"></i> Cancel
                        </button>
                    </div>
                    <p class="text-muted small mt-2"><i class="ti ti-info-circle"></i> Click "Save Changes" to apply your attribute changes. Then click "Update Product" to save everything.</p>
                </div>`;
                html += '</div>';

                $('#attributes-edit-mode').html(html);
            }

            $('#editAttributesBtn, #loadAttributesBtn').click(function() {
                let primaryId = $('#primary_category_id').val();
                if (!primaryId) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Warning!',
                        text: 'Please select a primary category first to load attributes.',
                        confirmButtonColor: '#3085d6'
                    });
                    return;
                }
                loadAttributesForEdit();
            });

            $(document).on('click', '#saveAttributesBtn', function() {
                let attributeValues = {};
                let attributeFields = $('#attributes-edit-mode .attribute-field');

                attributeFields.each(function() {
                    let name = $(this).attr('name');
                    let value = $(this).val();

                    if (name && name.startsWith('attributes[')) {
                        if ($(this).is('select[multiple]')) {
                            value = $(this).val() || [];
                        } else if ($(this).is(':checkbox') && !$(this).is(':checked')) {
                            return;
                        }
                        attributeValues[name] = value;
                    }
                });

                let viewModeHtml = '<div class="row" id="attributes-view-mode">';
                let attributeNames = {};

                $('#attributes-edit-mode .col-md-6').each(function() {
                    let label = $(this).find('.form-label').clone();
                    label.find('.text-danger').remove();
                    let attrName = label.text().trim();
                    let attrId = $(this).data('attr-id');
                    if (attrId) {
                        attributeNames[attrId] = attrName;
                    }
                });

                attributeFields.each(function() {
                    let name = $(this).attr('name');
                    let match = name.match(/attributes\[(\d+)\](.*)/);

                    if (match) {
                        let attrId = match[1];
                        let isArray = match[2] === '[]';
                        let value = $(this).val();

                        if (isArray && value && value.length) {
                            let displayValue = Array.isArray(value) ? value.join(', ') : value;
                            viewModeHtml += `
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">${escapeHtml(attributeNames[attrId] || 'Attribute')}</label>
                                    <div class="form-control bg-light">${escapeHtml(displayValue)}</div>
                                    <input type="hidden" name="attributes[${attrId}]" value='${JSON.stringify(value)}'>
                                </div>
                            `;
                        } else if (value && value !== '') {
                            viewModeHtml += `
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">${escapeHtml(attributeNames[attrId] || 'Attribute')}</label>
                                    <div class="form-control bg-light">${escapeHtml(value)}</div>
                                    <input type="hidden" name="attributes[${attrId}]" value="${escapeHtml(value)}">
                                </div>
                            `;
                        }
                    }
                });

                viewModeHtml +=
                    '</div><div class="mt-3"><button type="button" class="btn btn-sm btn-primary" id="editAttributesBtn"><i class="ti ti-edit"></i> Edit Attributes</button></div>';
                $('#attributes-container').html(viewModeHtml);
                $('#editAttributesBtn').click(function() {
                    loadAttributesForEdit();
                });

                Swal.fire({
                    icon: 'success',
                    title: 'Saved!',
                    text: 'Attribute changes have been applied. Click "Update Product" to save permanently.',
                    timer: 2000,
                    showConfirmButton: false
                });
            });

            $(document).on('click', '#cancelAttributesBtn', function() {
                if (originalAttributesHtml) {
                    $('#attributes-container').html(originalAttributesHtml);
                    $('#editAttributesBtn').click(function() {
                        loadAttributesForEdit();
                    });
                } else {
                    location.reload();
                }
            });

            function escapeHtml(str) {
                if (!str) return '';
                return String(str).replace(/[&<>]/g, function(m) {
                    if (m === '&') return '&amp;';
                    if (m === '<') return '&lt;';
                    if (m === '>') return '&gt;';
                    return m;
                });
            }

            // ========== OTHER FUNCTIONS ==========
            $('#add-highlight').click(function() {
                let newRow = `<div class="input-group mb-2 highlight-row">
                    <input type="text" class="form-control" name="highlights[]" placeholder="e.g., 16GB RAM">
                    <button type="button" class="btn btn-danger remove-highlight"><i class="ti ti-trash"></i></button>
                </div>`;
                $('#highlights-container').append(newRow);
            });

            $(document).on('click', '.remove-highlight', function() {
                $(this).closest('.highlight-row').remove();
            });

            let variantCounter = {{ $product->variants->count() }};
            $('#add-variant').click(function() {
                let colors = @json($colors);
                let sizes = @json($sizes);
                let colorOptions = '<option value="">-- Select --</option>' + colors.map(c =>
                    `<option value="${c.id}">${c.name}</option>`).join('');
                let sizeOptions = '<option value="">-- Select --</option>' + sizes.map(s =>
                    `<option value="${s.id}">${s.name}</option>`).join('');

                let newRow = `<tr class="variant-row">
                    <td><select class="form-select form-select-sm" name="new_variants[${variantCounter}][color_id]">${colorOptions}</select></td>
                    <td><select class="form-select form-select-sm" name="new_variants[${variantCounter}][size_id]">${sizeOptions}</select></td>
                    <td><input type="text" class="form-control form-control-sm" name="new_variants[${variantCounter}][sku]" placeholder="SKU"></td>
                    <td><input type="number" step="0.01" class="form-control form-control-sm" name="new_variants[${variantCounter}][price]" placeholder="Price"></td>
                    <td><input type="number" step="0.01" class="form-control form-control-sm" name="new_variants[${variantCounter}][compare_price]" placeholder="Compare price"></td>
                    <td><input type="number" step="0.01" class="form-control form-control-sm" name="new_variants[${variantCounter}][wholesale_price]" placeholder="Wholesale"></td>
                    <td><input type="number" class="form-control form-control-sm" name="new_variants[${variantCounter}][stock_quantity]" placeholder="Stock"></td>
                    <td><input type="file" class="form-control form-control-sm" name="new_variants[${variantCounter}][image]" accept="image/*"><div class="variant-image-preview mt-1"></div></td>
                    <td><input type="text" class="form-control form-control-sm" name="new_variants[${variantCounter}][image_alt]" placeholder="Alt text"></td>
                    <td><button type="button" class="btn btn-sm btn-danger remove-variant"><i class="ti ti-trash"></i></button></td>
                </tr>`;
                $('#variants-tbody').append(newRow);
                variantCounter++;
            });

            $(document).on('click', '.remove-variant', function() {
                $(this).closest('tr').remove();
            });

            $(document).on('click', '.remove-existing-variant', function() {
                let variantId = $(this).data('id');
                $(this).closest('tr').append(
                    `<input type="hidden" name="remove_variants[]" value="${variantId}">`);
                $(this).closest('tr').hide();
            });

            let tierCounter = {{ $product->tierPrices->count() }};
            $('#add-tier').click(function() {
                let newRow = `<tr class="tier-row">
                    <td><input type="number" class="form-control form-control-sm" name="new_tiers[${tierCounter}][min_quantity]" placeholder="Min"></td>
                    <td><input type="number" class="form-control form-control-sm" name="new_tiers[${tierCounter}][max_quantity]" placeholder="Max"></td>
                    <td><input type="number" step="0.01" class="form-control form-control-sm" name="new_tiers[${tierCounter}][price]" placeholder="Price"></td>
                    <td><button type="button" class="btn btn-sm btn-danger remove-tier"><i class="ti ti-trash"></i></button></td>
                </tr>`;
                $('#tiers-tbody').append(newRow);
                tierCounter++;
            });

            $(document).on('click', '.remove-tier', function() {
                $(this).closest('tr').remove();
            });

            $(document).on('click', '.remove-existing-tier', function() {
                let tierId = $(this).data('id');
                $(this).closest('tr').append(
                    `<input type="hidden" name="remove_tiers[]" value="${tierId}">`);
                $(this).closest('tr').hide();
            });

            let videoCount = {{ $product->videos->count() }};
            $('#add-video').click(function() {
                let newRow = `<div class="video-row row mb-2">
                    <div class="col-md-5"><input type="url" class="form-control" name="new_videos[${videoCount}][url]" placeholder="Video URL"></div>
                    <div class="col-md-5"><input type="text" class="form-control" name="new_videos[${videoCount}][title]" placeholder="Title"></div>
                    <div class="col-md-2"><button type="button" class="btn btn-danger remove-video"><i class="ti ti-trash"></i></button></div>
                </div>`;
                $('#videos-container').append(newRow);
                videoCount++;
            });

            $(document).on('click', '.remove-video', function() {
                $(this).closest('.video-row').remove();
            });

            $(document).on('click', '.remove-existing-video', function() {
                let videoId = $(this).data('id');
                $(this).closest('.video-row').append(
                    `<input type="hidden" name="remove_videos[]" value="${videoId}">`);
                $(this).closest('.video-row').hide();
            });

            let imageCounter = 0;
            let imageFiles = [];

            $('#images_input').on('change', function(e) {
                const files = Array.from(e.target.files);
                files.forEach((file) => {
                    if (file.type.startsWith('image/')) {
                        imageFiles.push(file);
                        const reader = new FileReader();
                        const currentIndex = imageCounter;
                        reader.onload = function(ev) {
                            const previewHtml = `<div class="col-md-3 new-image-item" data-index="${currentIndex}">
                                <div class="border rounded p-2 text-center">
                                    <img src="${ev.target.result}" class="img-fluid rounded mb-2" style="height: 120px; object-fit: cover;">
                                    <div class="form-check">
                                        <input type="radio" name="new_main_image_index" value="${currentIndex}" class="form-check-input new-main-image-radio">
                                        <label class="form-check-label small">Main Image</label>
                                    </div>
                                    <input type="text" name="new_images_alt[${currentIndex}]" class="form-control form-control-sm mt-2" placeholder="Alt text">
                                    <button type="button" class="btn btn-sm btn-danger mt-2 remove-new-image" data-index="${currentIndex}">Remove</button>
                                </div>
                            </div>`;
                            $('#images-preview-container').append(previewHtml);
                        };
                        reader.readAsDataURL(file);
                        imageCounter++;
                    }
                });
                $(this).val('');
            });

            $(document).on('click', '.remove-existing-image', function() {
                let id = $(this).data('id');
                $(this).closest('.existing-image-item').append(
                    `<input type="hidden" name="remove_images[]" value="${id}">`);
                $(this).closest('.existing-image-item').hide();
            });

            $(document).on('click', '.remove-new-image', function() {
                let index = $(this).data('index');
                $(this).closest('.new-image-item').remove();
                imageFiles[index] = null;
            });

            $('#og_image').on('change', function(e) {
                let file = e.target.files[0];
                if (file) {
                    let reader = new FileReader();
                    reader.onload = function(ev) {
                        $('#ogImagePreview').html(
                            `<img src="${ev.target.result}" class="img-fluid rounded border" style="max-height: 100px;">`
                        );
                    };
                    reader.readAsDataURL(file);
                }
            });

            $('#name').on('keyup', function() {
                let slug = $(this).val().toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
                if (slug) $('#slug-preview').text(slug);
                updateSEOPreview();
            });

            function updateSEOPreview() {
                let title = $('#meta_title').val() || $('#name').val() || 'Product Name';
                let desc = $('#meta_description').val() || $('#short_description').val() || $('#description')
                    .val() || 'Product description';
                let slug = $('#name').val().toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '') ||
                    'product-slug';
                $('#seo-preview-title').text(title.substring(0, 70));
                $('#seo-preview-url').text('{{ url('/') }}/product/' + slug);
                $('#seo-preview-desc').text(desc.substring(0, 160));
            }

            $('#meta_title').on('keyup', function() {
                let len = $(this).val().length;
                $('#metaTitleCount').text(len + '/70 characters');
                if (len > 70) $('#metaTitleCount').addClass('text-danger');
                else $('#metaTitleCount').removeClass('text-danger');
                updateSEOPreview();
            });

            $('#meta_description').on('keyup', function() {
                let len = $(this).val().length;
                $('#metaDescCount').text(len + '/160 characters');
                if (len > 160) $('#metaDescCount').addClass('text-danger');
                else $('#metaDescCount').removeClass('text-danger');
                updateSEOPreview();
            });

            // Form submission
            let formSubmitting = false;
            $('#productForm').on('submit', function(e) {
                e.preventDefault();
                if (formSubmitting) return;

                let isValid = true;
                if (!$('#name').val().trim()) {
                    $('#name').addClass('is-invalid');
                    $('#name-error').text('Product name is required');
                    isValid = false;
                }
                if (!$('#price').val()) {
                    $('#price').addClass('is-invalid');
                    $('#price-error').text('Price is required');
                    isValid = false;
                }
                if (!$('#primary_category_id').val()) {
                    $('#primary_category_id').addClass('is-invalid');
                    $('#primary_category_id-error').text('Primary category is required');
                    isValid = false;
                }

                if (!isValid) {
                    $('html, body').animate({
                        scrollTop: $('.is-invalid:first').offset().top - 100
                    }, 500);
                    return;
                }

                formSubmitting = true;
                let btn = $('#submitBtn');
                let originalText = btn.html();
                btn.html('<span class="spinner-border spinner-border-sm me-1"></span> Updating...');
                btn.prop('disabled', true);

                let formData = new FormData(this);

                imageFiles.forEach((file, index) => {
                    if (file) {
                        formData.append(`new_images[]`, file);
                        let altText = $(`input[name="new_images_alt[${index}]"]`).val();
                        if (altText) formData.append(`new_images_alt[]`, altText);
                        let isMain = $(`.new-main-image-radio[value="${index}"]`).is(':checked');
                        formData.append(`new_images_is_main[]`, isMain ? '1' : '0');
                    }
                });

                let commaTags = $('#tags_input').val().trim();
                if (commaTags) {
                    let newTags = commaTags.split(',').map(tag => tag.trim()).filter(tag => tag !== '');
                    newTags.forEach(tag => formData.append('new_tags[]', tag));
                }

                let selectedTagIds = $('#tags_select').val() || [];
                selectedTagIds.forEach(id => formData.append('tags[]', id));

                $.ajax({
                    url: '{{ route('vendor.products.update', $product->id) }}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.href =
                                    '{{ route('vendor.products.index') }}';
                            });
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function(field, msgs) {
                                if (field === 'tags' || field === 'tags_select' ||
                                    field === 'tags_select[]') {
                                    $('#tags_select').addClass('is-invalid');
                                    $('#tags-error').text(msgs[0]);
                                } else {
                                    let $field = $(`[name="${field}"]`);
                                    if ($field.length) {
                                        $field.addClass('is-invalid');
                                        let cleanField = field.replace(/[\[\]]/g, '');
                                        $(`#${cleanField}-error`).text(msgs[0]);
                                    }
                                }
                            });
                            $('html, body').animate({
                                scrollTop: $('.is-invalid:first').offset().top - 100
                            }, 500);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: xhr.responseJSON?.message ||
                                    'Something went wrong.',
                                confirmButtonColor: '#d33'
                            });
                        }
                    },
                    complete: function() {
                        formSubmitting = false;
                        btn.html(originalText);
                        btn.prop('disabled', false);
                    }
                });
            });

            updateSEOPreview();
        });
    </script>
@endpush

@push('styles')
    <style>
        .nav-tabs .nav-link {
            color: #6c757d;
        }

        .nav-tabs .nav-link.active {
            color: #0d6efd;
            border-bottom: 2px solid #0d6efd;
        }

        .invalid-feedback {
            display: block;
            font-size: 0.875em;
            margin-top: 0.25rem;
        }

        .badge {
            font-weight: normal;
        }

        .category-level-edit {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
        }

        .choices {
            margin-bottom: 0;
            width: 100%;
        }

        .choices__inner {
            border-radius: 0.375rem;
            min-height: 38px;
        }

        .choices__list--multiple .choices__item {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        .image-preview-item {
            transition: transform 0.2s;
        }

        .image-preview-item:hover {
            transform: translateY(-3px);
        }

        /* Fix for tier pricing table */
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        #tiers-table {
            min-width: 500px;
        }

        #tiers-table th,
        #tiers-table td {
            vertical-align: middle;
            white-space: nowrap;
        }

        /* Fix for form actions */
        .form-actions {
            position: sticky;
            bottom: 0;
            background: white;
            padding: 15px 0;
            margin-top: 20px;
            border-top: 1px solid #e9ecef;
            z-index: 10;
        }

        /* Ensure all tabs are visible */
        .tab-pane {
            overflow: visible;
        }

        /* Fix for SEO fields */
        #seo .form-control,
        #seo .form-select {
            margin-bottom: 10px;
        }

        /* Ensure buttons are visible */
        .d-flex.justify-content-end {
            margin-top: 20px !important;
            padding-top: 15px !important;
            border-top: 1px solid #e9ecef;
        }

        /* Fix for tier pricing input fields */
        #tiers-table input {
            width: 100%;
            min-width: 100px;
        }

        /* Ensure all content is visible */
        .card-body {
            overflow: visible;
        }

        /* Fix for Select2 dropdown */
        .select2-container {
            width: 100% !important;
        }

        /* Ensure tabs content doesn't overflow */
        .tab-content {
            overflow: visible;
        }
    </style>
@endpush
