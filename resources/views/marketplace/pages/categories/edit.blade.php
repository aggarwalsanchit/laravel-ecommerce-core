{{-- resources/views/admin/categories/edit.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Edit Category')

@section('content')
    <div class="page-content">
        <div class="page-container">
            <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                <div class="flex-grow-1">
                    <h4 class="fs-18 text-uppercase fw-bold mb-0">Edit Category: {{ $category->name }}</h4>
                </div>
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">Categories</a></li>
                        <li class="breadcrumb-item active">Edit Category</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header border-bottom border-dashed">
                            <h4 class="card-title">Edit Category Information</h4>
                            <p class="text-muted mb-0">Update category details and settings</p>
                        </div>
                        <div class="card-body">
                            <form id="categoryForm" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Category Name <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="name" name="name"
                                                value="{{ old('name', $category->name) }}" autofocus>
                                            <div class="invalid-feedback" id="name-error"></div>
                                            <small class="text-muted">URL slug: <code>{{ $category->slug }}</code></small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="parent_id" class="form-label">Parent Category</label>
                                            <select class="form-select" id="parent_id" name="parent_id">
                                                <option value="">-- No Parent (Top Level) --</option>
                                                @foreach ($categories as $cat)
                                                    @if ($cat->id != $category->id)
                                                        <option value="{{ $cat->id }}"
                                                            {{ old('parent_id', $category->parent_id) == $cat->id ? 'selected' : '' }}>
                                                            {{ str_repeat('— ', $cat->depth) }}{{ $cat->name }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback" id="parent_id-error"></div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="order" class="form-label">Display Order</label>
                                            <input type="number" class="form-control" id="order" name="order"
                                                value="{{ old('order', $category->order) }}">
                                            <div class="invalid-feedback" id="order-error"></div>
                                            <small class="text-muted">Lower numbers appear first</small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Display Settings</label>
                                            <div class="d-flex flex-wrap gap-3">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="status"
                                                        name="status" value="1"
                                                        {{ old('status', $category->status) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="status">Active</label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="show_in_menu"
                                                        name="show_in_menu" value="1"
                                                        {{ old('show_in_menu', $category->show_in_menu) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="show_in_menu">Show in Menu</label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="is_featured"
                                                        name="is_featured" value="1"
                                                        {{ old('is_featured', $category->is_featured) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="is_featured">Featured</label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="is_popular"
                                                        name="is_popular" value="1"
                                                        {{ old('is_popular', $category->is_popular) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="is_popular">Popular</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="short_description" class="form-label">Short Description</label>
                                            <textarea class="form-control" id="short_description" name="short_description" rows="2">{{ old('short_description', $category->short_description) }}</textarea>
                                            <div class="invalid-feedback" id="short_description-error"></div>
                                            <small class="text-muted" id="shortDescCount">0/500 characters</small>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="description" class="form-label">Full Description</label>
                                            <textarea class="form-control" id="description" name="description" rows="5">{{ old('description', $category->description) }}</textarea>
                                            <div class="invalid-feedback" id="description-error"></div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Tabs --}}
                                <ul class="nav nav-tabs mt-4" id="categoryTabs" role="tablist">
                                    <li class="nav-item">
                                        <button class="nav-link active" id="images-tab" data-bs-toggle="tab"
                                            data-bs-target="#images" type="button">
                                            <i class="ti ti-photo"></i> Images
                                        </button>
                                    </li>
                                    <li class="nav-item">
                                        <button class="nav-link" id="seo-tab" data-bs-toggle="tab"
                                            data-bs-target="#seo" type="button">
                                            <i class="ti ti-chart-line"></i> SEO Settings
                                        </button>
                                    </li>
                                </ul>

                                <div class="tab-content mt-3">
                                    {{-- Images Tab --}}
                                    <div class="tab-pane fade show active" id="images" role="tabpanel">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="card border">
                                                    <div class="card-header bg-light">
                                                        <h6 class="mb-0">Main Image</h6>
                                                        <small class="text-muted">800x800px recommended</small>
                                                    </div>
                                                    <div class="card-body text-center">
                                                        <div id="imagePreview" class="mb-3">
                                                            @php
                                                                $mainImagePath = $category->image
                                                                    ? 'categories/' . $category->image
                                                                    : null;
                                                                $hasMainImage =
                                                                    $mainImagePath &&
                                                                    Storage::disk('public')->exists($mainImagePath);
                                                            @endphp

                                                            @if ($hasMainImage)
                                                                <img src="{{ Storage::disk('public')->url($mainImagePath) }}"
                                                                    class="img-fluid rounded mb-2"
                                                                    style="max-height: 150px; object-fit: cover;">
                                                                <div class="small text-muted mt-1">
                                                                    <i class="ti ti-database"></i> Current image
                                                                </div>
                                                            @else
                                                                <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                                    style="height: 150px;">
                                                                    <i class="ti ti-cloud-upload fs-1 text-muted"></i>
                                                                    <small class="text-muted d-block mt-2">No image</small>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <input type="file" class="form-control" id="image"
                                                            name="image" accept="image/*">
                                                        @if ($hasMainImage)
                                                            <div class="form-check mt-2">
                                                                <input type="checkbox" class="form-check-input"
                                                                    id="remove_image" name="remove_image" value="1">
                                                                <label class="form-check-label text-danger"
                                                                    for="remove_image">
                                                                    <i class="ti ti-trash"></i> Remove image
                                                                </label>
                                                            </div>
                                                        @endif
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
                                                            @php
                                                                $thumbPath = $category->thumbnail_image
                                                                    ? 'categories/thumbnails/' .
                                                                        $category->thumbnail_image
                                                                    : null;
                                                                $hasThumb =
                                                                    $thumbPath &&
                                                                    Storage::disk('public')->exists($thumbPath);
                                                            @endphp

                                                            @if ($hasThumb)
                                                                <img src="{{ Storage::disk('public')->url($thumbPath) }}"
                                                                    class="img-fluid rounded mb-2"
                                                                    style="max-height: 100px; object-fit: cover;">
                                                                <div class="small text-muted mt-1">Current thumbnail</div>
                                                            @else
                                                                <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                                    style="height: 100px;">
                                                                    <i class="ti ti-cloud-upload fs-1 text-muted"></i>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <input type="file" class="form-control" id="thumbnail_image"
                                                            name="thumbnail_image" accept="image/*">
                                                        @if ($hasThumb)
                                                            <div class="form-check mt-2">
                                                                <input type="checkbox" class="form-check-input"
                                                                    id="remove_thumbnail" name="remove_thumbnail"
                                                                    value="1">
                                                                <label class="form-check-label text-danger"
                                                                    for="remove_thumbnail">
                                                                    <i class="ti ti-trash"></i> Remove thumbnail
                                                                </label>
                                                            </div>
                                                        @endif
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
                                                            @php
                                                                $bannerPath = $category->banner_image
                                                                    ? 'categories/banners/' . $category->banner_image
                                                                    : null;
                                                                $hasBanner =
                                                                    $bannerPath &&
                                                                    Storage::disk('public')->exists($bannerPath);
                                                            @endphp

                                                            @if ($hasBanner)
                                                                <img src="{{ Storage::disk('public')->url($bannerPath) }}"
                                                                    class="img-fluid rounded mb-2"
                                                                    style="max-height: 100px; width: 100%; object-fit: cover;">
                                                                <div class="small text-muted mt-1">Current banner</div>
                                                            @else
                                                                <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                                    style="height: 100px;">
                                                                    <i class="ti ti-cloud-upload fs-1 text-muted"></i>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <input type="file" class="form-control" id="banner_image"
                                                            name="banner_image" accept="image/*">
                                                        @if ($hasBanner)
                                                            <div class="form-check mt-2">
                                                                <input type="checkbox" class="form-check-input"
                                                                    id="remove_banner" name="remove_banner"
                                                                    value="1">
                                                                <label class="form-check-label text-danger"
                                                                    for="remove_banner">
                                                                    <i class="ti ti-trash"></i> Remove banner
                                                                </label>
                                                            </div>
                                                        @endif
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
                                                        value="{{ old('focus_keyword', $category->focus_keyword) }}">
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="meta_title" class="form-label">Meta Title</label>
                                                    <input type="text" class="form-control" id="meta_title"
                                                        name="meta_title"
                                                        value="{{ old('meta_title', $category->meta_title) }}">
                                                    <small class="text-muted" id="metaTitleCount">0/70 characters</small>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="meta_description" class="form-label">Meta
                                                        Description</label>
                                                    <textarea class="form-control" id="meta_description" name="meta_description" rows="2">{{ old('meta_description', $category->meta_description) }}</textarea>
                                                    <small class="text-muted" id="metaDescCount">0/160 characters</small>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="meta_keywords" class="form-label">Meta Keywords</label>
                                                    <input type="text" class="form-control" id="meta_keywords"
                                                        name="meta_keywords"
                                                        value="{{ old('meta_keywords', $category->meta_keywords) }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="alert alert-info mt-3">
                                            <strong>SEO Preview:</strong>
                                            <div class="mt-2">
                                                <div class="text-primary fw-bold" id="seo-preview-title">
                                                    {{ $category->name }}</div>
                                                <div class="text-muted small" id="seo-preview-url">
                                                    {{ url('/category') }}/{{ $category->slug }}</div>
                                                <div class="text-muted small" id="seo-preview-desc">
                                                    {{ Str::limit($category->short_description ?? ($category->description ?? 'Category description'), 160) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2 mt-4">
                                    <a href="{{ route('admin.categories.index') }}" class="btn btn-danger">Cancel</a>
                                    <button type="submit" class="btn btn-primary" id="submitBtn">Update
                                        Category</button>
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

            // Character counters
            function updateCharCounts() {
                $('#shortDescCount').text($('#short_description').val().length + '/500 characters');
                $('#metaTitleCount').text($('#meta_title').val().length + '/70 characters');
                $('#metaDescCount').text($('#meta_description').val().length + '/160 characters');
            }
            updateCharCounts();

            $('#short_description, #meta_title, #meta_description').on('keyup', updateCharCounts);

            // SEO Preview
            function updateSEOPreview() {
                $('#seo-preview-title').text($('#meta_title').val() || $('#name').val() || 'Category Name');
                let slug = $('#name').val().toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '') ||
                    '{{ $category->slug }}';
                $('#seo-preview-url').text('{{ url('/') }}/category/' + slug);
                $('#seo-preview-desc').text($('#meta_description').val() || $('#short_description').val() || $(
                    '#description').val() || 'Category description');
            }

            $('#name, #meta_title, #meta_description, #short_description, #description').on('keyup',
                updateSEOPreview);

            // Image preview
            $('#image').on('change', function(e) {
                let file = e.target.files[0];
                if (file) {
                    let reader = new FileReader();
                    reader.onload = function(e) {
                        $('#imagePreview').html('<img src="' + e.target.result +
                            '" class="img-fluid rounded" style="max-height: 150px;">');
                    };
                    reader.readAsDataURL(file);
                    $('#remove_image').prop('checked', false);
                }
            });

            $('#thumbnail_image').on('change', function(e) {
                let file = e.target.files[0];
                if (file) {
                    let reader = new FileReader();
                    reader.onload = function(e) {
                        $('#thumbnailPreview').html('<img src="' + e.target.result +
                            '" class="img-fluid rounded" style="max-height: 100px;">');
                    };
                    reader.readAsDataURL(file);
                    $('#remove_thumbnail').prop('checked', false);
                }
            });

            $('#banner_image').on('change', function(e) {
                let file = e.target.files[0];
                if (file) {
                    let reader = new FileReader();
                    reader.onload = function(e) {
                        $('#bannerPreview').html('<img src="' + e.target.result +
                            '" class="img-fluid rounded" style="max-height: 100px; width: 100%;">');
                    };
                    reader.readAsDataURL(file);
                    $('#remove_banner').prop('checked', false);
                }
            });

            // Form submission
            $('#categoryForm').on('submit', function(e) {
                e.preventDefault();
                if (formSubmitting) return;

                if (!$('#name').val().trim()) {
                    $('#name').addClass('is-invalid');
                    $('#name-error').text('Category name is required');
                    return;
                }

                formSubmitting = true;
                let btn = $('#submitBtn');
                btn.html('<span class="spinner-border spinner-border-sm me-1"></span> Updating...').prop(
                    'disabled', true);

                $.ajax({
                    url: '{{ route('admin.categories.update', $category->id) }}',
                    type: 'POST',
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        Swal.fire({
                                icon: 'success',
                                title: 'Updated!',
                                text: response.message,
                                timer: 1500,
                                showConfirmButton: false
                            })
                            .then(() => window.location.href =
                                '{{ route('admin.categories.index') }}');
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            $.each(xhr.responseJSON.errors, function(field, messages) {
                                $('#' + field).addClass('is-invalid');
                                $('#' + field + '-error').text(messages[0]);
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Something went wrong.'
                            });
                        }
                    },
                    complete: function() {
                        formSubmitting = false;
                        btn.html('Update Category').prop('disabled', false);
                    }
                });
            });

            updateSEOPreview();
        });
    </script>
@endpush
