{{-- resources/views/admin/fabrics/analytics.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Fabric Analytics')

@section('content')
    <div class="page-content">
        <div class="page-container">
            <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                <div class="flex-grow-1">
                    <h4 class="fs-18 text-uppercase fw-bold mb-0">Fabric Analytics</h4>
                </div>
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.fabrics.index') }}">Fabrics</a></li>
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
                                    <h6>Total Fabrics</h6>
                                    <h2 class="mb-0">{{ $totalFabrics ?? 0 }}</h2>
                                </div>
                                <i class="ti ti-fabric fs-1 opacity-50"></i>
                            </div>
                            <small>Active: {{ $activeFabrics ?? 0 }} | Inactive: {{ $inactiveFabrics ?? 0 }}</small>
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
                            <small>Across all fabrics</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6>Total Views</h6>
                                    <h2 class="mb-0">{{ number_format($totalViews ?? 0) }}</h2>
                                </div>
                                <i class="ti ti-eye fs-1 opacity-50"></i>
                            </div>
                            <small>Fabric page views</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-dark">
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
                            <h5 class="text-muted">Avg. Products per Fabric</h5>
                            <h2 class="text-primary">{{ number_format($avgProductsPerFabric ?? 0, 1) }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body text-center">
                            <h5 class="text-muted">Avg. Views per Fabric</h5>
                            <h2 class="text-success">{{ number_format($avgViewsPerFabric ?? 0, 0) }}</h2>
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
                            <h5 class="text-muted">Conversion Rate</h5>
                            <h2 class="text-info">{{ $conversionRate ?? 0 }}%</h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                {{-- Top Fabrics by Views --}}
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="ti ti-eye me-1"></i> Most Viewed Fabrics</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        32
                                        <th>#</th>
                                        <th>Fabric</th>
                                        <th>Code</th>
                                        <th>Views</th>
                                        <th>Products</th>
                                    </thead>
                                    <tbody>
                                        @forelse($topViewsFabrics ?? [] as $index => $fabric)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        @if ($fabric->image && Storage::disk('public')->exists('fabrics/' . $fabric->image))
                                                            <img src="{{ Storage::disk('public')->url('fabrics/' . $fabric->image) }}"
                                                                style="width: 30px; height: 30px; object-fit: cover; border-radius: 6px;">
                                                        @else
                                                            <div
                                                                style="width: 30px; height: 30px; background: #e9ecef; border-radius: 6px; display: flex; align-items: center; justify-content: center;">
                                                                <i class="ti ti-fabric"></i>
                                                            </div>
                                                        @endif
                                                        {{ $fabric->name }}
                                                    </div>
                                                </td>
                                                <td><span class="badge bg-secondary">{{ $fabric->code }}</span></td>
                                                <td><span class="fw-bold">{{ number_format($fabric->view_count) }}</span>
                                                </td>
                                                <td>{{ number_format($fabric->product_count) }}</td>
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

                {{-- Top Fabrics by Revenue --}}
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="ti ti-chart-line me-1"></i> Top Fabrics by Revenue</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        32
                                        <th>#</th>
                                        <th>Fabric</th>
                                        <th>Revenue</th>
                                        <th>Orders</th>
                                        <th>Products</th>
                                    </thead>
                                    <tbody>
                                        @forelse($topRevenueFabrics ?? [] as $index => $fabric)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        @if ($fabric->image && Storage::disk('public')->exists('fabrics/' . $fabric->image))
                                                            <img src="{{ Storage::disk('public')->url('fabrics/' . $fabric->image) }}"
                                                                style="width: 30px; height: 30px; object-fit: cover; border-radius: 6px;">
                                                        @else
                                                            <div
                                                                style="width: 30px; height: 30px; background: #e9ecef; border-radius: 6px; display: flex; align-items: center; justify-content: center;">
                                                                <i class="ti ti-fabric"></i>
                                                            </div>
                                                        @endif
                                                        {{ $fabric->name }}
                                                    </div>
                                                </td>
                                                <td class="text-success fw-bold">
                                                    ${{ number_format($fabric->total_revenue, 2) }}</td>
                                                <td>{{ number_format($fabric->order_count) }}</td>
                                                <td>{{ number_format($fabric->product_count) }}</td>
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
                {{-- Top Fabrics by Products --}}
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="ti ti-package me-1"></i> Fabrics with Most Products</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        32
                                        <th>#</th>
                                        <th>Fabric</th>
                                        <th>Products</th>
                                        <th>Views</th>
                                        <th>Revenue</th>
                                    </thead>
                                    <tbody>
                                        @forelse($topProductFabrics ?? [] as $index => $fabric)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $fabric->name }}</td>
                                                <td><span
                                                        class="badge bg-primary">{{ number_format($fabric->product_count) }}</span>
                                                </td>
                                                <td>{{ number_format($fabric->view_count) }}</td>
                                                <td class="text-success">${{ number_format($fabric->total_revenue, 2) }}
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

                {{-- Top Fabrics by Rating --}}
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="ti ti-star me-1"></i> Highest Rated Fabrics</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        32
                                        <th>#</th>
                                        <th>Fabric</th>
                                        <th>Rating</th>
                                        <th>Reviews</th>
                                        <th>Orders</th>
                                    </thead>
                                    <tbody>
                                        @forelse($topRatedFabrics ?? [] as $index => $fabric)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $fabric->name }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <span
                                                            class="text-warning me-1">{{ number_format($fabric->avg_rating, 1) }}</span>
                                                        <i class="ti ti-star text-warning"></i>
                                                    </div>
                                                </td>
                                                <td>{{ number_format($fabric->review_count) }}</td>
                                                <td>{{ number_format($fabric->order_count) }}</td>
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
                {{-- Fabric Distribution Chart --}}
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="ti ti-chart-pie me-1"></i> Fabric Distribution by Products</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="fabricDistributionChart" height="250"></canvas>
                        </div>
                    </div>
                </div>

                {{-- Fabric Growth Chart --}}
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="ti ti-chart-bar me-1"></i> Fabric Growth (Last 30 Days)</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="growthChart" height="250"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                {{-- Care Instructions Popularity --}}
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="ti ti-wash me-1"></i> Care Instructions Popularity</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="card border">
                                        <div class="card-body text-center">
                                            <i class="ti ti-wash fs-1 text-primary"></i>
                                            <h4 class="mt-2">{{ $washingStats['machine_wash_cold'] ?? 0 }}</h4>
                                            <p class="text-muted">Machine Wash Cold</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card border">
                                        <div class="card-body text-center">
                                            <i class="ti ti-iron fs-1 text-primary"></i>
                                            <h4 class="mt-2">{{ $ironingStats['low_heat'] ?? 0 }}</h4>
                                            <p class="text-muted">Low Heat Ironing</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card border">
                                        <div class="card-body text-center">
                                            <i class="ti ti-dry fs-1 text-primary"></i>
                                            <h4 class="mt-2">{{ $dryingStats['line_dry'] ?? 0 }}</h4>
                                            <p class="text-muted">Line Dry</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card border">
                                        <div class="card-body text-center">
                                            <i class="ti ti-bleach fs-1 text-primary"></i>
                                            <h4 class="mt-2">{{ $bleachingStats['do_not_bleach'] ?? 0 }}</h4>
                                            <p class="text-muted">Do Not Bleach</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                {{-- Fabric Performance Summary --}}
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="ti ti-chart-pie me-1"></i> Fabric Performance Summary</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 text-center">
                                    <h3 class="text-primary">{{ number_format($totalFabrics ?? 0) }}</h3>
                                    <p class="text-muted">Total Fabrics</p>
                                </div>
                                <div class="col-md-4 text-center">
                                    <h3 class="text-success">{{ number_format($activeFabrics ?? 0) }}</h3>
                                    <p class="text-muted">Active Fabrics</p>
                                </div>
                                <div class="col-md-4 text-center">
                                    <h3 class="text-danger">{{ number_format($inactiveFabrics ?? 0) }}</h3>
                                    <p class="text-muted">Inactive Fabrics</p>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-4 text-center">
                                    <h4 class="text-info">${{ number_format($avgRevenuePerFabric ?? 0, 2) }}</h4>
                                    <p class="text-muted">Avg Revenue per Fabric</p>
                                </div>
                                <div class="col-md-4 text-center">
                                    <h4 class="text-warning">{{ number_format($avgProductsPerFabric ?? 0, 1) }}</h4>
                                    <p class="text-muted">Avg Products per Fabric</p>
                                </div>
                                <div class="col-md-4 text-center">
                                    <h4 class="text-secondary">{{ number_format($avgViewsPerFabric ?? 0, 0) }}</h4>
                                    <p class="text-muted">Avg Views per Fabric</p>
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
        // Fabric Distribution Chart
        const distCtx = document.getElementById('fabricDistributionChart').getContext('2d');
        new Chart(distCtx, {
            type: 'doughnut',
            data: {
                labels: @json($topProductFabrics->pluck('name') ?? []),
                datasets: [{
                    data: @json($topProductFabrics->pluck('product_count') ?? []),
                    backgroundColor: ['#0d6efd', '#28a745', '#ffc107', '#dc3545', '#6f42c1', '#fd7e14',
                        '#20c997', '#e83e8c'
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
                    label: 'New Fabrics',
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
                            text: 'Number of Fabrics'
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
