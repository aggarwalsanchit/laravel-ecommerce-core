{{-- resources/views/marketplace/pages/attributes/value-request.blade.php --}}
@extends('management.layouts.app')

@section('title', 'Request New Attribute Value')

@section('content')
<div class="page-content">
    <div class="page-container">
        <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
            <div class="flex-grow-1">
                <h4 class="fs-18 text-uppercase fw-bold mb-0">Request New Attribute Value</h4>
                <p class="text-muted mb-0">Submit a request to add a new option for an existing attribute</p>
            </div>
            <div class="text-end">
                <ol class="breadcrumb m-0 py-0">
                    <li class="breadcrumb-item"><a href="{{ route('vendor.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('vendor.attributes.index') }}">Attributes</a></li>
                    <li class="breadcrumb-item active">Request Value</li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card">
                    <div class="card-header border-bottom border-dashed">
                        <h5 class="card-title mb-0">
                            <i class="ti ti-list"></i> Attribute Value Request Form
                        </h5>
                        <p class="text-muted mb-0">Request a new option for an existing attribute</p>
                    </div>
                    <div class="card-body">
                        {{-- Info Alert --}}
                        <div class="alert alert-info mb-4">
                            <div class="d-flex">
                                <i class="ti ti-info-circle me-2 fs-5"></i>
                                <div>
                                    <strong>How it works:</strong>
                                    <ul class="mb-0 mt-1">
                                        <li>Select an existing attribute that needs a new value</li>
                                        <li>Provide the value name and optional display label</li>
                                        <li>Your request will be reviewed by our admin team</li>
                                        <li>Once approved, the value will be available for all vendors</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <form id="valueRequestForm" method="POST" action="{{ route('vendor.attributes.value-request.store') }}">
                            @csrf

                            <div class="row">
                                {{-- Select Attribute --}}
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="attribute_id" class="form-label">Select Attribute <span class="text-danger">*</span></label>
                                        <select class="form-select @error('attribute_id') is-invalid @enderror" id="attribute_id" name="attribute_id" required>
                                            <option value="">-- Choose an attribute --</option>
                                            @foreach($attributes as $attribute)
                                                <option value="{{ $attribute->id }}" {{ old('attribute_id') == $attribute->id ? 'selected' : '' }}>
                                                    {{ $attribute->name }} ({{ $attribute->type_label }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('attribute_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @else
                                            <small class="text-muted">Select the attribute you want to add a value to</small>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Value --}}
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="value" class="form-label">Value <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light">
                                                <i class="ti ti-tag"></i>
                                            </span>
                                            <input type="text" class="form-control @error('value') is-invalid @enderror" 
                                                   id="value" name="value" value="{{ old('value') }}" 
                                                   placeholder="e.g., Extra Large, 32GB, Intel i9" autofocus>
                                        </div>
                                        @error('value')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @else
                                            <small class="text-muted">The actual value to be stored (e.g., xl, 32gb, intel-i9)</small>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Label (Display Name) --}}
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="label" class="form-label">Label (Display Name)</label>
                                        <input type="text" class="form-control" id="label" name="label" value="{{ old('label') }}" 
                                               placeholder="e.g., Extra Large, 32GB, Intel i9">
                                        <small class="text-muted">Optional. If empty, the value will be displayed instead.</small>
                                    </div>
                                </div>

                                {{-- Color Code (only visible if attribute type is color) --}}
                                <div class="col-md-12" id="color-code-section" style="display: none;">
                                    <div class="mb-3">
                                        <label for="color_code" class="form-label">Color Code</label>
                                        <div class="input-group">
                                            <input type="color" class="form-control" id="color_code" name="color_code" value="#000000" style="height: 38px;">
                                            <span class="input-group-text" id="colorPreview" style="width: 50px; background-color: #000000;"></span>
                                        </div>
                                        <small class="text-muted">Required if the attribute is of type "Color"</small>
                                    </div>
                                </div>

                                {{-- Reason for Request --}}
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="reason" class="form-label">Reason for Request <span class="text-danger">*</span></label>
                                        <textarea class="form-control @error('reason') is-invalid @enderror" 
                                                  id="reason" name="reason" rows="3" 
                                                  placeholder="Why do you need this value? How will it help your business?">{{ old('reason') }}</textarea>
                                        @error('reason')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @else
                                            <small class="text-muted">Please provide a detailed reason to help us evaluate your request</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Preview Section --}}
                            <div class="card border mt-3">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="ti ti-eye"></i> Preview</h6>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex align-items-center gap-3">
                                        <div id="previewColor" style="width: 40px; height: 40px; border-radius: 50%; background-color: #ddd; border: 1px solid #ddd; display: none;"></div>
                                        <div>
                                            <div class="fw-semibold" id="previewLabel">Value Label</div>
                                            <div class="text-muted small" id="previewValue">value-slug</div>
                                            <div class="text-muted small" id="previewAttribute">Attribute: —</div>
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
                            <li>Check if the value already exists for the attribute before requesting</li>
                            <li>Use a clear and consistent naming convention</li>
                            <li>For color attributes, provide an accurate hex code</li>
                            <li>Explain why the existing values don't meet your needs</li>
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

    // Show/hide color code section based on selected attribute type
    $('#attribute_id').on('change', function() {
        let attributeId = $(this).val();
        if (attributeId) {
            $.ajax({
                url: '{{ url("vendor/attributes/values") }}/' + attributeId,
                type: 'GET',
                success: function(response) {
                    if (response.attribute && response.attribute.type === 'color') {
                        $('#color-code-section').show();
                    } else {
                        $('#color-code-section').hide();
                    }
                    updatePreview();
                },
                error: function() {
                    $('#color-code-section').hide();
                    updatePreview();
                }
            });
        } else {
            $('#color-code-section').hide();
            updatePreview();
        }
    });

    // Live preview
    $('#value, #label, #attribute_id, #color_code').on('input change', function() {
        updatePreview();
    });

    function updatePreview() {
        let value = $('#value').val().trim();
        let label = $('#label').val().trim();
        let attributeName = $('#attribute_id option:selected').text();
        let colorCode = $('#color_code').val();
        let isColorAttribute = $('#color-code-section').is(':visible');
        
        if (label) {
            $('#previewLabel').text(label);
        } else if (value) {
            $('#previewLabel').text(value);
        } else {
            $('#previewLabel').text('Value Label');
        }
        
        $('#previewValue').text(value || 'value-slug');
        $('#previewAttribute').text('Attribute: ' + (attributeName || '—'));
        
        if (isColorAttribute && colorCode) {
            $('#previewColor').css('background-color', colorCode).show();
        } else {
            $('#previewColor').hide();
        }
    }

    // Color preview
    $('#color_code').on('input', function() {
        let color = $(this).val();
        $('#colorPreview').css('background-color', color);
        updatePreview();
    });

    // Remove error on input
    $('input, select, textarea').on('input change', function() {
        $(this).removeClass('is-invalid');
    });

    // Form submission with button disable
    $('#valueRequestForm').on('submit', function(e) {
        e.preventDefault();
        
        if (formSubmitting) return false;
        
        let isValid = true;
        let attributeId = $('#attribute_id').val();
        let valueValue = $('#value').val().trim();
        let reasonValue = $('#reason').val().trim();

        if (!attributeId) {
            $('#attribute_id').addClass('is-invalid');
            isValid = false;
        }

        if (!valueValue) {
            $('#value').addClass('is-invalid');
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
                        window.location.href = '{{ route("vendor.attributes.value-requests.index") }}';
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