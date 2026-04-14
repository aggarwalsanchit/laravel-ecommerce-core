{{-- resources/views/admin/pages/attribute-groups/show.blade.php --}}
@extends('management.layouts.app')

@section('title', 'Attribute Group Details - ' . ($attribute_group->name ?? 'Not Found'))

@section('content')
<div class="page-content">
    <div class="page-container">
        <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
            <div class="flex-grow-1">
                <h4 class="fs-18 text-uppercase fw-bold mb-0">Attribute Group Details: {{ $attribute_group->name ?? 'N/A' }}</h4>
            </div>
            <div class="text-end">
                <ol class="breadcrumb m-0 py-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.attribute-groups.index') }}">Attribute Groups</a></li>
                    <li class="breadcrumb-item active">{{ $attribute_group->name ?? 'Details' }}</li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4">
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ti ti-info-circle"></i> Group Information</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <td width="120"><strong>ID:</strong></td>
                                <td>#{{ $attribute_group->id ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Name:</strong></td>
                                <td>{{ $attribute_group->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Slug:</strong></td>
                                <td><code>{{ $attribute_group->slug ?? 'N/A' }}</code></td>
                            </tr>
                            <tr>
                                <td><strong>Icon:</strong></td>
                                <td>
                                    @if(!empty($attribute_group->icon))
                                        <i class="{{ $attribute_group->icon }}"></i> <code>{{ $attribute_group->icon }}</code>
                                    @else
                                        —
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Order:</strong></td>
                                <td>{{ $attribute_group->order ?? 0 }}</td>
                            </tr>
                            <tr>
                                <td><strong>Position:</strong></td>
                                <td>
                                    @if(!empty($attribute_group->position))
                                        <span class="badge bg-secondary">{{ ucfirst($attribute_group->position) }}</span>
                                    @else
                                        —
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td>{!! $attribute_group->status_badge ?? '<span class="badge bg-secondary">Unknown</span>' !!}</td>
                            </tr>
                            <tr>
                                <td><strong>Collapsible:</strong></td>
                                <td>
                                    @if(isset($attribute_group->is_collapsible) && $attribute_group->is_collapsible)
                                        <span class="badge bg-success">Yes</span>
                                    @else
                                        <span class="badge bg-secondary">No</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Open by Default:</strong></td>
                                <td>
                                    @if(isset($attribute_group->is_open_by_default) && $attribute_group->is_open_by_default)
                                        <span class="badge bg-success">Yes</span>
                                    @else
                                        <span class="badge bg-secondary">No</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Created:</strong></td>
                                <td>
                                    @if($attribute_group->created_at)
                                        {{ $attribute_group->created_at->format('F d, Y H:i') }}
                                    @else
                                        —
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Updated:</strong></td>
                                <td>
                                    @if($attribute_group->updated_at)
                                        {{ $attribute_group->updated_at->diffForHumans() }}
                                    @else
                                        —
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                {{-- Approval Info Card (only if not approved) --}}
                @if(isset($attribute_group->approval_status) && $attribute_group->approval_status !== 'approved')
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ti ti-check-circle"></i> Approval Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            <strong>Status:</strong> {!! $attribute_group->status_badge ?? '<span class="badge bg-secondary">Unknown</span>' !!}
                        </div>
                        @if(!empty($attribute_group->requested_by))
                            <div class="mb-2">
                                <strong>Requested by:</strong> Vendor #{{ $attribute_group->requested_by }}
                                @if($attribute_group->requested_at)
                                    <br><small>{{ $attribute_group->requested_at->format('F d, Y') }}</small>
                                @endif
                            </div>
                        @endif
                        @if(!empty($attribute_group->rejection_reason))
                            <div class="mb-2">
                                <strong>Rejection Reason:</strong><br>
                                <span class="text-danger">{{ $attribute_group->rejection_reason }}</span>
                            </div>
                        @endif
                        @if(!empty($attribute_group->approved_by))
                            <div class="mb-2">
                                <strong>Processed by:</strong> Admin #{{ $attribute_group->approved_by }}
                                @if($attribute_group->approved_at)
                                    on {{ $attribute_group->approved_at->format('F d, Y') }}
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            <div class="col-lg-8">
                {{-- Description Card --}}
                @if(!empty($attribute_group->description))
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ti ti-align-left"></i> Description</h5>
                    </div>
                    <div class="card-body">
                        <p>{{ $attribute_group->description }}</p>
                    </div>
                </div>
                @endif

                {{-- Attributes in this Group Card --}}
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ti ti-list"></i> Attributes in this Group 
                            <span class="badge bg-info ms-1">{{ $attribute_group->attributes->count() ?? 0 }}</span>
                        </h5>
                    </div>
                    <div class="card-body">
                        @if(isset($attribute_group->attributes) && $attribute_group->attributes->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Type</th>
                                            <th>Required</th>
                                            <th>Filterable</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($attribute_group->attributes as $attribute)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    @if($attribute->icon)
                                                        <i class="{{ $attribute->icon }} text-primary"></i>
                                                    @else
                                                        <i class="ti ti-input text-primary"></i>
                                                    @endif
                                                    <a href="{{ route('admin.attributes.show', $attribute->id) }}" class="text-decoration-none">
                                                        {{ $attribute->name }}
                                                    </a>
                                                </div>
                                                <div class="small text-muted">{{ $attribute->slug }}</div>
                                            </td>
                                            <td><span class="badge bg-info">{{ $attribute->type_label ?? $attribute->type }}</span></td>
                                            <td>
                                                @if($attribute->is_required)
                                                    <i class="ti ti-check-circle text-success"></i> Yes
                                                @else
                                                    <i class="ti ti-circle-x text-danger"></i> No
                                                @endif
                                            </td>
                                            <td>
                                                @if($attribute->is_filterable)
                                                    <i class="ti ti-check-circle text-success"></i> Yes
                                                @else
                                                    <i class="ti ti-circle-x text-danger"></i> No
                                                @endif
                                            </td>
                                            <td>
                                                @if($attribute->status)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-danger">Inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('admin.attributes.show', $attribute->id) }}" class="btn btn-info" title="View">
                                                        <i class="ti ti-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.attributes.edit', $attribute->id) }}" class="btn btn-primary" title="Edit">
                                                        <i class="ti ti-edit"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="ti ti-list-off" style="font-size: 48px; opacity: 0.5;"></i>
                                <p class="text-muted mt-2">No attributes in this group yet.</p>
                                <a href="{{ route('admin.attributes.create') }}?group_id={{ $attribute_group->id }}" class="btn btn-sm btn-primary">
                                    <i class="ti ti-plus"></i> Add Attribute to this Group
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Associated Categories Card --}}
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ti ti-folder"></i> Associated Categories 
                            <span class="badge bg-info ms-1">{{ $attribute_group->categories->count() ?? 0 }}</span>
                        </h5>
                    </div>
                    <div class="card-body">
                        @if(isset($attribute_group->categories) && $attribute_group->categories->count() > 0)
                            <div class="row">
                                @foreach($attribute_group->categories as $category)
                                    <div class="col-md-6 mb-2">
                                        <div class="d-flex align-items-center p-2 bg-light rounded">
                                            <i class="ti ti-folder text-primary me-2"></i>
                                            <a href="{{ route('admin.categories.show', $category->id) }}" class="text-decoration-none">
                                                {{ $category->name }}
                                            </a>
                                            @if($category->parent)
                                                <small class="text-muted ms-2">({{ $category->parent->name }})</small>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="ti ti-folder-off" style="font-size: 48px; opacity: 0.5;"></i>
                                <p class="text-muted mt-2">No categories associated with this group.</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="card">
                    <div class="card-footer d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.attribute-groups.index') }}" class="btn btn-secondary">
                            <i class="ti ti-arrow-left me-1"></i> Back
                        </a>
                        @can('edit_attribute_groups')
                            <a href="{{ route('admin.attribute-groups.edit', $attribute_group->id) }}" class="btn btn-primary">
                                <i class="ti ti-edit me-1"></i> Edit Group
                            </a>
                        @endcan
                        @if(isset($attribute_group->approval_status) && $attribute_group->approval_status === 'pending' && auth()->guard('admin')->user()->can('edit_attribute_groups'))
                            <button type="button" class="btn btn-info" onclick="approveGroup({{ $attribute_group->id }})">
                                <i class="ti ti-check"></i> Approve
                            </button>
                            <button type="button" class="btn btn-warning" onclick="showRejectModal({{ $attribute_group->id }})">
                                <i class="ti ti-x"></i> Reject
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function approveGroup(groupId) {
    Swal.fire({
        title: 'Approve Group?',
        text: 'Are you sure you want to approve this attribute group?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, approve it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '{{ url("admin/attribute-groups/requests") }}/' + groupId + '/approve',
                type: 'POST',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Approved!',
                            text: response.message,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: xhr.responseJSON?.message || 'Failed to approve group.',
                        confirmButtonColor: '#d33'
                    });
                }
            });
        }
    });
}

function showRejectModal(groupId) {
    Swal.fire({
        title: 'Reject Group',
        html: '<textarea id="rejectionReason" class="swal2-textarea" placeholder="Please provide a reason for rejection..." rows="3"></textarea>',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, reject it!',
        preConfirm: () => {
            const reason = document.getElementById('rejectionReason').value;
            if (!reason) {
                Swal.showValidationMessage('Please provide a rejection reason');
                return false;
            }
            return { reason: reason };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '{{ url("admin/attribute-groups/requests") }}/' + groupId + '/reject',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    rejection_reason: result.value.reason
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Rejected!',
                            text: response.message,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: xhr.responseJSON?.message || 'Failed to reject group.',
                        confirmButtonColor: '#d33'
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
    .btn-group-sm .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
</style>
@endpush