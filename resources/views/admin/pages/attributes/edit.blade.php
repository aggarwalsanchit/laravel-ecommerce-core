{{-- resources/views/admin/attributes/edit.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Edit Attribute')

@section('content')
<div class="page-content">
    <div class="page-container">
        <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
            <div class="flex-grow-1">
                <h4 class="fs-18 text-uppercase fw-bold mb-0">Edit Attribute: {{ $attribute->name }}</h4>
            </div>
            <div class="text-end">
                <ol class="breadcrumb m-0 py-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.attributes.index') }}">Attributes</a></li>
                    <li class="breadcrumb-item active">Edit Attribute</li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8 mx-auto">
                <form id="attributeForm" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    {{-- Basic Information --}}
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="card-title">Basic Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Attribute Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="name" value="{{ old('name', $attribute->name) }}" required>
                                        <div class="invalid-feedback" id="name-error"></div>
                                        <small class="text-muted">
                                            <i class="ti ti-link"></i> Current URL slug: <code>{{ $attribute->slug }}</code>
                                        </small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Attribute Code</label>
                                        <input type="text" class="form-control" name="code" value="{{ old('code', $attribute->code) }}">
                                        <div class="invalid-feedback" id="code-error"></div>
                                        <small class="text-muted">Unique identifier (e.g., PROCESSOR, RAM)</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Attribute Type <span class="text-danger">*</span></label>
                                        <select class="form-select" name="type" required>
                                            <option value="text" {{ $attribute->type == 'text' ? 'selected' : '' }}>Text</option>
                                            <option value="textarea" {{ $attribute->type == 'textarea' ? 'selected' : '' }}>Textarea</option>
                                            <option value="number" {{ $attribute->type == 'number' ? 'selected' : '' }}>Number</option>
                                            <option value="select" {{ $attribute->type == 'select' ? 'selected' : '' }}>Select (Dropdown)</option>
                                            <option value="multiselect" {{ $attribute->type == 'multiselect' ? 'selected' : '' }}>Multi-Select</option>
                                            <option value="color" {{ $attribute->type == 'color' ? 'selected' : '' }}>Color Picker</option>
                                            <option value="size" {{ $attribute->type == 'size' ? 'selected' : '' }}>Size Selector</option>
                                            <option value="checkbox" {{ $attribute->type == 'checkbox' ? 'selected' : '' }}>Checkbox</option>
                                            <option value="radio" {{ $attribute->type == 'radio' ? 'selected' : '' }}>Radio Buttons</option>
                                            <option value="date" {{ $attribute->type == 'date' ? 'selected' : '' }}>Date Picker</option>
                                            <option value="boolean" {{ $attribute->type == 'boolean' ? 'selected' : '' }}>Yes/No</option>
                                            <option value="range" {{ $attribute->type == 'range' ? 'selected' : '' }}>Range (Min/Max)</option>
                                        </select>
                                        <div class="invalid-feedback" id="type-error"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Unit (Optional)</label>
                                        <input type="text" class="form-control" name="unit" value="{{ old('unit', $attribute->unit) }}" placeholder="e.g., GB, MHz, cm, kg">
                                        <small class="text-muted">Display unit after the value</small>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">Description</label>
                                        <textarea class="form-control" name="description" rows="2">{{ old('description', $attribute->description) }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Organization --}}
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="card-title">Organization</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Group</label>
                                        <select class="form-select" name="attribute_group_id">
                                            <option value="">-- Select Group --</option>
                                            @foreach($groups as $group)
                                                <option value="{{ $group->id }}" {{ $attribute->attribute_group_id == $group->id ? 'selected' : '' }}>
                                                    {{ $group->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
    <label class="form-label">Link to Product Category (Optional)</label>
    <select class="form-select" name="product_category_id">
        <option value="">-- All Categories (Global Attribute) --</option>
        @foreach($categories as $category)
            <option value="{{ $category->id }}" 
                {{ old('product_category_id', $attribute->product_category_id ?? '') == $category->id ? 'selected' : '' }}>
                {{ str_repeat('— ', $category->depth) }}{{ $category->name }}
            </option>
        @endforeach
    </select>
    <small class="text-muted">
        If selected, this attribute will only appear for products in this category.
        Leave empty to make it available for all products.
    </small>
</div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Display Order</label>
                                        <input type="number" class="form-control" name="display_order" value="{{ old('display_order', $attribute->display_order) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Icon</label>
                                        <input type="file" class="form-control" name="icon" accept="image/*">
                                        <small class="text-muted">Optional icon (max 512KB)</small>
                                        @if($attribute->icon)
                                            <div id="iconPreview" class="mt-2">
                                                <img src="{{ asset('storage/attributes/icons/' . $attribute->icon) }}" style="max-height: 60px;">
                                                <div class="form-check mt-1">
                                                    <input type="checkbox" class="form-check-input" id="remove_icon" name="remove_icon" value="1">
                                                    <label class="form-check-label text-danger" for="remove_icon">Remove current icon</label>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Settings --}}
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="card-title">Settings</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-check form-switch mb-3">
                                        <input type="checkbox" class="form-check-input" id="is_required" name="is_required" value="1" {{ $attribute->is_required ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_required">Required Field</label>
                                    </div>
                                    <div class="form-check form-switch mb-3">
                                        <input type="checkbox" class="form-check-input" id="is_filterable" name="is_filterable" value="1" {{ $attribute->is_filterable ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_filterable">Filterable</label>
                                    </div>
                                    <div class="form-check form-switch mb-3">
                                        <input type="checkbox" class="form-check-input" id="is_variant" name="is_variant" value="1" {{ $attribute->is_variant ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_variant">Used for Variants</label>
                                    </div>
                                    <div class="form-check form-switch mb-3">
                                        <input type="checkbox" class="form-check-input" id="has_image" name="has_image" value="1" {{ $attribute->has_image ? 'checked' : '' }}>
                                        <label class="form-check-label" for="has_image">Values Have Images</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check form-switch mb-3">
                                        <input type="checkbox" class="form-check-input" id="is_visible_on_product_page" name="is_visible_on_product_page" value="1" {{ $attribute->is_visible_on_product_page ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_visible_on_product_page">Show on Product Page</label>
                                    </div>
                                    <div class="form-check form-switch mb-3">
                                        <input type="checkbox" class="form-check-input" id="is_visible_on_shop_page" name="is_visible_on_shop_page" value="1" {{ $attribute->is_visible_on_shop_page ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_visible_on_shop_page">Show on Shop Page</label>
                                    </div>
                                    <div class="form-check form-switch mb-3">
                                        <input type="checkbox" class="form-check-input" id="discount_applicable" name="discount_applicable" value="1" {{ $attribute->discount_applicable ? 'checked' : '' }}>
                                        <label class="form-check-label" for="discount_applicable">Discount Applicable</label>
                                    </div>
                                    <div class="form-check form-switch mb-3">
                                        <input type="checkbox" class="form-check-input" id="track_analytics" name="track_analytics" value="1" {{ $attribute->track_analytics ? 'checked' : '' }}>
                                        <label class="form-check-label" for="track_analytics">Track Analytics</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- SEO Settings --}}
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="card-title">SEO Settings</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">Meta Title</label>
                                        <input type="text" class="form-control" name="meta_title" value="{{ old('meta_title', $attribute->meta_title) }}">
                                        <small class="text-muted" id="metaTitleCount">0/70 characters</small>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">Meta Description</label>
                                        <textarea class="form-control" name="meta_description" rows="2">{{ old('meta_description', $attribute->meta_description) }}</textarea>
                                        <small class="text-muted" id="metaDescCount">0/160 characters</small>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">Meta Keywords</label>
                                        <input type="text" class="form-control" name="meta_keywords" value="{{ old('meta_keywords', $attribute->meta_keywords) }}">
                                        <small class="text-muted">Comma separated keywords</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="alert alert-info mt-3">
                                <i class="ti ti-eye me-1"></i>
                                <strong>SEO Preview:</strong>
                                <div class="mt-2">
                                    <div class="text-primary fw-bold" id="seo-preview-title">{{ $attribute->meta_title ?: $attribute->name }}</div>
                                    <div class="text-muted small" id="seo-preview-url">{{ url('/attribute') }}/{{ $attribute->slug }}</div>
                                    <div class="text-muted small" id="seo-preview-desc">{{ Str::limit($attribute->meta_description ?: $attribute->description ?: 'Attribute description will appear here...', 160) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="card-title">Status</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input" id="status" name="status" value="1" {{ $attribute->status ? 'checked' : '' }}>
                                        <label class="form-check-label" for="status">Active</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured" value="1" {{ $attribute->is_featured ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_featured">Featured Attribute</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Submit Buttons --}}
                    <div class="card">
                        <div class="card-footer text-end">
                            <a href="{{ route('admin.attributes.index') }}" class="btn btn-danger">
                                <i class="ti ti-x me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="ti ti-edit me-1"></i> Update Attribute
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Character counters
    $('#meta_title').on('keyup', function() {
        let length = $(this).val().length;
        $('#metaTitleCount').text(length + '/70 characters');
        updateSEOPreview();
    });
    
    $('#meta_description').on('keyup', function() {
        let length = $(this).val().length;
        $('#metaDescCount').text(length + '/160 characters');
        updateSEOPreview();
    });
    
    $('#name').on('keyup', updateSEOPreview);
    $('#description').on('keyup', updateSEOPreview);
    
    function updateSEOPreview() {
        let title = $('#meta_title').val() || $('#name').val() || 'Attribute Name';
        let desc = $('#meta_description').val() || $('#description').val() || 'Attribute description will appear here...';
        let slug = $('#name').val().toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '') || '{{ $attribute->slug }}';
        
        $('#seo-preview-title').text(title.substring(0, 70));
        $('#seo-preview-url').text('{{ url('/attribute') }}/' + slug);
        $('#seo-preview-desc').text(desc.substring(0, 160));
    }
    
    // Form submission
    $('#attributeForm').on('submit', function(e) {
        e.preventDefault();
        let btn = $('#submitBtn');
        let originalText = btn.html();
        btn.html('<span class="spinner-border spinner-border-sm me-1"></span> Updating...').prop('disabled', true);
        
        let formData = new FormData(this);
        
        $.ajax({
            url: '{{ route("admin.attributes.update", $attribute->id) }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    Swal.fire({ icon: 'success', title: 'Updated!', text: response.message, timer: 1500, showConfirmButton: false })
                        .then(() => window.location.href = '{{ route("admin.attributes.index") }}');
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    $.each(errors, function(field, messages) {
                        $('[name="' + field + '"]').addClass('is-invalid');
                        $('#' + field + '-error').text(messages[0]);
                    });
                } else {
                    Swal.fire({ icon: 'error', title: 'Error!', text: xhr.responseJSON?.message || 'Something went wrong.' });
                }
                btn.html(originalText).prop('disabled', false);
            }
        });
    });
    
    updateSEOPreview();
});
</script>
@endpush