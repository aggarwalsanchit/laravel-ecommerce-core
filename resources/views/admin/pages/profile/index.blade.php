{{-- resources/views/admin/profile/index.blade.php --}}

@extends('management.layouts.app')

@section('title', 'My Profile')

@section('content')
    <div class="page-content">
        <div class="page-container">
            <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                <div class="flex-grow-1">
                    <h4 class="fs-18 text-uppercase fw-bold mb-0">My Profile</h4>
                    <p class="text-muted mb-0">View and manage your profile information</p>
                </div>
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Profile</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-4">
                    {{-- Profile Card --}}
                    <div class="card">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                @if ($admin->avatar)
                                    <img src="{{ asset('storage/' . $admin->avatar) }}" alt="Avatar"
                                        class="rounded-circle" width="120" height="120" style="object-fit: cover;">
                                @else
                                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center bg-primary text-white"
                                        style="width: 120px; height: 120px; font-size: 48px; font-weight: 500;">
                                        {{ strtoupper(substr($admin->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <h4 class="mb-1">{{ $admin->name }}</h4>
                            <p class="text-muted mb-3">{{ $admin->email }}</p>

                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('admin.profile.edit') }}" class="btn btn-primary">
                                    <i class="ti ti-edit"></i> Edit Profile
                                </a>
                                <a href="{{ route('admin.profile.change-password') }}" class="btn btn-outline-secondary">
                                    <i class="ti ti-lock"></i> Change Password
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Account Info Card --}}
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Account Information</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Joined:</th>
                                    <td>{{ $admin->created_at->format('d M Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        @if ($admin->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Email Verified:</th>
                                    <td>
                                        @if ($admin->email_verified_at)
                                            <span class="badge bg-success">Verified</span>
                                        @else
                                            <span class="badge bg-warning">Not Verified</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-xl-8">
                    {{-- Personal Information Card --}}
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Personal Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="text-muted small mb-1">Full Name</label>
                                        <p class="fw-semibold mb-0">{{ $admin->name }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="text-muted small mb-1">Email Address</label>
                                        <p class="fw-semibold mb-0">{{ $admin->email }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="text-muted small mb-1">Phone Number</label>
                                        <p class="fw-semibold mb-0">
                                            @if ($admin->phone_code && $admin->phone)
                                                +{{ $admin->phone_code }} {{ $admin->phone }}
                                            @elseif($admin->phone)
                                                {{ $admin->phone }}
                                            @else
                                                Not provided
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="text-muted small mb-1">Birth Date</label>
                                        <p class="fw-semibold mb-0">
                                            {{ $admin->birth_date ? $admin->birth_date->format('d M Y') : 'Not provided' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Address Information Card --}}
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Address Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="text-muted small mb-1">Address</label>
                                        <p class="fw-semibold mb-0">{{ $admin->address ?? 'Not provided' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="text-muted small mb-1">Country</label>
                                        <p class="fw-semibold mb-0">{{ $admin->country->name ?? 'Not provided' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="text-muted small mb-1">State</label>
                                        <p class="fw-semibold mb-0">{{ $admin->state->name ?? 'Not provided' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="text-muted small mb-1">City</label>
                                        <p class="fw-semibold mb-0">{{ $admin->city ?? 'Not provided' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="text-muted small mb-1">Postal Code</label>
                                        <p class="fw-semibold mb-0">{{ $admin->postal_code ?? 'Not provided' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="text-muted small mb-1">Full Address</label>
                                        <p class="fw-semibold mb-0">{{ $admin->full_address ?? 'Not provided' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Permissions Card --}}
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Permissions</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @php
                                    $permissions = $admin->getAllPermissions();
                                @endphp
                                @forelse($permissions as $permission)
                                    <div class="col-md-4 mb-2">
                                        <span class="badge bg-info">
                                            <i class="ti ti-check"></i> {{ $permission->name }}
                                        </span>
                                    </div>
                                @empty
                                    <div class="col-12">
                                        <p class="text-muted mb-0">No permissions assigned</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
