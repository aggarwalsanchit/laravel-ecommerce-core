{{-- resources/views/admin/pages/discounts/create.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Create Discount')

@section('content')
    <div class="page-content">
        <div class="page-container">
            <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                <div class="flex-grow-1">
                    <h4 class="fs-18 text-uppercase fw-bold mb-0">Create New Discount</h4>
                </div>
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.discounts.index') }}">Discounts</a></li>
                        <li class="breadcrumb-item active">Create Discount</li>
                    </ol>
                </div>
            </div>

            <form id="discountForm">
                @csrf

                <div class="row">
                    <div class="col-lg-8">
                        <!-- Basic Information -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5>Basic Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Discount Name <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="name" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Coupon Code <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="code" required
                                                placeholder="SUMMER2024">
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" name="description" rows="2"></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Discount Type -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5>Discount Type</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Discount Type</label>
                                            <select class="form-select" name="discount_type" id="discountType">
                                                <option value="percentage">Percentage (%)</option>
                                                <option value="fixed_amount">Fixed Amount ($)</option>
                                                <option value="buy_x_get_y">Buy X Get Y Free</option>
                                                <option value="free_shipping">Free Shipping</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6" id="discountValueDiv">
                                        <div class="mb-3">
                                            <label class="form-label" id="discountValueLabel">Discount Value</label>
                                            <input type="number" class="form-control" name="discount_value" step="0.01">
                                        </div>
                                    </div>
                                </div>

                                <div id="buyXGetYDiv" style="display: none;">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Buy Quantity (X)</label>
                                                <input type="number" class="form-control" name="buy_quantity"
                                                    min="1">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Get Quantity (Y Free)</label>
                                                <input type="number" class="form-control" name="get_quantity"
                                                    min="1">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Target Selection - Using Custom Attributes -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5>Target Selection</h5>
                                <p class="text-muted mb-0">Select what this discount applies to</p>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Target Type</label>
                                    <select class="form-select" name="target_type" id="targetType">
                                        <option value="all_products">All Products</option>
                                        <option value="products">Specific Products</option>
                                        <option value="categories">Categories</option>
                                        <option value="subcategories">Subcategories</option>
                                        <option value="colors">Colors</option>
                                        <option value="sizes">Sizes</option>
                                        <option value="custom_attributes">Custom Attributes (Collection, Season, Fabric,
                                            RAM, Processor, etc.)</option>
                                    </select>
                                </div>

                                <!-- Products Target -->
                                <div id="productsTarget" style="display: none;">
                                    <div class="mb-3">
                                        <label class="form-label">Select Products</label>
                                        <select class="form-control" name="target_ids[]" multiple id="productsSelect">
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}">{{ $product->name }}
                                                    ({{ $product->sku }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Categories Target -->
                                <div id="categoriesTarget" style="display: none;">
                                    <div class="mb-3">
                                        <label class="form-label">Select Categories</label>
                                        <select class="form-control" name="target_ids[]" multiple id="categoriesSelect">
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Subcategories Target -->
                                <div id="subcategoriesTarget" style="display: none;">
                                    <div class="mb-3">
                                        <label class="form-label">Select Subcategories</label>
                                        <select class="form-control" name="target_ids[]" multiple
                                            id="subcategoriesSelect">
                                            @foreach ($subcategories as $subcategory)
                                                <option value="{{ $subcategory->id }}">{{ $subcategory->name }}
                                                    ({{ $subcategory->parent->name ?? 'Main' }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Colors Target -->
                                <div id="colorsTarget" style="display: none;">
                                    <div class="mb-3">
                                        <label class="form-label">Select Colors</label>
                                        <select class="form-control" name="target_ids[]" multiple id="colorsSelect">
                                            @foreach ($colors as $color)
                                                <option value="{{ $color->id }}">{{ $color->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Sizes Target -->
                                <div id="sizesTarget" style="display: none;">
                                    <div class="mb-3">
                                        <label class="form-label">Select Sizes</label>
                                        <select class="form-control" name="target_ids[]" multiple id="sizesSelect">
                                            @foreach ($sizes as $size)
                                                <option value="{{ $size->id }}">{{ $size->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Custom Attributes Target - Dynamic -->
                                <div id="customAttributesTarget" style="display: none;">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Select Attribute Type</label>
                                                <select class="form-select" id="attributeTypeSelect">
                                                    <option value="">Choose Attribute</option>
                                                    @foreach ($customAttributes as $attribute)
                                                        <option value="{{ $attribute->id }}"
                                                            data-attribute-name="{{ $attribute->name }}">
                                                            {{ $attribute->name }} ({{ $attribute->type }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Select Values</label>
                                                <select class="form-control" name="target_ids[]" multiple
                                                    id="attributeValuesSelect" disabled>
                                                    <option>First select an attribute</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <small class="text-muted">
                                        <i class="ti ti-info-circle"></i>
                                        Examples: For "Fabric" attribute, select Cotton, Silk, Wool. For "RAM", select 8GB,
                                        16GB. For "Collection", select Summer, Winter.
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Conditions -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5>Conditions & Limits</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Minimum Purchase Amount</label>
                                            <input type="number" class="form-control" name="min_purchase_amount"
                                                step="0.01" placeholder="Optional">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Max Usage Per User</label>
                                            <input type="number" class="form-control" name="max_usage_per_user"
                                                placeholder="Optional">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Total Usage Limit</label>
                                            <input type="number" class="form-control" name="total_usage_limit"
                                                placeholder="Optional">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">User Groups</label>
                                            <select class="form-control" name="user_groups[]" multiple
                                                id="userGroupsSelect">
                                                <option value="new">New Customers</option>
                                                <option value="regular">Regular Customers</option>
                                                <option value="vip">VIP Customers</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <!-- Schedule -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5>Schedule (Sale Start & End Date)</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Start Date & Time</label>
                                    <input type="datetime-local" class="form-control" name="start_date">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">End Date & Time</label>
                                    <input type="datetime-local" class="form-control" name="end_date">
                                </div>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5>Status</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-check form-switch mb-3">
                                    <input type="checkbox" class="form-check-input" id="status" name="status"
                                        value="1" checked>
                                    <label class="form-check-label" for="status">Active</label>
                                </div>
                                <div class="form-check form-switch mb-3">
                                    <input type="checkbox" class="form-check-input" id="isFeatured" name="is_featured"
                                        value="1">
                                    <label class="form-check-label" for="isFeatured">Featured Discount</label>
                                </div>
                                <div class="form-check form-switch mb-3">
                                    <input type="checkbox" class="form-check-input" id="stackable" name="stackable"
                                        value="1">
                                    <label class="form-check-label" for="stackable">Stackable with other discounts</label>
                                </div>
                            </div>
                        </div>

                        <!-- Submit -->
                        <div class="card">
                            <div class="card-footer text-end">
                                <a href="{{ route('admin.discounts.index') }}" class="btn btn-danger">Cancel</a>
                                <button type="submit" class="btn btn-primary" id="submitBtn">Create Discount</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" rel="stylesheet">

    <script>
        $(document).ready(function() {
            // Initialize Choices
            new Choices('#productsSelect', {
                removeItemButton: true,
                searchEnabled: true
            });
            new Choices('#categoriesSelect', {
                removeItemButton: true,
                searchEnabled: true
            });
            new Choices('#subcategoriesSelect', {
                removeItemButton: true,
                searchEnabled: true
            });
            new Choices('#colorsSelect', {
                removeItemButton: true,
                searchEnabled: true
            });
            new Choices('#sizesSelect', {
                removeItemButton: true,
                searchEnabled: true
            });
            new Choices('#userGroupsSelect', {
                removeItemButton: true,
                searchEnabled: true
            });

            let attributeValuesChoices = null;

            // Show/hide discount type fields
            $('#discountType').on('change', function() {
                let type = $(this).val();
                $('#discountValueDiv, #buyXGetYDiv').hide();

                if (type === 'percentage' || type === 'fixed_amount') {
                    $('#discountValueDiv').show();
                    $('#discountValueLabel').text(type === 'percentage' ? 'Percentage (%)' :
                        'Fixed Amount ($)');
                } else if (type === 'buy_x_get_y') {
                    $('#buyXGetYDiv').show();
                }
            }).trigger('change');

            // Show/hide target sections
            $('#targetType').on('change', function() {
                let target = $(this).val();
                $('[id$="Target"]').hide();

                if (target === 'products') $('#productsTarget').show();
                else if (target === 'categories') $('#categoriesTarget').show();
                else if (target === 'subcategories') $('#subcategoriesTarget').show();
                else if (target === 'colors') $('#colorsTarget').show();
                else if (target === 'sizes') $('#sizesTarget').show();
                else if (target === 'custom_attributes') $('#customAttributesTarget').show();
            }).trigger('change');

            // Load attribute values dynamically
            let allAttributeValues = @json(
                $customAttributes->mapWithKeys(function ($attr) {
                    return [$attr->id => $attr->values->pluck('value', 'id')];
                }));

            $('#attributeTypeSelect').on('change', function() {
                let attributeId = $(this).val();
                let $valuesSelect = $('#attributeValuesSelect');

                if (!attributeId) {
                    $valuesSelect.prop('disabled', true).html('<option>First select an attribute</option>');
                    if (attributeValuesChoices) attributeValuesChoices.destroy();
                    return;
                }

                let values = allAttributeValues[attributeId] || {};
                $valuesSelect.prop('disabled', false).empty();

                if (Object.keys(values).length === 0) {
                    $valuesSelect.html('<option>No values found for this attribute</option>');
                } else {
                    $.each(values, function(id, value) {
                        $valuesSelect.append('<option value="' + id + '">' + value + '</option>');
                    });
                }

                // Reinitialize Choices
                if (attributeValuesChoices) attributeValuesChoices.destroy();
                attributeValuesChoices = new Choices($valuesSelect[0], {
                    removeItemButton: true,
                    searchEnabled: true
                });
            });

            // Form submission
            $('#discountForm').on('submit', function(e) {
                e.preventDefault();
                let btn = $('#submitBtn');
                btn.html('<span class="spinner-border spinner-border-sm me-1"></span> Creating...').prop(
                    'disabled', true);

                let formData = new FormData(this);

                $.ajax({
                    url: '{{ route('admin.discounts.store') }}',
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
                                .then(() => window.location.href =
                                    '{{ route('admin.discounts.index') }}');
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            let errorMsg = '';
                            $.each(errors, function(field, messages) {
                                errorMsg += messages[0] + '\n';
                            });
                            Swal.fire({
                                icon: 'error',
                                title: 'Validation Error!',
                                text: errorMsg
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: xhr.responseJSON?.message ||
                                    'Something went wrong.'
                            });
                        }
                        btn.html('Create Discount').prop('disabled', false);
                    }
                });
            });
        });
    </script>
@endpush
