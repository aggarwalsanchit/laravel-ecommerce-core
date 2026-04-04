{{-- resources/views/admin/colors/partials/colors-table.blade.php --}}
<div class="table-responsive">
    <table class="table table-hover text-nowrap mb-0">
        <thead class="bg-dark-subtle">
            <tr>
                <th class="ps-3" style="width: 50px;">
                    <input type="checkbox" class="form-check-input" id="selectAll">
                </th>
                <th>ID</th>
                <th>Color</th>
                <th>Code</th>
                <th>Hex Code</th>
                <th>Products</th>
                <th>Orders</th>
                <th>Revenue</th>
                <th>Views</th>
                <th>Status</th>
                <th class="text-center" style="width: 120px;">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($colors as $color)
                <tr data-id="{{ $color->id }}">
                    <td class="ps-3">
                        <input type="checkbox" class="form-check-input color-checkbox" value="{{ $color->id }}">
                    </td>
                    <td>
                        <span class="fw-semibold">#{{ $color->id }}</span>
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            {{-- Auto-generated color preview box --}}
                            <div
                                style="width: 40px; height: 40px; background: {{ $color->hex_code }}; border-radius: 8px; border: 1px solid #dee2e6; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                            </div>
                            <div>
                                <span class="fw-semibold">{{ $color->name }}</span>
                                <div class="small text-muted">{{ $color->slug }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-secondary-subtle text-secondary">
                            <i class="ti ti-barcode me-1"></i> {{ $color->code }}
                        </span>
                    </td>
                    <td>
                        <code class="small">{{ $color->hex_code }}</code>
                        <div class="small text-muted mt-1">
                            <div
                                style="width: 100%; height: 4px; background: linear-gradient(to right, {{ $color->hex_code }}, {{ $color->hex_code }}); border-radius: 2px;">
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-primary-subtle text-primary p-2">
                            <i class="ti ti-package me-1"></i> {{ number_format($color->product_count) }}
                        </span>
                    </td>
                    <td>
                        <span class="badge bg-success-subtle text-success p-2">
                            <i class="ti ti-shopping-cart me-1"></i> {{ number_format($color->order_count) }}
                        </span>
                    </td>
                    <td class="text-success fw-semibold">
                        ${{ number_format($color->total_revenue, 2) }}
                    </td>
                    <td>
                        <div class="d-flex flex-column">
                            <span class="fw-semibold">{{ number_format($color->view_count) }}</span>
                            @if ($color->updated_at)
                                <small class="text-muted">{{ $color->updated_at->diffForHumans() }}</small>
                            @endif
                        </div>
                    </td>
                    <td>
                        <div class="form-check form-switch">
                            <input type="checkbox" class="form-check-input toggle-status" data-id="{{ $color->id }}"
                                {{ $color->status ? 'checked' : '' }}>
                        </div>
                        @if ($color->is_featured ?? false)
                            <span class="badge bg-warning-subtle text-warning mt-1 d-block" style="font-size: 10px;">
                                <i class="ti ti-star"></i> Featured
                            </span>
                        @endif
                    </td>
                    <td class="pe-3 text-center">
                        <div class="hstack gap-1 justify-content-center">
                            <a href="{{ route('admin.colors.show', $color->id) }}"
                                class="btn btn-soft-primary btn-icon btn-sm rounded-circle" data-bs-toggle="tooltip"
                                title="View Details">
                                <i class="ti ti-eye"></i>
                            </a>
                            <a href="{{ route('admin.colors.edit', $color->id) }}"
                                class="btn btn-soft-success btn-icon btn-sm rounded-circle" data-bs-toggle="tooltip"
                                title="Edit Color">
                                <i class="ti ti-edit"></i>
                            </a>
                            @can('delete colors')
                                @if ($color->product_count == 0)
                                    <button type="button" class="btn btn-soft-danger btn-icon btn-sm rounded-circle"
                                        onclick="confirmDelete({{ $color->id }})" data-bs-toggle="tooltip"
                                        title="Delete Color">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                @else
                                    <button type="button" class="btn btn-soft-secondary btn-icon btn-sm rounded-circle"
                                        disabled data-bs-toggle="tooltip"
                                        title="Cannot delete - has {{ $color->product_count }} products">
                                        <i class="ti ti-lock"></i>
                                    </button>
                                @endif
                            @endcan
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" class="text-center py-5">
                        <div class="empty-state">
                            <i class="ti ti-palette-off" style="font-size: 48px; opacity: 0.5;"></i>
                            <h5 class="mt-3">No Colors Found</h5>
                            <p class="text-muted">Get started by creating a new color.</p>
                            @can('create colors')
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

        /* Color preview animation */
        [style*="background:"]:hover {
            transform: scale(1.05);
            transition: transform 0.2s ease;
            cursor: pointer;
        }

        /* Form switch styling */
        .form-switch .form-check-input {
            width: 2.5em;
            height: 1.25em;
            cursor: pointer;
        }

        /* Code styling */
        code {
            background: #f8f9fa;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 12px;
        }

        /* Tooltip styling */
        [data-bs-toggle="tooltip"] {
            cursor: pointer;
        }
    </style>
@endpush
