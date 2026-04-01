{{-- resources/views/admin/products/create.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Create Product')

@section('content')
<div class="page-content">
    <div class="page-container">
        <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
            <div class="flex-grow-1">
                <h4 class="fs-18 text-uppercase fw-bold mb-0">Create New Product</h4>
            </div>
            <div class="text-end">
                <ol class="breadcrumb m-0 py-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Products</a></li>
                    <li class="breadcrumb-item active">Create Product</li>
                </ol>
            </div>
        </div>

        <form id="productForm" enctype="multipart/form-data">
            @csrf
            
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
                                        <input type="text" class="form-control" name="name" required>
                                        <div class="invalid-feedback" id="name-error"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">SKU <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="sku" required>
                                        <div class="invalid-feedback" id="sku-error"></div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">Short Description</label>
                                        <textarea class="form-control" name="short_description" rows="2"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">Full Description</label>
                                        <div id="fullDescriptionEditor" style="height: 300px;"></div>
                                        <textarea name="description" id="description" style="display: none;"></textarea>
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
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
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
                                            <option value="single">Single Price</option>
                                            <option value="tiered">Tiered Pricing (Quantity Based)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Regular Price <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="price" step="0.01" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Sale Price</label>
                                        <input type="number" class="form-control" name="sale_price" step="0.01">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Sale Start Date</label>
                                        <input type="date" class="form-control" name="sale_start_date">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Sale End Date</label>
                                        <input type="date" class="form-control" name="sale_end_date">
                                    </div>
                                </div>
                            </div>
                            
                            {{-- Tiered Pricing Section --}}
                            <div id="tieredPricingSection" style="display: none;">
                                <hr>
                                <h6 class="mt-3">Tiered Pricing (Quantity Based)</h6>
                                <div id="tieredPricesContainer">
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
                                                <option value="{{ $color->id }}">{{ $color->name }}</option>
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
                                                <option value="{{ $size->id }}">{{ $size->name }}</option>
                                            @endforeach
                                        </select>
                                        <small class="text-muted">Select multiple sizes for this product</small>
                                    </div>
                                </div>
                            </div>
                            
                            {{-- Variants Section --}}
                            <div id="variantsSection" style="display: none;">
                                <hr>
                                <h6 class="mt-3">Product Variants (Color + Size Combinations)</h6>
                                <div id="variantsContainer" class="table-responsive"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    {{-- Images Section --}}
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5>Product Images</h5>
                            <p class="text-muted mb-0">Upload multiple images. Click the star to set featured image.</p>
                        </div>
                        <div class="card-body">
                            {{-- Image Upload Area --}}
                            <div class="mb-3">
                                <label class="form-label">Upload Images</label>
                                <input type="file" class="form-control" id="productImages" name="product_images[]" multiple accept="image/*">
                                <small class="text-muted">You can select multiple images. First image will be featured by default.</small>
                            </div>
                            
                            {{-- Image Gallery Preview (Vertical Layout with Cross on Top Left) --}}
                            <div id="imageGallery" class="mt-3"></div>
                            
                            {{-- Hidden input to track featured image index --}}
                            <input type="hidden" name="featured_image_index" id="featuredImageIndex" value="0">
                        </div>
                    </div>

                    {{-- Stock & Shipping --}}
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5>Stock & Shipping</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-check form-switch mb-3">
                                <input type="checkbox" class="form-check-input" id="track_stock" name="track_stock" value="1" checked>
                                <label class="form-check-label" for="track_stock">Track Stock</label>
                            </div>
                            <div class="mb-3" id="stockQuantityDiv">
                                <label class="form-label">Stock Quantity</label>
                                <input type="number" class="form-control" name="stock" value="0">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Low Stock Threshold</label>
                                <input type="number" class="form-control" name="low_stock_threshold" value="5">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Weight (kg)</label>
                                <input type="number" class="form-control" name="weight" step="0.01">
                            </div>
                            <div class="row">
                                <div class="col-4">
                                    <label class="form-label">Length (cm)</label>
                                    <input type="number" class="form-control" name="length" step="0.01">
                                </div>
                                <div class="col-4">
                                    <label class="form-label">Width (cm)</label>
                                    <input type="number" class="form-control" name="width" step="0.01">
                                </div>
                                <div class="col-4">
                                    <label class="form-label">Height (cm)</label>
                                    <input type="number" class="form-control" name="height" step="0.01">
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
                                <input type="checkbox" class="form-check-input" id="status" name="status" value="1" checked>
                                <label class="form-check-label" for="status">Active</label>
                            </div>
                            <div class="form-check form-switch mb-2">
                                <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured" value="1">
                                <label class="form-check-label" for="is_featured">Featured Product</label>
                            </div>
                            <div class="form-check form-switch mb-2">
                                <input type="checkbox" class="form-check-input" id="is_new" name="is_new" value="1">
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
                                <input type="text" class="form-control" name="meta_title" maxlength="70">
                                <small class="text-muted" id="metaTitleCount">0/70 characters</small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Meta Description</label>
                                <textarea class="form-control" name="meta_description" rows="2" maxlength="160"></textarea>
                                <small class="text-muted" id="metaDescCount">0/160 characters</small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Meta Keywords</label>
                                <input type="text" class="form-control" name="meta_keywords" placeholder="keyword1, keyword2, keyword3">
                                <small class="text-muted">Comma separated keywords</small>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-footer text-end">
                            <a href="{{ route('admin.products.index') }}" class="btn btn-danger">Cancel</a>
                            <button type="submit" class="btn btn-primary" id="submitBtn">Create Product</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<!-- Include Quill Editor for Rich Text -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    // ========== RICH TEXT EDITOR ==========
    var quill = new Quill('#fullDescriptionEditor', {
        theme: 'snow',
        placeholder: 'Write a detailed product description...',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                ['blockquote', 'code-block'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'indent': '-1'}, { 'indent': '+1' }],
                [{ 'align': [] }],
                ['link', 'image', 'video'],
                ['clean']
            ]
        }
    });
    
    quill.on('text-change', function() {
        $('#description').val(quill.root.innerHTML);
    });
    
    // ========== IMAGE GALLERY (VERTICAL LAYOUT WITH CROSS ON TOP LEFT) ==========
    let imageFiles = [];
    let imagePreviews = [];
    let featuredIndex = 0;

    $('#productImages').on('change', function(e) {
        const files = Array.from(e.target.files);
        
        files.forEach((file) => {
            if (file.size > 2 * 1024 * 1024) {
                Swal.fire({
                    icon: 'warning',
                    title: 'File Too Large',
                    text: `${file.name} is larger than 2MB. It will be skipped.`,
                    confirmButtonColor: '#d33'
                });
                return;
            }
            
            if (!file.type.match('image.*')) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Invalid File',
                    text: `${file.name} is not an image file.`,
                    confirmButtonColor: '#d33'
                });
                return;
            }
            
            imageFiles.push(file);
            
            const reader = new FileReader();
            
            reader.onload = function(e) {
                imagePreviews.push({
                    id: Date.now() + Math.random(),
                    url: e.target.result,
                    file: file,
                    name: file.name,
                    size: file.size
                });
                
                renderImageGallery();
                
                if (imagePreviews.length === 1) {
                    featuredIndex = 0;
                    $('#featuredImageIndex').val(0);
                }
            };
            
            reader.readAsDataURL(file);
        });
        
        $(this).val('');
    });

    function renderImageGallery() {
        if (imagePreviews.length === 0) {
            $('#imageGallery').html(`
                <div class="text-center py-4 border rounded bg-light">
                    <i class="ti ti-photo-off fs-1 text-muted"></i>
                    <p class="text-muted mt-2 mb-0">No images selected. Upload images to see preview.</p>
                </div>
            `);
            return;
        }
        
        let html = '';
        imagePreviews.forEach((preview, index) => {
            const isFeatured = (featuredIndex === index);
            html += `
                <div class="card mb-3 shadow-sm" data-image-index="${index}">
                    <div class="row g-0 align-items-center">
                        <div class="col-auto position-relative">
                            <div class="position-relative">
                                <img src="${preview.url}" class="img-fluid rounded-start" style="width: 100px; height: 100px; object-fit: cover;" alt="Product image">
                                <button type="button" class="btn btn-sm btn-danger position-absolute top-0 start-0 m-1 remove-image" data-index="${index}" style="border-radius: 50%; width: 26px; height: 26px; padding: 0; line-height: 1; font-size: 12px;">
                                    <i class="ti ti-x"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card-body py-2">
                                <div class="d-flex justify-content-between align-items-center flex-wrap">
                                    <div>
                                        <div class="fw-semibold text-truncate" style="max-width: 200px;">${preview.name}</div>
                                        <div class="small text-muted">${(preview.size / 1024).toFixed(1)} KB</div>
                                    </div>
                                    <div class="form-check">
                                        <input type="radio" class="form-check-input set-featured" name="featured_radio" value="${index}" 
                                            ${isFeatured ? 'checked' : ''} data-index="${index}" id="featured_${index}">
                                        <label class="form-check-label" for="featured_${index}">
                                            ${isFeatured ? '<i class="ti ti-star text-warning"></i> Featured' : 'Set as Featured'}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });
        
        $('#imageGallery').html(html);
    }

    // Remove image with cross button
    $(document).on('click', '.remove-image', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const index = $(this).data('index');
        
        Swal.fire({
            title: 'Remove Image?',
            text: 'Are you sure you want to remove this image?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, remove it!'
        }).then((result) => {
            if (result.isConfirmed) {
                imagePreviews.splice(index, 1);
                
                if (featuredIndex === index) {
                    featuredIndex = 0;
                    $('#featuredImageIndex').val(0);
                } else if (featuredIndex > index) {
                    featuredIndex--;
                    $('#featuredImageIndex').val(featuredIndex);
                }
                
                renderImageGallery();
            }
        });
    });

    // Set featured image
    $(document).on('change', '.set-featured', function() {
        const index = parseInt($(this).val());
        featuredIndex = index;
        $('#featuredImageIndex').val(index);
        renderImageGallery();
    });

    // ========== DYNAMIC CATEGORIES & ATTRIBUTES (FIXED - CLEAR ON CHANGE) ==========
    
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
    
    // Clear all attribute sections
    function clearAllAttributes() {
        $('#customAttributesContainer').empty();
        $('#subcategoriesContainer').empty();
        $('#subSubcategoriesContainer').empty();
    }
    
    // Main Category Change - Clear everything and reload
    $('#mainCategory').on('change', function() {
        let categoryId = $(this).val();
        
        // Clear all previous data
        clearAllAttributes();
        
        if (categoryId) {
            // Load attributes for main category
            loadAttributesForCategory(categoryId, 'main');
            // Load subcategories
            loadSubcategories(categoryId, 0, '#subcategoriesContainer', 'subcategories[]', 'Subcategories');
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
                    
                    // When subcategory changes, load its attributes
                    $(`#subcategory-level-${level}`).off('change').on('change', function() {
                        let selectedSubcategories = $(this).val();
                        // Remove previous subcategory attributes
                        removeAttributesForLevel(`sub_${level}`);
                        
                        if (selectedSubcategories && selectedSubcategories.length > 0) {
                            selectedSubcategories.forEach(function(subId) {
                                loadAttributesForCategory(subId, `sub_${level}`);
                            });
                            // Load sub-subcategories for the first selected
                            if (selectedSubcategories.length > 0) {
                                loadSubSubcategories(selectedSubcategories[0], level + 1);
                            }
                        }
                    });
                } else {
                    $(containerId).html('');
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
                    // Remove existing container if any
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
                    
                    // When sub-subcategory changes, load its attributes
                    $(`#subcategory-level-${level}`).off('change').on('change', function() {
                        let selectedSubSubs = $(this).val();
                        // Remove previous sub-subcategory attributes
                        removeAttributesForLevel(`sub_sub_${level}`);
                        
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
    
    function loadAttributesForCategory(categoryId, level) {
        $.ajax({
            url: '/admin/attributes/by-category/' + categoryId,
            type: 'GET',
            success: function(response) {
                if (response.attributes && response.attributes.length > 0) {
                    let containerId = `#attributes-level-${level}`;
                    let levelName = getLevelName(level);
                    
                    // Check if section already exists
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
                    } else {
                        // Clear existing content
                        $(`#attributes-level-${level}`).empty();
                    }
                    
                    let html = '';
                    response.attributes.forEach(function(attribute) {
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
                                html += `<option value="${value.id}">${value.value}</option>`;
                            });
                            html += `</select>`;
                        } else if (attribute.type === 'color') {
                            html += `<div class="d-flex flex-wrap gap-2 mt-2">`;
                            attribute.values.forEach(function(value) {
                                html += `
                                    <label class="color-option">
                                        <input type="radio" name="custom_attributes[${attribute.id}]" value="${value.id}" style="display: none;">
                                        <div style="width: 40px; height: 40px; background: ${value.color_code}; border-radius: 8px; border: 2px solid #dee2e6; cursor: pointer;"></div>
                                        <span class="d-block text-center small">${value.value}</span>
                                    </label>
                                `;
                            });
                            html += `</div>`;
                        } else if (attribute.type === 'size') {
                            html += `<div class="d-flex flex-wrap gap-2 mt-2">`;
                            attribute.values.forEach(function(value) {
                                html += `
                                    <label class="btn btn-outline-secondary size-option">
                                        <input type="checkbox" name="custom_attributes[${attribute.id}][]" value="${value.id}" style="display: none;">
                                        ${value.value}
                                    </label>
                                `;
                            });
                            html += `</div>`;
                        } else if (attribute.type === 'textarea') {
                            html += `<textarea class="form-control" name="custom_attributes[${attribute.id}]" rows="2"></textarea>`;
                        } else {
                            html += `<input type="${attribute.type}" class="form-control" name="custom_attributes[${attribute.id}]">`;
                        }
                        
                        html += `</div>`;
                    });
                    
                    $(containerId).html(html);
                    $('#customAttributesContainer').show();
                    
                    // Reinitialize Choices.js for new selects
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
                    
                    // Initialize color and size options
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
    
    function removeAttributesForLevel(level) {
        $(`#attributes-level-${level}-card`).remove();
    }
    
    function getLevelName(level) {
        if (level === 'main') return 'Main Category';
        if (level.toString().startsWith('sub_')) return 'Subcategory';
        if (level.toString().startsWith('sub_sub_')) return 'Sub-Subcategory';
        return 'Category';
    }
    
    // ========== VARIANTS GENERATION ==========
    function generateVariantsTable(colors, sizes) {
        if (!colors || !sizes || colors.length === 0 || sizes.length === 0) {
            $('#variantsContainer').empty();
            return;
        }
        
        let html = `
            <div class="table-responsive">
                <table class="table table-bordered table-sm">
                    <thead class="table-light">
                            <th>Color</th>
                            <th>Size</th>
                            <th>SKU</th>
                            <th>Price</th>
                            <th>Sale Price</th>
                            <th>Stock</th>
                        </thead>
                        <tbody>
        `;
        
        let variantIndex = 0;
        
         for (let colorId of colors) {
        let colorName = $('#colors option[value="' + colorId + '"]').text();
        for (let sizeId of sizes) {
            let sizeName = $('#sizes option[value="' + sizeId + '"]').text();
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
                        <input type="text" class="form-control form-control-sm" name="variants[${variantIndex}][sku]" placeholder="Enter SKU">
                    </td>
                    <td>
                        <input type="number" class="form-control form-control-sm" name="variants[${variantIndex}][price]" step="0.01" placeholder="Price">
                    </td>
                    <td>
                        <input type="number" class="form-control form-control-sm" name="variants[${variantIndex}][sale_price]" step="0.01" placeholder="Sale Price">
                    </td>
                    <td>
                        <input type="number" class="form-control form-control-sm" name="variants[${variantIndex}][stock]" placeholder="Stock" value="0">
                    </td>
                </tr>
            `;
            variantIndex++;
        }
    }
    
    $('#variantsContainer').html(html);
}
    
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
    
    // ========== PRICING ==========
    $('#pricingType').on('change', function() {
        if ($(this).val() === 'tiered') {
            $('#tieredPricingSection').slideDown();
        } else {
            $('#tieredPricingSection').slideUp();
        }
    });
    
    $('#track_stock').on('change', function() {
        if ($(this).is(':checked')) {
            $('#stockQuantityDiv').slideDown();
        } else {
            $('#stockQuantityDiv').slideUp();
        }
    });
    
    let tierIndex = 1;
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
    
    // Character counters
    $('[name="meta_title"]').on('keyup', function() {
        let length = $(this).val().length;
        $('#metaTitleCount').text(length + '/70 characters');
        if (length > 70) $('#metaTitleCount').addClass('text-danger');
        else $('#metaTitleCount').removeClass('text-danger');
    });
    
    $('[name="meta_description"]').on('keyup', function() {
        let length = $(this).val().length;
        $('#metaDescCount').text(length + '/160 characters');
        if (length > 160) $('#metaDescCount').addClass('text-danger');
        else $('#metaDescCount').removeClass('text-danger');
    });
    
    // Form submission
    $('#productForm').on('submit', function(e) {
        e.preventDefault();
        
        $('#description').val(quill.root.innerHTML);
        
        let btn = $('#submitBtn');
        btn.html('<span class="spinner-border spinner-border-sm me-1"></span> Creating...').prop('disabled', true);
        
        let formData = new FormData(this);
        
        for (let key of formData.keys()) {
            if (key.startsWith('product_images')) {
                formData.delete(key);
            }
        }
        
        imagePreviews.forEach((preview) => {
            formData.append('product_images[]', preview.file);
        });
        
        formData.set('featured_image_index', featuredIndex);
        
        $.ajax({
            url: '{{ route("admin.products.store") }}',
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
                    
                    $('html, body').animate({
                        scrollTop: $('.is-invalid:first').offset().top - 100
                    }, 500);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: xhr.responseJSON?.message || 'Something went wrong.'
                    });
                }
                btn.html('Create Product').prop('disabled', false);
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
.ql-editor {
    min-height: 250px;
}
.remove-image {
    opacity: 0.8;
    transition: all 0.2s;
    z-index: 10;
}
.remove-image:hover {
    opacity: 1;
    transform: scale(1.1);
}
.card {
    transition: transform 0.2s, box-shadow 0.2s;
}
.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}
</style>
@endpush