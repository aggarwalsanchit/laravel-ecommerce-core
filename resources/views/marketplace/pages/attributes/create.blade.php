{{-- resources/views/admin/attributes/create.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Create Attribute')

@section('content')
    <div class="page-content">
        <div class="page-container">
            <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                <div class="flex-grow-1">
                    <h4 class="fs-18 text-uppercase fw-bold mb-0">Create New Attribute</h4>
                </div>
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.attributes.index') }}">Attributes</a></li>
                        <li class="breadcrumb-item active">Create Attribute</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <form id="attributeForm" enctype="multipart/form-data">
                        @csrf

                        {{-- Basic Information --}}
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5>Basic Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Attribute Name <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="name" required>
                                            <div class="invalid-feedback" id="name-error"></div>
                                            <small class="text-muted">e.g., Processor, RAM, Fabric, Material</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Attribute Code</label>
                                            <input type="text" class="form-control" name="code">
                                            <div class="invalid-feedback" id="code-error"></div>
                                            <small class="text-muted">Unique identifier (e.g., PROCESSOR, RAM)</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Attribute Type <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select" name="type" required>
                                                <option value="text">Text</option>
                                                <option value="textarea">Textarea</option>
                                                <option value="number">Number</option>
                                                <option value="select">Select (Dropdown)</option>
                                                <option value="multiselect">Multi-Select</option>
                                                <option value="color">Color Picker</option>
                                                <option value="size">Size Selector</option>
                                                <option value="checkbox">Checkbox</option>
                                                <option value="radio">Radio Buttons</option>
                                                <option value="date">Date Picker</option>
                                                <option value="boolean">Yes/No</option>
                                                <option value="range">Range (Min/Max)</option>
                                            </select>
                                            <div class="invalid-feedback" id="type-error"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Unit (Optional)</label>
                                            <input type="text" class="form-control" name="unit"
                                                placeholder="e.g., GB, MHz, cm, kg">
                                            <small class="text-muted">Display unit after the value</small>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label">Description</label>
                                            <textarea class="form-control" name="description" rows="2"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Organization --}}
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5>Organization</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Group</label>
                                            <select class="form-select" name="attribute_group_id">
                                                <option value="">-- Select Group --</option>
                                                @foreach ($groups as $group)
                                                    <option value="{{ $group->id }}">{{ $group->name }}</option>
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
                                            <input type="number" class="form-control" name="display_order" value="0">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Icon</label>
                                            <input type="file" class="form-control" name="icon" accept="image/*">
                                            <small class="text-muted">Optional icon (max 512KB)</small>
                                            <div id="iconPreview" class="mt-2"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Settings --}}
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5>Settings</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-check form-switch mb-3">
                                            <input type="checkbox" class="form-check-input" id="is_required"
                                                name="is_required" value="1">
                                            <label class="form-check-label" for="is_required">Required Field</label>
                                        </div>
                                        <div class="form-check form-switch mb-3">
                                            <input type="checkbox" class="form-check-input" id="is_filterable"
                                                name="is_filterable" value="1" checked>
                                            <label class="form-check-label" for="is_filterable">Filterable</label>
                                        </div>
                                        <div class="form-check form-switch mb-3">
                                            <input type="checkbox" class="form-check-input" id="is_variant"
                                                name="is_variant" value="1">
                                            <label class="form-check-label" for="is_variant">Used for Variants</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check form-switch mb-3">
                                            <input type="checkbox" class="form-check-input" id="has_image"
                                                name="has_image" value="1">
                                            <label class="form-check-label" for="has_image">Values Have Images</label>
                                        </div>
                                        <div class="form-check form-switch mb-3">
                                            <input type="checkbox" class="form-check-input" id="discount_applicable"
                                                name="discount_applicable" value="1" checked>
                                            <label class="form-check-label" for="discount_applicable">Discount
                                                Applicable</label>
                                        </div>
                                        <div class="form-check form-switch mb-3">
                                            <input type="checkbox" class="form-check-input" id="track_analytics"
                                                name="track_analytics" value="1" checked>
                                            <label class="form-check-label" for="track_analytics">Track Analytics</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- SEO --}}
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5>SEO Settings</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Meta Title</label>
                                    <input type="text" class="form-control" name="meta_title">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Meta Description</label>
                                    <textarea class="form-control" name="meta_description" rows="2"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Meta Keywords</label>
                                    <input type="text" class="form-control" name="meta_keywords">
                                </div>
                            </div>
                        </div>

                        {{-- Submit --}}
                        <div class="card">
                            <div class="card-footer text-end">
                                <a href="{{ route('admin.attributes.index') }}" class="btn btn-danger">Cancel</a>
                                <button type="submit" class="btn btn-primary">Create Attribute</button>
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
        $('#attributeForm').on('submit', function(e) {
            e.preventDefault();
            let btn = $(this).find('button[type="submit"]');
            let originalText = btn.html();
            btn.html('<span class="spinner-border spinner-border-sm me-1"></span> Creating...').prop('disabled',
                true);

            let formData = new FormData(this);

            $.ajax({
                url: '{{ route('admin.attributes.store') }}',
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
                            })
                            .then(() => window.location.href = '{{ route('admin.attributes.index') }}');
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
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: xhr.responseJSON?.message || 'Something went wrong.'
                        });
                    }
                    btn.html(originalText).prop('disabled', false);
                }
            });
        });

        // Icon preview
        $('[name="icon"]').on('change', function(e) {
            let file = e.target.files[0];
            if (file) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    $('#iconPreview').html('<img src="' + e.target.result + '" style="max-height: 80px;">');
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
@endpush
