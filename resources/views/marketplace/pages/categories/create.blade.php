{{-- resources/views/admin/categories/create.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Add New Category')

@section('content')
    <div class="page-content">
        <div class="page-container">
            <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                <div class="flex-grow-1">
                    <h4 class="fs-18 text-uppercase fw-bold mb-0">Add New Category</h4>
                </div>
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">Categories</a></li>
                        <li class="breadcrumb-item active">Add Category</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header border-bottom border-dashed">
                            <h4 class="card-title">Category Information</h4>
                            <p class="text-muted mb-0">Create a new category for your products with optimized images</p>
                        </div>
                        <div class="card-body">
                            <form id="categoryForm" enctype="multipart/form-data">
                                @csrf

                                <div class="row">
                                    {{-- Basic Information --}}
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Category Name <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light">
                                                    <i class="ti ti-folder"></i>
                                                </span>
                                                <input type="text" class="form-control" id="name" name="name"
                                                    placeholder="Enter category name" autofocus>
                                            </div>
                                            <div class="invalid-feedback" id="name-error"></div>
                                            <small class="text-muted">
                                                <i class="ti ti-link"></i> URL slug: <span id="slug-preview"
                                                    class="text-primary"></span>
                                            </small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="parent_id" class="form-label">Parent Category</label>
                                            <select class="form-select" id="parent_id" name="parent_id">
                                                <option value="">-- No Parent (Top Level) --</option>
                                                @foreach ($categories as $cat)
                                                    <option value="{{ $cat->id }}"
                                                        style="padding-left: {{ $cat->depth * 20 }}px">
                                                        {{ str_repeat('— ', $cat->depth) }}{{ $cat->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback" id="parent_id-error"></div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="order" class="form-label">Display Order</label>
                                            <input type="number" class="form-control" id="order" name="order"
                                                value="0">
                                            <div class="invalid-feedback" id="order-error"></div>
                                            <small class="text-muted">Lower numbers appear first in menus</small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Display Settings</label>
                                            <div class="d-flex flex-wrap gap-3">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="status"
                                                        name="status" value="1" checked>
                                                    <label class="form-check-label" for="status">Active</label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="show_in_menu"
                                                        name="show_in_menu" value="1" checked>
                                                    <label class="form-check-label" for="show_in_menu">Show in Menu</label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="is_featured"
                                                        name="is_featured" value="1">
                                                    <label class="form-check-label" for="is_featured">Featured</label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="is_popular"
                                                        name="is_popular" value="1">
                                                    <label class="form-check-label" for="is_popular">Popular</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="short_description" class="form-label">Short Description</label>
                                            <textarea class="form-control" id="short_description" name="short_description" rows="2"
                                                placeholder="Brief description for category cards and meta description"></textarea>
                                            <div class="invalid-feedback" id="short_description-error"></div>
                                            <small class="text-muted" id="shortDescCount">0/500 characters</small>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="description" class="form-label">Full Description</label>
                                            <textarea class="form-control" id="description" name="description" rows="5"
                                                placeholder="Detailed description of the category for SEO and category page"></textarea>
                                            <div class="invalid-feedback" id="description-error"></div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Tabs for Images and SEO --}}
                                <ul class="nav nav-tabs mt-4" id="categoryTabs" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="images-tab" data-bs-toggle="tab"
                                            data-bs-target="#images" type="button" role="tab">
                                            <i class="ti ti-photo"></i> Images
                                            <span class="badge bg-primary ms-1">Optimized</span>
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="seo-tab" data-bs-toggle="tab"
                                            data-bs-target="#seo" type="button" role="tab">
                                            <i class="ti ti-chart-line"></i> SEO Settings
                                        </button>
                                    </li>
                                </ul>

                                <div class="tab-content mt-3">
                                    {{-- Images Tab --}}
                                    <div class="tab-pane fade show active" id="images" role="tabpanel">
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
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="card border">
                                                    <div class="card-header bg-light">
                                                        <h6 class="mb-0">Main Image</h6>
                                                        <small class="text-muted">800x800px recommended</small>
                                                    </div>
                                                    <div class="card-body text-center">
                                                        <div id="imagePreview" class="mb-3">
                                                            <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                                style="height: 150px;">
                                                                <i class="ti ti-cloud-upload fs-1 text-muted"></i>
                                                            </div>
                                                        </div>
                                                        <input type="file" class="form-control" id="image"
                                                            name="image" accept="image/*">
                                                        <div class="invalid-feedback" id="image-error"></div>
                                                        <small class="text-muted mt-2 d-block">
                                                            <i class="ti ti-info-circle"></i> Max 5MB, will be compressed
                                                            to ~200KB
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="card border">
                                                    <div class="card-header bg-light">
                                                        <h6 class="mb-0">Thumbnail Image</h6>
                                                        <small class="text-muted">150x150px recommended</small>
                                                    </div>
                                                    <div class="card-body text-center">
                                                        <div id="thumbnailPreview" class="mb-3">
                                                            <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                                style="height: 150px;">
                                                                <i class="ti ti-cloud-upload fs-1 text-muted"></i>
                                                            </div>
                                                        </div>
                                                        <input type="file" class="form-control" id="thumbnail_image"
                                                            name="thumbnail_image" accept="image/*">
                                                        <div class="invalid-feedback" id="thumbnail_image-error"></div>
                                                        <small class="text-muted mt-2 d-block">
                                                            <i class="ti ti-info-circle"></i> Max 2MB, will be compressed
                                                            to ~30KB
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="card border">
                                                    <div class="card-header bg-light">
                                                        <h6 class="mb-0">Banner Image</h6>
                                                        <small class="text-muted">1920x400px recommended</small>
                                                    </div>
                                                    <div class="card-body text-center">
                                                        <div id="bannerPreview" class="mb-3">
                                                            <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                                style="height: 150px;">
                                                                <i class="ti ti-cloud-upload fs-1 text-muted"></i>
                                                            </div>
                                                        </div>
                                                        <input type="file" class="form-control" id="banner_image"
                                                            name="banner_image" accept="image/*">
                                                        <div class="invalid-feedback" id="banner_image-error"></div>
                                                        <small class="text-muted mt-2 d-block">
                                                            <i class="ti ti-info-circle"></i> Max 10MB, will be compressed
                                                            to ~400KB
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- SEO Tab --}}
                                    <div class="tab-pane fade" id="seo" role="tabpanel">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="focus_keyword" class="form-label">Focus Keyword</label>
                                                    <input type="text" class="form-control" id="focus_keyword"
                                                        name="focus_keyword"
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
                                                        name="meta_title" placeholder="SEO title (60-70 characters)">
                                                    <div class="invalid-feedback" id="meta_title-error"></div>
                                                    <small class="text-muted" id="metaTitleCount">0/70 characters</small>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="meta_description" class="form-label">Meta
                                                        Description</label>
                                                    <textarea class="form-control" id="meta_description" name="meta_description" rows="2"
                                                        placeholder="SEO description (150-160 characters)"></textarea>
                                                    <div class="invalid-feedback" id="meta_description-error"></div>
                                                    <small class="text-muted" id="metaDescCount">0/160 characters</small>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="meta_keywords" class="form-label">Meta Keywords</label>
                                                    <input type="text" class="form-control" id="meta_keywords"
                                                        name="meta_keywords" placeholder="keyword1, keyword2, keyword3">
                                                    <div class="invalid-feedback" id="meta_keywords-error"></div>
                                                    <small class="text-muted">Comma separated keywords (optional)</small>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="canonical_url" class="form-label">Canonical URL</label>
                                                    <input type="url" class="form-control" id="canonical_url"
                                                        name="canonical_url"
                                                        placeholder="https://example.com/canonical-url">
                                                    <div class="invalid-feedback" id="canonical_url-error"></div>
                                                    <small class="text-muted">Leave empty to use auto-generated URL</small>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- SEO Preview Card --}}
                                        <div class="card border mt-3">
                                            <div class="card-header bg-light">
                                                <h6 class="mb-0"><i class="ti ti-eye"></i> SEO Preview</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="text-primary fw-bold fs-5" id="seo-preview-title">Category
                                                    Name</div>
                                                <div class="text-muted small" id="seo-preview-url">
                                                    {{ url('/category') }}/category-slug</div>
                                                <div class="text-muted small mt-2" id="seo-preview-desc">Category
                                                    description will appear here...</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2 mt-4">
                                    <a href="{{ route('admin.categories.index') }}" class="btn btn-danger">
                                        <i class="ti ti-x me-1"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary" id="submitBtn">
                                        <i class="ti ti-plus me-1"></i> Create Category
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
                $('#slug-preview').text(slug || 'category-slug');
                updateSEOPreview();
            });

            // Update SEO preview
            function updateSEOPreview() {
                let title = $('#meta_title').val() || $('#name').val() || 'Category Name';
                let desc = $('#meta_description').val() || $('#short_description').val() || $('#description')
                .val() || 'Category description will appear here...';
                let slug = $('#name').val().toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '') ||
                    'category-name';

                $('#seo-preview-title').text(title.substring(0, 70));
                $('#seo-preview-url').text('{{ url('/') }}/category/' + slug);
                $('#seo-preview-desc').text(desc.substring(0, 160));
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
            });

            // Image preview with size validation
            $('#image').on('change', function(event) {
                let file = event.target.files[0];
                if (file) {
                    // Validate file size (5MB max)
                    if (file.size > 5 * 1024 * 1024) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'File Too Large',
                            text: 'Image size should be less than 5MB. Please compress your image.',
                            confirmButtonColor: '#d33'
                        });
                        $(this).val('');
                        $('#imagePreview').html(
                            '<div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 150px;"><i class="ti ti-cloud-upload fs-1 text-muted"></i></div>'
                            );
                        return;
                    }

                    let reader = new FileReader();
                    reader.onload = function(e) {
                        let estimatedCompressed = Math.round(file.size / 1024 * 0.7);
                        $('#imagePreview').html(`
                    <div>
                        <img src="${e.target.result}" class="img-fluid rounded mb-2" style="max-height: 150px;">
                        <div class="small text-muted">
                            <i class="ti ti-info-circle"></i> Original: ${(file.size / 1024).toFixed(2)} KB
                            <br><i class="ti ti-compress"></i> Estimated after compression: ~${estimatedCompressed} KB
                        </div>
                    </div>
                `);
                    };
                    reader.readAsDataURL(file);
                }
            });

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
                        $('#thumbnailPreview').html(
                            '<div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 150px;"><i class="ti ti-cloud-upload fs-1 text-muted"></i></div>'
                            );
                        return;
                    }

                    let reader = new FileReader();
                    reader.onload = function(e) {
                        $('#thumbnailPreview').html(`
                    <img src="${e.target.result}" class="img-fluid rounded" style="max-height: 150px;">
                    <div class="small text-muted mt-1">Size: ${(file.size / 1024).toFixed(2)} KB</div>
                `);
                    };
                    reader.readAsDataURL(file);
                }
            });

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
                        $('#bannerPreview').html(
                            '<div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 150px;"><i class="ti ti-cloud-upload fs-1 text-muted"></i></div>'
                            );
                        return;
                    }

                    let reader = new FileReader();
                    reader.onload = function(e) {
                        $('#bannerPreview').html(`
                    <img src="${e.target.result}" class="img-fluid rounded" style="max-height: 150px; width: 100%; object-fit: cover;">
                    <div class="small text-muted mt-1">Size: ${(file.size / 1024).toFixed(2)} KB</div>
                `);
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
            $('#categoryForm').on('submit', function(e) {
                e.preventDefault();

                if (formSubmitting) return false;

                let isValid = true;

                if (!$('#name').val().trim()) {
                    $('#name').addClass('is-invalid');
                    $('#name-error').text('Category name is required');
                    isValid = false;
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
                btn.html('<span class="spinner-border spinner-border-sm me-1"></span> Creating...');
                btn.prop('disabled', true);

                let formData = new FormData(this);

                $.ajax({
                    url: '{{ route('admin.categories.store') }}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            let message = response.message;
                            if (response.compression_stats) {
                                message +=
                                    `<br><small class="text-muted">Image optimized: ${response.compression_stats.original_size} → ${response.compression_stats.compressed_size} (${response.compression_stats.ratio}% saved)</small>`;
                            }

                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                html: message,
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.href =
                                    '{{ route('admin.categories.index') }}';
                            });
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

            // Initial SEO preview
            updateSEOPreview();
        });
    </script>
@endpush
