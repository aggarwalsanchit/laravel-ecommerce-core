{{-- resources/views/admin/pages/users/show.blade.php --}}

@extends('management.layouts.app')

@section('title', 'User Details - ' . $user->name)

@section('content')
    <div class="page-content">
        <div class="page-container">

            <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                <div class="flex-grow-1">
                    <h4 class="fs-18 text-uppercase fw-bold mb-0">User Details</h4>
                </div>
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Users</a></li>
                        <li class="breadcrumb-item active">User Details</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <!-- Left Column - Profile & Account Info -->
                <div class="col-lg-4">
                    <!-- Profile Card -->
                    <div class="card mb-4">
                        <div class="card-body text-center">
                            @php
                                $avatarPath = $user->avatar ? 'avatars/' . $user->avatar : null;
                                $hasAvatar = $avatarPath && Storage::disk('public')->exists($avatarPath);
                            @endphp

                            @if ($hasAvatar)
                                <img src="{{ Storage::url($avatarPath) }}" alt="{{ $user->name }}"
                                    class="rounded-circle img-fluid mb-3"
                                    style="width: 150px; height: 150px; object-fit: cover; border: 3px solid #fff; box-shadow: 0 0 0 1px #dee2e6;">
                            @else
                                <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center text-white mx-auto mb-3"
                                    style="width: 150px; height: 150px; font-size: 60px; font-weight: 500; box-shadow: 0 0 0 1px #dee2e6;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            @endif

                            <h4 class="mb-1">{{ $user->name }}</h4>
                            <p class="text-muted mb-2">{{ $user->email }}</p>

                            @if ($user->phone)
                                <p class="mb-2">
                                    <i class="ti ti-phone me-1"></i>
                                    @if ($user->phone_code)
                                        <span class="text-muted">+{{ $user->phone_code }}</span>
                                    @endif
                                    {{ $user->phone }}
                                </p>
                            @endif

                            <div class="mb-3">
                                @if ($user->is_active)
                                    <span class="badge bg-success-subtle text-success fs-14 p-2">
                                        <i class="ti ti-circle-check me-1"></i> Active
                                    </span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger fs-14 p-2">
                                        <i class="ti ti-circle-x me-1"></i> Inactive
                                    </span>
                                @endif
                            </div>

                            <div class="d-flex justify-content-center gap-2 flex-wrap">
                                @can('edit_users')
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary btn-sm">
                                        <i class="ti ti-edit me-1"></i> Edit User
                                    </a>
                                @endcan

                                @if (!$user->is_active && auth('admin')->user()->can('activate_users'))
                                    <button class="btn btn-success btn-sm" onclick="confirmActivate({{ $user->id }})">
                                        <i class="ti ti-check me-1"></i> Activate
                                    </button>
                                @endif

                                @if ($user->is_active && auth('admin')->user()->can('deactivate_users') && $user->id !== auth('admin')->id())
                                    <button class="btn btn-warning btn-sm"
                                        onclick="confirmDeactivate({{ $user->id }})">
                                        <i class="ti ti-user-x me-1"></i> Deactivate
                                    </button>
                                @endif

                                @if (auth('admin')->user()->can('delete_users') && $user->id !== auth('admin')->id() && !$user->hasRole('Super Admin'))
                                    <button class="btn btn-danger btn-sm" onclick="confirmDelete({{ $user->id }})">
                                        <i class="ti ti-trash me-1"></i> Delete
                                    </button>
                                @endif

                                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm">
                                    <i class="ti ti-arrow-left me-1"></i> Back
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Account Information Card -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0"><i class="ti ti-info-circle me-1"></i> Account Information</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless mb-0">
                                <tr>
                                    <td width="130"><strong>User ID:</strong></td>
                                    <td>#{{ str_pad($user->id, 5, '0', STR_PAD_LEFT) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Joined:</strong></td>
                                    <td>{{ $user->created_at->format('F d, Y') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Last Updated:</strong></td>
                                    <td>{{ $user->updated_at->format('F d, Y h:i A') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email Verified:</strong></td>
                                    <td>
                                        @if ($user->email_verified_at)
                                            <span class="badge bg-success">Verified</span>
                                            <small
                                                class="text-muted d-block">{{ $user->email_verified_at->format('d M Y h:i A') }}</small>
                                        @else
                                            <span class="badge bg-warning">Unverified</span>
                                        @endif
                                    </td>
                                </tr>
                                @if ($user->birth_date)
                                    <tr>
                                        <td><strong>Birth Date:</strong></td>
                                        <td>{{ \Carbon\Carbon::parse($user->birth_date)->format('F d, Y') }}</td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                    </div>

                    <!-- Address Information Card -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0"><i class="ti ti-map-pin me-1"></i> Address Information</h5>
                        </div>
                        <div class="card-body">
                            @if ($user->address || $user->city || $user->state || $user->country)
                                <div class="mb-2">
                                    <strong>Address:</strong>
                                    <p class="mb-1">{{ $user->address ?? 'Not provided' }}</p>
                                </div>
                                <div class="mb-2">
                                    <strong>City:</strong>
                                    <p class="mb-1">{{ $user->city ?? 'Not provided' }}</p>
                                </div>
                                <div class="mb-2">
                                    <strong>State:</strong>
                                    <p class="mb-1">{{ $user->state->name ?? ($user->state ?? 'Not provided') }}</p>
                                </div>
                                <div class="mb-2">
                                    <strong>Country:</strong>
                                    <p class="mb-1">{{ $user->country->name ?? ($user->country ?? 'Not provided') }}</p>
                                </div>
                                <div class="mb-2">
                                    <strong>Postal Code:</strong>
                                    <p class="mb-1">{{ $user->postal_code ?? 'Not provided' }}</p>
                                </div>
                            @else
                                <p class="text-muted text-center mb-0">No address information provided</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Right Column - Roles & Permissions -->
                <div class="col-lg-8">
                    <!-- Roles Card -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0"><i class="ti ti-shield me-1"></i> Assigned Roles</h5>
                        </div>
                        <div class="card-body">
                            @forelse($user->roles as $role)
                                <div class="d-flex justify-content-between align-items-center mb-3 p-3 bg-light rounded">
                                    <div>
                                        <h6 class="mb-1">{{ ucfirst($role->name) }}</h6>
                                        <small class="text-muted">
                                            <i class="ti ti-lock me-1"></i>
                                            {{ $role->permissions->count() }} permissions
                                        </small>
                                    </div>
                                    <div>
                                        <span class="badge bg-primary">Assigned</span>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-4">
                                    <i class="ti ti-shield-off" style="font-size: 48px; opacity: 0.5;"></i>
                                    <p class="text-muted mt-2">No roles assigned to this user.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Direct Permissions Card -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0"><i class="ti ti-lock me-1"></i> Direct Permissions</h5>
                        </div>
                        <div class="card-body">
                            @php
                                $directPermissions = $user->getDirectPermissions();
                            @endphp

                            @if ($directPermissions->count() > 0)
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach ($directPermissions as $permission)
                                        <span class="badge bg-info-subtle text-info p-2">
                                            <i class="ti ti-circle-check me-1"></i>
                                            {{ str_replace('_', ' ', $permission->name) }}
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="ti ti-lock-off" style="font-size: 48px; opacity: 0.5;"></i>
                                    <p class="text-muted mt-2">No direct permissions assigned.</p>
                                    <small class="text-muted">User inherits permissions from roles.</small>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- All Permissions Card -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0"><i class="ti ti-key me-1"></i> All Permissions</h5>
                        </div>
                        <div class="card-body">
                            @php
                                $allPermissions = $user->getAllPermissions()->pluck('name')->toArray();
                                sort($allPermissions);
                            @endphp

                            @if (count($allPermissions) > 0)
                                <div class="mb-3">
                                    <div class="alert alert-info">
                                        <i class="ti ti-info-circle me-1"></i>
                                        <strong>Total Permissions:</strong> {{ count($allPermissions) }}
                                    </div>
                                </div>

                                <div class="d-flex flex-wrap gap-2">
                                    @foreach ($allPermissions as $permission)
                                        <span class="badge bg-primary-subtle text-primary p-2">
                                            <i class="ti ti-check me-1"></i>
                                            {{ str_replace('_', ' ', $permission) }}
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="ti ti-key-off" style="font-size: 48px; opacity: 0.5;"></i>
                                    <p class="text-muted mt-2">No permissions available.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Activity Log Card (Optional) -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0"><i class="ti ti-history me-1"></i> Recent Activity</h5>
                        </div>
                        <div class="card-body">
                            <div class="text-center py-4">
                                <i class="ti ti-activity" style="font-size: 48px; opacity: 0.5;"></i>
                                <p class="text-muted mt-2">Activity logs coming soon.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Delete Form --}}
    <form id="deleteForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    {{-- Activate/Deactivate Form --}}
    <form id="statusForm" method="POST" style="display: none;">
        @csrf
    </form>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Delete User
        function confirmDelete(userId) {
            Swal.fire({
                title: 'Delete User?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    let form = $('#deleteForm');
                    form.attr('action', '{{ url('admin/users') }}/' + userId);
                    form.submit();
                }
            });
        }

        // Activate User
        function confirmActivate(userId) {
            Swal.fire({
                title: 'Activate User?',
                text: "Are you sure you want to activate this user? They will be able to access the system.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, activate!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    let form = $('#statusForm');
                    form.attr('action', '{{ url('admin/users') }}/' + userId + '/activate');

                    $.ajax({
                        url: form.attr('action'),
                        type: 'POST',
                        data: form.serialize(),
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Activated!',
                                    text: response.message,
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(function() {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: response.message
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Something went wrong.'
                            });
                        }
                    });
                }
            });
        }

        // Deactivate User
        function confirmDeactivate(userId) {
            Swal.fire({
                title: 'Deactivate User?',
                text: "Are you sure you want to deactivate this user? They will not be able to access the system.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ffc107',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, deactivate!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    let form = $('#statusForm');
                    form.attr('action', '{{ url('admin/users') }}/' + userId + '/deactivate');

                    $.ajax({
                        url: form.attr('action'),
                        type: 'POST',
                        data: form.serialize(),
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deactivated!',
                                    text: response.message,
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(function() {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: response.message
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Something went wrong.'
                            });
                        }
                    });
                }
            });
        }
    </script>
@endpush

@push('styles')
    <style>
        .badge.bg-primary-subtle {
            background-color: rgba(13, 110, 253, 0.1);
            border: 1px solid rgba(13, 110, 253, 0.2);
        }

        .badge.bg-info-subtle {
            background-color: rgba(13, 202, 240, 0.1);
            border: 1px solid rgba(13, 202, 240, 0.2);
        }

        .badge.bg-success-subtle {
            background-color: rgba(25, 135, 84, 0.1);
            border: 1px solid rgba(25, 135, 84, 0.2);
        }

        .badge.bg-danger-subtle {
            background-color: rgba(220, 53, 69, 0.1);
            border: 1px solid rgba(220, 53, 69, 0.2);
        }

        .badge.bg-warning-subtle {
            background-color: rgba(255, 193, 7, 0.1);
            border: 1px solid rgba(255, 193, 7, 0.2);
        }

        .alert-info {
            background-color: rgba(13, 110, 253, 0.05);
            border-color: rgba(13, 110, 253, 0.1);
        }

        .bg-light {
            background-color: #f8f9fa !important;
        }

        .card {
            transition: box-shadow 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .badge {
            transition: all 0.2s ease;
        }

        .badge:hover {
            transform: scale(1.05);
        }
    </style>
@endpush
