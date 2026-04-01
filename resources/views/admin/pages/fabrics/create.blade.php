{{-- resources/views/admin/fabrics/create.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Add New Fabric')

@section('content')
    <div class="page-content">
        <div class="page-container">
            <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                <div class="flex-grow-1">
                    <h4 class="fs-18 text-uppercase fw-bold mb-0">Add New Fabric</h4>
                </div>
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.fabrics.index') }}">Fabrics</a></li>
                        <li class="breadcrumb-item active">Add Fabric</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="card">
                        <div class="card-header border-bottom border-dashed">
                            <h4 class="card-title">Fabric Information</h4>
                            <p class="text-muted mb-0">Create a new fabric type for your products</p>
                        </div>
                        <div class="card-body">
                            <form id="fabricForm" enctype="multipart/form-data">
                                @csrf

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Fabric Name <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light">
                                                    <i class="ti ti-fabric"></i>
                                                </span>
                                                <input type="text" class="form-control" id="name" name="name"
                                                    placeholder="Enter fabric name (e.g., Cotton, Polyester, Silk)"
                                                    autofocus>
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
                                            <label for="code" class="form-label">Fabric Code <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light">
                                                    <i class="ti ti-barcode"></i>
                                                </span>
                                                <input type="text" class="form-control" id="code" name="code"
                                                    placeholder="Enter fabric code (e.g., COT, POL, SIL)">
                                            </div>
                                            <div class="invalid-feedback" id="code-error"></div>
                                            <small class="text-muted">Unique identifier for the fabric (e.g., COT for
                                                Cotton)</small>
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

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="description" class="form-label">Description</label>
                                            <textarea class="form-control" id="description" name="description" rows="4"
                                                placeholder="Describe the fabric characteristics, feel, breathability, etc."></textarea>
                                            <div class="invalid-feedback" id="description-error"></div>
                                            <small class="text-muted">Detailed description of the fabric for product
                                                pages</small>
                                        </div>
                                    </div>
                                </div>

                                {{-- Tabs for Images and SEO --}}
                                <ul class="nav nav-tabs mt-4" id="fabricTabs" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="images-tab" data-bs-toggle="tab"
                                            data-bs-target="#images" type="button" role="tab">
                                            <i class="ti ti-photo"></i> Fabric Image
                                            <span class="badge bg-primary ms-1">Optional</span>
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="care-tab" data-bs-toggle="tab"
                                            data-bs-target="#care" type="button" role="tab">
                                            <i class="ti ti-wash"></i> Care Instructions
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
                                    {{-- Image Tab --}}
                                    <div class="tab-pane fade show active" id="images" role="tabpanel">
                                        <div class="row">
                                            <div class="col-md-6 mx-auto">
                                                <div class="card border">
                                                    <div class="card-header bg-light">
                                                        <h6 class="mb-0">Fabric Image</h6>
                                                        <small class="text-muted">150x150px recommended</small>
                                                    </div>
                                                    <div class="card-body text-center">
                                                        <div id="imagePreview" class="mb-3">
                                                            <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                                style="height: 150px; flex-direction: column;">
                                                                <i class="ti ti-cloud-upload fs-1 text-muted"></i>
                                                                <small class="text-muted mt-2">No image uploaded</small>
                                                            </div>
                                                        </div>
                                                        <input type="file" class="form-control" id="image"
                                                            name="image" accept="image/*">
                                                        <div class="invalid-feedback" id="image-error"></div>
                                                        <div class="alert alert-info mt-2 small">
                                                            <i class="ti ti-info-circle me-1"></i>
                                                            Image will be automatically compressed to ~30KB. Max size 2MB.
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Care Instructions Tab --}}
                                    <div class="tab-pane fade" id="care" role="tabpanel">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="alert alert-info">
                                                    <i class="ti ti-info-circle me-2"></i>
                                                    These care instructions will be displayed on product pages for fabrics
                                                    of this type.
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Washing Instructions</label>
                                                    <select class="form-select" id="washing" name="washing">
                                                        <option value="machine_wash_cold">Machine Wash Cold</option>
                                                        <option value="machine_wash_warm">Machine Wash Warm</option>
                                                        <option value="hand_wash">Hand Wash Only</option>
                                                        <option value="dry_clean">Dry Clean Only</option>
                                                        <option value="do_not_wash">Do Not Wash</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Bleaching</label>
                                                    <select class="form-select" id="bleaching" name="bleaching">
                                                        <option value="do_not_bleach">Do Not Bleach</option>
                                                        <option value="non_chlorine">Non-Chlorine Bleach Only</option>
                                                        <option value="any_bleach">Any Bleach Allowed</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Drying Instructions</label>
                                                    <select class="form-select" id="drying" name="drying">
                                                        <option value="tumble_dry_low">Tumble Dry Low</option>
                                                        <option value="tumble_dry_medium">Tumble Dry Medium</option>
                                                        <option value="line_dry">Line Dry</option>
                                                        <option value="do_not_tumble">Do Not Tumble Dry</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Ironing</label>
                                                    <select class="form-select" id="ironing" name="ironing">
                                                        <option value="low_heat">Low Heat</option>
                                                        <option value="medium_heat">Medium Heat</option>
                                                        <option value="high_heat">High Heat</option>
                                                        <option value="do_not_iron">Do Not Iron</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label class="form-label">Additional Care Tips</label>
                                                    <textarea class="form-control" id="care_tips" name="care_tips" rows="3"
                                                        placeholder="Additional care instructions for this fabric type..."></textarea>
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
                                        </div>

                                        {{-- SEO Preview --}}
                                        <div class="card border mt-3">
                                            <div class="card-header bg-light">
                                                <h6 class="mb-0"><i class="ti ti-eye"></i> SEO Preview</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="text-primary fw-bold fs-5" id="seo-preview-title">Fabric Name
                                                </div>
                                                <div class="text-muted small" id="seo-preview-url">
                                                    {{ url('/fabric') }}/fabric-slug</div>
                                                <div class="text-muted small mt-2" id="seo-preview-desc">Fabric
                                                    description will appear here...</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2 mt-4">
                                    <a href="{{ route('admin.fabrics.index') }}" class="btn btn-danger">
                                        <i class="ti ti-x me-1"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary" id="submitBtn">
                                        <i class="ti ti-plus me-1"></i> Create Fabric
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
                $('#slug-preview').text(slug || 'fabric-slug');
                updateSEOPreview();
            });

            // Update SEO preview
            function updateSEOPreview() {
                let title = $('#meta_title').val() || $('#name').val() || 'Fabric Name';
                let desc = $('#meta_description').val() || $('#description').val() ||
                    'Fabric description will appear here...';
                let slug = $('#name').val().toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '') ||
                    'fabric-slug';

                $('#seo-preview-title').text(title.substring(0, 70));
                $('#seo-preview-url').text('{{ url('/fabric') }}/' + slug);
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

            // Image preview with size validation
            $('#image').on('change', function(event) {
                let file = event.target.files[0];
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
                            '<div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 150px; flex-direction: column;"><i class="ti ti-cloud-upload fs-1 text-muted"></i><small class="text-muted mt-2">No image uploaded</small></div>'
                            );
                        return;
                    }

                    let reader = new FileReader();
                    reader.onload = function(e) {
                        let estimatedCompressed = Math.round(file.size / 1024 * 0.7);
                        $('#imagePreview').html(`
                    <div>
                        <img src="${e.target.result}" class="img-fluid rounded mb-2" style="max-height: 150px;">
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

            // Remove error on input
            $('input, select, textarea').on('input change', function() {
                $(this).removeClass('is-invalid');
                $('#' + $(this).attr('name') + '-error').text('');
            });

            // Form submission
            $('#fabricForm').on('submit', function(e) {
                e.preventDefault();

                if (formSubmitting) return false;

                let isValid = true;

                if (!$('#name').val().trim()) {
                    $('#name').addClass('is-invalid');
                    $('#name-error').text('Fabric name is required');
                    isValid = false;
                }

                if (!$('#code').val().trim()) {
                    $('#code').addClass('is-invalid');
                    $('#code-error').text('Fabric code is required');
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
                    url: '{{ route('admin.fabrics.store') }}',
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
                                    '{{ route('admin.fabrics.index') }}';
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

@push('styles')
    <style>
        #imagePreview img {
            max-height: 150px;
            object-fit: cover;
        }
    </style>
@endpush
