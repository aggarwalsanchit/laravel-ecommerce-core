{{-- resources/views/admin/products/show.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Product Details')

@section('content')
<div class="page-content">
    <div class="page-container">
        <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
            <div class="flex-grow-1">
                <h4 class="fs-18 text-uppercase fw-bold mb-0">Product Details: {{ $product->name }}</h4>
            </div>
            <div class="text-end">
                <ol class="breadcrumb m-0 py-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Products</a></li>
                    <li class="breadcrumb-item active">{{ $product->name }}</li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4">
                {{-- Product Images --}}
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title">Product Images</h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <img src="{{ $product->featured_image_url }}" 
                                 alt="{{ $product->name }}" 
                                 class="img-fluid rounded" 
                                 style="max-height: 250px; width: auto;">
                            <p class="text-muted mt-2">Featured Image</p>
                        </div>
                        @if($product->images->count() > 0)
                            <div class="d-flex flex-wrap gap-2 justify-content-center">
                                @foreach($product->images as $image)
                                    <img src="{{ $image->image_url }}" 
                                         class="rounded" 
                                         style="width: 70px; height: 70px; object-fit: cover; cursor: pointer;"
                                         onclick="showImageModal('{{ $image->image_url }}')">
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Product Information --}}
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title">Product Information</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            32
                                <td width="120"><strong>ID:</strong>64
                                <td>#{{ $product->id }}64
                            </tr>
                            32
                                <td><strong>SKU:</strong>32
                                <td><code>{{ $product->sku }}</code>32
                            </tr>
                            32
                                <td><strong>Status:</strong>32
                                <td>
                                    @if($product->status)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                    @if($product->is_featured)
                                        <span class="badge bg-warning ms-1">Featured</span>
                                    @endif
                                    @if($product->is_new)
                                        <span class="badge bg-info ms-1">New</span>
                                    @endif
                                32
                            </tr>
                            32
                                <td><strong>Category:</strong>32
                                <td>
                                    <span class="badge bg-secondary">{{ $product->mainCategory->name ?? 'N/A' }}</span>
                                    @if($product->subcategories->count() > 0)
                                        <br>
                                        <small class="text-muted">{{ $product->subcategories->pluck('name')->implode(', ') }}</small>
                                    @endif
                                32
                            </tr>
                            32
                                <td><strong>Created:</strong>32
                                <td>{{ $product->created_at->format('F d, Y H:i') }}<br>
                                    <small class="text-muted">{{ $product->created_at->diffForHumans() }}</small>
                                32
                            </tr>
                            32
                                <td><strong>Last Updated:</strong>32
                                <td>{{ $product->updated_at->diffForHumans() }}32
                            </tr>
                        </table>
                    </div>
                </div>

                {{-- Analytics Card --}}
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title">Analytics</h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6 mb-3">
                                <div class="bg-primary-subtle rounded p-3">
                                    <h3 class="mb-0">{{ number_format($product->view_count) }}</h3>
                                    <small>Views</small>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="bg-success-subtle rounded p-3">
                                    <h3 class="mb-0">{{ number_format($product->order_count) }}</h3>
                                    <small>Orders</small>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="bg-warning-subtle rounded p-3">
                                    <h3 class="mb-0">${{ number_format($product->total_sold, 2) }}</h3>
                                    <small>Revenue</small>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="bg-info-subtle rounded p-3">
                                    <h3 class="mb-0">{{ number_format($product->avg_rating, 1) }}</h3>
                                    <small>Rating</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Stock & Shipping --}}
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Stock & Shipping</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            <strong>Stock Status:</strong>
                            @if($product->track_stock)
                                @if($product->stock > $product->low_stock_threshold)
                                    <span class="badge bg-success">{{ $product->stock }} in stock</span>
                                @elseif($product->stock > 0)
                                    <span class="badge bg-warning">{{ $product->stock }} left</span>
                                @else
                                    <span class="badge bg-danger">Out of stock</span>
                                @endif
                            @else
                                <span class="badge bg-info">Unlimited</span>
                            @endif
                        </div>
                        @if($product->weight)
                            <div class="mb-2"><strong>Weight:</strong> {{ $product->weight }} kg</div>
                        @endif
                        @if($product->length || $product->width || $product->height)
                            <div><strong>Dimensions:</strong> 
                                {{ $product->length }} x {{ $product->width }} x {{ $product->height }} cm
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                {{-- Pricing --}}
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title">Pricing</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="text-center p-3 bg-light rounded">
                                    <h6>Regular Price</h6>
                                    <h3 class="text-primary">${{ number_format($product->price, 2) }}</h3>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-center p-3 bg-light rounded">
                                    <h6>Current Price</h6>
                                    <h3 class="text-success">${{ number_format($product->current_price, 2) }}</h3>
                                    @if($product->is_on_sale)
                                        <span class="badge bg-danger">-{{ $product->discount_percentage }}% OFF</span>
                                        <br>
                                        <small>Sale: {{ $product->sale_start_date ? $product->sale_start_date->format('M d') : 'Now' }} - {{ $product->sale_end_date ? $product->sale_end_date->format('M d') : 'Until Stock Lasts' }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        @if($product->tierPrices->count() > 0)
                            <div class="mt-3">
                                <h6>Tiered Pricing</h6>
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            32
                                                <th>Quantity</th>
                                                <th>Price per Unit</th>
                                                <th>Total Savings</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($product->tierPrices as $tier)
                                                @php
                                                    $originalTotal = $product->price * $tier->min_quantity;
                                                    $discountedTotal = $tier->price * $tier->min_quantity;
                                                    $savings = $originalTotal - $discountedTotal;
                                                @endphp
                                                <tr>
                                                    <td>{{ $tier->min_quantity }}{{ $tier->max_quantity ? ' - ' . $tier->max_quantity : '+' }}</td>
                                                    <td class="text-success">${{ number_format($tier->price, 2) }}</td>
                                                    <td>Save ${{ number_format($savings, 2) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Colors & Sizes --}}
                @if($product->colors->count() > 0 || $product->sizes->count() > 0)
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title">Available Variants</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @if($product->colors->count() > 0)
                            <div class="col-md-6">
                                <h6>Colors</h6>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($product->colors as $color)
                                        <div class="text-center">
                                            <div style="width: 50px; height: 50px; background: {{ $color->hex_code }}; border-radius: 8px; border: 1px solid #dee2e6;"></div>
                                            <small>{{ $color->name }}</small>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                            @if($product->sizes->count() > 0)
                            <div class="col-md-6">
                                <h6>Sizes</h6>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($product->sizes as $size)
                                        <span class="badge bg-secondary p-2">{{ $size->name }}</span>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                        
                        @if($product->variants->count() > 0)
                            <div class="mt-3">
                                <h6>All Variants</h6>
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            32
                                                <th>Color</th>
                                                <th>Size</th>
                                                <th>SKU</th>
                                                <th>Price</th>
                                                <th>Stock</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($product->variants as $variant)
                                                <tr>
                                                    <td>{{ $variant->color->name ?? '-' }}</td>
                                                    <td>{{ $variant->size->name ?? '-' }}</td>
                                                    <td><code>{{ $variant->sku }}</code></td>
                                                    <td>${{ number_format($variant->price, 2) }}</td>
                                                    <td>{{ $variant->stock }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                @endif

                {{-- Description --}}
                @if($product->description)
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title">Description</h5>
                    </div>
                    <div class="card-body">
                        <div class="p-3 bg-light rounded">
                            {!! nl2br(e($product->description)) !!}
                        </div>
                    </div>
                </div>
                @endif

                {{-- Short Description --}}
                @if($product->short_description)
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title">Short Description</h5>
                    </div>
                    <div class="card-body">
                        <div class="p-3 bg-light rounded">
                            {{ $product->short_description }}
                        </div>
                    </div>
                </div>
                @endif

                {{-- Custom Attributes --}}
                @if($product->customAttributes->count() > 0)
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title">Product Specifications</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($product->customAttributes->groupBy('attribute.name') as $attributeName => $values)
                                <div class="col-md-6 mb-3">
                                    <strong>{{ $attributeName }}:</strong><br>
                                    @foreach($values as $value)
                                        <span class="badge bg-info me-1">{{ $value->value }}</span>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                {{-- SEO Information --}}
                @if($product->meta_title || $product->meta_description)
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title">SEO Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            <strong>Meta Title:</strong><br>
                            {{ $product->meta_title ?: 'Not set' }}
                        </div>
                        <div class="mb-2">
                            <strong>Meta Description:</strong><br>
                            {{ $product->meta_description ?: 'Not set' }}
                        </div>
                        @if($product->meta_keywords)
                            <div>
                                <strong>Meta Keywords:</strong><br>
                                {{ $product->meta_keywords }}
                            </div>
                        @endif
                    </div>
                </div>
                @endif

                {{-- Action Buttons --}}
                <div class="card">
                    <div class="card-footer text-end">
                        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                            <i class="ti ti-arrow-left"></i> Back
                        </a>
                        <a href="{{ route('admin.products.analytics', $product) }}" class="btn btn-info">
                            <i class="ti ti-chart-bar"></i> View Analytics
                        </a>
                        <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-primary">
                            <i class="ti ti-edit"></i> Edit Product
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Image Modal --}}
<div class="modal fade" id="imageModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Product Image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" class="img-fluid">
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function showImageModal(imageUrl) {
    $('#modalImage').attr('src', imageUrl);
    $('#imageModal').modal('show');
}
</script>
@endpush