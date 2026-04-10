{{-- resources/views/marketplace/pages/profile/edit.blade.php --}}

@extends('management.layouts.app')

@section('title', 'Edit Profile')

@section('content')
    <div class="page-content">
        <div class="page-container">
            <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                <div class="flex-grow-1">
                    <h4 class="fs-18 text-uppercase fw-bold mb-0">Edit Profile</h4>
                    <p class="text-muted mb-0">Update your profile information</p>
                </div>
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('vendor.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('vendor.profile.index') }}">Profile</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Edit Profile Information</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('vendor.profile.update') }}" enctype="multipart/form-data"
                                id="profileForm">
                                @csrf
                                @method('PUT')

                                {{-- Avatar Section --}}
                                <div class="text-center mb-4">
                                    <div class="position-relative d-inline-block">
                                        @php
                                            $hasAvatar =
                                                $vendor->avatar && Storage::disk('public')->exists($vendor->avatar);
                                            $avatarUrl = $hasAvatar ? Storage::url($vendor->avatar) : null;
                                            $firstChar = strtoupper(substr($vendor->name, 0, 1));
                                            $colors = [
                                                '#0d6efd',
                                                '#198754',
                                                '#dc3545',
                                                '#fd7e14',
                                                '#6f42c1',
                                                '#20c997',
                                                '#0dcaf0',
                                                '#d63384',
                                            ];
                                            $colorIndex = abs(crc32($vendor->name)) % count($colors);
                                            $avatarBgColor = $colors[$colorIndex];
                                        @endphp

                                        <div id="avatarContainer">
                                            @if ($hasAvatar)
                                                <img src="{{ $avatarUrl }}" id="avatarPreview" class="rounded-circle"
                                                    width="100" height="100"
                                                    style="object-fit: cover; border: 3px solid #fff; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                                            @else
                                                <div id="avatarPreview"
                                                    class="rounded-circle d-flex align-items-center justify-content-center text-white"
                                                    style="width: 100px; height: 100px; font-size: 40px; font-weight: 500; background-color: {{ $avatarBgColor }}; border: 3px solid #fff; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                                                    {{ $firstChar }}
                                                </div>
                                            @endif
                                        </div>

                                        <div class="position-absolute bottom-0 end-0">
                                            <label for="avatar_input" class="btn btn-sm btn-secondary rounded-circle"
                                                style="width: 34px; height: 34px; padding: 0; display: flex; align-items: center; justify-content: center; cursor: pointer; background-color: #6c757d; border: 2px solid #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.2); transition: all 0.3s ease;">
                                                <i class="ti ti-camera fs-14"></i>
                                            </label>
                                            <input type="file" id="avatar_input" name="avatar" class="d-none"
                                                accept="image/*">
                                        </div>
                                    </div>
                                    <p class="text-muted small mt-2">Click the camera icon to change avatar</p>
                                </div>

                                <div class="row">
                                    {{-- Personal Information --}}
                                    <div class="col-12">
                                        <h6 class="border-bottom pb-2 mb-3">Personal Information</h6>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                            <input type="text" name="name" id="vendor_name"
                                                class="form-control @error('name') is-invalid @enderror"
                                                value="{{ old('name', $vendor->name) }}" required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Email Address <span
                                                    class="text-danger">*</span></label>
                                            <input type="email" name="email"
                                                class="form-control @error('email') is-invalid @enderror"
                                                value="{{ old('email', $vendor->email) }}" required>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- Country first, then Phone --}}
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label">Country <span class="text-danger">*</span></label>
                                            <select name="country_id" id="country_id" class="form-select"
                                                style="width: 100%;">
                                                <option value="">Select Country</option>
                                                @foreach ($countries as $country)
                                                    <option value="{{ $country->id }}"
                                                        data-phone-code="{{ $country->phonecode }}"
                                                        {{ old('country_id', $vendor->country_id) == $country->id ? 'selected' : '' }}>
                                                        {{ $country->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('country_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label">Phone Number</label>
                                            <div class="row g-2">
                                                <div class="col-md-3">
                                                    <input type="text" name="phone_code" id="phone_code"
                                                        class="form-control @error('phone_code') is-invalid @enderror"
                                                        value="{{ old('phone_code', $vendor->phone_code) }}"
                                                        placeholder="Code" readonly>
                                                </div>
                                                <div class="col-md-9">
                                                    <input type="text" name="phone"
                                                        class="form-control @error('phone') is-invalid @enderror"
                                                        value="{{ old('phone', $vendor->phone) }}"
                                                        placeholder="Enter phone number">
                                                </div>
                                            </div>
                                            @error('phone_code')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                            @error('phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">Country code will auto-populate based on selected
                                                country</small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Birth Date</label>
                                            <input type="date" name="birth_date" id="birth_date"
                                                class="form-control @error('birth_date') is-invalid @enderror"
                                                value="{{ old('birth_date', $vendor->birth_date ? \Carbon\Carbon::parse($vendor->birth_date)->format('Y-m-d') : '') }}"
                                                max="{{ date('Y-m-d') }}">
                                            @error('birth_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">Select your date of birth</small>
                                        </div>
                                    </div>

                                    {{-- Address Information --}}
                                    <div class="col-12">
                                        <h6 class="border-bottom pb-2 mb-3 mt-2">Address Information</h6>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="form-label">Street Address</label>
                                            <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="2">{{ old('address', $vendor->address) }}</textarea>
                                            @error('address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">State/Province</label>
                                            <select name="state_id" id="state_id" class="form-select"
                                                style="width: 100%;">
                                                <option value="">Select State</option>
                                            </select>
                                            @error('state_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">City</label>
                                            <input type="text" name="city" id="city"
                                                class="form-control @error('city') is-invalid @enderror"
                                                value="{{ old('city', $vendor->city) }}" placeholder="Enter city name">
                                            @error('city')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label">Postal / ZIP Code</label>
                                            <input type="text" name="postal_code"
                                                class="form-control @error('postal_code') is-invalid @enderror"
                                                value="{{ old('postal_code', $vendor->postal_code) }}">
                                            @error('postal_code')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2 mt-3">
                                    <a href="{{ route('vendor.profile.index') }}" class="btn btn-secondary">
                                        <i class="ti ti-x"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary" id="submitBtn">
                                        <i class="ti ti-save"></i> Update Profile
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

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .btn-secondary:hover {
            background-color: #5a6268 !important;
            transform: scale(1.05);
        }

        #avatarContainer {
            transition: all 0.3s ease;
        }

        #avatarContainer:hover {
            transform: scale(1.02);
        }

        .select2-container--default .select2-selection--single {
            height: 38px;
            padding: 5px;
            border-radius: 0.375rem;
            border-color: #dee2e6;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 26px;
        }

        .select2-container {
            width: 100% !important;
        }

        .btn-primary:disabled {
            opacity: 0.65;
            cursor: not-allowed;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            let isSubmitting = false;

            // Set max date to today (disable future dates)
            const today = new Date().toISOString().split('T')[0];
            $('#birth_date').attr('max', today);

            // Birth date validation
            $('#birth_date').on('change', function() {
                const selectedDate = $(this).val();
                if (selectedDate > today) {
                    $(this).val('');
                    Swal.fire({
                        icon: 'warning',
                        title: 'Invalid Date',
                        text: 'Birth date cannot be in the future!',
                        timer: 2000,
                        showConfirmButton: false,
                        toast: true,
                        position: 'top-end'
                    });
                }
            });

            // Initialize Select2 for country dropdown
            $('#country_id').select2({
                placeholder: 'Search country...',
                allowClear: true,
                width: '100%',
                dropdownParent: $('#country_id').parent()
            });

            // Initialize Select2 for state dropdown
            $('#state_id').select2({
                placeholder: 'Select State',
                allowClear: true,
                width: '100%',
                dropdownParent: $('#state_id').parent()
            });

            // Function to update state dropdown
            function updateStates(countryId, selectedStateId = null) {
                if (countryId && countryId !== '') {
                    $.ajax({
                        url: '/marketplace/location/states/' + countryId,
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            const $stateSelect = $('#state_id');

                            // Clear existing options
                            $stateSelect.empty();
                            $stateSelect.append('<option value="">Select State</option>');

                            // Populate states
                            if (Array.isArray(response) && response.length > 0) {
                                $.each(response, function(index, state) {
                                    const selected = (selectedStateId == state.id) ?
                                        'selected' : '';
                                    $stateSelect.append('<option value="' + state.id + '" ' +
                                        selected + '>' + state.name + '</option>');
                                });
                            }

                            // Refresh Select2 to show new options
                            $stateSelect.trigger('change');
                        },
                        error: function(xhr) {
                            console.error('Error loading states:', xhr);
                        }
                    });
                } else {
                    const $stateSelect = $('#state_id');
                    $stateSelect.empty();
                    $stateSelect.append('<option value="">Select State</option>');
                    $stateSelect.trigger('change');
                }
            }

            // Country change event
            $('#country_id').on('change', function() {
                const countryId = $(this).val();
                const selectedOption = $(this).find('option:selected');
                const phoneCode = selectedOption.data('phone-code');

                if (phoneCode) {
                    $('#phone_code').val(phoneCode);
                } else {
                    $('#phone_code').val('');
                }

                updateStates(countryId);
            });

            // Load states on page load if country is already selected
            const originalCountryId = $('#country_id').val();
            const originalStateId = {{ $vendor->state_id ?? 'null' }};

            if (originalCountryId && originalCountryId !== '') {
                setTimeout(function() {
                    updateStates(originalCountryId, originalStateId);
                }, 100);
            }

            // Set initial phone code
            const initialPhoneCode = $('#country_id').find('option:selected').data('phone-code');
            if (initialPhoneCode) {
                $('#phone_code').val(initialPhoneCode);
            }

            // Avatar preview
            $('#avatar_input').on('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const imgHtml =
                            `<img src="${e.target.result}" id="avatarPreview" class="rounded-circle" width="100" height="100" style="object-fit: cover; border: 3px solid #fff; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">`;
                        $('#avatarContainer').html(imgHtml);
                    }
                    reader.readAsDataURL(file);
                }
            });

            // Update avatar when name changes
            $('#vendor_name').on('keyup', function() {
                const name = $(this).val();
                const firstChar = name.charAt(0).toUpperCase();

                const currentContent = $('#avatarContainer').html();
                if (currentContent.includes('img')) {
                    return;
                }

                const colors = ['#0d6efd', '#198754', '#dc3545', '#fd7e14', '#6f42c1', '#20c997', '#0dcaf0',
                    '#d63384'
                ];
                const colorIndex = Math.abs(this.value.split('').reduce((a, b) => a + b.charCodeAt(0), 0)) %
                    colors.length;
                const bgColor = colors[colorIndex];

                $('#avatarContainer').html(`
                    <div id="avatarPreview" class="rounded-circle d-flex align-items-center justify-content-center text-white"
                        style="width: 100px; height: 100px; font-size: 40px; font-weight: 500; background-color: ${bgColor}; border: 3px solid #fff; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                        ${firstChar || 'A'}
                    </div>
                `);
            });

            // Form submission with button disable
            $('#profileForm').on('submit', function(e) {
                e.preventDefault();

                if (isSubmitting) {
                    return false;
                }

                // Basic validation
                let isValid = true;
                let firstInvalid = null;

                // Validate required fields
                $('#profileForm [required]').each(function() {
                    if (!$(this).val()) {
                        $(this).addClass('is-invalid');
                        isValid = false;
                        if (!firstInvalid) firstInvalid = $(this);
                    } else {
                        $(this).removeClass('is-invalid');
                    }
                });

                if (!isValid) {
                    if (firstInvalid) {
                        $('html, body').animate({
                            scrollTop: firstInvalid.offset().top - 100
                        }, 500);
                        firstInvalid.focus();
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        text: 'Please fill all required fields',
                        confirmButtonColor: '#d33'
                    });
                    return false;
                }

                isSubmitting = true;
                const $submitBtn = $('#submitBtn');
                const originalText = $submitBtn.html();

                // Disable button and show loading
                $submitBtn.prop('disabled', true);
                $submitBtn.html('<span class="spinner-border spinner-border-sm me-1"></span> Updating...');

                // Submit form via AJAX
                const formData = new FormData(this);

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: response.message ||
                                    'Profile updated successfully!',
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.href = response.redirect_url ||
                                    '{{ route('vendor.profile.index') }}';
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: response.message || 'Something went wrong!',
                                confirmButtonColor: '#d33'
                            });
                            $submitBtn.prop('disabled', false);
                            $submitBtn.html(originalText);
                            isSubmitting = false;
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            $.each(errors, function(field, messages) {
                                const $input = $('[name="' + field + '"]');
                                $input.addClass('is-invalid');
                                if (!$input.next('.invalid-feedback').length) {
                                    $input.after('<div class="invalid-feedback">' +
                                        messages[0] + '</div>');
                                }
                            });
                            Swal.fire({
                                icon: 'error',
                                title: 'Validation Error',
                                text: 'Please check the form for errors',
                                confirmButtonColor: '#d33'
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: xhr.responseJSON?.message ||
                                    'Something went wrong. Please try again.',
                                confirmButtonColor: '#d33'
                            });
                        }
                        $submitBtn.prop('disabled', false);
                        $submitBtn.html(originalText);
                        isSubmitting = false;
                    }
                });
            });

            // Remove error on input
            $('input, select, textarea').on('input change', function() {
                $(this).removeClass('is-invalid');
                $(this).next('.invalid-feedback').remove();
            });
        });
    </script>
@endpush
