{{-- resources/views/admin/attributes/partials/table.blade.php --}}
<div class="table-responsive">
    <table class="table table-hover text-nowrap mb-0">
        <thead class="bg-dark-subtle">
            <tr>
                <th class="ps-3" style="width: 50px;">
                    <input type="checkbox" class="form-check-input" id="selectAll">
                </th>
                <th>ID</th>
                <th>Attribute</th>
                <th>Code</th>
                <th>Type</th>
                <th>Group</th>
                <th>Values</th>
                <th>Usage</th>
                <th>Status</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($attributes as $attribute)
            <tr data-id="{{ $attribute->id }}">
                <td class="ps-3">
                    <input type="checkbox" class="form-check-input attribute-checkbox" value="{{ $attribute->id }}">
                </td>
                <td>
                    <span class="fw-semibold">#{{ $attribute->id }}</span>
                </td>
                <td>
                    <div class="d-flex align-items-center gap-2">
                        @if($attribute->icon)
                            <img src="{{ asset('storage/attributes/icons/' . $attribute->icon) }}" 
                                 alt="{{ $attribute->name }}" 
                                 class="rounded" 
                                 style="width: 35px; height: 35px; object-fit: cover;">
                        @else
                            <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                 style="width: 35px; height: 35px;">
                                <i class="ti ti-{{ $attribute->type }} text-primary"></i>
                            </div>
                        @endif
                        <div>
                            <span class="fw-semibold">{{ $attribute->name }}</span>
                            <div class="small text-muted">{{ $attribute->slug }}</div>
                        </div>
                    </div>
                </td>
                <td>
                    @if($attribute->code)
                        <code class="small">{{ $attribute->code }}</code>
                    @else
                        <span class="text-muted">—</span>
                    @endif
                </td>
                <td>
                    <span class="badge bg-info-subtle text-info">
                        <i class="ti ti-{{ $attribute->type }} me-1"></i>
                        {{ ucfirst($attribute->type) }}
                    </span>
                    @if($attribute->unit)
                        <br><small class="text-muted">({{ $attribute->unit }})</small>
                    @endif
                </td>
                <td>
                    @if($attribute->group)
                        <a href="{{ route('admin.attribute-groups.show', $attribute->group) }}" class="text-decoration-none">
                            <span class="badge bg-secondary-subtle text-secondary">
                                <i class="ti ti-category me-1"></i>
                                {{ $attribute->group->name }}
                            </span>
                        </a>
                    @else
                        <span class="text-muted">—</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('admin.attributes.values.index', $attribute) }}" class="text-primary text-decoration-none">
                        <i class="ti ti-list me-1"></i>
                        <span class="fw-semibold">{{ $attribute->values->count() }}</span>
                        <small class="text-muted">values</small>
                    </a>
                </td>
                <td>
                    <div class="small">
                        <div class="d-flex align-items-center gap-2">
                            <i class="ti ti-package text-muted"></i>
                            <span>{{ number_format($attribute->total_products) }} products</span>
                        </div>
                        <div class="d-flex align-items-center gap-2 mt-1">
                            <i class="ti ti-chart-line text-success"></i>
                            <span class="text-success">${{ number_format($attribute->total_revenue, 2) }}</span>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="form-check form-switch">
                        <input type="checkbox" class="form-check-input toggle-status" 
                               data-id="{{ $attribute->id }}"
                               {{ $attribute->status ? 'checked' : '' }}
                               onclick="toggleStatus({{ $attribute->id }})">
                    </div>
                    @if($attribute->is_featured)
                        <span class="badge bg-warning-subtle text-warning mt-1 d-block" style="font-size: 10px;">
                            <i class="ti ti-star"></i> Featured
                        </span>
                    @endif
                    @if($attribute->is_popular)
                        <span class="badge bg-danger-subtle text-danger mt-1 d-block" style="font-size: 10px;">
                            <i class="ti ti-fire"></i> Popular
                        </span>
                    @endif
                </td>
                <td class="text-center">
                    <div class="hstack gap-1 justify-content-center">
                        <a href="{{ route('admin.attributes.analytics', $attribute) }}" 
                           class="btn btn-soft-info btn-icon btn-sm rounded-circle" 
                           data-bs-toggle="tooltip" 
                           title="View Analytics">
                            <i class="ti ti-chart-bar"></i>
                        </a>
                        <a href="{{ route('admin.attributes.values.index', $attribute) }}" 
                           class="btn btn-soft-secondary btn-icon btn-sm rounded-circle" 
                           data-bs-toggle="tooltip" 
                           title="Manage Values">
                            <i class="ti ti-list-check"></i>
                        </a>
                        <a href="{{ route('admin.attributes.edit', $attribute) }}" 
                           class="btn btn-soft-success btn-icon btn-sm rounded-circle" 
                           data-bs-toggle="tooltip" 
                           title="Edit Attribute">
                            <i class="ti ti-edit"></i>
                        </a>
                        @can('delete attributes')
                            <button type="button" 
                                    class="btn btn-soft-danger btn-icon btn-sm rounded-circle" 
                                    onclick="confirmDelete({{ $attribute->id }})"
                                    data-bs-toggle="tooltip" 
                                    title="Delete Attribute">
                                <i class="ti ti-trash"></i>
                            </button>
                        @endcan
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="10" class="text-center py-5">
                    <div class="empty-state">
                        <i class="ti ti-list-off" style="font-size: 48px; opacity: 0.5;"></i>
                        <h5 class="mt-3">No Attributes Found</h5>
                        <p class="text-muted">Get started by creating a new attribute.</p>
                        @can('create attributes')
                            <a href="{{ route('admin.attributes.create') }}" class="btn btn-primary mt-2">
                                <i class="ti ti-plus me-1"></i> Add New Attribute
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

.table > :not(caption) > * > * {
    vertical-align: middle;
    padding: 0.75rem;
}

.form-switch .form-check-input {
    width: 2.5em;
    height: 1.25em;
    cursor: pointer;
}

/* Tooltip styles */
[data-bs-toggle="tooltip"] {
    cursor: pointer;
}

/* Hover effects */
.btn-soft-primary:hover {
    background-color: #0d6efd;
    border-color: #0d6efd;
    color: #fff;
}

.btn-soft-success:hover {
    background-color: #198754;
    border-color: #198754;
    color: #fff;
}

.btn-soft-danger:hover {
    background-color: #dc3545;
    border-color: #dc3545;
    color: #fff;
}

.btn-soft-info:hover {
    background-color: #0dcaf0;
    border-color: #0dcaf0;
    color: #000;
}

.btn-soft-secondary:hover {
    background-color: #6c757d;
    border-color: #6c757d;
    color: #fff;
}
</style>
@endpush