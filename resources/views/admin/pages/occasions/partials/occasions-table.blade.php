{{-- resources/views/admin/occasions/partials/occasions-table.blade.php --}}
<div class="table-responsive">
    <table class="table table-hover text-nowrap mb-0">
        <thead class="bg-dark-subtle">
            <tr>
                <th class="ps-3" style="width: 50px;">
                    <input type="checkbox" class="form-check-input" id="selectAll">
                </th>
                <th>ID</th>
                <th>Occasion</th>
                <th>Code</th>
                <th>Products</th>
                <th>Orders</th>
                <th>Revenue</th>
                <th>Views</th>
                <th>Rating</th>
                <th>Status</th>
                <th>Featured</th>
                <th class="text-center" style="width: 120px;">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($occasions as $occasion)
                @php
                    $imagePath = null;
                    if ($occasion->image && Storage::disk('public')->exists('occasions/' . $occasion->image)) {
                        $imagePath = Storage::disk('public')->url('occasions/' . $occasion->image);
                    }
                @endphp
                <tr data-id="{{ $occasion->id }}">
                    <td class="ps-3">
                        <input type="checkbox" class="form-check-input occasion-checkbox" value="{{ $occasion->id }}">
                    </td>
                    <td>
                        <span class="fw-semibold">#{{ $occasion->id }}</span>
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            @if ($imagePath)
                                <img src="{{ $imagePath }}" alt="{{ $occasion->name }}" class="rounded"
                                    style="width: 40px; height: 40px; object-fit: cover;">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                    style="width: 40px; height: 40px;">
                                    <i class="ti ti-calendar-event text-primary"></i>
                                </div>
                            @endif
                            <div>
                                <span class="fw-semibold">{{ $occasion->name }}</span>
                                <div class="small text-muted">{{ $occasion->slug }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-secondary-subtle text-secondary">
                            <i class="ti ti-barcode"></i> {{ $occasion->code }}
                        </span>
                    </td>
                    <td>
                        <span class="badge bg-primary-subtle text-primary p-2">
                            <i class="ti ti-package me-1"></i> {{ number_format($occasion->product_count) }}
                        </span>
                    </td>
                    <td>
                        <span class="badge bg-success-subtle text-success p-2">
                            <i class="ti ti-shopping-cart me-1"></i> {{ number_format($occasion->order_count) }}
                        </span>
                    </td>
                    <td class="text-success fw-semibold">
                        ${{ number_format($occasion->total_revenue, 2) }}
                    </td>
                    <td>
                        <div class="d-flex flex-column">
                            <span class="fw-semibold">{{ number_format($occasion->view_count) }}</span>
                            <small class="text-muted">{{ $occasion->updated_at->diffForHumans() }}</small>
                        </div>
                    </td>
                    <td>
                        @if ($occasion->avg_rating > 0)
                            <div class="d-flex align-items-center">
                                <span class="text-warning me-1">{{ number_format($occasion->avg_rating, 1) }}</span>
                                <i class="ti ti-star text-warning"></i>
                                <small class="text-muted ms-1">({{ $occasion->review_count }})</small>
                            </div>
                        @else
                            <span class="text-muted">No ratings</span>
                        @endif
                    </td>
                    <td>
                        <div class="form-check form-switch">
                            <input type="checkbox" class="form-check-input toggle-status" data-id="{{ $occasion->id }}"
                                {{ $occasion->status ? 'checked' : '' }}>
                        </div>
                    </td>
                    <td>
                        <div class="form-check form-switch">
                            <input type="checkbox" class="form-check-input toggle-featured"
                                data-id="{{ $occasion->id }}" {{ $occasion->is_featured ? 'checked' : '' }}>
                        </div>
                    </td>
                    <td class="pe-3 text-center">
                        <div class="hstack gap-1 justify-content-center">
                            <a href="{{ route('admin.occasions.show', $occasion->id) }}"
                                class="btn btn-soft-primary btn-icon btn-sm rounded-circle" data-bs-toggle="tooltip"
                                title="View Details">
                                <i class="ti ti-eye"></i>
                            </a>
                            <a href="{{ route('admin.occasions.edit', $occasion->id) }}"
                                class="btn btn-soft-success btn-icon btn-sm rounded-circle" data-bs-toggle="tooltip"
                                title="Edit Occasion">
                                <i class="ti ti-edit"></i>
                            </a>
                            @can('delete occasions')
                                @if ($occasion->product_count == 0)
                                    <button type="button" class="btn btn-soft-danger btn-icon btn-sm rounded-circle"
                                        onclick="confirmDelete({{ $occasion->id }})" data-bs-toggle="tooltip"
                                        title="Delete Occasion">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                @else
                                    <button type="button" class="btn btn-soft-secondary btn-icon btn-sm rounded-circle"
                                        disabled data-bs-toggle="tooltip"
                                        title="Cannot delete - has {{ $occasion->product_count }} products">
                                        <i class="ti ti-lock"></i>
                                    </button>
                                @endif
                            @endcan
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="12" class="text-center py-5">
                        <div class="empty-state">
                            <i class="ti ti-calendar-off" style="font-size: 48px; opacity: 0.5;"></i>
                            <h5 class="mt-3">No Occasions Found</h5>
                            <p class="text-muted">Get started by creating a new occasion.</p>
                            @can('create occasions')
                                <a href="{{ route('admin.occasions.create') }}" class="btn btn-primary mt-2">
                                    <i class="ti ti-plus me-1"></i> Add New Occasion
                                </a>
                            @endcan
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
