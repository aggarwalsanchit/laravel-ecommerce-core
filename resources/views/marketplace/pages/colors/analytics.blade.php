{{-- resources/views/admin/colors/analytics.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Color Analytics')

@section('content')
    <div class="page-content">
        <div class="page-container">
            <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                <div class="flex-grow-1">
                    <h4 class="fs-18 text-uppercase fw-bold mb-0">Color Analytics</h4>
                </div>
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.colors.index') }}">Colors</a></li>
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
                                    <h6>Total Colors</h6>
                                    <h2 class="mb-0">{{ $totalColors ?? 0 }}</h2>
                                </div>
                                <i class="ti ti-palette fs-1 opacity-50"></i>
                            </div>
                            <small>Active: {{ $activeColors ?? 0 }} | Inactive: {{ $inactiveColors ?? 0 }}</small>
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
                            <small>Across all colors</small>
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
                            <small>Color page views</small>
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

            <div class="row">
                {{-- Top Colors by Views --}}
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="ti ti-eye me-1"></i> Most Viewed Colors</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        32
                                        <th>#</th>
                                        <th>Color</th>
                                        <th>Code</th>
                                        <th>Views</th>
                                        <th>Products</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($topViewsColors ?? [] as $index => $color)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div
                                                            style="width: 30px; height: 30px; background: {{ $color->hex_code }}; border-radius: 6px; border: 1px solid #dee2e6;">
                                                        </div>
                                                        {{ $color->name }}
                                                    </div>
                                                </td>
                                                <td><span class="badge bg-secondary">{{ $color->code }}</span></td>
                                                <td><span class="fw-bold">{{ number_format($color->view_count) }}</span>
                                                </td>
                                                <td>{{ number_format($color->product_count) }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">No data</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Top Colors by Revenue --}}
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="ti ti-chart-line me-1"></i> Top Colors by Revenue</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        32
                                        <th>#</th>
                                        <th>Color</th>
                                        <th>Revenue</th>
                                        <th>Orders</th>
                                        <th>Products</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($topRevenueColors ?? [] as $index => $color)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div
                                                            style="width: 30px; height: 30px; background: {{ $color->hex_code }}; border-radius: 6px; border: 1px solid #dee2e6;">
                                                        </div>
                                                        {{ $color->name }}
                                                    </div>
                                                </td>
                                                <td class="text-success fw-bold">
                                                    ${{ number_format($color->total_revenue, 2) }}</td>
                                                <td>{{ number_format($color->order_count) }}</td>
                                                <td>{{ number_format($color->product_count) }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">No data</td>
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
                {{-- Top Colors by Products --}}
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="ti ti-package me-1"></i> Colors with Most Products</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        32
                                        <th>#</th>
                                        <th>Color</th>
                                        <th>Products</th>
                                        <th>Views</th>
                                        <th>Revenue</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($topProductColors ?? [] as $index => $color)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div
                                                            style="width: 30px; height: 30px; background: {{ $color->hex_code }}; border-radius: 6px; border: 1px solid #dee2e6;">
                                                        </div>
                                                        {{ $color->name }}
                                                    </div>
                                                </td>
                                                <td><span
                                                        class="badge bg-primary">{{ number_format($color->product_count) }}</span>
                                                </td>
                                                <td>{{ number_format($color->view_count) }}</td>
                                                <td class="text-success">${{ number_format($color->total_revenue, 2) }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">No data</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Color Distribution Chart --}}
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="ti ti-chart-pie me-1"></i> Color Distribution</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="colorDistributionChart" height="250"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                {{-- Color Growth Chart --}}
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="ti ti-chart-bar me-1"></i> Color Growth (Last 30 Days)</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="growthChart" height="100"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Color Performance Summary --}}
            <div class="row mt-4">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="ti ti-chart-pie me-1"></i> Color Performance Summary</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 text-center">
                                    <h3 class="text-primary">{{ $activeColors ?? 0 }}</h3>
                                    <p class="text-muted">Active Colors</p>
                                </div>
                                <div class="col-md-3 text-center">
                                    <h3 class="text-danger">{{ $inactiveColors ?? 0 }}</h3>
                                    <p class="text-muted">Inactive Colors</p>
                                </div>
                                <div class="col-md-3 text-center">
                                    <h3 class="text-success">{{ number_format($totalProducts ?? 0) }}</h3>
                                    <p class="text-muted">Total Products</p>
                                </div>
                                <div class="col-md-3 text-center">
                                    <h3 class="text-warning">
                                        ${{ number_format(($totalRevenue ?? 0) / max($totalColors ?? 1, 1), 2) }}</h3>
                                    <p class="text-muted">Avg Revenue per Color</p>
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
        // Color Distribution Chart
        const distCtx = document.getElementById('colorDistributionChart').getContext('2d');
        new Chart(distCtx, {
            type: 'doughnut',
            data: {
                labels: @json($topProductColors->pluck('name') ?? []),
                datasets: [{
                    data: @json($topProductColors->pluck('product_count') ?? []),
                    backgroundColor: @json(
                        $topProductColors->map(function ($c) {
                            return $c->hex_code;
                        }) ?? []
                    ),
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
                    label: 'New Colors',
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
    </style>
@endpush
