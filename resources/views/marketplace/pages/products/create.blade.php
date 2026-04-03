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
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Basic Information</h5>
                                <button type="button" class="btn btn-sm btn-outline-secondary" id="refreshDataBtn"
                                    title="Refresh all dynamic data without page reload">
                                    <i class="ti ti-refresh"></i> Refresh Data
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Product Name <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="name" id="productName"
                                                required>
                                            <div class="invalid-feedback" id="name-error"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">SKU <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="sku" id="productSku"
                                                required>
                                            <div class="invalid-feedback" id="sku-error"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label">Short Description</label>
                                            <textarea class="form-control" name="short_description" id="shortDescription" rows="2"></textarea>
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

                        {{-- Categories Section with Multi-level Support --}}
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5>Categories</h5>
                                <p class="text-muted mb-0">Select main category, then subcategories will appear (supports
                                    unlimited levels)</p>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label">Main Category <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <select class="form-select" name="category_id" id="mainCategory" required>
                                                    <option value="">Select Main Category</option>
                                                    @foreach ($categories as $category)
                                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                    @endforeach
                                                </select>
                                                <button type="button" class="btn btn-outline-primary"
                                                    data-bs-toggle="modal" data-bs-target="#addMainCategoryModal">
                                                    <i class="ti ti-plus"></i> Add
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Dynamic Multi-level Categories Container --}}
                                <div id="multiLevelCategoriesContainer"></div>
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
                                            <label class="form-label">Regular Price <span
                                                    class="text-danger">*</span></label>
                                            <input type="number" class="form-control" name="price" id="regularPrice"
                                                step="0.01" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Sale Price</label>
                                            <input type="number" class="form-control" name="sale_price" id="salePrice"
                                                step="0.01">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Sale Start Date</label>
                                            <input type="date" class="form-control" name="sale_start_date"
                                                id="saleStartDate">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Sale End Date</label>
                                            <input type="date" class="form-control" name="sale_end_date"
                                                id="saleEndDate">
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
                                                <input type="number" class="form-control"
                                                    name="tier_prices[0][min_quantity]" placeholder="Min Quantity"
                                                    step="1">
                                            </div>
                                            <div class="col-md-3">
                                                <input type="number" class="form-control"
                                                    name="tier_prices[0][max_quantity]" placeholder="Max Quantity"
                                                    step="1">
                                            </div>
                                            <div class="col-md-3">
                                                <input type="number" class="form-control" name="tier_prices[0][price]"
                                                    placeholder="Price" step="0.01">
                                            </div>
                                            <div class="col-md-3">
                                                <button type="button"
                                                    class="btn btn-danger btn-sm remove-tier">Remove</button>
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
                                            <div class="input-group">
                                                <select class="form-control" id="colorsSelect" name="colors[]" multiple>
                                                    @foreach ($colors as $color)
                                                        <option value="{{ $color->id }}">{{ $color->name }}</option>
                                                    @endforeach
                                                </select>
                                                <button type="button" class="btn btn-outline-primary"
                                                    data-bs-toggle="modal" data-bs-target="#addColorModal">
                                                    <i class="ti ti-plus"></i> Add
                                                </button>
                                            </div>
                                            <small class="text-muted">Select multiple colors or add new</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Sizes</label>
                                            <div class="input-group">
                                                <select class="form-control" id="sizesSelect" name="sizes[]" multiple>
                                                    @foreach ($sizes as $size)
                                                        <option value="{{ $size->id }}">{{ $size->name }}</option>
                                                    @endforeach
                                                </select>
                                                <button type="button" class="btn btn-outline-primary"
                                                    data-bs-toggle="modal" data-bs-target="#addSizeModal">
                                                    <i class="ti ti-plus"></i> Add
                                                </button>
                                            </div>
                                            <small class="text-muted">Select multiple sizes or add new</small>
                                        </div>
                                    </div>
                                </div>

                                {{-- Color Preview Section --}}
                                <div id="colorPreviewSection" class="mt-2" style="display: none;">
                                    <label class="form-label">Selected Colors:</label>
                                    <div id="selectedColorsPreview" class="d-flex flex-wrap gap-2"></div>
                                </div>

                                {{-- Size Preview Section --}}
                                <div id="sizePreviewSection" class="mt-2" style="display: none;">
                                    <label class="form-label">Selected Sizes:</label>
                                    <div id="selectedSizesPreview" class="d-flex flex-wrap gap-2"></div>
                                </div>

                                {{-- Variants Section --}}
                                <div id="variantsSection" style="display: none;">
                                    <hr>
                                    <h6 class="mt-3">Product Variants (Color + Size Combinations)</h6>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-sm align-middle">
                                            <thead class="table-light">
                                                <tr>
                                                    <th style="width: 15%">Color</th>
                                                    <th style="width: 15%">Size</th>
                                                    <th style="width: 25%">SKU</th>
                                                    <th style="width: 15%">Price</th>
                                                    <th style="width: 15%">Sale Price</th>
                                                    <th style="width: 15%">Stock</th>
                                                </tr>
                                            </thead>
                                            <tbody id="variantsContainer">
                                                <tr>
                                                    <td colspan="6" class="text-center text-muted py-3">
                                                        Select colors and sizes to generate variants
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        {{-- Images Section --}}
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5>Product Images</h5>
                                <p class="text-muted mb-0">Upload multiple images. Click the star to set featured image.
                                </p>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Upload Images</label>
                                    <input type="file" class="form-control" id="productImages"
                                        name="product_images[]" multiple accept="image/*">
                                    <small class="text-muted">You can select multiple images. First image will be featured
                                        by default.</small>
                                </div>

                                <div id="imageGallery" class="mt-3"></div>

                                <input type="hidden" name="featured_image_index" id="featuredImageIndex"
                                    value="0">
                            </div>
                        </div>

                        {{-- Stock & Shipping --}}
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5>Stock & Shipping</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-check form-switch mb-3">
                                    <input type="checkbox" class="form-check-input" id="track_stock" name="track_stock"
                                        value="1" checked>
                                    <label class="form-check-label" for="track_stock">Track Stock</label>
                                </div>
                                <div class="mb-3" id="stockQuantityDiv">
                                    <label class="form-label">Stock Quantity</label>
                                    <input type="number" class="form-control" name="stock" id="stockQuantity"
                                        value="0">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Low Stock Threshold</label>
                                    <input type="number" class="form-control" name="low_stock_threshold"
                                        id="lowStockThreshold" value="5">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Weight (kg)</label>
                                    <input type="number" class="form-control" name="weight" id="weight"
                                        step="0.01">
                                </div>
                                <div class="row">
                                    <div class="col-4">
                                        <label class="form-label">Length (cm)</label>
                                        <input type="number" class="form-control" name="length" id="length"
                                            step="0.01">
                                    </div>
                                    <div class="col-4">
                                        <label class="form-label">Width (cm)</label>
                                        <input type="number" class="form-control" name="width" id="width"
                                            step="0.01">
                                    </div>
                                    <div class="col-4">
                                        <label class="form-label">Height (cm)</label>
                                        <input type="number" class="form-control" name="height" id="height"
                                            step="0.01">
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
                                    <input type="checkbox" class="form-check-input" id="status" name="status"
                                        value="1" checked>
                                    <label class="form-check-label" for="status">Active</label>
                                </div>
                                <div class="form-check form-switch mb-2">
                                    <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured"
                                        value="1">
                                    <label class="form-check-label" for="is_featured">Featured Product</label>
                                </div>
                                <div class="form-check form-switch mb-2">
                                    <input type="checkbox" class="form-check-input" id="is_new" name="is_new"
                                        value="1">
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
                                    <input type="text" class="form-control" name="meta_title" id="metaTitle"
                                        maxlength="70">
                                    <small class="text-muted" id="metaTitleCount">0/70 characters</small>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Meta Description</label>
                                    <textarea class="form-control" name="meta_description" id="metaDescription" rows="2" maxlength="160"></textarea>
                                    <small class="text-muted" id="metaDescCount">0/160 characters</small>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Meta Keywords</label>
                                    <input type="text" class="form-control" name="meta_keywords" id="metaKeywords"
                                        placeholder="keyword1, keyword2, keyword3">
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

    {{-- ========== MODALS ========== --}}

    {{-- Add Main Category Modal --}}
    <div class="modal fade" id="addMainCategoryModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Main Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addMainCategoryForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Category Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="form-check form-switch">
                            <input type="checkbox" class="form-check-input" id="mainCategoryStatus" name="status"
                                value="1" checked>
                            <label class="form-check-label" for="mainCategoryStatus">Active</label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveMainCategoryBtn">Add Category</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Add Subcategory Modal --}}
    <div class="modal fade" id="addSubcategoryModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Subcategory</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addSubcategoryForm">
                        @csrf
                        <input type="hidden" name="parent_id" id="subcategoryParentId">
                        <div class="mb-3">
                            <label class="form-label">Parent Category</label>
                            <input type="text" class="form-control" id="parentCategoryName" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Subcategory Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="form-check form-switch">
                            <input type="checkbox" class="form-check-input" id="subcategoryStatus" name="status"
                                value="1" checked>
                            <label class="form-check-label" for="subcategoryStatus">Active</label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveSubcategoryBtn">Add Subcategory</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Add Color Modal --}}
    <div class="modal fade" id="addColorModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Color</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addColorForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Color Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Color Code</label>
                            <input type="text" class="form-control" name="code" placeholder="e.g., RED">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Hex Code</label>
                            <input type="color" class="form-control" name="hex_code" value="#000000"
                                style="height: 45px;">
                        </div>
                        <div class="form-check form-switch">
                            <input type="checkbox" class="form-check-input" id="colorStatus" name="status"
                                value="1" checked>
                            <label class="form-check-label" for="colorStatus">Active</label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveColorBtn">Add Color</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Add Size Modal --}}
    <div class="modal fade" id="addSizeModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Size</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addSizeForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Size Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Size Code</label>
                            <input type="text" class="form-control" name="code" placeholder="e.g., S, M, L, XL">
                        </div>
                        <div class="form-check form-switch">
                            <input type="checkbox" class="form-check-input" id="sizeStatus" name="status"
                                value="1" checked>
                            <label class="form-check-label" for="sizeStatus">Active</label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveSizeBtn">Add Size</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Add Custom Attribute Modal --}}
    <div class="modal fade" id="addAttributeModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Custom Attribute</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addAttributeForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Attribute Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Attribute Type</label>
                            <select class="form-select" name="type">
                                <option value="text">Text</option>
                                <option value="select">Select (Dropdown)</option>
                                <option value="multiselect">Multi-Select</option>
                                <option value="color">Color</option>
                                <option value="size">Size</option>
                                <option value="number">Number</option>
                                <option value="checkbox">Checkbox</option>
                                <option value="radio">Radio</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Unit (Optional)</label>
                            <input type="text" class="form-control" name="unit" placeholder="e.g., GB, cm, kg">
                        </div>
                        <div class="form-check form-switch">
                            <input type="checkbox" class="form-check-input" id="attributeStatus" name="status"
                                value="1" checked>
                            <label class="form-check-label" for="attributeStatus">Active</label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveAttributeBtn">Add Attribute</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Add Attribute Value Modal --}}
    <div class="modal fade" id="addAttributeValueModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Attribute Value</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addAttributeValueForm">
                        @csrf
                        <input type="hidden" name="attribute_id" id="attributeIdForValue">
                        <div class="mb-3">
                            <label class="form-label">Value <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="value" required>
                        </div>
                        <div class="mb-3" id="colorCodeField" style="display: none;">
                            <label class="form-label">Color Code</label>
                            <input type="color" class="form-control" name="color_code">
                        </div>
                        <div class="form-check form-switch">
                            <input type="checkbox" class="form-check-input" id="valueDefault" name="is_default"
                                value="1">
                            <label class="form-check-label" for="valueDefault">Set as Default</label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveAttributeValueBtn">Add Value</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Include Quill Editor -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" rel="stylesheet">

    <style>
        /* Fix Choices.js dropdown width */
        .choices {
            width: 100% !important;
            margin-bottom: 0 !important;
        }

        .choices__inner {
            width: 100% !important;
            background-color: #fff;
            border-radius: 0.375rem;
            min-height: 38px;
            padding: 0.25rem 2rem 0.25rem 0.5rem;
        }

        .choices__list--dropdown,
        .choices__list[aria-expanded] {
            width: 100% !important;
            min-width: 100% !important;
        }

        .input-group .choices {
            flex: 1 1 auto;
            width: 1% !important;
        }

        .input-group .choices__inner {
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }

        .color-preview-badge {
            display: inline-flex;
            align-items: center;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 13px;
            gap: 8px;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
        }

        .color-dot {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: inline-block;
            border: 1px solid #dee2e6;
        }

        .size-badge {
            display: inline-flex;
            align-items: center;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 13px;
            background-color: #e9ecef;
            color: #495057;
        }

        .category-level {
            margin-left: 20px;
            margin-top: 10px;
            padding-left: 15px;
            border-left: 2px solid #dee2e6;
        }

        .category-header {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
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
    </style>

    <script>
        $(document).ready(function() {
            // ========== INITIALIZE CHOICES.JS ==========
            let colorsChoices, sizesChoices;
            let selectedCategoryIds = []; // Track all selected category IDs for attributes

            function initChoices() {
                if (typeof Choices !== 'undefined') {
                    if (colorsChoices) colorsChoices.destroy();
                    colorsChoices = new Choices('#colorsSelect', {
                        removeItemButton: true,
                        placeholder: true,
                        placeholderValue: 'Select colors...',
                        searchEnabled: true,
                        shouldSort: false,
                        itemSelectText: '',
                    });

                    if (sizesChoices) sizesChoices.destroy();
                    sizesChoices = new Choices('#sizesSelect', {
                        removeItemButton: true,
                        placeholder: true,
                        placeholderValue: 'Select sizes...',
                        searchEnabled: true,
                        shouldSort: false,
                        itemSelectText: '',
                    });
                }
            }

            initChoices();

            // ========== RICH TEXT EDITOR ==========
            var quill = new Quill('#fullDescriptionEditor', {
                theme: 'snow',
                placeholder: 'Write a detailed product description...',
                modules: {
                    toolbar: [
                        [{
                            'header': [1, 2, 3, 4, 5, 6, false]
                        }],
                        ['bold', 'italic', 'underline', 'strike'],
                        ['blockquote', 'code-block'],
                        [{
                            'list': 'ordered'
                        }, {
                            'list': 'bullet'
                        }],
                        [{
                            'indent': '-1'
                        }, {
                            'indent': '+1'
                        }],
                        [{
                            'align': []
                        }],
                        ['link', 'image', 'video'],
                        ['clean']
                    ]
                }
            });

            quill.on('text-change', function() {
                $('#description').val(quill.root.innerHTML);
            });

            // ========== IMAGE GALLERY ==========
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
                            text: `${file.name} is larger than 2MB. It will be skipped.`
                        });
                        return;
                    }
                    if (!file.type.match('image.*')) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Invalid File',
                            text: `${file.name} is not an image file.`
                        });
                        return;
                    }

                    imageFiles.push(file);
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        imagePreviews.push({
                            id: Date.now(),
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
                    $('#imageGallery').html(
                        `<div class="text-center py-4 border rounded bg-light"><i class="ti ti-photo-off fs-1 text-muted"></i><p class="text-muted mt-2 mb-0">No images selected.</p></div>`
                    );
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
                                        <img src="${preview.url}" class="img-fluid rounded-start" style="width: 80px; height: 80px; object-fit: cover;">
                                        <button type="button" class="btn btn-sm btn-danger position-absolute top-0 start-0 m-1 remove-image" data-index="${index}" style="border-radius: 50%; width: 22px; height: 22px; padding: 0; line-height: 1; font-size: 10px;"><i class="ti ti-x"></i></button>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="card-body py-2">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div><div class="fw-semibold text-truncate" style="max-width: 150px;">${preview.name}</div><div class="small text-muted">${(preview.size / 1024).toFixed(1)} KB</div></div>
                                            <div class="form-check">
                                                <input type="radio" class="form-check-input set-featured" name="featured_radio" value="${index}" ${isFeatured ? 'checked' : ''} data-index="${index}" id="featured_${index}">
                                                <label class="form-check-label" for="featured_${index}">${isFeatured ? '<i class="ti ti-star text-warning"></i> Featured' : 'Set as Featured'}</label>
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

            $(document).on('click', '.remove-image', function(e) {
                const index = $(this).data('index');
                Swal.fire({
                    title: 'Remove Image?',
                    text: 'Are you sure?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Yes'
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

            $(document).on('change', '.set-featured', function() {
                featuredIndex = parseInt($(this).val());
                $('#featuredImageIndex').val(featuredIndex);
                renderImageGallery();
            });

            // ========== MULTI-LEVEL CATEGORIES WITH ACCUMULATED ATTRIBUTES ==========
            $('#mainCategory').on('change', function() {
                let categoryId = $(this).val();
                let categoryName = $(this).find('option:selected').text();

                if (categoryId) {
                    $('#multiLevelCategoriesContainer').html('');
                    selectedCategoryIds = [parseInt(categoryId)]; // Start with main category
                    loadChildCategories(categoryId, 0, categoryName);
                    loadAttributesForCategories(
                        selectedCategoryIds); // Load attributes for all selected categories
                } else {
                    $('#multiLevelCategoriesContainer').html('');
                    $('#customAttributesContainer').empty();
                    selectedCategoryIds = [];
                }
            });

            function loadChildCategories(parentId, level, parentName) {
                $.ajax({
                    url: '{{ url('/admin/categories') }}/' + parentId + '/subcategories',
                    type: 'GET',
                    success: function(response) {
                        if (response.subcategories && response.subcategories.length > 0) {
                            let levelHtml = `
                                <div class="category-level" data-level="${level}" data-parent="${parentId}">
                                    <div class="category-header">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <label class="form-label fw-bold mb-0">Select ${parentName}</label>
                                            <button type="button" class="btn btn-sm btn-outline-primary add-subcategory-btn" data-parent-id="${parentId}" data-parent-name="${parentName}">
                                                <i class="ti ti-plus"></i> Add Subcategory
                                            </button>
                                        </div>
                                    </div>
                                    <div class="row">
                            `;

                            response.subcategories.forEach(function(sub) {
                                let isChecked = selectedCategoryIds.includes(sub.id);
                                levelHtml += `
                                    <div class="col-md-6 mb-2">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input subcategory-checkbox" name="subcategories[]" value="${sub.id}" data-category-id="${sub.id}" data-category-name="${sub.name}" data-parent-id="${parentId}" id="sub_${sub.id}" ${isChecked ? 'checked' : ''}>
                                            <label class="form-check-label" for="sub_${sub.id}">${sub.name}</label>
                                            <button type="button" class="btn btn-sm btn-link p-0 ms-2 load-child-categories" data-category-id="${sub.id}" data-category-name="${sub.name}" data-level="${level + 1}">
                                                <i class="ti ti-chevron-right"></i> Load Subcategories
                                            </button>
                                        </div>
                                    </div>
                                `;
                            });

                            levelHtml += `</div></div>`;

                            $(`.category-level[data-level="${level}"]`).nextAll('.category-level')
                                .remove();

                            if ($(`.category-level[data-level="${level}"]`).length) {
                                $(`.category-level[data-level="${level}"]`).replaceWith(levelHtml);
                            } else {
                                $('#multiLevelCategoriesContainer').append(levelHtml);
                            }
                        } else {
                            let noSubHtml = `
                                <div class="category-level" data-level="${level}" data-parent="${parentId}">
                                    <div class="category-header">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <label class="form-label fw-bold mb-0">${parentName} (No Subcategories)</label>
                                            <button type="button" class="btn btn-sm btn-outline-primary add-subcategory-btn" data-parent-id="${parentId}" data-parent-name="${parentName}">
                                                <i class="ti ti-plus"></i> Add Subcategory
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            `;

                            $(`.category-level[data-level="${level}"]`).nextAll('.category-level')
                                .remove();

                            if ($(`.category-level[data-level="${level}"]`).length) {
                                $(`.category-level[data-level="${level}"]`).replaceWith(noSubHtml);
                            } else {
                                $('#multiLevelCategoriesContainer').append(noSubHtml);
                            }
                        }
                    }
                });
            }

            $(document).on('click', '.load-child-categories', function(e) {
                e.preventDefault();
                let categoryId = $(this).data('category-id');
                let categoryName = $(this).data('category-name');
                let level = $(this).data('level');
                loadChildCategories(categoryId, level, categoryName);
            });

            $(document).on('click', '.add-subcategory-btn', function() {
                let parentId = $(this).data('parent-id');
                let parentName = $(this).data('parent-name');
                openAddSubcategoryModal(parentId, parentName);
            });

            window.openAddSubcategoryModal = function(parentId, parentName) {
                $('#subcategoryParentId').val(parentId);
                $('#parentCategoryName').val(parentName);
                $('#addSubcategoryModal').modal('show');
            };

            // Handle subcategory checkbox changes - UPDATE ACCUMULATED ATTRIBUTES
            $(document).on('change', '.subcategory-checkbox', function() {
                let categoryId = parseInt($(this).val());

                if ($(this).is(':checked')) {
                    // Add to selected categories if not already present
                    if (!selectedCategoryIds.includes(categoryId)) {
                        selectedCategoryIds.push(categoryId);
                    }
                } else {
                    // Remove from selected categories
                    let index = selectedCategoryIds.indexOf(categoryId);
                    if (index > -1) {
                        selectedCategoryIds.splice(index, 1);
                    }
                }

                // Also load child categories for this category if checked and not loaded
                if ($(this).is(':checked')) {
                    let categoryName = $(this).data('category-name');
                    let level = $(this).closest('.category-level').data('level');
                    if (level !== undefined) {
                        loadChildCategories(categoryId, level + 1, categoryName);
                    }
                }

                // Load attributes for ALL selected categories (main + subcategories)
                if (selectedCategoryIds.length > 0) {
                    loadAttributesForCategories(selectedCategoryIds);
                } else {
                    let mainCategoryId = $('#mainCategory').val();
                    if (mainCategoryId) {
                        selectedCategoryIds = [parseInt(mainCategoryId)];
                        loadAttributesForCategories(selectedCategoryIds);
                    } else {
                        $('#customAttributesContainer').empty();
                    }
                }
            });

            // Load attributes from multiple categories and merge them
            function loadAttributesForCategories(categoryIds) {
                if (!categoryIds || categoryIds.length === 0) {
                    $('#customAttributesContainer').empty();
                    return;
                }

                // Show loading state
                $('#customAttributesContainer').html(
                    '<div class="card mb-3"><div class="card-body text-center"><div class="spinner-border spinner-border-sm"></div> Loading attributes...</div></div>'
                );

                // Fetch attributes for all categories and merge them
                let allAttributes = [];
                let completedRequests = 0;

                categoryIds.forEach(function(categoryId) {
                    $.ajax({
                        url: '{{ url('/admin/attributes/by-category') }}/' + categoryId,
                        type: 'GET',
                        async: true,
                        success: function(response) {
                            if (response.attributes && response.attributes.length > 0) {
                                response.attributes.forEach(function(attr) {
                                    // Check if attribute already exists (by ID)
                                    let existing = allAttributes.find(a => a.id === attr
                                        .id);
                                    if (!existing) {
                                        allAttributes.push(attr);
                                    } else {
                                        // Merge values if needed
                                        if (attr.values && attr.values.length) {
                                            existing.values = existing.values || [];
                                            attr.values.forEach(function(v) {
                                                if (!existing.values.find(ev =>
                                                        ev.id === v.id)) {
                                                    existing.values.push(v);
                                                }
                                            });
                                        }
                                    }
                                });
                            }
                            completedRequests++;

                            // When all requests are complete, render the merged attributes
                            if (completedRequests === categoryIds.length) {
                                renderAttributes(allAttributes);
                            }
                        },
                        error: function() {
                            completedRequests++;
                            if (completedRequests === categoryIds.length) {
                                renderAttributes(allAttributes);
                            }
                        }
                    });
                });
            }

            function renderAttributes(attributes) {
                if (attributes && attributes.length > 0) {
                    let html =
                        '<div class="card mb-3"><div class="card-header d-flex justify-content-between align-items-center"><h5 class="mb-0">Product Specifications</h5><button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addAttributeModal"><i class="ti ti-plus"></i> Add Attribute</button></div><div class="card-body">';

                    attributes.forEach(function(attribute) {
                        html +=
                            `<div class="mb-3"><label class="form-label fw-semibold">${attribute.name} ${attribute.unit ? '(' + attribute.unit + ')' : ''}</label>`;

                        if (attribute.type === 'select') {
                            html +=
                                `<select class="form-select" name="custom_attributes[${attribute.id}]"><option value="">Select ${attribute.name}</option>`;
                            if (attribute.values && attribute.values.length > 0) {
                                attribute.values.forEach(function(v) {
                                    html += `<option value="${v.id}">${v.value}</option>`;
                                });
                            }
                            html += `</select>`;
                            html +=
                                `<button type="button" class="btn btn-sm btn-link p-0 mt-1 add-value-btn" data-attribute-id="${attribute.id}" data-attribute-name="${attribute.name}" data-attribute-type="${attribute.type}">+ Add new value</button>`;
                        } else if (attribute.type === 'multiselect') {
                            html +=
                                `<select class="form-control attr-multiselect" id="attr_${attribute.id}" name="custom_attributes[${attribute.id}][]" multiple>`;
                            if (attribute.values && attribute.values.length > 0) {
                                attribute.values.forEach(function(v) {
                                    html += `<option value="${v.id}">${v.value}</option>`;
                                });
                            }
                            html += `</select>`;
                            html +=
                                `<button type="button" class="btn btn-sm btn-link p-0 mt-1 add-value-btn" data-attribute-id="${attribute.id}" data-attribute-name="${attribute.name}" data-attribute-type="${attribute.type}">+ Add new value</button>`;
                        } else if (attribute.type === 'color') {
                            html +=
                                `<div class="d-flex align-items-center gap-2"><input type="color" class="form-control w-auto" style="width: 60px;" name="custom_attributes[${attribute.id}]" value="#000000"> <input type="text" class="form-control" name="custom_attributes_text[${attribute.id}]" placeholder="Color name"></div>`;
                        } else {
                            html +=
                                `<input type="${attribute.type === 'number' ? 'number' : 'text'}" class="form-control" name="custom_attributes[${attribute.id}]" step="${attribute.type === 'number' ? '0.01' : ''}">`;
                        }
                        html += `</div>`;
                    });

                    html += '</div></div>';
                    $('#customAttributesContainer').html(html);

                    // Initialize Choices for multiselect attributes
                    if (typeof Choices !== 'undefined') {
                        $('.attr-multiselect').each(function() {
                            new Choices(this, {
                                removeItemButton: true,
                                placeholder: true,
                                placeholderValue: 'Select options...',
                                searchEnabled: true,
                                itemSelectText: '',
                            });
                        });
                    }
                } else {
                    $('#customAttributesContainer').html(`
                        <div class="card mb-3">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Product Specifications</h5>
                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addAttributeModal">
                                    <i class="ti ti-plus"></i> Add Attribute
                                </button>
                            </div>
                            <div class="card-body text-muted text-center py-4">
                                No custom attributes available. Click "Add Attribute" to create one.
                            </div>
                        </div>
                    `);
                }
            }

            // ========== COLORS PREVIEW ==========
            function updateColorPreview() {
                if (colorsChoices) {
                    let selectedValues = colorsChoices.getValue(true);
                    if (selectedValues && selectedValues.length > 0) {
                        let previewHtml = '';
                        selectedValues.forEach(function(colorId) {
                            let colorName = $('#colorsSelect option[value="' + colorId + '"]').text();
                            previewHtml +=
                                `<span class="color-preview-badge"><span class="color-dot" style="background-color: #6c757d;"></span> ${colorName}</span>`;
                        });
                        $('#selectedColorsPreview').html(previewHtml);
                        $('#colorPreviewSection').show();
                    } else {
                        $('#colorPreviewSection').hide();
                    }
                }
            }

            // ========== SIZES PREVIEW ==========
            function updateSizePreview() {
                if (sizesChoices) {
                    let selectedValues = sizesChoices.getValue(true);
                    if (selectedValues && selectedValues.length > 0) {
                        let previewHtml = '';
                        selectedValues.forEach(function(sizeId) {
                            let sizeName = $('#sizesSelect option[value="' + sizeId + '"]').text();
                            previewHtml += `<span class="size-badge">${sizeName}</span>`;
                        });
                        $('#selectedSizesPreview').html(previewHtml);
                        $('#sizePreviewSection').show();
                    } else {
                        $('#sizePreviewSection').hide();
                    }
                }
            }

            // ========== VARIANTS GENERATION ==========
            function generateVariantsTable(colors, sizes) {
                if (!colors || !sizes || colors.length === 0 || sizes.length === 0) {
                    $('#variantsContainer').html(
                        '<tr><td colspan="6" class="text-center text-muted py-3">Select colors and sizes to generate variants</td></tr>'
                    );
                    return;
                }

                let html = '';
                let variantIndex = 0;
                for (let colorId of colors) {
                    let colorName = $('#colorsSelect option[value="' + colorId + '"]').text();
                    for (let sizeId of sizes) {
                        let sizeName = $('#sizesSelect option[value="' + sizeId + '"]').text();
                        html += `<tr>
                            <td>${colorName}<input type="hidden" name="variants[${variantIndex}][color_id]" value="${colorId}"></td>
                            <td>${sizeName}<input type="hidden" name="variants[${variantIndex}][size_id]" value="${sizeId}"></td>
                            <td><input type="text" class="form-control form-control-sm" name="variants[${variantIndex}][sku]" placeholder="SKU"></td>
                            <td><input type="number" class="form-control form-control-sm" name="variants[${variantIndex}][price]" step="0.01" placeholder="Price"></td>
                            <td><input type="number" class="form-control form-control-sm" name="variants[${variantIndex}][sale_price]" step="0.01" placeholder="Sale Price"></td>
                            <td><input type="number" class="form-control form-control-sm" name="variants[${variantIndex}][stock]" placeholder="Stock" value="0"></td>
                        </tr>`;
                        variantIndex++;
                    }
                }
                $('#variantsContainer').html(html);
            }

            // Listen for changes on Choices instances
            if (colorsChoices) {
                colorsChoices.passedElement.element.addEventListener('change', function() {
                    let colors = colorsChoices.getValue(true);
                    let sizes = sizesChoices ? sizesChoices.getValue(true) : [];
                    updateColorPreview();

                    if (colors && colors.length > 0 && sizes && sizes.length > 0) {
                        generateVariantsTable(colors, sizes);
                        $('#variantsSection').slideDown();
                    } else {
                        $('#variantsSection').slideUp();
                    }
                });
            }

            if (sizesChoices) {
                sizesChoices.passedElement.element.addEventListener('change', function() {
                    let colors = colorsChoices ? colorsChoices.getValue(true) : [];
                    let sizes = sizesChoices.getValue(true);
                    updateSizePreview();

                    if (colors && colors.length > 0 && sizes && sizes.length > 0) {
                        generateVariantsTable(colors, sizes);
                        $('#variantsSection').slideDown();
                    } else {
                        $('#variantsSection').slideUp();
                    }
                });
            }

            // ========== REFRESH BUTTON - FIXED WITH CORRECT ENDPOINTS ==========
            $('#refreshDataBtn').on('click', function() {
                let btn = $(this);
                btn.html('<span class="spinner-border spinner-border-sm me-1"></span> Refreshing...').prop(
                    'disabled', true);

                // Get current selected values
                let currentMainCategory = $('#mainCategory').val();
                let currentColors = colorsChoices ? colorsChoices.getValue(true) : [];
                let currentSizes = sizesChoices ? sizesChoices.getValue(true) : [];

                // Track current selected subcategories
                let currentSubcategories = [];
                $('.subcategory-checkbox:checked').each(function() {
                    currentSubcategories.push($(this).val());
                });

                // Fetch categories
                $.ajax({
                    url: '{{ route('admin.categories.index') }}?list=true',
                    type: 'GET',
                    success: function(categories) {
                        let categorySelect = $('#mainCategory');
                        let currentVal = categorySelect.val();
                        categorySelect.html('<option value="">Select Main Category</option>');
                        if (categories && categories.data && categories.data.length) {
                            categories.data.forEach(function(cat) {
                                categorySelect.append(
                                    `<option value="${cat.id}">${cat.name}</option>`
                                    );
                            });
                        } else if (categories && categories.length) {
                            categories.forEach(function(cat) {
                                categorySelect.append(
                                    `<option value="${cat.id}">${cat.name}</option>`
                                    );
                            });
                        }
                        if (currentMainCategory) categorySelect.val(currentMainCategory);
                    }
                });

                // Fetch colors
                $.ajax({
                    url: '{{ route('admin.colors.index') }}?list=true',
                    type: 'GET',
                    success: function(colors) {
                        if (colorsChoices) colorsChoices.destroy();
                        $('#colorsSelect').html('');
                        let colorsList = colors.data || colors;
                        if (colorsList && colorsList.length) {
                            colorsList.forEach(function(color) {
                                $('#colorsSelect').append(
                                    `<option value="${color.id}">${color.name}</option>`
                                    );
                            });
                        }
                        colorsChoices = new Choices('#colorsSelect', {
                            removeItemButton: true,
                            placeholder: true,
                            placeholderValue: 'Select colors...',
                            searchEnabled: true,
                            shouldSort: false,
                            itemSelectText: '',
                        });
                        if (currentColors && currentColors.length) {
                            colorsChoices.setValue(currentColors);
                        }
                        updateColorPreview();
                    },
                    error: function() {
                        // If route fails, try direct URL
                        $.ajax({
                            url: '/admin/colors?list=true',
                            type: 'GET',
                            success: function(colors) {
                                if (colorsChoices) colorsChoices.destroy();
                                $('#colorsSelect').html('');
                                let colorsList = colors.data || colors;
                                if (colorsList && colorsList.length) {
                                    colorsList.forEach(function(color) {
                                        $('#colorsSelect').append(
                                            `<option value="${color.id}">${color.name}</option>`
                                            );
                                    });
                                }
                                colorsChoices = new Choices('#colorsSelect', {
                                    removeItemButton: true,
                                    placeholder: true,
                                    placeholderValue: 'Select colors...',
                                    searchEnabled: true,
                                    shouldSort: false,
                                    itemSelectText: '',
                                });
                                if (currentColors && currentColors.length) {
                                    colorsChoices.setValue(currentColors);
                                }
                                updateColorPreview();
                            }
                        });
                    }
                });

                // Fetch sizes
                $.ajax({
                    url: '{{ route('admin.sizes.index') }}?list=true',
                    type: 'GET',
                    success: function(sizes) {
                        if (sizesChoices) sizesChoices.destroy();
                        $('#sizesSelect').html('');
                        let sizesList = sizes.data || sizes;
                        if (sizesList && sizesList.length) {
                            sizesList.forEach(function(size) {
                                $('#sizesSelect').append(
                                    `<option value="${size.id}">${size.name}</option>`
                                    );
                            });
                        }
                        sizesChoices = new Choices('#sizesSelect', {
                            removeItemButton: true,
                            placeholder: true,
                            placeholderValue: 'Select sizes...',
                            searchEnabled: true,
                            shouldSort: false,
                            itemSelectText: '',
                        });
                        if (currentSizes && currentSizes.length) {
                            sizesChoices.setValue(currentSizes);
                        }
                        updateSizePreview();
                    },
                    error: function() {
                        // If route fails, try direct URL
                        $.ajax({
                            url: '/admin/sizes?list=true',
                            type: 'GET',
                            success: function(sizes) {
                                if (sizesChoices) sizesChoices.destroy();
                                $('#sizesSelect').html('');
                                let sizesList = sizes.data || sizes;
                                if (sizesList && sizesList.length) {
                                    sizesList.forEach(function(size) {
                                        $('#sizesSelect').append(
                                            `<option value="${size.id}">${size.name}</option>`
                                            );
                                    });
                                }
                                sizesChoices = new Choices('#sizesSelect', {
                                    removeItemButton: true,
                                    placeholder: true,
                                    placeholderValue: 'Select sizes...',
                                    searchEnabled: true,
                                    shouldSort: false,
                                    itemSelectText: '',
                                });
                                if (currentSizes && currentSizes.length) {
                                    sizesChoices.setValue(currentSizes);
                                }
                                updateSizePreview();
                            }
                        });
                    }
                });

                // Refresh attributes if a category is selected
                let currentCategory = $('#mainCategory').val();
                if (currentCategory) {
                    // Get current selected category IDs
                    let categoryIds = [parseInt(currentCategory)];
                    $('.subcategory-checkbox:checked').each(function() {
                        categoryIds.push(parseInt($(this).val()));
                    });
                    setTimeout(function() {
                        if (categoryIds.length > 0) {
                            loadAttributesForCategories(categoryIds);
                        }
                    }, 500);
                }

                setTimeout(function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Refreshed!',
                        text: 'Categories, Colors & Sizes have been refreshed.',
                        timer: 1500,
                        showConfirmButton: false
                    });
                    btn.html('<i class="ti ti-refresh"></i> Refresh Data').prop('disabled', false);
                }, 1000);
            });

            // ========== QUICK ADD MODALS ==========
            $('#saveMainCategoryBtn').on('click', function() {
                let formData = $('#addMainCategoryForm').serialize();
                $.ajax({
                    url: '{{ route('admin.categories.quick-store') }}',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            location.reload();
                        }
                    },
                    error: function(xhr) {
                        let msg = xhr.responseJSON?.message || 'Failed to add category.';
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: msg
                        });
                    }
                });
            });

            $('#saveSubcategoryBtn').on('click', function() {
                let formData = $('#addSubcategoryForm').serialize();
                $.ajax({
                    url: '{{ route('admin.categories.quick-store') }}',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            let parentId = $('#subcategoryParentId').val();
                            let parentName = $('#parentCategoryName').val();
                            loadChildCategories(parentId, 0, parentName);
                            $('#addSubcategoryModal').modal('hide');
                            $('#addSubcategoryForm')[0].reset();
                            Swal.fire({
                                icon: 'success',
                                title: 'Added!',
                                text: 'Subcategory added.',
                                timer: 1500,
                                showConfirmButton: false
                            });
                        }
                    },
                    error: function(xhr) {
                        let msg = xhr.responseJSON?.message || 'Failed to add subcategory.';
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: msg
                        });
                    }
                });
            });

            $('#saveColorBtn').on('click', function() {
                let formData = $('#addColorForm').serialize();
                $.ajax({
                    url: '{{ route('admin.colors.quick-store') }}',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            location.reload();
                        }
                    },
                    error: function(xhr) {
                        let msg = xhr.responseJSON?.message || 'Failed to add color.';
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: msg
                        });
                    }
                });
            });

            $('#saveSizeBtn').on('click', function() {
                let formData = $('#addSizeForm').serialize();
                $.ajax({
                    url: '{{ route('admin.sizes.quick-store') }}',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            location.reload();
                        }
                    },
                    error: function(xhr) {
                        let msg = xhr.responseJSON?.message || 'Failed to add size.';
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: msg
                        });
                    }
                });
            });

            $('#saveAttributeBtn').on('click', function() {
                let formData = $('#addAttributeForm').serialize();
                $.ajax({
                    url: '{{ route('admin.attributes.quick-store') }}',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            $('#addAttributeModal').modal('hide');
                            $('#addAttributeForm')[0].reset();
                            if (selectedCategoryIds.length > 0) {
                                loadAttributesForCategories(selectedCategoryIds);
                            }
                            Swal.fire({
                                icon: 'success',
                                title: 'Added!',
                                text: 'Attribute added.',
                                timer: 1500,
                                showConfirmButton: false
                            });
                        }
                    },
                    error: function(xhr) {
                        let msg = xhr.responseJSON?.message || 'Failed to add attribute.';
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: msg
                        });
                    }
                });
            });

            $(document).on('click', '.add-value-btn', function() {
                let attributeId = $(this).data('attribute-id');
                let attributeName = $(this).data('attribute-name');
                let attributeType = $(this).data('attribute-type');
                $('#attributeIdForValue').val(attributeId);
                $('#addAttributeValueModal .modal-title').text(`Add Value to ${attributeName}`);
                if (attributeType === 'color') {
                    $('#colorCodeField').show();
                } else {
                    $('#colorCodeField').hide();
                }
                $('#addAttributeValueModal').modal('show');
            });

            $('#saveAttributeValueBtn').on('click', function() {
                let formData = $('#addAttributeValueForm').serialize();
                $.ajax({
                    url: '{{ route('admin.attribute-values.quick-store') }}',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            $('#addAttributeValueModal').modal('hide');
                            $('#addAttributeValueForm')[0].reset();
                            if (selectedCategoryIds.length > 0) {
                                loadAttributesForCategories(selectedCategoryIds);
                            }
                            Swal.fire({
                                icon: 'success',
                                title: 'Added!',
                                text: 'Value added.',
                                timer: 1500,
                                showConfirmButton: false
                            });
                        }
                    },
                    error: function(xhr) {
                        let msg = xhr.responseJSON?.message || 'Failed to add value.';
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: msg
                        });
                    }
                });
            });

            // ========== PRICING & STOCK ==========
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
                let newRow =
                    `<div class="row tier-price-row mb-2"><div class="col-md-3"><input type="number" class="form-control" name="tier_prices[${tierIndex}][min_quantity]" placeholder="Min Quantity" step="1"></div><div class="col-md-3"><input type="number" class="form-control" name="tier_prices[${tierIndex}][max_quantity]" placeholder="Max Quantity" step="1"></div><div class="col-md-3"><input type="number" class="form-control" name="tier_prices[${tierIndex}][price]" placeholder="Price" step="0.01"></div><div class="col-md-3"><button type="button" class="btn btn-danger btn-sm remove-tier">Remove</button></div></div>`;
                $('#tieredPricesContainer').append(newRow);
                tierIndex++;
            });

            $(document).on('click', '.remove-tier', function() {
                $(this).closest('.tier-price-row').remove();
            });

            // Character counters
            $('#metaTitle').on('keyup', function() {
                $('#metaTitleCount').text($(this).val().length + '/70 characters');
            });
            $('#metaDescription').on('keyup', function() {
                $('#metaDescCount').text($(this).val().length + '/160 characters');
            });

            // Form submission
            $('#productForm').on('submit', function(e) {
                e.preventDefault();
                $('#description').val(quill.root.innerHTML);
                let btn = $('#submitBtn');
                btn.html('<span class="spinner-border spinner-border-sm me-1"></span> Creating...').prop(
                    'disabled', true);

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
                    url: '{{ route('admin.products.store') }}',
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
                                })
                                .then(() => window.location.href =
                                    '{{ route('admin.products.index') }}');
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
                                text: xhr.responseJSON?.message ||
                                    'Something went wrong.'
                            });
                        }
                        btn.html('Create Product').prop('disabled', false);
                    }
                });
            });
        });
    </script>
@endpush
