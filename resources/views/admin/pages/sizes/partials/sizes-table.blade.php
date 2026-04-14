{{-- resources/views/admin/pages/sizes/partials/sizes-table.blade.php --}}
<div class="table-responsive">
    <table class="table table-hover text-nowrap mb-0">
        <thead class="bg-dark-subtle">
            <tr>
                <th class="ps-3" style="width: 50px;">
                    <input type="checkbox" class="form-check-input" id="selectAll">
                </th>
                <th>ID</th>
                <th>Size</th>
                <th>Code</th>
                <th>Gender</th>
                <th>US/UK/EU</th>
                <th>Measurements</th>
                <th>Usage</th>
                <th>Products</th>
                <th>Status</th>
                <th>Approval</th>
                <th class="text-center" style="width: 180px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($sizes as $size)
                @php
                    $approvalBadge =
                        [
                            'approved' => '<span class="badge bg-success"><i class="ti ti-check"></i> Approved</span>',
                            'pending' => '<span class="badge bg-warning"><i class="ti ti-clock"></i> Pending</span>',
                            'rejected' => '<span class="badge bg-danger"><i class="ti ti-x"></i> Rejected</span>',
                        ][$size->approval_status] ?? '<span class="badge bg-secondary">Unknown</span>';

                    $genderColors = [
                        'Men' => 'primary',
                        'Women' => 'danger',
                        'Unisex' => 'info',
                        'Kids' => 'success',
                    ];
                    $genderColor = $genderColors[$size->gender] ?? 'secondary';

                    // Build size conversion display
                    $conversions = [];
                    if ($size->us_size) {
                        $conversions[] = "US: {$size->us_size}";
                    }
                    if ($size->uk_size) {
                        $conversions[] = "UK: {$size->uk_size}";
                    }
                    if ($size->eu_size) {
                        $conversions[] = "EU: {$size->eu_size}";
                    }
                    $conversionText = !empty($conversions) ? implode(' | ', $conversions) : 'N/A';

                    // Build measurements display
                    $measurements = [];
                    if ($size->chest) {
                        $measurements[] = "Chest: {$size->chest}\"";
                    }
                    if ($size->waist) {
                        $measurements[] = "Waist: {$size->waist}\"";
                    }
                    if ($size->hip) {
                        $measurements[] = "Hip: {$size->hip}\"";
                    }
                    if ($size->inseam) {
                        $measurements[] = "Inseam: {$size->inseam}\"";
                    }
                    $measurementText = !empty($measurements) ? implode(', ', $measurements) : '—';
                @endphp
                <tr>
                    <td class="ps-3">
                        <input type="checkbox" class="form-check-input size-checkbox" value="{{ $size->id }}">
                    </td>
                    <td>#{{ $size->id }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            @if ($size->image)
                                <img src="{{ asset('storage/sizes/' . $size->image) }}" alt="{{ $size->name }}"
                                    class="rounded" style="width: 35px; height: 35px; object-fit: cover;">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                    style="width: 35px; height: 35px;">
                                    <i class="ti ti-ruler text-primary"></i>
                                </div>
                            @endif
                            <div>
                                <span class="fw-semibold">{{ $size->name }}</span>
                                <div class="small text-muted">{{ $size->slug }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <code>{{ $size->code }}</code>
                    </td>
                    <td>
                        <span class="badge bg-{{ $genderColor }}">{{ $size->gender }}</span>
                    </td>
                    <td>
                        <div class="small">
                            {{ $conversionText }}
                        </div>
                        @if ($size->int_size)
                            <div class="small text-muted">Int: {{ $size->int_size }}</div>
                        @endif
                    </td>
                    <td>
                        <div class="small text-muted" title="{{ $measurementText }}">
                            {{ Str::limit($measurementText, 30) }}
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-secondary">{{ number_format($size->usage_count) }} products</span>
                    </td>
                    <td>
                        <span class="fw-semibold">{{ number_format($size->product_count ?? 0) }}</span>
                    </td>
                    <td>
                        <div class="form-check form-switch">
                            <input type="checkbox" class="form-check-input toggle-status" data-id="{{ $size->id }}"
                                {{ $size->status ? 'checked' : '' }}>
                        </div>
                        @if ($size->is_featured)
                            <span class="badge bg-warning mt-1 d-block" style="font-size: 10px;">
                                <i class="ti ti-star"></i> Featured
                            </span>
                        @endif
                        @if ($size->is_popular)
                            <span class="badge bg-danger mt-1 d-block" style="font-size: 10px;">
                                <i class="ti ti-fire"></i> Popular
                            </span>
                        @endif
                    </td>
                    <td>
                        {!! $approvalBadge !!}
                        @if ($size->requested_by && $size->approval_status === 'pending')
                            <div class="small text-muted mt-1">
                                Requested by: Vendor #{{ $size->requested_by }}
                            </div>
                        @endif
                        @if ($size->rejection_reason)
                            <div class="small text-danger mt-1" title="{{ $size->rejection_reason }}">
                                <i class="ti ti-alert-circle"></i> {{ Str::limit($size->rejection_reason, 30) }}
                            </div>
                        @endif
                    </td>
                    <td class="pe-3 text-center">
                        <div class="hstack gap-1 justify-content-center">
                            @php $admin = auth()->guard('admin')->user(); @endphp

                            @if ($admin->can('view_sizes'))
                                <a href="{{ route('admin.sizes.show', $size->id) }}"
                                    class="btn btn-soft-primary btn-icon btn-sm rounded-circle" data-bs-toggle="tooltip"
                                    title="View Details">
                                    <i class="ti ti-eye"></i>
                                </a>
                            @endif

                            @if ($admin->can('edit_sizes'))
                                @if ($size->status)
                                    <button type="button" class="btn btn-soft-warning btn-icon btn-sm rounded-circle"
                                        onclick="toggleStatus({{ $size->id }})" data-bs-toggle="tooltip"
                                        title="Deactivate">
                                        <i class="ti ti-circle-x"></i>
                                    </button>
                                @else
                                    <button type="button" class="btn btn-soft-success btn-icon btn-sm rounded-circle"
                                        onclick="toggleStatus({{ $size->id }})" data-bs-toggle="tooltip"
                                        title="Activate">
                                        <i class="ti ti-circle-check"></i>
                                    </button>
                                @endif

                                <a href="{{ route('admin.sizes.edit', $size->id) }}"
                                    class="btn btn-soft-success btn-icon btn-sm rounded-circle" data-bs-toggle="tooltip"
                                    title="Edit Size">
                                    <i class="ti ti-edit"></i>
                                </a>

                                @if ($size->approval_status === 'pending')
                                    <button type="button" class="btn btn-soft-info btn-icon btn-sm rounded-circle"
                                        onclick="approveSize({{ $size->id }}, '{{ $size->name }}')"
                                        data-bs-toggle="tooltip" title="Approve">
                                        <i class="ti ti-check"></i>
                                    </button>
                                    <button type="button" class="btn btn-soft-warning btn-icon btn-sm rounded-circle"
                                        onclick="showRejectModal({{ $size->id }}, '{{ $size->name }}')"
                                        data-bs-toggle="tooltip" title="Reject">
                                        <i class="ti ti-x"></i>
                                    </button>
                                @endif
                            @endif

                            @if ($admin->can('delete_sizes') && $size->products()->count() == 0)
                                <button type="button" class="btn btn-soft-danger btn-icon btn-sm rounded-circle"
                                    onclick="confirmDelete({{ $size->id }})" data-bs-toggle="tooltip"
                                    title="Delete Size">
                                    <i class="ti ti-trash"></i>
                                </button>
                            @elseif($size->products()->count() > 0)
                                <button type="button" class="btn btn-soft-secondary btn-icon btn-sm rounded-circle"
                                    disabled data-bs-toggle="tooltip"
                                    title="Cannot delete - has {{ $size->products()->count() }} products">
                                    <i class="ti ti-lock"></i>
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="12" class="text-center py-5">
                        <div class="empty-state">
                            <i class="ti ti-ruler-off" style="font-size: 48px; opacity: 0.5;"></i>
                            <h5 class="mt-3">No Sizes Found</h5>
                            <p class="text-muted">Get started by creating a new size.</p>
                            @can('create_sizes')
                                <a href="{{ route('admin.sizes.create') }}" class="btn btn-primary mt-2">
                                    <i class="ti ti-plus me-1"></i> Add New Size
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
        // Approve Size
        function approveSize(sizeId, sizeName) {
            Swal.fire({
                title: 'Approve Size?',
                text: `Are you sure you want to approve "${sizeName}"?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, approve it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ url('admin/sizes/requests') }}/' + sizeId + '/approve',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
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
                                text: xhr.responseJSON?.message || 'Failed to approve size.',
                                confirmButtonColor: '#d33'
                            });
                        }
                    });
                }
            });
        }

        // Show Reject Modal
        function showRejectModal(sizeId, sizeName) {
            Swal.fire({
                title: 'Reject Size',
                html: `
            <p>Are you sure you want to reject <strong>"${sizeName}"</strong>?</p>
            <textarea id="rejectionReason" class="swal2-textarea" placeholder="Please provide a rejection reason..." rows="3"></textarea>
        `,
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
                    return {
                        reason: reason
                    };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ url('admin/sizes/requests') }}/' + sizeId + '/reject',
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
                                text: xhr.responseJSON?.message || 'Failed to reject size.',
                                confirmButtonColor: '#d33'
                            });
                        }
                    });
                }
            });
        }

        // Toggle Status function (if not defined in index)
        function toggleStatus(sizeId) {
            $.ajax({
                url: '{{ url('admin/sizes') }}/' + sizeId + '/toggle-status',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Updated!',
                            text: response.message,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Failed to update status.',
                        confirmButtonColor: '#d33'
                    });
                }
            });
        }

        // Confirm Delete function (if not defined in index)
        function confirmDelete(sizeId) {
            Swal.fire({
                title: 'Delete Size?',
                text: "Are you sure you want to delete this size?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ url('admin/sizes') }}/' + sizeId,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: response.message,
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Cannot Delete!',
                                    text: response.message,
                                    confirmButtonColor: '#d33'
                                });
                            }
                        }
                    });
                }
            });
        }
    </script>
@endpush
