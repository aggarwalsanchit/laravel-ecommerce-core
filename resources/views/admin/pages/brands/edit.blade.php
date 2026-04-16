{{-- resources/views/admin/pages/brands/edit.blade.php --}}
@extends('management.layouts.app')

@section('title', 'Edit Brand - ' . $brand->name)

@section('content')
    <div class="page-content">
        <div class="page-container">
            <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                <div class="flex-grow-1">
                    <h4 class="fs-18 text-uppercase fw-bold mb-0">Edit Brand: {{ $brand->name }}</h4>
                </div>
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.brands.index') }}">Brands</a></li>
                        <li class="breadcrumb-item active">Edit Brand</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header border-bottom border-dashed">
                            <h4 class="card-title">Brand Information</h4>
                            <p class="text-muted mb-0">Update brand information with optimized images and SEO</p>
                        </div>
                        <div class="card-body">
                            <form id="brandForm" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <ul class="nav nav-tabs" id="brandTabs" role="tablist">
                                    <li class="nav-item">
                                        <button class="nav-link active" id="basic-tab" data-bs-toggle="tab"
                                            data-bs-target="#basic" type="button">
                                            <i class="ti ti-info-circle"></i> Basic Info
                                        </button>
                                    </li>
                                    <li class="nav-item">
                                        <button class="nav-link" id="images-tab" data-bs-toggle="tab"
                                            data-bs-target="#images" type="button">
                                            <i class="ti ti-photo"></i> Images
                                            <span class="badge bg-primary ms-1">Optimized</span>
                                        </button>
                                    </li>
                                    <li class="nav-item">
                                        <button class="nav-link" id="categories-tab" data-bs-toggle="tab"
                                            data-bs-target="#categories" type="button">
                                            <i class="ti ti-folder"></i> Categories
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
                                    {{-- Basic Information Tab --}}
                                    <div class="tab-pane fade show active" id="basic" role="tabpanel">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="name" class="form-label">Brand Name <span
                                                            class="text-danger">*</span></label>
                                                    <div class="input-group">
                                                        <span class="input-group-text bg-light">
                                                            <i class="ti ti-brand-airbnb"></i>
                                                        </span>
                                                        <input type="text" class="form-control" id="name"
                                                            name="name" value="{{ old('name', $brand->name) }}"
                                                            autofocus>
                                                    </div>
                                                    <div class="invalid-feedback" id="name-error"></div>
                                                    <small class="text-muted">
                                                        <i class="ti ti-link"></i> URL slug: <span id="slug-preview"
                                                            class="text-primary">{{ $brand->slug }}</span>
                                                    </small>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="code" class="form-label">Brand Code</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text bg-light">
                                                            <i class="ti ti-barcode"></i>
                                                        </span>
                                                        <input type="text" class="form-control" id="code"
                                                            name="code" value="{{ old('code', $brand->code) }}"
                                                            placeholder="e.g., APPLE, SAMSUNG">
                                                    </div>
                                                    <div class="invalid-feedback" id="code-error"></div>
                                                    <small class="text-muted">Unique identifier</small>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="order" class="form-label">Display Order</label>
                                                    <input type="number" class="form-control" id="order"
                                                        name="order" value="{{ old('order', $brand->order) }}">
                                                    <div class="invalid-feedback" id="order-error"></div>
                                                    <small class="text-muted">Lower numbers appear first</small>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Display Settings</label>
                                                    <div class="d-flex gap-3">
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input"
                                                                id="status" name="status" value="1"
                                                                {{ $brand->status ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="status">
                                                                <i class="ti ti-circle-check"></i> Active
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input"
                                                                id="is_featured" name="is_featured" value="1"
                                                                {{ $brand->is_featured ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="is_featured">
                                                                <i class="ti ti-star"></i> Featured
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="description" class="form-label">Description</label>
                                                    <textarea class="form-control" id="description" name="description" rows="5"
                                                        placeholder="Detailed description of the brand">{{ old('description', $brand->description) }}</textarea>
                                                    <div class="invalid-feedback" id="description-error"></div>
                                                    <small class="text-muted"
                                                        id="descCount">{{ strlen($brand->description ?? '') }}/1000
                                                        characters</small>
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
                                                        <li>Images are automatically compressed and resized</li>
                                                        <li>Maximum file size: Logo 2MB, Banner 5MB</li>
                                                        <li>Recommended dimensions: Logo 200x200px, Banner 1200x400px</li>
                                                        <li>Always provide alt text for better accessibility and SEO</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            {{-- Logo Image --}}
                                            <div class="col-md-6">
                                                <div class="card border">
                                                    <div class="card-header bg-light">
                                                        <h6 class="mb-0">Brand Logo</h6>
                                                        <small class="text-muted">200x200px recommended</small>
                                                    </div>
                                                    <div class="card-body text-center">
                                                        <div id="logoPreview" class="mb-3">
                                                            @if ($brand->logo)
                                                                <img src="{{ asset('storage/brands/' . $brand->logo) }}"
                                                                    alt="{{ $brand->logo_alt ?? $brand->name }}"
                                                                    class="img-fluid rounded" style="max-height: 150px;">
                                                                <div class="small text-muted mt-1">
                                                                    <i class="ti ti-info-circle"></i> Current logo
                                                                </div>
                                                            @else
                                                                <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                                    style="height: 150px;">
                                                                    <i class="ti ti-cloud-upload fs-1 text-muted"></i>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <input type="file" class="form-control" id="logo"
                                                            name="logo" accept="image/*">
                                                        <div class="invalid-feedback" id="logo-error"></div>
                                                        <div class="mt-2">
                                                            <label class="form-label small">Alt Text</label>
                                                            <input type="text" class="form-control form-control-sm"
                                                                id="logo_alt" name="logo_alt"
                                                                value="{{ old('logo_alt', $brand->logo_alt) }}"
                                                                placeholder="Describe the logo">
                                                            <small class="text-muted">Helps with SEO</small>
                                                        </div>
                                                        @if ($brand->logo)
                                                            <div class="mt-2">
                                                                <div class="form-check">
                                                                    <input type="checkbox" class="form-check-input"
                                                                        id="remove_logo" name="remove_logo"
                                                                        value="1">
                                                                    <label class="form-check-label text-danger"
                                                                        for="remove_logo">
                                                                        <i class="ti ti-trash"></i> Remove current logo
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Banner Image --}}
                                            <div class="col-md-6">
                                                <div class="card border">
                                                    <div class="card-header bg-light">
                                                        <h6 class="mb-0">Brand Banner</h6>
                                                        <small class="text-muted">1200x400px recommended</small>
                                                    </div>
                                                    <div class="card-body text-center">
                                                        <div id="bannerPreview" class="mb-3">
                                                            @if ($brand->banner)
                                                                <img src="{{ asset('storage/brands/banners/' . $brand->banner) }}"
                                                                    alt="{{ $brand->banner_alt ?? $brand->name }}"
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
                                                        <input type="file" class="form-control" id="banner"
                                                            name="banner" accept="image/*">
                                                        <div class="invalid-feedback" id="banner-error"></div>
                                                        <div class="mt-2">
                                                            <label class="form-label small">Alt Text</label>
                                                            <input type="text" class="form-control form-control-sm"
                                                                id="banner_alt" name="banner_alt"
                                                                value="{{ old('banner_alt', $brand->banner_alt) }}"
                                                                placeholder="Describe the banner">
                                                            <small class="text-muted">Used for hero section</small>
                                                        </div>
                                                        @if ($brand->banner)
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

                                    {{-- Categories Tab --}}
                                    <div class="tab-pane fade" id="categories" role="tabpanel">
                                        <div class="alert alert-info mb-3">
                                            <i class="ti ti-info-circle me-1"></i>
                                            Select the categories where this brand belongs.
                                        </div>

                                        <div class="mb-3">
                                            <label for="categories" class="form-label">Select Categories</label>
                                            <select class="form-control" id="choices-multiple-remove-button" data-choices
                                                data-choices-removeItem name="categories[]" multiple>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}"
                                                        {{ in_array($category->id, $brandCategoryIds) ? 'selected' : '' }}>
                                                        {{ str_repeat('— ', $category->depth ?? 0) }}{{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback" id="categories-error"></div>
                                            <small class="text-muted">Search and select multiple categories</small>
                                        </div>

                                        <div class="mt-3">
                                            <button type="button" class="btn btn-sm btn-outline-primary"
                                                id="selectAllCategories">
                                                <i class="ti ti-check-all"></i> Select All
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-secondary"
                                                id="deselectAllCategories">
                                                <i class="ti ti-check"></i> Deselect All
                                            </button>
                                        </div>
                                    </div>

                                    {{-- SEO Tab --}}
                                    <div class="tab-pane fade" id="seo" role="tabpanel">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="meta_title" class="form-label">Meta Title</label>
                                                    <input type="text" class="form-control" id="meta_title"
                                                        name="meta_title"
                                                        value="{{ old('meta_title', $brand->meta_title) }}"
                                                        placeholder="SEO title (50-60 characters)">
                                                    <div class="invalid-feedback" id="meta_title-error"></div>
                                                    <small class="text-muted"
                                                        id="metaTitleCount">{{ strlen($brand->meta_title ?? '') }}/70
                                                        characters</small>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="meta_description" class="form-label">Meta
                                                        Description</label>
                                                    <textarea class="form-control" id="meta_description" name="meta_description" rows="2"
                                                        placeholder="SEO description (150-160 characters)">{{ old('meta_description', $brand->meta_description) }}</textarea>
                                                    <div class="invalid-feedback" id="meta_description-error"></div>
                                                    <small class="text-muted"
                                                        id="metaDescCount">{{ strlen($brand->meta_description ?? '') }}/160
                                                        characters</small>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="meta_keywords" class="form-label">Meta Keywords</label>
                                                    <input type="text" class="form-control" id="meta_keywords"
                                                        name="meta_keywords"
                                                        value="{{ old('meta_keywords', $brand->meta_keywords) }}"
                                                        placeholder="keyword1, keyword2, keyword3">
                                                    <div class="invalid-feedback" id="meta_keywords-error"></div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card border mt-3">
                                            <div class="card-header bg-light">
                                                <h6 class="mb-0"><i class="ti ti-eye"></i> SEO Preview</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="text-primary fw-bold fs-5" id="seo-preview-title">
                                                    {{ $brand->meta_title ?? $brand->name }}
                                                </div>
                                                <div class="text-muted small" id="seo-preview-url">
                                                    {{ url('/brand') }}/{{ $brand->slug }}
                                                </div>
                                                <div class="text-muted small mt-2" id="seo-preview-desc">
                                                    {{ Str::limit($brand->meta_description ?? ($brand->description ?? 'Brand description...'), 160) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2 mt-4">
                                    <a href="{{ route('admin.brands.index') }}" class="btn btn-danger">
                                        <i class="ti ti-x me-1"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary" id="submitBtn">
                                        <i class="ti ti-edit me-1"></i> Update Brand
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
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    <script>
        $(document).ready(function() {
            let formSubmitting = false;
            let choicesInstance = null;

            // Initialize Choices.js for categories
            const element = document.getElementById('choices-multiple-remove-button');
            if (element) {
                choicesInstance = new Choices(element, {
                    removeItemButton: true,
                    removeItems: true,
                    duplicateItemsAllowed: false,
                    placeholder: true,
                    placeholderValue: 'Select categories',
                    searchEnabled: true,
                    searchChoices: true,
                    searchResultLimit: 20,
                    shouldSort: true,
                    itemSelectText: '',
                    addItems: false,
                    noResultsText: 'No categories found',
                    noChoicesText: 'No categories available',
                    renderSelectedChoices: 'auto'
                });
            }

            // Auto-generate slug preview
            $('#name').on('keyup', function() {
                let slug = $(this).val().toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
                if (slug) {
                    $('#slug-preview').text(slug);
                } else {
                    $('#slug-preview').text('{{ $brand->slug }}');
                }
                updateSEOPreview();

                $(this).removeClass('is-invalid');
                $('#name-error').text('');
            });

            // Update SEO preview
            function updateSEOPreview() {
                let title = $('#meta_title').val() || $('#name').val() || 'Brand Name';
                let desc = $('#meta_description').val() || $('#description').val() || 'Brand description...';
                let slug = $('#name').val().toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '') ||
                    '{{ $brand->slug }}';

                $('#seo-preview-title').text(title.substring(0, 70));
                $('#seo-preview-url').text('{{ url('/') }}/brand/' + slug);
                $('#seo-preview-desc').text(desc.substring(0, 160));
            }

            // Character counters
            $('#description').on('keyup', function() {
                let length = $(this).val().length;
                $('#descCount').text(length + '/1000 characters');
                if (length > 1000) $('#descCount').addClass('text-danger');
                else $('#descCount').removeClass('text-danger');
                updateSEOPreview();
                $(this).removeClass('is-invalid');
                $('#description-error').text('');
            });

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

            // Logo preview
            $('#logo').on('change', function(event) {
                let file = event.target.files[0];
                if (file) {
                    if (file.size > 2 * 1024 * 1024) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'File Too Large',
                            text: 'Logo size should be less than 2MB.',
                            confirmButtonColor: '#d33'
                        });
                        $(this).val('');
                        $('#logoPreview').html(
                            '<div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 150px;"><i class="ti ti-cloud-upload fs-1 text-muted"></i></div>'
                            );
                        return;
                    }
                    let reader = new FileReader();
                    reader.onload = function(e) {
                        $('#logoPreview').html(
                            `<img src="${e.target.result}" class="img-fluid rounded" style="max-height: 150px;"><div class="small text-muted mt-1">${(file.size / 1024).toFixed(2)} KB</div>`
                            );
                    };
                    reader.readAsDataURL(file);
                }
                $(this).removeClass('is-invalid');
                $('#logo-error').text('');
            });

            // Banner preview
            $('#banner').on('change', function(event) {
                let file = event.target.files[0];
                if (file) {
                    if (file.size > 5 * 1024 * 1024) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'File Too Large',
                            text: 'Banner size should be less than 5MB.',
                            confirmButtonColor: '#d33'
                        });
                        $(this).val('');
                        $('#bannerPreview').html(
                            '<div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 150px;"><i class="ti ti-cloud-upload fs-1 text-muted"></i></div>'
                            );
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
                $(this).removeClass('is-invalid');
                $('#banner-error').text('');
            });

            // Select/Deselect all categories using Choices.js
            $('#selectAllCategories').on('click', function() {
                if (choicesInstance) {
                    const selectElement = document.getElementById('choices-multiple-remove-button');
                    const allValues = Array.from(selectElement.options)
                        .filter(option => option.value !== '')
                        .map(option => option.value);
                    choicesInstance.setValue(allValues);
                }
            });

            $('#deselectAllCategories').on('click', function() {
                if (choicesInstance) {
                    choicesInstance.removeActiveItems();
                }
            });

            // Remove error on input
            $('input, select, textarea').on('input change', function() {
                $(this).removeClass('is-invalid');
                $('#' + $(this).attr('name') + '-error').text('');
            });

            // Form submission
            $('#brandForm').on('submit', function(e) {
                e.preventDefault();
                if (formSubmitting) return;

                let isValid = true;
                if (!$('#name').val().trim()) {
                    $('#name').addClass('is-invalid');
                    $('#name-error').text('Brand name is required');
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
                if (!formData.has('name') || !formData.get('name')) {
                    formData.set('name', $('#name').val().trim());
                }

                $.ajax({
                    url: '{{ route('admin.brands.update', $brand->id) }}',
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
                                    '{{ route('admin.brands.index') }}';
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: response.message || 'Something went wrong.'
                            });
                            formSubmitting = false;
                            btn.html(originalText);
                            btn.prop('disabled', false);
                        }
                    },
                    error: function(xhr) {
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
                                    'You do not have permission.'
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: xhr.responseJSON?.message ||
                                    'Something went wrong.'
                            });
                        }
                        formSubmitting = false;
                        btn.html(originalText);
                        btn.prop('disabled', false);
                    }
                });
            });

            // Initial previews
            updateSEOPreview();
        });
    </script>
@endpush

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
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
        }

        /* Choices.js custom styling */
        .choices {
            margin-bottom: 0;
        }

        .choices__inner {
            background-color: #fff;
            border-radius: 0.375rem;
            min-height: 38px;
            border: 1px solid #dee2e6;
        }

        .choices__list--multiple .choices__item {
            background-color: #0d6efd;
            border-color: #0d6efd;
            border-radius: 0.25rem;
        }

        .choices__list--multiple .choices__item.is-highlighted {
            background-color: #0b5ed7;
            border-color: #0b5ed7;
        }

        .choices__input {
            background-color: transparent;
        }

        .is-focused .choices__inner,
        .is-open .choices__inner {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
    </style>
@endpush
