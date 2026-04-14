{{-- resources/views/admin/pages/colors/edit.blade.php --}}
@extends('management.layouts.app')

@section('title', 'Edit Color - ' . $color->name)

@section('content')
    <div class="page-content">
        <div class="page-container">
            <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                <div class="flex-grow-1">
                    <h4 class="fs-18 text-uppercase fw-bold mb-0">Edit Color: {{ $color->name }}</h4>
                </div>
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.colors.index') }}">Colors</a></li>
                        <li class="breadcrumb-item active">Edit Color</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header border-bottom border-dashed">
                            <h4 class="card-title">Color Information</h4>
                            <p class="text-muted mb-0">Update color information with hex code and image</p>
                        </div>
                        <div class="card-body">
                            <form id="colorForm" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <ul class="nav nav-tabs" id="colorTabs" role="tablist">
                                    <li class="nav-item"><button class="nav-link active" id="basic-tab" data-bs-toggle="tab"
                                            data-bs-target="#basic" type="button"><i class="ti ti-info-circle"></i> Basic
                                            Info</button></li>
                                    <li class="nav-item"><button class="nav-link" id="seo-tab" data-bs-toggle="tab"
                                            data-bs-target="#seo" type="button"><i class="ti ti-meta-tag"></i> SEO &
                                            Social</button></li>
                                </ul>

                                <div class="tab-content mt-3">
                                    <div class="tab-pane fade show active" id="basic" role="tabpanel">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="name" class="form-label">Color Name <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="name" name="name"
                                                        value="{{ old('name', $color->name) }}">
                                                    <div class="invalid-feedback" id="name-error"></div>
                                                    <small class="text-muted">URL slug: <span id="slug-preview"
                                                            class="text-primary">{{ $color->slug }}</span></small>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="code" class="form-label">Hex Code <span
                                                            class="text-danger">*</span></label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" id="code"
                                                            name="code" value="{{ old('code', $color->code) }}"
                                                            maxlength="7">
                                                        <span class="input-group-text" id="colorPreview"
                                                            style="width: 40px; background-color: {{ $color->code }};"></span>
                                                    </div>
                                                    <div class="invalid-feedback" id="code-error"></div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="rgb" class="form-label">RGB Value</label>
                                                    <input type="text" class="form-control" id="rgb" name="rgb"
                                                        value="{{ old('rgb', $color->rgb) }}">
                                                    <div class="invalid-feedback" id="rgb-error"></div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="hsl" class="form-label">HSL Value</label>
                                                    <input type="text" class="form-control" id="hsl" name="hsl"
                                                        value="{{ old('hsl', $color->hsl) }}">
                                                    <div class="invalid-feedback" id="hsl-error"></div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="order" class="form-label">Display Order</label>
                                                    <input type="number" class="form-control" id="order"
                                                        name="order" value="{{ old('order', $color->order) }}">
                                                    <div class="invalid-feedback" id="order-error"></div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Display Settings</label>
                                                    <div class="d-flex flex-wrap gap-3">
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input"
                                                                id="status" name="status" value="1"
                                                                {{ $color->status ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="status"><i
                                                                    class="ti ti-circle-check"></i> Active</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input"
                                                                id="is_featured" name="is_featured" value="1"
                                                                {{ $color->is_featured ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="is_featured"><i
                                                                    class="ti ti-star"></i> Featured</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input"
                                                                id="is_popular" name="is_popular" value="1"
                                                                {{ $color->is_popular ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="is_popular"><i
                                                                    class="ti ti-fire"></i> Popular</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="description" class="form-label">Description</label>
                                                    <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $color->description) }}</textarea>
                                                    <div class="invalid-feedback" id="description-error"></div>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="card border">
                                                    <div class="card-header bg-light">
                                                        <h6 class="mb-0">Color Swatch Image</h6>
                                                    </div>
                                                    <div class="card-body text-center">
                                                        <div id="imagePreview" class="mb-3">
                                                            @if ($color->image)
                                                                <img src="{{ asset('storage/colors/' . $color->image) }}"
                                                                    class="img-fluid rounded" style="max-height: 150px;">
                                                                <div class="small text-muted mt-1">Current image</div>
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
                                                                value="{{ old('image_alt', $color->image_alt) }}">
                                                        </div>
                                                        @if ($color->image)
                                                            <div class="mt-2">
                                                                <div class="form-check">
                                                                    <input type="checkbox" class="form-check-input"
                                                                        id="remove_image" name="remove_image"
                                                                        value="1">
                                                                    <label class="form-check-label text-danger"
                                                                        for="remove_image">Remove current image</label>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="seo" role="tabpanel">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="focus_keyword" class="form-label">Focus Keyword</label>
                                                    <input type="text" class="form-control" id="focus_keyword"
                                                        name="focus_keyword"
                                                        value="{{ old('focus_keyword', $color->focus_keyword) }}">
                                                    <div class="invalid-feedback" id="focus_keyword-error"></div>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="meta_title" class="form-label">Meta Title</label>
                                                    <input type="text" class="form-control" id="meta_title"
                                                        name="meta_title"
                                                        value="{{ old('meta_title', $color->meta_title) }}">
                                                    <div class="invalid-feedback" id="meta_title-error"></div>
                                                    <small class="text-muted"
                                                        id="metaTitleCount">{{ strlen($color->meta_title ?? '') }}/70
                                                        characters</small>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="meta_description" class="form-label">Meta
                                                        Description</label>
                                                    <textarea class="form-control" id="meta_description" name="meta_description" rows="2">{{ old('meta_description', $color->meta_description) }}</textarea>
                                                    <div class="invalid-feedback" id="meta_description-error"></div>
                                                    <small class="text-muted"
                                                        id="metaDescCount">{{ strlen($color->meta_description ?? '') }}/160
                                                        characters</small>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="meta_keywords" class="form-label">Meta Keywords</label>
                                                    <input type="text" class="form-control" id="meta_keywords"
                                                        name="meta_keywords"
                                                        value="{{ old('meta_keywords', $color->meta_keywords) }}">
                                                    <div class="invalid-feedback" id="meta_keywords-error"></div>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="canonical_url" class="form-label">Canonical URL</label>
                                                    <input type="url" class="form-control" id="canonical_url"
                                                        name="canonical_url"
                                                        value="{{ old('canonical_url', $color->canonical_url) }}">
                                                    <div class="invalid-feedback" id="canonical_url-error"></div>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <hr>
                                                <h6 class="mb-3"><i class="ti ti-share"></i> Social Media (Open Graph)
                                                </h6>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="og_title" class="form-label">OG Title</label>
                                                    <input type="text" class="form-control" id="og_title"
                                                        name="og_title" value="{{ old('og_title', $color->og_title) }}">
                                                    <div class="invalid-feedback" id="og_title-error"></div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="og_image" class="form-label">OG Image</label>
                                                    <input type="file" class="form-control" id="og_image"
                                                        name="og_image" accept="image/*">
                                                    <div class="invalid-feedback" id="og_image-error"></div>
                                                    @if ($color->og_image)
                                                        <div class="mt-2">
                                                            <div class="form-check">
                                                                <input type="checkbox" class="form-check-input"
                                                                    id="remove_og_image" name="remove_og_image"
                                                                    value="1">
                                                                <label class="form-check-label text-danger"
                                                                    for="remove_og_image">Remove current OG image</label>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div id="ogImagePreview" class="mt-2">
                                                    @if ($color->og_image)
                                                        <img src="{{ asset('storage/' . $color->og_image) }}"
                                                            class="img-fluid rounded border" style="max-height: 100px;">
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="og_description" class="form-label">OG Description</label>
                                                    <textarea class="form-control" id="og_description" name="og_description" rows="2">{{ old('og_description', $color->og_description) }}</textarea>
                                                    <div class="invalid-feedback" id="og_description-error"></div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card border mt-3">
                                            <div class="card-header bg-light">
                                                <h6 class="mb-0"><i class="ti ti-eye"></i> SEO Preview</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="text-primary fw-bold fs-5" id="seo-preview-title">
                                                    {{ $color->meta_title ?? $color->name }}</div>
                                                <div class="text-muted small" id="seo-preview-url">
                                                    {{ url('/color') }}/{{ $color->slug }}</div>
                                                <div class="text-muted small mt-2" id="seo-preview-desc">
                                                    {{ Str::limit($color->meta_description ?? ($color->description ?? 'Color description will appear here...'), 160) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2 mt-4">
                                    <a href="{{ route('admin.colors.index') }}" class="btn btn-danger"><i
                                            class="ti ti-x me-1"></i> Cancel</a>
                                    <button type="submit" class="btn btn-primary" id="submitBtn"><i
                                            class="ti ti-edit me-1"></i> Update Color</button>
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

            $('#name').on('keyup', function() {
                let slug = $(this).val().toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
                if (slug) $('#slug-preview').text(slug);
                updateSEOPreview();
                updateSocialPreview();
                $(this).removeClass('is-invalid');
                $('#name-error').text('');
            });

            $('#code').on('input', function() {
                let code = $(this).val();
                if (code.match(/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/)) {
                    $('#colorPreview').css('background-color', code);
                    $(this).removeClass('is-invalid');
                    $('#code-error').text('');
                } else {
                    $('#colorPreview').css('background-color', '#000000');
                }
            });

            function updateSEOPreview() {
                let title = $('#meta_title').val() || $('#name').val() || 'Color Name';
                let desc = $('#meta_description').val() || $('#description').val() ||
                    'Color description will appear here...';
                let slug = $('#name').val().toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '') ||
                    '{{ $color->slug }}';
                $('#seo-preview-title').text(title.substring(0, 70));
                $('#seo-preview-url').text('{{ url('/') }}/color/' + slug);
                $('#seo-preview-desc').text(desc.substring(0, 160));
            }

            function updateSocialPreview() {
                let title = $('#og_title').val() || $('#meta_title').val() || $('#name').val() || 'Color Name';
                let desc = $('#og_description').val() || $('#meta_description').val() || $('#description').val() ||
                    'Color description...';
                let slug = $('#name').val().toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '') ||
                    '{{ $color->slug }}';
                $('#social-preview-title').text(title.substring(0, 60));
                $('#social-preview-desc').text(desc.substring(0, 200));
                $('#social-preview-url').text('{{ url('/') }}/color/' + slug);
            }

            $('#meta_title').on('keyup', function() {
                let length = $(this).val().length;
                $('#metaTitleCount').text(length + '/70 characters');
                updateSEOPreview();
                updateSocialPreview();
                $(this).removeClass('is-invalid');
                $('#meta_title-error').text('');
            });

            $('#meta_description').on('keyup', function() {
                let length = $(this).val().length;
                $('#metaDescCount').text(length + '/160 characters');
                updateSEOPreview();
                updateSocialPreview();
                $(this).removeClass('is-invalid');
                $('#meta_description-error').text('');
            });

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
                        $('#imagePreview').html(
                            `<img src="${e.target.result}" class="img-fluid rounded" style="max-height: 150px;"><div class="small text-muted mt-1">${(file.size / 1024).toFixed(2)} KB</div>`
                            );
                    };
                    reader.readAsDataURL(file);
                }
            });

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

            $('input, select, textarea').on('input change', function() {
                $(this).removeClass('is-invalid');
                $('#' + $(this).attr('name') + '-error').text('');
            });

            $('#colorForm').on('submit', function(e) {
                e.preventDefault();
                if (formSubmitting) return false;

                let isValid = true;
                let nameValue = $('#name').val().trim();
                let codeValue = $('#code').val().trim();

                if (!nameValue) {
                    $('#name').addClass('is-invalid');
                    $('#name-error').text('Color name is required');
                    isValid = false;
                }

                if (!codeValue) {
                    $('#code').addClass('is-invalid');
                    $('#code-error').text('Hex code is required');
                    isValid = false;
                } else if (!codeValue.match(/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/)) {
                    $('#code').addClass('is-invalid');
                    $('#code-error').text('Please enter a valid hex code');
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
                    url: '{{ route('admin.colors.update', $color->id) }}',
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
                                })
                                .then(() => {
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
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: xhr.responseJSON?.message ||
                                    'Something went wrong.',
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

            updateSEOPreview();
            updateSocialPreview();
        });
    </script>
@endpush
