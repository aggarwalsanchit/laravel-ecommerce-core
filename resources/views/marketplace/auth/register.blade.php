{{-- resources/views/marketplace/auth/register.blade.php --}}

@extends('management.layouts.auth')

@section('title', 'Become a Seller')

@section('content')
    @php
        use App\Models\WebsiteSetting;
        use Illuminate\Support\Facades\Storage;

        $settings = WebsiteSetting::first();

        $logoLight = asset('dummy-admin-logo.webp');
        if ($settings && $settings->logo_light && Storage::disk('public')->exists($settings->logo_light)) {
            $logoLight = asset('storage/' . $settings->logo_light);
        } elseif (file_exists(public_path('assets/images/logo.png'))) {
            $logoLight = asset('assets/images/logo.png');
        }

        $logoDark = asset('dummy-admin-logo.webp');
        if ($settings && $settings->logo_dark && Storage::disk('public')->exists($settings->logo_dark)) {
            $logoDark = asset('storage/' . $settings->logo_dark);
        } elseif (file_exists(public_path('assets/images/logo-dark.png'))) {
            $logoDark = asset('assets/images/logo-dark.png');
        }

        $websiteName = $settings->website_name ?? 'Boron Marketplace';
    @endphp

    <div class="auth-bg d-flex min-vh-100 justify-content-center align-items-center py-5">
        <div class="row g-0 justify-content-center w-100 m-xxl-5 px-xxl-4 m-3">
            <div class="col-xl-6 col-lg-8 col-md-10">
                <div class="card overflow-hidden h-100 p-xxl-4 p-3 mb-0">
                    <div class="text-center">
                        <a href="{{ url('/') }}" class="auth-brand mb-3 d-inline-block">
                            <img src="{{ $logoLight }}" alt="{{ $websiteName }}" height="30" class="logo-light">
                            <img src="{{ $logoDark }}" alt="{{ $websiteName }}" height="30" class="logo-dark">
                        </a>
                        <h4 class="fw-semibold mb-2">Become a Seller</h4>
                        <p class="text-muted mb-4">Join {{ $websiteName }} marketplace and start selling your products</p>
                    </div>

                    <form id="vendorRegisterForm" class="text-start mb-3" method="POST"
                        action="{{ route('vendor.register.submit') }}" enctype="multipart/form-data">
                        @csrf

                        {{-- Personal Information --}}
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label" for="name">Full Name <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="ti ti-user"></i></span>
                                    <input type="text" id="name" name="name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        placeholder="Enter your full name" value="{{ old('name') }}" required>
                                </div>
                                <div class="invalid-feedback name-error d-none"></div>
                                @error('name')
                                    <div class="invalid-feedback d-block text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label" for="email">Email Address <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="ti ti-mail"></i></span>
                                    <input type="email" id="email" name="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        placeholder="Enter your email address" value="{{ old('email') }}" required>
                                </div>
                                <div class="invalid-feedback email-error d-none"></div>
                                @error('email')
                                    <div class="invalid-feedback d-block text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="password">Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="ti ti-lock"></i></span>
                                    <input type="password" id="password" name="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        placeholder="Create a password" required>
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="ti ti-eye"></i>
                                    </button>
                                </div>
                                <div class="invalid-feedback password-error d-none"></div>
                                @error('password')
                                    <div class="invalid-feedback d-block text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="password_confirmation">Confirm Password <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="ti ti-lock"></i></span>
                                    <input type="password" id="password_confirmation" name="password_confirmation"
                                        class="form-control" placeholder="Confirm your password" required>
                                </div>
                                <div class="invalid-feedback confirm-password-error d-none"></div>
                            </div>
                        </div>

                        {{-- Shop Information --}}
                        <div class="row mt-3">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3">Shop Information</h5>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label" for="shop_name">Shop Name <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="ti ti-building-store"></i></span>
                                    <input type="text" id="shop_name" name="shop_name"
                                        class="form-control @error('shop_name') is-invalid @enderror"
                                        placeholder="Enter your shop/business name" value="{{ old('shop_name') }}"
                                        required>
                                </div>
                                <div class="invalid-feedback shop-name-error d-none"></div>
                                @error('shop_name')
                                    <div class="invalid-feedback d-block text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label" for="shop_slug">Shop URL Slug</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">{{ url('/shop') }}/</span>
                                    <input type="text" id="shop_slug" name="shop_slug"
                                        class="form-control @error('shop_slug') is-invalid @enderror"
                                        placeholder="your-shop-name" value="{{ old('shop_slug') }}">
                                </div>
                                <small class="text-muted">Leave empty to auto-generate from shop name</small>
                                <div class="invalid-feedback shop-slug-error d-none"></div>
                                @error('shop_slug')
                                    <div class="invalid-feedback d-block text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Terms and Conditions - Fixed to same line --}}
                        <div class="mb-3 mt-4">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                                <label class="form-check-label" for="terms">
                                    I agree to all <a href="#!" class="link-dark text-decoration-underline">Terms &
                                        Conditions</a> and
                                    <a href="#!" class="link-dark text-decoration-underline">Seller Policies</a>
                                </label>
                            </div>
                            <div class="invalid-feedback terms-error d-none"></div>
                            @error('terms')
                                <div class="invalid-feedback d-block text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="ti ti-check"></i> Register as Seller
                            </button>
                        </div>
                    </form>

                    <div class="text-center mt-4">
                        <p class="text-muted mb-0">Already have a seller account?
                            <a href="{{ route('vendor.login') }}" class="fw-semibold text-primary">Login here</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .auth-brand img {
            max-height: 32px;
        }

        .is-invalid {
            border-color: #dc3545 !important;
        }

        .invalid-feedback {
            display: block;
            width: 100%;
            margin-top: 0.25rem;
            font-size: 0.875em;
            color: #dc3545;
        }

        .form-check {
            display: flex;
            align-items: center;
        }

        .form-check-input {
            margin-top: 0;
            margin-right: 8px;
        }

        .form-check-label {
            margin-bottom: 0;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Password toggle
            $('#togglePassword').on('click', function() {
                const type = $('#password').attr('type') === 'password' ? 'text' : 'password';
                $('#password').attr('type', type);
                $(this).find('i').toggleClass('ti-eye ti-eye-off');
            });

            // Auto-generate slug
            $('#shop_name').on('keyup', function() {
                let slug = $(this).val().toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
                $('#shop_slug').val(slug);
            });

            // Clear error on input
            $('input').on('input change', function() {
                $(this).removeClass('is-invalid');
                $(this).closest('.mb-3').find('.invalid-feedback').addClass('d-none').text('');
            });

            // Client-side validation before submit
            $('#vendorRegisterForm').on('submit', function(e) {
                let isValid = true;

                // Clear all previous errors
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').addClass('d-none').text('');

                // Validate Name
                let name = $('#name').val().trim();
                if (name === '') {
                    $('#name').addClass('is-invalid');
                    $('.name-error').removeClass('d-none').text('Please enter your full name');
                    isValid = false;
                }

                // Validate Email
                let email = $('#email').val().trim();
                let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (email === '') {
                    $('#email').addClass('is-invalid');
                    $('.email-error').removeClass('d-none').text('Please enter your email address');
                    isValid = false;
                } else if (!emailRegex.test(email)) {
                    $('#email').addClass('is-invalid');
                    $('.email-error').removeClass('d-none').text('Please enter a valid email address');
                    isValid = false;
                }

                // Validate Password
                let password = $('#password').val();
                if (password === '') {
                    $('#password').addClass('is-invalid');
                    $('.password-error').removeClass('d-none').text('Please create a password');
                    isValid = false;
                } else if (password.length < 8) {
                    $('#password').addClass('is-invalid');
                    $('.password-error').removeClass('d-none').text(
                        'Password must be at least 8 characters');
                    isValid = false;
                }

                // Validate Password Confirmation
                let confirmPassword = $('#password_confirmation').val();
                if (confirmPassword === '') {
                    $('#password_confirmation').addClass('is-invalid');
                    $('.confirm-password-error').removeClass('d-none').text('Please confirm your password');
                    isValid = false;
                } else if (password !== confirmPassword) {
                    $('#password_confirmation').addClass('is-invalid');
                    $('.confirm-password-error').removeClass('d-none').text(
                        'Password confirmation does not match');
                    isValid = false;
                }

                // Validate Shop Name
                let shopName = $('#shop_name').val().trim();
                if (shopName === '') {
                    $('#shop_name').addClass('is-invalid');
                    $('.shop-name-error').removeClass('d-none').text('Please enter your shop name');
                    isValid = false;
                }

                // Validate Terms
                if (!$('#terms').is(':checked')) {
                    $('#terms').addClass('is-invalid');
                    $('.terms-error').removeClass('d-none').text(
                        'You must agree to the terms and conditions');
                    isValid = false;
                }

                // If not valid, prevent form submission and scroll to first error
                if (!isValid) {
                    e.preventDefault();
                    let firstError = $('.is-invalid:first');
                    if (firstError.length) {
                        $('html, body').animate({
                            scrollTop: firstError.offset().top - 100
                        }, 500);
                        firstError.focus();
                    }
                    return false;
                }
            });
        });
    </script>
@endpush
