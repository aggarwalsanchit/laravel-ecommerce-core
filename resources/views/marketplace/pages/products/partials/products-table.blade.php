{{-- resources/views/marketplace/pages/products/partials/products-table.blade.php --}}
<div class="table-responsive">
    <table class="table table-hover text-nowrap mb-0">
        <thead class="bg-dark-subtle">
            <tr>
                <th class="ps-3" style="width: 50px;">
                    <input type="checkbox" class="form-check-input" id="selectAll">
                </th>
                <th>ID</th>
                <th>Product</th>
                <th>SKU</th>
                <th>Category</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Approval</th>
                <th>Status</th>
                <th>Created</th>
                <th class="text-center" style="width: 180px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
                @php
                    $stockClass = 'success';
                    $stockText = 'In Stock';
                    if ($product->stock_quantity <= 0) {
                        $stockClass = 'danger';
                        $stockText = 'Out of Stock';
                    } elseif ($product->stock_quantity <= $product->low_stock_threshold) {
                        $stockClass = 'warning';
                        $stockText = 'Low Stock';
                    }

                    $approvalBadge =
                        [
                            'pending' => '<span class="badge bg-warning"><i class="ti ti-clock"></i> Pending</span>',
                            'approved' => '<span class="badge bg-success"><i class="ti ti-check"></i> Approved</span>',
                            'rejected' => '<span class="badge bg-danger"><i class="ti ti-x"></i> Rejected</span>',
                        ][$product->approval_status] ?? '<span class="badge bg-secondary">Unknown</span>';

                    $mainImage = $product->images->where('is_main', true)->first();
                @endphp
                <tr>
                    <td class="ps-3">
                        <input type="checkbox" class="form-check-input product-checkbox" value="{{ $product->id }}">
</div>
</div>
</td>
<td>#{{ $product->id }}</div>
    </div>
</td>
<td>
    <div class="d-flex align-items-center gap-2">
        @if ($mainImage)
            <img src="{{ asset('storage/products/' . $mainImage->image) }}" alt="{{ $product->name }}" class="rounded"
                style="width: 40px; height: 40px; object-fit: cover;">
        @else
            <div class="bg-light rounded d-flex align-items-center justify-content-center"
                style="width: 40px; height: 40px;">
                <i class="ti ti-package text-primary"></i>
            </div>
        @endif
        <div>
            <span class="fw-semibold">{{ $product->name }}</span>
            @if ($product->short_description)
                <div class="small text-muted">{{ Str::limit($product->short_description, 40) }}</div>
            @endif
        </div>
    </div>
    </div>
    </div>
</td>
<td><code class="small">{{ $product->sku ?? 'N/A' }}</code></div>
    </div>
</td>
<td>
    @if ($product->primary_category_id)
        @php
            $primaryCat = \App\Models\Category::find($product->primary_category_id);
        @endphp
        @if ($primaryCat)
            <span class="badge bg-primary-subtle text-primary">{{ $primaryCat->name }}</span>
        @else
            <span class="text-muted">N/A</span>
        @endif
    @else
        <span class="text-muted">N/A</span>
    @endif
    </div>
    </div>
</td>
<td>
    <div>
        <span class="fw-bold text-success">${{ number_format($product->price, 2) }}</span>
        @if ($product->compare_price)
            <br><small
                class="text-muted text-decoration-line-through">${{ number_format($product->compare_price, 2) }}</small>
        @endif
    </div>
    </div>
    </div>
</td>
<td>
    <div>
        <span class="badge bg-{{ $stockClass }}">{{ $stockText }}</span>
        <br><small>{{ number_format($product->stock_quantity) }} units</small>
    </div>
    </div>
    </div>
</td>
<td>{!! $approvalBadge !!}</div>
    </div>
</td>
<td>
    <div class="form-check form-switch">
        <input type="checkbox" class="form-check-input toggle-status" data-id="{{ $product->id }}"
            {{ $product->status ? 'checked' : '' }} {{ $product->approval_status !== 'approved' ? 'disabled' : '' }}>
    </div>
    </div>
    </div>
</td>
<td>
    {{ $product->created_at->format('M d, Y') }}<br>
    <small class="text-muted">{{ $product->created_at->diffForHumans() }}</small>
    </div>
    </div>
</td>
<td class="pe-3 text-center">
    <div class="hstack gap-1 justify-content-center">
        <a href="{{ route('vendor.products.show', $product->id) }}"
            class="btn btn-soft-primary btn-icon btn-sm rounded-circle" data-bs-toggle="tooltip" title="View Details">
            <i class="ti ti-eye"></i>
        </a>
        @if ($product->approval_status === 'approved')
            <a href="{{ route('vendor.products.edit', $product->id) }}"
                class="btn btn-soft-success btn-icon btn-sm rounded-circle" data-bs-toggle="tooltip"
                title="Edit Product">
                <i class="ti ti-edit"></i>
            </a>
        @endif
        <button type="button" class="btn btn-soft-danger btn-icon btn-sm rounded-circle"
            onclick="confirmDelete({{ $product->id }}, '{{ addslashes($product->name) }}')" data-bs-toggle="tooltip"
            title="Delete Product">
            <i class="ti ti-trash"></i>
        </button>
    </div>
    </div>
    </div>
</td>
</tr>
@empty
<tr>
    <td colspan="11" class="text-center py-5">
        <div class="empty-state">
            <i class="ti ti-package-off" style="font-size: 48px; opacity: 0.5;"></i>
            <h5 class="mt-3">No Products Found</h5>
            <p class="text-muted">Get started by creating a new product.</p>
            <a href="{{ route('vendor.products.create') }}" class="btn btn-primary mt-2">
                <i class="ti ti-plus me-1"></i> Add New Product
            </a>
        </div>
        </div>
        </div>
    </td>
</tr>
@endforelse
</tbody>
</table>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.toggle-status').on('change', function() {
                let checkbox = $(this);
                let productId = checkbox.data('id');
                let isChecked = checkbox.prop('checked');

                $.ajax({
                    url: '{{ url('vendor/products') }}/' + productId + '/toggle-status',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Updated!',
                                text: response.message,
                                timer: 1500,
                                showConfirmButton: false
                            });
                        }
                    },
                    error: function() {
                        checkbox.prop('checked', !isChecked);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Failed to update status.',
                            confirmButtonColor: '#d33'
                        });
                    }
                });
            });
        });
    </script>
@endpush
