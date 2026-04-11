{{-- resources/views/marketplace/auth/forgot-password.blade.php --}}

@extends('management.layouts.auth')

@section('title', 'Forgot Password - ' . ($websiteSettings->website_name ?? 'Boron Marketplace'))

@section('content')
    @php
        $logoLight = null;
        $logoDark = null;

        if (isset($websiteSettings)) {
            if ($websiteSettings->logo_light && Storage::disk('public')->exists($websiteSettings->logo_light)) {
                $logoLight = asset('storage/' . $websiteSettings->logo_light);
            }
            if ($websiteSettings->logo_dark && Storage::disk('public')->exists($websiteSettings->logo_dark)) {
                $logoDark = asset('storage/' . $websiteSettings->logo_dark);
            }
        }

        $dummyLogoLight = asset('dummy-admin-logo.webp');
        $dummyLogoDark = asset('dummy-admin-logo.webp');

        if (file_exists(public_path('assets/images/logo.png'))) {
            $dummyLogoLight = asset('assets/images/logo.png');
        }
        if (file_exists(public_path('assets/images/logo-dark.png'))) {
            $dummyLogoDark = asset('assets/images/logo-dark.png');
        }

        $footerCopyright = $websiteSettings->footer_copyright_text ?? '© ' . date('Y') . ' All rights reserved.';
    @endphp

    <div class="auth-bg d-flex min-vh-100 justify-content-center align-items-center">
        <div class="row g-0 justify-content-center w-100 m-xxl-5 px-xxl-4 m-3">
            <div class="col-xl-4 col-lg-5 col-md-6">
                <div class="card overflow-hidden text-center h-100 p-xxl-4 p-3 mb-0">
                    <a href="{{ route('vendor.login') }}" class="auth-brand mb-3">
                        @if ($logoLight || $logoDark)
                            @if ($logoLight)
                                <img src="{{ $logoLight }}" alt="Logo" height="35" class="logo-light">
                            @endif
                            @if ($logoDark)
                                <img src="{{ $logoDark }}" alt="Logo" height="35" class="logo-dark">
                            @endif
                        @else
                            <img src="{{ $dummyLogoLight }}" alt="Logo" height="40" class="logo-light">
                            <img src="{{ $dummyLogoDark }}" alt="Logo" height="40" class="logo-dark">
                        @endif
                    </a>

                    <h4 class="fw-semibold mb-2">Forgot Password?</h4>

                    <p class="text-muted mb-4">Enter your email address and we'll send you a link to reset your password.
                    </p>

                    @if (session('status'))
                        <div class="alert alert-success mb-3">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('vendor.password.email') }}" class="text-start mb-3"
                        id="forgotForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" for="email">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="ti ti-mail"></i>
                                </span>
                                <input type="email" name="email" id="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    placeholder="Enter your registered email" value="{{ old('email') }}" required
                                    autofocus>
                            </div>
                            @error('email')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button class="btn btn-primary" type="submit" id="submitBtn">
                                <i class="ti ti-send me-1"></i> Send Reset Link
                            </button>
                            <a href="{{ route('vendor.login') }}" class="btn btn-link">
                                <i class="ti ti-arrow-left me-1"></i> Back to Login
                            </a>
                        </div>
                    </form>

                    <p class="mt-auto mb-0 mt-3">
                        {!! $footerCopyright !!}
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const form = document.getElementById('forgotForm');
        const button = document.getElementById('submitBtn');

        if (form && button) {
            let isSubmitting = false;

            form.addEventListener('submit', function(e) {
                if (isSubmitting) {
                    e.preventDefault();
                    return false;
                }

                isSubmitting = true;
                button.disabled = true;
                const originalContent = button.innerHTML;
                button.setAttribute('data-original', originalContent);
                button.innerHTML =
                    '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Sending...';

                return true;
            });

            @if ($errors->any())
                button.disabled = false;
                isSubmitting = false;
                const original = button.getAttribute('data-original');
                if (original) {
                    button.innerHTML = original;
                }
            @endif
        }
    </script>
@endpush
