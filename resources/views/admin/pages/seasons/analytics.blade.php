{{-- resources/views/admin/seasons/analytics.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Season Analytics')

@section('content')
    <div class="page-content">
        <div class="page-container">
            <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                <div class="flex-grow-1">
                    <h4 class="fs-18 text-uppercase fw-bold mb-0">Season Analytics</h4>
                </div>
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.seasons.index') }}">Seasons</a></li>
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
                                    <h6>Total Seasons</h6>
                                    <h2 class="mb-0">{{ $totalSeasons ?? 0 }}</h2>
                                </div>
                                <i class="ti ti-calendar fs-1 opacity-50"></i>
                            </div>
                            <small>Active: {{ $activeSeasons ?? 0 }} | Inactive: {{ $inactiveSeasons ?? 0 }}</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-dark">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6>Current Season</h6>
                                    <h2 class="mb-0">{{ $currentSeasons ?? 0 }}</h2>
                                </div>
                                <i class="ti ti-star fs-1 opacity-50"></i>
                            </div>
                            <small>{{ $currentSeason->name ?? 'No current season' }}</small>
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
                            <small>Across all seasons</small>
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
                            <h5 class="text-muted">Avg. Products per Season</h5>
                            <h2 class="text-primary">{{ number_format($avgProductsPerSeason ?? 0, 1) }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body text-center">
                            <h5 class="text-muted">Avg. Views per Season</h5>
                            <h2 class="text-success">{{ number_format($avgViewsPerSeason ?? 0, 0) }}</h2>
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
                            <h5 class="text-muted">Avg. Revenue per Season</h5>
                            <h2 class="text-info">${{ number_format($avgRevenuePerSeason ?? 0, 2) }}</h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                {{-- Top Seasons by Views --}}
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="ti ti-eye me-1"></i> Most Viewed Seasons</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        32
                                        <th>#</th>
                                        <th>Season</th>
                                        <th>Code</th>
                                        <th>Views</th>
                                        <th>Products</th>
                                    </thead>
                                    <tbody>
                                        @forelse($topViewsSeasons ?? [] as $index => $season)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        @if ($season->image && Storage::disk('public')->exists('seasons/' . $season->image))
                                                            <img src="{{ Storage::disk('public')->url('seasons/' . $season->image) }}"
                                                                style="width: 30px; height: 30px; object-fit: cover; border-radius: 6px;">
                                                        @else
                                                            <div
                                                                style="width: 30px; height: 30px; background: #e9ecef; border-radius: 6px; display: flex; align-items: center; justify-content: center;">
                                                                <i class="ti ti-{{ $season->icon ?? 'calendar' }}"></i>
                                                            </div>
                                                        @endif
                                                        {{ $season->name }}
                                                    </div>
                                                </td>
                                                <td><span class="badge bg-secondary">{{ $season->code }}</span></td>
                                                <td><span class="fw-bold">{{ number_format($season->view_count) }}</span>
                                                </td>
                                                <td>{{ number_format($season->product_count) }}</td>
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

                {{-- Top Seasons by Revenue --}}
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="ti ti-chart-line me-1"></i> Top Seasons by Revenue</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        32
                                        <th>#</th>
                                        <th>Season</th>
                                        <th>Revenue</th>
                                        <th>Orders</th>
                                        <th>Products</th>
                                    </thead>
                                    <tbody>
                                        @forelse($topRevenueSeasons ?? [] as $index => $season)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $season->name }}</td>
                                                <td class="text-success fw-bold">
                                                    ${{ number_format($season->total_revenue, 2) }}</td>
                                                <td>{{ number_format($season->order_count) }}</td>
                                                <td>{{ number_format($season->product_count) }}</td>
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
                {{-- Top Seasons by Products --}}
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="ti ti-package me-1"></i> Seasons with Most Products</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        32
                                        <th>#</th>
                                        <th>Season</th>
                                        <th>Products</th>
                                        <th>Views</th>
                                        <th>Revenue</th>
                                    </thead>
                                    <tbody>
                                        @forelse($topProductSeasons ?? [] as $index => $season)
                                            <tr>
                                                <td>{{ $index + 1 }}32
                                                <td>{{ $season->name }}32
                                                <td><span
                                                        class="badge bg-primary">{{ number_format($season->product_count) }}</span>32
                                                <td>{{ number_format($season->view_count) }}32
                                                <td class="text-success">${{ number_format($season->total_revenue, 2) }}32
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

                {{-- Top Rated Seasons --}}
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="ti ti-star me-1"></i> Highest Rated Seasons</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Season</th>
                                            <th>Rating</th>
                                            <th>Reviews</th>
                                            <th>Orders</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($topRatedSeasons ?? [] as $index => $season)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $season->name }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <span
                                                            class="text-warning me-1">{{ number_format($season->avg_rating, 1) }}</span>
                                                        <i class="ti ti-star text-warning"></i>
                                                    </div>
                                                </td>
                                                <td>{{ number_format($season->review_count) }}</td>
                                                <td>{{ number_format($season->order_count) }}</td>
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
                {{-- Season Distribution Chart --}}
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="ti ti-chart-pie me-1"></i> Season Distribution by Products</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="seasonDistributionChart" height="250"></canvas>
                        </div>
                    </div>
                </div>

                {{-- Season Growth Chart --}}
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="ti ti-chart-bar me-1"></i> Season Growth (Last 30 Days)</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="growthChart" height="250"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                {{-- Seasonal Performance Comparison --}}
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="ti ti-chart-line me-1"></i> Seasonal Performance Comparison</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="seasonalComparisonChart" height="300"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                {{-- Season Performance Summary --}}
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="ti ti-chart-pie me-1"></i> Season Performance Summary</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 text-center">
                                    <h3 class="text-primary">{{ number_format($totalSeasons ?? 0) }}</h3>
                                    <p class="text-muted">Total Seasons</p>
                                </div>
                                <div class="col-md-3 text-center">
                                    <h3 class="text-success">{{ number_format($activeSeasons ?? 0) }}</h3>
                                    <p class="text-muted">Active Seasons</p>
                                </div>
                                <div class="col-md-3 text-center">
                                    <h3 class="text-warning">{{ number_format($currentSeasons ?? 0) }}</h3>
                                    <p class="text-muted">Current Season</p>
                                </div>
                                <div class="col-md-3 text-center">
                                    <h3 class="text-danger">{{ number_format($inactiveSeasons ?? 0) }}</h3>
                                    <p class="text-muted">Inactive Seasons</p>
                                </div>
                            </div>
                            <hr>
                            <div class="row mt-3">
                                <div class="col-md-4 text-center">
                                    <h4 class="text-info">${{ number_format($avgRevenuePerSeason ?? 0, 2) }}</h4>
                                    <p class="text-muted">Avg Revenue per Season</p>
                                </div>
                                <div class="col-md-4 text-center">
                                    <h4 class="text-primary">{{ number_format($avgProductsPerSeason ?? 0, 1) }}</h4>
                                    <p class="text-muted">Avg Products per Season</p>
                                </div>
                                <div class="col-md-4 text-center">
                                    <h4 class="text-success">{{ number_format($avgViewsPerSeason ?? 0, 0) }}</h4>
                                    <p class="text-muted">Avg Views per Season</p>
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
        // Season Distribution Chart
        const distCtx = document.getElementById('seasonDistributionChart').getContext('2d');
        new Chart(distCtx, {
            type: 'doughnut',
            data: {
                labels: @json($topProductSeasons->pluck('name') ?? []),
                datasets: [{
                    data: @json($topProductSeasons->pluck('product_count') ?? []),
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
                    label: 'New Seasons',
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
                            text: 'Number of Seasons'
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

        // Seasonal Performance Comparison Chart
        const compareCtx = document.getElementById('seasonalComparisonChart').getContext('2d');
        new Chart(compareCtx, {
            type: 'bar',
            data: {
                labels: @json($topProductSeasons->pluck('name') ?? []),
                datasets: [{
                        label: 'Products',
                        data: @json($topProductSeasons->pluck('product_count') ?? []),
                        backgroundColor: 'rgba(13, 110, 253, 0.8)',
                        borderColor: '#0d6efd',
                        borderWidth: 1
                    },
                    {
                        label: 'Revenue ($)',
                        data: @json($topProductSeasons->pluck('total_revenue') ?? []),
                        backgroundColor: 'rgba(40, 167, 69, 0.8)',
                        borderColor: '#28a745',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                let value = context.raw || 0;
                                if (label === 'Revenue ($)') {
                                    return `${label}: $${value.toLocaleString()}`;
                                }
                                return `${label}: ${value.toLocaleString()}`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Count / Amount'
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
