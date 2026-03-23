{{-- resources/views/admin/seasons/partials/seasons-table.blade.php --}}
<div class="table-responsive">
    <table class="table table-hover text-nowrap mb-0">
        <thead class="bg-dark-subtle">
            32
            <th class="ps-3" style="width: 50px;">
                <input type="checkbox" class="form-check-input" id="selectAll">
            </th>
            <th>ID</th>
            <th>Season</th>
            <th>Code</th>
            <th>Date Range</th>
            <th>Products</th>
            <th>Orders</th>
            <th>Revenue</th>
            <th>Views</th>
            <th>Rating</th>
            <th>Status</th>
            <th>Current</th>
            <th class="text-center" style="width: 120px;">Action</th>
        </thead>
        <tbody>
            @forelse($seasons as $season)
                @php
                    $imagePath = null;
                    if ($season->image && Storage::disk('public')->exists('seasons/' . $season->image)) {
                        $imagePath = Storage::disk('public')->url('seasons/' . $season->image);
                    }

                    $isActive = $season->isActive();
                    $statusBadge = $isActive ? 'success' : ($season->status ? 'warning' : 'danger');
                    $statusText = $isActive ? 'Active' : ($season->status ? 'Scheduled' : 'Inactive');
                @endphp
                <tr data-id="{{ $season->id }}">
                    <td class="ps-3">
                        <input type="checkbox" class="form-check-input season-checkbox" value="{{ $season->id }}">
                    </td>
                    <td>
                        <span class="fw-semibold">#{{ $season->id }}</span>
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            @if ($imagePath)
                                <img src="{{ $imagePath }}" alt="{{ $season->name }}" class="rounded"
                                    style="width: 40px; height: 40px; object-fit: cover;">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                    style="width: 40px; height: 40px;">
                                    @if ($season->icon)
                                        <i class="ti ti-{{ $season->icon }} text-primary"></i>
                                    @else
                                        <i class="ti ti-calendar text-primary"></i>
                                    @endif
                                </div>
                            @endif
                            <div>
                                <span class="fw-semibold">{{ $season->name }}</span>
                                <div class="small text-muted">{{ $season->slug }}</div>
                                @if ($season->icon)
                                    <small class="text-muted"><i class="ti ti-{{ $season->icon }}"></i>
                                        {{ $season->icon }}</small>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-secondary-subtle text-secondary">
                            <i class="ti ti-barcode"></i> {{ $season->code }}
                        </span>
                    </td>
                    <td>
                        @if ($season->start_date || $season->end_date)
                            <div class="small">
                                @if ($season->start_date)
                                    <i class="ti ti-calendar-start me-1"></i>
                                    {{ $season->start_date->format('d M Y') }}
                                @endif
                                @if ($season->end_date)
                                    <br><i class="ti ti-calendar-end me-1"></i>
                                    {{ $season->end_date->format('d M Y') }}
                                @endif
                            </div>
                        @else
                            <span class="text-muted">No date range</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge bg-primary-subtle text-primary p-2">
                            <i class="ti ti-package me-1"></i> {{ number_format($season->product_count) }}
                        </span>
                    </td>
                    <td>
                        <span class="badge bg-success-subtle text-success p-2">
                            <i class="ti ti-shopping-cart me-1"></i> {{ number_format($season->order_count) }}
                        </span>
                    </td>
                    <td class="text-success fw-semibold">
                        ${{ number_format($season->total_revenue, 2) }}
                    </td>
                    <td>
                        <div class="d-flex flex-column">
                            <span class="fw-semibold">{{ number_format($season->view_count) }}</span>
                            <small class="text-muted">{{ $season->updated_at->diffForHumans() }}</small>
                        </div>
                    </td>
                    <td>
                        @if ($season->avg_rating > 0)
                            <div class="d-flex align-items-center">
                                <span class="text-warning me-1">{{ number_format($season->avg_rating, 1) }}</span>
                                <i class="ti ti-star text-warning"></i>
                                <small class="text-muted ms-1">({{ $season->review_count }})</small>
                            </div>
                        @else
                            <span class="text-muted">No ratings</span>
                        @endif
                    </td>
                    <td>
                        <div class="form-check form-switch">
                            <input type="checkbox" class="form-check-input toggle-status" data-id="{{ $season->id }}"
                                {{ $season->status ? 'checked' : '' }}>
                        </div>
                        <small class="text-muted">{{ $statusText }}</small>
                    </td>
                    <td>
                        @if ($season->is_current)
                            <span class="badge bg-warning current-badge">
                                <i class="ti ti-star"></i> Current
                            </span>
                        @else
                            <button type="button" class="btn btn-sm btn-outline-primary set-current"
                                data-id="{{ $season->id }}" onclick="setCurrent({{ $season->id }})"
                                data-bs-toggle="tooltip" title="Set as Current Season">
                                <i class="ti ti-star"></i> Set
                            </button>
                        @endif
                    </td>
                    <td class="pe-3 text-center">
                        <div class="hstack gap-1 justify-content-center">
                            <a href="{{ route('admin.seasons.show', $season->id) }}"
                                class="btn btn-soft-primary btn-icon btn-sm rounded-circle" data-bs-toggle="tooltip"
                                title="View Details">
                                <i class="ti ti-eye"></i>
                            </a>
                            <a href="{{ route('admin.seasons.edit', $season->id) }}"
                                class="btn btn-soft-success btn-icon btn-sm rounded-circle" data-bs-toggle="tooltip"
                                title="Edit Season">
                                <i class="ti ti-edit"></i>
                            </a>
                            @can('delete seasons')
                                @if ($season->product_count == 0)
                                    <button type="button" class="btn btn-soft-danger btn-icon btn-sm rounded-circle"
                                        onclick="confirmDelete({{ $season->id }})" data-bs-toggle="tooltip"
                                        title="Delete Season">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                @else
                                    <button type="button" class="btn btn-soft-secondary btn-icon btn-sm rounded-circle"
                                        disabled data-bs-toggle="tooltip"
                                        title="Cannot delete - has {{ $season->product_count }} products">
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
                            <i class="ti ti-calendar-off" style="font-size: 48px; opacity: 0.5;"></i>
                            <h5 class="mt-3">No Seasons Found</h5>
                            <p class="text-muted">Get started by creating a new season.</p>
                            @can('create seasons')
                                <a href="{{ route('admin.seasons.create') }}" class="btn btn-primary mt-2">
                                    <i class="ti ti-plus me-1"></i> Add New Season
                                </a>
                            @endcan
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
