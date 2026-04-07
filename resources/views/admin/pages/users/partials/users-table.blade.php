{{-- resources/views/admin/pages/users/partials/users-table.blade.php --}}

<div class="table-responsive">
    <table class="table table-hover text-nowrap mb-0">
        <thead class="bg-dark-subtle">
            <tr>
                <th class="ps-3" style="width: 50px;">
                    <input type="checkbox" class="form-check-input" id="selectAll">
                </th>
                <th>User ID</th>
                <th>User</th>
                <th>Contact</th>
                <th>Roles</th>
                <th>Permissions</th>
                <th>Status</th>
                <th>Joined</th>
                <th class="text-center" style="width: 150px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users ?? [] as $user)
                <tr>
                    <td class="ps-3">
                        <input type="checkbox" class="form-check-input user-checkbox" value="{{ $user->id }}"
                            {{ $user->id === auth('admin')->id() ? 'disabled' : '' }}>
                    </td>
                    <td>
                        <span class="fw-semibold">#{{ str_pad($user->id, 5, '0', STR_PAD_LEFT) }}</span>
                    </td>
                    <td>
                        <div class="d-flex justify-content-start align-items-center gap-3">
                            <div class="avatar-md">
                                @if ($user->avatar)
                                    <img src="{{ asset('storage/avatars/' . $user->avatar) }}" alt="{{ $user->name }}"
                                        class="img-fluid rounded-circle"
                                        style="width: 40px; height: 40px; object-fit: cover;">
                                @else
                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white"
                                        style="width: 40px; height: 40px; font-size: 16px;">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            <div>
                                <div class="fw-semibold">{{ $user->name }}</div>
                                <small class="text-muted">ID: {{ $user->id }}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div><i class="ti ti-mail me-1"></i> {{ $user->email }}</div>
                        @if ($user->phone)
                            <small><i class="ti ti-phone me-1"></i> {{ $user->phone }}</small>
                        @endif
                    </td>
                    <td>
                        @forelse($user->roles as $role)
                            <span class="badge bg-primary-subtle text-primary mb-1 p-2">
                                <i class="ti ti-shield me-1"></i>{{ $role->name }}
                            </span>
                        @empty
                            <span class="badge bg-secondary-subtle text-secondary p-2">No Role</span>
                        @endforelse
                    </td>
                    <td>
                        @php
                            $permissions = $user->getAllPermissions()->pluck('name')->toArray();
                            $displayPermissions = array_slice($permissions, 0, 3);
                            $remainingCount = count($permissions) - 3;
                        @endphp

                        @if (count($displayPermissions) > 0)
                            @foreach ($displayPermissions as $permission)
                                <span class="badge bg-info-subtle text-info mb-1 p-1" style="font-size: 11px;">
                                    {{ $permission }}
                                </span>
                            @endforeach
                            @if ($remainingCount > 0)
                                <span class="badge bg-secondary-subtle text-secondary p-1" data-bs-toggle="tooltip"
                                    title="{{ implode(', ', array_slice($permissions, 3)) }}">
                                    +{{ $remainingCount }} more
                                </span>
                            @endif
                        @else
                            <span class="badge bg-secondary-subtle text-secondary p-2">No Permissions</span>
                        @endif
                    </td>
                    <td>
                        @if ($user->is_active)
                            <span class="badge bg-success-subtle text-success fs-12 p-2">
                                <i class="ti ti-circle-check me-1"></i>Active
                            </span>
                        @else
                            <span class="badge bg-danger-subtle text-danger fs-12 p-2">
                                <i class="ti ti-circle-x me-1"></i>Inactive
                            </span>
                        @endif
                    </td>
                    <td>
                        <div>{{ $user->created_at->format('d M Y') }}</div>
                        <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                    </td>
                    <td class="pe-3">
                        <div class="hstack gap-1 justify-content-end">
                            @can('view_users')
                                <a href="{{ route('admin.users.show', $user->id) }}"
                                    class="btn btn-soft-primary btn-icon btn-sm rounded-circle" data-bs-toggle="tooltip"
                                    title="View Details">
                                    <i class="ti ti-eye"></i>
                                </a>
                            @endcan

                            @can('edit_users')
                                <a href="{{ route('admin.users.edit', $user->id) }}"
                                    class="btn btn-soft-success btn-icon btn-sm rounded-circle" data-bs-toggle="tooltip"
                                    title="Edit User">
                                    <i class="ti ti-edit fs-16"></i>
                                </a>
                            @endcan

                            @can('activate_users')
                                @if ($user->is_active)
                                    <button type="button" class="btn btn-soft-warning btn-icon btn-sm rounded-circle"
                                        onclick="confirmDeactivate({{ $user->id }}, '{{ addslashes($user->name) }}')"
                                        data-bs-toggle="tooltip" title="Deactivate User"
                                        {{ $user->id === auth('admin')->id() ? 'disabled' : '' }}>
                                        <i class="ti ti-user-x"></i>
                                    </button>
                                @else
                                    <button type="button" class="btn btn-soft-success btn-icon btn-sm rounded-circle"
                                        onclick="confirmActivate({{ $user->id }}, '{{ addslashes($user->name) }}')"
                                        data-bs-toggle="tooltip" title="Activate User">
                                        <i class="ti ti-user-check"></i>
                                    </button>
                                @endif
                            @endcan

                            @can('delete_users')
                                <button type="button" class="btn btn-soft-danger btn-icon btn-sm rounded-circle"
                                    onclick="confirmDelete({{ $user->id }}, '{{ addslashes($user->name) }}')"
                                    data-bs-toggle="tooltip" title="Delete User"
                                    {{ $user->id === auth('admin')->id() ? 'disabled' : '' }}>
                                    <i class="ti ti-trash"></i>
                                </button>
                            @endcan

                            @can('impersonate_users')
                                <button type="button" class="btn btn-soft-info btn-icon btn-sm rounded-circle"
                                    onclick="impersonateUser({{ $user->id }}, '{{ addslashes($user->name) }}')"
                                    data-bs-toggle="tooltip" title="Login as this user">
                                    <i class="ti ti-user-check"></i>
                                </button>
                            @endcan
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center py-5">
                        <div class="empty-state">
                            <i class="ti ti-users" style="font-size: 48px; opacity: 0.5;"></i>
                            <h5 class="mt-3">No Users Found</h5>
                            <p class="text-muted">Get started by creating a new user.</p>
                            @can('create_users')
                                <a href="{{ route('admin.users.create') }}" class="btn btn-primary mt-2">
                                    <i class="ti ti-plus me-1"></i> Add New User
                                </a>
                            @endcan
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
