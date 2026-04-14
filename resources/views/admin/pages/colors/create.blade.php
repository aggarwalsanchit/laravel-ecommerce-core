{{-- resources/views/admin/pages/colors/create.blade.php --}}
@extends('management.layouts.app')

@section('title', 'Add New Color')

@section('content')
    <div class="page-content">
        <div class="page-container">
            <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                <div class="flex-grow-1">
                    <h4 class="fs-18 text-uppercase fw-bold mb-0">Add New Color</h4>
                </div>
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.colors.index') }}">Colors</a></li>
                        <li class="breadcrumb-item active">Add Color</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header border-bottom border-dashed">
                            <h4 class="card-title">Color Information</h4>
                            <p class="text-muted mb-0">Create a new color with hex code and optimized image</p>
                        </div>
                        <div class="card-body">
                            <form id="colorForm" enctype="multipart/form-data">
                                @csrf

                                {{-- Basic Information Tabs --}}
                                <ul class="nav nav-tabs" id="colorTabs" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="basic-tab" data-bs-toggle="tab"
                                            data-bs-target="#basic" type="button" role="tab">
                                            <i class="ti ti-info-circle"></i> Basic Info
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
                                                    <label for="name" class="form-label">Color Name <span
                                                            class="text-danger">*</span></label>
                                                    <div class="input-group">
                                                        <span class="input-group-text bg-light">
                                                            <i class="ti ti-palette"></i>
                                                        </span>
                                                        <input type="text" class="form-control" id="name"
                                                            name="name" placeholder="Enter color name" autofocus>
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
                                                    <label for="code" class="form-label">Hex Code <span
                                                            class="text-danger">*</span></label>
                                                    <div class="input-group">
                                                        <span class="input-group-text bg-light">
                                                            <i class="ti ti-color-swatch"></i>
                                                        </span>
                                                        <input type="text" class="form-control" id="code"
                                                            name="code" placeholder="#FF0000" maxlength="7">
                                                        <span class="input-group-text" id="colorPreview"
                                                            style="width: 40px; background-color: #000000;"></span>
                                                    </div>
                                                    <div class="invalid-feedback" id="code-error"></div>
                                                    <small class="text-muted">Enter valid hex code (e.g., #FF0000 for
                                                        Red)</small>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="rgb" class="form-label">RGB Value</label>
                                                    <input type="text" class="form-control" id="rgb" name="rgb"
                                                        placeholder="rgb(255, 0, 0)">
                                                    <div class="invalid-feedback" id="rgb-error"></div>
                                                    <small class="text-muted">Optional: rgb(255, 0, 0)</small>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="hsl" class="form-label">HSL Value</label>
                                                    <input type="text" class="form-control" id="hsl"
                                                        name="hsl" placeholder="hsl(0, 100%, 50%)">
                                                    <div class="invalid-feedback" id="hsl-error"></div>
                                                    <small class="text-muted">Optional: hsl(0, 100%, 50%)</small>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="order" class="form-label">Display Order</label>
                                                    <input type="number" class="form-control" id="order"
                                                        name="order" value="0">
                                                    <div class="invalid-feedback" id="order-error"></div>
                                                    <small class="text-muted">Lower numbers appear first</small>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Display Settings</label>
                                                    <div class="d-flex flex-wrap gap-3">
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input"
                                                                id="status" name="status" value="1" checked>
                                                            <label class="form-check-label" for="status">
                                                                <i class="ti ti-circle-check"></i> Active
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input"
                                                                id="is_featured" name="is_featured" value="1">
                                                            <label class="form-check-label" for="is_featured">
                                                                <i class="ti ti-star"></i> Featured
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input"
                                                                id="is_popular" name="is_popular" value="1">
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
                                                        placeholder="Color description for SEO and product pages"></textarea>
                                                    <div class="invalid-feedback" id="description-error"></div>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="card border">
                                                    <div class="card-header bg-light">
                                                        <h6 class="mb-0">Color Swatch Image</h6>
                                                        <small class="text-muted">Optional image for color swatch</small>
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
                                                        <div class="mt-2">
                                                            <label class="form-label small">Alt Text</label>
                                                            <input type="text" class="form-control form-control-sm"
                                                                id="image_alt" name="image_alt"
                                                                placeholder="Describe the color swatch">
                                                            <small class="text-muted">Helps with accessibility</small>
                                                        </div>
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
                                                        name="focus_keyword" placeholder="Primary keyword for this color">
                                                    <div class="invalid-feedback" id="focus_keyword-error"></div>
                                                    <small class="text-muted">Main keyword you want to rank for</small>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="meta_title" class="form-label">Meta Title</label>
                                                    <input type="text" class="form-control" id="meta_title"
                                                        name="meta_title" placeholder="SEO title (50-60 characters)">
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
                                                    <small class="text-muted">Comma separated keywords</small>
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
                                                        name="og_title" placeholder="Title for social sharing">
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
                                                <h6 class="mb-0"><i class="ti ti-eye"></i> SEO Preview (Google Search
                                                    Result)</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="text-primary fw-bold fs-5" id="seo-preview-title">Color Name
                                                </div>
                                                <div class="text-muted small" id="seo-preview-url">
                                                    {{ url('/color') }}/color-slug</div>
                                                <div class="text-muted small mt-2" id="seo-preview-desc">Color description
                                                    will appear here...</div>
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
                                                        <i class="ti ti-photo text-muted"></i>
                                                    </div>
                                                    <div>
                                                        <div class="fw-semibold" id="social-preview-title">Color Name
                                                        </div>
                                                        <div class="text-muted small" id="social-preview-desc">Color
                                                            description...</div>
                                                        <div class="text-muted small" id="social-preview-url">
                                                            {{ url('/color') }}/color-slug</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2 mt-4">
                                    <a href="{{ route('admin.colors.index') }}" class="btn btn-danger">
                                        <i class="ti ti-x me-1"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary" id="submitBtn">
                                        <i class="ti ti-plus me-1"></i> Create Color
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

            // Hex code validation function - FIXED
            function isValidHexCode(code) {
                if (!code) return false;
                var hexPattern = /^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/;
                return hexPattern.test(code);
            }

            // Auto-generate slug preview
            $('#name').on('keyup', function() {
                var slug = $(this).val().toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
                $('#slug-preview').text(slug || 'color-slug');
                updateSEOPreview();
                updateSocialPreview();
                $(this).removeClass('is-invalid');
                $('#name-error').text('');
            });

            // Color preview
            $('#code').on('input', function() {
                var code = $(this).val();
                if (isValidHexCode(code)) {
                    $('#colorPreview').css('background-color', code);
                    $(this).removeClass('is-invalid');
                    $('#code-error').text('');
                } else {
                    $('#colorPreview').css('background-color', '#000000');
                }
            });

            // Update SEO preview
            function updateSEOPreview() {
                var title = $('#meta_title').val() || $('#name').val() || 'Color Name';
                var desc = $('#meta_description').val() || $('#description').val() ||
                    'Color description will appear here...';
                var slug = $('#name').val().toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '') ||
                    'color-slug';

                $('#seo-preview-title').text(title.substring(0, 70));
                $('#seo-preview-url').text('{{ url('/') }}/color/' + slug);
                $('#seo-preview-desc').text(desc.substring(0, 160));
            }

            // Update social preview
            function updateSocialPreview() {
                var title = $('#og_title').val() || $('#meta_title').val() || $('#name').val() || 'Color Name';
                var desc = $('#og_description').val() || $('#meta_description').val() || $('#description').val() ||
                    'Color description...';
                var slug = $('#name').val().toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '') ||
                    'color-slug';

                $('#social-preview-title').text(title.substring(0, 60));
                $('#social-preview-desc').text(desc.substring(0, 200));
                $('#social-preview-url').text('{{ url('/') }}/color/' + slug);
            }

            // Character counters
            $('#meta_title').on('keyup', function() {
                var length = $(this).val().length;
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
                var length = $(this).val().length;
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
                var file = event.target.files[0];
                if (file) {
                    if (file.size > 2 * 1024 * 1024) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'File Too Large',
                            text: 'Image size should be less than 2MB.',
                            confirmButtonColor: '#d33'
                        });
                        $(this).val('');
                        $('#imagePreview').html(
                            '<div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 150px;"><i class="ti ti-cloud-upload fs-1 text-muted"></i></div>'
                            );
                        return;
                    }
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#imagePreview').html('<img src="' + e.target.result +
                            '" class="img-fluid rounded" style="max-height: 150px;"><div class="small text-muted mt-1">' +
                            (file.size / 1024).toFixed(2) + ' KB</div>');
                    };
                    reader.readAsDataURL(file);
                }
            });

            // OG Image preview
            $('#og_image').on('change', function(event) {
                var file = event.target.files[0];
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
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#ogImagePreview').html('<img src="' + e.target.result +
                            '" class="img-fluid rounded border" style="max-height: 100px;"><div class="small text-muted mt-1">' +
                            (file.size / 1024).toFixed(2) + ' KB</div>');
                        $('#socialImagePreview').html('<img src="' + e.target.result +
                            '" style="width: 120px; height: 63px; object-fit: cover;">');
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
            $('#colorForm').on('submit', function(e) {
                e.preventDefault();

                if (formSubmitting) return false;

                var isValid = true;
                var nameValue = $('#name').val().trim();
                var codeValue = $('#code').val().trim();

                if (!nameValue) {
                    $('#name').addClass('is-invalid');
                    $('#name-error').text('Color name is required');
                    isValid = false;
                }

                if (!codeValue) {
                    $('#code').addClass('is-invalid');
                    $('#code-error').text('Hex code is required');
                    isValid = false;
                } else if (!isValidHexCode(codeValue)) {
                    $('#code').addClass('is-invalid');
                    $('#code-error').text('Please enter a valid hex code (e.g., #FF0000)');
                    isValid = false;
                }

                if (!isValid) {
                    $('html, body').animate({
                        scrollTop: $('.is-invalid:first').offset().top - 100
                    }, 500);
                    return false;
                }

                formSubmitting = true;
                var btn = $('#submitBtn');
                var originalText = btn.html();
                btn.html('<span class="spinner-border spinner-border-sm me-1"></span> Creating...');
                btn.prop('disabled', true);

                var formData = new FormData(this);

                $.ajax({
                    url: '{{ route('admin.colors.store') }}',
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
                            }).then(function() {
                                window.location.href =
                                    '{{ route('admin.colors.index') }}';
                            });
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
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
