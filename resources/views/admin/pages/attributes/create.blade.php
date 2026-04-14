{{-- resources/views/admin/pages/attributes/create.blade.php --}}
@extends('management.layouts.app')

@section('title', 'Add New Attribute')

@section('content')
    <div class="page-content">
        <div class="page-container">
            <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                <div class="flex-grow-1">
                    <h4 class="fs-18 text-uppercase fw-bold mb-0">Add New Attribute</h4>
                </div>
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.attributes.index') }}">Attributes</a></li>
                        <li class="breadcrumb-item active">Add Attribute</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header border-bottom border-dashed">
                            <h4 class="card-title">Attribute Information</h4>
                            <p class="text-muted mb-0">Create a new custom attribute for products</p>
                        </div>
                        <div class="card-body">
                            <form id="attributeForm" enctype="multipart/form-data">
                                @csrf

                                {{-- Tabs --}}
                                <ul class="nav nav-tabs" id="attributeTabs" role="tablist">
                                    <li class="nav-item">
                                        <button class="nav-link active" id="basic-tab" data-bs-toggle="tab" data-bs-target="#basic" type="button">
                                            <i class="ti ti-info-circle"></i> Basic Info
                                        </button>
                                    </li>
                                    <li class="nav-item">
                                        <button class="nav-link" id="validation-tab" data-bs-toggle="tab" data-bs-target="#validation" type="button">
                                            <i class="ti ti-checklist"></i> Validation
                                        </button>
                                    </li>
                                    <li class="nav-item">
                                        <button class="nav-link" id="values-tab" data-bs-toggle="tab" data-bs-target="#values" type="button">
                                            <i class="ti ti-list"></i> Predefined Values
                                        </button>
                                    </li>
                                    <li class="nav-item">
                                        <button class="nav-link" id="seo-tab" data-bs-toggle="tab" data-bs-target="#seo" type="button">
                                            <i class="ti ti-meta-tag"></i> SEO & Display
                                        </button>
                                    </li>
                                </ul>

                                <div class="tab-content mt-3">
                                    {{-- Basic Information Tab --}}
                                    <div class="tab-pane fade show active" id="basic" role="tabpanel">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="name" class="form-label">Attribute Name <span class="text-danger">*</span></label>
                                                    <div class="input-group">
                                                        <span class="input-group-text bg-light">
                                                            <i class="ti ti-tag"></i>
                                                        </span>
                                                        <input type="text" class="form-control" id="name" name="name" placeholder="e.g., Brand, Material, Size, Color" autofocus>
                                                    </div>
                                                    <div class="invalid-feedback" id="name-error"></div>
                                                    <small class="text-muted">
                                                        <i class="ti ti-link"></i> URL slug: <span id="slug-preview" class="text-primary"></span>
                                                    </small>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="type" class="form-label">Field Type <span class="text-danger">*</span></label>
                                                    <select class="form-select" id="type" name="type">
                                                        <option value="text">Text Field</option>
                                                        <option value="textarea">Text Area</option>
                                                        <option value="number">Number Field</option>
                                                        <option value="decimal">Decimal Field</option>
                                                        <option value="select">Select Dropdown</option>
                                                        <option value="multiselect">Multi-Select</option>
                                                        <option value="checkbox">Checkbox</option>
                                                        <option value="radio">Radio Button</option>
                                                        <option value="date">Date Picker</option>
                                                        <option value="datetime">Date & Time</option>
                                                        <option value="color">Color Picker</option>
                                                        <option value="image">Image Upload</option>
                                                        <option value="file">File Upload</option>
                                                        <option value="url">URL Field</option>
                                                        <option value="email">Email Field</option>
                                                        <option value="phone">Phone Field</option>
                                                    </select>
                                                    <div class="invalid-feedback" id="type-error"></div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="group_id" class="form-label">Attribute Group</label>
                                                    <select class="form-select select2-single" id="group_id" name="group_id">
                                                        <option value="">-- No Group --</option>
                                                        @foreach($groups as $group)
                                                            <option value="{{ $group->id }}">{{ $group->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="invalid-feedback" id="group_id-error"></div>
                                                    <small class="text-muted">Group this attribute under a category</small>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="order" class="form-label">Display Order</label>
                                                    <input type="number" class="form-control" id="order" name="order" value="0">
                                                    <div class="invalid-feedback" id="order-error"></div>
                                                    <small class="text-muted">Lower numbers appear first</small>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="category_ids" class="form-label">Associated Categories <span class="text-danger">*</span></label>
                                                    <select class="form-control select2-multi" id="category_ids" name="category_ids[]" multiple>
                                                        @foreach($categories as $category)
                                                            <option value="{{ $category->id }}">
                                                                {{ str_repeat('— ', $category->depth ?? 0) }}{{ $category->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <div class="invalid-feedback" id="category_ids-error"></div>
                                                    <small class="text-muted">Select which categories this attribute applies to</small>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="description" class="form-label">Description</label>
                                                    <textarea class="form-control" id="description" name="description" rows="2"
                                                        placeholder="Describe what this attribute is used for"></textarea>
                                                    <div class="invalid-feedback" id="description-error"></div>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label class="form-label">Display Settings</label>
                                                    <div class="d-flex flex-wrap gap-3">
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input" id="status" name="status" value="1" checked>
                                                            <label class="form-check-label" for="status">
                                                                <i class="ti ti-circle-check"></i> Active
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured" value="1">
                                                            <label class="form-check-label" for="is_featured">
                                                                <i class="ti ti-star"></i> Featured
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input" id="is_required" name="is_required" value="1">
                                                            <label class="form-check-label" for="is_required">
                                                                <i class="ti ti-asterisk"></i> Required Field
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input" id="is_filterable" name="is_filterable" value="1">
                                                            <label class="form-check-label" for="is_filterable">
                                                                <i class="ti ti-filter"></i> Filterable in Products
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input" id="is_searchable" name="is_searchable" value="1">
                                                            <label class="form-check-label" for="is_searchable">
                                                                <i class="ti ti-search"></i> Searchable
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input" id="is_comparable" name="is_comparable" value="1">
                                                            <label class="form-check-label" for="is_comparable">
                                                                <i class="ti ti-chart-line"></i> Comparable
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input" id="show_on_product_page" name="show_on_product_page" value="1" checked>
                                                            <label class="form-check-label" for="show_on_product_page">
                                                                <i class="ti ti-eye"></i> Show on Product Page
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input" id="show_on_product_list" name="show_on_product_list" value="1">
                                                            <label class="form-check-label" for="show_on_product_list">
                                                                <i class="ti ti-list"></i> Show on Product List
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="icon" class="form-label">Icon Class</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text bg-light">
                                                            <i class="ti ti-icon"></i>
                                                        </span>
                                                        <input type="text" class="form-control" id="icon" name="icon" placeholder="ti ti-brand">
                                                    </div>
                                                    <div class="invalid-feedback" id="icon-error"></div>
                                                    <small class="text-muted">e.g., ti ti-brand, ti ti-device-mobile</small>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="unit" class="form-label">Unit of Measurement</label>
                                                    <input type="text" class="form-control" id="unit" name="unit" placeholder="e.g., cm, kg, inches, $">
                                                    <div class="invalid-feedback" id="unit-error"></div>
                                                    <small class="text-muted">Optional unit for number/decimal fields</small>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="placeholder" class="form-label">Placeholder Text</label>
                                                    <input type="text" class="form-control" id="placeholder" name="placeholder" placeholder="Enter placeholder text">
                                                    <div class="invalid-feedback" id="placeholder-error"></div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="default_value" class="form-label">Default Value</label>
                                                    <input type="text" class="form-control" id="default_value" name="default_value" placeholder="Default value">
                                                    <div class="invalid-feedback" id="default_value-error"></div>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="help_text" class="form-label">Help Text</label>
                                                    <textarea class="form-control" id="help_text" name="help_text" rows="1"
                                                        placeholder="Helpful instructions for users"></textarea>
                                                    <div class="invalid-feedback" id="help_text-error"></div>
                                                    <small class="text-muted">Displayed below the input field</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Validation Tab --}}
                                    <div class="tab-pane fade" id="validation" role="tabpanel">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="min_value" class="form-label">Minimum Value</label>
                                                    <input type="text" class="form-control" id="min_value" name="min_value" placeholder="e.g., 0, 1, 10">
                                                    <div class="invalid-feedback" id="min_value-error"></div>
                                                    <small class="text-muted">For number/decimal fields</small>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="max_value" class="form-label">Maximum Value</label>
                                                    <input type="text" class="form-control" id="max_value" name="max_value" placeholder="e.g., 100, 1000">
                                                    <div class="invalid-feedback" id="max_value-error"></div>
                                                    <small class="text-muted">For number/decimal fields</small>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="max_length" class="form-label">Maximum Length</label>
                                                    <input type="number" class="form-control" id="max_length" name="max_length" placeholder="e.g., 255">
                                                    <div class="invalid-feedback" id="max_length-error"></div>
                                                    <small class="text-muted">For text fields</small>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="regex_pattern" class="form-label">Regex Pattern</label>
                                                    <input type="text" class="form-control" id="regex_pattern" name="regex_pattern" placeholder="/^[A-Za-z]+$/">
                                                    <div class="invalid-feedback" id="regex_pattern-error"></div>
                                                    <small class="text-muted">Regular expression for validation</small>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="alert alert-info">
                                                    <i class="ti ti-info-circle"></i>
                                                    <strong>Validation Rules Applied:</strong>
                                                    <ul class="mb-0 mt-1" id="validation-summary">
                                                        <li>Required: <span id="summary-required">No</span></li>
                                                        <li>Min/Max: <span id="summary-minmax">Not set</span></li>
                                                        <li>Max Length: <span id="summary-maxlength">Not set</span></li>
                                                        <li>Regex: <span id="summary-regex">Not set</span></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Predefined Values Tab (for select/multiselect/radio) --}}
                                    <div class="tab-pane fade" id="values" role="tabpanel">
                                        <div class="alert alert-info mb-3">
                                            <i class="ti ti-info-circle"></i>
                                            This tab is only for Select, Multi-Select, and Radio button types. Add predefined options here.
                                        </div>
                                        
                                        <div class="values-container">
                                            <div class="table-responsive">
                                                <table class="table table-bordered" id="values-table">
                                                    <thead>
                                                        <tr>
                                                            <th>Value</th>
                                                            <th>Label</th>
                                                            <th>Color Code</th>
                                                            <th>Price Adj.</th>
                                                            <th>Weight Adj.</th>
                                                            <th>Default</th>
                                                            <th style="width: 50px"></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="values-tbody">
                                                        <tr class="value-row">
                                                            <td><input type="text" class="form-control form-control-sm" name="default_values[]" placeholder="e.g., red"></td>
                                                            <td><input type="text" class="form-control form-control-sm" name="default_labels[]" placeholder="Display label"></td>
                                                            <td><input type="color" class="form-control form-control-sm" name="default_colors[]" style="height: 38px;"></td>
                                                            <td><input type="number" step="0.01" class="form-control form-control-sm" name="default_price_adjustments[]" placeholder="0.00"></td>
                                                            <td><input type="number" step="0.01" class="form-control form-control-sm" name="default_weight_adjustments[]" placeholder="0.00"></td>
                                                            <td class="text-center"><input type="radio" name="default_selected" value="0" class="form-check-input default-radio"></td>
                                                            <td class="text-center">
                                                                <button type="button" class="btn btn-sm btn-danger remove-value" disabled>
                                                                    <i class="ti ti-trash"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td colspan="7">
                                                                <button type="button" class="btn btn-sm btn-success" id="add-value">
                                                                    <i class="ti ti-plus"></i> Add Option
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- SEO & Display Tab --}}
                                    <div class="tab-pane fade" id="seo" role="tabpanel">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="meta_title" class="form-label">Meta Title</label>
                                                    <input type="text" class="form-control" id="meta_title" name="meta_title" placeholder="SEO title (50-60 characters)">
                                                    <div class="invalid-feedback" id="meta_title-error"></div>
                                                    <small class="text-muted" id="metaTitleCount">0/70 characters</small>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="meta_description" class="form-label">Meta Description</label>
                                                    <textarea class="form-control" id="meta_description" name="meta_description" rows="2"
                                                        placeholder="SEO description (150-160 characters)"></textarea>
                                                    <div class="invalid-feedback" id="meta_description-error"></div>
                                                    <small class="text-muted" id="metaDescCount">0/160 characters</small>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="input_class" class="form-label">Input CSS Class</label>
                                                    <input type="text" class="form-control" id="input_class" name="input_class" placeholder="form-control custom-class">
                                                    <div class="invalid-feedback" id="input_class-error"></div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="wrapper_class" class="form-label">Wrapper CSS Class</label>
                                                    <input type="text" class="form-control" id="wrapper_class" name="wrapper_class" placeholder="mb-3 col-md-6">
                                                    <div class="invalid-feedback" id="wrapper_class-error"></div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- SEO Preview Card --}}
                                        <div class="card border mt-3">
                                            <div class="card-header bg-light">
                                                <h6 class="mb-0"><i class="ti ti-eye"></i> SEO Preview (Google Search Result)</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="text-primary fw-bold fs-5" id="seo-preview-title">Attribute Name</div>
                                                <div class="text-muted small" id="seo-preview-url">{{ url('/attribute') }}/attribute-slug</div>
                                                <div class="text-muted small mt-2" id="seo-preview-desc">Attribute description will appear here...</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2 mt-4">
                                    <a href="{{ route('admin.attributes.index') }}" class="btn btn-danger">
                                        <i class="ti ti-x me-1"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary" id="submitBtn">
                                        <i class="ti ti-plus me-1"></i> Create Attribute
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
<!-- Select2 CSS and JS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<!-- Choices.js for multi-select -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize Select2
    $('.select2-single').select2({ theme: 'bootstrap-5', placeholder: 'Select option', allowClear: true, width: '100%' });
    
    // Initialize Choices.js for categories
    const categoriesSelect = document.getElementById('category_ids');
    let choicesInstance = null;
    if (categoriesSelect) {
        choicesInstance = new Choices(categoriesSelect, {
            removeItemButton: true,
            removeItems: true,
            duplicateItemsAllowed: false,
            placeholder: true,
            placeholderValue: 'Select categories for this attribute',
            searchEnabled: true,
            searchChoices: true,
            searchResultLimit: 10,
            shouldSort: true,
            itemSelectText: '',
            noChoicesText: 'No categories available',
            noResultsText: 'No categories found',
        });
    }

    let formSubmitting = false;
    let rowCounter = 1;

    // Auto-generate slug preview
    $('#name').on('keyup', function() {
        let slug = $(this).val().toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
        $('#slug-preview').text(slug || 'attribute-slug');
        updateSEOPreview();
        $(this).removeClass('is-invalid');
        $('#name-error').text('');
    });

    // Update validation summary based on field type
    $('#type, #is_required, #min_value, #max_value, #max_length, #regex_pattern').on('change keyup', function() {
        updateValidationSummary();
    });

    function updateValidationSummary() {
        let type = $('#type').val();
        let isRequired = $('#is_required').is(':checked');
        let minVal = $('#min_value').val();
        let maxVal = $('#max_value').val();
        let maxLen = $('#max_length').val();
        let regex = $('#regex_pattern').val();
        
        $('#summary-required').text(isRequired ? 'Yes' : 'No');
        
        if (minVal && maxVal) $('#summary-minmax').text(`${minVal} - ${maxVal}`);
        else if (minVal) $('#summary-minmax').text(`Min: ${minVal}`);
        else if (maxVal) $('#summary-minmax').text(`Max: ${maxVal}`);
        else $('#summary-minmax').text('Not set');
        
        $('#summary-maxlength').text(maxLen ? `${maxLen} characters` : 'Not set');
        $('#summary-regex').text(regex ? regex : 'Not set');
        
        // Show/hide values tab based on type
        if (['select', 'multiselect', 'radio'].includes(type)) {
            $('#values-tab').show();
        } else {
            $('#values-tab').hide();
            if ($('#values-tab').hasClass('active')) {
                $('#basic-tab').tab('show');
            }
        }
    }

    // Add value row
    $('#add-value').on('click', function() {
        let newRow = `
            <tr class="value-row">
                <td><input type="text" class="form-control form-control-sm" name="default_values[]" placeholder="e.g., red"></td>
                <td><input type="text" class="form-control form-control-sm" name="default_labels[]" placeholder="Display label"></td>
                <td><input type="color" class="form-control form-control-sm" name="default_colors[]" style="height: 38px;"></td>
                <td><input type="number" step="0.01" class="form-control form-control-sm" name="default_price_adjustments[]" placeholder="0.00"></td>
                <td><input type="number" step="0.01" class="form-control form-control-sm" name="default_weight_adjustments[]" placeholder="0.00"></td>
                <td class="text-center"><input type="radio" name="default_selected" value="${rowCounter}" class="form-check-input default-radio"></td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-danger remove-value">
                        <i class="ti ti-trash"></i>
                    </button>
                </td>
            </tr>
        `;
        $('#values-tbody').append(newRow);
        rowCounter++;
        
        // Enable remove buttons for all rows except first
        $('.remove-value').prop('disabled', false);
    });

    // Remove value row
    $(document).on('click', '.remove-value', function() {
        if ($('.value-row').length > 1) {
            $(this).closest('tr').remove();
        }
    });

    // Update SEO preview
    function updateSEOPreview() {
        let title = $('#meta_title').val() || $('#name').val() || 'Attribute Name';
        let desc = $('#meta_description').val() || $('#description').val() || 'Attribute description will appear here...';
        let slug = $('#name').val().toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '') || 'attribute-slug';
        $('#seo-preview-title').text(title.substring(0, 70));
        $('#seo-preview-url').text('{{ url('/') }}/attribute/' + slug);
        $('#seo-preview-desc').text(desc.substring(0, 160));
    }

    // Character counters
    $('#meta_title').on('keyup', function() {
        let length = $(this).val().length;
        $('#metaTitleCount').text(length + '/70 characters');
        if (length > 70) $('#metaTitleCount').addClass('text-danger');
        else $('#metaTitleCount').removeClass('text-danger');
        updateSEOPreview();
        $(this).removeClass('is-invalid');
        $('#meta_title-error').text('');
    });

    $('#meta_description').on('keyup', function() {
        let length = $(this).val().length;
        $('#metaDescCount').text(length + '/160 characters');
        if (length > 160) $('#metaDescCount').addClass('text-danger');
        else $('#metaDescCount').removeClass('text-danger');
        updateSEOPreview();
        $(this).removeClass('is-invalid');
        $('#meta_description-error').text('');
    });

    // Remove error on input
    $('input, select, textarea').on('input change', function() {
        $(this).removeClass('is-invalid');
        $('#' + $(this).attr('name') + '-error').text('');
    });

    // Form submission
    $('#attributeForm').on('submit', function(e) {
        e.preventDefault();
        if (formSubmitting) return false;

        let isValid = true;
        let nameValue = $('#name').val().trim();
        let typeValue = $('#type').val();
        
        // Get selected categories from Choices.js
        let selectedCategories = [];
        if (choicesInstance) {
            selectedCategories = choicesInstance.getValue(true);
        }
        let selectElement = document.getElementById('category_ids');
        if (selectElement && (!selectedCategories || selectedCategories.length === 0)) {
            selectedCategories = Array.from(selectElement.selectedOptions).map(option => option.value);
        }

        if (!nameValue) {
            $('#name').addClass('is-invalid');
            $('#name-error').text('Attribute name is required');
            isValid = false;
        }

        if (!typeValue) {
            $('#type').addClass('is-invalid');
            $('#type-error').text('Please select a field type');
            isValid = false;
        }

        if (!selectedCategories || selectedCategories.length === 0) {
            $('#category_ids').addClass('is-invalid');
            $('#category_ids-error').text('Please select at least one category');
            isValid = false;
        } else {
            $('#category_ids').removeClass('is-invalid');
            $('#category_ids-error').text('');
        }

        if (!isValid) {
            $('html, body').animate({ scrollTop: $('.is-invalid:first').offset().top - 100 }, 500);
            return false;
        }

        formSubmitting = true;
        let btn = $('#submitBtn');
        let originalText = btn.html();
        btn.html('<span class="spinner-border spinner-border-sm me-1"></span> Creating...');
        btn.prop('disabled', true);

        let formData = new FormData(this);
        
        // Ensure category_ids are in FormData
        formData.delete('category_ids[]');
        if (selectedCategories && selectedCategories.length > 0) {
            selectedCategories.forEach(categoryId => {
                formData.append('category_ids[]', categoryId);
            });
        }

        $.ajax({
            url: '{{ route("admin.attributes.store") }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            success: function(response) {
                if (response.success) {
                    Swal.fire({ icon: 'success', title: 'Success!', text: response.message, timer: 2000, showConfirmButton: false })
                        .then(() => { window.location.href = '{{ route("admin.attributes.index") }}'; });
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    $.each(errors, function(field, messages) {
                        if (field === 'category_ids') {
                            $('#category_ids').addClass('is-invalid');
                            $('#category_ids-error').text(messages[0]);
                        } else {
                            $('#' + field).addClass('is-invalid');
                            $('#' + field + '-error').text(messages[0]);
                        }
                    });
                    $('html, body').animate({ scrollTop: $('.is-invalid:first').offset().top - 100 }, 500);
                } else {
                    Swal.fire({ icon: 'error', title: 'Error!', text: xhr.responseJSON?.message || 'Something went wrong.', confirmButtonColor: '#d33' });
                }
            },
            complete: function() {
                formSubmitting = false;
                btn.html(originalText);
                btn.prop('disabled', false);
            }
        });
    });

    updateValidationSummary();
    updateSEOPreview();
});
</script>
@endpush

