{{-- resources/views/admin/products/partials/products-table.blade.php --}}
<div class="table-responsive">
    <table class="table table-hover text-nowrap mb-0">
        <thead class="bg-dark-subtle">
            32
                <th class="ps-3" style="width: 50px;">
                    <input type="checkbox" class="form-check-input" id="selectAll">
                </th>
                <th>ID</th>
                <th>Product</th>
                <th>SKU</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Category</th>
                <th>Views</th>
                <th>Orders</th>
                <th>Status</th>
                <th class="text-center" style="width: 120px;">Actions</th>
            </thead>
        <tbody>
            @forelse($products as $product)
            <tr>
                <td class="ps-3">
                    <input type="checkbox" class="form-check-input product-checkbox" value="{{ $product->id }}">
                </td>
                <td>
                    <span class="fw-semibold">#{{ $product->id }}</span>
                </td>
                <td>
                    <div class="d-flex align-items-center gap-2">
                        @php
                            $featuredImage = $product->featuredImage()->first();
                            $imageUrl = $featuredImage ? $featuredImage->image_url : asset('images/placeholder.jpg');
                        @endphp
                        <img src="{{ $imageUrl }}" 
                             alt="{{ $product->name }}" 
                             class="rounded" 
                             style="width: 50px; height: 50px; object-fit: cover;">
                        <div>
                            <span class="fw-semibold">{{ $product->name }}</span>
                            <div class="small text-muted">{{ Str::limit($product->short_description ?? $product->description, 50) }}</div>
                        </div>
                    </div>
                </td>
                <td>
                    <code>{{ $product->sku }}</code>
                </td>
                <td>
                    <div>
                        @if($product->is_on_sale && $product->sale_price && $product->sale_start_date <= now() && $product->sale_end_date >= now())
                            <span class="text-muted text-decoration-line-through">${{ number_format($product->price, 2) }}</span>
                            <br>
                            <span class="text-danger fw-bold">${{ number_format($product->sale_price, 2) }}</span>
                            <span class="badge bg-danger ms-1">-{{ $product->discount_percentage }}%</span>
                        @else
                            <span class="fw-bold">${{ number_format($product->price, 2) }}</span>
                        @endif
                    </div>
                </td>
                <td>
                    @if($product->track_stock)
                        @if($product->stock > $product->low_stock_threshold)
                            <span class="badge bg-success">{{ $product->stock }} in stock</span>
                        @elseif($product->stock > 0)
                            <span class="badge bg-warning text-dark">{{ $product->stock }} left</span>
                        @else
                            <span class="badge bg-danger">Out of stock</span>
                        @endif
                    @else
                        <span class="badge bg-info">Unlimited</span>
                    @endif
                </td>
                <td>
                    <span class="badge bg-secondary">{{ $product->mainCategory->name ?? 'N/A' }}</span>
                    @if($product->subcategories && $product->subcategories->count() > 0)
                        <br>
                        <small class="text-muted">{{ $product->subcategories->pluck('name')->implode(', ') }}</small>
                    @endif
                </td>
                <td>
                    <span class="fw-semibold">{{ number_format($product->view_count) }}</span>
                </td>
                <td>
                    <span class="fw-semibold">{{ number_format($product->order_count) }}</span>
                </td>
                <td>
                    <div class="form-check form-switch">
                        <input type="checkbox" class="form-check-input toggle-status" 
                               data-id="{{ $product->id }}"
                               {{ $product->status ? 'checked' : '' }}>
                    </div>
                    @if($product->is_featured)
                        <span class="badge bg-warning mt-1 d-block">Featured</span>
                    @endif
                    @if($product->is_on_sale)
                        <span class="badge bg-danger mt-1 d-block">Sale</span>
                    @endif
                    @if($product->is_new)
                        <span class="badge bg-info mt-1 d-block">New</span>
                    @endif
                </td>
                <td class="text-center">
                    <div class="btn-group" role="group">
                        <a href="{{ route('admin.products.show', $product->id) }}" 
                           class="btn btn-soft-primary btn-sm" 
                           data-bs-toggle="tooltip" 
                           title="View Product">
                            <i class="ti ti-eye"></i>
                        </a>
                        <a href="{{ route('admin.products.edit', $product->id) }}" 
                           class="btn btn-soft-success btn-sm" 
                           data-bs-toggle="tooltip" 
                           title="Edit Product">
                            <i class="ti ti-edit"></i>
                        </a>
                        <button type="button" 
                                class="btn btn-soft-danger btn-sm" 
                                onclick="confirmDelete({{ $product->id }})"
                                data-bs-toggle="tooltip" 
                                title="Delete Product">
                            <i class="ti ti-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="11" class="text-center py-5">
                    <div class="empty-state">
                        <i class="ti ti-package-off" style="font-size: 48px; opacity: 0.5;"></i>
                        <h5 class="mt-3">No Products Found</h5>
                        <p class="text-muted">Get started by creating your first product.</p>
                        <a href="{{ route('admin.products.create') }}" class="btn btn-primary mt-2">
                            <i class="ti ti-plus me-1"></i> Add New Product
                        </a>
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

.btn-soft-primary {
    background-color: rgba(13, 110, 253, 0.1);
    border: 1px solid rgba(13, 110, 253, 0.2);
    color: #0d6efd;
}

.btn-soft-primary:hover {
    background-color: #0d6efd;
    border-color: #0d6efd;
    color: #fff;
}

.btn-soft-success {
    background-color: rgba(25, 135, 84, 0.1);
    border: 1px solid rgba(25, 135, 84, 0.2);
    color: #198754;
}

.btn-soft-success:hover {
    background-color: #198754;
    border-color: #198754;
    color: #fff;
}

.btn-soft-danger {
    background-color: rgba(220, 53, 69, 0.1);
    border: 1px solid rgba(220, 53, 69, 0.2);
    color: #dc3545;
}

.btn-soft-danger:hover {
    background-color: #dc3545;
    border-color: #dc3545;
    color: #fff;
}

.form-check-input:checked {
    background-color: #0d6efd;
    border-color: #0d6efd;
}

.badge {
    font-weight: 500;
    padding: 0.35rem 0.65rem;
}
</style>
@endpush