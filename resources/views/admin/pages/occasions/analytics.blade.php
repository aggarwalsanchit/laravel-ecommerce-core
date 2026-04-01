{{-- resources/views/admin/occasions/analytics.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Occasion Analytics')

@section('content')
    <div class="page-content">
        <div class="page-container">
            <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                <div class="flex-grow-1">
                    <h4 class="fs-18 text-uppercase fw-bold mb-0">Occasion Analytics</h4>
                </div>
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.occasions.index') }}">Occasions</a></li>
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
                                    <h6>Total Occasions</h6>
                                    <h2 class="mb-0">{{ $totalOccasions ?? 0 }}</h2>
                                </div>
                                <i class="ti ti-calendar-event fs-1 opacity-50"></i>
                            </div>
                            <small>Active: {{ $activeOccasions ?? 0 }} | Inactive: {{ $inactiveOccasions ?? 0 }}</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-dark">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6>Featured Occasions</h6>
                                    <h2 class="mb-0">{{ $featuredOccasions ?? 0 }}</h2>
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
                            <small>Across all occasions</small>
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
                            <h5 class="text-muted">Avg. Products per Occasion</h5>
                            <h2 class="text-primary">{{ number_format($avgProductsPerOccasion ?? 0, 1) }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body text-center">
                            <h5 class="text-muted">Avg. Views per Occasion</h5>
                            <h2 class="text-success">{{ number_format($avgViewsPerOccasion ?? 0, 0) }}</h2>
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
                            <h5 class="text-muted">Avg. Revenue per Occasion</h5>
                            <h2 class="text-info">${{ number_format($avgRevenuePerOccasion ?? 0, 2) }}</h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                {{-- Top Occasions by Views --}}
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="ti ti-eye me-1"></i> Most Viewed Occasions</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        32
                                        <th>#</th>
                                        <th>Occasion</th>
                                        <th>Code</th>
                                        <th>Views</th>
                                        <th>Products</th>
                                    </thead>
                                    <tbody>
                                        @forelse($topViewsOccasions ?? [] as $index => $occasion)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        @if ($occasion->image && Storage::disk('public')->exists('occasions/' . $occasion->image))
                                                            <img src="{{ Storage::disk('public')->url('occasions/' . $occasion->image) }}"
                                                                style="width: 30px; height: 30px; object-fit: cover; border-radius: 6px;">
                                                        @else
                                                            <div
                                                                style="width: 30px; height: 30px; background: #e9ecef; border-radius: 6px; display: flex; align-items: center; justify-content: center;">
                                                                <i class="ti ti-calendar-event"></i>
                                                            </div>
                                                        @endif
                                                        {{ $occasion->name }}
                                                    </div>
                                                </td>
                                                <td><span class="badge bg-secondary">{{ $occasion->code }}</span></td>
                                                <td><span class="fw-bold">{{ number_format($occasion->view_count) }}</span>
                                                </td>
                                                <td>{{ number_format($occasion->product_count) }}</td>
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

                {{-- Top Occasions by Revenue --}}
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="ti ti-chart-line me-1"></i> Top Occasions by Revenue</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        32
                                        <th>#</th>
                                        <th>Occasion</th>
                                        <th>Revenue</th>
                                        <th>Orders</th>
                                        <th>Products</th>
                                    </thead>
                                    <tbody>
                                        @forelse($topRevenueOccasions ?? [] as $index => $occasion)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        @if ($occasion->image && Storage::disk('public')->exists('occasions/' . $occasion->image))
                                                            <img src="{{ Storage::disk('public')->url('occasions/' . $occasion->image) }}"
                                                                style="width: 30px; height: 30px; object-fit: cover; border-radius: 6px;">
                                                        @else
                                                            <div
                                                                style="width: 30px; height: 30px; background: #e9ecef; border-radius: 6px; display: flex; align-items: center; justify-content: center;">
                                                                <i class="ti ti-calendar-event"></i>
                                                            </div>
                                                        @endif
                                                        {{ $occasion->name }}
                                                    </div>
                                                </td>
                                                <td class="text-success fw-bold">
                                                    ${{ number_format($occasion->total_revenue, 2) }}</td>
                                                <td>{{ number_format($occasion->order_count) }}</td>
                                                <td>{{ number_format($occasion->product_count) }}</td>
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
                {{-- Top Occasions by Products --}}
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="ti ti-package me-1"></i> Occasions with Most Products</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        32
                                        <th>#</th>
                                        <th>Occasion</th>
                                        <th>Products</th>
                                        <th>Views</th>
                                        <th>Revenue</th>
                                    </thead>
                                    <tbody>
                                        @forelse($topProductOccasions ?? [] as $index => $occasion)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $occasion->name }}</td>
                                                <td><span
                                                        class="badge bg-primary">{{ number_format($occasion->product_count) }}</span>
                                                </td>
                                                <td>{{ number_format($occasion->view_count) }}</td>
                                                <td class="text-success">${{ number_format($occasion->total_revenue, 2) }}
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

                {{-- Top Rated Occasions --}}
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="ti ti-star me-1"></i> Highest Rated Occasions</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        32
                                        <th>#</th>
                                        <th>Occasion</th>
                                        <th>Rating</th>
                                        <th>Reviews</th>
                                        <th>Orders</th>
                                    </thead>
                                    <tbody>
                                        @forelse($topRatedOccasions ?? [] as $index => $occasion)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $occasion->name }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <span
                                                            class="text-warning me-1">{{ number_format($occasion->avg_rating, 1) }}</span>
                                                        <i class="ti ti-star text-warning"></i>
                                                    </div>
                                                </td>
                                                <td>{{ number_format($occasion->review_count) }}</td>
                                                <td>{{ number_format($occasion->order_count) }}</td>
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
                {{-- Occasion Distribution Chart --}}
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="ti ti-chart-pie me-1"></i> Occasion Distribution by Products</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="occasionDistributionChart" height="250"></canvas>
                        </div>
                    </div>
                </div>

                {{-- Occasion Growth Chart --}}
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="ti ti-chart-bar me-1"></i> Occasion Growth (Last 30 Days)</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="growthChart" height="250"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                {{-- Top Occasions by Conversion Rate --}}
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="ti ti-chart-line me-1"></i> Occasion Conversion Rates</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        32
                                        <th>#</th>
                                        <th>Occasion</th>
                                        <th>Views</th>
                                        <th>Orders</th>
                                        <th>Conversion Rate</th>
                                        <th>Revenue</th>
                                    </thead>
                                    <tbody>
                                        @forelse($topViewsOccasions ?? [] as $index => $occasion)
                                            @php
                                                $conversionRate =
                                                    $occasion->view_count > 0
                                                        ? round(
                                                            ($occasion->order_count / $occasion->view_count) * 100,
                                                            2,
                                                        )
                                                        : 0;
                                            @endphp
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $occasion->name }}</td>
                                                <td>{{ number_format($occasion->view_count) }}</td>
                                                <td>{{ number_format($occasion->order_count) }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div class="progress" style="height: 8px; width: 100px;">
                                                            <div class="progress-bar bg-{{ $conversionRate >= 10 ? 'success' : ($conversionRate >= 5 ? 'warning' : 'danger') }}"
                                                                style="width: {{ $conversionRate }}%;"></div>
                                                        </div>
                                                        <span class="fw-semibold">{{ $conversionRate }}%</span>
                                                    </div>
                                                </td>
                                                <td class="text-success">${{ number_format($occasion->total_revenue, 2) }}
                                                </td>
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

            {{-- Occasion Performance Summary --}}
            <div class="row mt-4">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="ti ti-chart-pie me-1"></i> Occasion Performance Summary</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 text-center">
                                    <h3 class="text-primary">{{ number_format($totalOccasions ?? 0) }}</h3>
                                    <p class="text-muted">Total Occasions</p>
                                </div>
                                <div class="col-md-3 text-center">
                                    <h3 class="text-success">{{ number_format($activeOccasions ?? 0) }}</h3>
                                    <p class="text-muted">Active Occasions</p>
                                </div>
                                <div class="col-md-3 text-center">
                                    <h3 class="text-warning">{{ number_format($featuredOccasions ?? 0) }}</h3>
                                    <p class="text-muted">Featured Occasions</p>
                                </div>
                                <div class="col-md-3 text-center">
                                    <h3 class="text-danger">{{ number_format($inactiveOccasions ?? 0) }}</h3>
                                    <p class="text-muted">Inactive Occasions</p>
                                </div>
                            </div>
                            <hr>
                            <div class="row mt-3">
                                <div class="col-md-4 text-center">
                                    <h4 class="text-info">${{ number_format($avgRevenuePerOccasion ?? 0, 2) }}</h4>
                                    <p class="text-muted">Avg Revenue per Occasion</p>
                                </div>
                                <div class="col-md-4 text-center">
                                    <h4 class="text-primary">{{ number_format($avgProductsPerOccasion ?? 0, 1) }}</h4>
                                    <p class="text-muted">Avg Products per Occasion</p>
                                </div>
                                <div class="col-md-4 text-center">
                                    <h4 class="text-success">{{ number_format($avgViewsPerOccasion ?? 0, 0) }}</h4>
                                    <p class="text-muted">Avg Views per Occasion</p>
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
        // Occasion Distribution Chart
        const distCtx = document.getElementById('occasionDistributionChart').getContext('2d');
        new Chart(distCtx, {
            type: 'doughnut',
            data: {
                labels: @json($topProductOccasions->pluck('name') ?? []),
                datasets: [{
                    data: @json($topProductOccasions->pluck('product_count') ?? []),
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
                    label: 'New Occasions',
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
                            text: 'Number of Occasions'
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
