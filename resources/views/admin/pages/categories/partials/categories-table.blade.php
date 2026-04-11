{{-- resources/views/admin/pages/categories/partials/categories-table.blade.php --}}
<div class="table-responsive">
    <table class="table table-hover text-nowrap mb-0">
        <thead class="bg-dark-subtle">
            <tr>
                <th class="ps-3" style="width: 50px;">
                    <input type="checkbox" class="form-check-input" id="selectAll">
                </th>
                <th>ID</th>
                <th>Category</th>
                <th>Parent Category</th>
                <th>Path/Breadcrumb</th>
                <th>Products</th>
                <th>Orders</th>
                <th>Revenue</th>
                <th>Views</th>
                <th>Status</th>
                <th>Approval</th>
                <th class="text-center" style="width: 220px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($categories as $category)
                @php
                    $indent = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $category->depth ?? 0);
                    $imagePath = null;

                    if (
                        $category->thumbnail_image &&
                        Storage::disk('public')->exists('categories/thumbnails/' . $category->thumbnail_image)
                    ) {
                        $imagePath = Storage::disk('public')->url(
                            'categories/thumbnails/' . $category->thumbnail_image,
                        );
                    } elseif ($category->image && Storage::disk('public')->exists('categories/' . $category->image)) {
                        $imagePath = Storage::disk('public')->url('categories/' . $category->image);
                    }

                    $approvalBadge =
                        [
                            'approved' => '<span class="badge bg-success"><i class="ti ti-check"></i> Approved</span>',
                            'pending' => '<span class="badge bg-warning"><i class="ti ti-clock"></i> Pending</span>',
                            'rejected' => '<span class="badge bg-danger"><i class="ti ti-x"></i> Rejected</span>',
                        ][$category->approval_status] ?? '<span class="badge bg-secondary">Unknown</span>';

                    $statusBadge = $category->status
                        ? '<span class="badge bg-success"><i class="ti ti-circle-check"></i> Active</span>'
                        : '<span class="badge bg-danger"><i class="ti ti-circle-x"></i> Inactive</span>';
                @endphp
                <tr>
                    <td class="ps-3">
                        <input type="checkbox" class="form-check-input category-checkbox" value="{{ $category->id }}">
                    </td>
                    <td>#{{ $category->id }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            @if ($imagePath)
                                <img src="{{ $imagePath }}" alt="{{ $category->name }}" class="rounded"
                                    style="width: 40px; height: 40px; object-fit: cover;">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                    style="width: 40px; height: 40px;">
                                    @if (($category->depth ?? 0) > 0)
                                        <i class="ti ti-subdirectory text-secondary"></i>
                                    @else
                                        <i class="ti ti-folder text-primary"></i>
                                    @endif
                                </div>
                            @endif
                            <div>
                                <span class="fw-semibold">
                                    @if (($category->depth ?? 0) > 0)
                                        <i class="ti ti-corner-down-right text-muted me-1" style="font-size: 12px;"></i>
                                    @endif
                                    {!! $indent !!}{{ $category->name }}
                                </span>
                                <div class="small text-muted">{{ $category->slug }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        @if ($category->parent)
                            <a href="{{ route('admin.categories.show', $category->parent->id) }}"
                                class="text-decoration-none">
                                <i class="ti ti-arrow-narrow-up me-1 text-success"></i>
                                {{ $category->parent->name }}
                            </a>
                            <br>
                            <small class="text-muted">ID: #{{ $category->parent->id }}</small>
                        @else
                            <span class="badge bg-secondary-subtle text-secondary">
                                <i class="ti ti-home"></i> Main Category
                            </span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex flex-column">
                            <span class="text-muted small" title="{{ $category->path }}">
                                <i class="ti ti-link me-1"></i>
                                {{ Str::limit($category->path, 40) }}
                            </span>
                            @if (($category->depth ?? 0) > 0)
                                <span class="badge bg-info-subtle text-info mt-1" style="font-size: 10px;">
                                    Level {{ $category->depth }}
                                </span>
                            @endif
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-primary-subtle text-primary p-2">
                            <i class="ti ti-package me-1"></i> {{ number_format($category->product_count) }}
                        </span>
                    </td>
                    <td>
                        <span class="badge bg-success-subtle text-success p-2">
                            <i class="ti ti-shopping-cart me-1"></i> {{ number_format($category->order_count) }}
                        </span>
                    </td>
                    <td class="text-success fw-semibold">
                        ${{ number_format($category->total_revenue, 2) }}
                    </td>
                    <td>
                        <div class="d-flex flex-column">
                            <span class="fw-semibold">{{ number_format($category->view_count) }}</span>
                            @if ($category->last_viewed_at)
                                <small class="text-muted">{{ $category->last_viewed_at->diffForHumans() }}</small>
                            @endif
                        </div>
                    </td>
                    <td>
                        <div class="form-check form-switch">
                            <input type="checkbox" class="form-check-input toggle-status" data-id="{{ $category->id }}"
                                {{ $category->status ? 'checked' : '' }}>
                        </div>
                        @if ($category->is_featured)
                            <span class="badge bg-warning-subtle text-warning mt-1 d-block" style="font-size: 10px;">
                                <i class="ti ti-star"></i> Featured
                            </span>
                        @endif
                        @if ($category->is_popular)
                            <span class="badge bg-danger-subtle text-danger mt-1 d-block" style="font-size: 10px;">
                                <i class="ti ti-fire"></i> Popular
                            </span>
                        @endif
                    </td>
                    <td>
                        {!! $approvalBadge !!}
                        @if ($category->requested_by && $category->approval_status === 'pending')
                            <div class="small text-muted mt-1">
                                Requested by: Vendor #{{ $category->requested_by }}
                            </div>
                        @endif
                        @if ($category->rejection_reason)
                            <div class="small text-danger mt-1" title="{{ $category->rejection_reason }}">
                                <i class="ti ti-alert-circle"></i> {{ Str::limit($category->rejection_reason, 30) }}
                            </div>
                        @endif
                    </td>
                    <td class="pe-3 text-center">
                        <div class="hstack gap-1 justify-content-center">
                            @php $admin = auth()->guard('admin')->user(); @endphp

                            <!-- View Button -->
                            @if ($admin->can('view_categories'))
                                <a href="{{ route('admin.categories.show', $category->id) }}"
                                    class="btn btn-soft-primary btn-icon btn-sm rounded-circle" data-bs-toggle="tooltip"
                                    title="View Details">
                                    <i class="ti ti-eye"></i>
                                </a>
                            @endif

                            <!-- Activate/Deactivate Button -->
                            @if ($admin->can('edit_categories'))
                                @if ($category->status)
                                    <button type="button" class="btn btn-soft-warning btn-icon btn-sm rounded-circle"
                                        onclick="toggleCategoryStatus({{ $category->id }}, 'deactivate', '{{ $category->name }}')"
                                        data-bs-toggle="tooltip" title="Deactivate Category">
                                        <i class="ti ti-circle-x"></i>
                                    </button>
                                @else
                                    <button type="button" class="btn btn-soft-success btn-icon btn-sm rounded-circle"
                                        onclick="toggleCategoryStatus({{ $category->id }}, 'activate', '{{ $category->name }}')"
                                        data-bs-toggle="tooltip" title="Activate Category">
                                        <i class="ti ti-circle-check"></i>
                                    </button>
                                @endif
                            @endif

                            <!-- Edit Button -->
                            @if ($admin->can('edit_categories'))
                                <a href="{{ route('admin.categories.edit', $category->id) }}"
                                    class="btn btn-soft-success btn-icon btn-sm rounded-circle" data-bs-toggle="tooltip"
                                    title="Edit Category">
                                    <i class="ti ti-edit"></i>
                                </a>
                            @endif

                            <!-- Approve Button (only for pending categories) -->
                            @if ($admin->can('edit_categories'))
                                @if ($category->approval_status === 'pending')
                                    <button type="button" class="btn btn-soft-info btn-icon btn-sm rounded-circle"
                                        onclick="approveCategory({{ $category->id }}, '{{ $category->name }}')"
                                        data-bs-toggle="tooltip" title="Approve Category">
                                        <i class="ti ti-check"></i>
                                    </button>
                                    <button type="button" class="btn btn-soft-warning btn-icon btn-sm rounded-circle"
                                        onclick="showRejectModal({{ $category->id }}, '{{ $category->name }}')"
                                        data-bs-toggle="tooltip" title="Reject Category">
                                        <i class="ti ti-x"></i>
                                    </button>
                                @endif
                            @endif

                            <!-- Delete Button (only if no children and no products) -->
                            @if ($category->children->count() == 0 && $category->product_count == 0)
                                @if ($admin->can('delete_categories'))
                                    <button type="button" class="btn btn-soft-danger btn-icon btn-sm rounded-circle"
                                        onclick="confirmDelete({{ $category->id }})" data-bs-toggle="tooltip"
                                        title="Delete Category">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                @endif
                            @else
                                <button type="button" class="btn btn-soft-secondary btn-icon btn-sm rounded-circle"
                                    disabled data-bs-toggle="tooltip"
                                    title="Cannot delete - has {{ $category->children->count() }} subcategories and {{ $category->product_count }} products">
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
                            <i class="ti ti-folder-off" style="font-size: 48px; opacity: 0.5;"></i>
                            <h5 class="mt-3">No Categories Found</h5>
                            <p class="text-muted">Get started by creating a new category.</p>
                            @php $admin = auth()->guard('admin')->user(); @endphp
                            @if ($admin->can('create_categories'))
                                <a href="{{ route('admin.categories.create') }}" class="btn btn-primary mt-2">
                                    <i class="ti ti-plus me-1"></i> Add New Category
                                </a>
                            @endif
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@push('scripts')
    <script>
        // Toggle Category Status (Activate/Deactivate)
        function toggleCategoryStatus(categoryId, action, categoryName) {
            let actionText = action === 'activate' ? 'activate' : 'deactivate';
            let confirmColor = action === 'activate' ? '#28a745' : '#dc3545';

            Swal.fire({
                title: `${actionText.toUpperCase()} Category?`,
                text: `Are you sure you want to ${actionText} "${categoryName}"?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: confirmColor,
                cancelButtonColor: '#6c757d',
                confirmButtonText: `Yes, ${actionText} it!`
            }).then((result) => {
                if (result.isConfirmed) {
                    // Use the existing toggle-status endpoint
                    $.ajax({
                        url: '{{ url('admin/categories') }}/' + categoryId + '/toggle-status',
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
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: xhr.responseJSON?.message || 'Failed to update status.',
                                confirmButtonColor: '#d33'
                            });
                        }
                    });
                }
            });
        }

        // Approve Category
        function approveCategory(categoryId, categoryName) {
            Swal.fire({
                title: 'Approve Category?',
                text: `Are you sure you want to approve "${categoryName}"?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, approve it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ url('admin/categories') }}/' + categoryId + '/approve',
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
                                text: xhr.responseJSON?.message ||
                                    'Failed to approve category.',
                                confirmButtonColor: '#d33'
                            });
                        }
                    });
                }
            });
        }

        // Show Reject Modal
        function showRejectModal(categoryId, categoryName) {
            Swal.fire({
                title: 'Reject Category',
                html: `
                    <p>Are you sure you want to reject "${categoryName}"?</p>
                    <textarea id="rejectionReason" class="swal2-textarea" placeholder="Please provide a reason for rejection..." rows="3"></textarea>
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
                        url: '{{ url('admin/categories') }}/' + categoryId + '/reject',
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
                                text: xhr.responseJSON?.message || 'Failed to reject category.',
                                confirmButtonColor: '#d33'
                            });
                        }
                    });
                }
            });
        }
    </script>
@endpush
