{{-- resources/views/marketplace/pages/staff/partials/staff-table.blade.php --}}

<div class="table-responsive">
    <table class="table table-hover text-nowrap mb-0">
        <thead class="bg-dark-subtle">
            <tr>
                <th class="ps-3" style="width: 50px;">
                    <input type="checkbox" class="form-check-input" id="selectAll">
                </th>
                <th>Staff ID</th>
                <th>Staff</th>
                <th>Contact</th>
                <th>Role</th>
                <th>Permissions</th>
                <th>Status</th>
                <th>Joined</th>
                <th class="text-center" style="width: 150px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($staffs ?? [] as $staff)
                <tr>
                    <td class="ps-3">
                        <input type="checkbox" class="form-check-input staff-checkbox" value="{{ $staff->id }}">
                    </td>
                    <td>
                        <span class="fw-semibold">#{{ str_pad($staff->id, 5, '0', STR_PAD_LEFT) }}</span>
                    </td>
                    <td>
                        <div class="d-flex justify-content-start align-items-center gap-3">
                            <div class="avatar-md">
                                @if ($staff->avatar)
                                    <img src="{{ Storage::url($staff->avatar) }}" alt="{{ $staff->name }}"
                                        class="img-fluid rounded-circle"
                                        style="width: 40px; height: 40px; object-fit: cover;">
                                @else
                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white"
                                        style="width: 40px; height: 40px; font-size: 16px;">
                                        {{ substr($staff->name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            <div>
                                <div class="fw-semibold">{{ $staff->name }}</div>
                                <small class="text-muted">ID: {{ $staff->id }}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div><i class="ti ti-mail me-1"></i> {{ $staff->email }}</div>
                        @if ($staff->phone)
                            <small><i class="ti ti-phone me-1"></i> {{ $staff->phone }}</small>
                        @endif
                    </td>
                    <td>
                        @forelse($staff->roles as $role)
                            <span class="badge bg-primary-subtle text-primary mb-1 p-2">
                                <i class="ti ti-shield me-1"></i>{{ $role->name }}
                            </span>
                        @empty
                            <span class="badge bg-secondary-subtle text-secondary p-2">No Role</span>
                        @endforelse
                    </td>
                    <td>
                        @php
                            $permissions = $staff->getAllPermissions()->pluck('name')->toArray();
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
                        @if ($staff->is_active)
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
                        <div>{{ $staff->created_at->format('d M Y') }}</div>
                        <small class="text-muted">{{ $staff->created_at->diffForHumans() }}</small>
                    </td>
                    <td class="pe-3">
                        <div class="hstack gap-1 justify-content-end">
                            @php $vendor = Auth::guard('vendor')->user(); @endphp
                            @if ($vendor->can('view_staff'))
                                <a href="{{ route('vendor.staff.show', $staff->id) }}"
                                    class="btn btn-soft-primary btn-icon btn-sm rounded-circle" data-bs-toggle="tooltip"
                                    title="View Details">
                                    <i class="ti ti-eye"></i>
                                </a>
                            @endif
                            @if ($vendor->can('edit_staff'))
                                <a href="{{ route('vendor.staff.edit', $staff->id) }}"
                                    class="btn btn-soft-success btn-icon btn-sm rounded-circle" data-bs-toggle="tooltip"
                                    title="Edit Staff">
                                    <i class="ti ti-edit fs-16"></i>
                                </a>
                            @endif
                            @if ($vendor->can('activate_staff'))
                                @if ($staff->is_active)
                                    <button type="button" class="btn btn-soft-warning btn-icon btn-sm rounded-circle"
                                        onclick="confirmDeactivate({{ $staff->id }}, '{{ addslashes($staff->name) }}')"
                                        data-bs-toggle="tooltip" title="Deactivate Staff">
                                        <i class="ti ti-user-x"></i>
                                    </button>
                                @else
                                    <button type="button" class="btn btn-soft-success btn-icon btn-sm rounded-circle"
                                        onclick="confirmActivate({{ $staff->id }}, '{{ addslashes($staff->name) }}')"
                                        data-bs-toggle="tooltip" title="Activate Staff">
                                        <i class="ti ti-user-check"></i>
                                    </button>
                                @endif
                            @endif
                            @if ($vendor->can('delete_staff'))
                                <button type="button" class="btn btn-soft-danger btn-icon btn-sm rounded-circle"
                                    onclick="confirmDelete({{ $staff->id }}, '{{ addslashes($staff->name) }}')"
                                    data-bs-toggle="tooltip" title="Delete Staff"
                                    {{ $staff->role === 'admin' ? 'disabled' : '' }}>
                                    <i class="ti ti-trash"></i>
                                </button>
                            @endif
                            @if ($vendor->can('impersonate_staff'))
                                <button type="button" class="btn btn-soft-info btn-icon btn-sm rounded-circle"
                                    onclick="impersonateUser({{ $staff->id }}, '{{ addslashes($staff->name) }}')"
                                    data-bs-toggle="tooltip" title="Login as this staff">
                                    <i class="ti ti-user-check"></i>
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center py-5">
                        <div class="empty-state">
                            <i class="ti ti-users" style="font-size: 48px; opacity: 0.5;"></i>
                            <h5 class="mt-3">No Staff Members Found</h5>
                            <p class="text-muted">Get started by adding a new staff member.</p>
                            @if ($vendor->can('create_staff'))
                                <a href="{{ route('vendor.staff.create') }}" class="btn btn-primary mt-2">
                                    <i class="ti ti-plus me-1"></i> Add New Staff
                                </a>
                            @endif
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
