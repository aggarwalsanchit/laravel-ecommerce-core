{{-- resources/views/marketplace/pages/colors/request.blade.php --}}
@extends('management.layouts.app')

@section('title', 'Request New Color')

@section('content')
<div class="page-content">
    <div class="page-container">
        <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
            <div class="flex-grow-1">
                <h4 class="fs-18 text-uppercase fw-bold mb-0">Request New Color</h4>
                <p class="text-muted mb-0">Submit a request to add a new product color</p>
            </div>
            <div class="text-end">
                <ol class="breadcrumb m-0 py-0">
                    <li class="breadcrumb-item"><a href="{{ route('vendor.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('vendor.colors.index') }}">Colors</a></li>
                    <li class="breadcrumb-item active">Request Color</li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card">
                    <div class="card-header border-bottom border-dashed">
                        <h5 class="card-title mb-0">
                            <i class="ti ti-color-swatch"></i> Color Request Form
                        </h5>
                        <p class="text-muted mb-0">Fill in the details below to request a new color</p>
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
                                        <li>Approved colors will be available for all vendors</li>
                                        <li>You can track your request status in "My Requests" section</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <form id="colorRequestForm" method="POST" action="{{ route('vendor.colors.request.store') }}">
                            @csrf

                            <div class="row">
                                {{-- Color Name --}}
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Color Name <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light">
                                                <i class="ti ti-palette"></i>
                                            </span>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                                   id="name" name="name" value="{{ old('name') }}" 
                                                   placeholder="e.g., Crimson Red, Ocean Blue, Forest Green" autofocus>
                                        </div>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @else
                                            <small class="text-muted">Enter the name of the color you need</small>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Hex Code --}}
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="code" class="form-label">Hex Code <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light">
                                                <i class="ti ti-color-swatch"></i>
                                            </span>
                                            <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                                   id="code" name="code" value="{{ old('code') }}" 
                                                   placeholder="#FF0000" maxlength="7">
                                            <span class="input-group-text" id="colorPreview" style="width: 50px; background-color: #000000;"></span>
                                        </div>
                                        @error('code')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @else
                                            <small class="text-muted">Enter a valid hex code (e.g., #FF0000 for Red)</small>
                                        @enderror
                                    </div>
                                </div>

                                {{-- RGB Value (Optional) --}}
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="rgb" class="form-label">RGB Value</label>
                                        <input type="text" class="form-control" id="rgb" name="rgb" 
                                               value="{{ old('rgb') }}" placeholder="rgb(255, 0, 0)">
                                        <small class="text-muted">Optional</small>
                                    </div>
                                </div>

                                {{-- HSL Value (Optional) --}}
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="hsl" class="form-label">HSL Value</label>
                                        <input type="text" class="form-control" id="hsl" name="hsl" 
                                               value="{{ old('hsl') }}" placeholder="hsl(0, 100%, 50%)">
                                        <small class="text-muted">Optional</small>
                                    </div>
                                </div>

                                {{-- Description --}}
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea class="form-control" id="description" name="description" rows="2" 
                                                  placeholder="Describe this color and where it would be used">{{ old('description') }}</textarea>
                                        <small class="text-muted">Optional. Provide a brief description of the color</small>
                                    </div>
                                </div>

                                {{-- Reason for Request --}}
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="reason" class="form-label">Reason for Request <span class="text-danger">*</span></label>
                                        <textarea class="form-control @error('reason') is-invalid @enderror" 
                                                  id="reason" name="reason" rows="3" 
                                                  placeholder="Why do you need this color? How will it help your business?">{{ old('reason') }}</textarea>
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
                                                <div id="previewColor" style="width: 60px; height: 60px; background-color: #000000; border-radius: 50%; border: 2px solid #ddd;"></div>
                                                <div>
                                                    <div class="fw-semibold fs-5" id="previewName">Color Name</div>
                                                    <div class="text-muted small" id="previewCode">#000000</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <a href="{{ route('vendor.colors.index') }}" class="btn btn-danger">
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
                            <li>Check if the color already exists before requesting</li>
                            <li>Provide accurate hex code for the color</li>
                            <li>Explain why existing colors don't meet your needs</li>
                            <li>Mention potential demand from other vendors</li>
                            <li>Provide product examples that would use this color</li>
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

    // Hex code validation function
    function isValidHexCode(code) {
        return /^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/.test(code);
    }

    // Live preview
    $('#name, #code').on('input change', function() {
        updatePreview();
    });

    // Color preview on hex code change
    $('#code').on('input', function() {
        let code = $(this).val();
        if (isValidHexCode(code)) {
            $('#colorPreview').css('background-color', code);
            $('#previewColor').css('background-color', code);
            $(this).removeClass('is-invalid');
        } else {
            $('#colorPreview').css('background-color', '#000000');
            $('#previewColor').css('background-color', '#000000');
        }
        updatePreview();
    });

    function updatePreview() {
        let name = $('#name').val().trim();
        let code = $('#code').val().trim();
        
        if (name) {
            $('#previewName').text(name);
        } else {
            $('#previewName').text('Color Name');
        }
        
        if (code && isValidHexCode(code)) {
            $('#previewCode').text(code.toUpperCase());
        } else {
            $('#previewCode').text('#000000');
        }
    }

    // Remove error on input
    $('input, select, textarea').on('input change', function() {
        $(this).removeClass('is-invalid');
    });

    // Form submission with button disable
    $('#colorRequestForm').on('submit', function(e) {
        e.preventDefault();
        
        if (formSubmitting) return false;
        
        let isValid = true;
        let nameValue = $('#name').val().trim();
        let codeValue = $('#code').val().trim();
        let reasonValue = $('#reason').val().trim();

        if (!nameValue) {
            $('#name').addClass('is-invalid');
            isValid = false;
        }

        if (!codeValue) {
            $('#code').addClass('is-invalid');
            $('#code-error').text('Hex code is required');
            isValid = false;
        } else if (!isValidHexCode(codeValue)) {
            $('#code').addClass('is-invalid');
            $('#code-error').text('Please enter a valid hex code (e.g., #FF0000)');
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
                        window.location.href = '{{ route("vendor.colors.requests.index") }}';
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
                        if (field === 'code') {
                            $('#code-error').text(messages[0]);
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
    #previewColor {
        transition: background-color 0.2s ease;
    }
</style>
@endpush