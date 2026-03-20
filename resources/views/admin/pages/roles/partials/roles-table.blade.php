{{-- resources/views/admin/roles/partials/roles-table.blade.php --}}
<table class="table table-hover text-nowrap mb-0">
    <thead class="bg-dark-subtle">
        32
        <th class="ps-3" style="width: 50px;">
            <input type="checkbox" class="form-check-input" id="selectAll">
        </th>
        <th>ID</th>
        <th>Role Name</th>
        <th>Guard</th>
        <th>Permissions</th>
        <th>Users</th>
        <th>Created</th>
        <th class="text-center" style="width: 120px;">Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($roles as $role)
            @php
                $permissionCount = $role->permissions()->count();
                $userCount = $role->users()->count();
            @endphp
            <tr>
                <td class="ps-3">
                    @if ($role->name !== 'Super Admin')
                        <input type="checkbox" class="form-check-input role-checkbox" value="{{ $role->id }}">
                    @endif
                </td>
                <td>#{{ $role->id }}</td>
                <td>
                    <div class="d-flex align-items-center gap-2">
                        <i class="ti ti-shield text-primary"></i>
                        <span class="fw-semibold">{{ $role->name }}</span>
                        @if ($role->name === 'Super Admin')
                            <span class="badge bg-danger-subtle text-danger ms-2">System</span>
                        @endif
                    </div>
                </td>
                <td>
                    <span class="badge bg-info-subtle text-info p-2">
                        <i class="ti ti-lock me-1"></i> {{ ucfirst($role->guard_name) }}
                    </span>
                </td>
                <td>
                    @if ($permissionCount > 0)
                        <span class="badge bg-primary-subtle text-primary p-2">
                            <i class="ti ti-key me-1"></i> {{ $permissionCount }} permissions
                        </span>
                    @else
                        <span class="badge bg-secondary-subtle text-secondary p-2">No permissions</span>
                    @endif
                </td>
                <td>
                    <span class="badge bg-success-subtle text-success p-2">
                        <i class="ti ti-users me-1"></i> {{ $userCount }} users
                    </span>
                </td>
                <td>
                    <div>
                        <div>{{ $role->created_at->format('d M Y') }}</div>
                        <small class="text-muted">{{ $role->created_at->diffForHumans() }}</small>
                    </div>
                </td>
                <td class="pe-3">
                    <div class="hstack gap-1 justify-content-end">
                        @can('view roles')
                            <a href="{{ route('admin.roles.show', $role->id) }}"
                                class="btn btn-soft-primary btn-icon btn-sm rounded-circle" data-bs-toggle="tooltip"
                                title="View Details">
                                <i class="ti ti-eye"></i>
                            </a>
                        @endcan

                        @can('edit roles')
                            @if ($role->name !== 'Super Admin')
                                <a href="{{ route('admin.roles.edit', $role->id) }}"
                                    class="btn btn-soft-success btn-icon btn-sm rounded-circle" data-bs-toggle="tooltip"
                                    title="Edit Role">
                                    <i class="ti ti-edit fs-16"></i>
                                </a>
                            @endif
                        @endcan

                        @can('assign permissions')
                            @if ($role->name !== 'Super Admin')
                                <a href="{{ route('admin.roles.assign-permissions', $role->id) }}"
                                    class="btn btn-soft-info btn-icon btn-sm rounded-circle" data-bs-toggle="tooltip"
                                    title="Assign Permissions">
                                    <i class="ti ti-key"></i>
                                </a>
                            @endif
                        @endcan

                        @can('delete roles')
                            @if ($role->name !== 'Super Admin' && $userCount == 0)
                                <button type="button" class="btn btn-soft-danger btn-icon btn-sm rounded-circle"
                                    onclick="confirmDelete({{ $role->id }}, '{{ $role->name }}')"
                                    data-bs-toggle="tooltip" title="Delete Role">
                                    <i class="ti ti-trash"></i>
                                </button>
                            @elseif($role->name !== 'Super Admin' && $userCount > 0)
                                <button type="button" class="btn btn-soft-secondary btn-icon btn-sm rounded-circle"
                                    disabled data-bs-toggle="tooltip"
                                    title="Cannot delete - has {{ $userCount }} users">
                                    <i class="ti ti-lock"></i>
                                </button>
                            @endif
                        @endcan
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-center py-5">
                    <div class="empty-state">
                        <i class="ti ti-shield-off" style="font-size: 48px; opacity: 0.5;"></i>
                        <h5 class="mt-3">No Roles Found</h5>
                        <p class="text-muted">Get started by creating a new role.</p>
                        @can('create roles')
                            <a href="{{ route('admin.roles.create') }}" class="btn btn-primary mt-2">
                                <i class="ti ti-plus me-1"></i> Add New Role
                            </a>
                        @endcan
                    </div>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
