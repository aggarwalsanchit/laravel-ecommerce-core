{{-- resources/views/admin/pages/sizes/create.blade.php --}}
@extends('management.layouts.app')

@section('title', 'Add New Size')

@section('content')
    <div class="page-content">
        <div class="page-container">
            <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                <div class="flex-grow-1">
                    <h4 class="fs-18 text-uppercase fw-bold mb-0">Add New Size</h4>
                </div>
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.sizes.index') }}">Sizes</a></li>
                        <li class="breadcrumb-item active">Add Size</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header border-bottom border-dashed">
                            <h4 class="card-title">Size Information</h4>
                            <p class="text-muted mb-0">Create a new size with measurements and conversion charts</p>
                        </div>
                        <div class="card-body">
                            <form id="sizeForm" enctype="multipart/form-data">
                                @csrf

                                {{-- Tabs --}}
                                <ul class="nav nav-tabs" id="sizeTabs" role="tablist">
                                    <li class="nav-item">
                                        <button class="nav-link active" id="basic-tab" data-bs-toggle="tab" data-bs-target="#basic" type="button">
                                            <i class="ti ti-info-circle"></i> Basic Info
                                        </button>
                                    </li>
                                    <li class="nav-item">
                                        <button class="nav-link" id="measurements-tab" data-bs-toggle="tab" data-bs-target="#measurements" type="button">
                                            <i class="ti ti-ruler"></i> Measurements
                                        </button>
                                    </li>
                                    <li class="nav-item">
                                        <button class="nav-link" id="conversion-tab" data-bs-toggle="tab" data-bs-target="#conversion" type="button">
                                            <i class="ti ti-exchange"></i> Size Conversion
                                        </button>
                                    </li>
                                    <li class="nav-item">
                                        <button class="nav-link" id="seo-tab" data-bs-toggle="tab" data-bs-target="#seo" type="button">
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
                                                    <label for="name" class="form-label">Size Name <span class="text-danger">*</span></label>
                                                    <div class="input-group">
                                                        <span class="input-group-text bg-light">
                                                            <i class="ti ti-ruler"></i>
                                                        </span>
                                                        <input type="text" class="form-control" id="name" name="name" placeholder="e.g., Small, Medium, Large, US 8" autofocus>
                                                    </div>
                                                    <div class="invalid-feedback" id="name-error"></div>
                                                    <small class="text-muted">
                                                        <i class="ti ti-link"></i> URL slug: <span id="slug-preview" class="text-primary"></span>
                                                    </small>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="code" class="form-label">Size Code <span class="text-danger">*</span></label>
                                                    <div class="input-group">
                                                        <span class="input-group-text bg-light">
                                                            <i class="ti ti-hash"></i>
                                                        </span>
                                                        <input type="text" class="form-control" id="code" name="code" placeholder="e.g., S, M, L, XL, US8">
                                                    </div>
                                                    <div class="invalid-feedback" id="code-error"></div>
                                                    <small class="text-muted">Unique identifier for this size (e.g., S, M, L, XL)</small>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="gender" class="form-label">Gender <span class="text-danger">*</span></label>
                                                    <select class="form-select" id="gender" name="gender">
                                                        <option value="">Select Gender</option>
                                                        <option value="Men">Men</option>
                                                        <option value="Women">Women</option>
                                                        <option value="Unisex">Unisex</option>
                                                        <option value="Kids">Kids</option>
                                                    </select>
                                                    <div class="invalid-feedback" id="gender-error"></div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="order" class="form-label">Display Order</label>
                                                    <input type="number" class="form-control" id="order" name="order" value="0">
                                                    <div class="invalid-feedback" id="order-error"></div>
                                                    <small class="text-muted">Lower numbers appear first</small>
                                                </div>
                                            </div>

                                            {{-- Categories Multi-Select with Choices.js --}}
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label class="form-label text-muted">Associated Categories <span class="text-danger">*</span></label>
                                                    <p class="text-muted small">Select which categories this size applies to (you can select multiple)</p>
                                                    <select class="form-control" id="choices-categories" name="category_ids[]" multiple>
                                                        @foreach($categories as $category)
                                                            <option value="{{ $category->id }}">
                                                                {{ str_repeat('— ', $category->depth ?? 0) }}{{ $category->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <div class="invalid-feedback" id="category_ids-error"></div>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label class="form-label">Display Settings</label>
                                                    <div class="d-flex flex-wrap gap-3">
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input" id="status" name="status" value="1" checked>
                                                            <label class="form-check-label" for="status">
                                                                <i class="ti ti-circle-check"></i> Active
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured" value="1">
                                                            <label class="form-check-label" for="is_featured">
                                                                <i class="ti ti-star"></i> Featured
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input" id="is_popular" name="is_popular" value="1">
                                                            <label class="form-check-label" for="is_popular">
                                                                <i class="ti ti-fire"></i> Popular
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="description" class="form-label">Description</label>
                                                    <textarea class="form-control" id="description" name="description" rows="3"
                                                        placeholder="Size description and fit notes"></textarea>
                                                    <div class="invalid-feedback" id="description-error"></div>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="card border">
                                                    <div class="card-header bg-light">
                                                        <h6 class="mb-0">Size Chart Image</h6>
                                                        <small class="text-muted">Optional image for size chart</small>
                                                    </div>
                                                    <div class="card-body text-center">
                                                        <div id="imagePreview" class="mb-3">
                                                            <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 150px;">
                                                                <i class="ti ti-cloud-upload fs-1 text-muted"></i>
                                                            </div>
                                                        </div>
                                                        <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                                        <div class="invalid-feedback" id="image-error"></div>
                                                        <div class="mt-2">
                                                            <label class="form-label small">Alt Text</label>
                                                            <input type="text" class="form-control form-control-sm" id="image_alt" name="image_alt"
                                                                placeholder="Describe the size chart image">
                                                            <small class="text-muted">Helps with accessibility</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Measurements Tab --}}
                                    <div class="tab-pane fade" id="measurements" role="tabpanel">
                                        <div class="alert alert-info mb-3">
                                            <i class="ti ti-info-circle"></i> Measurements in inches ("). Leave empty if not applicable.
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="chest" class="form-label">Chest/Bust</label>
                                                    <div class="input-group">
                                                        <input type="number" step="0.1" class="form-control" id="chest" name="chest" placeholder="e.g., 38">
                                                        <span class="input-group-text">inches</span>
                                                    </div>
                                                    <div class="invalid-feedback" id="chest-error"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="waist" class="form-label">Waist</label>
                                                    <div class="input-group">
                                                        <input type="number" step="0.1" class="form-control" id="waist" name="waist" placeholder="e.g., 32">
                                                        <span class="input-group-text">inches</span>
                                                    </div>
                                                    <div class="invalid-feedback" id="waist-error"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="hip" class="form-label">Hip</label>
                                                    <div class="input-group">
                                                        <input type="number" step="0.1" class="form-control" id="hip" name="hip" placeholder="e.g., 40">
                                                        <span class="input-group-text">inches</span>
                                                    </div>
                                                    <div class="invalid-feedback" id="hip-error"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="inseam" class="form-label">Inseam</label>
                                                    <div class="input-group">
                                                        <input type="number" step="0.1" class="form-control" id="inseam" name="inseam" placeholder="e.g., 32">
                                                        <span class="input-group-text">inches</span>
                                                    </div>
                                                    <div class="invalid-feedback" id="inseam-error"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="shoulder" class="form-label">Shoulder</label>
                                                    <div class="input-group">
                                                        <input type="number" step="0.1" class="form-control" id="shoulder" name="shoulder" placeholder="e.g., 18">
                                                        <span class="input-group-text">inches</span>
                                                    </div>
                                                    <div class="invalid-feedback" id="shoulder-error"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="sleeve" class="form-label">Sleeve Length</label>
                                                    <div class="input-group">
                                                        <input type="number" step="0.1" class="form-control" id="sleeve" name="sleeve" placeholder="e.g., 25">
                                                        <span class="input-group-text">inches</span>
                                                    </div>
                                                    <div class="invalid-feedback" id="sleeve-error"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="neck" class="form-label">Neck</label>
                                                    <div class="input-group">
                                                        <input type="number" step="0.1" class="form-control" id="neck" name="neck" placeholder="e.g., 15">
                                                        <span class="input-group-text">inches</span>
                                                    </div>
                                                    <div class="invalid-feedback" id="neck-error"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="height" class="form-label">Height Recommendation</label>
                                                    <div class="input-group">
                                                        <input type="number" step="0.1" class="form-control" id="height" name="height" placeholder="e.g., 5.9">
                                                        <span class="input-group-text">feet</span>
                                                    </div>
                                                    <div class="invalid-feedback" id="height-error"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="weight" class="form-label">Weight Recommendation</label>
                                                    <div class="input-group">
                                                        <input type="number" step="0.1" class="form-control" id="weight" name="weight" placeholder="e.g., 160">
                                                        <span class="input-group-text">lbs</span>
                                                    </div>
                                                    <div class="invalid-feedback" id="weight-error"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Size Conversion Tab --}}
                                    <div class="tab-pane fade" id="conversion" role="tabpanel">
                                        <div class="alert alert-info mb-3">
                                            <i class="ti ti-info-circle"></i> International size conversions. Leave empty if not applicable.
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="us_size" class="form-label">US Size</label>
                                                    <input type="text" class="form-control" id="us_size" name="us_size" placeholder="e.g., S, 8, 38">
                                                    <div class="invalid-feedback" id="us_size-error"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="uk_size" class="form-label">UK Size</label>
                                                    <input type="text" class="form-control" id="uk_size" name="uk_size" placeholder="e.g., S, 10, 40">
                                                    <div class="invalid-feedback" id="uk_size-error"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="eu_size" class="form-label">EU Size</label>
                                                    <input type="text" class="form-control" id="eu_size" name="eu_size" placeholder="e.g., 36, 42, 48">
                                                    <div class="invalid-feedback" id="eu_size-error"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="au_size" class="form-label">AU Size</label>
                                                    <input type="text" class="form-control" id="au_size" name="au_size" placeholder="e.g., 6, 8, 10">
                                                    <div class="invalid-feedback" id="au_size-error"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="jp_size" class="form-label">JP Size</label>
                                                    <input type="text" class="form-control" id="jp_size" name="jp_size" placeholder="e.g., S, M, L">
                                                    <div class="invalid-feedback" id="jp_size-error"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="cn_size" class="form-label">CN Size</label>
                                                    <input type="text" class="form-control" id="cn_size" name="cn_size" placeholder="e.g., 160, 165, 170">
                                                    <div class="invalid-feedback" id="cn_size-error"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="int_size" class="form-label">International Size</label>
                                                    <input type="text" class="form-control" id="int_size" name="int_size" placeholder="e.g., XS, S, M, L, XL">
                                                    <div class="invalid-feedback" id="int_size-error"></div>
                                                    <small class="text-muted">Standard international size (XXS, XS, S, M, L, XL, XXL, etc.)</small>
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
                                                    <input type="text" class="form-control" id="focus_keyword" name="focus_keyword" placeholder="Primary keyword for this size">
                                                    <div class="invalid-feedback" id="focus_keyword-error"></div>
                                                    <small class="text-muted">Main keyword you want to rank for</small>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="meta_title" class="form-label">Meta Title</label>
                                                    <input type="text" class="form-control" id="meta_title" name="meta_title" placeholder="SEO title (50-60 characters)">
                                                    <div class="invalid-feedback" id="meta_title-error"></div>
                                                    <small class="text-muted" id="metaTitleCount">0/70 characters</small>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="meta_description" class="form-label">Meta Description</label>
                                                    <textarea class="form-control" id="meta_description" name="meta_description" rows="2"
                                                        placeholder="SEO description (150-160 characters)"></textarea>
                                                    <div class="invalid-feedback" id="meta_description-error"></div>
                                                    <small class="text-muted" id="metaDescCount">0/160 characters</small>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="meta_keywords" class="form-label">Meta Keywords</label>
                                                    <input type="text" class="form-control" id="meta_keywords" name="meta_keywords" placeholder="keyword1, keyword2, keyword3">
                                                    <div class="invalid-feedback" id="meta_keywords-error"></div>
                                                    <small class="text-muted">Comma separated keywords</small>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="canonical_url" class="form-label">Canonical URL</label>
                                                    <input type="url" class="form-control" id="canonical_url" name="canonical_url" placeholder="https://example.com/canonical-url">
                                                    <div class="invalid-feedback" id="canonical_url-error"></div>
                                                    <small class="text-muted">Leave empty to use auto-generated URL</small>
                                                </div>
                                            </div>

                                            {{-- Open Graph / Social Media --}}
                                            <div class="col-md-12">
                                                <hr>
                                                <h6 class="mb-3"><i class="ti ti-share"></i> Social Media (Open Graph)</h6>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="og_title" class="form-label">OG Title</label>
                                                    <input type="text" class="form-control" id="og_title" name="og_title" placeholder="Title for social sharing">
                                                    <div class="invalid-feedback" id="og_title-error"></div>
                                                    <small class="text-muted">Leave empty to use meta title</small>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="og_image" class="form-label">OG Image</label>
                                                    <input type="file" class="form-control" id="og_image" name="og_image" accept="image/*">
                                                    <div class="invalid-feedback" id="og_image-error"></div>
                                                    <small class="text-muted">Image for social sharing (1200x630px)</small>
                                                </div>
                                                <div id="ogImagePreview" class="mt-2"></div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="og_description" class="form-label">OG Description</label>
                                                    <textarea class="form-control" id="og_description" name="og_description" rows="2"
                                                        placeholder="Description for social sharing"></textarea>
                                                    <div class="invalid-feedback" id="og_description-error"></div>
                                                    <small class="text-muted">Leave empty to use meta description</small>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- SEO Preview Card --}}
                                        <div class="card border mt-3">
                                            <div class="card-header bg-light">
                                                <h6 class="mb-0"><i class="ti ti-eye"></i> SEO Preview (Google Search Result)</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="text-primary fw-bold fs-5" id="seo-preview-title">Size Name</div>
                                                <div class="text-muted small" id="seo-preview-url">{{ url('/size') }}/size-slug</div>
                                                <div class="text-muted small mt-2" id="seo-preview-desc">Size description will appear here...</div>
                                            </div>
                                        </div>

                                        {{-- Social Preview Card --}}
                                        <div class="card border mt-3">
                                            <div class="card-header bg-light">
                                                <h6 class="mb-0"><i class="ti ti-brand-facebook"></i> Social Media Preview</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="d-flex gap-3">
                                                    <div id="socialImagePreview" class="bg-light rounded"
                                                        style="width: 120px; height: 63px; display: flex; align-items: center; justify-content: center;">
                                                        <i class="ti ti-photo text-muted"></i>
                                                    </div>
                                                    <div>
                                                        <div class="fw-semibold" id="social-preview-title">Size Name</div>
                                                        <div class="text-muted small" id="social-preview-desc">Size description...</div>
                                                        <div class="text-muted small" id="social-preview-url">{{ url('/size') }}/size-slug</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2 mt-4">
                                    <a href="{{ route('admin.sizes.index') }}" class="btn btn-danger">
                                        <i class="ti ti-x me-1"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary" id="submitBtn">
                                        <i class="ti ti-plus me-1"></i> Create Size
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
<!-- Choices.js CSS and JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize Choices.js for categories (MULTI SELECT with remove button)
    const categoriesSelect = document.getElementById('choices-categories');
    let choicesInstance = null;
    
    if (categoriesSelect) {
        choicesInstance = new Choices(categoriesSelect, {
            removeItemButton: true,
            removeItems: true,
            duplicateItemsAllowed: false,
            placeholder: true,
            placeholderValue: 'Select categories for this size',
            searchEnabled: true,
            searchChoices: true,
            searchResultLimit: 10,
            shouldSort: true,
            itemSelectText: '',
            noChoicesText: 'No categories available',
            noResultsText: 'No categories found',
        });
        
        // Remove error when selection changes
        categoriesSelect.addEventListener('change', function() {
            let selectedValues = choicesInstance.getValue(true);
            if (selectedValues && selectedValues.length > 0) {
                $('#choices-categories').removeClass('is-invalid');
                $('#category_ids-error').text('');
            }
        });
    }

    let formSubmitting = false;

    // Auto-generate slug preview
    $('#name').on('keyup', function() {
        let slug = $(this).val().toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
        let gender = $('#gender').val().toLowerCase();
        if (gender && gender !== 'unisex') {
            slug = slug + '-' + gender;
        }
        $('#slug-preview').text(slug || 'size-slug');
        updateSEOPreview();
        updateSocialPreview();
        $(this).removeClass('is-invalid');
        $('#name-error').text('');
    });

    $('#gender').on('change', function() {
        let name = $('#name').val();
        if (name) {
            let slug = name.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
            let gender = $(this).val().toLowerCase();
            if (gender && gender !== 'unisex') {
                slug = slug + '-' + gender;
            }
            $('#slug-preview').text(slug);
            updateSEOPreview();
            updateSocialPreview();
        }
        $(this).removeClass('is-invalid');
        $('#gender-error').text('');
    });

    // Update SEO preview
    function updateSEOPreview() {
        let title = $('#meta_title').val() || $('#name').val() || 'Size Name';
        let desc = $('#meta_description').val() || $('#description').val() || 'Size description will appear here...';
        let slug = $('#slug-preview').text() || 'size-slug';

        $('#seo-preview-title').text(title.substring(0, 70));
        $('#seo-preview-url').text('{{ url('/') }}/size/' + slug);
        $('#seo-preview-desc').text(desc.substring(0, 160));
    }

    // Update social preview
    function updateSocialPreview() {
        let title = $('#og_title').val() || $('#meta_title').val() || $('#name').val() || 'Size Name';
        let desc = $('#og_description').val() || $('#meta_description').val() || $('#description').val() || 'Size description...';
        let slug = $('#slug-preview').text() || 'size-slug';

        $('#social-preview-title').text(title.substring(0, 60));
        $('#social-preview-desc').text(desc.substring(0, 200));
        $('#social-preview-url').text('{{ url('/') }}/size/' + slug);
    }

    // Character counters
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
        $(this).removeClass('is-invalid');
        $('#' + $(this).attr('name') + '-error').text('');
    });

    // Image preview
    $('#image').on('change', function(event) {
        let file = event.target.files[0];
        if (file) {
            if (file.size > 2 * 1024 * 1024) {
                Swal.fire({ icon: 'warning', title: 'File Too Large', text: 'Image size should be less than 2MB.', confirmButtonColor: '#d33' });
                $(this).val('');
                $('#imagePreview').html('<div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 150px;"><i class="ti ti-cloud-upload fs-1 text-muted"></i></div>');
                return;
            }
            let reader = new FileReader();
            reader.onload = function(e) {
                $('#imagePreview').html(`<img src="${e.target.result}" class="img-fluid rounded" style="max-height: 150px;"><div class="small text-muted mt-1">${(file.size / 1024).toFixed(2)} KB</div>`);
            };
            reader.readAsDataURL(file);
        }
    });

    // OG Image preview
    $('#og_image').on('change', function(event) {
        let file = event.target.files[0];
        if (file) {
            if (file.size > 2 * 1024 * 1024) {
                Swal.fire({ icon: 'warning', title: 'File Too Large', text: 'OG Image size should be less than 2MB.', confirmButtonColor: '#d33' });
                $(this).val('');
                $('#ogImagePreview').html('');
                return;
            }
            let reader = new FileReader();
            reader.onload = function(e) {
                $('#ogImagePreview').html(`<img src="${e.target.result}" class="img-fluid rounded border" style="max-height: 100px;"><div class="small text-muted mt-1">${(file.size / 1024).toFixed(2)} KB</div>`);
                $('#socialImagePreview').html(`<img src="${e.target.result}" style="width: 120px; height: 63px; object-fit: cover;">`);
            };
            reader.readAsDataURL(file);
        }
    });

    // Remove error on input
    $('input, select, textarea').on('input change', function() {
        $(this).removeClass('is-invalid');
        $('#' + $(this).attr('name') + '-error').text('');
    });

    // Form submission
    $('#sizeForm').on('submit', function(e) {
        e.preventDefault();

        if (formSubmitting) return false;

        let isValid = true;
        let nameValue = $('#name').val().trim();
        let codeValue = $('#code').val().trim();
        let genderValue = $('#gender').val();
        
        // Get selected values from Choices.js
        let selectedCategories = [];
        if (choicesInstance) {
            selectedCategories = choicesInstance.getValue(true);
        }
        
        // Alternative: Get from original select element
        let selectElement = document.getElementById('choices-categories');
        if (selectElement && (!selectedCategories || selectedCategories.length === 0)) {
            selectedCategories = Array.from(selectElement.selectedOptions).map(option => option.value);
        }

        // Name validation
        if (!nameValue) {
            $('#name').addClass('is-invalid');
            $('#name-error').text('Size name is required');
            isValid = false;
        } else {
            $('#name').removeClass('is-invalid');
            $('#name-error').text('');
        }

        // Code validation
        if (!codeValue) {
            $('#code').addClass('is-invalid');
            $('#code-error').text('Size code is required');
            isValid = false;
        } else {
            $('#code').removeClass('is-invalid');
            $('#code-error').text('');
        }

        // Gender validation
        if (!genderValue) {
            $('#gender').addClass('is-invalid');
            $('#gender-error').text('Please select a gender');
            isValid = false;
        } else {
            $('#gender').removeClass('is-invalid');
            $('#gender-error').text('');
        }

        // Categories validation
        if (!selectedCategories || selectedCategories.length === 0) {
            $('#choices-categories').addClass('is-invalid');
            $('#category_ids-error').text('Please select at least one category');
            isValid = false;
        } else {
            $('#choices-categories').removeClass('is-invalid');
            $('#category_ids-error').text('');
        }

        if (!isValid) {
            $('html, body').animate({ scrollTop: $('.is-invalid:first').offset().top - 100 }, 500);
            return false;
        }

        formSubmitting = true;
        let btn = $('#submitBtn');
        let originalText = btn.html();
        btn.html('<span class="spinner-border spinner-border-sm me-1"></span> Creating...');
        btn.prop('disabled', true);

        let formData = new FormData(this);
        
        // Ensure category_ids are in FormData
        formData.delete('category_ids[]');
        if (selectedCategories && selectedCategories.length > 0) {
            selectedCategories.forEach(categoryId => {
                formData.append('category_ids[]', categoryId);
            });
        }

        $.ajax({
            url: '{{ route("admin.sizes.store") }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            success: function(response) {
                if (response.success) {
                    Swal.fire({ icon: 'success', title: 'Success!', text: response.message, timer: 2000, showConfirmButton: false })
                        .then(() => { window.location.href = '{{ route("admin.sizes.index") }}'; });
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    $.each(errors, function(field, messages) {
                        if (field === 'category_ids') {
                            $('#choices-categories').addClass('is-invalid');
                            $('#category_ids-error').text(messages[0]);
                        } else {
                            $('#' + field).addClass('is-invalid');
                            $('#' + field + '-error').text(messages[0]);
                        }
                    });
                    $('html, body').animate({ scrollTop: $('.is-invalid:first').offset().top - 100 }, 500);
                } else {
                    Swal.fire({ icon: 'error', title: 'Error!', text: xhr.responseJSON?.message || 'Something went wrong.', confirmButtonColor: '#d33' });
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
    updateSocialPreview();
});
</script>
@endpush

@push('styles')
<style>
    .nav-tabs .nav-link { color: #6c757d; }
    .nav-tabs .nav-link.active { color: #0d6efd; border-bottom: 2px solid #0d6efd; }
    .form-check-input:checked { background-color: #0d6efd; border-color: #0d6efd; }
    .invalid-feedback { display: block; font-size: 0.875em; margin-top: 0.25rem; }
    
    /* Choices.js Styling */
    .choices {
        margin-bottom: 0;
    }
    .choices.is-invalid .choices__inner {
        border-color: #dc3545;
        background-color: #fff0f0;
    }
    .choices__inner {
        border-radius: 0.375rem;
        min-height: 38px;
        background-color: #fff;
        border: 1px solid #dee2e6;
    }
    .choices__button {
        background-size: 12px;
        opacity: 0.7;
    }
    .choices__button:hover {
        opacity: 1;
    }
    .choices__list--multiple .choices__item {
        background-color: #0d6efd;
        border-color: #0d6efd;
        border-radius: 0.25rem;
        padding: 0.25rem 0.75rem;
        margin: 0.125rem;
    }
    .choices__list--multiple .choices__item.is-highlighted {
        background-color: #0b5ed7;
        border-color: #0b5ed7;
    }
    .choices__list--dropdown .choices__item--selectable.is-highlighted {
        background-color: #e9ecef;
    }
    .is-focused .choices__inner {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
</style>
@endpush