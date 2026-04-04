@extends('management.layouts.auth')

@section('title', 'Vendor Login')

@section('content')
    <div class="auth-bg d-flex min-vh-100 justify-content-center align-items-center">
        <div class="row g-0 justify-content-center w-100 m-xxl-5 px-xxl-4 m-3">
            <div class="col-xl-4 col-lg-5 col-md-6">
                <div class="card overflow-hidden text-center h-100 p-xxl-4 p-3 mb-0">
                    <a href="{{ route('vendor.login') }}" class="auth-brand mb-3">
                        <img src="{{ asset('assets/images/logo-dark.png') }}" alt="dark logo" height="30" class="logo-dark">
                        <img src="{{ asset('assets/images/logo.png') }}" alt="logo light" height="30" class="logo-light">
                    </a>

                    <h4 class="fw-semibold mb-2">Welcome Back!</h4>

                    <p class="text-muted mb-4">Login to your seller account</p>

                    <form method="POST" action="{{ route('vendor.login.submit') }}" class="text-start mb-3">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" for="email">Email Address</label>
                            <input type="email" name="email" id="email"
                                class="form-control @error('email') is-invalid @enderror" placeholder="Enter your email"
                                value="{{ old('email') }}" required autofocus>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="password">Password</label>
                            <input type="password" name="password" id="password"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="Enter your password" required>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label" for="remember">Remember me</label>
                            </div>

                            <a href="{{ route('vendor.password.request') }}"
                                class="text-muted border-bottom border-dashed">Forgot Password?</a>
                        </div>

                        <div class="d-grid">
                            <button class="btn btn-primary" type="submit">Login</button>
                        </div>
                    </form>

                    {{-- Become a Seller Section --}}
                    <div class="mt-4 pt-3 border-top">
                        <p class="text-muted mb-2">Want to start selling on {{ config('app.name') }}?</p>
                        <a href="{{ route('vendor.register') }}" class="btn btn-success w-100">
                            🏪 Become a Seller
                        </a>
                    </div>
                    <br>
                    <p class="mt-auto mb-0 mt-3">
                        <script>
                            document.write(new Date().getFullYear())
                        </script> © Boron - By <span
                            class="fw-bold text-decoration-underline text-uppercase text-reset fs-12">Coderthemes</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        console.log('Vendor Login page loaded');
    </script>
@endpush
