{{-- resources/views/marketplace/pages/attributes/request.blade.php --}}
@extends('management.layouts.app')

@section('title', 'Request New Attribute')

@section('content')
<div class="page-content">
    <div class="page-container">
        <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
            <div class="flex-grow-1">
                <h4 class="fs-18 text-uppercase fw-bold mb-0">Request New Attribute</h4>
                <p class="text-muted mb-0">Submit a request to add a new product attribute</p>
            </div>
            <div class="text-end">
                <ol class="breadcrumb m-0 py-0">
                    <li class="breadcrumb-item"><a href="{{ route('vendor.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('vendor.attributes.index') }}">Attributes</a></li>
                    <li class="breadcrumb-item active">Request Attribute</li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-10 mx-auto">
                <div class="card">
                    <div class="card-header border-bottom border-dashed">
                        <h5 class="card-title mb-0">
                            <i class="ti ti-input"></i> Attribute Request Form
                        </h5>
                        <p class="text-muted mb-0">Fill in the details below to request a new attribute</p>
                    </div>
                    <div class="card-body">
                        {{-- Info Alert --}}
                        <div class="alert alert-info mb-4">
                            <div class="d-flex">
                                <i class="ti ti-info-circle me-2 fs-5"></i>
                                <div>
                                    <strong>How it works:</strong>
                                    <ul class="mb-0 mt-1">
                                        <li>Your request will be reviewed by our admin team</li>
                                        <li>You will be notified once your request is approved or rejected</li>
                                        <li>Approved attributes will be available for all vendors</li>
                                        <li>You can track your request status in "My Requests" section</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <form id="attributeRequestForm" method="POST" action="{{ route('vendor.attributes.request.store') }}">
                            @csrf

                            {{-- Basic Information --}}
                            <h6 class="mb-3">Basic Information</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Attribute Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                               id="name" name="name" value="{{ old('name') }}" 
                                               placeholder="e.g., Processor, RAM, Material, Brand" autofocus>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @else
                                            <small class="text-muted">Enter the name of the attribute you need</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="type" class="form-label">Field Type <span class="text-danger">*</span></label>
                                        <select class="form-select @error('type') is-invalid @enderror" id="type" name="type">
                                            <option value="">Select Field Type</option>
                                            <option value="text" {{ old('type') == 'text' ? 'selected' : '' }}>Text Field</option>
                                            <option value="textarea" {{ old('type') == 'textarea' ? 'selected' : '' }}>Text Area</option>
                                            <option value="number" {{ old('type') == 'number' ? 'selected' : '' }}>Number Field</option>
                                            <option value="decimal" {{ old('type') == 'decimal' ? 'selected' : '' }}>Decimal Field</option>
                                            <option value="select" {{ old('type') == 'select' ? 'selected' : '' }}>Select Dropdown</option>
                                            <option value="multiselect" {{ old('type') == 'multiselect' ? 'selected' : '' }}>Multi-Select</option>
                                            <option value="checkbox" {{ old('type') == 'checkbox' ? 'selected' : '' }}>Checkbox</option>
                                            <option value="radio" {{ old('type') == 'radio' ? 'selected' : '' }}>Radio Button</option>
                                            <option value="date" {{ old('type') == 'date' ? 'selected' : '' }}>Date Picker</option>
                                            <option value="datetime" {{ old('type') == 'datetime' ? 'selected' : '' }}>Date & Time</option>
                                            <option value="color" {{ old('type') == 'color' ? 'selected' : '' }}>Color Picker</option>
                                            <option value="url" {{ old('type') == 'url' ? 'selected' : '' }}>URL Field</option>
                                            <option value="email" {{ old('type') == 'email' ? 'selected' : '' }}>Email Field</option>
                                            <option value="phone" {{ old('type') == 'phone' ? 'selected' : '' }}>Phone Field</option>
                                        </select>
                                        @error('type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @else
                                            <small class="text-muted">Select the type of input field for this attribute</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="group_id" class="form-label">Attribute Group (Optional)</label>
                                        <select class="form-select @error('group_id') is-invalid @enderror" id="group_id" name="group_id">
                                            <option value="">-- No Group --</option>
                                            @foreach($groups as $group)
                                                <option value="{{ $group->id }}" {{ old('group_id') == $group->id ? 'selected' : '' }}>
                                                    {{ $group->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('group_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @else
                                            <small class="text-muted">Group this attribute under a category (helps with organization)</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="category_ids" class="form-label">Associated Categories <span class="text-danger">*</span></label>
                                        <select class="form-control select2-multi @error('category_ids') is-invalid @enderror" 
                                                id="category_ids" name="category_ids[]" multiple>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ in_array($category->id, old('category_ids', [])) ? 'selected' : '' }}>
                                                    {{ str_repeat('— ', $category->depth ?? 0) }}{{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('category_ids')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @else
                                            <small class="text-muted">Select which categories this attribute applies to</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Settings --}}
                            <h6 class="mb-3 mt-4">Settings</h6>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <div class="form-check form-check-inline">
                                            <input type="checkbox" class="form-check-input" id="is_required" name="is_required" value="1" {{ old('is_required') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_required">
                                                <i class="ti ti-asterisk"></i> Required Field
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input type="checkbox" class="form-check-input" id="is_filterable" name="is_filterable" value="1" {{ old('is_filterable') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_filterable">
                                                <i class="ti ti-filter"></i> Filterable in Products
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Predefined Values (for select/multiselect/radio) --}}
                            <div id="values-section" style="display: none;">
                                <h6 class="mb-3 mt-4">Predefined Values <span class="text-muted">(For Select, Multi-Select, Radio)</span></h6>
                                <div class="alert alert-info mb-3">
                                    <i class="ti ti-info-circle"></i> Add options that customers can choose from.
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="values-table">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Value <span class="text-danger">*</span></th>
                                                <th>Label (Display Name)</th>
                                                <th>Color Code</th>
                                                <th>Price Adj.</th>
                                                <th>Weight Adj.</th>
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
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-sm btn-danger remove-value" disabled>
                                                        <i class="ti ti-trash"></i>
                                                    </button>
                                                </td>
                                             </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="6">
                                                    <button type="button" class="btn btn-sm btn-success" id="add-value">
                                                        <i class="ti ti-plus"></i> Add Option
                                                    </button>
                                                </td>
                                             </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                            {{-- Description and Reason --}}
                            <h6 class="mb-3 mt-4">Additional Information</h6>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea class="form-control" id="description" name="description" rows="2" 
                                                  placeholder="Describe what this attribute is used for">{{ old('description') }}</textarea>
                                        <small class="text-muted">Optional. Provide a brief description of the attribute</small>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="reason" class="form-label">Reason for Request <span class="text-danger">*</span></label>
                                        <textarea class="form-control @error('reason') is-invalid @enderror" 
                                                  id="reason" name="reason" rows="3" 
                                                  placeholder="Why do you need this attribute? How will it help your business?">{{ old('reason') }}</textarea>
                                        @error('reason')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @else
                                            <small class="text-muted">Please provide a detailed reason to help us evaluate your request</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Preview Section --}}
                            <div class="card border mt-4">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="ti ti-eye"></i> Preview</h6>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex align-items-center gap-3">
                                        <i class="ti ti-input text-primary fs-1"></i>
                                        <div>
                                            <div class="fw-semibold fs-5" id="previewName">Attribute Name</div>
                                            <div class="text-muted small" id="previewType">Type: —</div>
                                            <div class="text-muted small" id="previewCategories">Categories: —</div>
                                            <div class="text-muted small" id="previewValues">Values: —</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <a href="{{ route('vendor.attributes.index') }}" class="btn btn-danger">
                                    <i class="ti ti-x me-1"></i> Cancel
                                </a>
                                <button type="submit" class="btn btn-primary" id="submitBtn">
                                    <i class="ti ti-send me-1"></i> Submit Request
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Tips Card --}}
                <div class="card mt-3">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="ti ti-light-bulb"></i> Tips for a Successful Request</h6>
                    </div>
                    <div class="card-body">
                        <ul class="mb-0">
                            <li>Check if the attribute already exists before requesting</li>
                            <li>Choose the correct field type for your needs</li>
                            <li>Select all relevant categories this attribute applies to</li>
                            <li>For dropdown attributes, provide all possible options</li>
                            <li>Explain why existing attributes don't meet your needs</li>
                            <li>Mention potential demand from other vendors</li>
                        </ul>
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
    let formSubmitting = false;
    let rowCounter = 1;

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

    // Show/hide values section based on type
    $('#type').on('change', function() {
        let type = $(this).val();
        if (type === 'select' || type === 'multiselect' || type === 'radio') {
            $('#values-section').show();
        } else {
            $('#values-section').hide();
        }
        updatePreview();
    });

    // Add value row
    $('#add-value').on('click', function() {
        let newRow = `
            <tr class="value-row">
                <td><input type="text" class="form-control form-control-sm" name="default_values[]" placeholder="e.g., red"></td>
                <td><input type="text" class="form-control form-control-sm" name="default_labels[]" placeholder="Display label"></td>
                <td><input type="color" class="form-control form-control-sm" name="default_colors[]" style="height: 38px;"></td>
                <td><input type="number" step="0.01" class="form-control form-control-sm" name="default_price_adjustments[]" placeholder="0.00"></td>
                <td><input type="number" step="0.01" class="form-control form-control-sm" name="default_weight_adjustments[]" placeholder="0.00"></td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-danger remove-value">
                        <i class="ti ti-trash"></i>
                    </button>
                </td>
            </tr>
        `;
        $('#values-tbody').append(newRow);
        rowCounter++;
        $('.remove-value').prop('disabled', false);
        updatePreview();
    });

    // Remove value row
    $(document).on('click', '.remove-value', function() {
        if ($('.value-row').length > 1) {
            $(this).closest('tr').remove();
        }
        updatePreview();
    });

    // Live preview
    $('#name, #type, #category_ids, #is_required, #is_filterable').on('input change', function() {
        updatePreview();
    });

    function updatePreview() {
        let name = $('#name').val().trim();
        let type = $('#type').val();
        let typeText = $('#type option:selected').text();
        
        // Get selected categories
        let selectedCategories = [];
        if (choicesInstance) {
            selectedCategories = choicesInstance.getValue(true);
        }
        
        // Get values count
        let valuesCount = $('.value-row').length;
        let hasValues = valuesCount > 0 && $('#type').val() && (type === 'select' || type === 'multiselect' || type === 'radio');
        
        if (name) {
            $('#previewName').text(name);
        } else {
            $('#previewName').text('Attribute Name');
        }
        
        $('#previewType').html(`Type: ${typeText || '—'}`);
        $('#previewCategories').html(`Categories: ${selectedCategories.length > 0 ? selectedCategories.length + ' selected' : '—'}`);
        
        if (hasValues && valuesCount > 0) {
            $('#previewValues').html(`Values: ${valuesCount} option(s) added`).removeClass('text-muted').addClass('text-success');
        } else {
            $('#previewValues').html('Values: —').removeClass('text-success').addClass('text-muted');
        }
    }

    // Remove error on input
    $('input, select, textarea').on('input change', function() {
        $(this).removeClass('is-invalid');
    });

    // Form submission with button disable
    $('#attributeRequestForm').on('submit', function(e) {
        e.preventDefault();
        
        if (formSubmitting) return false;
        
        let isValid = true;
        let nameValue = $('#name').val().trim();
        let typeValue = $('#type').val();
        let reasonValue = $('#reason').val().trim();
        
        // Get selected categories
        let selectedCategories = [];
        if (choicesInstance) {
            selectedCategories = choicesInstance.getValue(true);
        }

        if (!nameValue) {
            $('#name').addClass('is-invalid');
            isValid = false;
        }

        if (!typeValue) {
            $('#type').addClass('is-invalid');
            isValid = false;
        }

        if (!selectedCategories || selectedCategories.length === 0) {
            $('#category_ids').addClass('is-invalid');
            $('#category_ids-error').text('Please select at least one category');
            isValid = false;
        }

        if (!reasonValue) {
            $('#reason').addClass('is-invalid');
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
        
        btn.html('<span class="spinner-border spinner-border-sm me-1"></span> Submitting...');
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
            url: $(this).attr('action'),
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
                        title: 'Request Submitted!',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = '{{ route("vendor.attributes.requests.index") }}';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: response.message,
                        confirmButtonColor: '#d33'
                    }).then(() => {
                        btn.html(originalText);
                        btn.prop('disabled', false);
                        formSubmitting = false;
                    });
                }
            },
            error: function(xhr) {
                let errorMsg = 'Something went wrong. Please try again.';
                if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                    let errors = xhr.responseJSON.errors;
                    let firstError = Object.values(errors)[0];
                    errorMsg = Array.isArray(firstError) ? firstError[0] : firstError;
                    
                    $.each(errors, function(field, messages) {
                        if (field === 'category_ids') {
                            $('#category_ids').addClass('is-invalid');
                            $('#category_ids-error').text(messages[0]);
                        } else {
                            $('#' + field).addClass('is-invalid');
                        }
                    });
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: errorMsg,
                    confirmButtonColor: '#d33'
                }).then(() => {
                    btn.html(originalText);
                    btn.prop('disabled', false);
                    formSubmitting = false;
                });
            }
        });
    });

    // Initial preview and values section visibility
    if ($('#type').val() === 'select' || $('#type').val() === 'multiselect' || $('#type').val() === 'radio') {
        $('#values-section').show();
    }
    updatePreview();
});
</script>
@endpush

@push('styles')
<style>
    .choices { margin-bottom: 0; }
    .choices.is-invalid .choices__inner { border-color: #dc3545; background-color: #fff0f0; }
    .choices__inner { border-radius: 0.375rem; min-height: 38px; background-color: #fff; border: 1px solid #dee2e6; }
    .choices__list--multiple .choices__item { background-color: #0d6efd; border-color: #0d6efd; border-radius: 0.25rem; padding: 0.25rem 0.75rem; margin: 0.125rem; }
    .is-focused .choices__inner { border-color: #86b7fe; box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25); }
    .form-check-input:checked { background-color: #0d6efd; border-color: #0d6efd; }
    .invalid-feedback { display: block; font-size: 0.875em; margin-top: 0.25rem; }
</style>
@endpush