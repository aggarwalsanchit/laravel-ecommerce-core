{{-- resources/views/admin/pages/categories/edit.blade.php --}}
@extends('management.layouts.app')

@section('title', 'Edit Category - ' . $category->name)

@section('content')
    <div class="page-content">
        <div class="page-container">
            <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                <div class="flex-grow-1">
                    <h4 class="fs-18 text-uppercase fw-bold mb-0">Edit Category: {{ $category->name }}</h4>
                </div>
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">Categories</a></li>
                        <li class="breadcrumb-item active">Edit Category</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header border-bottom border-dashed">
                            <h4 class="card-title">Category Information</h4>
                            <p class="text-muted mb-0">Update category information with optimized images and SEO</p>
                        </div>
                        <div class="card-body">
                            <form id="categoryForm" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                {{-- Basic Information Tabs --}}
                                <ul class="nav nav-tabs" id="categoryTabs" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="basic-tab" data-bs-toggle="tab"
                                            data-bs-target="#basic" type="button" role="tab">
                                            <i class="ti ti-info-circle"></i> Basic Info
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="images-tab" data-bs-toggle="tab"
                                            data-bs-target="#images" type="button" role="tab">
                                            <i class="ti ti-photo"></i> Images
                                            <span class="badge bg-primary ms-1">Optimized</span>
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="seo-tab" data-bs-toggle="tab" data-bs-target="#seo"
                                            type="button" role="tab">
                                            <i class="ti ti-meta-tag"></i> SEO & Social
                                        </button>
                                    </li>
                                </ul>

                                <div class="tab-content mt-3">
                                    {{-- Basic Information Tab --}}
                                    <div class="tab-pane fade show active" id="basic" role="tabpanel">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="name" class="form-label">Category Name <span
                                                            class="text-danger">*</span></label>
                                                    <div class="input-group">
                                                        <span class="input-group-text bg-light">
                                                            <i class="ti ti-folder"></i>
                                                        </span>
                                                        <input type="text" class="form-control" id="name"
                                                            name="name" value="{{ old('name', $category->name) }}"
                                                            autofocus>
                                                    </div>
                                                    <div class="invalid-feedback" id="name-error"></div>
                                                    <small class="text-muted">
                                                        <i class="ti ti-link"></i> URL slug: <span id="slug-preview"
                                                            class="text-primary">{{ $category->slug }}</span>
                                                    </small>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="parent_id" class="form-label">Parent Category</label>
                                                    <select class="form-select" id="parent_id" name="parent_id">
                                                        <option value="">-- No Parent (Top Level) --</option>
                                                        @foreach ($categories as $cat)
                                                            @if ($cat->id != $category->id)
                                                                <option value="{{ $cat->id }}"
                                                                    {{ $category->parent_id == $cat->id ? 'selected' : '' }}>
                                                                    {{ str_repeat('— ', $cat->depth ?? 0) }}{{ $cat->name }}
                                                                </option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                    <div class="invalid-feedback" id="parent_id-error"></div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="order" class="form-label">Display Order</label>
                                                    <input type="number" class="form-control" id="order" name="order"
                                                        value="{{ old('order', $category->order) }}">
                                                    <div class="invalid-feedback" id="order-error"></div>
                                                    <small class="text-muted">Lower numbers appear first in menus</small>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="icon" class="form-label">Icon Class</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text bg-light">
                                                            <i class="ti ti-icon"></i>
                                                        </span>
                                                        <input type="text" class="form-control" id="icon"
                                                            name="icon" value="{{ old('icon', $category->icon) }}"
                                                            placeholder="ti ti-folder">
                                                    </div>
                                                    <div class="invalid-feedback" id="icon-error"></div>
                                                    <small class="text-muted">Enter icon class (e.g., ti ti-folder, ti
                                                        ti-devices)</small>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label class="form-label">Display Settings</label>
                                                    <div class="d-flex flex-wrap gap-3">
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input"
                                                                id="status" name="status" value="1"
                                                                {{ $category->status ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="status">
                                                                <i class="ti ti-circle-check"></i> Active
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input"
                                                                id="show_in_menu" name="show_in_menu" value="1"
                                                                {{ $category->show_in_menu ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="show_in_menu">
                                                                <i class="ti ti-eye"></i> Show in Menu
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input"
                                                                id="is_featured" name="is_featured" value="1"
                                                                {{ $category->is_featured ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="is_featured">
                                                                <i class="ti ti-star"></i> Featured
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input"
                                                                id="is_popular" name="is_popular" value="1"
                                                                {{ $category->is_popular ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="is_popular">
                                                                <i class="ti ti-fire"></i> Popular
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input"
                                                                id="is_trending" name="is_trending" value="1"
                                                                {{ $category->is_trending ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="is_trending">
                                                                <i class="ti ti-trending-up"></i> Trending
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="short_description" class="form-label">Short
                                                        Description</label>
                                                    <textarea class="form-control" id="short_description" name="short_description" rows="2"
                                                        placeholder="Brief description for category cards and meta description">{{ old('short_description', $category->short_description) }}</textarea>
                                                    <div class="invalid-feedback" id="short_description-error"></div>
                                                    <small class="text-muted"
                                                        id="shortDescCount">{{ strlen($category->short_description ?? '') }}/500
                                                        characters</small>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="description" class="form-label">Full Description</label>
                                                    <textarea class="form-control" id="description" name="description" rows="5"
                                                        placeholder="Detailed description of the category for SEO and category page">{{ old('description', $category->description) }}</textarea>
                                                    <div class="invalid-feedback" id="description-error"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Images Tab --}}
                                    <div class="tab-pane fade" id="images" role="tabpanel">
                                        <div class="alert alert-info mb-3">
                                            <div class="d-flex">
                                                <i class="ti ti-info-circle me-2 fs-5"></i>
                                                <div>
                                                    <strong>Image Optimization Info:</strong>
                                                    <ul class="mb-0 mt-1">
                                                        <li>Images are automatically compressed and resized for optimal
                                                            performance</li>
                                                        <li>Maximum file size: Main 5MB, Thumbnail 2MB, Banner 10MB</li>
                                                        <li>Recommended dimensions: Main 800x800px, Thumbnail 150x150px,
                                                            Banner 1920x400px</li>
                                                        <li>Compression saves bandwidth and improves loading speed by up to
                                                            80%</li>
                                                        <li>Always provide alt text for better accessibility and SEO</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            {{-- Main Image --}}
                                            <div class="col-md-4">
                                                <div class="card border">
                                                    <div class="card-header bg-light">
                                                        <h6 class="mb-0">Main Image</h6>
                                                        <small class="text-muted">800x800px recommended</small>
                                                    </div>
                                                    <div class="card-body text-center">
                                                        <div id="imagePreview" class="mb-3">
                                                            @if ($category->image)
                                                                <img src="{{ asset('storage/categories/' . $category->image) }}"
                                                                    alt="{{ $category->image_alt ?? $category->name }}"
                                                                    class="img-fluid rounded" style="max-height: 150px;">
                                                                <div class="small text-muted mt-1">
                                                                    <i class="ti ti-info-circle"></i> Current image
                                                                </div>
                                                            @else
                                                                <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                                    style="height: 150px;">
                                                                    <i class="ti ti-cloud-upload fs-1 text-muted"></i>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <input type="file" class="form-control" id="image"
                                                            name="image" accept="image/*">
                                                        <div class="invalid-feedback" id="image-error"></div>
                                                        <div class="mt-2">
                                                            <label class="form-label small">Alt Text</label>
                                                            <input type="text" class="form-control form-control-sm"
                                                                id="image_alt" name="image_alt"
                                                                value="{{ old('image_alt', $category->image_alt) }}"
                                                                placeholder="Describe the image for accessibility">
                                                            <small class="text-muted">Helps with SEO and screen
                                                                readers</small>
                                                        </div>
                                                        @if ($category->image)
                                                            <div class="mt-2">
                                                                <div class="form-check">
                                                                    <input type="checkbox" class="form-check-input"
                                                                        id="remove_image" name="remove_image"
                                                                        value="1">
                                                                    <label class="form-check-label text-danger"
                                                                        for="remove_image">
                                                                        <i class="ti ti-trash"></i> Remove current image
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Thumbnail Image --}}
                                            <div class="col-md-4">
                                                <div class="card border">
                                                    <div class="card-header bg-light">
                                                        <h6 class="mb-0">Thumbnail Image</h6>
                                                        <small class="text-muted">150x150px recommended</small>
                                                    </div>
                                                    <div class="card-body text-center">
                                                        <div id="thumbnailPreview" class="mb-3">
                                                            @if ($category->thumbnail_image)
                                                                <img src="{{ asset('storage/categories/thumbnails/' . $category->thumbnail_image) }}"
                                                                    alt="{{ $category->thumbnail_alt ?? $category->name }}"
                                                                    class="img-fluid rounded" style="max-height: 150px;">
                                                                <div class="small text-muted mt-1">
                                                                    <i class="ti ti-info-circle"></i> Current thumbnail
                                                                </div>
                                                            @else
                                                                <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                                    style="height: 150px;">
                                                                    <i class="ti ti-cloud-upload fs-1 text-muted"></i>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <input type="file" class="form-control" id="thumbnail_image"
                                                            name="thumbnail_image" accept="image/*">
                                                        <div class="invalid-feedback" id="thumbnail_image-error"></div>
                                                        <div class="mt-2">
                                                            <label class="form-label small">Alt Text</label>
                                                            <input type="text" class="form-control form-control-sm"
                                                                id="thumbnail_alt" name="thumbnail_alt"
                                                                value="{{ old('thumbnail_alt', $category->thumbnail_alt) }}"
                                                                placeholder="Describe the thumbnail">
                                                            <small class="text-muted">Used for listings and cards</small>
                                                        </div>
                                                        @if ($category->thumbnail_image)
                                                            <div class="mt-2">
                                                                <div class="form-check">
                                                                    <input type="checkbox" class="form-check-input"
                                                                        id="remove_thumbnail" name="remove_thumbnail"
                                                                        value="1">
                                                                    <label class="form-check-label text-danger"
                                                                        for="remove_thumbnail">
                                                                        <i class="ti ti-trash"></i> Remove current
                                                                        thumbnail
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Banner Image --}}
                                            <div class="col-md-4">
                                                <div class="card border">
                                                    <div class="card-header bg-light">
                                                        <h6 class="mb-0">Banner Image</h6>
                                                        <small class="text-muted">1920x400px recommended</small>
                                                    </div>
                                                    <div class="card-body text-center">
                                                        <div id="bannerPreview" class="mb-3">
                                                            @if ($category->banner_image)
                                                                <img src="{{ asset('storage/categories/banners/' . $category->banner_image) }}"
                                                                    alt="{{ $category->banner_alt ?? $category->name }}"
                                                                    class="img-fluid rounded"
                                                                    style="max-height: 150px; width: 100%; object-fit: cover;">
                                                                <div class="small text-muted mt-1">
                                                                    <i class="ti ti-info-circle"></i> Current banner
                                                                </div>
                                                            @else
                                                                <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                                    style="height: 150px;">
                                                                    <i class="ti ti-cloud-upload fs-1 text-muted"></i>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <input type="file" class="form-control" id="banner_image"
                                                            name="banner_image" accept="image/*">
                                                        <div class="invalid-feedback" id="banner_image-error"></div>
                                                        <div class="mt-2">
                                                            <label class="form-label small">Alt Text</label>
                                                            <input type="text" class="form-control form-control-sm"
                                                                id="banner_alt" name="banner_alt"
                                                                value="{{ old('banner_alt', $category->banner_alt) }}"
                                                                placeholder="Describe the banner">
                                                            <small class="text-muted">Used for category hero
                                                                section</small>
                                                        </div>
                                                        @if ($category->banner_image)
                                                            <div class="mt-2">
                                                                <div class="form-check">
                                                                    <input type="checkbox" class="form-check-input"
                                                                        id="remove_banner" name="remove_banner"
                                                                        value="1">
                                                                    <label class="form-check-label text-danger"
                                                                        for="remove_banner">
                                                                        <i class="ti ti-trash"></i> Remove current banner
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- SEO & Social Tab --}}
                                    <div class="tab-pane fade" id="seo" role="tabpanel">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="focus_keyword" class="form-label">Focus Keyword</label>
                                                    <input type="text" class="form-control" id="focus_keyword"
                                                        name="focus_keyword"
                                                        value="{{ old('focus_keyword', $category->focus_keyword) }}"
                                                        placeholder="Primary keyword for this category">
                                                    <div class="invalid-feedback" id="focus_keyword-error"></div>
                                                    <small class="text-muted">Main keyword you want to rank for in search
                                                        engines</small>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="meta_title" class="form-label">Meta Title</label>
                                                    <input type="text" class="form-control" id="meta_title"
                                                        name="meta_title"
                                                        value="{{ old('meta_title', $category->meta_title) }}"
                                                        placeholder="SEO title (50-60 characters recommended)">
                                                    <div class="invalid-feedback" id="meta_title-error"></div>
                                                    <small class="text-muted"
                                                        id="metaTitleCount">{{ strlen($category->meta_title ?? '') }}/70
                                                        characters</small>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="meta_description" class="form-label">Meta
                                                        Description</label>
                                                    <textarea class="form-control" id="meta_description" name="meta_description" rows="2"
                                                        placeholder="SEO description (150-160 characters)">{{ old('meta_description', $category->meta_description) }}</textarea>
                                                    <div class="invalid-feedback" id="meta_description-error"></div>
                                                    <small class="text-muted"
                                                        id="metaDescCount">{{ strlen($category->meta_description ?? '') }}/160
                                                        characters</small>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="meta_keywords" class="form-label">Meta Keywords</label>
                                                    <input type="text" class="form-control" id="meta_keywords"
                                                        name="meta_keywords"
                                                        value="{{ old('meta_keywords', $category->meta_keywords) }}"
                                                        placeholder="keyword1, keyword2, keyword3">
                                                    <div class="invalid-feedback" id="meta_keywords-error"></div>
                                                    <small class="text-muted">Comma separated keywords (optional, less
                                                        important now)</small>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="canonical_url" class="form-label">Canonical URL</label>
                                                    <input type="url" class="form-control" id="canonical_url"
                                                        name="canonical_url"
                                                        value="{{ old('canonical_url', $category->canonical_url) }}"
                                                        placeholder="https://example.com/canonical-url">
                                                    <div class="invalid-feedback" id="canonical_url-error"></div>
                                                    <small class="text-muted">Leave empty to use auto-generated URL</small>
                                                </div>
                                            </div>

                                            {{-- Open Graph / Social Media --}}
                                            <div class="col-md-12">
                                                <hr>
                                                <h6 class="mb-3"><i class="ti ti-share"></i> Social Media (Open Graph)
                                                </h6>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="og_title" class="form-label">OG Title</label>
                                                    <input type="text" class="form-control" id="og_title"
                                                        name="og_title"
                                                        value="{{ old('og_title', $category->og_title) }}"
                                                        placeholder="Title for social sharing">
                                                    <div class="invalid-feedback" id="og_title-error"></div>
                                                    <small class="text-muted">Leave empty to use meta title</small>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="og_image" class="form-label">OG Image</label>
                                                    <input type="file" class="form-control" id="og_image"
                                                        name="og_image" accept="image/*">
                                                    <div class="invalid-feedback" id="og_image-error"></div>
                                                    <small class="text-muted">Image for social sharing (1200x630px
                                                        recommended)</small>
                                                    @if ($category->og_image)
                                                        <div class="mt-2">
                                                            <div class="form-check">
                                                                <input type="checkbox" class="form-check-input"
                                                                    id="remove_og_image" name="remove_og_image"
                                                                    value="1">
                                                                <label class="form-check-label text-danger"
                                                                    for="remove_og_image">
                                                                    <i class="ti ti-trash"></i> Remove current OG image
                                                                </label>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div id="ogImagePreview" class="mt-2">
                                                    @if ($category->og_image)
                                                        <img src="{{ asset('storage/' . $category->og_image) }}"
                                                            class="img-fluid rounded border" style="max-height: 100px;">
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="og_description" class="form-label">OG Description</label>
                                                    <textarea class="form-control" id="og_description" name="og_description" rows="2"
                                                        placeholder="Description for social sharing">{{ old('og_description', $category->og_description) }}</textarea>
                                                    <div class="invalid-feedback" id="og_description-error"></div>
                                                    <small class="text-muted">Leave empty to use meta description</small>
                                                </div>
                                            </div>

                                            {{-- Schema Markup --}}
                                            <div class="col-md-12">
                                                <hr>
                                                <h6 class="mb-3"><i class="ti ti-code"></i> Schema Markup (JSON-LD)</h6>
                                                <div class="mb-3">
                                                    <label for="schema_markup" class="form-label">Custom Schema
                                                        Markup</label>
                                                    <textarea class="form-control" id="schema_markup" name="schema_markup" rows="5"
                                                        placeholder="Enter JSON-LD schema markup"></textarea>
                                                    <div class="invalid-feedback" id="schema_markup-error"></div>
                                                    <small class="text-muted">
                                                        <i class="ti ti-info-circle"></i>
                                                        JSON-LD format. Leave empty for auto-generated schema.
                                                    </small>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- SEO Preview Card --}}
                                        <div class="card border mt-3">
                                            <div class="card-header bg-light">
                                                <h6 class="mb-0"><i class="ti ti-eye"></i> SEO Preview (Google Search
                                                    Result)</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="text-primary fw-bold fs-5" id="seo-preview-title">
                                                    {{ $category->meta_title ?? $category->name }}</div>
                                                <div class="text-muted small" id="seo-preview-url">
                                                    {{ url('/category') }}/{{ $category->slug }}</div>
                                                <div class="text-muted small mt-2" id="seo-preview-desc">
                                                    {{ Str::limit($category->meta_description ?? ($category->short_description ?? ($category->description ?? 'Category description will appear here...')), 160) }}
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Social Preview Card --}}
                                        <div class="card border mt-3">
                                            <div class="card-header bg-light">
                                                <h6 class="mb-0"><i class="ti ti-brand-facebook"></i> Social Media
                                                    Preview</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="d-flex gap-3">
                                                    <div id="socialImagePreview" class="bg-light rounded"
                                                        style="width: 120px; height: 63px; display: flex; align-items: center; justify-content: center;">
                                                        @if ($category->og_image)
                                                            <img src="{{ asset('storage/' . $category->og_image) }}"
                                                                style="width: 120px; height: 63px; object-fit: cover;">
                                                        @else
                                                            <i class="ti ti-photo text-muted"></i>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <div class="fw-semibold" id="social-preview-title">
                                                            {{ $category->og_title ?? ($category->meta_title ?? $category->name) }}
                                                        </div>
                                                        <div class="text-muted small" id="social-preview-desc">
                                                            {{ Str::limit($category->og_description ?? ($category->meta_description ?? ($category->short_description ?? 'Category description...')), 200) }}
                                                        </div>
                                                        <div class="text-muted small" id="social-preview-url">
                                                            {{ url('/category') }}/{{ $category->slug }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2 mt-4">
                                    <a href="{{ route('admin.categories.index') }}" class="btn btn-danger">
                                        <i class="ti ti-x me-1"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary" id="submitBtn">
                                        <i class="ti ti-edit me-1"></i> Update Category
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
    <script>
        $(document).ready(function() {
            let formSubmitting = false;

            // Auto-generate slug preview
            $('#name').on('keyup', function() {
                let slug = $(this).val().toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
                if (slug) {
                    $('#slug-preview').text(slug);
                } else {
                    $('#slug-preview').text('{{ $category->slug }}');
                }
                updateSEOPreview();
                updateSocialPreview();

                // Remove error when typing
                $(this).removeClass('is-invalid');
                $('#name-error').text('');
            });

            // Update SEO preview
            function updateSEOPreview() {
                let title = $('#meta_title').val() || $('#name').val() || 'Category Name';
                let desc = $('#meta_description').val() || $('#short_description').val() || $('#description')
                    .val() || 'Category description will appear here...';
                let slug = $('#name').val().toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '') ||
                    '{{ $category->slug }}';

                $('#seo-preview-title').text(title.substring(0, 70));
                $('#seo-preview-url').text('{{ url('/') }}/category/' + slug);
                $('#seo-preview-desc').text(desc.substring(0, 160));
            }

            // Update social preview
            function updateSocialPreview() {
                let title = $('#og_title').val() || $('#meta_title').val() || $('#name').val() || 'Category Name';
                let desc = $('#og_description').val() || $('#meta_description').val() || $('#short_description')
                    .val() || 'Category description...';
                let slug = $('#name').val().toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '') ||
                    '{{ $category->slug }}';

                $('#social-preview-title').text(title.substring(0, 60));
                $('#social-preview-desc').text(desc.substring(0, 200));
                $('#social-preview-url').text('{{ url('/') }}/category/' + slug);
            }

            // Character counters
            $('#short_description').on('keyup', function() {
                let length = $(this).val().length;
                $('#shortDescCount').text(length + '/500 characters');
                if (length > 500) {
                    $('#shortDescCount').addClass('text-danger');
                } else {
                    $('#shortDescCount').removeClass('text-danger');
                }
                updateSEOPreview();
            });

            $('#meta_title').on('keyup', function() {
                let length = $(this).val().length;
                $('#metaTitleCount').text(length + '/70 characters');
                if (length > 70) {
                    $('#metaTitleCount').addClass('text-danger');
                } else {
                    $('#metaTitleCount').removeClass('text-danger');
                }
                updateSEOPreview();
                updateSocialPreview();

                $(this).removeClass('is-invalid');
                $('#meta_title-error').text('');
            });

            $('#meta_description').on('keyup', function() {
                let length = $(this).val().length;
                $('#metaDescCount').text(length + '/160 characters');
                if (length > 160) {
                    $('#metaDescCount').addClass('text-danger');
                } else {
                    $('#metaDescCount').removeClass('text-danger');
                }
                updateSEOPreview();
                updateSocialPreview();

                $(this).removeClass('is-invalid');
                $('#meta_description-error').text('');
            });

            $('#og_title, #og_description').on('keyup', function() {
                updateSocialPreview();
            });

            // Main Image preview
            $('#image').on('change', function(event) {
                let file = event.target.files[0];
                if (file) {
                    if (file.size > 5 * 1024 * 1024) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'File Too Large',
                            text: 'Image size should be less than 5MB.',
                            confirmButtonColor: '#d33'
                        });
                        $(this).val('');
                        return;
                    }
                    let reader = new FileReader();
                    reader.onload = function(e) {
                        $('#imagePreview').html(
                            `<img src="${e.target.result}" class="img-fluid rounded" style="max-height: 150px;"><div class="small text-muted mt-1">${(file.size / 1024).toFixed(2)} KB</div>`
                        );
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Thumbnail preview
            $('#thumbnail_image').on('change', function(event) {
                let file = event.target.files[0];
                if (file) {
                    if (file.size > 2 * 1024 * 1024) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'File Too Large',
                            text: 'Thumbnail size should be less than 2MB.',
                            confirmButtonColor: '#d33'
                        });
                        $(this).val('');
                        return;
                    }
                    let reader = new FileReader();
                    reader.onload = function(e) {
                        $('#thumbnailPreview').html(
                            `<img src="${e.target.result}" class="img-fluid rounded" style="max-height: 150px;"><div class="small text-muted mt-1">${(file.size / 1024).toFixed(2)} KB</div>`
                        );
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Banner preview
            $('#banner_image').on('change', function(event) {
                let file = event.target.files[0];
                if (file) {
                    if (file.size > 10 * 1024 * 1024) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'File Too Large',
                            text: 'Banner size should be less than 10MB.',
                            confirmButtonColor: '#d33'
                        });
                        $(this).val('');
                        return;
                    }
                    let reader = new FileReader();
                    reader.onload = function(e) {
                        $('#bannerPreview').html(
                            `<img src="${e.target.result}" class="img-fluid rounded" style="max-height: 150px; width: 100%; object-fit: cover;"><div class="small text-muted mt-1">${(file.size / 1024).toFixed(2)} KB</div>`
                        );
                    };
                    reader.readAsDataURL(file);
                }
            });

            // OG Image preview
            $('#og_image').on('change', function(event) {
                let file = event.target.files[0];
                if (file) {
                    if (file.size > 2 * 1024 * 1024) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'File Too Large',
                            text: 'OG Image size should be less than 2MB.',
                            confirmButtonColor: '#d33'
                        });
                        $(this).val('');
                        $('#ogImagePreview').html('');
                        return;
                    }
                    let reader = new FileReader();
                    reader.onload = function(e) {
                        $('#ogImagePreview').html(
                            `<img src="${e.target.result}" class="img-fluid rounded border" style="max-height: 100px;"><div class="small text-muted mt-1">${(file.size / 1024).toFixed(2)} KB</div>`
                        );
                        $('#socialImagePreview').html(
                            `<img src="${e.target.result}" style="width: 120px; height: 63px; object-fit: cover;">`
                        );
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Remove error on any input change
            $('input, select, textarea').on('input change', function() {
                $(this).removeClass('is-invalid');
                $('#' + $(this).attr('name') + '-error').text('');
            });

            // Form submission - FIXED VERSION
            $('#categoryForm').on('submit', function(e) {
                e.preventDefault();

                if (formSubmitting) return false;

                let isValid = true;
                let nameValue = $('#name').val().trim();

                // Validate name field
                if (!nameValue) {
                    $('#name').addClass('is-invalid');
                    $('#name-error').text('Category name is required');
                    isValid = false;
                } else {
                    $('#name').removeClass('is-invalid');
                    $('#name-error').text('');
                }

                if (!isValid) {
                    $('html, body').animate({
                        scrollTop: $('.is-invalid:first').offset().top - 100
                    }, 500);
                    return false;
                }

                formSubmitting = true;
                let btn = $('#submitBtn');
                let originalText = btn.html();
                btn.html('<span class="spinner-border spinner-border-sm me-1"></span> Updating...');
                btn.prop('disabled', true);

                // Create FormData and manually append name if needed
                let formData = new FormData(this);

                // Ensure name is in FormData
                if (!formData.has('name') || !formData.get('name')) {
                    formData.set('name', nameValue);
                }

                // Debug: Log all form data
                console.log('=== Form Data being sent ===');
                for (var pair of formData.entries()) {
                    console.log(pair[0] + ': ' + pair[1]);
                }

                $.ajax({
                    url: '{{ route('admin.categories.update', $category->id) }}',
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
                                    '{{ route('admin.categories.index') }}';
                            });
                        }
                    },
                    error: function(xhr) {
                        console.log('Error response:', xhr);

                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function(field, messages) {
                                $('#' + field).addClass('is-invalid');
                                $('#' + field + '-error').text(messages[0]);
                            });
                            $('html, body').animate({
                                scrollTop: $('.is-invalid:first').offset().top - 100
                            }, 500);
                        } else if (xhr.status === 403) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Permission Denied!',
                                text: xhr.responseJSON?.message ||
                                    'You do not have permission to perform this action.',
                                confirmButtonColor: '#d33'
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: xhr.responseJSON?.message ||
                                    'Something went wrong. Please try again.',
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

            // Initial previews
            updateSEOPreview();
            updateSocialPreview();
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

        .form-check-input:checked {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        .invalid-feedback {
            display: block;
            font-size: 0.875em;
            margin-top: 0.25rem;
        }
    </style>
@endpush
