{{-- resources/views/admin/pages/colors/partials/colors-table.blade.php --}}
<div class="table-responsive">
    <table class="table table-hover text-nowrap mb-0">
        <thead class="bg-dark-subtle">
            <tr>
                <th class="ps-3" style="width: 50px;">
                    <input type="checkbox" class="form-check-input" id="selectAll">
                </th>
                <th>ID</th>
                <th>Color</th>
                <th>Name</th>
                <th>Hex Code</th>
                <th>RGB</th>
                <th>Usage</th>
                <th>Products</th>
                <th>Views</th>
                <th>Status</th>
                <th>Approval</th>
                <th class="text-center" style="width: 150px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($colors as $color)
                @php
                    $approvalBadge =
                        [
                            'approved' => '<span class="badge bg-success"><i class="ti ti-check"></i> Approved</span>',
                            'pending' => '<span class="badge bg-warning"><i class="ti ti-clock"></i> Pending</span>',
                            'rejected' => '<span class="badge bg-danger"><i class="ti ti-x"></i> Rejected</span>',
                        ][$color->approval_status] ?? '<span class="badge bg-secondary">Unknown</span>';
                @endphp
                <tr>
                    <td class="ps-3">
                        <input type="checkbox" class="form-check-input color-checkbox" value="{{ $color->id }}">
                    </td>
                    <td>#{{ $color->id }}</td>
                    <td>
                        <div class="color-preview" style="background-color: {{ $color->code }};"></div>
                    </td>
                    <td>
                        <div class="fw-semibold">{{ $color->name }}</div>
                        <div class="small text-muted">{{ $color->slug }}</div>
                    </td>
                    <td>
                        <code>{{ $color->code }}</code>
                    </td>
                    <td>
                        <small class="text-muted">{{ $color->rgb ?? 'N/A' }}</small>
                    </td>
                    <td>
                        <span class="badge bg-secondary">{{ number_format($color->usage_count) }} products</span>
                    </td>
                    <td>
                        <span class="fw-semibold">{{ number_format($color->product_count ?? 0) }}</span>
                    </td>
                    <td>
                        {{ number_format($color->view_count ?? 0) }}
                    </td>
                    <td>
                        <div class="form-check form-switch">
                            <input type="checkbox" class="form-check-input toggle-status" data-id="{{ $color->id }}"
                                {{ $color->status ? 'checked' : '' }}>
                        </div>
                        @if ($color->is_featured)
                            <span class="badge bg-warning mt-1 d-block" style="font-size: 10px;">
                                <i class="ti ti-star"></i> Featured
                            </span>
                        @endif
                        @if ($color->is_popular)
                            <span class="badge bg-danger mt-1 d-block" style="font-size: 10px;">
                                <i class="ti ti-fire"></i> Popular
                            </span>
                        @endif
                    </td>
                    <td>
                        {!! $approvalBadge !!}
                        @if ($color->requested_by && $color->approval_status === 'pending')
                            <div class="small text-muted mt-1">
                                Requested by: Vendor #{{ $color->requested_by }}
                            </div>
                        @endif
                        @if ($color->rejection_reason)
                            <div class="small text-danger mt-1" title="{{ $color->rejection_reason }}">
                                <i class="ti ti-alert-circle"></i> {{ Str::limit($color->rejection_reason, 30) }}
                            </div>
                        @endif
                    </td>
                    <td class="pe-3 text-center">
                        <div class="hstack gap-1 justify-content-center">
                            @php $admin = auth()->guard('admin')->user(); @endphp

                            @if ($admin->can('view_colors'))
                                <a href="{{ route('admin.colors.show', $color->id) }}"
                                    class="btn btn-soft-primary btn-icon btn-sm rounded-circle" data-bs-toggle="tooltip"
                                    title="View Details">
                                    <i class="ti ti-eye"></i>
                                </a>
                            @endif

                            @if ($admin->can('edit_colors'))
                                @if ($color->status)
                                    <button type="button" class="btn btn-soft-warning btn-icon btn-sm rounded-circle"
                                        onclick="toggleStatus({{ $color->id }})" data-bs-toggle="tooltip"
                                        title="Deactivate">
                                        <i class="ti ti-circle-x"></i>
                                    </button>
                                @else
                                    <button type="button" class="btn btn-soft-success btn-icon btn-sm rounded-circle"
                                        onclick="toggleStatus({{ $color->id }})" data-bs-toggle="tooltip"
                                        title="Activate">
                                        <i class="ti ti-circle-check"></i>
                                    </button>
                                @endif

                                <a href="{{ route('admin.colors.edit', $color->id) }}"
                                    class="btn btn-soft-success btn-icon btn-sm rounded-circle" data-bs-toggle="tooltip"
                                    title="Edit">
                                    <i class="ti ti-edit"></i>
                                </a>

                                @if ($color->approval_status === 'pending')
                                    <button type="button" class="btn btn-soft-info btn-icon btn-sm rounded-circle"
                                        onclick="approveColor({{ $color->id }}, '{{ $color->name }}')"
                                        data-bs-toggle="tooltip" title="Approve">
                                        <i class="ti ti-check"></i>
                                    </button>
                                    <button type="button" class="btn btn-soft-warning btn-icon btn-sm rounded-circle"
                                        onclick="showRejectModal({{ $color->id }}, '{{ $color->name }}')"
                                        data-bs-toggle="tooltip" title="Reject">
                                        <i class="ti ti-x"></i>
                                    </button>
                                @endif
                            @endif

                            @if ($admin->can('delete_colors') && $color->products()->count() == 0)
                                <button type="button" class="btn btn-soft-danger btn-icon btn-sm rounded-circle"
                                    onclick="confirmDelete({{ $color->id }})" data-bs-toggle="tooltip"
                                    title="Delete">
                                    <i class="ti ti-trash"></i>
                                </button>
                            @elseif($color->products()->count() > 0)
                                <button type="button" class="btn btn-soft-secondary btn-icon btn-sm rounded-circle"
                                    disabled data-bs-toggle="tooltip"
                                    title="Cannot delete - has {{ $color->products()->count() }} products">
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
                            <i class="ti ti-palette-off" style="font-size: 48px; opacity: 0.5;"></i>
                            <h5 class="mt-3">No Colors Found</h5>
                            <p class="text-muted">Get started by creating a new color.</p>
                            @can('create_colors')
                                <a href="{{ route('admin.colors.create') }}" class="btn btn-primary mt-2">
                                    <i class="ti ti-plus me-1"></i> Add New Color
                                </a>
                            @endcan
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
