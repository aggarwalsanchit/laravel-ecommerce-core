{{-- resources/views/marketplace/pages/products/show.blade.php --}}
@extends('management.layouts.app')

@section('title', 'Product Details - ' . ($product->name ?? 'Not Found'))

@section('content')
    <div class="page-content">
        <div class="page-container">
            <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                <div class="flex-grow-1">
                    <h4 class="fs-18 text-uppercase fw-bold mb-0">Product Details: {{ $product->name ?? 'N/A' }}</h4>
                </div>
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('vendor.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('vendor.products.index') }}">Products</a></li>
                        <li class="breadcrumb-item active">{{ $product->name ?? 'Details' }}</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <!-- Left Column -->
                <div class="col-lg-4">
                    {{-- Product Images Card --}}
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ti ti-photo"></i> Product Images
                            </h5>
                        </div>
                        <div class="card-body">
                            @if ($product->images->count() > 0)
                                <div class="row g-2">
                                    @foreach ($product->images as $image)
                                        <div class="col-6 mb-2">
                                            <div class="position-relative">
                                                <img src="{{ asset('storage/products/' . $image->image) }}"
                                                    alt="{{ $image->alt_text ?? $product->name }}"
                                                    class="img-fluid rounded border"
                                                    style="height: 100px; width: 100%; object-fit: cover;">
                                                @if ($image->is_main)
                                                    <span class="badge bg-primary position-absolute top-0 end-0 m-1"
                                                        style="font-size: 8px;">Main</span>
                                                @endif
                                            </div>
                                            @if ($image->alt_text)
                                                <small
                                                    class="text-muted d-block text-truncate">{{ $image->alt_text }}</small>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <div class="bg-light rounded p-4">
                                        <i class="ti ti-photo-off" style="font-size: 48px; opacity: 0.5;"></i>
                                        <p class="text-muted mt-2 mb-0">No images uploaded</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Product Information Card --}}
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ti ti-info-circle"></i> Product Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="120"><strong>ID:</strong></td>
                                    <td><span class="badge bg-secondary">#{{ $product->id }}</span>
                        </div>
                    </div>
                    </td>
                    </tr>
                    <tr>
                        <td><strong>Name:</strong></td>
                        <td>{{ $product->name }}
                </div>
            </div>
            </td>
            </tr>
            <tr>
                <td><strong>Slug:</strong></td>
                <td><code>{{ $product->slug }}</code>
        </div>
    </div>
    </td>
    </tr>
    <tr>
        <td><strong>SKU:</strong></td>
        <td><code>{{ $product->sku ?? 'N/A' }}</code></div>
            </div>
        </td>
    </tr>
    @if ($product->barcode)
        <tr>
            <td><strong>Barcode:</strong></td>
            <td>{{ $product->barcode }}</div>
                </div>
            </td>
        </tr>
    @endif
    <tr>
        <td><strong>Created:</strong></td>
        <td>
            {{ $product->created_at->format('F d, Y H:i:s') }}<br>
            <small class="text-muted">{{ $product->created_at->diffForHumans() }}</small>
            </div>
            </div>
        </td>
    </tr>
    <tr>
        <td><strong>Last Updated:</strong></td>
        <td>
            {{ $product->updated_at->format('F d, Y H:i:s') }}<br>
            <small class="text-muted">{{ $product->updated_at->diffForHumans() }}</small>
            </div>
            </div>
        </td>
    </tr>
    </table>
    </div>
    </div>

    {{-- Status Card --}}
    <div class="card mb-3">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="ti ti-flag"></i> Status & Visibility
            </h5>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label class="fw-semibold text-muted small">Product Status</label>
                <div>
                    @if ($product->status)
                        <span class="badge bg-success"><i class="ti ti-circle-check"></i> Active</span>
                    @else
                        <span class="badge bg-danger"><i class="ti ti-circle-x"></i> Inactive</span>
                    @endif
                </div>
            </div>

            <div class="mb-3">
                <label class="fw-semibold text-muted small">Approval Status</label>
                <div>
                    @php
                        $approvalBadge =
                            [
                                'approved' =>
                                    '<span class="badge bg-success"><i class="ti ti-check"></i> Approved</span>',
                                'pending' =>
                                    '<span class="badge bg-warning"><i class="ti ti-clock"></i> Pending</span>',
                                'rejected' => '<span class="badge bg-danger"><i class="ti ti-x"></i> Rejected</span>',
                            ][$product->approval_status] ?? '<span class="badge bg-secondary">Unknown</span>';
                    @endphp
                    {!! $approvalBadge !!}
                </div>
                @if ($product->rejection_reason)
                    <div class="mt-2 text-danger small">
                        <i class="ti ti-alert-circle"></i> {{ $product->rejection_reason }}
                    </div>
                @endif
            </div>

            <div class="mb-3">
                <label class="fw-semibold text-muted small">Flags</label>
                <div class="d-flex gap-2 flex-wrap">
                    @if ($product->is_featured)
                        <span class="badge bg-warning"><i class="ti ti-star"></i> Featured</span>
                    @endif
                    @if ($product->is_bestseller)
                        <span class="badge bg-danger"><i class="ti ti-trending-up"></i> Bestseller</span>
                    @endif
                    @if ($product->is_new)
                        <span class="badge bg-primary"><i class="ti ti-spark"></i> New</span>
                    @endif
                    @if (!$product->is_featured && !$product->is_bestseller && !$product->is_new)
                        <span class="text-muted small">No special flags assigned</span>
                    @endif
                </div>
            </div>

            @if ($product->free_shipping)
                <div class="mb-3">
                    <span class="badge bg-info"><i class="ti ti-truck"></i> Free Shipping</span>
                </div>
            @endif
        </div>
    </div>

    {{-- Category & Brand Card --}}
    <div class="card mb-3">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="ti ti-folder"></i> Category & Brand
            </h5>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label class="fw-semibold text-muted small">Primary Category</label>
                <div>
                    @if ($product->primaryCategory)
                        <span class="badge bg-primary">
                            <i class="ti ti-folder"></i> {{ $product->primaryCategory->name }}
                        </span>
                    @else
                        <span class="text-muted">N/A</span>
                    @endif
                </div>
            </div>

            @if ($product->categories->count() > 0)
                <div class="mb-3">
                    <label class="fw-semibold text-muted small">Additional Categories</label>
                    <div class="d-flex flex-wrap gap-1">
                        @foreach ($product->categories as $category)
                            <span class="badge bg-info-subtle text-info">
                                {{ $category->name }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="mb-3">
                <label class="fw-semibold text-muted small">Brand</label>
                <div>
                    @if ($product->brand)
                        <span class="badge bg-secondary">
                            <i class="ti ti-brand-airbnb"></i> {{ $product->brand->name }}
                        </span>
                        <br>
                        <small class="text-muted">Code: {{ $product->brand->code }}</small>
                    @else
                        <span class="text-muted">No brand assigned</span>
                    @endif
                </div>
            </div>

            @if ($product->tags->count() > 0)
                <div class="mb-3">
                    <label class="fw-semibold text-muted small">Tags</label>
                    <div class="d-flex flex-wrap gap-1">
                        @foreach ($product->tags as $tag)
                            <span class="badge bg-secondary-subtle text-secondary">
                                <i class="ti ti-tag"></i> {{ $tag->name }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
    </div>

    <!-- Right Column -->
    <div class="col-lg-8">
        {{-- Analytics Card --}}
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ti ti-chart-bar"></i> Analytics Dashboard
                </h5>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-4 col-6 mb-3">
                        <div class="bg-primary-subtle rounded p-3 text-center">
                            <i class="ti ti-eye fs-2 text-primary"></i>
                            <h3 class="mb-0 mt-2">{{ number_format($totalViews ?? 0) }}</h3>
                            <small class="text-muted">Total Views</small>
                        </div>
                    </div>
                    <div class="col-md-4 col-6 mb-3">
                        <div class="bg-success-subtle rounded p-3 text-center">
                            <i class="ti ti-shopping-cart fs-2 text-success"></i>
                            <h3 class="mb-0 mt-2">{{ number_format($totalOrders ?? 0) }}</h3>
                            <small class="text-muted">Total Orders</small>
                        </div>
                    </div>
                    <div class="col-md-4 col-6 mb-3">
                        <div class="bg-warning-subtle rounded p-3 text-center">
                            <i class="ti ti-currency-dollar fs-2 text-warning"></i>
                            <h3 class="mb-0 mt-2">${{ number_format($totalRevenue ?? 0, 2) }}</h3>
                            <small class="text-muted">Total Revenue</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Price & Stock Card --}}
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ti ti-currency-dollar"></i> Pricing & Stock
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="fw-semibold text-muted small">Regular Price</label>
                            <div class="fs-4 fw-bold text-success">${{ number_format($product->price, 2) }}</div>
                        </div>
                    </div>
                    @if ($product->compare_price)
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="fw-semibold text-muted small">Compare at Price</label>
                                <div class="text-muted text-decoration-line-through">
                                    ${{ number_format($product->compare_price, 2) }}</div>
                            </div>
                        </div>
                    @endif
                    @if ($product->cost)
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="fw-semibold text-muted small">Your Cost</label>
                                <div>${{ number_format($product->cost, 2) }}</div>
                            </div>
                        </div>
                    @endif
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="fw-semibold text-muted small">Stock Quantity</label>
                            <div
                                class="fs-5 {{ $product->stock_quantity <= $product->low_stock_threshold ? 'text-warning' : 'text-success' }}">
                                {{ number_format($product->stock_quantity) }} units
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="fw-semibold text-muted small">Stock Status</label>
                            <div>
                                @php
                                    $stockStatusBadge =
                                        [
                                            'instock' => '<span class="badge bg-success">In Stock</span>',
                                            'outofstock' => '<span class="badge bg-danger">Out of Stock</span>',
                                            'backorder' => '<span class="badge bg-warning">Backorder</span>',
                                        ][$product->stock_status] ?? '<span class="badge bg-secondary">Unknown</span>';
                                @endphp
                                {!! $stockStatusBadge !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="fw-semibold text-muted small">Low Stock Threshold</label>
                            <div>{{ $product->low_stock_threshold }} units</div>
                        </div>
                    </div>
                </div>

                @if ($product->track_stock || $product->allow_backorder)
                    <hr>
                    <div class="d-flex gap-3">
                        @if ($product->track_stock)
                            <span class="badge bg-info">Track Stock</span>
                        @endif
                        @if ($product->allow_backorder)
                            <span class="badge bg-warning">Allow Backorders</span>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        {{-- Shipping Details Card --}}
        @if ($product->weight || $product->length || $product->width || $product->height)
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ti ti-truck"></i> Shipping Details
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @if ($product->weight)
                            <div class="col-md-3">
                                <label class="fw-semibold text-muted small">Weight</label>
                                <div>{{ $product->weight }} kg</div>
                            </div>
                        @endif
                        @if ($product->length)
                            <div class="col-md-3">
                                <label class="fw-semibold text-muted small">Length</label>
                                <div>{{ $product->length }} cm</div>
                            </div>
                        @endif
                        @if ($product->width)
                            <div class="col-md-3">
                                <label class="fw-semibold text-muted small">Width</label>
                                <div>{{ $product->width }} cm</div>
                            </div>
                        @endif
                        @if ($product->height)
                            <div class="col-md-3">
                                <label class="fw-semibold text-muted small">Height</label>
                                <div>{{ $product->height }} cm</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        {{-- Description Card --}}
        @if ($product->short_description || $product->description)
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ti ti-align-left"></i> Description
                    </h5>
                </div>
                <div class="card-body">
                    @if ($product->short_description)
                        <div class="alert alert-info mb-3">
                            <strong class="d-block mb-1">Short Description:</strong>
                            {{ $product->short_description }}
                        </div>
                    @endif

                    @if ($product->description)
                        <div class="bg-light p-3 rounded">
                            {!! nl2br(e($product->description)) !!}
                        </div>
                    @endif
                </div>
            </div>
        @endif

        {{-- Highlights Card --}}
        @if ($product->highlights)
            @php
                $highlights = is_string($product->highlights)
                    ? json_decode($product->highlights, true)
                    : $product->highlights;
            @endphp
            @if (is_array($highlights) && count($highlights) > 0)
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ti ti-list"></i> Key Highlights
                        </h5>
                    </div>
                    <div class="card-body">
                        <ul class="mb-0">
                            @foreach ($highlights as $highlight)
                                @if (!empty($highlight))
                                    <li>{{ $highlight }}</li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
        @endif

        {{-- Variants Card --}}
        @if ($product->variants && $product->variants->count() > 0)
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ti ti-color-swatch"></i> Product Variants ({{ $product->variants->count() }})
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Color</th>
                                    <th>Size</th>
                                    <th>SKU</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($product->variants as $variant)
                                    <tr>
                                        <td>{{ $variant->color->name ?? 'N/A' }}
                                            @if ($variant->color && $variant->color->code)
                                                <span
                                                    style="display:inline-block;width:15px;height:15px;background:{{ $variant->color->code }};border-radius:3px;"></span>
                                            @endif
                                        </td>
                                        <td>{{ $variant->size->name ?? 'N/A' }}</td>
                                        <td><code>{{ $variant->sku ?? 'N/A' }}</code></td>
                                        <td class="text-success">${{ number_format($variant->price, 2) }}</td>
                                        <td>
                                            <span
                                                class="badge {{ $variant->stock_quantity > 0 ? 'bg-success' : 'bg-danger' }}">
                                                {{ number_format($variant->stock_quantity) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        {{-- Tier Pricing Card --}}
        @if ($product->tierPrices && $product->tierPrices->count() > 0)
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ti ti-chart-line"></i> Tier Pricing (Quantity Discounts)
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Min Quantity</th>
                                    <th>Max Quantity</th>
                                    <th>Price per Unit</th>
                                    <th>Savings</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($product->tierPrices as $tier)
                                    @php
                                        $savings = ($product->price - $tier->price) * $tier->min_quantity;
                                    @endphp
                                    <tr>
                                        <td>{{ number_format($tier->min_quantity) }}+</td>
                                        <td>{{ $tier->max_quantity ? number_format($tier->max_quantity) . '+' : 'Unlimited' }}
                                        </td>
                                        <td class="text-success">${{ number_format($tier->price, 2) }}</td>
                                        <td class="text-info">Save ${{ number_format($savings, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        {{-- Action Buttons --}}
        <div class="card">
            <div class="card-footer d-flex justify-content-end gap-2">
                <a href="{{ route('vendor.products.index') }}" class="btn btn-secondary">
                    <i class="ti ti-arrow-left me-1"></i> Back to Products
                </a>
                @if ($product->approval_status === 'approved')
                    <a href="{{ route('vendor.products.edit', $product->id) }}" class="btn btn-primary">
                        <i class="ti ti-edit me-1"></i> Edit Product
                    </a>
                @endif
            </div>
        </div>
    </div>
    </div>
    </div>
    </div>
@endsection

@push('styles')
    <style>
        .bg-primary-subtle {
            background-color: rgba(59, 130, 246, 0.1);
        }

        .bg-success-subtle {
            background-color: rgba(34, 197, 94, 0.1);
        }

        .bg-warning-subtle {
            background-color: rgba(234, 179, 8, 0.1);
        }

        .bg-info-subtle {
            background-color: rgba(14, 165, 233, 0.1);
        }

        .table-borderless td,
        .table-borderless th {
            padding: 0.5rem 0;
        }
    </style>
@endpush
