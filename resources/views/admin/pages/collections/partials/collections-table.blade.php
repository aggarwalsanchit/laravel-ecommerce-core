{{-- resources/views/admin/collections/partials/collections-table.blade.php --}}
<div class="table-responsive">
    <table class="table table-hover text-nowrap mb-0">
        <thead class="bg-dark-subtle">
            32
            <th class="ps-3" style="width: 50px;">
                <input type="checkbox" class="form-check-input" id="selectAll">
            </th>
            <th>ID</th>
            <th>Collection</th>
            <th>Code</th>
            <th>Date Range</th>
            <th>Products</th>
            <th>Orders</th>
            <th>Revenue</th>
            <th>Views</th>
            <th>Rating</th>
            <th>Status</th>
            <th>Featured</th>
            <th class="text-center" style="width: 120px;">Action</th>
        </thead>
        <tbody>
            @forelse($collections as $collection)
                @php
                    $imagePath = null;
                    if ($collection->image && Storage::disk('public')->exists('collections/' . $collection->image)) {
                        $imagePath = Storage::disk('public')->url('collections/' . $collection->image);
                    }

                    $isActive = $collection->isActive();
                    $statusBadge = $isActive ? 'success' : ($collection->status ? 'warning' : 'danger');
                    $statusText = $isActive ? 'Active' : ($collection->status ? 'Scheduled' : 'Inactive');
                @endphp
                <tr data-id="{{ $collection->id }}">
                    <td class="ps-3">
                        <input type="checkbox" class="form-check-input collection-checkbox"
                            value="{{ $collection->id }}">
                    </td>
                    <td>
                        <span class="fw-semibold">#{{ $collection->id }}</span>
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            @if ($imagePath)
                                <img src="{{ $imagePath }}" alt="{{ $collection->name }}" class="rounded"
                                    style="width: 40px; height: 40px; object-fit: cover;">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                    style="width: 40px; height: 40px;">
                                    <i class="ti ti-category text-primary"></i>
                                </div>
                            @endif
                            <div>
                                <span class="fw-semibold">{{ $collection->name }}</span>
                                <div class="small text-muted">{{ $collection->slug }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-secondary-subtle text-secondary">
                            <i class="ti ti-barcode"></i> {{ $collection->code }}
                        </span>
                    </td>
                    <td>
                        @if ($collection->start_date || $collection->end_date)
                            <div class="small">
                                @if ($collection->start_date)
                                    <i class="ti ti-calendar-start me-1"></i>
                                    {{ $collection->start_date->format('d M Y') }}
                                @endif
                                @if ($collection->end_date)
                                    <br><i class="ti ti-calendar-end me-1"></i>
                                    {{ $collection->end_date->format('d M Y') }}
                                @endif
                            </div>
                        @else
                            <span class="text-muted">No date range</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge bg-primary-subtle text-primary p-2">
                            <i class="ti ti-package me-1"></i> {{ number_format($collection->product_count) }}
                        </span>
                    </td>
                    <td>
                        <span class="badge bg-success-subtle text-success p-2">
                            <i class="ti ti-shopping-cart me-1"></i> {{ number_format($collection->order_count) }}
                        </span>
                    </td>
                    <td class="text-success fw-semibold">
                        ${{ number_format($collection->total_revenue, 2) }}
                    </td>
                    <td>
                        <div class="d-flex flex-column">
                            <span class="fw-semibold">{{ number_format($collection->view_count) }}</span>
                            <small class="text-muted">{{ $collection->updated_at->diffForHumans() }}</small>
                        </div>
                    </td>
                    <td>
                        @if ($collection->avg_rating > 0)
                            <div class="d-flex align-items-center">
                                <span class="text-warning me-1">{{ number_format($collection->avg_rating, 1) }}</span>
                                <i class="ti ti-star text-warning"></i>
                                <small class="text-muted ms-1">({{ $collection->review_count }})</small>
                            </div>
                        @else
                            <span class="text-muted">No ratings</span>
                        @endif
                    </td>
                    <td>
                        <div class="form-check form-switch">
                            <input type="checkbox" class="form-check-input toggle-status"
                                data-id="{{ $collection->id }}" {{ $collection->status ? 'checked' : '' }}>
                        </div>
                        <small class="text-muted">{{ $statusText }}</small>
                    </td>
                    <td>
                        <div class="form-check form-switch">
                            <input type="checkbox" class="form-check-input toggle-featured"
                                data-id="{{ $collection->id }}" {{ $collection->is_featured ? 'checked' : '' }}>
                        </div>
                    </td>
                    <td class="pe-3 text-center">
                        <div class="hstack gap-1 justify-content-center">
                            <a href="{{ route('admin.collections.show', $collection->id) }}"
                                class="btn btn-soft-primary btn-icon btn-sm rounded-circle" data-bs-toggle="tooltip"
                                title="View Details">
                                <i class="ti ti-eye"></i>
                            </a>
                            <a href="{{ route('admin.collections.edit', $collection->id) }}"
                                class="btn btn-soft-success btn-icon btn-sm rounded-circle" data-bs-toggle="tooltip"
                                title="Edit Collection">
                                <i class="ti ti-edit"></i>
                            </a>
                            @can('delete collections')
                                @if ($collection->product_count == 0)
                                    <button type="button" class="btn btn-soft-danger btn-icon btn-sm rounded-circle"
                                        onclick="confirmDelete({{ $collection->id }})" data-bs-toggle="tooltip"
                                        title="Delete Collection">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                @else
                                    <button type="button" class="btn btn-soft-secondary btn-icon btn-sm rounded-circle"
                                        disabled data-bs-toggle="tooltip"
                                        title="Cannot delete - has {{ $collection->product_count }} products">
                                        <i class="ti ti-lock"></i>
                                    </button>
                                @endif
                            @endcan
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="13" class="text-center py-5">
                        <div class="empty-state">
                            <i class="ti ti-category-off" style="font-size: 48px; opacity: 0.5;"></i>
                            <h5 class="mt-3">No Collections Found</h5>
                            <p class="text-muted">Get started by creating a new collection.</p>
                            @can('create collections')
                                <a href="{{ route('admin.collections.create') }}" class="btn btn-primary mt-2">
                                    <i class="ti ti-plus me-1"></i> Add New Collection
                                </a>
                            @endcan
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
