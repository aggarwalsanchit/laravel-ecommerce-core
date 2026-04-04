{{-- resources/views/admin/sizes/edit.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Edit Size')

@section('content')
    <div class="page-content">
        <div class="page-container">
            <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                <div class="flex-grow-1">
                    <h4 class="fs-18 text-uppercase fw-bold mb-0">Edit Size: {{ $size->name }}</h4>
                </div>
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.sizes.index') }}">Sizes</a></li>
                        <li class="breadcrumb-item active">Edit Size</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-10 mx-auto">
                    <div class="card">
                        <div class="card-header border-bottom border-dashed">
                            <h4 class="card-title">Edit Size Information</h4>
                            <p class="text-muted mb-0">Update size details and settings</p>
                        </div>
                        <div class="card-body">
                            <form id="sizeForm" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Size Name <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light">
                                                    <i class="ti ti-ruler"></i>
                                                </span>
                                                <input type="text" class="form-control" id="name" name="name"
                                                    value="{{ old('name', $size->name) }}" autofocus>
                                            </div>
                                            <div class="invalid-feedback" id="name-error"></div>
                                            <small class="text-muted">
                                                <i class="ti ti-link"></i> Current URL slug:
                                                <code>{{ $size->slug }}</code>
                                            </small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="code" class="form-label">Size Code <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light">
                                                    <i class="ti ti-barcode"></i>
                                                </span>
                                                <input type="text" class="form-control" id="code" name="code"
                                                    value="{{ old('code', $size->code) }}">
                                            </div>
                                            <div class="invalid-feedback" id="code-error"></div>
                                            <small class="text-muted">Unique identifier for the size (e.g., S, M, L,
                                                XL)</small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="order" class="form-label">Display Order</label>
                                            <input type="number" class="form-control" id="order" name="order"
                                                value="{{ old('order', $size->order) }}">
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
                                                    {{ old('status', $size->status) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="status">Active</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="description" class="form-label">Description</label>
                                            <textarea class="form-control" id="description" name="description" rows="4"
                                                placeholder="Detailed description of this size (e.g., fits chest size 34-36 inches)">{{ old('description', $size->description) }}</textarea>
                                            <div class="invalid-feedback" id="description-error"></div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Tabs for Images and SEO --}}
                                <ul class="nav nav-tabs mt-4" id="sizeTabs" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="images-tab" data-bs-toggle="tab"
                                            data-bs-target="#images" type="button" role="tab">
                                            <i class="ti ti-photo"></i> Image
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
                                                        <h6 class="mb-0">Size Image</h6>
                                                        <small class="text-muted">150x150px recommended</small>
                                                    </div>
                                                    <div class="card-body text-center">
                                                        <div id="imagePreview" class="mb-3">
                                                            @php
                                                                $imageExists = false;
                                                                $imageUrl = null;
                                                                if (
                                                                    $size->image &&
                                                                    Storage::disk('public')->exists(
                                                                        'sizes/' . $size->image,
                                                                    )
                                                                ) {
                                                                    $imageExists = true;
                                                                    $imageUrl = Storage::disk('public')->url(
                                                                        'sizes/' . $size->image,
                                                                    );
                                                                    $imageSize = Storage::disk('public')->size(
                                                                        'sizes/' . $size->image,
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
                                                        value="{{ old('meta_title', $size->meta_title) }}"
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
                                                        placeholder="SEO description (150-160 characters)">{{ old('meta_description', $size->meta_description) }}</textarea>
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
                                                <div class="text-primary fw-bold fs-5" id="seo-preview-title">
                                                    {{ $size->meta_title ?: $size->name }}</div>
                                                <div class="text-muted small" id="seo-preview-url">
                                                    {{ url('/size') }}/{{ $size->slug }}</div>
                                                <div class="text-muted small mt-2" id="seo-preview-desc">
                                                    {{ Str::limit($size->meta_description ?: $size->description ?: 'Size description will appear here...', 160) }}
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
                                        <i class="ti ti-edit me-1"></i> Update Size
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
                let title = $('#meta_title').val() || $('#name').val() || 'Size Name';
                let desc = $('#meta_description').val() || $('#description').val() ||
                    'Size description will appear here...';
                let slug = $('#name').val().toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '') ||
                    '{{ $size->slug }}';

                $('#seo-preview-title').text(title.substring(0, 70));
                $('#seo-preview-url').text('{{ url('/size') }}/' + slug);
                $('#seo-preview-desc').text(desc.substring(0, 160));
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

            $('#name').on('keyup', function() {
                updateSEOPreview();
            });

            $('#description').on('keyup', function() {
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

            // Remove image checkbox handler
            $('#remove_image').on('change', function() {
                if ($(this).is(':checked')) {
                    $('#imagePreview').html(
                        '<div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 150px; flex-direction: column;"><i class="ti ti-trash fs-1 text-danger"></i><small class="text-danger mt-2">Image will be removed on save</small></div>'
                        );
                    $('#image').val('');
                } else {
                    @if (isset($size->image) && Storage::disk('public')->exists('sizes/' . $size->image))
                        $('#imagePreview').html(`
                    <div>
                        <img src="{{ Storage::disk('public')->url('sizes/' . $size->image) }}" 
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

                if (!$('#name').val().trim()) {
                    $('#name').addClass('is-invalid');
                    $('#name-error').text('Size name is required');
                    isValid = false;
                }

                if (!$('#code').val().trim()) {
                    $('#code').addClass('is-invalid');
                    $('#code-error').text('Size code is required');
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
                    url: '{{ route('admin.sizes.update', $size->id) }}',
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
                                    '{{ route('admin.sizes.index') }}';
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
