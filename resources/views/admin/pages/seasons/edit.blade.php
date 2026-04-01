{{-- resources/views/admin/seasons/edit.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Edit Season')

@section('content')
    <div class="page-content">
        <div class="page-container">
            <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                <div class="flex-grow-1">
                    <h4 class="fs-18 text-uppercase fw-bold mb-0">Edit Season: {{ $season->name }}</h4>
                </div>
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.seasons.index') }}">Seasons</a></li>
                        <li class="breadcrumb-item active">Edit Season</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="card">
                        <div class="card-header border-bottom border-dashed">
                            <h4 class="card-title">Edit Season Information</h4>
                            <p class="text-muted mb-0">Update season details and settings</p>
                        </div>
                        <div class="card-body">
                            <form id="seasonForm" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Season Name <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light">
                                                    <i class="ti ti-calendar"></i>
                                                </span>
                                                <input type="text" class="form-control" id="name" name="name"
                                                    value="{{ old('name', $season->name) }}" autofocus>
                                            </div>
                                            <div class="invalid-feedback" id="name-error"></div>
                                            <small class="text-muted">
                                                <i class="ti ti-link"></i> Current URL slug:
                                                <code>{{ $season->slug }}</code>
                                            </small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="code" class="form-label">Season Code <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light">
                                                    <i class="ti ti-barcode"></i>
                                                </span>
                                                <input type="text" class="form-control" id="code" name="code"
                                                    value="{{ old('code', $season->code) }}">
                                            </div>
                                            <div class="invalid-feedback" id="code-error"></div>
                                            <small class="text-muted">Unique identifier for the season</small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="icon" class="form-label">Icon</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light">
                                                    <i class="ti ti-icon"></i>
                                                </span>
                                                <input type="text" class="form-control" id="icon" name="icon"
                                                    value="{{ old('icon', $season->icon) }}"
                                                    placeholder="Enter icon name (e.g., sun, snowflake)">
                                            </div>
                                            <div class="invalid-feedback" id="icon-error"></div>
                                            <div class="mt-1">
                                                <div id="icon-preview" class="d-inline-block">
                                                    @if ($season->icon)
                                                        <i class="ti ti-{{ $season->icon }} fs-4 text-primary"></i>
                                                        <span class="text-muted ms-1">Current: {{ $season->icon }}</span>
                                                    @endif
                                                </div>
                                                <a href="https://tabler.io/icons" target="_blank"
                                                    class="small text-primary ms-2">
                                                    <i class="ti ti-external-link"></i> Browse Icons
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="order" class="form-label">Display Order</label>
                                            <input type="number" class="form-control" id="order" name="order"
                                                value="{{ old('order', $season->order) }}">
                                            <div class="invalid-feedback" id="order-error"></div>
                                            <small class="text-muted">Lower numbers appear first</small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Status</label>
                                            <div class="form-check form-switch mt-2">
                                                <input type="checkbox" class="form-check-input" id="status"
                                                    name="status" value="1"
                                                    {{ old('status', $season->status) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="status">Active</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Current Season</label>
                                            <div class="form-check form-switch mt-2">
                                                <input type="checkbox" class="form-check-input" id="is_current"
                                                    name="is_current" value="1"
                                                    {{ old('is_current', $season->is_current) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_current">Set as Current
                                                    Season</label>
                                            </div>
                                            <small class="text-muted">Only one season can be current at a time</small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="start_date" class="form-label">Start Date</label>
                                            <input type="date" class="form-control" id="start_date" name="start_date"
                                                value="{{ old('start_date', $season->start_date ? $season->start_date->format('Y-m-d') : '') }}">
                                            <div class="invalid-feedback" id="start_date-error"></div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="end_date" class="form-label">End Date</label>
                                            <input type="date" class="form-control" id="end_date" name="end_date"
                                                value="{{ old('end_date', $season->end_date ? $season->end_date->format('Y-m-d') : '') }}">
                                            <div class="invalid-feedback" id="end_date-error"></div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="description" class="form-label">Description</label>
                                            <textarea class="form-control" id="description" name="description" rows="4"
                                                placeholder="Describe this season and what products are suitable for it...">{{ old('description', $season->description) }}</textarea>
                                            <div class="invalid-feedback" id="description-error"></div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Tabs for Images and SEO --}}
                                <ul class="nav nav-tabs mt-4" id="seasonTabs" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="images-tab" data-bs-toggle="tab"
                                            data-bs-target="#images" type="button" role="tab">
                                            <i class="ti ti-photo"></i> Season Image
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
                                                        <h6 class="mb-0">Season Image</h6>
                                                        <small class="text-muted">300x300px recommended</small>
                                                    </div>
                                                    <div class="card-body text-center">
                                                        <div id="imagePreview" class="mb-3">
                                                            @php
                                                                $imageExists = false;
                                                                $imageUrl = null;
                                                                if (
                                                                    $season->image &&
                                                                    Storage::disk('public')->exists(
                                                                        'seasons/' . $season->image,
                                                                    )
                                                                ) {
                                                                    $imageExists = true;
                                                                    $imageUrl = Storage::disk('public')->url(
                                                                        'seasons/' . $season->image,
                                                                    );
                                                                    $imageSize = Storage::disk('public')->size(
                                                                        'seasons/' . $season->image,
                                                                    );
                                                                }
                                                            @endphp

                                                            @if ($imageExists)
                                                                <div>
                                                                    <img src="{{ $imageUrl }}"
                                                                        class="img-fluid rounded mb-2"
                                                                        style="max-height: 150px; object-fit: cover;">
                                                                    <div class="small text-muted mt-1">
                                                                        <i class="ti ti-database"></i>
                                                                        Current size:
                                                                        @if ($imageSize >= 1048576)
                                                                            {{ round($imageSize / 1048576, 2) }} MB
                                                                        @elseif($imageSize >= 1024)
                                                                            {{ round($imageSize / 1024, 2) }} KB
                                                                        @else
                                                                            {{ $imageSize }} bytes
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @else
                                                                <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                                    style="height: 150px; flex-direction: column;">
                                                                    <i class="ti ti-cloud-upload fs-1 text-muted"></i>
                                                                    <small class="text-muted mt-2">No image
                                                                        uploaded</small>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <input type="file" class="form-control" id="image"
                                                            name="image" accept="image/*">
                                                        <div class="invalid-feedback" id="image-error"></div>
                                                        @if ($imageExists)
                                                            <div class="form-check mt-2">
                                                                <input type="checkbox" class="form-check-input"
                                                                    id="remove_image" name="remove_image" value="1">
                                                                <label class="form-check-label text-danger"
                                                                    for="remove_image">
                                                                    <i class="ti ti-trash"></i> Remove current image
                                                                </label>
                                                            </div>
                                                        @endif
                                                        <div class="alert alert-info mt-2 small">
                                                            <i class="ti ti-info-circle me-1"></i>
                                                            Upload new image to replace current one. Max size 2MB.
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
                                                        name="meta_title"
                                                        value="{{ old('meta_title', $season->meta_title) }}"
                                                        placeholder="SEO title (60-70 characters)">
                                                    <div class="invalid-feedback" id="meta_title-error"></div>
                                                    <small class="text-muted" id="metaTitleCount">0/70 characters</small>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="meta_description" class="form-label">Meta
                                                        Description</label>
                                                    <textarea class="form-control" id="meta_description" name="meta_description" rows="2"
                                                        placeholder="SEO description (150-160 characters)">{{ old('meta_description', $season->meta_description) }}</textarea>
                                                    <div class="invalid-feedback" id="meta_description-error"></div>
                                                    <small class="text-muted" id="metaDescCount">0/160 characters</small>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="meta_keywords" class="form-label">Meta Keywords</label>
                                                    <input type="text" class="form-control" id="meta_keywords"
                                                        name="meta_keywords"
                                                        value="{{ old('meta_keywords', $season->meta_keywords) }}"
                                                        placeholder="keyword1, keyword2, keyword3">
                                                    <div class="invalid-feedback" id="meta_keywords-error"></div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- SEO Preview --}}
                                        <div class="card border mt-3">
                                            <div class="card-header bg-light">
                                                <h6 class="mb-0"><i class="ti ti-eye"></i> SEO Preview</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="text-primary fw-bold fs-5" id="seo-preview-title">
                                                    {{ $season->meta_title ?: $season->name }}</div>
                                                <div class="text-muted small" id="seo-preview-url">
                                                    {{ url('/season') }}/{{ $season->slug }}</div>
                                                <div class="text-muted small mt-2" id="seo-preview-desc">
                                                    {{ Str::limit($season->meta_description ?: $season->description ?: 'Season description will appear here...', 160) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2 mt-4">
                                    <a href="{{ route('admin.seasons.index') }}" class="btn btn-danger">
                                        <i class="ti ti-x me-1"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary" id="submitBtn">
                                        <i class="ti ti-edit me-1"></i> Update Season
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

            // Initialize character counts
            function initCharCounts() {
                let metaTitleLength = $('#meta_title').val().length;
                $('#metaTitleCount').text(metaTitleLength + '/70 characters');
                if (metaTitleLength > 70) $('#metaTitleCount').addClass('text-danger');

                let metaDescLength = $('#meta_description').val().length;
                $('#metaDescCount').text(metaDescLength + '/160 characters');
                if (metaDescLength > 160) $('#metaDescCount').addClass('text-danger');
            }
            initCharCounts();

            // Update SEO preview
            function updateSEOPreview() {
                let title = $('#meta_title').val() || $('#name').val() || 'Season Name';
                let desc = $('#meta_description').val() || $('#description').val() ||
                    'Season description will appear here...';
                let slug = $('#name').val().toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '') ||
                    '{{ $season->slug }}';

                $('#seo-preview-title').text(title.substring(0, 70));
                $('#seo-preview-url').text('{{ url('/season') }}/' + slug);
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

            $('#name').on('keyup', function() {
                updateSEOPreview();
            });

            $('#description').on('keyup', function() {
                updateSEOPreview();
            });

            // Icon preview
            $('#icon').on('keyup', function() {
                let icon = $(this).val();
                if (icon) {
                    $('#icon-preview').html(
                        `<i class="ti ti-${icon} fs-4 text-primary"></i> <span class="text-muted ms-1">Preview: ${icon}</span>`
                        );
                } else if ('{{ $season->icon }}') {
                    $('#icon-preview').html(
                        `<i class="ti ti-{{ $season->icon }} fs-4 text-primary"></i> <span class="text-muted ms-1">Current: {{ $season->icon }}</span>`
                        );
                } else {
                    $('#icon-preview').html('');
                }
            });

            // Image preview
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
                        return;
                    }

                    let reader = new FileReader();
                    reader.onload = function(e) {
                        let estimatedCompressed = Math.round(file.size / 1024 * 0.7);
                        $('#imagePreview').html(`
                    <div>
                        <img src="${e.target.result}" class="img-fluid rounded mb-2" style="max-height: 150px;">
                        <div class="small text-muted mt-1">
                            <i class="ti ti-info-circle"></i> New image: ${(file.size / 1024).toFixed(2)} KB
                            <br><i class="ti ti-compress"></i> Estimated after compression: ~${estimatedCompressed} KB
                        </div>
                    </div>
                `);
                    };
                    reader.readAsDataURL(file);
                    $('#remove_image').prop('checked', false);
                }
            });

            // Remove image handler
            $('#remove_image').on('change', function() {
                if ($(this).is(':checked')) {
                    $('#imagePreview').html(
                        '<div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 150px; flex-direction: column;"><i class="ti ti-trash fs-1 text-danger"></i><small class="text-danger mt-2">Image will be removed on save</small></div>'
                        );
                    $('#image').val('');
                } else {
                    @if (isset($season->image) && Storage::disk('public')->exists('seasons/' . $season->image))
                        $('#imagePreview').html(`
                    <div>
                        <img src="{{ Storage::disk('public')->url('seasons/' . $season->image) }}" 
                             class="img-fluid rounded mb-2" 
                             style="max-height: 150px; object-fit: cover;">
                        <div class="small text-muted mt-1">
                            <i class="ti ti-database"></i> Current image
                        </div>
                    </div>
                `);
                    @endif
                }
            });

            // Date validation
            $('#end_date').on('change', function() {
                let startDate = $('#start_date').val();
                let endDate = $(this).val();

                if (startDate && endDate && endDate < startDate) {
                    $(this).addClass('is-invalid');
                    $('#end_date-error').text('End date must be after start date');
                } else {
                    $(this).removeClass('is-invalid');
                    $('#end_date-error').text('');
                }
            });

            // Remove error on input
            $('input, select, textarea').on('input change', function() {
                $(this).removeClass('is-invalid');
                $('#' + $(this).attr('name') + '-error').text('');
            });

            // Form submission
            $('#seasonForm').on('submit', function(e) {
                e.preventDefault();

                if (formSubmitting) return false;

                let isValid = true;

                if (!$('#name').val().trim()) {
                    $('#name').addClass('is-invalid');
                    $('#name-error').text('Season name is required');
                    isValid = false;
                }

                if (!$('#code').val().trim()) {
                    $('#code').addClass('is-invalid');
                    $('#code-error').text('Season code is required');
                    isValid = false;
                }

                let startDate = $('#start_date').val();
                let endDate = $('#end_date').val();

                if (startDate && endDate && endDate < startDate) {
                    $('#end_date').addClass('is-invalid');
                    $('#end_date-error').text('End date must be after start date');
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
                btn.html('<span class="spinner-border spinner-border-sm me-1"></span> Updating...');
                btn.prop('disabled', true);

                let formData = new FormData(this);

                $.ajax({
                    url: '{{ route('admin.seasons.update', $season->id) }}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Updated!',
                                text: response.message,
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.href =
                                    '{{ route('admin.seasons.index') }}';
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
