{{-- resources/views/marketplace/pages/categories/request.blade.php --}}
@extends('management.layouts.app')

@section('title', 'Request New Category')

@section('content')
<div class="page-content">
    <div class="page-container">
        <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
            <div class="flex-grow-1">
                <h4 class="fs-18 text-uppercase fw-bold mb-0">Request New Category</h4>
                <p class="text-muted mb-0">Submit a request to add a new product category</p>
            </div>
            <div class="text-end">
                <ol class="breadcrumb m-0 py-0">
                    <li class="breadcrumb-item"><a href="{{ route('vendor.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('vendor.categories.index') }}">Categories</a></li>
                    <li class="breadcrumb-item active">Request Category</li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card">
                    <div class="card-header border-bottom border-dashed">
                        <h5 class="card-title mb-0">
                            <i class="ti ti-folder-plus"></i> Category Request Form
                        </h5>
                        <p class="text-muted mb-0">Fill in the details below to request a new category</p>
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
                                        <li>Approved categories will be available for all vendors</li>
                                        <li>You can track your request status in "My Requests" section</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <form id="categoryRequestForm" method="POST" action="{{ route('vendor.categories.request.store') }}">
                            @csrf

                            <div class="row">
                                {{-- Category Name --}}
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Category Name <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light">
                                                <i class="ti ti-folder"></i>
                                            </span>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                                   id="name" name="name" value="{{ old('name') }}" 
                                                   placeholder="e.g., Gaming Laptops, Organic Food, Sports Equipment" autofocus>
                                        </div>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @else
                                            <small class="text-muted">Enter the name of the category you need</small>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Parent Category --}}
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="parent_id" class="form-label">Parent Category <span class="text-muted">(Optional)</span></label>
                                        <select class="form-select @error('parent_id') is-invalid @enderror" id="parent_id" name="parent_id">
                                            <option value="">-- No Parent (Top Level Category) --</option>
                                            @foreach($parentCategories as $category)
                                                <option value="{{ $category->id }}" {{ old('parent_id') == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                                @foreach($category->children as $child)
                                                    <option value="{{ $child->id }}" {{ old('parent_id') == $child->id ? 'selected' : '' }}>
                                                        &nbsp;&nbsp;&nbsp;— {{ $child->name }}
                                                    </option>
                                                @endforeach
                                            @endforeach
                                        </select>
                                        @error('parent_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @else
                                            <small class="text-muted">Select a parent category if this is a subcategory</small>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Description --}}
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                                  id="description" name="description" rows="3" 
                                                  placeholder="Describe what products would belong to this category">{{ old('description') }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @else
                                            <small class="text-muted">Optional. Provide a brief description of the category</small>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Reason for Request --}}
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="reason" class="form-label">Reason for Request <span class="text-danger">*</span></label>
                                        <textarea class="form-control @error('reason') is-invalid @enderror" 
                                                  id="reason" name="reason" rows="3" 
                                                  placeholder="Why do you need this category? How will it help your business?">{{ old('reason') }}</textarea>
                                        @error('reason')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @else
                                            <small class="text-muted">Please provide a detailed reason to help us evaluate your request</small>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Preview Section --}}
                                <div class="col-md-12">
                                    <div class="card border mt-2">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0"><i class="ti ti-eye"></i> Preview</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="d-flex align-items-center gap-3">
                                                <i class="ti ti-folder text-primary fs-1"></i>
                                                <div>
                                                    <div class="fw-semibold fs-5" id="previewName">Category Name</div>
                                                    <div class="text-muted small" id="previewPath">Main Category</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <a href="{{ route('vendor.categories.index') }}" class="btn btn-danger">
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
                            <li>Check if the category already exists before requesting</li>
                            <li>Be specific about what products belong to this category</li>
                            <li>Explain why existing categories don't meet your needs</li>
                            <li>Provide examples of products you would list in this category</li>
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
<script>
$(document).ready(function() {
    let formSubmitting = false;

    // Live preview
    $('#name, #parent_id').on('input change', function() {
        updatePreview();
    });

    function updatePreview() {
        let name = $('#name').val().trim();
        let parentId = $('#parent_id').val();
        let parentName = $('#parent_id option:selected').text();
        
        if (name) {
            $('#previewName').text(name);
        } else {
            $('#previewName').text('Category Name');
        }
        
        if (parentId && parentName !== '-- No Parent (Top Level Category) --') {
            $('#previewPath').text(parentName + ' > ' + (name || 'Subcategory'));
        } else {
            $('#previewPath').text(name || 'Main Category');
        }
    }

    // Remove error on input
    $('input, select, textarea').on('input change', function() {
        $(this).removeClass('is-invalid');
    });

    // Form submission with button disable
    $('#categoryRequestForm').on('submit', function(e) {
        e.preventDefault();
        
        if (formSubmitting) return false;
        
        let isValid = true;
        let nameValue = $('#name').val().trim();
        let reasonValue = $('#reason').val().trim();

        if (!nameValue) {
            $('#name').addClass('is-invalid');
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
        
        // Disable button and show loading state
        btn.html('<span class="spinner-border spinner-border-sm me-1"></span> Submitting...');
        btn.prop('disabled', true);
        
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
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
                        window.location.href = '{{ route("vendor.categories.requests.index") }}';
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
                    
                    // Display validation errors on fields
                    $.each(errors, function(field, messages) {
                        $('#' + field).addClass('is-invalid');
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

    // Initial preview
    updatePreview();
});
</script>
@endpush

@push('styles')
<style>
    .form-check-input:checked {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
    .invalid-feedback {
        display: block;
        font-size: 0.875em;
        margin-top: 0.25rem;
    }
</style>
@endpush