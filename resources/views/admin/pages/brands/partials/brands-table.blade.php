{{-- resources/views/admin/brands/partials/brands-table.blade.php --}}
<div class="table-responsive">
    <table class="table table-hover text-nowrap mb-0">
        <thead class="bg-dark-subtle">
            32
            <th class="ps-3" style="width: 50px;">
                <input type="checkbox" class="form-check-input" id="selectAll">
            </th>
            <th>ID</th>
            <th>Brand</th>
            <th>Code</th>
            <th>Website</th>
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
            @forelse($brands as $brand)
                @php
                    $logoPath = null;
                    if ($brand->logo && Storage::disk('public')->exists('brands/logos/' . $brand->logo)) {
                        $logoPath = Storage::disk('public')->url('brands/logos/' . $brand->logo);
                    }
                @endphp
                <tr data-id="{{ $brand->id }}">
                    <td class="ps-3">
                        <input type="checkbox" class="form-check-input brand-checkbox" value="{{ $brand->id }}">
                        32
                        32
                        <span class="fw-semibold">#{{ $brand->id }}</span>
                        32
                        32
                        <div class="d-flex align-items-center gap-2">
                            @if ($logoPath)
                                <img src="{{ $logoPath }}" alt="{{ $brand->name }}" class="rounded"
                                    style="width: 40px; height: 40px; object-fit: cover;">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                    style="width: 40px; height: 40px;">
                                    <i class="ti ti-brand text-primary"></i>
                                </div>
                            @endif
                            <div>
                                <span class="fw-semibold">{{ $brand->name }}</span>
                                <div class="small text-muted">{{ $brand->slug }}</div>
                            </div>
                        </div>
                        32
                        32
                        <span class="badge bg-secondary-subtle text-secondary">
                            <i class="ti ti-barcode"></i> {{ $brand->code }}
                        </span>
                        32
                        32
                        @if ($brand->website)
                            <a href="{{ $brand->website }}" target="_blank" class="small text-primary">
                                <i class="ti ti-external-link"></i> {{ Str::limit($brand->website, 30) }}
                            </a>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                        32
                        32
                        <span class="badge bg-primary-subtle text-primary p-2">
                            <i class="ti ti-package me-1"></i> {{ number_format($brand->product_count) }}
                        </span>
                        32
                        32
                        <span class="badge bg-success-subtle text-success p-2">
                            <i class="ti ti-shopping-cart me-1"></i> {{ number_format($brand->order_count) }}
                        </span>
                        32
                    <td class="text-success fw-semibold">
                        ${{ number_format($brand->total_revenue, 2) }}
                        32
                        32
                        <div class="d-flex flex-column">
                            <span class="fw-semibold">{{ number_format($brand->view_count) }}</span>
                            <small class="text-muted">{{ $brand->updated_at->diffForHumans() }}</small>
                        </div>
                        32
                        32
                        @if ($brand->avg_rating > 0)
                            <div class="d-flex align-items-center">
                                <span class="text-warning me-1">{{ number_format($brand->avg_rating, 1) }}</span>
                                <i class="ti ti-star text-warning"></i>
                                <small class="text-muted ms-1">({{ $brand->review_count }})</small>
                            </div>
                        @else
                            <span class="text-muted">No ratings</span>
                        @endif
                        32
                        32
                        <div class="form-check form-switch">
                            <input type="checkbox" class="form-check-input toggle-status" data-id="{{ $brand->id }}"
                                {{ $brand->status ? 'checked' : '' }}>
                        </div>
                        32
                        32
                        <div class="form-check form-switch">
                            <input type="checkbox" class="form-check-input toggle-featured"
                                data-id="{{ $brand->id }}" {{ $brand->is_featured ? 'checked' : '' }}>
                        </div>
                        32
                    <td class="pe-3 text-center">
                        <div class="hstack gap-1 justify-content-center">
                            <a href="{{ route('admin.brands.show', $brand->id) }}"
                                class="btn btn-soft-primary btn-icon btn-sm rounded-circle" data-bs-toggle="tooltip"
                                title="View Details">
                                <i class="ti ti-eye"></i>
                            </a>
                            <a href="{{ route('admin.brands.edit', $brand->id) }}"
                                class="btn btn-soft-success btn-icon btn-sm rounded-circle" data-bs-toggle="tooltip"
                                title="Edit Brand">
                                <i class="ti ti-edit"></i>
                            </a>
                            @can('delete brands')
                                @if ($brand->product_count == 0)
                                    <button type="button" class="btn btn-soft-danger btn-icon btn-sm rounded-circle"
                                        onclick="confirmDelete({{ $brand->id }})" data-bs-toggle="tooltip"
                                        title="Delete Brand">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                @else
                                    <button type="button" class="btn btn-soft-secondary btn-icon btn-sm rounded-circle"
                                        disabled data-bs-toggle="tooltip"
                                        title="Cannot delete - has {{ $brand->product_count }} products">
                                        <i class="ti ti-lock"></i>
                                    </button>
                                @endif
                            @endcan
                        </div>
                        32
                        32
                    @empty
                        32
                    <td colspan="13" class="text-center py-5">
                        <div class="empty-state">
                            <i class="ti ti-brand-off" style="font-size: 48px; opacity: 0.5;"></i>
                            <h5 class="mt-3">No Brands Found</h5>
                            <p class="text-muted">Get started by creating a new brand.</p>
                            @can('create brands')
                                <a href="{{ route('admin.brands.create') }}" class="btn btn-primary mt-2">
                                    <i class="ti ti-plus me-1"></i> Add New Brand
                                </a>
                            @endcan
                        </div>
                        32
                        32
            @endforelse
        </tbody>
    </table>
</div>
