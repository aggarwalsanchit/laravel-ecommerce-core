{{-- resources/views/admin/pages/brands/partials/brands-table.blade.php --}}
<div class="table-responsive">
    <table class="table table-hover text-nowrap mb-0">
        <thead class="bg-dark-subtle">
            <tr>
                <th class="ps-3" style="width: 50px;">
                    <input type="checkbox" class="form-check-input" id="selectAll">
                </th>
                <th>ID</th>
                <th>Brand</th>
                <th>Code</th>
                <th>Categories</th>
                <th>Products</th>
                <th>Views</th>
                <th>Orders</th>
                <th>Revenue</th>
                <th>Status</th>
                <th>Featured</th>
                <th class="text-center" style="width: 180px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($brands as $brand)
                @php
                    $logoPath = null;
                    if ($brand->logo && Storage::disk('public')->exists('brands/' . $brand->logo)) {
                        $logoPath = Storage::disk('public')->url('brands/' . $brand->logo);
                    }

                    $statusBadge = $brand->status
                        ? '<span class="badge bg-success"><i class="ti ti-circle-check"></i> Active</span>'
                        : '<span class="badge bg-danger"><i class="ti ti-circle-x"></i> Inactive</span>';

                    $featuredBadge = $brand->is_featured
                        ? '<span class="badge bg-warning"><i class="ti ti-star"></i> Featured</span>'
                        : '<span class="badge bg-secondary"><i class="ti ti-star-off"></i> Not Featured</span>';
                @endphp
                <tr>
                    <td class="ps-3">
                        <input type="checkbox" class="form-check-input brand-checkbox" value="{{ $brand->id }}">
                    </td>
                    <td>#{{ $brand->id }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            @if ($logoPath)
                                <img src="{{ $logoPath }}" alt="{{ $brand->name }}" class="rounded"
                                    style="width: 40px; height: 40px; object-fit: cover;">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                    style="width: 40px; height: 40px;">
                                    <i class="ti ti-brand-airbnb text-primary"></i>
                                </div>
                            @endif
                            <div>
                                <span class="fw-semibold">{{ $brand->name }}</span>
                                <div class="small text-muted">{{ $brand->slug }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <code class="small">{{ $brand->code }}</code>
                    </td>
                    <td>
                        @if ($brand->categories->count() > 0)
                            <div class="d-flex flex-wrap gap-1">
                                @foreach ($brand->categories->take(2) as $category)
                                    <span class="badge bg-primary-subtle text-primary" style="font-size: 10px;">
                                        {{ $category->name }}
                                    </span>
                                @endforeach
                                @if ($brand->categories->count() > 2)
                                    <span class="badge bg-secondary-subtle text-secondary" style="font-size: 10px;">
                                        +{{ $brand->categories->count() - 2 }} more
                                    </span>
                                @endif
                            </div>
                        @else
                            <span class="text-muted small">No categories</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge bg-primary-subtle text-primary p-2">
                            <i class="ti ti-package me-1"></i>
                            {{ number_format($brand->products_count ?? $brand->products->count()) }}
                        </span>
                    </td>
                    <td>
                        <span class="badge bg-info-subtle text-info p-2">
                            <i class="ti ti-eye me-1"></i> {{ number_format($brand->total_views ?? 0) }}
                        </span>
                    </td>
                    <td>
                        <span class="badge bg-success-subtle text-success p-2">
                            <i class="ti ti-shopping-cart me-1"></i> {{ number_format($brand->total_orders ?? 0) }}
                        </span>
                    </td>
                    <td class="text-success fw-semibold">
                        ${{ number_format($brand->total_revenue ?? 0, 2) }}
                    </td>
                    <td>
                        {!! $statusBadge !!}
                    </td>
                    <td>
                        {!! $featuredBadge !!}
                    </td>
                    <td class="pe-3 text-center">
                        <div class="hstack gap-1 justify-content-center">
                            @php $admin = auth()->guard('admin')->user(); @endphp

                            <!-- View Button -->
                            @if ($admin->can('view_brands'))
                                <a href="{{ route('admin.brands.show', $brand->id) }}"
                                    class="btn btn-soft-primary btn-icon btn-sm rounded-circle" data-bs-toggle="tooltip"
                                    title="View Details">
                                    <i class="ti ti-eye"></i>
                                </a>
                            @endif

                            <!-- Activate/Deactivate Button -->
                            @if ($admin->can('edit_brands'))
                                @if ($brand->status)
                                    <button type="button" class="btn btn-soft-warning btn-icon btn-sm rounded-circle"
                                        onclick="toggleBrandStatus({{ $brand->id }}, 'deactivate', '{{ $brand->name }}')"
                                        data-bs-toggle="tooltip" title="Deactivate Brand">
                                        <i class="ti ti-circle-x"></i>
                                    </button>
                                @else
                                    <button type="button" class="btn btn-soft-success btn-icon btn-sm rounded-circle"
                                        onclick="toggleBrandStatus({{ $brand->id }}, 'activate', '{{ $brand->name }}')"
                                        data-bs-toggle="tooltip" title="Activate Brand">
                                        <i class="ti ti-circle-check"></i>
                                    </button>
                                @endif
                            @endif

                            <!-- Toggle Featured Button -->
                            @if ($admin->can('edit_brands'))
                                <button type="button" class="btn btn-soft-warning btn-icon btn-sm rounded-circle"
                                    onclick="toggleFeatured({{ $brand->id }}, '{{ $brand->name }}')"
                                    data-bs-toggle="tooltip" title="Toggle Featured">
                                    <i class="ti ti-star"></i>
                                </button>
                            @endif

                            <!-- Edit Button -->
                            @if ($admin->can('edit_brands'))
                                <a href="{{ route('admin.brands.edit', $brand->id) }}"
                                    class="btn btn-soft-success btn-icon btn-sm rounded-circle" data-bs-toggle="tooltip"
                                    title="Edit Brand">
                                    <i class="ti ti-edit"></i>
                                </a>
                            @endif

                            <!-- Delete Button -->
                            @if ($brand->products->count() == 0)
                                @if ($admin->can('delete_brands'))
                                    <button type="button" class="btn btn-soft-danger btn-icon btn-sm rounded-circle"
                                        onclick="confirmDelete({{ $brand->id }}, '{{ $brand->name }}')"
                                        data-bs-toggle="tooltip" title="Delete Brand">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                @endif
                            @else
                                <button type="button" class="btn btn-soft-secondary btn-icon btn-sm rounded-circle"
                                    disabled data-bs-toggle="tooltip"
                                    title="Cannot delete - has {{ $brand->products->count() }} products">
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
                            <i class="ti ti-brand-airbnb" style="font-size: 48px; opacity: 0.5;"></i>
                            <h5 class="mt-3">No Brands Found</h5>
                            <p class="text-muted">Get started by creating a new brand.</p>
                            @php $admin = auth()->guard('admin')->user(); @endphp
                            @if ($admin->can('create_brands'))
                                <a href="{{ route('admin.brands.create') }}" class="btn btn-primary mt-2">
                                    <i class="ti ti-plus me-1"></i> Add New Brand
                                </a>
                            @endif
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
