{{-- resources/views/marketplace/pages/products/create.blade.php --}}
@extends('management.layouts.app')

@section('title', 'Add New Product')

@section('content')
    <div class="page-content">
        <div class="page-container">
            <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                <div class="flex-grow-1">
                    <h4 class="fs-18 text-uppercase fw-bold mb-0">Add New Product</h4>
                </div>
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('vendor.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('vendor.products.index') }}">Products</a></li>
                        <li class="breadcrumb-item active">Add Product</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form id="productForm" enctype="multipart/form-data">
                                @csrf

                                {{-- Tabs --}}
                                <ul class="nav nav-tabs" id="productTabs" role="tablist">
                                    <li class="nav-item"><button class="nav-link active" id="basic-tab" data-bs-toggle="tab"
                                            data-bs-target="#basic" type="button"><i class="ti ti-info-circle"></i> Basic
                                            Info</button></li>
                                    <li class="nav-item"><button class="nav-link" id="categories-tab" data-bs-toggle="tab"
                                            data-bs-target="#categories" type="button"><i class="ti ti-folder"></i>
                                            Categories</button></li>
                                    <li class="nav-item"><button class="nav-link" id="pricing-tab" data-bs-toggle="tab"
                                            data-bs-target="#pricing" type="button"><i class="ti ti-currency-dollar"></i>
                                            Pricing & Stock</button></li>
                                    <li class="nav-item"><button class="nav-link" id="media-tab" data-bs-toggle="tab"
                                            data-bs-target="#media" type="button"><i class="ti ti-photo"></i>
                                            Media</button></li>
                                    <li class="nav-item"><button class="nav-link" id="variants-tab" data-bs-toggle="tab"
                                            data-bs-target="#variants" type="button"><i class="ti ti-color-swatch"></i>
                                            Variants</button></li>
                                    <li class="nav-item"><button class="nav-link" id="attributes-tab" data-bs-toggle="tab"
                                            data-bs-target="#attributes" type="button"><i class="ti ti-list"></i>
                                            Attributes</button></li>
                                    <li class="nav-item"><button class="nav-link" id="tiers-tab" data-bs-toggle="tab"
                                            data-bs-target="#tiers" type="button"><i class="ti ti-chart-line"></i> Tier
                                            Pricing</button></li>
                                    <li class="nav-item"><button class="nav-link" id="seo-tab" data-bs-toggle="tab"
                                            data-bs-target="#seo" type="button"><i class="ti ti-meta-tag"></i> SEO &
                                            Social</button></li>
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
                                                        placeholder="e.g., Apple MacBook Pro 2024" autofocus>
                                                    <div class="invalid-feedback" id="name-error"></div>
                                                    <small class="text-muted">URL slug: <span id="slug-preview"
                                                            class="text-primary"></span></small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="sku" class="form-label">SKU</label>
                                                    <input type="text" class="form-control" id="sku"
                                                        name="sku" placeholder="Unique identifier">
                                                    <div class="invalid-feedback" id="sku-error"></div>
                                                    <small class="text-muted">Leave empty to auto-generate</small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="barcode" class="form-label">Barcode</label>
                                                    <input type="text" class="form-control" id="barcode"
                                                        name="barcode" placeholder="Product barcode">
                                                    <div class="invalid-feedback" id="barcode-error"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="sort_order" class="form-label">Sort Order</label>
                                                    <input type="number" class="form-control" id="sort_order"
                                                        name="sort_order" value="0">
                                                    <div class="invalid-feedback" id="sort_order-error"></div>
                                                    <small class="text-muted">Lower numbers appear first</small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Product Flags</label>
                                                    <div class="d-flex gap-3">
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input"
                                                                id="is_featured" name="is_featured" value="1">
                                                            <label class="form-check-label" for="is_featured"><i
                                                                    class="ti ti-star"></i> Featured</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input"
                                                                id="is_bestseller" name="is_bestseller" value="1">
                                                            <label class="form-check-label" for="is_bestseller"><i
                                                                    class="ti ti-trending-up"></i> Bestseller</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input"
                                                                id="is_new" name="is_new" value="1" checked>
                                                            <label class="form-check-label" for="is_new"><i
                                                                    class="ti ti-spark"></i> New</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input"
                                                                id="status" name="status" value="1" checked>
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
                                                                    <option value="{{ $tag->id }}">{{ $tag->name }}
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
                                                    <div class="invalid-feedback" id="tags-error"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="short_description" class="form-label">Short
                                                        Description</label>
                                                    <textarea class="form-control" id="short_description" name="short_description" rows="2"
                                                        placeholder="Brief description (appears in listings)"></textarea>
                                                    <div class="invalid-feedback" id="short_description-error"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="description" class="form-label">Full Description</label>
                                                    <textarea class="form-control" id="description" name="description" rows="5"
                                                        placeholder="Detailed product description"></textarea>
                                                    <div class="invalid-feedback" id="description-error"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="highlights" class="form-label">Highlights (Bullet
                                                        Points)</label>
                                                    <div id="highlights-container">
                                                        <div class="input-group mb-2 highlight-row">
                                                            <input type="text" class="form-control"
                                                                name="highlights[]"
                                                                placeholder="e.g., 16GB RAM, 512GB SSD">
                                                            <button type="button"
                                                                class="btn btn-danger remove-highlight"><i
                                                                    class="ti ti-trash"></i></button>
                                                        </div>
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
                                                    <div class="card-header bg-light">
                                                        <h6 class="mb-0"><i class="ti ti-star text-warning"></i> Primary
                                                            Category <span class="text-danger">*</span></h6>
                                                    </div>
                                                    <div class="card-body">
                                                        <select class="form-control select2-single"
                                                            id="primary_category_id" name="primary_category_id"
                                                            style="width: 100%;">
                                                            <option value="">-- Select Primary Category --</option>
                                                            @foreach ($parentCategories as $cat)
                                                                <option value="{{ $cat->id }}">{{ $cat->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <div class="invalid-feedback" id="primary_category_id-error">
                                                        </div>
                                                        <small class="text-muted">This is the main category for SEO and URL
                                                            structure</small>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="card">
                                                    <div class="card-header bg-light">
                                                        <h6 class="mb-0"><i class="ti ti-folder-plus"></i> Additional
                                                            Categories (Multi-Level)</h6>
                                                    </div>
                                                    <div class="card-body">
                                                        <div id="category-levels-container">
                                                            <div class="alert alert-secondary mb-0">Select a primary
                                                                category first to add additional categories</div>
                                                        </div>
                                                        <input type="hidden" name="additional_categories"
                                                            id="selected-additional-categories" value="">
                                                        <div class="invalid-feedback" id="additional_categories-error">
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
                                                    <div class="input-group"><span class="input-group-text">$</span><input
                                                            type="number" step="0.01" class="form-control"
                                                            id="price" name="price" placeholder="0.00"></div>
                                                    <div class="invalid-feedback" id="price-error"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="compare_price" class="form-label">Compare at Price</label>
                                                    <div class="input-group"><span class="input-group-text">$</span><input
                                                            type="number" step="0.01" class="form-control"
                                                            id="compare_price" name="compare_price" placeholder="0.00">
                                                    </div>
                                                    <div class="invalid-feedback" id="compare_price-error"></div>
                                                    <small class="text-muted">Old price for sale display</small>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="cost" class="form-label">Cost (Your cost)</label>
                                                    <div class="input-group"><span class="input-group-text">$</span><input
                                                            type="number" step="0.01" class="form-control"
                                                            id="cost" name="cost" placeholder="0.00"></div>
                                                    <div class="invalid-feedback" id="cost-error"></div>
                                                    <small class="text-muted">Your purchase cost</small>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="wholesale_price" class="form-label">Wholesale
                                                        Price</label>
                                                    <div class="input-group"><span class="input-group-text">$</span><input
                                                            type="number" step="0.01" class="form-control"
                                                            id="wholesale_price" name="wholesale_price"
                                                            placeholder="0.00"></div>
                                                    <div class="invalid-feedback" id="wholesale_price-error"></div>
                                                    <div class="form-check mt-1"><input type="checkbox"
                                                            class="form-check-input" id="is_wholesale"
                                                            name="is_wholesale" value="1"><label
                                                            class="form-check-label" for="is_wholesale">Enable wholesale
                                                            pricing</label></div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="min_price" class="form-label">Minimum Price (for
                                                        range)</label>
                                                    <div class="input-group"><span class="input-group-text">$</span><input
                                                            type="number" step="0.01" class="form-control"
                                                            id="min_price" name="min_price" placeholder="0.00"></div>
                                                    <div class="invalid-feedback" id="min_price-error"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="max_price" class="form-label">Maximum Price (for
                                                        range)</label>
                                                    <div class="input-group"><span class="input-group-text">$</span><input
                                                            type="number" step="0.01" class="form-control"
                                                            id="max_price" name="max_price" placeholder="0.00"></div>
                                                    <div class="invalid-feedback" id="max_price-error"></div>
                                                    <div class="form-check mt-1"><input type="checkbox"
                                                            class="form-check-input" id="is_range" name="is_range"
                                                            value="1"><label class="form-check-label"
                                                            for="is_range">This product has a price range</label></div>
                                                </div>
                                            </div>
                                        </div>

                                        <hr>
                                        <h6>Stock & Inventory</h6>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="mb-3"><label for="stock_quantity" class="form-label">Stock
                                                        Quantity</label><input type="number" class="form-control"
                                                        id="stock_quantity" name="stock_quantity" value="0">
                                                    <div class="invalid-feedback" id="stock_quantity-error"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="mb-3"><label for="low_stock_threshold"
                                                        class="form-label">Low Stock Threshold</label><input
                                                        type="number" class="form-control" id="low_stock_threshold"
                                                        name="low_stock_threshold" value="5">
                                                    <div class="invalid-feedback" id="low_stock_threshold-error"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="mb-3"><label for="stock_status" class="form-label">Stock
                                                        Status</label><select class="form-select" id="stock_status"
                                                        name="stock_status">
                                                        <option value="instock">In Stock</option>
                                                        <option value="outofstock">Out of Stock</option>
                                                        <option value="backorder">Backorder</option>
                                                    </select>
                                                    <div class="invalid-feedback" id="stock_status-error"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="mt-4">
                                                    <div class="form-check"><input type="checkbox"
                                                            class="form-check-input" id="track_stock" name="track_stock"
                                                            value="1" checked><label class="form-check-label"
                                                            for="track_stock">Track stock</label></div>
                                                    <div class="form-check"><input type="checkbox"
                                                            class="form-check-input" id="allow_backorder"
                                                            name="allow_backorder" value="1"><label
                                                            class="form-check-label" for="allow_backorder">Allow
                                                            backorders</label></div>
                                                </div>
                                            </div>
                                        </div>

                                        <hr>
                                        <h6>Shipping Details</h6>
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="mb-3"><label for="weight" class="form-label">Weight
                                                        (kg)</label><input type="number" step="0.01"
                                                        class="form-control" id="weight" name="weight"></div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="mb-3"><label for="length" class="form-label">Length
                                                        (cm)</label><input type="number" step="0.01"
                                                        class="form-control" id="length" name="length"></div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="mb-3"><label for="width" class="form-label">Width
                                                        (cm)</label><input type="number" step="0.01"
                                                        class="form-control" id="width" name="width"></div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="mb-3"><label for="height" class="form-label">Height
                                                        (cm)</label><input type="number" step="0.01"
                                                        class="form-control" id="height" name="height"></div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-check mt-4"><input type="checkbox"
                                                        class="form-check-input" id="free_shipping" name="free_shipping"
                                                        value="1"><label class="form-check-label"
                                                        for="free_shipping">Free Shipping</label></div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Media Tab --}}
                                    <div class="tab-pane fade" id="media" role="tabpanel">
                                        <div class="alert alert-info mb-3"><i class="ti ti-info-circle"></i> Images are
                                            automatically compressed. First image will be the main product image.</div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="card">
                                                    <div class="card-header bg-light">
                                                        <h6 class="mb-0">Product Images</h6>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="mb-3"><input type="file" class="form-control"
                                                                id="images_input" name="images[]" accept="image/*"
                                                                multiple>
                                                            <div class="invalid-feedback" id="images-error"></div><small
                                                                class="text-muted">Select multiple images (Ctrl+Click or
                                                                Shift+Click)</small>
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
                                                            <div class="video-row row mb-2">
                                                                <div class="col-md-5"><input type="url"
                                                                        class="form-control" name="videos[0][url]"
                                                                        placeholder="Video URL"></div>
                                                                <div class="col-md-5"><input type="text"
                                                                        class="form-control" name="videos[0][title]"
                                                                        placeholder="Title"></div>
                                                                <div class="col-md-2"><button type="button"
                                                                        class="btn btn-danger remove-video" disabled><i
                                                                            class="ti ti-trash"></i></button></div>
                                                            </div>
                                                        </div>
                                                        <button type="button" class="btn btn-sm btn-secondary"
                                                            id="add-video"><i class="ti ti-plus"></i> Add Video</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Variants Tab --}}
                                    <div class="tab-pane fade" id="variants" role="tabpanel">
                                        <div class="alert alert-info mb-3"><i class="ti ti-info-circle"></i> Create
                                            product variants based on color and size combinations.</div>
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
                                                <tbody id="variants-tbody"></tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td colspan="10"><button type="button"
                                                                class="btn btn-sm btn-primary" id="add-variant"><i
                                                                    class="ti ti-plus"></i> Add Variant</button></td>
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
                                                    <div class="alert alert-secondary">Select categories first to see
                                                        relevant attributes.</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Tier Pricing Tab --}}
                                    <div class="tab-pane fade" id="tiers" role="tabpanel">
                                        <div class="alert alert-info"><i class="ti ti-info-circle"></i> Quantity-based
                                            discounts. Leave blank if not needed.</div>
                                        <div id="tiers-container">
                                            <table class="table table-bordered" id="tiers-table">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Min Quantity</th>
                                                        <th>Max Quantity</th>
                                                        <th>Price</th>
                                                        <th style="width:50px"></th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tiers-tbody"></tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td colspan="4"><button type="button"
                                                                class="btn btn-sm btn-primary" id="add-tier"><i
                                                                    class="ti ti-plus"></i> Add Tier</button></td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>

                                    {{-- SEO & Social Tab --}}
                                    <div class="tab-pane fade" id="seo" role="tabpanel">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="mb-3"><label for="meta_title" class="form-label">Meta
                                                        Title</label><input type="text" class="form-control"
                                                        id="meta_title" name="meta_title"
                                                        placeholder="SEO title (50-60 characters)">
                                                    <div class="invalid-feedback" id="meta_title-error"></div><small
                                                        class="text-muted" id="metaTitleCount">0/70 characters</small>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="mb-3"><label for="meta_description" class="form-label">Meta
                                                        Description</label>
                                                    <textarea class="form-control" id="meta_description" name="meta_description" rows="2"
                                                        placeholder="SEO description (150-160 characters)"></textarea>
                                                    <div class="invalid-feedback" id="meta_description-error"></div><small
                                                        class="text-muted" id="metaDescCount">0/160 characters</small>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="mb-3"><label for="meta_keywords" class="form-label">Meta
                                                        Keywords</label><input type="text" class="form-control"
                                                        id="meta_keywords" name="meta_keywords"
                                                        placeholder="keyword1, keyword2">
                                                    <div class="invalid-feedback" id="meta_keywords-error"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="mb-3"><label for="focus_keyword" class="form-label">Focus
                                                        Keyword</label><input type="text" class="form-control"
                                                        id="focus_keyword" name="focus_keyword"
                                                        placeholder="Main keyword for SEO">
                                                    <div class="invalid-feedback" id="focus_keyword-error"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="mb-3"><label for="canonical_url"
                                                        class="form-label">Canonical URL</label><input type="text"
                                                        class="form-control" id="canonical_url" name="canonical_url"
                                                        placeholder="https://example.com/canonical">
                                                    <div class="invalid-feedback" id="canonical_url-error"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <hr>
                                                <h6>Open Graph (Social Sharing)</h6>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3"><label for="og_title" class="form-label">OG
                                                        Title</label><input type="text" class="form-control"
                                                        id="og_title" name="og_title" placeholder="Social title">
                                                    <div class="invalid-feedback" id="og_title-error"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3"><label for="og_image" class="form-label">OG
                                                        Image</label><input type="file" class="form-control"
                                                        id="og_image" name="og_image" accept="image/*">
                                                    <div id="ogImagePreview" class="mt-2"></div>
                                                    <div class="invalid-feedback" id="og_image-error"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="mb-3"><label for="og_description" class="form-label">OG
                                                        Description</label>
                                                    <textarea class="form-control" id="og_description" name="og_description" rows="2"
                                                        placeholder="Social description"></textarea>
                                                    <div class="invalid-feedback" id="og_description-error"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card border mt-3">
                                            <div class="card-header bg-light">
                                                <h6 class="mb-0">SEO Preview (Google)</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="text-primary fw-bold fs-5" id="seo-preview-title">Product Name
                                                </div>
                                                <div class="text-muted small" id="seo-preview-url">
                                                    {{ url('/product') }}/product-slug</div>
                                                <div class="text-muted small mt-2" id="seo-preview-desc">Product
                                                    description will appear here...</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2 mt-4">
                                    <a href="{{ route('vendor.products.index') }}" class="btn btn-danger"><i
                                            class="ti ti-x"></i> Cancel</a>
                                    <button type="submit" class="btn btn-primary" id="submitBtn"><i
                                            class="ti ti-plus"></i> Create Product</button>
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
                width: '100%',
                placeholder: 'Select option',
            });

            let choicesInstances = [];
            let selectedCategoriesByLevel = {
                0: []
            };

            const tagsSelect = document.getElementById('tags_select');
            if (tagsSelect) {
                const tagsChoices = new Choices(tagsSelect, {
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
                    addItemFilter: null,
                    removeItemButton: true,
                });
            }
            let variantCounter = 0;

            // Slug preview
            $('#name').on('keyup', function() {
                let slug = $(this).val().toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
                $('#slug-preview').text(slug || 'product-slug');
                updateSEOPreview();
                $(this).removeClass('is-invalid');
                $('#name-error').text('');
            });

            // Function to load brands based on selected primary category only
            function loadBrandsByPrimaryCategory() {
                let primaryId = $('#primary_category_id').val();

                if (!primaryId) {
                    $('#brand_id').empty().append('<option value="">-- Select Brand --</option>');
                    $('#brand_id').trigger('change');
                    return;
                }

                $('#brand_id').empty().append('<option value="">Loading brands...</option>');
                $('#brand_id').prop('disabled', true);

                // Use the correct route name - match what you defined in routes
                $.ajax({
                    url: '{{ url('marketplace/brands/by-category') }}/' + primaryId,
                    type: 'GET',
                    success: function(response) {
                        $('#brand_id').prop('disabled', false);

                        if (response.success && response.brands.length > 0) {
                            let options = '<option value="">-- Select Brand --</option>';
                            response.brands.forEach(brand => {
                                options +=
                                    `<option value="${brand.id}">${brand.name} ${brand.code ? '(' + brand.code + ')' : ''}</option>`;
                            });
                            $('#brand_id').html(options);
                        } else {
                            $('#brand_id').html(
                                '<option value="">-- No brands available for selected category --</option>'
                            );
                        }
                        $('#brand_id').trigger('change');
                    },
                    error: function(xhr) {
                        console.error('Failed to load brands:', xhr);
                        $('#brand_id').prop('disabled', false);
                        $('#brand_id').html('<option value="">-- Error loading brands --</option>');
                    }
                });
            }

            // Load subcategories function
            function loadSubcategories(parentIds, level) {
                if (!parentIds || parentIds.length === 0) return;

                let promises = [];
                let allSubcategories = [];

                parentIds.forEach(parentId => {
                    promises.push($.ajax({
                        url: '{{ url('marketplace/categories') }}/' + parentId +
                            '/subcategories',
                        type: 'GET',
                        dataType: 'json'
                    }));
                });

                $.when.apply($, promises).done(function(...responses) {
                    let responseArray = responses;
                    if (responses.length === 1 && !Array.isArray(responses[0])) {
                        responseArray = [responses];
                    }

                    responseArray.forEach(response => {
                        let data = response[0] || response;
                        if (data && data.subcategories && data.subcategories.length) {
                            data.subcategories.forEach(cat => {
                                if (!allSubcategories.some(c => c.id === cat.id)) {
                                    allSubcategories.push(cat);
                                }
                            });
                        }
                    });

                    if (allSubcategories.length > 0) {
                        $(`.category-level[data-level="${level}"]`).remove();
                        $(`.category-level[data-level="${level + 1}"]`).remove();

                        if (choicesInstances[level]) choicesInstances[level].destroy();

                        let options = '';
                        allSubcategories.forEach(cat => {
                            options += `<option value="${cat.id}">${cat.name}</option>`;
                        });

                        let levelHtml = `
                    <div class="category-level mt-3 p-3 bg-light rounded border" data-level="${level}">
                        <label class="form-label fw-semibold">Level ${level} Categories (Select Multiple)</label>
                        <select class="form-control category-multi-select" data-level="${level}" multiple>
                            ${options}
                        </select>
                        <small class="text-muted mt-1">You can select multiple categories</small>
                    </div>
                `;
                        $('#category-levels-container').append(levelHtml);

                        const selectElement = $(`.category-multi-select[data-level="${level}"]`)[0];
                        if (selectElement) {
                            let choices = new Choices(selectElement, {
                                removeItemButton: true,
                                removeItems: true,
                                duplicateItemsAllowed: false,
                                placeholder: true,
                                placeholderValue: `Select level ${level} categories`,
                                searchEnabled: true,
                                searchChoices: true,
                                searchResultLimit: 20,
                                shouldSort: true,
                                itemSelectText: '',
                            });
                            choicesInstances[level] = choices;

                            if (selectedCategoriesByLevel[level] && selectedCategoriesByLevel[level]
                                .length) {
                                choices.setValue(selectedCategoriesByLevel[level]);
                            }

                            $(selectElement).on('change', function() {
                                let selectedValues = $(this).val() || [];
                                selectedCategoriesByLevel[level] = selectedValues;

                                $(`.category-level[data-level="${level + 1}"]`).remove();
                                if (choicesInstances[level + 1]) {
                                    choicesInstances[level + 1].destroy();
                                    delete choicesInstances[level + 1];
                                }
                                delete selectedCategoriesByLevel[level + 1];

                                if (selectedValues && selectedValues.length > 0) {
                                    loadSubcategories(selectedValues, level + 1);
                                }

                                updateSelectedCategories();
                                loadAttributesForSelectedCategories();
                            });
                        }
                    }

                    updateSelectedCategories();
                    loadAttributesForSelectedCategories();
                }).fail(function(error) {
                    console.error('Failed to load subcategories:', error);
                });
            }

            // Primary category change
            $('#primary_category_id').on('change', function() {
                let categoryId = $(this).val();

                $('#category-levels-container').empty();
                choicesInstances.forEach(instance => {
                    if (instance && instance.destroy) instance.destroy();
                });
                choicesInstances = [];
                selectedCategoriesByLevel = {
                    0: []
                };

                if (categoryId && categoryId !== '') {
                    selectedCategoriesByLevel[0] = [parseInt(categoryId)];
                    loadSubcategories([parseInt(categoryId)], 1);
                    $(this).removeClass('is-invalid');
                    $('#primary_category_id-error').text('');
                    // Load brands when primary category changes
                    loadBrandsByPrimaryCategory();
                } else {
                    $('#category-levels-container').html(
                        '<div class="alert alert-secondary mb-0">Select a primary category first to add additional categories</div>'
                    );
                    // Clear brands when no primary category
                    $('#brand_id').empty().append('<option value="">-- Select Brand --</option>');
                }

                updateSelectedCategories();
                loadAttributesForSelectedCategories();
            });

            function updateSelectedCategories() {
                let allIds = [];
                let primaryId = $('#primary_category_id').val();
                if (primaryId) allIds.push(parseInt(primaryId));

                for (let level in selectedCategoriesByLevel) {
                    if (selectedCategoriesByLevel.hasOwnProperty(level) && level != 0) {
                        selectedCategoriesByLevel[level].forEach(id => {
                            if (!allIds.includes(parseInt(id))) allIds.push(parseInt(id));
                        });
                    }
                }

                $('#selected-additional-categories').val(JSON.stringify(allIds));
            }

            function loadAttributesForSelectedCategories() {
                let categoryIds = [];
                let primaryId = $('#primary_category_id').val();
                if (primaryId) categoryIds.push(parseInt(primaryId));

                for (let level in selectedCategoriesByLevel) {
                    if (selectedCategoriesByLevel.hasOwnProperty(level) && level != 0) {
                        selectedCategoriesByLevel[level].forEach(id => {
                            if (!categoryIds.includes(parseInt(id))) categoryIds.push(parseInt(id));
                        });
                    }
                }

                if (categoryIds.length > 0) {
                    $.ajax({
                        url: '{{ route('vendor.attributes.by-categories') }}',
                        type: 'POST',
                        data: {
                            category_ids: categoryIds,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            renderAttributes(response.attributes);
                        },
                        error: function() {
                            $('#attributes-container').html(
                                '<div class="alert alert-danger">Failed to load attributes.</div>');
                        }
                    });
                } else {
                    $('#attributes-container').html(
                        '<div class="alert alert-secondary">Select categories first to see relevant attributes.</div>'
                    );
                }
            }

            function renderAttributes(attributes) {
                if (!attributes || !attributes.length) {
                    $('#attributes-container').html(
                        '<div class="alert alert-secondary">No attributes found for selected categories.</div>');
                    return;
                }

                let html = '';
                attributes.forEach(attr => {
                    html +=
                        `<div class="mb-3" data-attr-id="${attr.id}">
                <label class="form-label">${attr.name} ${attr.is_required ? '<span class="text-danger">*</span>' : ''}</label>`;

                    if (attr.type === 'text' || attr.type === 'textarea' || attr.type === 'number') {
                        html +=
                            `<input type="${attr.type === 'textarea' ? 'textarea' : 'text'}" class="form-control attribute-input" data-attr-id="${attr.id}" name="attributes[${attr.id}]" placeholder="${attr.placeholder || ''}">`;
                    } else if (attr.type === 'select') {
                        html +=
                            `<select class="form-select attribute-select" data-attr-id="${attr.id}" name="attributes[${attr.id}]">`;
                        html += `<option value="">-- Select ${attr.name} --</option>`;
                        if (attr.values && attr.values.length) {
                            attr.values.forEach(v => {
                                html +=
                                    `<option value="${v.value}">${v.display_name || v.label || v.value}</option>`;
                            });
                        }
                        html += `</select>`;
                    } else if (attr.type === 'multiselect') {
                        html +=
                            `<select class="form-select attribute-multiselect" data-attr-id="${attr.id}" name="attributes[${attr.id}][]" multiple>`;
                        if (attr.values && attr.values.length) {
                            attr.values.forEach(v => {
                                html +=
                                    `<option value="${v.value}">${v.display_name || v.label || v.value}</option>`;
                            });
                        }
                        html += `</select>`;
                        html += `<small class="text-muted">Hold Ctrl to select multiple options</small>`;
                    } else if (attr.type === 'color') {
                        html +=
                            `<input type="color" class="form-control attribute-color" data-attr-id="${attr.id}" name="attributes[${attr.id}]" value="#000000">`;
                    } else if (attr.type === 'checkbox') {
                        if (attr.values && attr.values.length) {
                            attr.values.forEach(v => {
                                html += `<div class="form-check">
                            <input type="checkbox" class="form-check-input attribute-checkbox" data-attr-id="${attr.id}" name="attributes[${attr.id}][]" value="${v.value}" id="attr_${attr.id}_${v.value}">
                            <label class="form-check-label" for="attr_${attr.id}_${v.value}">${v.display_name || v.label || v.value}</label>
                        </div>`;
                            });
                        }
                    } else if (attr.type === 'radio') {
                        if (attr.values && attr.values.length) {
                            attr.values.forEach(v => {
                                html += `<div class="form-check">
                            <input type="radio" class="form-check-input attribute-radio" data-attr-id="${attr.id}" name="attributes[${attr.id}]" value="${v.value}" id="attr_${attr.id}_${v.value}">
                            <label class="form-check-label" for="attr_${attr.id}_${v.value}">${v.display_name || v.label || v.value}</label>
                        </div>`;
                            });
                        }
                    } else if (attr.type === 'date') {
                        html +=
                            `<input type="date" class="form-control attribute-date" data-attr-id="${attr.id}" name="attributes[${attr.id}]">`;
                    } else {
                        html +=
                            `<input type="text" class="form-control attribute-input" data-attr-id="${attr.id}" name="attributes[${attr.id}]" placeholder="Enter ${attr.name}">`;
                    }

                    if (attr.help_text) html += `<small class="text-muted">${attr.help_text}</small>`;
                    html += `<div class="invalid-feedback" id="attributes_${attr.id}-error"></div>`;
                    html += `</div>`;
                });
                $('#attributes-container').html(html);
            }

            // Add Variant
            $('#add-variant').click(function() {
                let colors = @json($colors);
                let sizes = @json($sizes);
                let colorOptions = '<option value="">-- Select --</option>' + colors.map(c =>
                    `<option value="${c.id}">${c.name}</option>`).join('');
                let sizeOptions = '<option value="">-- Select --</option>' + sizes.map(s =>
                    `<option value="${s.id}">${s.name}</option>`).join('');

                let newRow = `
            <tr class="variant-row" data-index="${variantCounter}">
                <td><select class="form-select form-select-sm" name="variants[${variantCounter}][color_id]">${colorOptions}</select></td>
                <td><select class="form-select form-select-sm" name="variants[${variantCounter}][size_id]">${sizeOptions}</select></td>
                <td><input type="text" class="form-control form-control-sm" name="variants[${variantCounter}][sku]" placeholder="SKU"></td>
                <td><input type="number" step="0.01" class="form-control form-control-sm" name="variants[${variantCounter}][price]" placeholder="Price"></td>
                <td><input type="number" step="0.01" class="form-control form-control-sm" name="variants[${variantCounter}][compare_price]" placeholder="Compare price"></td>
                <td><input type="number" step="0.01" class="form-control form-control-sm" name="variants[${variantCounter}][wholesale_price]" placeholder="Wholesale"></td>
                <td><input type="number" class="form-control form-control-sm" name="variants[${variantCounter}][stock_quantity]" placeholder="Stock" value="0"></td>
                <td>
                    <input type="file" class="form-control form-control-sm variant-image" data-index="${variantCounter}" name="variants[${variantCounter}][image]" accept="image/*">
                    <div class="variant-image-preview mt-1" id="variant-preview-${variantCounter}" style="display: none;">
                        <img src="" style="max-width: 50px; max-height: 50px;" class="rounded">
                    </div>
                </td>
                <td><input type="text" class="form-control form-control-sm" name="variants[${variantCounter}][image_alt]" placeholder="Alt text"></td>
                <td><button type="button" class="btn btn-sm btn-danger remove-variant"><i class="ti ti-trash"></i></button></td>
            </tr>
        `;
                $('#variants-tbody').append(newRow);
                variantCounter++;
            });

            // Variant image preview
            $(document).on('change', '.variant-image', function() {
                let index = $(this).data('index');
                let file = this.files[0];
                if (file) {
                    let reader = new FileReader();
                    reader.onload = function(e) {
                        $(`#variant-preview-${index}`).show().find('img').attr('src', e.target.result);
                    };
                    reader.readAsDataURL(file);
                }
            });

            $(document).on('click', '.remove-variant', function() {
                $(this).closest('tr').remove();
            });

            // Tier pricing
            let tierCount = 0;
            $('#add-tier').click(function() {
                let newRow = `<tr class="tier-row">
            <td><input type="number" class="form-control form-control-sm" name="tier_prices[${tierCount}][min_quantity]" placeholder="Min"></td>
            <td><input type="number" class="form-control form-control-sm" name="tier_prices[${tierCount}][max_quantity]" placeholder="Max"></td>
            <td><input type="number" step="0.01" class="form-control form-control-sm" name="tier_prices[${tierCount}][price]" placeholder="Price"></td>
            <td><button type="button" class="btn btn-sm btn-danger remove-tier"><i class="ti ti-trash"></i></button></td>
        </tr>`;
                $('#tiers-tbody').append(newRow);
                tierCount++;
            });
            $(document).on('click', '.remove-tier', function() {
                $(this).closest('tr').remove();
            });

            // Highlights
            $('#add-highlight').click(function() {
                let newRow =
                    `<div class="input-group mb-2 highlight-row"><input type="text" class="form-control" name="highlights[]" placeholder="e.g., 16GB RAM"><button type="button" class="btn btn-danger remove-highlight"><i class="ti ti-trash"></i></button></div>`;
                $('#highlights-container').append(newRow);
            });
            $(document).on('click', '.remove-highlight', function() {
                $(this).closest('.highlight-row').remove();
            });

            // Videos
            let videoCount = 1;
            $('#add-video').click(function() {
                let newRow = `<div class="video-row row mb-2">
            <div class="col-md-5"><input type="url" class="form-control" name="videos[${videoCount}][url]" placeholder="Video URL"></div>
            <div class="col-md-5"><input type="text" class="form-control" name="videos[${videoCount}][title]" placeholder="Title"></div>
            <div class="col-md-2"><button type="button" class="btn btn-danger remove-video"><i class="ti ti-trash"></i></button></div>
        </div>`;
                $('#videos-container').append(newRow);
                videoCount++;
            });
            $(document).on('click', '.remove-video', function() {
                $(this).closest('.video-row').remove();
            });

            // Images preview
            let imageCounter = 0;
            let imageFiles = [];
            let mainImageIndex = 0;

            $('#images_input').on('change', function(e) {
                const files = Array.from(e.target.files);
                const container = $('#images-preview-container');

                files.forEach((file, idx) => {
                    if (file.type.startsWith('image/')) {
                        imageFiles.push(file);
                        const reader = new FileReader();
                        const currentIndex = imageCounter;

                        reader.onload = function(ev) {
                            const isMain = (imageCounter === 0 && idx === 0) ||
                                mainImageIndex === currentIndex;
                            const previewHtml = `
                        <div class="col-md-3 image-preview-item mb-3" data-index="${currentIndex}">
                            <div class="border rounded p-2 text-center">
                                <img src="${ev.target.result}" class="img-fluid rounded mb-2" style="height: 120px; object-fit: cover; width: 100%;">
                                <div class="form-check mt-2">
                                    <input type="radio" name="main_image_index" value="${currentIndex}" class="form-check-input main-image-radio" ${isMain ? 'checked' : ''}>
                                    <label class="form-check-label small">Main Image</label>
                                </div>
                                <input type="text" name="images_alt[${currentIndex}]" class="form-control form-control-sm mt-2" placeholder="Alt text">
                                <button type="button" class="btn btn-sm btn-danger mt-2 remove-image-btn" data-index="${currentIndex}">Remove</button>
                            </div>
                        </div>
                    `;
                            container.append(previewHtml);

                            if (currentIndex === 0 && idx === 0) {
                                $('.main-image-radio').prop('checked', false);
                                $(`.main-image-radio[value="${currentIndex}"]`).prop('checked',
                                    true);
                            }
                        };
                        reader.readAsDataURL(file);
                        imageCounter++;
                    }
                });
                $(this).val('');
            });

            $(document).on('change', '.main-image-radio', function() {
                mainImageIndex = parseInt($(this).val());
            });

            $(document).on('click', '.remove-image-btn', function() {
                const index = $(this).data('index');
                $(`.image-preview-item[data-index="${index}"]`).remove();
                imageFiles[index] = null;

                if (mainImageIndex === index) {
                    const firstRadio = $('.main-image-radio').first();
                    if (firstRadio.length) {
                        firstRadio.prop('checked', true);
                        mainImageIndex = parseInt(firstRadio.val());
                    }
                }
            });

            // OG image preview
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
                $(this).removeClass('is-invalid');
                $('#og_image-error').text('');
            });

            function updateSEOPreview() {
                let title = $('#meta_title').val() || $('#name').val() || 'Product Name';
                let desc = $('#meta_description').val() || $('#short_description').val() || $('#description')
                    .val() || 'Product description will appear here...';
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
                $(this).removeClass('is-invalid');
                $('#meta_title-error').text('');
            });

            $('#meta_description').on('keyup', function() {
                let len = $(this).val().length;
                $('#metaDescCount').text(len + '/160 characters');
                if (len > 160) $('#metaDescCount').addClass('text-danger');
                else $('#metaDescCount').removeClass('text-danger');
                updateSEOPreview();
                $(this).removeClass('is-invalid');
                $('#meta_description-error').text('');
            });

            // Remove error on input
            $('input, select, textarea').on('input change', function() {
                $(this).removeClass('is-invalid');

                let fieldName = $(this).attr('name');
                if (fieldName) {
                    // Try to find error by ID without brackets
                    let safeName = fieldName.replace(/[\[\]]/g, '');
                    let $errorById = $(`#${safeName}-error`);

                    if ($errorById.length) {
                        $errorById.text('');
                    } else {
                        // Try to find error by data attribute
                        let $errorByData = $(`[data-error-for="${fieldName}"]`);
                        if ($errorByData.length) {
                            $errorByData.text('');
                        } else {
                            // Fallback: find error in parent container
                            $(this).closest('.mb-3').find('.invalid-feedback').text('');
                        }
                    }
                }
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
                btn.html('<span class="spinner-border spinner-border-sm me-1"></span> Creating...');
                btn.prop('disabled', true);

                let formData = new FormData(this);

                // Add primary category
                let primaryCategory = $('#primary_category_id').val();
                if (primaryCategory) formData.append('primary_category_id', primaryCategory);

                // Add all additional categories
                let allAdditionalCategories = [];
                for (let level in selectedCategoriesByLevel) {
                    if (selectedCategoriesByLevel.hasOwnProperty(level) && level != 0) {
                        selectedCategoriesByLevel[level].forEach(id => {
                            if (!allAdditionalCategories.includes(id)) allAdditionalCategories.push(
                                id);
                        });
                    }
                }
                allAdditionalCategories.forEach(id => formData.append('additional_categories[]', id));

                // Add all categories for attributes
                let allCategories = [primaryCategory, ...allAdditionalCategories];
                allCategories.forEach(id => formData.append('categories[]', id));

                // Process tags - combine select2 tags and comma separated input
                let selectedTagIds = $('#tags_select').val() || [];
                let commaTags = $('#tags_input').val().trim();
                let newTags = [];

                if (commaTags) {
                    newTags = commaTags.split(',').map(tag => tag.trim()).filter(tag => tag !== '');
                }

                // Add selected tag IDs
                selectedTagIds.forEach(id => formData.append('tags[]', id));

                // Add new tags as strings (will be created in controller)
                newTags.forEach(tag => formData.append('new_tags[]', tag));

                // Add attributes
                $('.attribute-input, .attribute-select, .attribute-multiselect, .attribute-color, .attribute-date, .attribute-checkbox:checked, .attribute-radio:checked')
                    .each(function() {
                        let attrId = $(this).data('attr-id');
                        let value = $(this).val();
                        if (value && value !== '') {
                            if ($(this).is('.attribute-multiselect')) {
                                value.forEach(v => formData.append(`attributes[${attrId}][]`, v));
                            } else {
                                formData.append(`attributes[${attrId}]`, value);
                            }
                        }
                    });

                // Add images
                imageFiles.forEach((file, index) => {
                    if (file) {
                        formData.append(`images[]`, file);
                        let altText = $(`input[name="images_alt[${index}]"]`).val();
                        if (altText) formData.append(`images_alt[]`, altText);
                        let isMain = $(`.main-image-radio[value="${index}"]`).is(':checked');
                        formData.append(`images_is_main[]`, isMain ? '1' : '0');
                    }
                });

                $.ajax({
                    url: '{{ route('vendor.products.store') }}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
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

                            // Clear previous errors
                            $('.is-invalid').removeClass('is-invalid');
                            $('.invalid-feedback').text('');

                            // Loop through each error
                            for (let field in errors) {
                                let message = errors[field][0];

                                // Handle tags field - the problematic one
                                if (field === 'tags_select' || field === 'tags_select[]' ||
                                    field === 'tags') {
                                    $('#tags_select').addClass('is-invalid');
                                    $('#tags-error').text(message);
                                    continue;
                                }

                                // Handle attributes
                                if (field.startsWith('attributes')) {
                                    let match = field.match(/attributes\[(\d+)\]/);
                                    if (match && match[1]) {
                                        let attrId = match[1];
                                        $(`#attributes_${attrId}-error`).text(message);
                                        $(`[data-attr-id="${attrId}"]`).addClass('is-invalid');
                                    }
                                    continue;
                                }

                                // Try to find the input element by name attribute
                                let $input = $(`[name="${field}"]`);

                                if ($input.length) {
                                    $input.addClass('is-invalid');

                                    // Find the error container - NEVER use the field name with brackets
                                    let $errorContainer = $input.closest('.mb-3').find(
                                        '.invalid-feedback');

                                    if ($errorContainer.length) {
                                        $errorContainer.text(message);
                                    } else {
                                        // Try to find by ID without brackets
                                        let cleanId = field.replace(/[\[\]]/g, '');
                                        let $errorById = $(`#${cleanId}-error`);
                                        if ($errorById.length) {
                                            $errorById.text(message);
                                        }
                                    }
                                } else {
                                    // Handle specific fields that might not be found by name
                                    switch (field) {
                                        case 'primary_category_id':
                                            $('#primary_category_id').addClass('is-invalid');
                                            $('#primary_category_id-error').text(message);
                                            break;
                                        case 'additional_categories':
                                            $('#additional_categories-error').text(message);
                                            break;
                                        case 'images':
                                            $('#images-error').text(message);
                                            break;
                                        case 'name':
                                            $('#name').addClass('is-invalid');
                                            $('#name-error').text(message);
                                            break;
                                        case 'price':
                                            $('#price').addClass('is-invalid');
                                            $('#price-error').text(message);
                                            break;
                                        case 'sku':
                                            $('#sku').addClass('is-invalid');
                                            $('#sku-error').text(message);
                                            break;
                                        case 'brand_id':
                                            $('#brand_id').addClass('is-invalid');
                                            $('#brand_id-error').text(message);
                                            break;
                                        case 'vendor_id':
                                            $('#vendor_id').addClass('is-invalid');
                                            $('#vendor_id-error').text(message);
                                            break;
                                        case 'meta_title':
                                            $('#meta_title').addClass('is-invalid');
                                            $('#meta_title-error').text(message);
                                            break;
                                        case 'meta_description':
                                            $('#meta_description').addClass('is-invalid');
                                            $('#meta_description-error').text(message);
                                            break;
                                        case 'canonical_url':
                                            $('#canonical_url').addClass('is-invalid');
                                            $('#canonical_url-error').text(message);
                                            break;
                                        default:
                                            console.log('Unhandled field:', field, message);
                                    }
                                }
                            }

                            // Scroll to first error
                            let firstError = $('.is-invalid:first');
                            if (firstError.length) {
                                $('html, body').animate({
                                    scrollTop: firstError.offset().top - 100
                                }, 500);
                            }
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

        .image-preview-item {
            transition: transform 0.2s;
        }

        .image-preview-item:hover {
            transform: translateY(-3px);
        }

        .invalid-feedback {
            display: block;
            font-size: 0.875em;
            margin-top: 0.25rem;
        }

        .choices {
            margin-bottom: 0;
        }

        .choices__inner {
            border-radius: 0.375rem;
            min-height: 38px;
        }

        .choices__list--multiple .choices__item {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        .category-level {
            background-color: #f8f9fa;
            border-radius: 8px;
            transition: all 0.2s;
        }
    </style>
@endpush
