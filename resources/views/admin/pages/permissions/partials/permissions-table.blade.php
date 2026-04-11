{{-- resources/views/admin/permissions/partials/permissions-table.blade.php --}}
<table class="table table-hover text-nowrap mb-0">
    <thead class="bg-dark-subtle">
        <th class="ps-3" style="width: 50px;">
            <input type="checkbox" class="form-check-input" id="selectAll">
        </th>
        <th>ID</th>
        <th>Permission Name</th>
        <th>Guard</th>
        <th>Module</th>
        <th>Assigned Roles</th>
        <th>Created</th>
        <th class="text-center" style="width: 120px;">Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($permissions as $permission)
            @php
                $parts = explode(' ', $permission->name);
                $module = $parts[1] ?? 'general';
                $roleCount = $permission->roles()->count();
            @endphp
            <tr>
                <td class="ps-3">
                    <input type="checkbox" class="form-check-input permission-checkbox" value="{{ $permission->id }}">
                </td>
                <td>#{{ $permission->id }}</td>
                <td>
                    <div class="d-flex align-items-center gap-2">
                        <i class="ti ti-lock text-primary"></i>
                        <span class="fw-semibold">{{ $permission->name }}</span>
                    </div>
                </td>
                <td>
                    <span class="badge bg-primary-subtle text-primary p-2">
                        <i class="ti ti-package me-1"></i> {{ $permission->guard_name }}
                    </span>
                </td>
                <td>
                    <span class="badge bg-primary-subtle text-primary p-2">
                        <i class="ti ti-package me-1"></i> {{ ucfirst($module) }}
                    </span>
                </td>
                <td>
                    @if ($roleCount > 0)
                        <span class="badge bg-info-subtle text-info p-2">
                            <i class="ti ti-shield me-1"></i> {{ $roleCount }} role(s)
                        </span>
                    @else
                        <span class="badge bg-secondary-subtle text-secondary p-2">No roles</span>
                    @endif
                </td>
                <td>
                    <div>
                        <div>{{ $permission->created_at->format('d M Y') }}</div>
                        <small class="text-muted">{{ $permission->created_at->diffForHumans() }}</small>
                    </div>
                </td>
                <td class="pe-3">
                    <div class="hstack gap-1 justify-content-end">
                        @php $admin = Auth::guard('admin')->user(); @endphp
                        @if ($admin->can('view_permissions'))
                            <a href="{{ route('admin.permissions.show', $permission->id) }}"
                                class="btn btn-soft-primary btn-icon btn-sm rounded-circle" data-bs-toggle="tooltip"
                                title="View Details">
                                <i class="ti ti-eye"></i>
                            </a>
                        @endif
                        @if ($admin->can('edit_permissions'))
                            <a href="{{ route('admin.permissions.edit', $permission->id) }}"
                                class="btn btn-soft-success btn-icon btn-sm rounded-circle" data-bs-toggle="tooltip"
                                title="Edit Permission">
                                <i class="ti ti-edit fs-16"></i>
                            </a>
                        @endif
                        @if ($admin->can('delete_permissions'))
                            @if ($roleCount == 0)
                                <button type="button" class="btn btn-soft-danger btn-icon btn-sm rounded-circle"
                                    onclick="confirmDelete({{ $permission->id }})" data-bs-toggle="tooltip"
                                    title="Delete Permission">
                                    <i class="ti ti-trash"></i>
                                </button>
                            @else
                                <button type="button" class="btn btn-soft-secondary btn-icon btn-sm rounded-circle"
                                    disabled data-bs-toggle="tooltip" title="Cannot delete - assigned to roles">
                                    <i class="ti ti-lock"></i>
                                </button>
                            @endif
                        @endif
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center py-5">
                    <div class="empty-state">
                        <i class="ti ti-lock-off" style="font-size: 48px; opacity: 0.5;"></i>
                        <h5 class="mt-3">No Permissions Found</h5>
                        <p class="text-muted">Get started by creating a new permission.</p>
                        @if ($admin->can('create_permissions'))
                            <a href="{{ route('admin.permissions.create') }}" class="btn btn-primary mt-2">
                                <i class="ti ti-plus me-1"></i> Add New Permission
                            </a>
                        @endif
                    </div>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
