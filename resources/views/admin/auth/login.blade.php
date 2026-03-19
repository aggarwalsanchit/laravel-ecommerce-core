@extends('admin.layouts.auth')

@section('title', 'Login')

@section('content')
<div class="auth-bg d-flex min-vh-100 justify-content-center align-items-center">
    <div class="row g-0 justify-content-center w-100 m-xxl-5 px-xxl-4 m-3">
        <div class="col-xl-4 col-lg-5 col-md-6">
            <div class="card overflow-hidden text-center h-100 p-xxl-4 p-3 mb-0">
                <a href="index.html" class="auth-brand mb-3">
                    <img src="assets/images/logo-dark.png" alt="dark logo" height="30" class="logo-dark">
                    <img src="assets/images/logo.png" alt="logo light" height="30" class="logo-light">
                </a>

                <h4 class="fw-semibold mb-2">Login your account</h4>

                <p class="text-muted mb-4">Enter your email address and password to access admin panel.</p>

                <form method="POST" action="{{ route('admin.login.submit') }}" class="text-start mb-3">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label" for="example-email">Email</label>
                        <input type="email" name="email" id="example-email" name="example-email" class="form-control @error('email') is-invalid @enderror" placeholder="Enter your email" value="{{ old('email') }}" required autofocus>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="example-password">Password</label>
                        <input type="password" name="password" id="example-password" class="form-control @error('password') is-invalid @enderror" placeholder="Enter your password" required>
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="checkbox-signin" name="remember">
                            <label class="form-check-label" for="checkbox-signin">Remember me</label>
                        </div>

                      {{--  <a href="auth-recoverpw.html" class="text-muted border-bottom border-dashed">Forget Password</a> --}}
                    </div>

                    <div class="d-grid">
                        <button class="btn btn-primary" type="submit">Login</button>
                    </div>
                </form>

               {{-- <p class="text-danger fs-14 mb-4">Don't have an account? <a href="auth-register.html" class="fw-semibold text-dark ms-1">Sign Up !</a></p>

                <p class="fs-13 fw-semibold">Or Login with Social</p>

                <div class="d-flex justify-content-center gap-2 mb-3">
                    <a href="#!" class="btn btn-soft-danger avatar-lg"><i class="ti ti-brand-google-filled fs-24"></i></a>
                    <a href="#!" class="btn btn-soft-success avatar-lg"><i class="ti ti-brand-apple fs-24"></i></a>
                    <a href="#!" class="btn btn-soft-primary avatar-lg"><i class="ti ti-brand-facebook fs-24"></i></a>
                    <a href="#!" class="btn btn-soft-info avatar-lg"><i class="ti ti-brand-linkedin fs-24"></i></a>
                </div> --}}

                <p class="mt-auto mb-0">
                    <script>document.write(new Date().getFullYear())</script> © Boron - By <span class="fw-bold text-decoration-underline text-uppercase text-reset fs-12">Coderthemes</span>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    console.log('Login page loaded');
</script>
@endpush