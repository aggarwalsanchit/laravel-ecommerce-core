@extends('admin.layouts.app')

@section('title', 'Edit Discount')

@section('content')
    <div class="page-content">
        <div class="page-container">
            <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                <div class="flex-grow-1">
                    <h4 class="fs-18 text-uppercase fw-bold mb-0">Edit Discount</h4>
                </div>
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.discounts.index') }}">Discounts</a></li>
                        <li class="breadcrumb-item active">Edit Discount</li>
                    </ol>
                </div>
            </div>

            <form id="discountForm">
                @csrf
                @method('PUT')

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
                                            <input type="text" class="form-control" name="name"
                                                value="{{ $discount->name }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Coupon Code <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="code"
                                                value="{{ $discount->code }}" required>
                                            <small class="text-muted">Will be converted to uppercase automatically</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" name="description" rows="2">{{ $discount->description }}</textarea>
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
                                                <option value="percentage"
                                                    {{ $discount->discount_type == 'percentage' ? 'selected' : '' }}>
                                                    Percentage (%)</option>
                                                <option value="fixed_amount"
                                                    {{ $discount->discount_type == 'fixed_amount' ? 'selected' : '' }}>Fixed
                                                    Amount ($)</option>
                                                <option value="buy_x_get_y"
                                                    {{ $discount->discount_type == 'buy_x_get_y' ? 'selected' : '' }}>Buy X
                                                    Get Y Free</option>
                                                <option value="free_shipping"
                                                    {{ $discount->discount_type == 'free_shipping' ? 'selected' : '' }}>Free
                                                    Shipping</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6" id="discountValueDiv"
                                        style="{{ in_array($discount->discount_type, ['percentage', 'fixed_amount']) ? '' : 'display: none;' }}">
                                        <div class="mb-3">
                                            <label class="form-label"
                                                id="discountValueLabel">{{ $discount->discount_type == 'percentage' ? 'Percentage (%)' : 'Fixed Amount ($)' }}</label>
                                            <input type="number" class="form-control" name="discount_value" step="0.01"
                                                value="{{ $discount->discount_value }}">
                                        </div>
                                    </div>
                                </div>

                                <div id="buyXGetYDiv"
                                    style="{{ $discount->discount_type == 'buy_x_get_y' ? '' : 'display: none;' }}">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Buy Quantity (X)</label>
                                                <input type="number" class="form-control" name="buy_quantity"
                                                    min="1" value="{{ $discount->buy_quantity }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Get Quantity (Y Free)</label>
                                                <input type="number" class="form-control" name="get_quantity"
                                                    min="1" value="{{ $discount->get_quantity }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="freeShippingDiv"
                                    style="{{ $discount->discount_type == 'free_shipping' ? '' : 'display: none;' }}">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="freeShippingOnly"
                                            name="free_shipping_only" value="1"
                                            {{ $discount->free_shipping_only ? 'checked' : '' }}>
                                        <label class="form-check-label" for="freeShippingOnly">Free Shipping Only (No other
                                            discounts)</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Target Selection -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5>Target Selection</h5>
                                <p class="text-muted mb-0">Select what this discount applies to</p>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Target Type</label>
                                    <select class="form-select" name="target_type" id="targetType">
                                        <option value="all_products"
                                            {{ $discount->target_type == 'all_products' ? 'selected' : '' }}>All Products
                                        </option>
                                        <option value="products"
                                            {{ $discount->target_type == 'products' ? 'selected' : '' }}>Specific Products
                                        </option>
                                        <option value="categories"
                                            {{ $discount->target_type == 'categories' ? 'selected' : '' }}>Categories
                                        </option>
                                        <option value="subcategories"
                                            {{ $discount->target_type == 'subcategories' ? 'selected' : '' }}>Subcategories
                                        </option>
                                        <option value="colors" {{ $discount->target_type == 'colors' ? 'selected' : '' }}>
                                            Colors</option>
                                        <option value="sizes" {{ $discount->target_type == 'sizes' ? 'selected' : '' }}>
                                            Sizes</option>
                                        <option value="custom_attributes"
                                            {{ $discount->target_type == 'custom_attributes' ? 'selected' : '' }}>Custom
                                            Attributes (Fabric, Collection, Season, RAM, Processor, etc.)</option>
                                        <option value="user_groups"
                                            {{ $discount->target_type == 'user_groups' ? 'selected' : '' }}>User Groups
                                        </option>
                                        <option value="min_purchase"
                                            {{ $discount->target_type == 'min_purchase' ? 'selected' : '' }}>Minimum
                                            Purchase Amount</option>
                                        <option value="first_purchase"
                                            {{ $discount->target_type == 'first_purchase' ? 'selected' : '' }}>First
                                            Purchase Only</option>
                                        <option value="holiday_special"
                                            {{ $discount->target_type == 'holiday_special' ? 'selected' : '' }}>Holiday
                                            Special</option>
                                        <option value="clearance"
                                            {{ $discount->target_type == 'clearance' ? 'selected' : '' }}>Clearance Items
                                        </option>
                                    </select>
                                </div>

                                <!-- Products Target -->
                                <div id="productsTarget"
                                    style="{{ $discount->target_type == 'products' ? '' : 'display: none;' }}">
                                    <div class="mb-3">
                                        <label class="form-label">Select Products</label>
                                        <select class="form-control" name="target_ids[]" multiple id="productsSelect">
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}"
                                                    {{ is_array($discount->target_ids) && in_array($product->id, $discount->target_ids) ? 'selected' : '' }}>
                                                    {{ $product->name }} ({{ $product->sku }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Categories Target -->
                                <div id="categoriesTarget"
                                    style="{{ $discount->target_type == 'categories' ? '' : 'display: none;' }}">
                                    <div class="mb-3">
                                        <label class="form-label">Select Categories</label>
                                        <select class="form-control" name="target_ids[]" multiple id="categoriesSelect">
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ is_array($discount->target_ids) && in_array($category->id, $discount->target_ids) ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Subcategories Target -->
                                <div id="subcategoriesTarget"
                                    style="{{ $discount->target_type == 'subcategories' ? '' : 'display: none;' }}">
                                    <div class="mb-3">
                                        <label class="form-label">Select Subcategories</label>
                                        <select class="form-control" name="target_ids[]" multiple
                                            id="subcategoriesSelect">
                                            @foreach ($subcategories as $subcategory)
                                                <option value="{{ $subcategory->id }}"
                                                    {{ is_array($discount->target_ids) && in_array($subcategory->id, $discount->target_ids) ? 'selected' : '' }}>
                                                    {{ $subcategory->name }} ({{ $subcategory->parent->name ?? 'Main' }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Colors Target -->
                                <div id="colorsTarget"
                                    style="{{ $discount->target_type == 'colors' ? '' : 'display: none;' }}">
                                    <div class="mb-3">
                                        <label class="form-label">Select Colors</label>
                                        <select class="form-control" name="target_ids[]" multiple id="colorsSelect">
                                            @foreach ($colors as $color)
                                                <option value="{{ $color->id }}"
                                                    {{ is_array($discount->target_ids) && in_array($color->id, $discount->target_ids) ? 'selected' : '' }}>
                                                    {{ $color->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Sizes Target -->
                                <div id="sizesTarget"
                                    style="{{ $discount->target_type == 'sizes' ? '' : 'display: none;' }}">
                                    <div class="mb-3">
                                        <label class="form-label">Select Sizes</label>
                                        <select class="form-control" name="target_ids[]" multiple id="sizesSelect">
                                            @foreach ($sizes as $size)
                                                <option value="{{ $size->id }}"
                                                    {{ is_array($discount->target_ids) && in_array($size->id, $discount->target_ids) ? 'selected' : '' }}>
                                                    {{ $size->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Custom Attributes Target - Dynamic -->
                                <div id="customAttributesTarget"
                                    style="{{ $discount->target_type == 'custom_attributes' ? '' : 'display: none;' }}">
                                    @php
                                        $selectedAttributeId = is_array($discount->target_ids)
                                            ? $discount->target_ids['attribute_id'] ?? null
                                            : null;
                                        $selectedAttributeValueIds = is_array($discount->target_ids)
                                            ? $discount->target_ids['attribute_value_ids'] ?? []
                                            : [];
                                    @endphp
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Select Attribute Type</label>
                                                <select class="form-select" name="attribute_id" id="attributeSelect">
                                                    <option value="">Choose an attribute</option>
                                                    @foreach ($customAttributes as $attribute)
                                                        <option value="{{ $attribute->id }}"
                                                            {{ $selectedAttributeId == $attribute->id ? 'selected' : '' }}>
                                                            {{ $attribute->name }} ({{ $attribute->type }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Select Attribute Values</label>
                                                <select class="form-control" name="attribute_value_ids[]" multiple
                                                    id="attributeValuesSelect">
                                                    @if ($selectedAttributeId)
                                                        @php
                                                            $selectedAttribute = $customAttributes
                                                                ->where('id', $selectedAttributeId)
                                                                ->first();
                                                        @endphp
                                                        @if ($selectedAttribute && $selectedAttribute->values)
                                                            @foreach ($selectedAttribute->values as $value)
                                                                <option value="{{ $value->id }}"
                                                                    {{ in_array($value->id, $selectedAttributeValueIds) ? 'selected' : '' }}>
                                                                    {{ $value->value }}
                                                                </option>
                                                            @endforeach
                                                        @endif
                                                    @else
                                                        <option>First select an attribute</option>
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <small class="text-muted">
                                        <i class="ti ti-info-circle"></i>
                                        Examples: For "Fabric" attribute, select Cotton, Silk, Wool. For "RAM", select 8GB,
                                        16GB, 32GB.
                                        For "Collection", select Summer, Winter, Spring. Products with any of these values
                                        will get the discount.
                                    </small>
                                </div>

                                <!-- User Groups Target -->
                                <div id="userGroupsTarget"
                                    style="{{ $discount->target_type == 'user_groups' ? '' : 'display: none;' }}">
                                    <div class="mb-3">
                                        <label class="form-label">Select User Groups</label>
                                        <select class="form-control" name="user_groups[]" multiple id="userGroupsSelect">
                                            <option value="new"
                                                {{ is_array($discount->user_groups) && in_array('new', $discount->user_groups) ? 'selected' : '' }}>
                                                New Customers</option>
                                            <option value="regular"
                                                {{ is_array($discount->user_groups) && in_array('regular', $discount->user_groups) ? 'selected' : '' }}>
                                                Regular Customers</option>
                                            <option value="vip"
                                                {{ is_array($discount->user_groups) && in_array('vip', $discount->user_groups) ? 'selected' : '' }}>
                                                VIP Customers</option>
                                            <option value="premium"
                                                {{ is_array($discount->user_groups) && in_array('premium', $discount->user_groups) ? 'selected' : '' }}>
                                                Premium Members</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Holiday Special -->
                                <div id="holidaySpecialTarget"
                                    style="{{ $discount->target_type == 'holiday_special' ? '' : 'display: none;' }}">
                                    <div class="alert alert-info">
                                        <i class="ti ti-info-circle"></i> This discount will be active during the selected
                                        date range only.
                                    </div>
                                </div>

                                <!-- Clearance Target -->
                                <div id="clearanceTarget"
                                    style="{{ $discount->target_type == 'clearance' ? '' : 'display: none;' }}">
                                    <div class="alert alert-warning">
                                        <i class="ti ti-info-circle"></i> This discount will apply to all products marked
                                        as clearance.
                                    </div>
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
                                                step="0.01" value="{{ $discount->min_purchase_amount }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Max Usage Per User</label>
                                            <input type="number" class="form-control" name="max_usage_per_user"
                                                value="{{ $discount->max_usage_per_user }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Total Usage Limit</label>
                                            <input type="number" class="form-control" name="total_usage_limit"
                                                value="{{ $discount->total_usage_limit }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Used Count</label>
                                            <input type="number" class="form-control" name="used_count"
                                                value="{{ $discount->used_count }}" readonly>
                                            <small class="text-muted">Auto-incremented when discount is used</small>
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
                                <h5>Sale Schedule</h5>
                                <p class="text-muted mb-0">Set start and end date for this discount</p>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Start Date & Time</label>
                                    <input type="datetime-local" class="form-control" name="start_date"
                                        value="{{ $discount->start_date ? \Carbon\Carbon::parse($discount->start_date)->format('Y-m-d\TH:i') : '' }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">End Date & Time</label>
                                    <input type="datetime-local" class="form-control" name="end_date"
                                        value="{{ $discount->end_date ? \Carbon\Carbon::parse($discount->end_date)->format('Y-m-d\TH:i') : '' }}">
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
                                        value="1" {{ $discount->status ? 'checked' : '' }}>
                                    <label class="form-check-label" for="status">Active</label>
                                </div>
                                <div class="form-check form-switch mb-3">
                                    <input type="checkbox" class="form-check-input" id="isFeatured" name="is_featured"
                                        value="1" {{ $discount->is_featured ? 'checked' : '' }}>
                                    <label class="form-check-label" for="isFeatured">Featured Discount (Shows with special
                                        badge)</label>
                                </div>
                                <div class="form-check form-switch mb-3">
                                    <input type="checkbox" class="form-check-input" id="stackable" name="stackable"
                                        value="1" {{ $discount->stackable ? 'checked' : '' }}>
                                    <label class="form-check-label" for="stackable">Stackable with other discounts</label>
                                </div>
                            </div>
                        </div>

                        <!-- Submit -->
                        <div class="card">
                            <div class="card-footer text-end">
                                <a href="{{ route('admin.discounts.index') }}" class="btn btn-danger">Cancel</a>
                                <button type="submit" class="btn btn-primary" id="submitBtn">Update Discount</button>
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
            // Initialize Choices for multiselects
            if (typeof Choices !== 'undefined') {
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
            }

            let attributeValuesChoices = null;

            // Show/hide discount type fields
            $('#discountType').on('change', function() {
                let type = $(this).val();
                $('#discountValueDiv, #buyXGetYDiv, #freeShippingDiv').hide();

                if (type === 'percentage' || type === 'fixed_amount') {
                    $('#discountValueDiv').show();
                    $('#discountValueLabel').text(type === 'percentage' ? 'Percentage (%)' :
                        'Fixed Amount ($)');
                } else if (type === 'buy_x_get_y') {
                    $('#buyXGetYDiv').show();
                } else if (type === 'free_shipping') {
                    $('#freeShippingDiv').show();
                }
            });

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
                else if (target === 'user_groups') $('#userGroupsTarget').show();
                else if (target === 'holiday_special') $('#holidaySpecialTarget').show();
                else if (target === 'clearance') $('#clearanceTarget').show();
            });

            // Load attribute values dynamically when attribute is selected
            $('#attributeSelect').on('change', function() {
                let attributeId = $(this).val();
                let $valuesSelect = $('#attributeValuesSelect');

                if (!attributeId) {
                    $valuesSelect.prop('disabled', true).html('<option>First select an attribute</option>');
                    if (attributeValuesChoices) attributeValuesChoices.destroy();
                    return;
                }

                // Fixed URL - using the correct route with parameter
                $.ajax({
                    url: '{{ route('admin.discounts.attribute-values', ':attributeId') }}'.replace(
                        ':attributeId', attributeId),
                    type: 'GET',
                    success: function(values) {
                        $valuesSelect.prop('disabled', false).empty();

                        if (values.length === 0) {
                            $valuesSelect.html(
                                '<option>No values found for this attribute</option>');
                        } else {
                            $.each(values, function(index, value) {
                                $valuesSelect.append('<option value="' + value.id +
                                    '">' + value.value + '</option>');
                            });
                        }

                        // Reinitialize Choices
                        if (attributeValuesChoices) attributeValuesChoices.destroy();
                        attributeValuesChoices = new Choices($valuesSelect[0], {
                            removeItemButton: true,
                            searchEnabled: true
                        });

                        // Set selected values for edit
                        let selectedValues = @json($selectedAttributeValueIds ?? []);
                        if (selectedValues && selectedValues.length > 0) {
                            attributeValuesChoices.setValue(selectedValues);
                        }
                    },
                    error: function(xhr) {
                        console.error('Error loading attribute values:', xhr);
                        $valuesSelect.prop('disabled', false).html(
                            '<option>Error loading values</option>');
                    }
                });
            });

            // Form submission
            $('#discountForm').on('submit', function(e) {
                e.preventDefault();
                let btn = $('#submitBtn');
                btn.html('<span class="spinner-border spinner-border-sm me-1"></span> Updating...').prop(
                    'disabled', true);

                let formData = new FormData(this);
                formData.append('_method', 'PUT');

                $.ajax({
                    url: '{{ route('admin.discounts.update', $discount->id) }}',
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
                        btn.html('Update Discount').prop('disabled', false);
                    }
                });
            });
        });
    </script>
@endpush