@push('styles')
<style>
    .nav-tabs .nav-link { color: #6c757d; }
    .nav-tabs .nav-link.active { color: #0d6efd; border-bottom: 2px solid #0d6efd; }
    .form-check-input:checked { background-color: #0d6efd; border-color: #0d6efd; }
    .invalid-feedback { display: block; font-size: 0.875em; margin-top: 0.25rem; }
    
    /* Select2 Styling */
    .select2-container--bootstrap-5 .select2-selection { border-radius: 0.375rem; min-height: 38px; }
    
    /* Choices.js Styling */
    .choices { margin-bottom: 0; }
    .choices.is-invalid .choices__inner { border-color: #dc3545; background-color: #fff0f0; }
    .choices__inner { border-radius: 0.375rem; min-height: 38px; background-color: #fff; border: 1px solid #dee2e6; }
    .choices__list--multiple .choices__item { background-color: #0d6efd; border-color: #0d6efd; border-radius: 0.25rem; padding: 0.25rem 0.75rem; margin: 0.125rem; }
    .is-focused .choices__inner { border-color: #86b7fe; box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25); }
    
    /* Values table */
    .values-container { max-height: 400px; overflow-y: auto; }
    #values-table th { background-color: #f8f9fa; position: sticky; top: 0; z-index: 10; }
</style>
@endpush