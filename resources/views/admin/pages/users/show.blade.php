@extends('management.layouts.app')

@section('title', 'User Details')

@section('content')
    <!-- ============================================================== -->
    <!-- Start Page Content here -->
    <!-- ============================================================== -->

    <div class="page-content">

        <!-- Start Content-->
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
                <div class="col-lg-4">
                    <!-- Profile Card -->
                    <div class="card">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                @if ($user->avatar)
                                    <img src="{{ asset('storage/avatars/' . $user->avatar) }}" alt="{{ $user->name }}"
                                        class="rounded-circle img-fluid"
                                        style="width: 150px; height: 150px; object-fit: cover; border: 3px solid #fff; box-shadow: 0 0 0 1px #dee2e6;">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=0D6EFD&color=fff&size=150"
                                        alt="{{ $user->name }}" class="rounded-circle img-fluid"
                                        style="width: 150px; height: 150px; object-fit: cover;">
                                @endif
                            </div>

                            <h4 class="mb-1">{{ $user->name }}</h4>
                            <p class="text-muted mb-2">{{ $user->email }}</p>

                            @if ($user->phone)
                                <p class="mb-2">
                                    <i class="ti ti-phone me-1"></i> {{ $user->phone }}
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

                            <div class="d-flex justify-content-center gap-2">
                                @can('edit users')
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary">
                                        <i class="ti ti-edit me-1"></i> Edit User
                                    </a>
                                @endcan

                                @can('activate users')
                                    @if (!$user->is_active)
                                        <button class="btn btn-success" onclick="confirmActivate({{ $user->id }})">
                                            <i class="ti ti-check me-1"></i> Activate
                                        </button>
                                    @endif
                                @endcan

                                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                                    <i class="ti ti-arrow-left me-1"></i> Back
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Account Information Card -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Account Information</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless mb-0">
                                <tr>
                                    <td width="120"><strong>User ID:</strong></td>
                                    <td>#{{ str_pad($user->id, 5, '0', STR_PAD_LEFT) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Joined:</strong></td>
                                    <td>{{ $user->created_at->format('F d, Y') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Last Updated:</strong></td>
                                    <td>{{ $user->updated_at->format('F d, Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email Verified:</strong></td>
                                    <td>
                                        @if ($user->email_verified_at)
                                            <span class="badge bg-success">Verified</span>
                                            <small
                                                class="text-muted d-block">{{ $user->email_verified_at->format('d M Y H:i') }}</small>
                                        @else
                                            <span class="badge bg-warning">Unverified</span>
                                        @endif
                                    </td>
                                </tr>
                                @if ($user->birth_date)
                                    <tr>
                                        <td><strong>Birth Date:</strong></td>
                                        <td>{{ $user->birth_date->format('F d, Y') }}</td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <!-- Roles Card -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ti ti-shield me-1"></i> Assigned Roles
                            </h5>
                        </div>
                        <div class="card-body">
                            @forelse($user->roles as $role)
                                <div class="d-flex justify-content-between align-items-center mb-3 p-3 bg-light rounded">
                                    <div>
                                        <h6 class="mb-1">{{ $role->name }}</h6>
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
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ti ti-lock me-1"></i> Direct Permissions
                            </h5>
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
                                            {{ $permission->name }}
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
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ti ti-key me-1"></i> All Permissions (Inherited + Direct)
                            </h5>
                        </div>
                        <div class="card-body">
                            @php
                                $allPermissions = $user->getAllPermissions()->pluck('name')->toArray();
                                $permissionsByModule = [];

                                foreach ($allPermissions as $permission) {
                                    $parts = explode(' ', $permission);
                                    $module = $parts[1] ?? 'general';
                                    if (!isset($permissionsByModule[$module])) {
                                        $permissionsByModule[$module] = [];
                                    }
                                    $permissionsByModule[$module][] = $permission;
                                }
                            @endphp

                            @if (count($allPermissions) > 0)
                                <div class="mb-3">
                                    <div class="alert alert-info">
                                        <i class="ti ti-info-circle me-1"></i>
                                        <strong>Total Permissions:</strong> {{ count($allPermissions) }}
                                    </div>
                                </div>

                                @foreach ($permissionsByModule as $module => $permissions)
                                    <div class="mb-3">
                                        <h6 class="mb-2 text-uppercase text-muted">{{ ucfirst($module) }}</h6>
                                        <div class="d-flex flex-wrap gap-2">
                                            @foreach ($permissions as $permission)
                                                <span class="badge bg-primary-subtle text-primary p-2">
                                                    <i class="ti ti-check me-1"></i>
                                                    {{ $permission }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center py-4">
                                    <i class="ti ti-key-off" style="font-size: 48px; opacity: 0.5;"></i>
                                    <p class="text-muted mt-2">No permissions available.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Additional Information Card -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ti ti-info-circle me-1"></i> Additional Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Address</label>
                                        <p class="mb-0">
                                            @if ($user->address)
                                                {{ $user->address }}
                                            @else
                                                <span class="text-muted">Not provided</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">City</label>
                                        <p class="mb-0">
                                            @if ($user->city)
                                                {{ $user->city }}
                                            @else
                                                <span class="text-muted">Not provided</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Country</label>
                                        <p class="mb-0">
                                            @if ($user->country)
                                                {{ $user->country }}
                                            @else
                                                <span class="text-muted">Not provided</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Postal Code</label>
                                        <p class="mb-0">
                                            @if ($user->postal_code)
                                                {{ $user->postal_code }}
                                            @else
                                                <span class="text-muted">Not provided</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div> <!-- container -->




        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->

        {{-- Activate Form --}}
        <form id="activateForm" method="POST" style="display: none;">
            @csrf
        </form>
    @endsection

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            $(document).ready(function() {
                // Initialize tooltips
                $('[data-bs-toggle="tooltip"]').tooltip();
            });

            // Confirm Activate
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
                        let form = $('#activateForm');
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
                                        window.location.reload();
                                    });
                                }
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: xhr.responseJSON?.message || 'Failed to activate user.',
                                    confirmButtonColor: '#d33'
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

            .table-borderless td {
                padding: 8px 0;
            }

            .bg-light {
                background-color: #f8f9fa !important;
            }

            /* Hover effects */
            .card {
                transition: box-shadow 0.3s ease;
            }

            .card:hover {
                box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            }

            /* Badge animations */
            .badge {
                transition: all 0.2s ease;
            }

            .badge:hover {
                transform: scale(1.05);
            }
        </style>
    @endpush
