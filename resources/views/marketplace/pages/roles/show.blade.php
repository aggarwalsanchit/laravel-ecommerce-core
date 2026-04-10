{{-- resources/views/vendor/roles/show.blade.php --}}
@extends('management.layouts.app')

@section('title', 'Role Details')

@section('content')
    <div class="page-content">
        <div class="page-container">
            <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                <div class="flex-grow-1">
                    <h4 class="fs-18 text-uppercase fw-bold mb-0">Role Details</h4>
                </div>
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('vendor.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('vendor.roles.index') }}">Roles</a></li>
                        <li class="breadcrumb-item active">Role Details</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Role Information</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <td width="150"><strong>ID:</strong></td>
                                <td>#{{ $role->id }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Name:</strong></td>
                                    <td>
                                        <span class="badge bg-primary p-2">
                                            <i class="ti ti-shield me-1"></i> {{ $role->name }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Guard:</strong></td>
                                    <td>
                                        <span class="badge bg-info-subtle text-info p-2">
                                            {{ ucfirst($role->guard_name) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Total Permissions:</strong></td>
                                    <td>
                                        <span class="badge bg-success-subtle text-success p-2">
                                            {{ $role->permissions->count() }} permissions
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Total Users:</strong></td>
                                    <td>
                                        <span class="badge bg-warning-subtle text-warning p-2">
                                            {{ $role->users()->count() }} users
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Created:</strong></td>
                                    <td>{{ $role->created_at->format('F d, Y H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Last Updated:</strong></td>
                                    <td>{{ $role->updated_at->diffForHumans() }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ti ti-key me-1"></i> Permissions
                            </h5>
                        </div>
                        <div class="card-body">
                            @if ($role->permissions->count() > 0)
                                @php
                                    $permissionsByModule = [];
                                    foreach ($role->permissions as $permission) {
                                        $parts = explode(' ', $permission->name);
                                        $module = $parts[1] ?? 'general';
                                        if (!isset($permissionsByModule[$module])) {
                                            $permissionsByModule[$module] = [];
                                        }
                                        $permissionsByModule[$module][] = $permission;
                                    }
                                @endphp

                                @foreach ($permissionsByModule as $module => $permissions)
                                    <div class="mb-3">
                                        <h6 class="text-uppercase text-muted">{{ ucfirst($module) }}</h6>
                                        <div class="d-flex flex-wrap gap-2">
                                            @foreach ($permissions as $permission)
                                                <span class="badge bg-primary-subtle text-primary p-2">
                                                    <i class="ti ti-check me-1"></i>
                                                    {{ $permission->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center py-4">
                                    <i class="ti ti-key-off" style="font-size: 48px; opacity: 0.5;"></i>
                                    <p class="text-muted mt-2">No permissions assigned to this role.</p>
                                    @can('assign permissions')
                                        <a href="{{ route('vendor.roles.assign-permissions', $role->id) }}"
                                            class="btn btn-primary mt-2">
                                            <i class="ti ti-key me-1"></i> Assign Permissions
                                        </a>
                                    @endcan
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ti ti-users me-1"></i> Users with this Role
                            </h5>
                        </div>
                        <div class="card-body">
                            @if ($users->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Status</th>
                                            <th>Joined</th>
                                            <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($users as $user)
                                                <tr>
                                                    <td>#{{ $user->id }}</td>
                                                    <td>
                                                        <div class="d-flex align-items-center gap-2">
                                                            @if ($user->avatar)
                                                                <img src="{{ Storage::url($user->avatar) }}"
                                                                    class="rounded-circle" width="30" height="30"
                                                                    style="object-fit: cover;">
                                                            @else
                                                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white"
                                                                    style="width: 30px; height: 30px; font-size: 12px;">
                                                                    {{ substr($user->name, 0, 1) }}
                                                                </div>
                                                            @endif
                                                            {{ $user->name }}
                                                        </div>
                                                    </td>
                                                    <td>{{ $user->email }}</td>
                                                    <td>
                                                        @if ($user->is_active)
                                                            <span class="badge bg-success">Active</span>
                                                        @else
                                                            <span class="badge bg-danger">Inactive</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $user->created_at->format('d M Y') }}</td>
                                                    <td>
                                                        <a href="{{ route('vendor.staff.show', $user->id) }}"
                                                            class="btn btn-sm btn-soft-primary">
                                                            <i class="ti ti-eye"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="mt-3">
                                    {{ $users->links('pagination::bootstrap-5') }}
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="ti ti-users-off" style="font-size: 48px; opacity: 0.5;"></i>
                                    <p class="text-muted mt-2">No users have this role assigned.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-footer text-end">
                            <a href="{{ route('vendor.roles.index') }}" class="btn btn-secondary">
                                <i class="ti ti-arrow-left me-1"></i> Back
                            </a>
                            @can('edit roles')
                                @if ($role->name !== 'Super Admin')
                                    <a href="{{ route('vendor.roles.edit', $role->id) }}" class="btn btn-primary">
                                        <i class="ti ti-edit me-1"></i> Edit Role
                                    </a>
                                @endif
                            @endcan
                            @can('assign permissions')
                                @if ($role->name !== 'Super Admin')
                                    <a href="{{ route('vendor.roles.assign-permissions', $role->id) }}" class="btn btn-info">
                                        <i class="ti ti-key me-1"></i> Assign Permissions
                                    </a>
                                @endif
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
