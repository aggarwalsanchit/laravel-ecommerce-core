{{-- resources/views/admin/profile/change-password.blade.php --}}

@extends('management.layouts.app')

@section('title', 'Change Password')

@section('content')
<div class="page-content">
    <div class="page-container">
        <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
            <div class="flex-grow-1">
                <h4 class="fs-18 text-uppercase fw-bold mb-0">Change Password</h4>
                <p class="text-muted mb-0">Update your account password</p>
            </div>
            <div class="text-end">
                <ol class="breadcrumb m-0 py-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.profile.index') }}">Profile</a></li>
                    <li class="breadcrumb-item active">Change Password</li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Change Password</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.profile.update-password') }}">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Current Password <span class="text-danger">*</span></label>
                                <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" required>
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">New Password <span class="text-danger">*</span></label>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Minimum 8 characters</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Confirm New Password <span class="text-danger">*</span></label>
                                <input type="password" name="password_confirmation" class="form-control" required>
                            </div>

                            <div class="d-flex justify-content-end gap-2 mt-3">
                                <a href="{{ route('admin.profile.index') }}" class="btn btn-secondary">
                                    <i class="ti ti-x"></i> Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="ti ti-lock"></i> Change Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="alert alert-info mt-3">
                    <i class="ti ti-info-circle"></i>
                    <strong>Password Guidelines:</strong>
                    <ul class="mb-0 mt-2">
                        <li>Use at least 8 characters</li>
                        <li>Use a mix of letters, numbers, and symbols</li>
                        <li>Avoid using common words or personal information</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection