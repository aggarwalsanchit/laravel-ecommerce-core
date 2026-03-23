{{-- resources/views/admin/collections/analytics.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Collection Analytics')

@section('content')
    <div class="page-content">
        <div class="page-container">
            <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                <div class="flex-grow-1">
                    <h4 class="fs-18 text-uppercase fw-bold mb-0">Collection Analytics</h4>
                </div>
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.collections.index') }}">Collections</a></li>
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
                                    <h6>Total Collections</h6>
                                    <h2 class="mb-0">{{ $totalCollections ?? 0 }}</h2>
                                </div>
                                <i class="ti ti-category fs-1 opacity-50"></i>
                            </div>
                            <small>Active: {{ $activeCollections ?? 0 }} | Inactive: {{ $inactiveCollections ?? 0 }}</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-dark">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6>Featured Collections</h6>
                                    <h2 class="mb-0">{{ $featuredCollections ?? 0 }}</h2>
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
                            <small>Across all collections</small>
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
                            <h5 class="text-muted">Avg. Products per Collection</h5>
                            <h2 class="text-primary">{{ number_format($avgProductsPerCollection ?? 0, 1) }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body text-center">
                            <h5 class="text-muted">Avg. Views per Collection</h5>
                            <h2 class="text-success">{{ number_format($avgViewsPerCollection ?? 0, 0) }}</h2>
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
                            <h5 class="text-muted">Avg. Revenue per Collection</h5>
                            <h2 class="text-info">${{ number_format($avgRevenuePerCollection ?? 0, 2) }}</h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                {{-- Top Collections by Views --}}
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="ti ti-eye me-1"></i> Most Viewed Collections</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        32
                                        <th>#</th>
                                        <th>Collection</th>
                                        <th>Code</th>
                                        <th>Views</th>
                                        <th>Products</th>
                                    </thead>
                                    <tbody>
                                        @forelse($topViewsCollections ?? [] as $index => $collection)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        @if ($collection->image && Storage::disk('public')->exists('collections/' . $collection->image))
                                                            <img src="{{ Storage::disk('public')->url('collections/' . $collection->image) }}"
                                                                style="width: 30px; height: 30px; object-fit: cover; border-radius: 6px;">
                                                        @else
                                                            <div
                                                                style="width: 30px; height: 30px; background: #e9ecef; border-radius: 6px; display: flex; align-items: center; justify-content: center;">
                                                                <i class="ti ti-category"></i>
                                                            </div>
                                                        @endif
                                                        {{ $collection->name }}
                                                    </div>
                                                </td>
                                                <td><span class="badge bg-secondary">{{ $collection->code }}</span></td>
                                                <td><span
                                                        class="fw-bold">{{ number_format($collection->view_count) }}</span>
                                                </td>
                                                <td>{{ number_format($collection->product_count) }}</td>
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

                {{-- Top Collections by Revenue --}}
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="ti ti-chart-line me-1"></i> Top Collections by Revenue</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        32
                                        <th>#</th>
                                        <th>Collection</th>
                                        <th>Revenue</th>
                                        <th>Orders</th>
                                        <th>Products</th>
                                    </thead>
                                    <tbody>
                                        @forelse($topRevenueCollections ?? [] as $index => $collection)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $collection->name }}</td>
                                                <td class="text-success fw-bold">
                                                    ${{ number_format($collection->total_revenue, 2) }}</td>
                                                <td>{{ number_format($collection->order_count) }}</td>
                                                <td>{{ number_format($collection->product_count) }}</td>
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
                {{-- Top Collections by Products --}}
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="ti ti-package me-1"></i> Collections with Most Products</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        32
                                        <th>#</th>
                                        <th>Collection</th>
                                        <th>Products</th>
                                        <th>Views</th>
                                        <th>Revenue</th>
                                    </thead>
                                    <tbody>
                                        @forelse($topProductCollections ?? [] as $index => $collection)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $collection->name }}</td>
                                                <td><span
                                                        class="badge bg-primary">{{ number_format($collection->product_count) }}</span>
                                                </td>
                                                <td>{{ number_format($collection->view_count) }}</td>
                                                <td class="text-success">
                                                    ${{ number_format($collection->total_revenue, 2) }}</td>
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

                {{-- Top Rated Collections --}}
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="ti ti-star me-1"></i> Highest Rated Collections</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        32
                                        <th>#</th>
                                        <th>Collection</th>
                                        <th>Rating</th>
                                        <th>Reviews</th>
                                        <th>Orders</th>
                                    </thead>
                                    <tbody>
                                        @forelse($topRatedCollections ?? [] as $index => $collection)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $collection->name }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <span
                                                            class="text-warning me-1">{{ number_format($collection->avg_rating, 1) }}</span>
                                                        <i class="ti ti-star text-warning"></i>
                                                    </div>
                                                </td>
                                                <td>{{ number_format($collection->review_count) }}</td>
                                                <td>{{ number_format($collection->order_count) }}</td>
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
                {{-- Collection Distribution Chart --}}
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="ti ti-chart-pie me-1"></i> Collection Distribution by Products</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="collectionDistributionChart" height="250"></canvas>
                        </div>
                    </div>
                </div>

                {{-- Collection Growth Chart --}}
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="ti ti-chart-bar me-1"></i> Collection Growth (Last 30 Days)</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="growthChart" height="250"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                {{-- Top Collections by Conversion Rate --}}
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="ti ti-chart-line me-1"></i> Collection Conversion Rates</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        32
                                        <th>#</th>
                                        <th>Collection</th>
                                        <th>Views</th>
                                        <th>Orders</th>
                                        <th>Conversion Rate</th>
                                        <th>Revenue</th>
                                    </thead>
                                    <tbody>
                                        @forelse($topViewsCollections ?? [] as $index => $collection)
                                            @php
                                                $conversionRate =
                                                    $collection->view_count > 0
                                                        ? round(
                                                            ($collection->order_count / $collection->view_count) * 100,
                                                            2,
                                                        )
                                                        : 0;
                                            @endphp
                                            32
                                            <td>{{ $index + 1 }}32
                                            <td>{{ $collection->name }}32
                                            <td>{{ number_format($collection->view_count) }}32
                                            <td>{{ number_format($collection->order_count) }}32
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="progress" style="height: 8px; width: 100px;">
                                                        <div class="progress-bar bg-{{ $conversionRate >= 10 ? 'success' : ($conversionRate >= 5 ? 'warning' : 'danger') }}"
                                                            style="width: {{ $conversionRate }}%;"></div>
                                                    </div>
                                                    <span class="fw-semibold">{{ $conversionRate }}%</span>
                                                </div>
                                                32
                                            <td class="text-success">${{ number_format($collection->total_revenue, 2) }}32
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center">No data available</td>
                                                </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Collection Performance Summary --}}
            <div class="row mt-4">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="ti ti-chart-pie me-1"></i> Collection Performance Summary</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 text-center">
                                    <h3 class="text-primary">{{ number_format($totalCollections ?? 0) }}</h3>
                                    <p class="text-muted">Total Collections</p>
                                </div>
                                <div class="col-md-3 text-center">
                                    <h3 class="text-success">{{ number_format($activeCollections ?? 0) }}</h3>
                                    <p class="text-muted">Active Collections</p>
                                </div>
                                <div class="col-md-3 text-center">
                                    <h3 class="text-warning">{{ number_format($featuredCollections ?? 0) }}</h3>
                                    <p class="text-muted">Featured Collections</p>
                                </div>
                                <div class="col-md-3 text-center">
                                    <h3 class="text-danger">{{ number_format($inactiveCollections ?? 0) }}</h3>
                                    <p class="text-muted">Inactive Collections</p>
                                </div>
                            </div>
                            <hr>
                            <div class="row mt-3">
                                <div class="col-md-4 text-center">
                                    <h4 class="text-info">${{ number_format($avgRevenuePerCollection ?? 0, 2) }}</h4>
                                    <p class="text-muted">Avg Revenue per Collection</p>
                                </div>
                                <div class="col-md-4 text-center">
                                    <h4 class="text-primary">{{ number_format($avgProductsPerCollection ?? 0, 1) }}</h4>
                                    <p class="text-muted">Avg Products per Collection</p>
                                </div>
                                <div class="col-md-4 text-center">
                                    <h4 class="text-success">{{ number_format($avgViewsPerCollection ?? 0, 0) }}</h4>
                                    <p class="text-muted">Avg Views per Collection</p>
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
        // Collection Distribution Chart
        const distCtx = document.getElementById('collectionDistributionChart').getContext('2d');
        new Chart(distCtx, {
            type: 'doughnut',
            data: {
                labels: @json($topProductCollections->pluck('name') ?? []),
                datasets: [{
                    data: @json($topProductCollections->pluck('product_count') ?? []),
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
                    label: 'New Collections',
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
                            text: 'Number of Collections'
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
