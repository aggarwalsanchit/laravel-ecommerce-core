{{-- resources/views/admin/fabrics/partials/fabrics-table.blade.php --}}
<div class="table-responsive">
    <table class="table table-hover text-nowrap mb-0">
        <thead class="bg-dark-subtle">
            32
            <th class="ps-3" style="width: 50px;">
                <input type="checkbox" class="form-check-input" id="selectAll">
            </th>
            <th>ID</th>
            <th>Fabric</th>
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
            @forelse($fabrics as $fabric)
                @php
                    $imagePath = null;
                    if ($fabric->image && Storage::disk('public')->exists('fabrics/' . $fabric->image)) {
                        $imagePath = Storage::disk('public')->url('fabrics/' . $fabric->image);
                    }
                @endphp
                <tr data-id="{{ $fabric->id }}">
                    <td class="ps-3">
                        <input type="checkbox" class="form-check-input fabric-checkbox" value="{{ $fabric->id }}">
                    </td>
                    <td>
                        <span class="fw-semibold">#{{ $fabric->id }}</span>
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            @if ($imagePath)
                                <img src="{{ $imagePath }}" alt="{{ $fabric->name }}" class="rounded"
                                    style="width: 40px; height: 40px; object-fit: cover;">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                    style="width: 40px; height: 40px;">
                                    <i class="ti ti-fabric text-primary"></i>
                                </div>
                            @endif
                            <div>
                                <span class="fw-semibold">{{ $fabric->name }}</span>
                                <div class="small text-muted">{{ $fabric->slug }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-secondary-subtle text-secondary">
                            <i class="ti ti-barcode"></i> {{ $fabric->code }}
                        </span>
                    </td>
                    <td>
                        <span class="badge bg-primary-subtle text-primary p-2">
                            <i class="ti ti-package me-1"></i> {{ number_format($fabric->product_count) }}
                        </span>
                    </td>
                    <td>
                        <span class="badge bg-success-subtle text-success p-2">
                            <i class="ti ti-shopping-cart me-1"></i> {{ number_format($fabric->order_count) }}
                        </span>
                    </td>
                    <td class="text-success fw-semibold">
                        ${{ number_format($fabric->total_revenue, 2) }}
                    </td>
                    <td>
                        <div class="d-flex flex-column">
                            <span class="fw-semibold">{{ number_format($fabric->view_count) }}</span>
                            @if ($fabric->updated_at)
                                <small class="text-muted">{{ $fabric->updated_at->diffForHumans() }}</small>
                            @endif
                        </div>
                    </td>
                    <td>
                        <div class="form-check form-switch">
                            <input type="checkbox" class="form-check-input toggle-status" data-id="{{ $fabric->id }}"
                                {{ $fabric->status ? 'checked' : '' }}>
                        </div>
                    </td>
                    <td class="pe-3 text-center">
                        <div class="hstack gap-1 justify-content-center">
                            <a href="{{ route('admin.fabrics.show', $fabric->id) }}"
                                class="btn btn-soft-primary btn-icon btn-sm rounded-circle" data-bs-toggle="tooltip"
                                title="View Details">
                                <i class="ti ti-eye"></i>
                            </a>
                            <a href="{{ route('admin.fabrics.edit', $fabric->id) }}"
                                class="btn btn-soft-success btn-icon btn-sm rounded-circle" data-bs-toggle="tooltip"
                                title="Edit Fabric">
                                <i class="ti ti-edit"></i>
                            </a>
                            @can('delete fabrics')
                                @if ($fabric->product_count == 0)
                                    <button type="button" class="btn btn-soft-danger btn-icon btn-sm rounded-circle"
                                        onclick="confirmDelete({{ $fabric->id }})" data-bs-toggle="tooltip"
                                        title="Delete Fabric">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                @else
                                    <button type="button" class="btn btn-soft-secondary btn-icon btn-sm rounded-circle"
                                        disabled data-bs-toggle="tooltip"
                                        title="Cannot delete - has {{ $fabric->product_count }} products">
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
                            <i class="ti ti-fabric-off" style="font-size: 48px; opacity: 0.5;"></i>
                            <h5 class="mt-3">No Fabrics Found</h5>
                            <p class="text-muted">Get started by creating a new fabric.</p>
                            @can('create fabrics')
                                <a href="{{ route('admin.fabrics.create') }}" class="btn btn-primary mt-2">
                                    <i class="ti ti-plus me-1"></i> Add New Fabric
                                </a>
                            @endcan
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@push('styles')
    <style>
        .empty-state {
            text-align: center;
            padding: 40px 20px;
        }

        .btn-icon {
            width: 32px;
            height: 32px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50% !important;
        }

        .btn-sm.rounded-circle {
            border-radius: 50% !important;
            width: 32px;
            height: 32px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .hstack {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .hstack .btn {
            margin: 0;
            flex-shrink: 0;
        }

        .badge {
            font-weight: 500;
        }

        .table> :not(caption)>*>* {
            vertical-align: middle;
            padding: 0.75rem;
        }

        .form-switch .form-check-input {
            width: 2.5em;
            height: 1.25em;
            cursor: pointer;
        }
    </style>
@endpush
