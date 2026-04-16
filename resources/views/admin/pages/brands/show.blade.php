{{-- resources/views/admin/pages/brands/show.blade.php --}}
@extends('management.layouts.app')

@section('title', 'Brand Details - ' . $brand->name)

@section('content')
    <div class="page-content">
        <div class="page-container">
            <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                <div class="flex-grow-1">
                    <h4 class="fs-18 text-uppercase fw-bold mb-0">Brand Details: {{ $brand->name }}</h4>
                </div>
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.brands.index') }}">Brands</a></li>
                        <li class="breadcrumb-item active">{{ $brand->name }}</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <!-- Left Column -->
                <div class="col-lg-4">
                    {{-- Images Card --}}
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ti ti-photo"></i> Brand Images
                            </h5>
                        </div>
                        <div class="card-body">
                            {{-- Logo Image --}}
                            @if ($brand->logo)
                                <div class="mb-4">
                                    <label class="fw-semibold text-muted small mb-2">Logo</label>
                                    <div class="text-center">
                                        <img src="{{ asset('storage/brands/' . $brand->logo) }}"
                                            alt="{{ $brand->logo_alt ?? $brand->name }}" class="img-fluid rounded border"
                                            style="max-height: 150px; width: auto;">
                                    </div>
                                    @if ($brand->logo_alt)
                                        <div class="mt-2 small text-muted">
                                            <strong>Alt Text:</strong> {{ $brand->logo_alt }}
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="text-center mb-4">
                                    <div class="bg-light rounded p-4">
                                        <i class="ti ti-photo-off" style="font-size: 48px; opacity: 0.5;"></i>
                                        <p class="text-muted mt-2 mb-0">No logo uploaded</p>
                                    </div>
                                </div>
                            @endif

                            {{-- Banner Image --}}
                            @if ($brand->banner)
                                <div class="mb-4">
                                    <label class="fw-semibold text-muted small mb-2">Banner Image</label>
                                    <div class="text-center">
                                        <img src="{{ asset('storage/brands/banners/' . $brand->banner) }}"
                                            alt="{{ $brand->banner_alt ?? $brand->name }}" class="img-fluid rounded border"
                                            style="width: 100%; max-height: 150px; object-fit: cover;">
                                    </div>
                                    @if ($brand->banner_alt)
                                        <div class="mt-2 small text-muted">
                                            <strong>Alt Text:</strong> {{ $brand->banner_alt }}
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="text-center mb-4">
                                    <div class="bg-light rounded p-3">
                                        <i class="ti ti-banner-off" style="font-size: 32px; opacity: 0.5;"></i>
                                        <p class="text-muted mt-1 mb-0 small">No banner image uploaded</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Brand Information Card --}}
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ti ti-info-circle"></i> Basic Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="120"><strong>ID:</strong></td>
                                    <td><span class="badge bg-secondary">#{{ $brand->id }}</span></td>
                                </tr>
                                <tr>
                                    <td><strong>Name:</strong></td>
                                    <td>{{ $brand->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Slug:</strong></td>
                                    <td><code>{{ $brand->slug }}</code></td>
                                </tr>
                                <tr>
                                    <td><strong>Code:</strong></td>
                                    <td><code class="text-primary">{{ $brand->code }}</code></td>
                                </tr>
                                <tr>
                                    <td><strong>Order:</strong></td>
                                    <td>{{ $brand->order }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Created:</strong></td>
                                    <td>{{ $brand->created_at->format('F d, Y H:i:s') }}<br>
                                        <small class="text-muted">{{ $brand->created_at->diffForHumans() }}</small>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Last Updated:</strong></td>
                                    <td>{{ $brand->updated_at->format('F d, Y H:i:s') }}<br>
                                        <small class="text-muted">{{ $brand->updated_at->diffForHumans() }}</small>
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
                                <label class="fw-semibold text-muted small">Status</label>
                                <div>
                                    @if ($brand->status)
                                        <span class="badge bg-success"><i class="ti ti-circle-check"></i> Active</span>
                                    @else
                                        <span class="badge bg-danger"><i class="ti ti-circle-x"></i> Inactive</span>
                                    @endif
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="fw-semibold text-muted small">Featured Status</label>
                                <div>
                                    @if ($brand->is_featured)
                                        <span class="badge bg-warning"><i class="ti ti-star"></i> Featured Brand</span>
                                    @else
                                        <span class="badge bg-secondary"><i class="ti ti-star-off"></i> Not Featured</span>
                                    @endif
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="fw-semibold text-muted small">Associated Categories</label>
                                <div class="d-flex flex-wrap gap-1">
                                    @forelse($brand->categories as $category)
                                        <a href="{{ route('admin.categories.show', $category->id) }}"
                                            class="badge bg-primary text-decoration-none">
                                            <i class="ti ti-folder"></i> {{ $category->name }}
                                        </a>
                                    @empty
                                        <span class="text-muted small">No categories assigned</span>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- SEO Information Card --}}
                    @if ($brand->meta_title || $brand->meta_description || $brand->meta_keywords)
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="ti ti-meta-tag"></i> SEO Information
                                </h5>
                            </div>
                            <div class="card-body">
                                @if ($brand->meta_title)
                                    <div class="mb-2">
                                        <label class="fw-semibold text-muted small">Meta Title</label>
                                        <div class="bg-light p-2 rounded small">{{ $brand->meta_title }}</div>
                                    </div>
                                @endif
                                @if ($brand->meta_description)
                                    <div class="mb-2">
                                        <label class="fw-semibold text-muted small">Meta Description</label>
                                        <div class="bg-light p-2 rounded small">{{ $brand->meta_description }}</div>
                                    </div>
                                @endif
                                @if ($brand->meta_keywords)
                                    <div class="mb-2">
                                        <label class="fw-semibold text-muted small">Meta Keywords</label>
                                        <div class="bg-light p-2 rounded small">{{ $brand->meta_keywords }}</div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
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
                                <div class="col-md-3 col-6 mb-3">
                                    <div class="bg-primary-subtle rounded p-3 text-center">
                                        <i class="ti ti-package fs-2 text-primary"></i>
                                        <h3 class="mb-0 mt-2">{{ number_format($totalProducts ?? 0) }}</h3>
                                        <small class="text-muted">Total Products</small>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6 mb-3">
                                    <div class="bg-info-subtle rounded p-3 text-center">
                                        <i class="ti ti-eye fs-2 text-info"></i>
                                        <h3 class="mb-0 mt-2">{{ number_format($totalViews ?? 0) }}</h3>
                                        <small class="text-muted">Total Views</small>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6 mb-3">
                                    <div class="bg-success-subtle rounded p-3 text-center">
                                        <i class="ti ti-shopping-cart fs-2 text-success"></i>
                                        <h3 class="mb-0 mt-2">{{ number_format($totalOrders ?? 0) }}</h3>
                                        <small class="text-muted">Total Orders</small>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6 mb-3">
                                    <div class="bg-warning-subtle rounded p-3 text-center">
                                        <i class="ti ti-currency-dollar fs-2 text-warning"></i>
                                        <h3 class="mb-0 mt-2">${{ number_format($totalRevenue ?? 0, 2) }}</h3>
                                        <small class="text-muted">Total Revenue</small>
                                    </div>
                                </div>
                            </div>

                            @if (($avgRating ?? 0) > 0)
                                <hr>
                                <div class="text-center">
                                    <span class="text-muted">Average Rating:</span>
                                    <div class="text-warning d-inline-block ms-2">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i
                                                class="ti ti-star{{ $i <= floor($avgRating) ? '-filled' : ($i - 0.5 <= $avgRating ? '-half-filled' : '') }}"></i>
                                        @endfor
                                    </div>
                                    <strong class="text-warning fs-5 ms-2">{{ number_format($avgRating, 1) }}</strong>
                                    <small class="text-muted">({{ number_format($reviewCount ?? 0) }} reviews)</small>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Description Card --}}
                    @if ($brand->description)
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="ti ti-align-left"></i> Description
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="bg-light p-3 rounded">
                                    {!! nl2br(e($brand->description)) !!}
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Associated Categories Card --}}
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ti ti-folder"></i> Associated Categories ({{ $brand->categories->count() }})
                            </h5>
                        </div>
                        <div class="card-body">
                            @if ($brand->categories->count() > 0)
                                <div class="row">
                                    @foreach ($brand->categories as $category)
                                        @php
                                            $categoryProducts = $categoryStats[$loop->index]['products_count'] ?? 0;
                                        @endphp
                                        <div class="col-md-6 mb-2">
                                            <div
                                                class="d-flex justify-content-between align-items-center p-2 bg-light rounded">
                                                <div>
                                                    <i class="ti ti-folder text-primary me-2"></i>
                                                    <a href="{{ route('admin.categories.show', $category->id) }}"
                                                        class="text-decoration-none">
                                                        {{ $category->name }}
                                                    </a>
                                                    <small class="text-muted d-block">
                                                        <i class="ti ti-package"></i>
                                                        {{ number_format($categoryProducts) }} products
                                                        @if ($category->status)
                                                            <span class="badge bg-success ms-1"
                                                                style="font-size: 8px;">Active</span>
                                                        @else
                                                            <span class="badge bg-danger ms-1"
                                                                style="font-size: 8px;">Inactive</span>
                                                        @endif
                                                    </small>
                                                </div>
                                                <div class="text-end">
                                                    @if ($category->is_featured)
                                                        <span class="badge bg-warning"><i class="ti ti-star"></i></span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="ti ti-folder-off" style="font-size: 48px; opacity: 0.5;"></i>
                                    <p class="text-muted mt-2">No categories associated with this brand.</p>
                                    <a href="{{ route('admin.brands.edit', $brand->id) }}"
                                        class="btn btn-sm btn-primary">
                                        <i class="ti ti-plus"></i> Add Categories
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Recent Products Card --}}
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ti ti-package"></i> Recent Products ({{ $brand->products->count() }})
                            </h5>
                        </div>
                        <div class="card-body">
                            @if ($brand->products->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover table-sm">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Product Name</th>
                                                <th>SKU</th>
                                                <th>Price</th>
                                                <th>Stock</th>
                                                <th>Status</th>
                                                <th class="text-center">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($brand->products->take(10) as $product)
                                                <tr>
                                                    <td>
                                                        <a href="{{ route('admin.products.show', $product->id) }}"
                                                            class="text-decoration-none fw-semibold">
                                                            {{ $product->name }}
                                                        </a>
                                                        <br><small
                                                            class="text-muted">{{ Str::limit($product->short_description, 50) }}</small>
                                                    </td>
                                                    <td><code>{{ $product->sku ?? 'N/A' }}</code></td>
                                                    <td> class="text-success fw-semibold">${{ number_format($product->price, 2) }}
                                                    </td>
                                                    <td>
                                                        @if ($product->track_stock)
                                                            <span
                                                                class="badge {{ $product->stock_quantity > 0 ? 'bg-success' : 'bg-danger' }}">
                                                                {{ $product->stock_quantity }} units
                                                            </span>
                                                        @else
                                                            <span class="badge bg-secondary">Not tracked</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($product->status)
                                                            <span class="badge bg-success">Active</span>
                                                        @else
                                                            <span class="badge bg-danger">Inactive</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="btn-group btn-group-sm">
                                                            <a href="{{ route('admin.products.show', $product->id) }}"
                                                                class="btn btn-info" title="View">
                                                                <i class="ti ti-eye"></i>
                                                            </a>
                                                            <a href="{{ route('admin.products.edit', $product->id) }}"
                                                                class="btn btn-primary" title="Edit">
                                                                <i class="ti ti-edit"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @if ($brand->products->count() > 10)
                                    <div class="text-center mt-3">
                                        <a href="{{ route('admin.products.index', ['brand_id' => $brand->id]) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="ti ti-eye"></i> View All {{ $brand->products->count() }} Products
                                        </a>
                                    </div>
                                @endif
                            @else
                                <div class="text-center py-4">
                                    <i class="ti ti-package-off" style="font-size: 48px; opacity: 0.5;"></i>
                                    <p class="text-muted mt-2">No products associated with this brand.</p>
                                    <a href="{{ route('admin.products.create') }}?brand_id={{ $brand->id }}"
                                        class="btn btn-sm btn-primary">
                                        <i class="ti ti-plus"></i> Add Product
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Performance Trends Chart --}}
                    @if (isset($recentAnalytics) && $recentAnalytics->count() > 0)
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="ti ti-chart-line"></i> Performance Trends (Last 30 Days)
                                </h5>
                            </div>
                            <div class="card-body">
                                <canvas id="analyticsChart" height="250"></canvas>
                            </div>
                        </div>
                    @endif

                    {{-- Action Buttons --}}
                    <div class="card">
                        <div class="card-footer d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.brands.index') }}" class="btn btn-secondary">
                                <i class="ti ti-arrow-left me-1"></i> Back to List
                            </a>
                            @can('edit_brands')
                                <a href="{{ route('admin.brands.edit', $brand->id) }}" class="btn btn-primary">
                                    <i class="ti ti-edit me-1"></i> Edit Brand
                                </a>
                            @endcan
                            @can('create_brands')
                                <a href="{{ route('admin.brands.create') }}" class="btn btn-success">
                                    <i class="ti ti-plus me-1"></i> Add New Brand
                                </a>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        @if (isset($recentAnalytics) && $recentAnalytics->count() > 0)
            // Analytics Chart
            const analyticsData = @json($recentAnalytics->sortBy('date')->values());
            const ctx = document.getElementById('analyticsChart').getContext('2d');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: analyticsData.map(item => {
                        const date = new Date(item.date);
                        return date.toLocaleDateString('en-US', {
                            month: 'short',
                            day: 'numeric'
                        });
                    }),
                    datasets: [{
                        label: 'Views',
                        data: analyticsData.map(item => item.view_count),
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        fill: true,
                        yAxisID: 'y'
                    }, {
                        label: 'Orders',
                        data: analyticsData.map(item => item.order_count),
                        borderColor: 'rgb(34, 197, 94)',
                        backgroundColor: 'rgba(34, 197, 94, 0.1)',
                        tension: 0.4,
                        fill: true,
                        yAxisID: 'y'
                    }, {
                        label: 'Revenue ($)',
                        data: analyticsData.map(item => item.total_revenue),
                        borderColor: 'rgb(234, 179, 8)',
                        backgroundColor: 'rgba(234, 179, 8, 0.1)',
                        tension: 0.4,
                        fill: true,
                        yAxisID: 'y1'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    let value = context.raw;
                                    if (context.dataset.label === 'Revenue ($)') {
                                        return label + ': $' + value.toLocaleString();
                                    }
                                    return label + ': ' + value.toLocaleString();
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Views & Orders',
                                color: '#6c757d'
                            },
                            ticks: {
                                callback: function(value) {
                                    return value.toLocaleString();
                                }
                            }
                        },
                        y1: {
                            beginAtZero: true,
                            position: 'right',
                            title: {
                                display: true,
                                text: 'Revenue ($)',
                                color: '#6c757d'
                            },
                            ticks: {
                                callback: function(value) {
                                    return '$' + value.toLocaleString();
                                }
                            },
                            grid: {
                                drawOnChartArea: false,
                            }
                        }
                    }
                }
            });
        @endif
    </script>
@endpush

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

        .bg-danger-subtle {
            background-color: rgba(220, 53, 69, 0.1);
        }

        .table-borderless td,
        .table-borderless th {
            padding: 0.5rem 0;
        }

        .display-4 {
            font-size: 2.5rem;
            font-weight: 300;
            line-height: 1.2;
        }

        .ti-star-filled,
        .ti-star-half-filled {
            color: #ffc107;
        }

        .btn-group-sm .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }
    </style>
@endpush
