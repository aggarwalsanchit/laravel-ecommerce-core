{{-- resources/views/admin/products/edit.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Edit Product')

@section('content')
<div class="page-content">
    <div class="page-container">
        <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
            <div class="flex-grow-1">
                <h4 class="fs-18 text-uppercase fw-bold mb-0">Edit Product: {{ $product->name }}</h4>
            </div>
            <div class="text-end">
                <ol class="breadcrumb m-0 py-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Products</a></li>
                    <li class="breadcrumb-item active">Edit Product</li>
                </ol>
            </div>
        </div>

        <form id="productForm" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-lg-8">
                    {{-- Basic Information --}}
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5>Basic Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Product Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="name" value="{{ old('name', $product->name) }}" required>
                                        <div class="invalid-feedback" id="name-error"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">SKU <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="sku" value="{{ old('sku', $product->sku) }}" required>
                                        <div class="invalid-feedback" id="sku-error"></div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">Short Description</label>
                                        <textarea class="form-control" name="short_description" rows="2">{{ old('short_description', $product->short_description) }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">Full Description</label>
                                        <textarea class="form-control" name="description" rows="5">{{ old('description', $product->description) }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Dynamic Categories Section --}}
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5>Categories</h5>
                            <p class="text-muted mb-0">Select main category, then subcategories will appear</p>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">Main Category <span class="text-danger">*</span></label>
                                        <select class="form-select" name="category_id" id="mainCategory" required>
                                            <option value="">Select Main Category</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            {{-- Dynamic Subcategories Container --}}
                            <div id="subcategoriesContainer"></div>
                            <div id="subSubcategoriesContainer"></div>
                        </div>
                    </div>

                    {{-- Dynamic Custom Attributes Container --}}
                    <div id="customAttributesContainer"></div>

                    {{-- Pricing --}}
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5>Pricing</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Pricing Type</label>
                                        <select class="form-select" name="pricing_type" id="pricingType">
                                            <option value="single" {{ $product->pricing_type == 'single' ? 'selected' : '' }}>Single Price</option>
                                            <option value="tiered" {{ $product->pricing_type == 'tiered' ? 'selected' : '' }}>Tiered Pricing (Quantity Based)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Regular Price <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="price" step="0.01" value="{{ old('price', $product->price) }}" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Sale Price</label>
                                        <input type="number" class="form-control" name="sale_price" step="0.01" value="{{ old('sale_price', $product->sale_price) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Sale Start Date</label>
                                        <input type="date" class="form-control" name="sale_start_date" value="{{ old('sale_start_date', $product->sale_start_date ? $product->sale_start_date->format('Y-m-d') : '') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Sale End Date</label>
                                        <input type="date" class="form-control" name="sale_end_date" value="{{ old('sale_end_date', $product->sale_end_date ? $product->sale_end_date->format('Y-m-d') : '') }}">
                                    </div>
                                </div>
                            </div>
                            
                            {{-- Tiered Pricing Section --}}
                            <div id="tieredPricingSection" style="display: {{ $product->pricing_type == 'tiered' ? 'block' : 'none' }};">
                                <hr>
                                <h6 class="mt-3">Tiered Pricing (Quantity Based)</h6>
                                <div id="tieredPricesContainer">
                                    @if($product->tierPrices->count() > 0)
                                        @foreach($product->tierPrices as $index => $tier)
                                        <div class="row tier-price-row mb-2">
                                            <div class="col-md-3">
                                                <input type="number" class="form-control" name="tier_prices[{{ $index }}][min_quantity]" value="{{ $tier->min_quantity }}" placeholder="Min Quantity" step="1">
                                            </div>
                                            <div class="col-md-3">
                                                <input type="number" class="form-control" name="tier_prices[{{ $index }}][max_quantity]" value="{{ $tier->max_quantity }}" placeholder="Max Quantity" step="1">
                                            </div>
                                            <div class="col-md-3">
                                                <input type="number" class="form-control" name="tier_prices[{{ $index }}][price]" value="{{ $tier->price }}" placeholder="Price" step="0.01">
                                            </div>
                                            <div class="col-md-3">
                                                <button type="button" class="btn btn-danger btn-sm remove-tier">Remove</button>
                                            </div>
                                        </div>
                                        @endforeach
                                    @else
                                        <div class="row tier-price-row mb-2">
                                            <div class="col-md-3">
                                                <input type="number" class="form-control" name="tier_prices[0][min_quantity]" placeholder="Min Quantity" step="1">
                                            </div>
                                            <div class="col-md-3">
                                                <input type="number" class="form-control" name="tier_prices[0][max_quantity]" placeholder="Max Quantity" step="1">
                                            </div>
                                            <div class="col-md-3">
                                                <input type="number" class="form-control" name="tier_prices[0][price]" placeholder="Price" step="0.01">
                                            </div>
                                            <div class="col-md-3">
                                                <button type="button" class="btn btn-danger btn-sm remove-tier">Remove</button>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="addTier">
                                    <i class="ti ti-plus"></i> Add Tier
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Colors & Sizes --}}
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5>Colors & Sizes</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Colors</label>
                                        <select class="form-select" name="colors[]" id="colors" multiple data-choices data-choices-removeItem>
                                            @foreach($colors as $color)
                                                <option value="{{ $color->id }}" {{ $product->colors->contains($color->id) ? 'selected' : '' }}>
                                                    {{ $color->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="text-muted">Select multiple colors for this product</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Sizes</label>
                                        <select class="form-select" name="sizes[]" id="sizes" multiple data-choices data-choices-removeItem>
                                            @foreach($sizes as $size)
                                                <option value="{{ $size->id }}" {{ $product->sizes->contains($size->id) ? 'selected' : '' }}>
                                                    {{ $size->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="text-muted">Select multiple sizes for this product</small>
                                    </div>
                                </div>
                            </div>
                            
                            {{-- Variants Section --}}
                            <div id="variantsSection" style="display: {{ ($product->colors->count() > 0 && $product->sizes->count() > 0) ? 'block' : 'none' }};">
                                <hr>
                                <h6 class="mt-3">Product Variants (Color + Size Combinations)</h6>
                                <div id="variantsContainer" class="table-responsive"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    {{-- Images --}}
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5>Product Images</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Featured Image</label>
                                <input type="file" class="form-control" name="featured_image" accept="image/*" id="featuredImage">
                                <div id="featuredImagePreview" class="mt-2 text-center">
                                    @if($product->featured_image)
                                        <img src="{{ Storage::disk('public')->url('products/' . $product->featured_image) }}" style="max-height: 100px;">
                                        <div class="form-check mt-2">
                                            <input type="checkbox" class="form-check-input" id="remove_featured_image" name="remove_featured_image" value="1">
                                            <label class="form-check-label text-danger" for="remove_featured_image">Remove current image</label>
                                        </div>
                                    @endif
                                </div>
                                <small class="text-muted">Recommended: 800x800px, Max 2MB</small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Gallery Images</label>
                                <input type="file" class="form-control" name="gallery_images[]" multiple accept="image/*" id="galleryImages">
                                <div id="galleryImagesPreview" class="mt-2 d-flex flex-wrap gap-2">
                                    @foreach($product->images as $image)
                                        <div class="position-relative">
                                            <img src="{{ $image->image_url }}" style="max-height: 80px; border-radius: 8px;">
                                            <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 remove-gallery-image" data-image-id="{{ $image->id }}" style="border-radius: 50%; padding: 2px 5px;">&times;</button>
                                        </div>
                                    @endforeach
                                </div>
                                <small class="text-muted">You can select multiple images. Recommended: 800x800px each</small>
                            </div>
                        </div>
                    </div>

                    {{-- Stock & Shipping --}}
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5>Stock & Shipping</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-check form-switch mb-3">
                                <input type="checkbox" class="form-check-input" id="track_stock" name="track_stock" value="1" {{ $product->track_stock ? 'checked' : '' }}>
                                <label class="form-check-label" for="track_stock">Track Stock</label>
                            </div>
                            <div class="mb-3" id="stockQuantityDiv" style="display: {{ $product->track_stock ? 'block' : 'none' }};">
                                <label class="form-label">Stock Quantity</label>
                                <input type="number" class="form-control" name="stock" value="{{ $product->stock }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Low Stock Threshold</label>
                                <input type="number" class="form-control" name="low_stock_threshold" value="{{ $product->low_stock_threshold }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Weight (kg)</label>
                                <input type="number" class="form-control" name="weight" step="0.01" value="{{ $product->weight }}">
                            </div>
                            <div class="row">
                                <div class="col-4">
                                    <label class="form-label">Length (cm)</label>
                                    <input type="number" class="form-control" name="length" step="0.01" value="{{ $product->length }}">
                                </div>
                                <div class="col-4">
                                    <label class="form-label">Width (cm)</label>
                                    <input type="number" class="form-control" name="width" step="0.01" value="{{ $product->width }}">
                                </div>
                                <div class="col-4">
                                    <label class="form-label">Height (cm)</label>
                                    <input type="number" class="form-control" name="height" step="0.01" value="{{ $product->height }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5>Product Status</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-check form-switch mb-2">
                                <input type="checkbox" class="form-check-input" id="status" name="status" value="1" {{ $product->status ? 'checked' : '' }}>
                                <label class="form-check-label" for="status">Active</label>
                            </div>
                            <div class="form-check form-switch mb-2">
                                <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured" value="1" {{ $product->is_featured ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_featured">Featured Product</label>
                            </div>
                            <div class="form-check form-switch mb-2">
                                <input type="checkbox" class="form-check-input" id="is_new" name="is_new" value="1" {{ $product->is_new ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_new">New Arrival</label>
                            </div>
                        </div>
                    </div>

                    {{-- SEO --}}
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5>SEO Settings</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Meta Title</label>
                                <input type="text" class="form-control" name="meta_title" value="{{ $product->meta_title }}" maxlength="70">
                                <small class="text-muted" id="metaTitleCount">{{ strlen($product->meta_title) }}/70 characters</small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Meta Description</label>
                                <textarea class="form-control" name="meta_description" rows="2" maxlength="160">{{ $product->meta_description }}</textarea>
                                <small class="text-muted" id="metaDescCount">{{ strlen($product->meta_description) }}/160 characters</small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Meta Keywords</label>
                                <input type="text" class="form-control" name="meta_keywords" value="{{ $product->meta_keywords }}" placeholder="keyword1, keyword2, keyword3">
                                <small class="text-muted">Comma separated keywords</small>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-footer text-end">
                            <a href="{{ route('admin.products.index') }}" class="btn btn-danger">Cancel</a>
                            <button type="submit" class="btn btn-primary" id="submitBtn">Update Product</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    let selectedCategories = [];
    let currentColors = {!! json_encode($product->colors->pluck('id')->toArray()) !!};
    let currentSizes = {!! json_encode($product->sizes->pluck('id')->toArray()) !!};
    
    // Initialize Choices.js
    if (typeof Choices !== 'undefined') {
        const choicesElements = document.querySelectorAll('[data-choices]');
        choicesElements.forEach(function(element) {
            new Choices(element, {
                removeItemButton: true,
                placeholderValue: 'Select options...',
                searchEnabled: true,
                searchChoices: true,
                shouldSort: false,
            });
        });
    }
    
    // Load current categories and attributes
    let currentCategoryId = $('#mainCategory').val();
    if (currentCategoryId) {
        loadCurrentSubcategories(currentCategoryId);
        loadAttributesForCategory(currentCategoryId, 'main');
    }
    
    function loadCurrentSubcategories(categoryId) {
        $.ajax({
            url: '/admin/categories/' + categoryId + '/subcategories',
            type: 'GET',
            success: function(response) {
                if (response.subcategories && response.subcategories.length > 0) {
                    let selectedSubIds = {!! json_encode($product->subcategories->pluck('id')->toArray()) !!};
                    let html = `
                        <div class="row mt-3" id="level-0-container">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">Subcategories</label>
                                    <select class="form-select" name="subcategories[]" id="subcategory-level-0" multiple data-choices data-choices-removeItem>
                    `;
                    response.subcategories.forEach(function(subcategory) {
                        let selected = selectedSubIds.includes(subcategory.id) ? 'selected' : '';
                        html += `<option value="${subcategory.id}" data-name="${subcategory.name}" ${selected}>${subcategory.name}</option>`;
                    });
                    html += `
                                    </select>
                                    <small class="text-muted">You can select multiple subcategories</small>
                                </div>
                            </div>
                        </div>
                    `;
                    $('#subcategoriesContainer').html(html);
                    
                    if (typeof Choices !== 'undefined') {
                        new Choices(document.getElementById('subcategory-level-0'), {
                            removeItemButton: true,
                            placeholderValue: 'Select subcategories...',
                            searchEnabled: true,
                            searchChoices: true,
                            shouldSort: false,
                        });
                    }
                    
                    if (selectedSubIds.length > 0) {
                        selectedSubIds.forEach(function(subId) {
                            loadAttributesForCategory(subId, 'sub_0');
                        });
                    }
                }
            }
        });
    }
    
    // Main Category Change
    $('#mainCategory').on('change', function() {
        let categoryId = $(this).val();
        selectedCategories = [];
        
        if (categoryId) {
            selectedCategories.push({ level: 'main', id: categoryId });
            loadAttributesForCategory(categoryId, 'main');
            loadSubcategories(categoryId, 0, '#subcategoriesContainer', 'subcategories[]', 'Subcategories');
            $('#subSubcategoriesContainer').empty();
        } else {
            $('#subcategoriesContainer').empty();
            $('#subSubcategoriesContainer').empty();
            $('#customAttributesContainer').empty();
        }
    });
    
    function loadSubcategories(parentId, level, containerId, inputName, labelText) {
        $.ajax({
            url: '/admin/categories/' + parentId + '/subcategories',
            type: 'GET',
            success: function(response) {
                if (response.subcategories && response.subcategories.length > 0) {
                    let html = `
                        <div class="row mt-3" id="level-${level}-container">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">${labelText}</label>
                                    <select class="form-select" name="${inputName}" id="subcategory-level-${level}" multiple data-choices data-choices-removeItem>
                    `;
                    response.subcategories.forEach(function(subcategory) {
                        html += `<option value="${subcategory.id}" data-name="${subcategory.name}">${subcategory.name}</option>`;
                    });
                    html += `
                                    </select>
                                    <small class="text-muted">You can select multiple ${labelText.toLowerCase()}</small>
                                </div>
                            </div>
                        </div>
                    `;
                    $(containerId).html(html);
                    
                    if (typeof Choices !== 'undefined') {
                        new Choices(document.getElementById(`subcategory-level-${level}`), {
                            removeItemButton: true,
                            placeholderValue: `Select ${labelText.toLowerCase()}...`,
                            searchEnabled: true,
                            searchChoices: true,
                            shouldSort: false,
                        });
                    }
                    
                    $(`#subcategory-level-${level}`).off('change').on('change', function() {
                        let selectedSubcategories = $(this).val();
                        removeAttributesForLevelAndBelow(`sub_${level}`);
                        if (selectedSubcategories && selectedSubcategories.length > 0) {
                            selectedSubcategories.forEach(function(subId) {
                                loadAttributesForCategory(subId, `sub_${level}`);
                            });
                            if (selectedSubcategories.length > 0) {
                                loadSubSubcategories(selectedSubcategories[0], level + 1);
                            }
                        }
                    });
                }
            }
        });
    }
    
    function loadSubSubcategories(parentId, level) {
        $.ajax({
            url: '/admin/categories/' + parentId + '/subcategories',
            type: 'GET',
            success: function(response) {
                if (response.subcategories && response.subcategories.length > 0) {
                    $(`#level-${level}-container`).remove();
                    let html = `
                        <div class="row mt-3" id="level-${level}-container">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">Sub-Subcategories</label>
                                    <select class="form-select" name="sub_subcategories[]" id="subcategory-level-${level}" multiple data-choices data-choices-removeItem>
                    `;
                    response.subcategories.forEach(function(subcategory) {
                        html += `<option value="${subcategory.id}" data-name="${subcategory.name}">${subcategory.name}</option>`;
                    });
                    html += `
                                    </select>
                                    <small class="text-muted">You can select multiple sub-subcategories</small>
                                </div>
                            </div>
                        </div>
                    `;
                    $('#subSubcategoriesContainer').append(html);
                    
                    if (typeof Choices !== 'undefined') {
                        new Choices(document.getElementById(`subcategory-level-${level}`), {
                            removeItemButton: true,
                            placeholderValue: 'Select sub-subcategories...',
                            searchEnabled: true,
                            searchChoices: true,
                            shouldSort: false,
                        });
                    }
                    
                    $(`#subcategory-level-${level}`).off('change').on('change', function() {
                        let selectedSubSubs = $(this).val();
                        removeAttributesForLevelAndBelow(`sub_sub_${level}`);
                        if (selectedSubSubs && selectedSubSubs.length > 0) {
                            selectedSubSubs.forEach(function(subSubId) {
                                loadAttributesForCategory(subSubId, `sub_sub_${level}`);
                            });
                        }
                    });
                }
            }
        });
    }
    
    let existingCustomAttributes = {!! json_encode($existingCustomAttributes ?? []) !!};
    
    function loadAttributesForCategory(categoryId, level) {
        $.ajax({
            url: '/admin/attributes/by-category/' + categoryId,
            type: 'GET',
            success: function(response) {
                if (response.attributes && response.attributes.length > 0) {
                    let containerId = `#attributes-level-${level}`;
                    let levelName = getLevelName(level);
                    
                    if ($(`#attributes-level-${level}-card`).length === 0) {
                        let newSection = `
                            <div class="card mb-3" id="attributes-level-${level}-card">
                                <div class="card-header">
                                    <h5>${levelName} Attributes</h5>
                                </div>
                                <div class="card-body" id="attributes-level-${level}">
                                </div>
                            </div>
                        `;
                        $('#customAttributesContainer').append(newSection);
                    }
                    
                    let html = '';
                    response.attributes.forEach(function(attribute) {
                        let existingValues = existingCustomAttributes[attribute.id] || [];
                        html += `
                            <div class="mb-4">
                                <label class="form-label fw-semibold">${attribute.name}</label>
                                ${attribute.unit ? `<small class="text-muted">(${attribute.unit})</small>` : ''}
                        `;
                        
                        if (attribute.type === 'select' || attribute.type === 'multiselect') {
                            html += `
                                <select class="form-select" name="custom_attributes[${attribute.id}][]" 
                                    ${attribute.type === 'multiselect' ? 'multiple data-choices data-choices-removeItem' : ''}>
                                    <option value="">Select ${attribute.name}</option>
                            `;
                            attribute.values.forEach(function(value) {
                                let selected = existingValues.includes(value.id) ? 'selected' : '';
                                html += `<option value="${value.id}" ${selected}>${value.value}</option>`;
                            });
                            html += `</select>`;
                        } else if (attribute.type === 'color') {
                            html += `<div class="d-flex flex-wrap gap-2">`;
                            attribute.values.forEach(function(value) {
                                let checked = existingValues.includes(value.id) ? 'checked' : '';
                                html += `
                                    <label class="color-option">
                                        <input type="radio" name="custom_attributes[${attribute.id}]" value="${value.id}" style="display: none;" ${checked}>
                                        <div style="width: 40px; height: 40px; background: ${value.color_code}; border-radius: 8px; border: 2px solid ${checked ? '#0d6efd' : '#dee2e6'}; cursor: pointer;"></div>
                                        <span class="d-block text-center small">${value.value}</span>
                                    </label>
                                `;
                            });
                            html += `</div>`;
                        } else if (attribute.type === 'size') {
                            html += `<div class="d-flex flex-wrap gap-2">`;
                            attribute.values.forEach(function(value) {
                                let checked = existingValues.includes(value.id) ? 'checked' : '';
                                html += `
                                    <label class="btn btn-outline-secondary size-option ${checked ? 'active btn-primary' : ''}">
                                        <input type="checkbox" name="custom_attributes[${attribute.id}][]" value="${value.id}" style="display: none;" ${checked}>
                                        ${value.value}
                                    </label>
                                `;
                            });
                            html += `</div>`;
                        } else if (attribute.type === 'checkbox') {
                            let checked = existingValues.includes(1) ? 'checked' : '';
                            html += `
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="custom_attributes[${attribute.id}]" value="1" ${checked}>
                                    <label class="form-check-label">Yes</label>
                                </div>
                            `;
                        } else if (attribute.type === 'boolean') {
                            let checkedYes = existingValues.includes(1) ? 'checked' : '';
                            let checkedNo = existingValues.includes(0) ? 'checked' : '';
                            html += `
                                <div class="d-flex gap-3">
                                    <div class="form-check">
                                        <input type="radio" class="form-check-input" name="custom_attributes[${attribute.id}]" value="1" ${checkedYes}>
                                        <label class="form-check-label">Yes</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="radio" class="form-check-input" name="custom_attributes[${attribute.id}]" value="0" ${checkedNo}>
                                        <label class="form-check-label">No</label>
                                    </div>
                                </div>
                            `;
                        } else if (attribute.type === 'textarea') {
                            let value = existingValues[0] || '';
                            html += `<textarea class="form-control" name="custom_attributes[${attribute.id}]" rows="2">${value}</textarea>`;
                        } else {
                            let value = existingValues[0] || '';
                            html += `<input type="${attribute.type}" class="form-control" name="custom_attributes[${attribute.id}]" value="${value}">`;
                        }
                        
                        html += `</div>`;
                    });
                    
                    $(containerId).html(html);
                    $('#customAttributesContainer').show();
                    
                    if (typeof Choices !== 'undefined') {
                        document.querySelectorAll(`#attributes-level-${level} select[multiple]`).forEach(function(element) {
                            new Choices(element, {
                                removeItemButton: true,
                                placeholderValue: 'Select options...',
                                searchEnabled: true,
                                searchChoices: true,
                                shouldSort: false,
                            });
                        });
                    }
                    
                    $('.color-option').off('click').on('click', function() {
                        $(this).find('input').prop('checked', true);
                        $(this).siblings().find('div').css('border', '2px solid #dee2e6');
                        $(this).find('div').css('border', '2px solid #0d6efd');
                    });
                    
                    $('.size-option').off('click').on('click', function() {
                        let checkbox = $(this).find('input');
                        checkbox.prop('checked', !checkbox.prop('checked'));
                        $(this).toggleClass('active btn-primary');
                    });
                }
            }
        });
    }
    
    function getLevelName(level) {
        if (level === 'main') return 'Main Category';
        if (level.toString().startsWith('sub_')) return 'Subcategory';
        if (level.toString().startsWith('sub_sub_')) return 'Sub-Subcategory';
        return 'Category';
    }
    
    function removeAttributesForLevelAndBelow(level) {
        $(`#attributes-level-${level}-card`).remove();
    }
    
    // ========== FIXED VARIANTS GENERATION ==========
    function generateVariantsTable(colors, sizes) {
        if (!colors || !sizes || colors.length === 0 || sizes.length === 0) {
            $('#variantsContainer').empty();
            return;
        }
        
        // Get existing variants from the product
        let existingVariants = {};
        @if($product->variants->count() > 0)
            existingVariants = {!! json_encode($product->variants->map(function($v) {
                return [
                    'key' => $v->color_id . '_' . $v->size_id,
                    'color_id' => $v->color_id,
                    'size_id' => $v->size_id,
                    'sku' => $v->sku,
                    'price' => $v->price,
                    'sale_price' => $v->sale_price,
                    'stock' => $v->stock
                ];
            })->keyBy('key')) !!};
        @endif
        
        let html = `
            <table class="table table-bordered table-sm">
                <thead class="table-light">
                    32
                        <th style="width: 15%">Color</th>
                        <th style="width: 15%">Size</th>
                        <th style="width: 25%">SKU</th>
                        <th style="width: 15%">Price</th>
                        <th style="width: 15%">Sale Price</th>
                        <th style="width: 15%">Stock</th>
                    </thead>
                    <tbody>
        `;
        
        let variantIndex = 0;
        
        for (let colorId of colors) {
            let colorName = $('#colors option[value="' + colorId + '"]').text();
            for (let sizeId of sizes) {
                let sizeName = $('#sizes option[value="' + sizeId + '"]').text();
                let key = colorId + '_' + sizeId;
                let existing = existingVariants[key] || {};
                
                html += `
                    <tr>
                        <td>
                            ${colorName}
                            <input type="hidden" name="variants[${variantIndex}][color_id]" value="${colorId}">
                        </td>
                        <td>
                            ${sizeName}
                            <input type="hidden" name="variants[${variantIndex}][size_id]" value="${sizeId}">
                        </td>
                        <td>
                            <input type="text" class="form-control form-control-sm" name="variants[${variantIndex}][sku]" placeholder="SKU" value="${existing.sku || ''}">
                        </td>
                        <td>
                            <input type="number" class="form-control form-control-sm" name="variants[${variantIndex}][price]" step="0.01" placeholder="Price" value="${existing.price || ''}">
                        </td>
                        <td>
                            <input type="number" class="form-control form-control-sm" name="variants[${variantIndex}][sale_price]" step="0.01" placeholder="Sale Price" value="${existing.sale_price || ''}">
                        </td>
                        <td>
                            <input type="number" class="form-control form-control-sm" name="variants[${variantIndex}][stock]" placeholder="Stock" value="${existing.stock || 0}">
                        </td>
                    `
                variantIndex++;
            }
        }
        
        html += `
                    </tbody>
                </table>
        `;
        
        $('#variantsContainer').html(html);
    }
    
    // Generate variants when colors or sizes change
    $('#colors, #sizes').on('change', function() {
        let colors = $('#colors').val();
        let sizes = $('#sizes').val();
        
        if (colors && colors.length > 0 && sizes && sizes.length > 0) {
            generateVariantsTable(colors, sizes);
            $('#variantsSection').slideDown();
        } else {
            $('#variantsSection').slideUp();
            $('#variantsContainer').empty();
        }
    });
    
    // Initial variants generation
    if (currentColors.length > 0 && currentSizes.length > 0) {
        generateVariantsTable(currentColors, currentSizes);
    }
    
    // Pricing type toggle
    $('#pricingType').on('change', function() {
        if ($(this).val() === 'tiered') {
            $('#tieredPricingSection').slideDown();
        } else {
            $('#tieredPricingSection').slideUp();
        }
    });
    
    // Track stock toggle
    $('#track_stock').on('change', function() {
        if ($(this).is(':checked')) {
            $('#stockQuantityDiv').slideDown();
        } else {
            $('#stockQuantityDiv').slideUp();
        }
    });
    
    // Add tiered price row
    let tierIndex = $('#tieredPricesContainer .tier-price-row').length;
    $('#addTier').on('click', function() {
        let newRow = `
            <div class="row tier-price-row mb-2">
                <div class="col-md-3">
                    <input type="number" class="form-control" name="tier_prices[${tierIndex}][min_quantity]" placeholder="Min Quantity" step="1">
                </div>
                <div class="col-md-3">
                    <input type="number" class="form-control" name="tier_prices[${tierIndex}][max_quantity]" placeholder="Max Quantity" step="1">
                </div>
                <div class="col-md-3">
                    <input type="number" class="form-control" name="tier_prices[${tierIndex}][price]" placeholder="Price" step="0.01">
                </div>
                <div class="col-md-3">
                    <button type="button" class="btn btn-danger btn-sm remove-tier">Remove</button>
                </div>
            </div>
        `;
        $('#tieredPricesContainer').append(newRow);
        tierIndex++;
    });
    
    $(document).on('click', '.remove-tier', function() {
        $(this).closest('.tier-price-row').remove();
    });
    
    // Remove gallery image
    $(document).on('click', '.remove-gallery-image', function() {
        let imageId = $(this).data('image-id');
        $(this).closest('.position-relative').remove();
        $('<input>').attr({
            type: 'hidden',
            name: 'remove_gallery_images[]',
            value: imageId
        }).appendTo('#productForm');
    });
    
    // Image preview
    $('#featuredImage').on('change', function(e) {
        previewImage(e.target.files[0], '#featuredImagePreview');
    });
    
    $('#galleryImages').on('change', function(e) {
        for (let file of e.target.files) {
            previewImage(file, '#galleryImagesPreview', true);
        }
    });
    
    function previewImage(file, container, isGallery = false) {
        if (file) {
            let reader = new FileReader();
            reader.onload = function(e) {
                let img = `<img src="${e.target.result}" style="max-height: 100px; width: auto; border-radius: 8px; border: 1px solid #dee2e6; padding: 5px;">`;
                if (isGallery) {
                    $(container).append(img);
                } else {
                    $(container).html(img);
                }
            };
            reader.readAsDataURL(file);
        }
    }
    
    // Character counters
    $('[name="meta_title"]').on('keyup', function() {
        let length = $(this).val().length;
        $('#metaTitleCount').text(length + '/70 characters');
    });
    
    $('[name="meta_description"]').on('keyup', function() {
        let length = $(this).val().length;
        $('#metaDescCount').text(length + '/160 characters');
    });
    
    // Form submission
    $('#productForm').on('submit', function(e) {
        e.preventDefault();
        let btn = $('#submitBtn');
        btn.html('<span class="spinner-border spinner-border-sm me-1"></span> Updating...').prop('disabled', true);
        
        let formData = new FormData(this);
        
        $.ajax({
            url: '{{ route("admin.products.update", $product->id) }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Updated!',
                        text: response.message,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = '{{ route("admin.products.index") }}';
                    });
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    $.each(errors, function(field, messages) {
                        $('[name="' + field + '"]').addClass('is-invalid');
                        $('#' + field + '-error').text(messages[0]);
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: xhr.responseJSON?.message || 'Something went wrong.'
                    });
                }
                btn.html('Update Product').prop('disabled', false);
            }
        });
    });
});
</script>
@endpush

@push('styles')
<style>
.color-option {
    cursor: pointer;
    text-align: center;
    transition: transform 0.2s;
}
.color-option:hover {
    transform: scale(1.05);
}
.size-option {
    cursor: pointer;
    transition: all 0.2s;
}
.size-option.active {
    background-color: #0d6efd;
    color: white;
    border-color: #0d6efd;
}
</style>
@endpush