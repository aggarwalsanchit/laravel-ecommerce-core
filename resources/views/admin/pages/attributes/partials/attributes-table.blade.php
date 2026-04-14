{{-- resources/views/admin/pages/attributes/partials/attributes-table.blade.php --}}
<div class="table-responsive">
    <table class="table table-hover text-nowrap mb-0">
        <thead class="bg-dark-subtle">
            <tr>
                <th class="ps-3" style="width: 50px;"><input type="checkbox" class="form-check-input" id="selectAll"></th>
                <th>ID</th>
                <th>Attribute</th>
                <th>Type</th>
                <th>Group</th>
                <th>Categories</th>
                <th>Settings</th>
                <th>Usage</th>
                <th>Status</th>
                <th>Approval</th>
                <th class="text-center" style="width: 180px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($attributes as $attribute)
                @php
                    $approvalBadge = [
                        'approved' => '<span class="badge bg-success"><i class="ti ti-check"></i> Approved</span>',
                        'pending' => '<span class="badge bg-warning"><i class="ti ti-clock"></i> Pending</span>',
                        'rejected' => '<span class="badge bg-danger"><i class="ti ti-x"></i> Rejected</span>',
                    ][$attribute->approval_status] ?? '<span class="badge bg-secondary">Unknown</span>';
                    
                    $typeClass = match($attribute->type) {
                        'text', 'textarea', 'email', 'phone', 'url' => 'text',
                        'number', 'decimal' => 'number',
                        'select', 'multiselect' => 'select',
                        'checkbox', 'radio' => 'checkbox',
                        'date', 'datetime' => 'date',
                        'color' => 'color',
                        default => 'text'
                    };
                    
                    $settingsIcons = [];
                    if ($attribute->is_required) $settingsIcons[] = '<i class="ti ti-asterisk text-danger" title="Required"></i>';
                    if ($attribute->is_filterable) $settingsIcons[] = '<i class="ti ti-filter text-info" title="Filterable"></i>';
                    if ($attribute->is_searchable) $settingsIcons[] = '<i class="ti ti-search text-success" title="Searchable"></i>';
                    if ($attribute->is_featured) $settingsIcons[] = '<i class="ti ti-star text-warning" title="Featured"></i>';
                @endphp
                <tr>
                    <td class="ps-3"><input type="checkbox" class="form-check-input attribute-checkbox" value="{{ $attribute->id }}"></td>
                    <td>#{{ $attribute->id }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            @if($attribute->icon)
                                <i class="{{ $attribute->icon }} text-primary"></i>
                            @else
                                <i class="ti ti-input text-primary"></i>
                            @endif
                            <div>
                                <span class="fw-semibold">{{ $attribute->name }}</span>
                                <div class="small text-muted">{{ $attribute->slug }}</div>
                            </div>
                        </div>
                    </td>
                    <td><span class="type-badge {{ $typeClass }}">{{ $attribute->type_label }}</span></td>
                    <td>
                        @if($attribute->group)
                            <span class="badge bg-secondary">{{ $attribute->group->name }}</span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>
                        @if($attribute->categories->count() > 0)
                            <span class="badge bg-info">{{ $attribute->categories->count() }} categories</span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            {!! implode(' ', $settingsIcons) !!}
                            @if($attribute->unit)
                                <span class="badge bg-light text-dark" title="Unit">{{ $attribute->unit }}</span>
                            @endif
                        </div>
                    </td>
                    <td>
                        <div class="d-flex flex-column">
                            <span class="fw-semibold">{{ number_format($attribute->usage_count ?? 0) }} uses</span>
                            <small class="text-muted">{{ number_format($attribute->view_count ?? 0) }} views</small>
                        </div>
                    </td>
                    <td>
                        <div class="form-check form-switch">
                            <input type="checkbox" class="form-check-input toggle-status" data-id="{{ $attribute->id }}"
                                {{ $attribute->status ? 'checked' : '' }}>
                        </div>
                    </td>
                    <td>{!! $approvalBadge !!}</td>
                    <td class="pe-3 text-center">
                        <div class="hstack gap-1 justify-content-center">
                            @php $admin = auth()->guard('admin')->user(); @endphp
                            
                            @if ($admin->can('view_attributes'))
                                <a href="{{ route('admin.attributes.show', $attribute->id) }}" class="btn btn-soft-primary btn-icon btn-sm rounded-circle" data-bs-toggle="tooltip" title="View Details">
                                    <i class="ti ti-eye"></i>
                                </a>
                            @endif
                            
                            @if ($admin->can('edit_attributes'))
                                @if ($attribute->status)
                                    <button type="button" class="btn btn-soft-warning btn-icon btn-sm rounded-circle" onclick="toggleStatus({{ $attribute->id }})" data-bs-toggle="tooltip" title="Deactivate">
                                        <i class="ti ti-circle-x"></i>
                                    </button>
                                @else
                                    <button type="button" class="btn btn-soft-success btn-icon btn-sm rounded-circle" onclick="toggleStatus({{ $attribute->id }})" data-bs-toggle="tooltip" title="Activate">
                                        <i class="ti ti-circle-check"></i>
                                    </button>
                                @endif
                                
                                <a href="{{ route('admin.attributes.edit', $attribute->id) }}" class="btn btn-soft-success btn-icon btn-sm rounded-circle" data-bs-toggle="tooltip" title="Edit Attribute">
                                    <i class="ti ti-edit"></i>
                                </a>
                                
                                @if(in_array($attribute->type, ['select', 'multiselect', 'radio']))
                                    <a href="{{ route('admin.attributes.values', $attribute->id) }}" class="btn btn-soft-info btn-icon btn-sm rounded-circle" data-bs-toggle="tooltip" title="Manage Values">
                                        <i class="ti ti-list"></i>
                                    </a>
                                @endif
                                
                                @if ($attribute->approval_status === 'pending')
                                    <button type="button" class="btn btn-soft-info btn-icon btn-sm rounded-circle" onclick="approveAttribute({{ $attribute->id }}, '{{ $attribute->name }}')" data-bs-toggle="tooltip" title="Approve">
                                        <i class="ti ti-check"></i>
                                    </button>
                                    <button type="button" class="btn btn-soft-warning btn-icon btn-sm rounded-circle" onclick="showRejectModal({{ $attribute->id }}, '{{ $attribute->name }}')" data-bs-toggle="tooltip" title="Reject">
                                        <i class="ti ti-x"></i>
                                    </button>
                                @endif
                            @endif
                            
                            @if ($admin->can('delete_attributes') && $attribute->productValues()->count() == 0)
                                <button type="button" class="btn btn-soft-danger btn-icon btn-sm rounded-circle" onclick="confirmDelete({{ $attribute->id }}, '{{ $attribute->name }}')" data-bs-toggle="tooltip" title="Delete Attribute">
                                    <i class="ti ti-trash"></i>
                                </button>
                            @elseif($attribute->productValues()->count() > 0)
                                <button type="button" class="btn btn-soft-secondary btn-icon btn-sm rounded-circle" disabled data-bs-toggle="tooltip" title="Cannot delete - used in {{ $attribute->productValues()->count() }} products">
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
                            <i class="ti ti-input" style="font-size: 48px; opacity: 0.5;"></i>
                            <h5 class="mt-3">No Attributes Found</h5>
                            <p class="text-muted">Get started by creating a new custom attribute.</p>
                            @can('create_attributes')
                                <a href="{{ route('admin.attributes.create') }}" class="btn btn-primary mt-2">
                                    <i class="ti ti-plus me-1"></i> Add New Attribute
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
function approveAttribute(attributeId, attributeName) {
    Swal.fire({
        title: 'Approve Attribute?',
        text: `Are you sure you want to approve "${attributeName}"?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        confirmButtonText: 'Yes, approve it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '{{ url("admin/attributes/requests") }}/' + attributeId + '/approve',
                type: 'POST',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({ icon: 'success', title: 'Approved!', text: response.message, timer: 1500, showConfirmButton: false }).then(() => location.reload());
                    }
                },
                error: function(xhr) {
                    Swal.fire({ icon: 'error', title: 'Error!', text: xhr.responseJSON?.message || 'Failed to approve attribute.', confirmButtonColor: '#d33' });
                }
            });
        }
    });
}

function showRejectModal(attributeId, attributeName) {
    Swal.fire({
        title: 'Reject Attribute',
        html: `<p>Reject "${attributeName}"?</p><textarea id="rejectionReason" class="swal2-textarea" placeholder="Provide rejection reason..." rows="3"></textarea>`,
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
                url: '{{ url("admin/attributes/requests") }}/' + attributeId + '/reject',
                type: 'POST',
                data: { _token: '{{ csrf_token() }}', rejection_reason: result.value.reason },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({ icon: 'success', title: 'Rejected!', text: response.message, timer: 1500, showConfirmButton: false }).then(() => location.reload());
                    }
                },
                error: function(xhr) {
                    Swal.fire({ icon: 'error', title: 'Error!', text: xhr.responseJSON?.message || 'Failed to reject attribute.', confirmButtonColor: '#d33' });
                }
            });
        }
    });
}
</script>
@endpush