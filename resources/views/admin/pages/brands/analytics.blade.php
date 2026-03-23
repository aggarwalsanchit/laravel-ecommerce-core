{{-- resources/views/admin/brands/analytics.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Brand Analytics')

@section('content')
    <div class="page-content">
        <div class="page-container">
            <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                <div class="flex-grow-1">
                    <h4 class="fs-18 text-uppercase fw-bold mb-0">Brand Analytics</h4>
                </div>
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.brands.index') }}">Brands</a></li>
                        <li class="breadcrumb-item active">Analytics</li>
                    </ol>
                </div>
            </div>

            {{-- Statistics Cards --}}
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6>Total Brands</h6>
                                    <h2 class="mb-0">{{ $totalBrands ?? 0 }}</h2>
                                </div>
                                <i class="ti ti-brand fs-1 opacity-50"></i>
                            </div>
                            <small>Active: {{ $activeBrands ?? 0 }} | Inactive: {{ $inactiveBrands ?? 0 }}</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-dark">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6>Featured Brands</h6>
                                    <h2 class="mb-0">{{ $featuredBrands ?? 0 }}</h2>
                                </div>
                                <i class="ti ti-star fs-1 opacity-50"></i>
                            </div>
                            <small>Promoted on homepage</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6>Total Products</h6>
                                    <h2 class="mb-0">{{ number_format($totalProducts ?? 0) }}</h2>
                                </div>
                                <i class="ti ti-package fs-1 opacity-50"></i>
                            </div>
                            <small>Across all brands</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6>Total Revenue</h6>
                                    <h2 class="mb-0">${{ number_format($totalRevenue ?? 0, 2) }}</h2>
                                </div>
                                <i class="ti ti-chart-line fs-1 opacity-50"></i>
                            </div>
                            <small>{{ number_format($totalOrders ?? 0) }} orders</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Performance Metrics --}}
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body text-center">
                            <h5 class="text-muted">Avg. Products per Brand</h5>
                            <h2 class="text-primary">{{ number_format($avgProductsPerBrand ?? 0, 1) }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body text-center">
                            <h5 class="text-muted">Avg. Views per Brand</h5>
                            <h2 class="text-success">{{ number_format($avgViewsPerBrand ?? 0, 0) }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body text-center">
                            <h5 class="text-muted">Avg. Rating</h5>
                            <h2 class="text-warning">{{ number_format($avgRating ?? 0, 1) }} <i class="ti ti-star"></i>
                            </h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body text-center">
                            <h5 class="text-muted">Avg. Revenue per Brand</h5>
                            <h2 class="text-info">${{ number_format($avgRevenuePerBrand ?? 0, 2) }}</h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                {{-- Top Brands by Views --}}
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="ti ti-eye me-1"></i> Most Viewed Brands</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        32
                                        <th>#</th>
                                        <th>Brand</th>
                                        <th>Code</th>
                                        <th>Views</th>
                                        <th>Products</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($topViewsBrands ?? [] as $index => $brand)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        @if ($brand->logo && Storage::disk('public')->exists('brands/logos/' . $brand->logo))
                                                            <img src="{{ Storage::disk('public')->url('brands/logos/' . $brand->logo) }}"
                                                                style="width: 30px; height: 30px; object-fit: cover; border-radius: 6px;">
                                                        @else
                                                            <div
                                                                style="width: 30px; height: 30px; background: #e9ecef; border-radius: 6px; display: flex; align-items: center; justify-content: center;">
                                                                <i class="ti ti-brand"></i>
                                                            </div>
                                                        @endif
                                                        {{ $brand->name }}
                                                    </div>
                                                </td>
                                                <td><span class="badge bg-secondary">{{ $brand->code }}</span></td>
                                                <td><span class="fw-bold">{{ number_format($brand->view_count) }}</span>
                                                </td>
                                                <td>{{ number_format($brand->product_count) }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">No data available</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Top Brands by Revenue --}}
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="ti ti-chart-line me-1"></i> Top Brands by Revenue</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        32
                                        <th>#</th>
                                        <th>Brand</th>
                                        <th>Revenue</th>
                                        <th>Orders</th>
                                        <th>Products</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($topRevenueBrands ?? [] as $index => $brand)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $brand->name }}</td>
                                                <td class="text-success fw-bold">
                                                    ${{ number_format($brand->total_revenue, 2) }}</td>
                                                <td>{{ number_format($brand->order_count) }}</td>
                                                <td>{{ number_format($brand->product_count) }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">No data available</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                {{-- Top Brands by Products --}}
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="ti ti-package me-1"></i> Brands with Most Products</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        32
                                        <th>#</th>
                                        <th>Brand</th>
                                        <th>Products</th>
                                        <th>Views</th>
                                        <th>Revenue</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($topProductBrands ?? [] as $index => $brand)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $brand->name }}</td>
                                                <td><span
                                                        class="badge bg-primary">{{ number_format($brand->product_count) }}</span>
                                                </td>
                                                <td>{{ number_format($brand->view_count) }}</td>
                                                <td class="text-success">${{ number_format($brand->total_revenue, 2) }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">No data available</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Top Rated Brands --}}
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="ti ti-star me-1"></i> Highest Rated Brands</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        32
                                        <th>#</th>
                                        <th>Brand</th>
                                        <th>Rating</th>
                                        <th>Reviews</th>
                                        <th>Orders</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($topRatedBrands ?? [] as $index => $brand)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $brand->name }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <span
                                                            class="text-warning me-1">{{ number_format($brand->avg_rating, 1) }}</span>
                                                        <i class="ti ti-star text-warning"></i>
                                                    </div>
                                                </td>
                                                <td>{{ number_format($brand->review_count) }}</td>
                                                <td>{{ number_format($brand->order_count) }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">No data available</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                {{-- Brand Distribution Chart --}}
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="ti ti-chart-pie me-1"></i> Brand Distribution by Products</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="brandDistributionChart" height="250"></canvas>
                        </div>
                    </div>
                </div>

                {{-- Brand Growth Chart --}}
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="ti ti-chart-bar me-1"></i> Brand Growth (Last 30 Days)</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="growthChart" height="250"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                {{-- Top Brands by Conversion Rate --}}
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="ti ti-chart-line me-1"></i> Brand Conversion Rates</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        32
                                        <th>#</th>
                                        <th>Brand</th>
                                        <th>Views</th>
                                        <th>Orders</th>
                                        <th>Conversion Rate</th>
                                        <th>Revenue</th>
                                    </thead>
                                    <tbody>
                                        @forelse($topViewsBrands ?? [] as $index => $brand)
                                            @php
                                                $conversionRate =
                                                    $brand->view_count > 0
                                                        ? round(($brand->order_count / $brand->view_count) * 100, 2)
                                                        : 0;
                                            @endphp
                                            32
                                            <td>{{ $index + 1 }}32
                                            <td>{{ $brand->name }}32
                                            <td>{{ number_format($brand->view_count) }}32
                                            <td>{{ number_format($brand->order_count) }}32
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="progress" style="height: 8px; width: 100px;">
                                                        <div class="progress-bar bg-{{ $conversionRate >= 10 ? 'success' : ($conversionRate >= 5 ? 'warning' : 'danger') }}"
                                                            style="width: {{ $conversionRate }}%;"></div>
                                                    </div>
                                                    <span class="fw-semibold">{{ $conversionRate }}%</span>
                                                </div>
                                                32
                                            <td class="text-success">${{ number_format($brand->total_revenue, 2) }}32
                                                32
                                            @empty
                                                32
                                            <td colspan="6" class="text-center">No data available32
                                                32
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Brand Performance Summary --}}
            <div class="row mt-4">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="ti ti-chart-pie me-1"></i> Brand Performance Summary</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 text-center">
                                    <h3 class="text-primary">{{ number_format($totalBrands ?? 0) }}</h3>
                                    <p class="text-muted">Total Brands</p>
                                </div>
                                <div class="col-md-3 text-center">
                                    <h3 class="text-success">{{ number_format($activeBrands ?? 0) }}</h3>
                                    <p class="text-muted">Active Brands</p>
                                </div>
                                <div class="col-md-3 text-center">
                                    <h3 class="text-warning">{{ number_format($featuredBrands ?? 0) }}</h3>
                                    <p class="text-muted">Featured Brands</p>
                                </div>
                                <div class="col-md-3 text-center">
                                    <h3 class="text-danger">{{ number_format($inactiveBrands ?? 0) }}</h3>
                                    <p class="text-muted">Inactive Brands</p>
                                </div>
                            </div>
                            <hr>
                            <div class="row mt-3">
                                <div class="col-md-4 text-center">
                                    <h4 class="text-info">${{ number_format($avgRevenuePerBrand ?? 0, 2) }}</h4>
                                    <p class="text-muted">Avg Revenue per Brand</p>
                                </div>
                                <div class="col-md-4 text-center">
                                    <h4 class="text-primary">{{ number_format($avgProductsPerBrand ?? 0, 1) }}</h4>
                                    <p class="text-muted">Avg Products per Brand</p>
                                </div>
                                <div class="col-md-4 text-center">
                                    <h4 class="text-success">{{ number_format($avgViewsPerBrand ?? 0, 0) }}</h4>
                                    <p class="text-muted">Avg Views per Brand</p>
                                </div>
                            </div>
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
        // Brand Distribution Chart
        const distCtx = document.getElementById('brandDistributionChart').getContext('2d');
        new Chart(distCtx, {
            type: 'doughnut',
            data: {
                labels: @json($topProductBrands->pluck('name') ?? []),
                datasets: [{
                    data: @json($topProductBrands->pluck('product_count') ?? []),
                    backgroundColor: ['#0d6efd', '#28a745', '#ffc107', '#dc3545', '#6f42c1', '#fd7e14',
                        '#20c997', '#e83e8c', '#0dcaf0', '#d63384'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                let value = context.raw || 0;
                                let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                let percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                return `${label}: ${value} products (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });

        // Growth Chart
        const growthCtx = document.getElementById('growthChart').getContext('2d');
        new Chart(growthCtx, {
            type: 'line',
            data: {
                labels: @json($growthLabels ?? []),
                datasets: [{
                    label: 'New Brands',
                    data: @json($growthData ?? []),
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13, 110, 253, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Brands'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Date'
                        }
                    }
                }
            }
        });
    </script>
@endpush

@push('styles')
    <style>
        .table td {
            vertical-align: middle;
        }

        .progress {
            background-color: #e9ecef;
            border-radius: 10px;
        }

        .progress-bar {
            border-radius: 10px;
            transition: width 0.3s ease;
        }

        .card {
            transition: box-shadow 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
    </style>
@endpush
