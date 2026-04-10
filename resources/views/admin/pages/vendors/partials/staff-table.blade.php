{{-- resources/views/admin/pages/vendors/partials/staff-table.blade.php --}}

<div class="table-responsive">
    <table class="table table-hover text-nowrap mb-0">
        <thead class="bg-dark-subtle">
            <tr>
                <th class="ps-3" style="width: 50px;">
                    <input type="checkbox" class="form-check-input" id="selectAll">
                </th>
                <th>Staff ID</th>
                <th>Staff Member</th>
                <th>Contact</th>
                <th>Role</th>
                <th>Status</th>
                <th>Joined</th>
                <th class="text-center" style="width: 150px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($staffMembers ?? [] as $staff)
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
                        @php
                            $roleColors = [
                                'manager' => 'warning',
                                'inventory' => 'info',
                                'fulfillment' => 'success',
                                'support' => 'primary',
                                'staff' => 'secondary',
                            ];
                            $roleColor = $roleColors[$staff->role] ?? 'secondary';
                        @endphp
                        <span class="badge bg-{{ $roleColor }}-subtle text-{{ $roleColor }} mb-1 p-2">
                            <i class="ti ti-briefcase me-1"></i>{{ ucfirst($staff->role) }}
                        </span>
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
                            @can('view_staff')
                                <button type="button" class="btn btn-soft-primary btn-icon btn-sm rounded-circle"
                                    onclick="viewStaff({{ $staff->id }}, '{{ addslashes($staff->name) }}')"
                                    data-bs-toggle="tooltip" title="View Details">
                                    <i class="ti ti-eye"></i>
                                </button>
                            @endcan

                            @can('edit_staff')
                                <a href="{{ route('admin.vendors.staff.edit', [$shop->id, $staff->id]) }}"
                                    class="btn btn-soft-success btn-icon btn-sm rounded-circle" data-bs-toggle="tooltip"
                                    title="Edit Staff">
                                    <i class="ti ti-edit fs-16"></i>
                                </a>
                            @endcan

                            @can('activate_staff')
                                @if ($staff->is_active)
                                    <button type="button" class="btn btn-soft-warning btn-icon btn-sm rounded-circle"
                                        onclick="deactivateStaff({{ $staff->id }}, '{{ addslashes($staff->name) }}')"
                                        data-bs-toggle="tooltip" title="Deactivate Staff">
                                        <i class="ti ti-user-x"></i>
                                    </button>
                                @else
                                    <button type="button" class="btn btn-soft-success btn-icon btn-sm rounded-circle"
                                        onclick="activateStaff({{ $staff->id }}, '{{ addslashes($staff->name) }}')"
                                        data-bs-toggle="tooltip" title="Activate Staff">
                                        <i class="ti ti-user-check"></i>
                                    </button>
                                @endif
                            @endcan

                            @can('delete_staff')
                                <button type="button" class="btn btn-soft-danger btn-icon btn-sm rounded-circle"
                                    onclick="deleteStaff({{ $staff->id }}, '{{ addslashes($staff->name) }}')"
                                    data-bs-toggle="tooltip" title="Delete Staff">
                                    <i class="ti ti-trash"></i>
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
                            <h5 class="mt-3">No Staff Members Found</h5>
                            <p class="text-muted">Get started by adding a new staff member.</p>
                            @can('create_staff')
                                <a href="{{ route('admin.vendors.staff.create', $shop->id) }}"
                                    class="btn btn-primary mt-2">
                                    <i class="ti ti-plus me-1"></i> Add New Staff
                                </a>
                            @endcan
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
