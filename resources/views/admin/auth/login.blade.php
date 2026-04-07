{{-- resources/views/admin/auth/login.blade.php --}}

@extends('management.layouts.auth')

@section('title', 'Login - ' . ($websiteSettings->website_name ?? 'Admin Panel'))

@section('content')
    <div class="auth-bg d-flex min-vh-100 justify-content-center align-items-center">
        <div class="row g-0 justify-content-center w-100 m-xxl-5 px-xxl-4 m-3">
            <div class="col-xl-4 col-lg-5 col-md-6">
                <div class="card overflow-hidden text-center h-100 p-xxl-4 p-3 mb-0">
                    <a href="{{ route('admin.login') }}" class="auth-brand mb-3">
                        {{-- Logo from website settings --}}
                        @php
                            $logoLight =
                                isset($websiteSettings) &&
                                $websiteSettings->logo_light &&
                                Storage::disk('public')->exists($websiteSettings->logo_light)
                                    ? asset('storage/' . $websiteSettings->logo_light)
                                    : null;
                            $logoDark =
                                isset($websiteSettings) &&
                                $websiteSettings->logo_dark &&
                                Storage::disk('public')->exists($websiteSettings->logo_dark)
                                    ? asset('storage/' . $websiteSettings->logo_dark)
                                    : null;
                        @endphp

                        @if ($logoDark || $logoLight)
                            @if ($logoLight)
                                <img src="{{ $logoLight }}" alt="{{ $websiteSettings->logo_light_alt_tag ?? 'Logo' }}"
                                    height="35" class="logo-light">
                            @endif
                            @if ($logoDark)
                                <img src="{{ $logoDark }}" alt="{{ $websiteSettings->logo_dark_alt_tag ?? 'Logo' }}"
                                    height="35" class="logo-dark">
                            @endif
                        @else
                            {{-- Dummy logo if no logo in settings --}}
                            <img src="{{ asset('dummy-admin-logo.webp') }}" alt="Logo" height="40">
                        @endif
                    </a>

                    <h4 class="fw-semibold mb-2">Login your account</h4>

                    <p class="text-muted mb-4">Enter your email address and password to access admin panel.</p>

                    <form method="POST" action="{{ route('admin.login.submit') }}" class="text-start mb-3" id="loginForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" for="email">Email</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="ti ti-mail"></i>
                                </span>
                                <input type="email" name="email" id="email"
                                    class="form-control @error('email') is-invalid @enderror" placeholder="Enter your email"
                                    value="{{ old('email') }}" required autofocus>
                            </div>
                            @error('email')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="password">Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="ti ti-lock"></i>
                                </span>
                                <input type="password" name="password" id="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Enter your password" required>
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="ti ti-eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label" for="remember">Remember me</label>
                            </div>

                        </div>

                        <div class="d-grid">
                            <button class="btn btn-primary" type="submit" id="loginBtn">
                                <i class="ti ti-login me-1"></i> Login
                            </button>
                        </div>
                    </form>

                    <p class="mt-auto mb-0">
                        @if ($websiteSettings && $websiteSettings->footer_copyright_text)
                            {!! $websiteSettings->footer_copyright_text !!}
                        @else
                            © {{ date('Y') }} All rights reserved.
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Password show/hide toggle
        const togglePassword = document.getElementById('togglePassword');
        const password = document.getElementById('password');

        if (togglePassword && password) {
            togglePassword.addEventListener('click', function() {
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);

                const icon = this.querySelector('i');
                if (icon) {
                    icon.classList.toggle('ti-eye');
                    icon.classList.toggle('ti-eye-off');
                }
            });
        }

        // ========== BUTTON DISABLE ON SUBMIT ==========
        (function() {
            const form = document.getElementById('loginForm');
            const button = document.getElementById('loginBtn');

            if (!form || !button) return;

            let isSubmitting = false;

            form.addEventListener('submit', function(e) {
                // If already submitting, prevent the submission
                if (isSubmitting) {
                    e.preventDefault();
                    e.stopPropagation();
                    return false;
                }

                // Mark as submitting
                isSubmitting = true;

                // Disable the button
                button.disabled = true;

                // Save original content
                const originalContent = button.innerHTML;
                button.setAttribute('data-original', originalContent);

                // Change button content to loading state
                button.innerHTML =
                    '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Logging in...';

                // Allow the form to submit
                return true;
            });

            // Re-enable button if there are errors (page loads with errors)
            @if ($errors->any())
                button.disabled = false;
                isSubmitting = false;
                const original = button.getAttribute('data-original');
                if (original) {
                    button.innerHTML = original;
                } else {
                    button.innerHTML = '<i class="ti ti-login me-1"></i> Login';
                }
            @endif
        })();
    </script>
@endpush

@push('styles')
    <style>
        .btn-primary:disabled {
            opacity: 0.65;
            cursor: not-allowed;
        }

        .spinner-border {
            display: inline-block;
            width: 1rem;
            height: 1rem;
            vertical-align: text-bottom;
            border: 0.2em solid currentColor;
            border-right-color: transparent;
            border-radius: 50%;
            animation: spinner-border 0.75s linear infinite;
        }

        @keyframes spinner-border {
            to {
                transform: rotate(360deg);
            }
        }
    </style>
@endpush
