{{-- resources/views/admin/pages/brands/analytics.blade.php --}}
@extends('management.layouts.app')

@section('title', 'Brand Analytics')

@section('content')
    <div class="page-content">
        <div class="page-container">
            <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                <div class="flex-grow-1">
                    <h4 class="fs-18 text-uppercase fw-bold mb-0">Brand Analytics Dashboard</h4>
                </div>
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.brands.index') }}">Brands</a></li>
                        <li class="breadcrumb-item active">Analytics</li>
                    </ol>
                </div>
            </div>

            {{-- Date Range Filter --}}
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.brands.analytics') }}" class="row align-items-end">
                        <div class="col-md-3">
                            <label class="form-label">Start Date</label>
                            <input type="date" name="start_date" class="form-control"
                                value="{{ request('start_date', $startDate ?? now()->subDays(30)->format('Y-m-d')) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">End Date</label>
                            <input type="date" name="end_date" class="form-control"
                                value="{{ request('end_date', $endDate ?? now()->format('Y-m-d')) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Period</label>
                            <select name="period" class="form-select" onchange="this.form.submit()">
                                <option value="7" {{ request('period', 30) == 7 ? 'selected' : '' }}>Last 7 days
                                </option>
                                <option value="30" {{ request('period', 30) == 30 ? 'selected' : '' }}>Last 30 days
                                </option>
                                <option value="90" {{ request('period', 30) == 90 ? 'selected' : '' }}>Last 90 days
                                </option>
                                <option value="365" {{ request('period', 30) == 365 ? 'selected' : '' }}>Last 12 months
                                </option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="ti ti-filter me-1"></i> Apply Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Statistics Overview Row 1 --}}
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">Total Brands</h6>
                                    <h2 class="mb-0">{{ $totalBrands ?? 0 }}</h2>
                                </div>
                                <i class="ti ti-brand-airbnb" style="font-size: 40px; opacity: 0.5;"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">Active Brands</h6>
                                    <h2 class="mb-0">{{ $activeBrands ?? 0 }}</h2>
                                </div>
                                <i class="ti ti-circle-check" style="font-size: 40px; opacity: 0.5;"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">Total Views</h6>
                                    <h2 class="mb-0">{{ number_format($totalViews ?? 0) }}</h2>
                                </div>
                                <i class="ti ti-eye" style="font-size: 40px; opacity: 0.5;"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-dark">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">Total Revenue</h6>
                                    <h2 class="mb-0">${{ number_format($totalRevenue ?? 0, 2) }}</h2>
                                </div>
                                <i class="ti ti-currency-dollar" style="font-size: 40px; opacity: 0.5;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Second Row of Stats --}}
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-secondary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">Inactive Brands</h6>
                                    <h2 class="mb-0">{{ $inactiveBrands ?? 0 }}</h2>
                                </div>
                                <i class="ti ti-circle-x" style="font-size: 40px; opacity: 0.5;"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-purple text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">Featured Brands</h6>
                                    <h2 class="mb-0">{{ $featuredBrandsCount ?? 0 }}</h2>
                                </div>
                                <i class="ti ti-star" style="font-size: 40px; opacity: 0.5;"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-danger text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">Total Orders</h6>
                                    <h2 class="mb-0">{{ number_format($totalOrders ?? 0) }}</h2>
                                </div>
                                <i class="ti ti-shopping-cart" style="font-size: 40px; opacity: 0.5;"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-pink text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">Total Products</h6>
                                    <h2 class="mb-0">{{ number_format($totalProducts ?? 0) }}</h2>
                                </div>
                                <i class="ti ti-package" style="font-size: 40px; opacity: 0.5;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                {{-- Top Brands by Revenue --}}
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ti ti-chart-line me-1"></i> Top Brands by Revenue
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Brand</th>
                                            <th>Revenue</th>
                                            <th>Orders</th>
                                            <th>Views</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($topRevenueBrands ?? [] as $index => $brand)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        @if ($brand->logo)
                                                            <img src="{{ asset('storage/brands/' . $brand->logo) }}"
                                                                alt="{{ $brand->name }}"
                                                                style="width: 30px; height: 30px; object-fit: cover;"
                                                                class="rounded">
                                                        @else
                                                            <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                                style="width: 30px; height: 30px;">
                                                                <i class="ti ti-brand-airbnb text-primary"></i>
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <a href="{{ route('admin.brands.show', $brand->id) }}"
                                                                class="text-decoration-none fw-semibold">
                                                                {{ $brand->name }}
                                                            </a>
                                                            <br>
                                                            <small class="text-muted">Code: {{ $brand->code }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-success fw-bold">
                                                    ${{ number_format($brand->total_revenue ?? 0, 2) }}
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge bg-warning">{{ number_format($brand->total_orders ?? 0) }}</span>
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge bg-info">{{ number_format($brand->total_views ?? 0) }}</span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-4">
                                                    <i class="ti ti-chart-line"
                                                        style="font-size: 48px; opacity: 0.5;"></i>
                                                    <p class="mt-2">No revenue data available</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Top Brands by Views --}}
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ti ti-eye me-1"></i> Top Brands by Views
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Brand</th>
                                            <th>Total Views</th>
                                            <th>Avg Daily</th>
                                            <th>Orders</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($topViewsBrands ?? [] as $index => $brand)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        @if ($brand->logo)
                                                            <img src="{{ asset('storage/brands/' . $brand->logo) }}"
                                                                alt="{{ $brand->name }}"
                                                                style="width: 30px; height: 30px; object-fit: cover;"
                                                                class="rounded">
                                                        @else
                                                            <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                                style="width: 30px; height: 30px;">
                                                                <i class="ti ti-brand-airbnb text-primary"></i>
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <a href="{{ route('admin.brands.show', $brand->id) }}"
                                                                class="text-decoration-none fw-semibold">
                                                                {{ $brand->name }}
                                                            </a>
                                                            <br>
                                                            <small class="text-muted">Code: {{ $brand->code }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span
                                                        class="fw-bold text-primary">{{ number_format($brand->total_views ?? 0) }}</span>
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge bg-info">{{ number_format(($brand->total_views ?? 0) / 30, 1) }}</span>
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge bg-warning">{{ number_format($brand->total_orders ?? 0) }}</span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-4">
                                                    <i class="ti ti-eye-off" style="font-size: 48px; opacity: 0.5;"></i>
                                                    <p class="mt-2">No view data available</p>
                                                </td>
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
                {{-- Top Brands by Orders --}}
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ti ti-shopping-cart me-1"></i> Top Brands by Orders
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Brand</th>
                                            <th>Orders</th>
                                            <th>Revenue</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($topOrdersBrands ?? [] as $index => $brand)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        @if ($brand->logo)
                                                            <img src="{{ asset('storage/brands/' . $brand->logo) }}"
                                                                alt="{{ $brand->name }}"
                                                                style="width: 30px; height: 30px; object-fit: cover;"
                                                                class="rounded">
                                                        @else
                                                            <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                                style="width: 30px; height: 30px;">
                                                                <i class="ti ti-brand-airbnb text-primary"></i>
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <a href="{{ route('admin.brands.show', $brand->id) }}"
                                                                class="text-decoration-none fw-semibold">
                                                                {{ $brand->name }}
                                                            </a>
                                                            <br>
                                                            <small class="text-muted">Code: {{ $brand->code }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span
                                                        class="fw-bold text-warning">{{ number_format($brand->total_orders ?? 0) }}</span>
                                                </td>
                                                <td class="text-success">
                                                    ${{ number_format($brand->total_revenue ?? 0, 2) }}
                                                </td>
                                                <td>
                                                    @if ($brand->status ?? true)
                                                        <span class="badge bg-success">Active</span>
                                                    @else
                                                        <span class="badge bg-danger">Inactive</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-4">
                                                    <i class="ti ti-shopping-cart-off"
                                                        style="font-size: 48px; opacity: 0.5;"></i>
                                                    <p class="mt-2">No order data available</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Featured Brands --}}
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ti ti-star me-1"></i> Featured Brands
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Brand</th>
                                            <th>Products</th>
                                            <th>Code</th>
                                            <th>Order</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($featuredBrands ?? [] as $index => $brand)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        @if ($brand->logo)
                                                            <img src="{{ asset('storage/brands/' . $brand->logo) }}"
                                                                alt="{{ $brand->name }}"
                                                                style="width: 30px; height: 30px; object-fit: cover;"
                                                                class="rounded">
                                                        @else
                                                            <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                                style="width: 30px; height: 30px;">
                                                                <i class="ti ti-brand-airbnb text-primary"></i>
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <a href="{{ route('admin.brands.show', $brand->id) }}"
                                                                class="text-decoration-none fw-semibold">
                                                                {{ $brand->name }}
                                                            </a>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge bg-primary">{{ number_format($brand->products_count ?? 0) }}</span>
                                                </td>
                                                <td>
                                                    <code class="small">{{ $brand->code }}</code>
                                                </td>
                                                <td>
                                                    <span class="badge bg-secondary">{{ $brand->order ?? 0 }}</span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-4">
                                                    <i class="ti ti-star-off" style="font-size: 48px; opacity: 0.5;"></i>
                                                    <p class="mt-2">No featured brands</p>
                                                </td>
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
                {{-- Performance Chart --}}
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ti ti-chart-line me-1"></i> Performance Overview (Last 30 Days)
                            </h5>
                        </div>
                        <div class="card-body">
                            <canvas id="performanceChart" height="300"></canvas>
                        </div>
                    </div>
                </div>

                {{-- Brand Performance Summary --}}
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ti ti-chart-pie me-1"></i> Brand Performance Summary
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="text-center p-3">
                                <div class="row mb-3">
                                    <div class="col-6">
                                        <div class="bg-light rounded p-2">
                                            <h4 class="text-primary mb-0">{{ $totalBrands ?? 0 }}</h4>
                                            <small class="text-muted">Total Brands</small>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="bg-light rounded p-2">
                                            <h4 class="text-success mb-0">{{ $activeBrands ?? 0 }}</h4>
                                            <small class="text-muted">Active</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-6">
                                        <div class="bg-light rounded p-2">
                                            <h4 class="text-info mb-0">{{ number_format($avgViewsPerBrand ?? 0, 0) }}</h4>
                                            <small class="text-muted">Avg Views/Brand</small>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="bg-light rounded p-2">
                                            <h4 class="text-warning mb-0">
                                                ${{ number_format($avgRevenuePerBrand ?? 0, 2) }}</h4>
                                            <small class="text-muted">Avg Revenue/Brand</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="bg-light rounded p-2">
                                            <h4 class="text-danger mb-0">{{ number_format($avgOrdersPerBrand ?? 0, 0) }}
                                            </h4>
                                            <small class="text-muted">Avg Orders/Brand</small>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="bg-light rounded p-2">
                                            <h4 class="text-purple mb-0">{{ number_format($avgProductsPerBrand ?? 0, 1) }}
                                            </h4>
                                            <small class="text-muted">Avg Products/Brand</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="text-center">
                                <small class="text-muted">
                                    <i class="ti ti-info-circle"></i> Data based on selected date range
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                {{-- Quick Actions --}}
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ti ti-settings me-1"></i> Quick Actions
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex gap-3 flex-wrap">
                                <a href="{{ route('admin.brands.create') }}" class="btn btn-primary">
                                    <i class="ti ti-plus me-1"></i> Add New Brand
                                </a>
                                <a href="{{ route('admin.brands.index') }}" class="btn btn-secondary">
                                    <i class="ti ti-list me-1"></i> Manage Brands
                                </a>
                                <a href="{{ route('admin.brands.export') }}" class="btn btn-info">
                                    <i class="ti ti-download me-1"></i> Export Report
                                </a>
                                <button type="button" class="btn btn-warning" onclick="refreshAnalytics()">
                                    <i class="ti ti-refresh me-1"></i> Refresh Data
                                </button>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // Performance Chart
            @if (isset($chartLabels) && isset($chartViewsData) && count($chartLabels) > 0)
                const perfCtx = document.getElementById('performanceChart').getContext('2d');
                new Chart(perfCtx, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($chartLabels ?? []) !!},
                        datasets: [{
                                label: 'Views',
                                data: {!! json_encode($chartViewsData ?? []) !!},
                                borderColor: '#0d6efd',
                                backgroundColor: 'rgba(13, 110, 253, 0.1)',
                                tension: 0.4,
                                fill: true,
                                yAxisID: 'y'
                            },
                            {
                                label: 'Orders',
                                data: {!! json_encode($chartOrdersData ?? []) !!},
                                borderColor: '#198754',
                                backgroundColor: 'rgba(25, 135, 84, 0.1)',
                                tension: 0.4,
                                fill: true,
                                yAxisID: 'y'
                            },
                            {
                                label: 'Revenue',
                                data: {!! json_encode($chartRevenueData ?? []) !!},
                                borderColor: '#ffc107',
                                backgroundColor: 'rgba(255, 193, 7, 0.1)',
                                tension: 0.4,
                                fill: true,
                                yAxisID: 'y1'
                            }
                        ]
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
                                        if (context.dataset.label === 'Revenue') {
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
        });

        // Refresh Analytics
        function refreshAnalytics() {
            Swal.fire({
                title: 'Refresh Data?',
                text: 'This will fetch the latest analytics data.',
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#0d6efd',
                confirmButtonText: 'Yes, refresh!'
            }).then((result) => {
                if (result.isConfirmed) {
                    location.reload();
                }
            });
        }
    </script>
@endpush

@push('styles')
    <style>
        .bg-purple {
            background-color: #6f42c1 !important;
        }

        .bg-pink {
            background-color: #d63384 !important;
        }

        .text-purple {
            color: #6f42c1 !important;
        }

        .table-responsive {
            max-height: 400px;
            overflow-y: auto;
        }

        .table-responsive::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        .table-responsive::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .table-responsive::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }

        .table-responsive::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>
@endpush
