{{-- resources/views/admin/categories/partials/categories-table.blade.php --}}
<div class="table-responsive">
    <table class="table table-hover text-nowrap mb-0">
        <thead class="bg-dark-subtle">
            32
            <th class="ps-3" style="width: 50px;">
                <input type="checkbox" class="form-check-input" id="selectAll">
            </th>
            <th>ID</th>
            <th>Category</th>
            <th>Parent Category</th>
            <th>Path/Breadcrumb</th>
            <th>Products</th>
            <th>Orders</th>
            <th>Revenue</th>
            <th>Views</th>
            <th>Status</th>
            <th class="text-center" style="width: 150px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($categories as $category)
                @php
                    $indent = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $category->depth);
                    $imagePath = null;

                    if (
                        $category->thumbnail_image &&
                        Storage::disk('public')->exists('categories/thumbnails/' . $category->thumbnail_image)
                    ) {
                        $imagePath = Storage::disk('public')->url(
                            'categories/thumbnails/' . $category->thumbnail_image,
                        );
                    } elseif ($category->image && Storage::disk('public')->exists('categories/' . $category->image)) {
                        $imagePath = Storage::disk('public')->url('categories/' . $category->image);
                    }
                @endphp
                <tr>
                    <td class="ps-3">
                        <input type="checkbox" class="form-check-input category-checkbox" value="{{ $category->id }}">
                    </td>
                    <td>#{{ $category->id }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            @if ($imagePath)
                                <img src="{{ $imagePath }}" alt="{{ $category->name }}" class="rounded"
                                    style="width: 40px; height: 40px; object-fit: cover;">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                    style="width: 40px; height: 40px;">
                                    @if ($category->depth > 0)
                                        <i class="ti ti-subdirectory text-secondary"></i>
                                    @else
                                        <i class="ti ti-folder text-primary"></i>
                                    @endif
                                </div>
                            @endif
                            <div>
                                <span class="fw-semibold">
                                    @if ($category->depth > 0)
                                        <i class="ti ti-corner-down-right text-muted me-1" style="font-size: 12px;"></i>
                                    @endif
                                    {!! $indent !!}{{ $category->name }}
                                </span>
                                <div class="small text-muted">{{ $category->slug }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        @if ($category->parent)
                            <a href="{{ route('admin.categories.show', $category->parent->id) }}"
                                class="text-decoration-none">
                                <i class="ti ti-arrow-narrow-up me-1 text-success"></i>
                                {{ $category->parent->name }}
                            </a>
                            <br>
                            <small class="text-muted">ID: #{{ $category->parent->id }}</small>
                        @else
                            <span class="badge bg-secondary-subtle text-secondary">
                                <i class="ti ti-home"></i> Main Category
                            </span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex flex-column">
                            <span class="text-muted small" title="{{ $category->path }}">
                                <i class="ti ti-link me-1"></i>
                                {{ Str::limit($category->path, 40) }}
                            </span>
                            @if ($category->depth > 0)
                                <span class="badge bg-info-subtle text-info mt-1" style="font-size: 10px;">
                                    Level {{ $category->depth }}
                                </span>
                            @endif
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-primary-subtle text-primary p-2">
                            <i class="ti ti-package me-1"></i> {{ number_format($category->product_count) }}
                        </span>
                    </td>
                    <td>
                        <span class="badge bg-success-subtle text-success p-2">
                            <i class="ti ti-shopping-cart me-1"></i> {{ number_format($category->order_count) }}
                        </span>
                    </td>
                    <td class="text-success fw-semibold">
                        ${{ number_format($category->total_revenue, 2) }}
                    </td>
                    <td>
                        <div class="d-flex flex-column">
                            <span class="fw-semibold">{{ number_format($category->view_count) }}</span>
                            @if ($category->last_viewed_at)
                                <small class="text-muted">{{ $category->last_viewed_at->diffForHumans() }}</small>
                            @endif
                        </div>
                    </td>
                    <td>
                        <div class="form-check form-switch">
                            <input type="checkbox" class="form-check-input toggle-status" data-id="{{ $category->id }}"
                                {{ $category->status ? 'checked' : '' }}>
                        </div>
                        @if ($category->is_featured)
                            <span class="badge bg-warning-subtle text-warning mt-1 d-block" style="font-size: 10px;">
                                <i class="ti ti-star"></i> Featured
                            </span>
                        @endif
                        @if ($category->is_popular)
                            <span class="badge bg-danger-subtle text-danger mt-1 d-block" style="font-size: 10px;">
                                <i class="ti ti-fire"></i> Popular
                            </span>
                        @endif
                    </td>
                    <td class="pe-3 text-center">
                        <div class="hstack gap-1 justify-content-center">
                            <a href="{{ route('admin.categories.show', $category->id) }}"
                                class="btn btn-soft-primary btn-icon btn-sm rounded-circle" data-bs-toggle="tooltip"
                                title="View Details">
                                <i class="ti ti-eye"></i>
                            </a>
                            <a href="{{ route('admin.categories.edit', $category->id) }}"
                                class="btn btn-soft-success btn-icon btn-sm rounded-circle" data-bs-toggle="tooltip"
                                title="Edit Category">
                                <i class="ti ti-edit"></i>
                            </a>
                            @if ($category->children->count() == 0 && $category->product_count == 0)
                                <button type="button" class="btn btn-soft-danger btn-icon btn-sm rounded-circle"
                                    onclick="confirmDelete({{ $category->id }})" data-bs-toggle="tooltip"
                                    title="Delete Category">
                                    <i class="ti ti-trash"></i>
                                </button>
                            @else
                                <button type="button" class="btn btn-soft-secondary btn-icon btn-sm rounded-circle"
                                    disabled data-bs-toggle="tooltip"
                                    title="Cannot delete - has {{ $category->children->count() }} subcategories and {{ $category->product_count }} products">
                                    <i class="ti ti-lock"></i>
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" class="text-center py-5">
                        <div class="empty-state">
                            <i class="ti ti-folder-off" style="font-size: 48px; opacity: 0.5;"></i>
                            <h5 class="mt-3">No Categories Found</h5>
                            <p class="text-muted">Get started by creating a new category.</p>
                            @can('create categories')
                                <a href="{{ route('admin.categories.create') }}" class="btn btn-primary mt-2">
                                    <i class="ti ti-plus me-1"></i> Add New Category
                                </a>
                            @endcan
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
