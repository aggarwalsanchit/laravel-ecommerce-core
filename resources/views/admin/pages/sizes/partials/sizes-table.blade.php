{{-- resources/views/admin/sizes/partials/sizes-table.blade.php --}}
<div class="table-responsive">
    <table class="table table-hover text-nowrap mb-0">
        <thead class="bg-dark-subtle">
            32
            <th class="ps-3" style="width: 50px;">
                <input type="checkbox" class="form-check-input" id="selectAll">
            </th>
            <th>ID</th>
            <th>Size</th>
            <th>Code</th>
            <th>Products</th>
            <th>Orders</th>
            <th>Revenue</th>
            <th>Views</th>
            <th>Status</th>
            <th class="text-center" style="width: 120px;">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($sizes as $size)
                @php
                    $imagePath = null;
                    if ($size->image && Storage::disk('public')->exists('sizes/' . $size->image)) {
                        $imagePath = Storage::disk('public')->url('sizes/' . $size->image);
                    }
                @endphp
                <tr data-id="{{ $size->id }}">
                    <td class="ps-3">
                        <input type="checkbox" class="form-check-input size-checkbox" value="{{ $size->id }}">
                    </td>
                    <td>
                        <span class="fw-semibold">#{{ $size->id }}</span>
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            @if ($imagePath)
                                <img src="{{ $imagePath }}" alt="{{ $size->name }}" class="rounded"
                                    style="width: 40px; height: 40px; object-fit: cover;">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                    style="width: 40px; height: 40px;">
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
                        <span class="badge bg-secondary-subtle text-secondary">
                            {{ $size->code }}
                        </span>
                    </td>
                    <td>
                        <span class="badge bg-primary-subtle text-primary p-2">
                            <i class="ti ti-package me-1"></i> {{ number_format($size->product_count) }}
                        </span>
                    </td>
                    <td>
                        <span class="badge bg-success-subtle text-success p-2">
                            <i class="ti ti-shopping-cart me-1"></i> {{ number_format($size->order_count) }}
                        </span>
                    </td>
                    <td class="text-success fw-semibold">
                        ${{ number_format($size->total_revenue, 2) }}
                    </td>
                    <td>
                        <div class="d-flex flex-column">
                            <span class="fw-semibold">{{ number_format($size->view_count) }}</span>
                            @if ($size->updated_at)
                                <small class="text-muted">{{ $size->updated_at->diffForHumans() }}</small>
                            @endif
                        </div>
                    </td>
                    <td>
                        <div class="form-check form-switch">
                            <input type="checkbox" class="form-check-input toggle-status" data-id="{{ $size->id }}"
                                {{ $size->status ? 'checked' : '' }}>
                        </div>
                    </td>
                    <td class="pe-3 text-center">
                        <div class="hstack gap-1 justify-content-center">
                            <a href="{{ route('admin.sizes.show', $size->id) }}"
                                class="btn btn-soft-primary btn-icon btn-sm rounded-circle" data-bs-toggle="tooltip"
                                title="View Details">
                                <i class="ti ti-eye"></i>
                            </a>
                            <a href="{{ route('admin.sizes.edit', $size->id) }}"
                                class="btn btn-soft-success btn-icon btn-sm rounded-circle" data-bs-toggle="tooltip"
                                title="Edit Size">
                                <i class="ti ti-edit"></i>
                            </a>
                            @can('delete sizes')
                                @if ($size->product_count == 0)
                                    <button type="button" class="btn btn-soft-danger btn-icon btn-sm rounded-circle"
                                        onclick="confirmDelete({{ $size->id }})" data-bs-toggle="tooltip"
                                        title="Delete Size">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                @else
                                    <button type="button" class="btn btn-soft-secondary btn-icon btn-sm rounded-circle"
                                        disabled data-bs-toggle="tooltip"
                                        title="Cannot delete - has {{ $size->product_count }} products">
                                        <i class="ti ti-lock"></i>
                                    </button>
                                @endif
                            @endcan
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center py-5">
                        <div class="empty-state">
                            <i class="ti ti-ruler-off" style="font-size: 48px; opacity: 0.5;"></i>
                            <h5 class="mt-3">No Sizes Found</h5>
                            <p class="text-muted">Get started by creating a new size.</p>
                            @can('create sizes')
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
