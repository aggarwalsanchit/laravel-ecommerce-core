{{-- resources/views/admin/colors/create.blade.php --}}
@extends('admin.layouts.app')

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
                <div class="col-lg-8 mx-auto">
                    <div class="card">
                        <div class="card-header border-bottom border-dashed">
                            <h4 class="card-title">Color Information</h4>
                            <p class="text-muted mb-0">Create a new color for your products</p>
                        </div>
                        <div class="card-body">
                            <form id="colorForm">
                                @csrf

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Color Name <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light">
                                                    <i class="ti ti-palette"></i>
                                                </span>
                                                <input type="text" class="form-control" id="name" name="name"
                                                    placeholder="Enter color name (e.g., Red, Blue, Black)" autofocus>
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
                                            <label for="code" class="form-label">Color Code <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light">
                                                    <i class="ti ti-barcode"></i>
                                                </span>
                                                <input type="text" class="form-control" id="code" name="code"
                                                    placeholder="Enter color code (e.g., RED, BLUE, BLK)">
                                            </div>
                                            <div class="invalid-feedback" id="code-error"></div>
                                            <small class="text-muted">Unique identifier for the color (e.g., RED, BLUE,
                                                BLACK)</small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="hex_code" class="form-label">Hex Code <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light">
                                                    <i class="ti ti-color-swatch"></i>
                                                </span>
                                                <input type="text" class="form-control" id="hex_code" name="hex_code"
                                                    placeholder="#FF0000" maxlength="7">
                                                <button class="btn btn-outline-secondary" type="button"
                                                    id="colorPickerBtn">
                                                    <i class="ti ti-eye"></i>
                                                </button>
                                            </div>
                                            <div class="invalid-feedback" id="hex_code-error"></div>
                                            <small class="text-muted">Hex color code (e.g., #FF0000 for Red)</small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Color Preview</label>
                                            <div class="border rounded p-3 text-center" style="background: #f8f9fa;">
                                                <div id="colorPreview"
                                                    style="width: 80px; height: 80px; background: #FFFFFF; border-radius: 12px; margin: 0 auto; border: 2px solid #dee2e6; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                                </div>
                                                <div class="mt-2">
                                                    <code id="hexPreview" class="small">#FFFFFF</code>
                                                </div>
                                            </div>
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
                                            <textarea class="form-control" id="description" name="description" rows="3"
                                                placeholder="Optional description of this color"></textarea>
                                            <div class="invalid-feedback" id="description-error"></div>
                                        </div>
                                    </div>
                                </div>

                                {{-- SEO Tab --}}
                                <ul class="nav nav-tabs mt-4" id="colorTabs" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="seo-tab" data-bs-toggle="tab"
                                            data-bs-target="#seo" type="button" role="tab">
                                            <i class="ti ti-chart-line"></i> SEO Settings
                                        </button>
                                    </li>
                                </ul>

                                <div class="tab-content mt-3">
                                    {{-- SEO Tab --}}
                                    <div class="tab-pane fade show active" id="seo" role="tabpanel">
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
                                                <div class="text-primary fw-bold fs-5" id="seo-preview-title">Color Name
                                                </div>
                                                <div class="text-muted small" id="seo-preview-url">
                                                    {{ url('/color') }}/color-slug</div>
                                                <div class="text-muted small mt-2" id="seo-preview-desc">Color description
                                                    will appear here...</div>
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

            // Auto-generate slug preview
            $('#name').on('keyup', function() {
                let slug = $(this).val().toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
                $('#slug-preview').text(slug || 'color-slug');
                updateSEOPreview();
            });

            // Hex code preview
            $('#hex_code').on('input', function() {
                let hex = $(this).val();
                if (hex.match(/^#[a-fA-F0-9]{6}$/)) {
                    $('#colorPreview').css('background', hex);
                    $('#hexPreview').text(hex);
                } else if (hex.match(/^#[a-fA-F0-9]{3}$/)) {
                    let expanded = '#' + hex[1] + hex[1] + hex[2] + hex[2] + hex[3] + hex[3];
                    $('#colorPreview').css('background', expanded);
                    $('#hexPreview').text(expanded);
                } else {
                    $('#colorPreview').css('background', '#FFFFFF');
                    $('#hexPreview').text('Invalid hex');
                }
                updateSEOPreview();
            });

            // Color picker button
            $('#colorPickerBtn').on('click', function() {
                let hex = $('#hex_code').val();
                if (hex && hex.match(/^#[a-fA-F0-9]{3,6}$/)) {
                    Swal.fire({
                        title: 'Color Preview',
                        html: `<div style="width: 200px; height: 200px; background: ${hex}; border-radius: 12px; margin: 0 auto; border: 2px solid #dee2e6;"></div>
                       <div class="mt-3"><code>${hex}</code></div>`,
                        showConfirmButton: true,
                        confirmButtonText: 'Close'
                    });
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Invalid Color',
                        text: 'Please enter a valid hex color code (e.g., #FF0000)',
                        confirmButtonColor: '#d33'
                    });
                }
            });

            // Update SEO preview
            function updateSEOPreview() {
                let title = $('#meta_title').val() || $('#name').val() || 'Color Name';
                let desc = $('#meta_description').val() || $('#description').val() ||
                    'Color description will appear here...';
                let slug = $('#name').val().toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '') ||
                    'color-slug';

                $('#seo-preview-title').text(title.substring(0, 70));
                $('#seo-preview-url').text('{{ url('/color') }}/' + slug);
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

            // Remove error on input
            $('input, select, textarea').on('input change', function() {
                $(this).removeClass('is-invalid');
                $('#' + $(this).attr('name') + '-error').text('');
            });

            // Form submission
            $('#colorForm').on('submit', function(e) {
                e.preventDefault();

                if (formSubmitting) return false;

                let isValid = true;

                if (!$('#name').val().trim()) {
                    $('#name').addClass('is-invalid');
                    $('#name-error').text('Color name is required');
                    isValid = false;
                }

                if (!$('#code').val().trim()) {
                    $('#code').addClass('is-invalid');
                    $('#code-error').text('Color code is required');
                    isValid = false;
                }

                let hex = $('#hex_code').val();
                if (!hex) {
                    $('#hex_code').addClass('is-invalid');
                    $('#hex_code-error').text('Hex code is required');
                    isValid = false;
                } else if (!hex.match(/^#[a-fA-F0-9]{6}$/i) && !hex.match(/^#[a-fA-F0-9]{3}$/i)) {
                    $('#hex_code').addClass('is-invalid');
                    $('#hex_code-error').text('Invalid hex color code (e.g., #FF0000 or #F00)');
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

                $.ajax({
                    url: '{{ route('admin.colors.store') }}',
                    type: 'POST',
                    data: $(this).serialize(),
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
                                    '{{ route('admin.colors.index') }}';
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
        #colorPreview {
            transition: background 0.3s ease;
        }

        .btn-outline-secondary:hover {
            background-color: #6c757d;
            border-color: #6c757d;
            color: #fff;
        }
    </style>
@endpush
