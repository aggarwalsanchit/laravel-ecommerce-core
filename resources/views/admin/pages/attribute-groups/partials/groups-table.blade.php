{{-- resources/views/admin/pages/attribute-groups/partials/groups-table.blade.php --}}
<div class="table-responsive">
    <table class="table table-hover text-nowrap mb-0">
        <thead class="bg-dark-subtle">
            <tr>
                <th class="ps-3" style="width: 50px;"><input type="checkbox" class="form-check-input" id="selectAll"></th>
                <th>ID</th>
                <th>Group Name</th>
                <th>Icon</th>
                <th>Position</th>
                <th>Attributes</th>
                <th>Order</th>
                <th>Settings</th>
                <th>Status</th>
                <th>Approval</th>
                <th class="text-center" style="width: 150px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($groups as $group)
                @php
                    $approvalBadge = [
                        'approved' => '<span class="badge bg-success"><i class="ti ti-check"></i> Approved</span>',
                        'pending' => '<span class="badge bg-warning"><i class="ti ti-clock"></i> Pending</span>',
                        'rejected' => '<span class="badge bg-danger"><i class="ti ti-x"></i> Rejected</span>',
                    ][$group->approval_status] ?? '<span class="badge bg-secondary">Unknown</span>';
                    
                    $settingsIcons = [];
                    if ($group->is_collapsible) $settingsIcons[] = '<i class="ti ti-chevron-down" title="Collapsible"></i>';
                    if ($group->is_open_by_default) $settingsIcons[] = '<i class="ti ti-eye" title="Open by Default"></i>';
                    
                    $attributesCount = $group->attributes()->count();
                @endphp
                <tr>
                    <td class="ps-3"><input type="checkbox" class="form-check-input group-checkbox" value="{{ $group->id }}"></td>
                    <td>#{{ $group->id }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            @if($group->icon)
                                <i class="{{ $group->icon }} text-primary"></i>
                            @else
                                <i class="ti ti-layout-sidebar text-primary"></i>
                            @endif
                            <div>
                                <span class="fw-semibold">{{ $group->name }}</span>
                                <div class="small text-muted">{{ $group->slug }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        @if($group->icon)
                            <code>{{ $group->icon }}</code>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>
                        @if($group->position)
                            <span class="badge bg-secondary">{{ ucfirst($group->position) }}</span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge bg-info">{{ $attributesCount }} attributes</span>
                    </td>
                    <td>{{ $group->order }}</td>
                    <td>
                        <div class="d-flex gap-1">
                            {!! implode(' ', $settingsIcons) !!}
                            @if($group->is_collapsible)
                                <span class="badge bg-light text-dark" title="Collapsible">Collapsible</span>
                            @endif
                        </div>
                    </td>
                    <td>
                        <div class="form-check form-switch">
                            <input type="checkbox" class="form-check-input toggle-status" data-id="{{ $group->id }}"
                                {{ $group->status ? 'checked' : '' }}>
                        </div>
                    </td>
                    <td>{!! $approvalBadge !!}</td>
                    <td class="pe-3 text-center">
                        <div class="hstack gap-1 justify-content-center">
                            @php $admin = auth()->guard('admin')->user(); @endphp
                            
                            @if ($admin->can('view_attribute_groups'))
                                <a href="{{ route('admin.attribute-groups.show', $group->id) }}" class="btn btn-soft-primary btn-icon btn-sm rounded-circle" data-bs-toggle="tooltip" title="View Details">
                                    <i class="ti ti-eye"></i>
                                </a>
                            @endif
                            
                            @if ($admin->can('edit_attribute_groups'))
                                @if ($group->status)
                                    <button type="button" class="btn btn-soft-warning btn-icon btn-sm rounded-circle" onclick="toggleStatus({{ $group->id }})" data-bs-toggle="tooltip" title="Deactivate">
                                        <i class="ti ti-circle-x"></i>
                                    </button>
                                @else
                                    <button type="button" class="btn btn-soft-success btn-icon btn-sm rounded-circle" onclick="toggleStatus({{ $group->id }})" data-bs-toggle="tooltip" title="Activate">
                                        <i class="ti ti-circle-check"></i>
                                    </button>
                                @endif
                                
                                <a href="{{ route('admin.attribute-groups.edit', $group->id) }}" class="btn btn-soft-success btn-icon btn-sm rounded-circle" data-bs-toggle="tooltip" title="Edit Group">
                                    <i class="ti ti-edit"></i>
                                </a>
                                
                                @if ($group->approval_status === 'pending')
                                    <button type="button" class="btn btn-soft-info btn-icon btn-sm rounded-circle" onclick="approveGroup({{ $group->id }}, '{{ $group->name }}')" data-bs-toggle="tooltip" title="Approve">
                                        <i class="ti ti-check"></i>
                                    </button>
                                    <button type="button" class="btn btn-soft-warning btn-icon btn-sm rounded-circle" onclick="showRejectModal({{ $group->id }}, '{{ $group->name }}')" data-bs-toggle="tooltip" title="Reject">
                                        <i class="ti ti-x"></i>
                                    </button>
                                @endif
                            @endif
                            
                            @if ($admin->can('delete_attribute_groups') && $attributesCount == 0)
                                <button type="button" class="btn btn-soft-danger btn-icon btn-sm rounded-circle" onclick="confirmDelete({{ $group->id }}, '{{ $group->name }}')" data-bs-toggle="tooltip" title="Delete Group">
                                    <i class="ti ti-trash"></i>
                                </button>
                            @elseif($attributesCount > 0)
                                <button type="button" class="btn btn-soft-secondary btn-icon btn-sm rounded-circle" disabled data-bs-toggle="tooltip" title="Cannot delete - has {{ $attributesCount }} attributes">
                                    <i class="ti ti-lock"></i>
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" class="text-center py-5">
                        <div class="empty-state">
                            <i class="ti ti-layout-sidebar-off" style="font-size: 48px; opacity: 0.5;"></i>
                            <h5 class="mt-3">No Attribute Groups Found</h5>
                            <p class="text-muted">Get started by creating a new attribute group.</p>
                            @can('create_attribute_groups')
                                <a href="{{ route('admin.attribute-groups.create') }}" class="btn btn-primary mt-2">
                                    <i class="ti ti-plus me-1"></i> Add New Group
                                </a>
                            @endcan
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@push('scripts')
<script>
function approveGroup(groupId, groupName) {
    Swal.fire({
        title: 'Approve Group?',
        text: `Are you sure you want to approve "${groupName}"?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        confirmButtonText: 'Yes, approve it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '{{ url("admin/attribute-groups/requests") }}/' + groupId + '/approve',
                type: 'POST',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({ icon: 'success', title: 'Approved!', text: response.message, timer: 1500, showConfirmButton: false }).then(() => location.reload());
                    }
                },
                error: function(xhr) {
                    Swal.fire({ icon: 'error', title: 'Error!', text: xhr.responseJSON?.message || 'Failed to approve group.', confirmButtonColor: '#d33' });
                }
            });
        }
    });
}

function showRejectModal(groupId, groupName) {
    Swal.fire({
        title: 'Reject Group',
        html: `<p>Reject "${groupName}"?</p><textarea id="rejectionReason" class="swal2-textarea" placeholder="Provide rejection reason..." rows="3"></textarea>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        confirmButtonText: 'Yes, reject it!',
        preConfirm: () => {
            const reason = document.getElementById('rejectionReason').value;
            if (!reason) { Swal.showValidationMessage('Please provide a reason'); return false; }
            return { reason: reason };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '{{ url("admin/attribute-groups/requests") }}/' + groupId + '/reject',
                type: 'POST',
                data: { _token: '{{ csrf_token() }}', rejection_reason: result.value.reason },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({ icon: 'success', title: 'Rejected!', text: response.message, timer: 1500, showConfirmButton: false }).then(() => location.reload());
                    }
                },
                error: function(xhr) {
                    Swal.fire({ icon: 'error', title: 'Error!', text: xhr.responseJSON?.message || 'Failed to reject group.', confirmButtonColor: '#d33' });
                }
            });
        }
    });
}
</script>
@endpush