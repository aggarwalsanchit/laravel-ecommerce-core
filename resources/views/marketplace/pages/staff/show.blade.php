{{-- resources/views/marketplace/pages/staff/show.blade.php --}}

@extends('management.layouts.app')

@section('title', 'Staff Details - ' . $staff->name)

@section('content')
    <div class="page-content">
        <div class="page-container">

            <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                <div class="flex-grow-1">
                    <h4 class="fs-18 text-uppercase fw-bold mb-0">Staff Details</h4>
                </div>
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('vendor.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('vendor.staff.index') }}">Staff</a></li>
                        <li class="breadcrumb-item active">Staff Details</li>
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
                                $avatarPath = $staff->avatar ? $staff->avatar : null;
                                $hasAvatar = $avatarPath && Storage::disk('public')->exists($avatarPath);
                            @endphp

                            @if ($hasAvatar)
                                <img src="{{ Storage::url($avatarPath) }}" alt="{{ $staff->name }}"
                                    class="rounded-circle img-fluid mb-3"
                                    style="width: 150px; height: 150px; object-fit: cover; border: 3px solid #fff; box-shadow: 0 0 0 1px #dee2e6;">
                            @else
                                <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center text-white mx-auto mb-3"
                                    style="width: 150px; height: 150px; font-size: 60px; font-weight: 500; box-shadow: 0 0 0 1px #dee2e6;">
                                    {{ strtoupper(substr($staff->name, 0, 1)) }}
                                </div>
                            @endif

                            <h4 class="mb-1">{{ $staff->name }}</h4>
                            <p class="text-muted mb-2">{{ $staff->email }}</p>

                            @if ($staff->phone)
                                <p class="mb-2">
                                    <i class="ti ti-phone me-1"></i>
                                    @if ($staff->phone_code)
                                        <span class="text-muted">+{{ $staff->phone_code }}</span>
                                    @endif
                                    {{ $staff->phone }}
                                </p>
                            @endif

                            <div class="mb-3">
                                @if ($staff->is_active)
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
                                @can('edit_staff', 'vendor')
                                    <a href="{{ route('vendor.staff.edit', $staff->id) }}" class="btn btn-primary btn-sm">
                                        <i class="ti ti-edit me-1"></i> Edit Staff
                                    </a>
                                @endcan

                                @if ($staff->is_active && auth()->guard('vendor')->user()->can('edit_staff'))
                                    <button class="btn btn-warning btn-sm"
                                        onclick="confirmDeactivate({{ $staff->id }})">
                                        <i class="ti ti-user-x me-1"></i> Deactivate
                                    </button>
                                @elseif (!$staff->is_active && auth()->guard('vendor')->user()->can('edit_staff'))
                                    <button class="btn btn-success btn-sm" onclick="confirmActivate({{ $staff->id }})">
                                        <i class="ti ti-check me-1"></i> Activate
                                    </button>
                                @endif

                                @can('delete_staff', 'vendor')
                                    <button class="btn btn-danger btn-sm" onclick="confirmDelete({{ $staff->id }})">
                                        <i class="ti ti-trash me-1"></i> Delete
                                    </button>
                                @endcan

                                <a href="{{ route('vendor.staff.index') }}" class="btn btn-secondary btn-sm">
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
                                    <td width="130"><strong>Staff ID:</strong></td>
                                    <td>#{{ str_pad($staff->id, 5, '0', STR_PAD_LEFT) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Joined:</strong></td>
                                    <td>{{ $staff->created_at->format('F d, Y') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Last Updated:</strong></td>
                                    <td>{{ $staff->updated_at->format('F d, Y h:i A') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Last Login:</strong></td>
                                    <td>{{ $staff->last_login_at ? \Carbon\Carbon::parse($staff->last_login_at)->format('F d, Y h:i A') : 'Never' }}
                                    </td>
                                </tr>
                                @if ($staff->birth_date)
                                    <tr>
                                        <td><strong>Birth Date:</strong></td>
                                        <td>{{ \Carbon\Carbon::parse($staff->birth_date)->format('F d, Y') }}</td>
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
                            @if ($staff->address || $staff->city || $staff->state_id || $staff->country_id)
                                <div class="mb-2">
                                    <strong>Address:</strong>
                                    <p class="mb-1">{{ $staff->address ?? 'Not provided' }}</p>
                                </div>
                                <div class="mb-2">
                                    <strong>City:</strong>
                                    <p class="mb-1">{{ $staff->city ?? 'Not provided' }}</p>
                                </div>
                                <div class="mb-2">
                                    <strong>State:</strong>
                                    <p class="mb-1">{{ $staff->state->name ?? ($staff->state ?? 'Not provided') }}</p>
                                </div>
                                <div class="mb-2">
                                    <strong>Country:</strong>
                                    <p class="mb-1">{{ $staff->country->name ?? ($staff->country ?? 'Not provided') }}
                                    </p>
                                </div>
                                <div class="mb-2">
                                    <strong>Postal Code:</strong>
                                    <p class="mb-1">{{ $staff->postal_code ?? 'Not provided' }}</p>
                                </div>
                            @else
                                <p class="text-muted text-center mb-0">No address information provided</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Right Column - Permissions & Shop Info -->
                <div class="col-lg-8">
                    <!-- Role Permissions Card -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0"><i class="ti ti-shield me-1"></i> Role Permissions</h5>
                        </div>
                        <div class="card-body">
                            @forelse($staff->roles as $role)
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

                    <!-- Custom Permissions Card -->
                    @if ($staff->custom_permissions && count($staff->custom_permissions) > 0)
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0"><i class="ti ti-lock me-1"></i> Custom Permissions</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach ($staff->custom_permissions as $permission)
                                        <span class="badge bg-info-subtle text-info p-2">
                                            <i class="ti ti-circle-check me-1"></i>
                                            {{ str_replace('_', ' ', $permission) }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Shop Information Card -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0"><i class="ti ti-building-store me-1"></i> Shop Information</h5>
                        </div>
                        <div class="card-body">
                            @if ($staff->shop)
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    @if ($staff->shop->shop_logo && Storage::disk('public')->exists($staff->shop->shop_logo))
                                        <img src="{{ Storage::url($staff->shop->shop_logo) }}"
                                            alt="{{ $staff->shop->shop_name }}"
                                            style="width: 60px; height: 60px; object-fit: cover; border-radius: 10px;">
                                    @else
                                        <div class="bg-primary rounded d-flex align-items-center justify-content-center text-white"
                                            style="width: 60px; height: 60px; font-size: 24px;">
                                            {{ strtoupper(substr($staff->shop->shop_name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <h5 class="mb-1">{{ $staff->shop->shop_name }}</h5>
                                        <p class="text-muted mb-0 small">{{ $staff->shop->shop_email }}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>Shop Phone:</strong>
                                        <p>{{ $staff->shop->shop_phone ?? 'Not provided' }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Vendor Type:</strong>
                                        <p>{{ ucfirst(str_replace('_', ' ', $staff->shop->vendor_type ?? 'Not provided')) }}
                                        </p>
                                    </div>
                                </div>
                            @else
                                <p class="text-muted text-center mb-0">No shop information available</p>
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
                                $allPermissions = $staff->getAllPermissions()->pluck('name')->toArray();
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

                    <!-- Activity Log Card -->
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
        // Delete Staff
        function confirmDelete(staffId) {
            Swal.fire({
                title: 'Delete Staff?',
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
                    form.attr('action', '{{ url('vendor/staff') }}/' + staffId);
                    form.submit();
                }
            });
        }

        // Activate Staff
        function confirmActivate(staffId) {
            Swal.fire({
                title: 'Activate Staff?',
                text: "Are you sure you want to activate this staff member? They will be able to access the system.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, activate!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    let form = $('#statusForm');
                    form.attr('action', '{{ url('vendor/staff') }}/' + staffId + '/activate');

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

        // Deactivate Staff
        function confirmDeactivate(staffId) {
            Swal.fire({
                title: 'Deactivate Staff?',
                text: "Are you sure you want to deactivate this staff member? They will not be able to access the system.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ffc107',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, deactivate!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    let form = $('#statusForm');
                    form.attr('action', '{{ url('vendor/staff') }}/' + staffId + '/deactivate');

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
