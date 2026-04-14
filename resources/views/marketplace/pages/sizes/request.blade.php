{{-- resources/views/marketplace/pages/sizes/request.blade.php --}}
@extends('management.layouts.app')

@section('title', 'Request New Size')

@section('content')
<div class="page-content">
    <div class="page-container">
        <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
            <div class="flex-grow-1">
                <h4 class="fs-18 text-uppercase fw-bold mb-0">Request New Size</h4>
                <p class="text-muted mb-0">Submit a request to add a new product size</p>
            </div>
            <div class="text-end">
                <ol class="breadcrumb m-0 py-0">
                    <li class="breadcrumb-item"><a href="{{ route('vendor.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('vendor.sizes.index') }}">Sizes</a></li>
                    <li class="breadcrumb-item active">Request Size</li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-10 mx-auto">
                <div class="card">
                    <div class="card-header border-bottom border-dashed">
                        <h5 class="card-title mb-0">
                            <i class="ti ti-ruler"></i> Size Request Form
                        </h5>
                        <p class="text-muted mb-0">Fill in the details below to request a new size</p>
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
                                        <li>Approved sizes will be available for all vendors</li>
                                        <li>You can track your request status in "My Requests" section</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <form id="sizeRequestForm" method="POST" action="{{ route('vendor.sizes.request.store') }}">
                            @csrf

                            {{-- Basic Information --}}
                            <h6 class="mb-3">Basic Information</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Size Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                               id="name" name="name" value="{{ old('name') }}" 
                                               placeholder="e.g., Small, Medium, Large, US 8">
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @else
                                            <small class="text-muted">Enter the name of the size you need</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="code" class="form-label">Size Code <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                               id="code" name="code" value="{{ old('code') }}" 
                                               placeholder="e.g., S, M, L, XL, US8">
                                        @error('code')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @else
                                            <small class="text-muted">Unique code for this size</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="gender" class="form-label">Gender <span class="text-danger">*</span></label>
                                        <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender">
                                            <option value="">Select Gender</option>
                                            <option value="Men" {{ old('gender') == 'Men' ? 'selected' : '' }}>Men</option>
                                            <option value="Women" {{ old('gender') == 'Women' ? 'selected' : '' }}>Women</option>
                                            <option value="Unisex" {{ old('gender') == 'Unisex' ? 'selected' : '' }}>Unisex</option>
                                            <option value="Kids" {{ old('gender') == 'Kids' ? 'selected' : '' }}>Kids</option>
                                        </select>
                                        @error('gender')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="category_ids" class="form-label">Associated Categories <span class="text-danger">*</span></label>
                                        <select class="form-select select2-multi @error('category_ids') is-invalid @enderror" 
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
                                            <small class="text-muted">Select which categories this size applies to</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Measurements --}}
                            <h6 class="mb-3 mt-4">Measurements (inches)</h6>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="chest" class="form-label">Chest/Bust</label>
                                        <input type="number" step="0.1" class="form-control" id="chest" name="chest" value="{{ old('chest') }}" placeholder="e.g., 38">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="waist" class="form-label">Waist</label>
                                        <input type="number" step="0.1" class="form-control" id="waist" name="waist" value="{{ old('waist') }}" placeholder="e.g., 32">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="hip" class="form-label">Hip</label>
                                        <input type="number" step="0.1" class="form-control" id="hip" name="hip" value="{{ old('hip') }}" placeholder="e.g., 40">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="inseam" class="form-label">Inseam</label>
                                        <input type="number" step="0.1" class="form-control" id="inseam" name="inseam" value="{{ old('inseam') }}" placeholder="e.g., 32">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="shoulder" class="form-label">Shoulder</label>
                                        <input type="number" step="0.1" class="form-control" id="shoulder" name="shoulder" value="{{ old('shoulder') }}" placeholder="e.g., 18">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="sleeve" class="form-label">Sleeve Length</label>
                                        <input type="number" step="0.1" class="form-control" id="sleeve" name="sleeve" value="{{ old('sleeve') }}" placeholder="e.g., 25">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="neck" class="form-label">Neck</label>
                                        <input type="number" step="0.1" class="form-control" id="neck" name="neck" value="{{ old('neck') }}" placeholder="e.g., 15">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="height" class="form-label">Height Recommendation</label>
                                        <input type="number" step="0.1" class="form-control" id="height" name="height" value="{{ old('height') }}" placeholder="e.g., 5.9">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="weight" class="form-label">Weight Recommendation</label>
                                        <input type="number" step="0.1" class="form-control" id="weight" name="weight" value="{{ old('weight') }}" placeholder="e.g., 160">
                                    </div>
                                </div>
                            </div>

                            {{-- International Conversions --}}
                            <h6 class="mb-3 mt-4">International Size Conversions</h6>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="us_size" class="form-label">US Size</label>
                                        <input type="text" class="form-control" id="us_size" name="us_size" value="{{ old('us_size') }}" placeholder="e.g., S, 8">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="uk_size" class="form-label">UK Size</label>
                                        <input type="text" class="form-control" id="uk_size" name="uk_size" value="{{ old('uk_size') }}" placeholder="e.g., S, 10">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="eu_size" class="form-label">EU Size</label>
                                        <input type="text" class="form-control" id="eu_size" name="eu_size" value="{{ old('eu_size') }}" placeholder="e.g., 36, 42">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="int_size" class="form-label">International Size</label>
                                        <input type="text" class="form-control" id="int_size" name="int_size" value="{{ old('int_size') }}" placeholder="e.g., XS, S, M, L">
                                    </div>
                                </div>
                            </div>

                            {{-- Additional Information --}}
                            <h6 class="mb-3 mt-4">Additional Information</h6>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea class="form-control" id="description" name="description" rows="2" 
                                                  placeholder="Describe this size and where it would be used">{{ old('description') }}</textarea>
                                        <small class="text-muted">Optional. Provide a brief description of the size</small>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="reason" class="form-label">Reason for Request <span class="text-danger">*</span></label>
                                        <textarea class="form-control @error('reason') is-invalid @enderror" 
                                                  id="reason" name="reason" rows="3" 
                                                  placeholder="Why do you need this size? How will it help your business?">{{ old('reason') }}</textarea>
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
                                        <i class="ti ti-ruler text-primary fs-1"></i>
                                        <div>
                                            <div class="fw-semibold fs-5" id="previewName">Size Name</div>
                                            <div class="text-muted small" id="previewDetails">Code: — | Gender: —</div>
                                            <div class="text-muted small" id="previewCategories">Categories: —</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <a href="{{ route('vendor.sizes.index') }}" class="btn btn-danger">
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
                            <li>Check if the size already exists before requesting</li>
                            <li>Provide accurate measurements for better accuracy</li>
                            <li>Specify which categories this size applies to</li>
                            <li>Explain why existing sizes don't meet your needs</li>
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

    // Initialize Choices.js for categories
    const categoriesSelect = document.getElementById('category_ids');
    let choicesInstance = null;
    if (categoriesSelect) {
        choicesInstance = new Choices(categoriesSelect, {
            removeItemButton: true,
            removeItems: true,
            duplicateItemsAllowed: false,
            placeholder: true,
            placeholderValue: 'Select categories for this size',
            searchEnabled: true,
            searchChoices: true,
            searchResultLimit: 10,
            shouldSort: true,
            itemSelectText: '',
            noChoicesText: 'No categories available',
            noResultsText: 'No categories found',
        });
    }

    // Live preview
    $('#name, #code, #gender, #category_ids').on('input change', function() {
        updatePreview();
    });

    function updatePreview() {
        let name = $('#name').val().trim();
        let code = $('#code').val().trim();
        let gender = $('#gender').val();
        
        // Get selected categories
        let selectedCategories = [];
        if (choicesInstance) {
            selectedCategories = choicesInstance.getValue(true);
        }
        let selectElement = document.getElementById('category_ids');
        if (selectElement && (!selectedCategories || selectedCategories.length === 0)) {
            selectedCategories = Array.from(selectElement.selectedOptions).map(option => option.value);
        }
        
        if (name) {
            $('#previewName').text(name);
        } else {
            $('#previewName').text('Size Name');
        }
        
        $('#previewDetails').html(`Code: ${code || '—'} | Gender: ${gender || '—'}`);
        $('#previewCategories').html(`Categories: ${selectedCategories.length > 0 ? selectedCategories.length + ' selected' : '—'}`);
    }

    // Remove error on input
    $('input, select, textarea').on('input change', function() {
        $(this).removeClass('is-invalid');
    });

    // Form submission with button disable
    $('#sizeRequestForm').on('submit', function(e) {
        e.preventDefault();
        
        if (formSubmitting) return false;
        
        let isValid = true;
        let nameValue = $('#name').val().trim();
        let codeValue = $('#code').val().trim();
        let genderValue = $('#gender').val();
        let reasonValue = $('#reason').val().trim();
        
        // Get selected categories
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
            isValid = false;
        }

        if (!codeValue) {
            $('#code').addClass('is-invalid');
            isValid = false;
        }

        if (!genderValue) {
            $('#gender').addClass('is-invalid');
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
                        window.location.href = '{{ route("vendor.sizes.requests.index") }}';
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
</style>
@endpush