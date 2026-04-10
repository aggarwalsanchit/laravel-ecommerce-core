{{-- resources/views/admin/permissions/show.blade.php --}}
@extends('management.layouts.app')

@section('title', 'Permission Details')

@section('content')
    <div class="page-content">
        <div class="page-container">
            <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                <div class="flex-grow-1">
                    <h4 class="fs-18 text-uppercase fw-bold mb-0">Permission Details</h4>
                </div>
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.permissions.index') }}">Permissions</a></li>
                        <li class="breadcrumb-item active">Permission Details</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Permission Information</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="150"><strong>ID:</strong></td>
                                    <td>#{{ $permission->id }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Name:</strong></td>
                                    <td>
                                        <span class="badge bg-primary p-2">
                                            <i class="ti ti-lock me-1"></i> {{ $permission->name }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Module:</strong></td>
                                    <td>
                                        @php
                                            $parts = explode(' ', $permission->name);
                                            $module = $parts[1] ?? 'general';
                                        @endphp
                                        <span class="badge bg-info-subtle text-info p-2">
                                            {{ ucfirst($module) }}
                                        </span>

                                </tr>
                                <tr>
                                    <td><strong>Created:</strong></td>
                                    <td>{{ $permission->created_at->format('F d, Y H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Last Updated:</strong></td>
                                    <td>{{ $permission->updated_at->diffForHumans() }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ti ti-shield me-1"></i> Roles with this Permission
                            </h5>
                        </div>
                        <div class="card-body">
                            @if ($roles->count() > 0)
                                <div class="list-group">
                                    @foreach ($roles as $role)
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <i class="ti ti-shield text-primary me-2"></i>
                                                <strong>{{ $role->name }}</strong>
                                            </div>
                                            <span class="badge bg-primary rounded-pill">
                                                {{ $role->users()->count() }} users
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="mt-3">
                                    {{ $roles->links('pagination::bootstrap-5') }}
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="ti ti-shield-off" style="font-size: 48px; opacity: 0.5;"></i>
                                    <p class="text-muted mt-2">No roles have this permission assigned.</p>
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
                            <a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary">
                                <i class="ti ti-arrow-left me-1"></i> Back
                            </a>
                            @can('edit permissions')
                                <a href="{{ route('admin.permissions.edit', $permission->id) }}" class="btn btn-primary">
                                    <i class="ti ti-edit me-1"></i> Edit Permission
                                </a>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
