{{-- resources/views/admin/brands/create.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Add New Brand')

@section('content')
    <div class="page-content">
        <div class="page-container">
            <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                <div class="flex-grow-1">
                    <h4 class="fs-18 text-uppercase fw-bold mb-0">Add New Brand</h4>
                </div>
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.brands.index') }}">Brands</a></li>
                        <li class="breadcrumb-item active">Add Brand</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="card">
                        <div class="card-header border-bottom border-dashed">
                            <h4 class="card-title">Brand Information</h4>
                            <p class="text-muted mb-0">Create a new brand for your products</p>
                        </div>
                        <div class="card-body">
                            <form id="brandForm" enctype="multipart/form-data">
                                @csrf

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Brand Name <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light">
                                                    <i class="ti ti-brand"></i>
                                                </span>
                                                <input type="text" class="form-control" id="name" name="name"
                                                    placeholder="Enter brand name (e.g., Nike, Adidas, Zara)" autofocus>
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
                                            <label for="code" class="form-label">Brand Code <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light">
                                                    <i class="ti ti-barcode"></i>
                                                </span>
                                                <input type="text" class="form-control" id="code" name="code"
                                                    placeholder="Enter brand code (e.g., NKE, ADD, ZRA)">
                                            </div>
                                            <div class="invalid-feedback" id="code-error"></div>
                                            <small class="text-muted">Unique identifier for the brand</small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="website" class="form-label">Website</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light">
                                                    <i class="ti ti-world"></i>
                                                </span>
                                                <input type="url" class="form-control" id="website" name="website"
                                                    placeholder="https://www.example.com">
                                            </div>
                                            <div class="invalid-feedback" id="website-error"></div>
                                            <small class="text-muted">Official brand website URL</small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light">
                                                    <i class="ti ti-mail"></i>
                                                </span>
                                                <input type="email" class="form-control" id="email" name="email"
                                                    placeholder="contact@brand.com">
                                            </div>
                                            <div class="invalid-feedback" id="email-error"></div>
                                            <small class="text-muted">Brand contact email</small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="phone" class="form-label">Phone</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light">
                                                    <i class="ti ti-phone"></i>
                                                </span>
                                                <input type="text" class="form-control" id="phone" name="phone"
                                                    placeholder="+1 234 567 8900">
                                            </div>
                                            <div class="invalid-feedback" id="phone-error"></div>
                                            <small class="text-muted">Brand contact phone number</small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="order" class="form-label">Display Order</label>
                                            <input type="number" class="form-control" id="order" name="order"
                                                value="0">
                                            <div class="invalid-feedback" id="order-error"></div>
                                            <small class="text-muted">Lower numbers appear first</small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Status</label>
                                            <div class="form-check form-switch mt-2">
                                                <input type="checkbox" class="form-check-input" id="status"
                                                    name="status" value="1" checked>
                                                <label class="form-check-label" for="status">Active</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Featured</label>
                                            <div class="form-check form-switch mt-2">
                                                <input type="checkbox" class="form-check-input" id="is_featured"
                                                    name="is_featured" value="1">
                                                <label class="form-check-label" for="is_featured">Show as Featured</label>
                                            </div>
                                            <small class="text-muted">Featured brands appear on homepage</small>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="address" class="form-label">Address</label>
                                            <textarea class="form-control" id="address" name="address" rows="2"
                                                placeholder="Brand headquarters address"></textarea>
                                            <div class="invalid-feedback" id="address-error"></div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="description" class="form-label">Description</label>
                                            <textarea class="form-control" id="description" name="description" rows="4"
                                                placeholder="Describe the brand, its history, products, etc."></textarea>
                                            <div class="invalid-feedback" id="description-error"></div>
                                            <small class="text-muted">Detailed description for SEO and brand page</small>
                                        </div>
                                    </div>
                                </div>

                                {{-- Tabs for Images and SEO --}}
                                <ul class="nav nav-tabs mt-4" id="brandTabs" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="images-tab" data-bs-toggle="tab"
                                            data-bs-target="#images" type="button" role="tab">
                                            <i class="ti ti-photo"></i> Images
                                            <span class="badge bg-primary ms-1">Optional</span>
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
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="card border">
                                                    <div class="card-header bg-light">
                                                        <h6 class="mb-0">Brand Logo</h6>
                                                        <small class="text-muted">150x150px recommended</small>
                                                    </div>
                                                    <div class="card-body text-center">
                                                        <div id="logoPreview" class="mb-3">
                                                            <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                                style="height: 150px; flex-direction: column;">
                                                                <i class="ti ti-cloud-upload fs-1 text-muted"></i>
                                                                <small class="text-muted mt-2">No logo uploaded</small>
                                                            </div>
                                                        </div>
                                                        <input type="file" class="form-control" id="logo"
                                                            name="logo" accept="image/*">
                                                        <div class="invalid-feedback" id="logo-error"></div>
                                                        <div class="alert alert-info mt-2 small">
                                                            <i class="ti ti-info-circle me-1"></i>
                                                            Logo will be automatically compressed. Max size 2MB.
                                                            <br>Recommended: Upload a square image with transparent
                                                            background.
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="card border">
                                                    <div class="card-header bg-light">
                                                        <h6 class="mb-0">Brand Banner</h6>
                                                        <small class="text-muted">1920x400px recommended</small>
                                                    </div>
                                                    <div class="card-body text-center">
                                                        <div id="bannerPreview" class="mb-3">
                                                            <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                                style="height: 100px; flex-direction: column;">
                                                                <i class="ti ti-cloud-upload fs-1 text-muted"></i>
                                                                <small class="text-muted mt-2">No banner uploaded</small>
                                                            </div>
                                                        </div>
                                                        <input type="file" class="form-control" id="banner"
                                                            name="banner" accept="image/*">
                                                        <div class="invalid-feedback" id="banner-error"></div>
                                                        <div class="alert alert-info mt-2 small">
                                                            <i class="ti ti-info-circle me-1"></i>
                                                            Banner for brand page header. Max size 3MB.
                                                        </div>
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
                                                    <label for="meta_title" class="form-label">Meta Title</label>
                                                    <input type="text" class="form-control" id="meta_title"
                                                        name="meta_title" placeholder="SEO title (60-70 characters)">
                                                    <div class="invalid-feedback" id="meta_title-error"></div>
                                                    <small class="text-muted" id="metaTitleCount">0/70 characters</small>
                                                    <div class="mt-1">
                                                        <small class="text-muted">Example: Nike Shoes - Buy Official Nike
                                                            Products | Store Name</small>
                                                    </div>
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
                                                    <small class="text-muted">Comma separated keywords for search
                                                        engines</small>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- SEO Preview --}}
                                        <div class="card border mt-3">
                                            <div class="card-header bg-light">
                                                <h6 class="mb-0"><i class="ti ti-eye"></i> SEO Preview</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="text-primary fw-bold fs-5" id="seo-preview-title">Brand Name
                                                </div>
                                                <div class="text-muted small" id="seo-preview-url">
                                                    {{ url('/brand') }}/brand-slug</div>
                                                <div class="text-muted small mt-2" id="seo-preview-desc">Brand description
                                                    will appear here...</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2 mt-4">
                                    <a href="{{ route('admin.brands.index') }}" class="btn btn-danger">
                                        <i class="ti ti-x me-1"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary" id="submitBtn">
                                        <i class="ti ti-plus me-1"></i> Create Brand
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
                $('#slug-preview').text(slug || 'brand-slug');
                updateSEOPreview();
            });

            // Update SEO preview
            function updateSEOPreview() {
                let title = $('#meta_title').val() || $('#name').val() || 'Brand Name';
                let desc = $('#meta_description').val() || $('#description').val() ||
                    'Brand description will appear here...';
                let slug = $('#name').val().toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '') ||
                    'brand-slug';

                $('#seo-preview-title').text(title.substring(0, 70));
                $('#seo-preview-url').text('{{ url('/brand') }}/' + slug);
                $('#seo-preview-desc').text(desc.substring(0, 160));
            }

            // Meta title counter
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

            // Meta description counter
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
                            '<div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 150px; flex-direction: column;"><i class="ti ti-cloud-upload fs-1 text-muted"></i><small class="text-muted mt-2">No logo uploaded</small></div>'
                            );
                        return;
                    }

                    let reader = new FileReader();
                    reader.onload = function(e) {
                        let estimatedCompressed = Math.round(file.size / 1024 * 0.7);
                        $('#logoPreview').html(`
                    <div>
                        <img src="${e.target.result}" class="img-fluid rounded mb-2" style="max-height: 150px; object-fit: contain;">
                        <div class="small text-muted mt-1">
                            <i class="ti ti-info-circle"></i> Original: ${(file.size / 1024).toFixed(2)} KB
                            <br><i class="ti ti-compress"></i> Estimated after compression: ~${estimatedCompressed} KB
                        </div>
                    </div>
                `);
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Banner preview
            $('#banner').on('change', function(event) {
                let file = event.target.files[0];
                if (file) {
                    if (file.size > 3 * 1024 * 1024) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'File Too Large',
                            text: 'Banner size should be less than 3MB.',
                            confirmButtonColor: '#d33'
                        });
                        $(this).val('');
                        $('#bannerPreview').html(
                            '<div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 100px; flex-direction: column;"><i class="ti ti-cloud-upload fs-1 text-muted"></i><small class="text-muted mt-2">No banner uploaded</small></div>'
                            );
                        return;
                    }

                    let reader = new FileReader();
                    reader.onload = function(e) {
                        $('#bannerPreview').html(`
                    <div>
                        <img src="${e.target.result}" class="img-fluid rounded mb-2" style="max-height: 100px; width: 100%; object-fit: cover;">
                        <div class="small text-muted mt-1">
                            <i class="ti ti-info-circle"></i> Original: ${(file.size / 1024).toFixed(2)} KB
                        </div>
                    </div>
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
            $('#brandForm').on('submit', function(e) {
                e.preventDefault();

                if (formSubmitting) return false;

                let isValid = true;

                if (!$('#name').val().trim()) {
                    $('#name').addClass('is-invalid');
                    $('#name-error').text('Brand name is required');
                    isValid = false;
                }

                if (!$('#code').val().trim()) {
                    $('#code').addClass('is-invalid');
                    $('#code-error').text('Brand code is required');
                    isValid = false;
                }

                if ($('#website').val() && !isValidUrl($('#website').val())) {
                    $('#website').addClass('is-invalid');
                    $('#website-error').text('Please enter a valid URL (e.g., https://example.com)');
                    isValid = false;
                }

                if ($('#email').val() && !isValidEmail($('#email').val())) {
                    $('#email').addClass('is-invalid');
                    $('#email-error').text('Please enter a valid email address');
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
                    url: '{{ route('admin.brands.store') }}',
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
                                window.location.href =
                                    '{{ route('admin.brands.index') }}';
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

            function isValidUrl(url) {
                try {
                    new URL(url);
                    return true;
                } catch {
                    return false;
                }
            }

            function isValidEmail(email) {
                return /^[^\s@]+@([^\s@]+\.)+[^\s@]+$/.test(email);
            }

            // Initial SEO preview
            updateSEOPreview();
        });
    </script>
@endpush

@push('styles')
    <style>
        #logoPreview img,
        #bannerPreview img {
            max-height: 150px;
            object-fit: contain;
        }
    </style>
@endpush
