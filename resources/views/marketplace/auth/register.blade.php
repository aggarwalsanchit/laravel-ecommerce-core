{{-- resources/views/marketplace/auth/register.blade.php --}}
@extends('management.layouts.auth')

@section('title', 'Become a Seller')

@section('content')
    <div class="auth-bg d-flex min-vh-100 justify-content-center align-items-center">
        <div class="row g-0 justify-content-center w-100 m-xxl-5 px-xxl-4 m-3">
            <div class="col-xl-6 col-lg-7 col-md-8">
                <div class="card overflow-hidden h-100 p-xxl-4 p-3 mb-0">
                    <div class="text-center">
                        <a href="{{ url('/') }}" class="auth-brand mb-3 d-inline-block">
                            <img src="{{ asset('assets/images/logo-dark.png') }}" alt="dark logo" height="24"
                                class="logo-dark">
                            <img src="{{ asset('assets/images/logo.png') }}" alt="logo light" height="24"
                                class="logo-light">
                        </a>

                        <h4 class="fw-semibold mb-2">Become a Seller</h4>
                        <p class="text-muted mb-4">Join our marketplace and start selling your products</p>
                    </div>

                    <form id="vendorRegisterForm" class="text-start mb-3" method="POST"
                        action="{{ route('vendor.register.submit') }}">
                        @csrf

                        {{-- Progress Steps --}}
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <div class="step-item text-center flex-fill" id="step1Indicator">
                                        <div class="step-circle bg-primary text-white mx-auto mb-1"
                                            style="width: 30px; height: 30px; line-height: 30px; border-radius: 50%;">1
                                        </div>
                                        <small class="step-label">Personal Info</small>
                                    </div>
                                    <div class="step-item text-center flex-fill" id="step2Indicator">
                                        <div class="step-circle bg-secondary text-white mx-auto mb-1"
                                            style="width: 30px; height: 30px; line-height: 30px; border-radius: 50%;">2
                                        </div>
                                        <small class="step-label">Shop Details</small>
                                    </div>
                                    <div class="step-item text-center flex-fill" id="step3Indicator">
                                        <div class="step-circle bg-secondary text-white mx-auto mb-1"
                                            style="width: 30px; height: 30px; line-height: 30px; border-radius: 50%;">3
                                        </div>
                                        <small class="step-label">Business Info</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Step 1: Personal Information --}}
                        <div id="step1" class="form-step">
                            <div class="mb-3">
                                <label class="form-label" for="name">Full Name <span
                                        class="text-danger">*</span></label>
                                <input type="text" id="name" name="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    placeholder="Enter your full name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="email">Email Address <span
                                        class="text-danger">*</span></label>
                                <input type="email" id="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    placeholder="Enter your email address" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="phone">Phone Number <span
                                        class="text-danger">*</span></label>
                                <input type="tel" id="phone" name="phone"
                                    class="form-control @error('phone') is-invalid @enderror"
                                    placeholder="Enter your phone number" value="{{ old('phone') }}" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="password">Password <span class="text-danger">*</span></label>
                                <input type="password" id="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Create a password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="password_confirmation">Confirm Password <span
                                        class="text-danger">*</span></label>
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                    class="form-control" placeholder="Confirm your password" required>
                            </div>
                        </div>

                        {{-- Step 2: Shop Details --}}
                        <div id="step2" class="form-step" style="display: none;">
                            <div class="mb-3">
                                <label class="form-label" for="shop_name">Shop Name <span
                                        class="text-danger">*</span></label>
                                <input type="text" id="shop_name" name="shop_name"
                                    class="form-control @error('shop_name') is-invalid @enderror"
                                    placeholder="Enter your shop/business name" value="{{ old('shop_name') }}" required>
                                <small class="text-muted">This will be your store name visible to customers</small>
                                @error('shop_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="shop_slug">Shop URL Slug</label>
                                <div class="input-group">
                                    <span class="input-group-text">{{ url('/shop') }}/</span>
                                    <input type="text" id="shop_slug" name="shop_slug"
                                        class="form-control @error('shop_slug') is-invalid @enderror"
                                        placeholder="your-shop-name" value="{{ old('shop_slug') }}">
                                </div>
                                <small class="text-muted">Leave empty to auto-generate from shop name</small>
                                @error('shop_slug')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="shop_description">Shop Description <span
                                        class="text-danger">*</span></label>
                                <textarea id="shop_description" name="shop_description"
                                    class="form-control @error('shop_description') is-invalid @enderror" rows="3"
                                    placeholder="Describe your shop and what you sell" required>{{ old('shop_description') }}</textarea>
                                @error('shop_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="shop_email">Business Email <span
                                        class="text-danger">*</span></label>
                                <input type="email" id="shop_email" name="shop_email"
                                    class="form-control @error('shop_email') is-invalid @enderror"
                                    placeholder="business@yourcompany.com" value="{{ old('shop_email') }}" required>
                                @error('shop_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="shop_phone">Business Phone <span
                                        class="text-danger">*</span></label>
                                <input type="tel" id="shop_phone" name="shop_phone"
                                    class="form-control @error('shop_phone') is-invalid @enderror"
                                    placeholder="Business contact number" value="{{ old('shop_phone') }}" required>
                                @error('shop_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="shop_whatsapp">WhatsApp Number</label>
                                <input type="tel" id="shop_whatsapp" name="shop_whatsapp" class="form-control"
                                    placeholder="WhatsApp number for customer support"
                                    value="{{ old('shop_whatsapp') }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="shop_website">Website (Optional)</label>
                                <input type="url" id="shop_website" name="shop_website" class="form-control"
                                    placeholder="https://yourwebsite.com" value="{{ old('shop_website') }}">
                            </div>
                        </div>

                        {{-- Step 3: Business Information --}}
                        <div id="step3" class="form-step" style="display: none;">
                            <div class="mb-3">
                                <label class="form-label" for="business_type">Business Type <span
                                        class="text-danger">*</span></label>
                                <select id="business_type" name="business_type"
                                    class="form-select @error('business_type') is-invalid @enderror" required>
                                    <option value="">Select Business Type</option>
                                    <option value="sole_proprietorship"
                                        {{ old('business_type') == 'sole_proprietorship' ? 'selected' : '' }}>Sole
                                        Proprietorship</option>
                                    <option value="partnership"
                                        {{ old('business_type') == 'partnership' ? 'selected' : '' }}>Partnership</option>
                                    <option value="llc" {{ old('business_type') == 'llc' ? 'selected' : '' }}>Limited
                                        Liability Company (LLC)</option>
                                    <option value="private_limited"
                                        {{ old('business_type') == 'private_limited' ? 'selected' : '' }}>Private Limited
                                        Company</option>
                                    <option value="public_limited"
                                        {{ old('business_type') == 'public_limited' ? 'selected' : '' }}>Public Limited
                                        Company</option>
                                    <option value="trust" {{ old('business_type') == 'trust' ? 'selected' : '' }}>Trust /
                                        NGO</option>
                                    <option value="other" {{ old('business_type') == 'other' ? 'selected' : '' }}>Other
                                    </option>
                                </select>
                                @error('business_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="shop_address">Business Address <span
                                        class="text-danger">*</span></label>
                                <textarea id="shop_address" name="shop_address" class="form-control @error('shop_address') is-invalid @enderror"
                                    rows="2" placeholder="Complete business address" required>{{ old('shop_address') }}</textarea>
                                @error('shop_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="shop_city">City <span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="shop_city" name="shop_city"
                                        class="form-control @error('shop_city') is-invalid @enderror" placeholder="City"
                                        value="{{ old('shop_city') }}" required>
                                    @error('shop_city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="shop_state">State <span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="shop_state" name="shop_state"
                                        class="form-control @error('shop_state') is-invalid @enderror"
                                        placeholder="State" value="{{ old('shop_state') }}" required>
                                    @error('shop_state')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="shop_country">Country <span
                                            class="text-danger">*</span></label>
                                    <select id="shop_country" name="shop_country"
                                        class="form-select @error('shop_country') is-invalid @enderror" required>
                                        <option value="">Select Country</option>
                                        <option value="India" {{ old('shop_country') == 'India' ? 'selected' : '' }}>
                                            India</option>
                                        <option value="United States"
                                            {{ old('shop_country') == 'United States' ? 'selected' : '' }}>United States
                                        </option>
                                        <option value="United Kingdom"
                                            {{ old('shop_country') == 'United Kingdom' ? 'selected' : '' }}>United Kingdom
                                        </option>
                                        <option value="Canada" {{ old('shop_country') == 'Canada' ? 'selected' : '' }}>
                                            Canada</option>
                                        <option value="Australia"
                                            {{ old('shop_country') == 'Australia' ? 'selected' : '' }}>Australia</option>
                                    </select>
                                    @error('shop_country')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="shop_postal_code">Postal Code <span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="shop_postal_code" name="shop_postal_code"
                                        class="form-control @error('shop_postal_code') is-invalid @enderror"
                                        placeholder="Postal / ZIP code" value="{{ old('shop_postal_code') }}" required>
                                    @error('shop_postal_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Business Categories (Select up to 5)</label>
                                <select class="form-select" name="business_categories[]" multiple>
                                    <option value="electronics">Electronics</option>
                                    <option value="fashion">Fashion & Clothing</option>
                                    <option value="home">Home & Living</option>
                                    <option value="beauty">Beauty & Personal Care</option>
                                    <option value="sports">Sports & Fitness</option>
                                    <option value="toys">Toys & Games</option>
                                    <option value="books">Books & Media</option>
                                    <option value="automotive">Automotive</option>
                                </select>
                                <small class="text-muted">Hold Ctrl/Cmd to select multiple categories</small>
                            </div>

                            <div class="form-check mb-3">
                                <input type="checkbox" class="form-check-input" id="accepts_cod" name="accepts_cod"
                                    value="1" {{ old('accepts_cod') ? 'checked' : '' }}>
                                <label class="form-check-label" for="accepts_cod">
                                    I accept Cash on Delivery (COD) orders
                                </label>
                            </div>
                        </div>

                        {{-- Terms and Conditions --}}
                        <div class="form-check mb-3" id="termsCheck">
                            <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                            <label class="form-check-label" for="terms">
                                I agree to all <a href="#!" class="link-dark text-decoration-underline">Terms &
                                    Conditions</a> and
                                <a href="#!" class="link-dark text-decoration-underline">Seller Policies</a>
                            </label>
                        </div>

                        {{-- Navigation Buttons --}}
                        <div class="d-flex justify-content-between gap-2">
                            <button type="button" class="btn btn-secondary" id="prevBtn" style="display: none;">
                                <i class="ti ti-arrow-left"></i> Previous
                            </button>
                            <button type="button" class="btn btn-primary" id="nextBtn">
                                Next <i class="ti ti-arrow-right"></i>
                            </button>
                            <button type="submit" class="btn btn-success" id="submitBtn" style="display: none;">
                                <i class="ti ti-check"></i> Register as Seller
                            </button>
                        </div>
                    </form>

                    <div class="text-center mt-4">
                        <p class="text-muted mb-0">Already have a seller account?
                            <a href="{{ route('vendor.login') }}" class="fw-semibold text-primary">Login here</a>
                        </p>
                        <p class="mt-2 mb-0">
                            <a href="{{ route('vendor.login') }}" class="text-muted">
                                <i class="ti ti-arrow-left"></i> Back to Home
                            </a>
                        </p>
                    </div>

                    <p class="mt-auto mb-0 text-center pt-3">
                        <script>
                            document.write(new Date().getFullYear())
                        </script> © Boron Marketplace - Sell with us
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            let currentStep = 1;
            const totalSteps = 3;

            function updateSteps() {
                // Update step indicators
                for (let i = 1; i <= totalSteps; i++) {
                    if (i < currentStep) {
                        $(`#step${i}Indicator .step-circle`).removeClass('bg-primary bg-secondary').addClass(
                            'bg-success');
                    } else if (i === currentStep) {
                        $(`#step${i}Indicator .step-circle`).removeClass('bg-secondary bg-success').addClass(
                            'bg-primary');
                    } else {
                        $(`#step${i}Indicator .step-circle`).removeClass('bg-primary bg-success').addClass(
                            'bg-secondary');
                    }
                }

                // Show/hide form steps
                $('.form-step').hide();
                $(`#step${currentStep}`).show();

                // Show/hide buttons
                $('#prevBtn').toggle(currentStep > 1);
                $('#nextBtn').toggle(currentStep < totalSteps);
                $('#submitBtn').toggle(currentStep === totalSteps);
            }

            function validateStep() {
                let isValid = true;

                if (currentStep === 1) {
                    if (!$('#name').val().trim()) {
                        $('#name').addClass('is-invalid');
                        isValid = false;
                    }
                    if (!$('#email').val().trim()) {
                        $('#email').addClass('is-invalid');
                        isValid = false;
                    }
                    if (!$('#password').val()) {
                        $('#password').addClass('is-invalid');
                        isValid = false;
                    }
                    if ($('#password').val() !== $('#password_confirmation').val()) {
                        $('#password_confirmation').addClass('is-invalid');
                        isValid = false;
                    }
                } else if (currentStep === 2) {
                    if (!$('#shop_name').val().trim()) {
                        $('#shop_name').addClass('is-invalid');
                        isValid = false;
                    }
                    if (!$('#shop_description').val().trim()) {
                        $('#shop_description').addClass('is-invalid');
                        isValid = false;
                    }
                    if (!$('#shop_email').val().trim()) {
                        $('#shop_email').addClass('is-invalid');
                        isValid = false;
                    }
                    if (!$('#shop_phone').val().trim()) {
                        $('#shop_phone').addClass('is-invalid');
                        isValid = false;
                    }
                } else if (currentStep === 3) {
                    if (!$('#business_type').val()) {
                        $('#business_type').addClass('is-invalid');
                        isValid = false;
                    }
                    if (!$('#shop_address').val().trim()) {
                        $('#shop_address').addClass('is-invalid');
                        isValid = false;
                    }
                    if (!$('#shop_city').val().trim()) {
                        $('#shop_city').addClass('is-invalid');
                        isValid = false;
                    }
                    if (!$('#shop_state').val().trim()) {
                        $('#shop_state').addClass('is-invalid');
                        isValid = false;
                    }
                    if (!$('#shop_country').val()) {
                        $('#shop_country').addClass('is-invalid');
                        isValid = false;
                    }
                    if (!$('#shop_postal_code').val().trim()) {
                        $('#shop_postal_code').addClass('is-invalid');
                        isValid = false;
                    }
                    if (!$('#terms').is(':checked')) {
                        alert('Please accept Terms & Conditions');
                        isValid = false;
                    }
                }

                return isValid;
            }

            // Remove error on input
            $('input, select, textarea').on('input change', function() {
                $(this).removeClass('is-invalid');
            });

            // Next button click
            $('#nextBtn').on('click', function() {
                if (validateStep()) {
                    currentStep++;
                    updateSteps();
                }
            });

            // Previous button click
            $('#prevBtn').on('click', function() {
                currentStep--;
                updateSteps();
            });

            // Auto-generate slug from shop name
            $('#shop_name').on('keyup', function() {
                let slug = $(this).val().toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
                $('#shop_slug').val(slug);
            });

            // Initialize
            updateSteps();
        });
    </script>
@endpush

@push('styles')
    <style>
        .step-circle {
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 14px;
        }

        .step-label {
            font-size: 12px;
            color: #6c757d;
        }

        .step-item.active .step-label {
            color: #0d6efd;
            font-weight: 500;
        }

        .form-step {
            min-height: 350px;
        }

        .btn {
            padding: 10px 20px;
            font-weight: 500;
        }

        .auth-brand img {
            max-height: 32px;
        }
    </style>
@endpush
