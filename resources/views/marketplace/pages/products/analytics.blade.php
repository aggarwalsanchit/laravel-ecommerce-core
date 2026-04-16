{{-- resources/views/marketplace/pages/products/analytics.blade.php --}}
@extends('management.layouts.app')

@section('title', 'Product Analytics')

@section('content')
    <div class="page-content">
        <div class="page-container">
            <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                <div class="flex-grow-1">
                    <h4 class="fs-18 text-uppercase fw-bold mb-0">Product Analytics Dashboard</h4>
                    <p class="text-muted mb-0">Track your product performance</p>
                </div>
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('vendor.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('vendor.products.index') }}">Products</a></li>
                        <li class="breadcrumb-item active">Analytics</li>
                    </ol>
                </div>
            </div>

            {{-- Date Range Filter --}}
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('vendor.products.analytics') }}" class="row align-items-end">
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
                                    <h6 class="mb-0">Total Products</h6>
                                    <h2 class="mb-0">{{ $totalProducts ?? 0 }}</h2>
                                </div>
                                <i class="ti ti-package" style="font-size: 40px; opacity: 0.5;"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">Active Products</h6>
                                    <h2 class="mb-0">{{ $activeProducts ?? 0 }}</h2>
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
                                    <h6 class="mb-0">Total Orders</h6>
                                    <h2 class="mb-0">{{ number_format($totalOrders ?? 0) }}</h2>
                                </div>
                                <i class="ti ti-shopping-cart" style="font-size: 40px; opacity: 0.5;"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-danger text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">Pending Approval</h6>
                                    <h2 class="mb-0">{{ $pendingApproval ?? 0 }}</h2>
                                </div>
                                <i class="ti ti-clock" style="font-size: 40px; opacity: 0.5;"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-purple text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">Low Stock</h6>
                                    <h2 class="mb-0">{{ $lowStockCount ?? 0 }}</h2>
                                </div>
                                <i class="ti ti-alert-triangle" style="font-size: 40px; opacity: 0.5;"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-pink text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">Avg Order Value</h6>
                                    <h2 class="mb-0">${{ number_format($avgOrderValue ?? 0, 2) }}</h2>
                                </div>
                                <i class="ti ti-receipt" style="font-size: 40px; opacity: 0.5;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                {{-- Top Products by Revenue --}}
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ti ti-chart-line me-1"></i> Top Products by Revenue
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Product</th>
                                            <th>Revenue</th>
                                            <th>Orders</th>
                                            <th>Views</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($topRevenueProducts ?? [] as $index => $product)
                                            <tr>
                                                <td>{{ $index + 1 }}
                            </div>
                        </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                @php $mainImage = $product->images->where('is_main', true)->first(); @endphp
                                @if ($mainImage)
                                    <img src="{{ asset('storage/products/' . $mainImage->image) }}"
                                        alt="{{ $product->name }}" style="width: 30px; height: 30px; object-fit: cover;"
                                        class="rounded">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                        style="width: 30px; height: 30px;">
                                        <i class="ti ti-package text-primary"></i>
                                    </div>
                                @endif
                                <div>
                                    <a href="{{ route('vendor.products.show', $product->id) }}"
                                        class="text-decoration-none fw-semibold">
                                        {{ $product->name }}
                                    </a>
                                    <br>
                                    <small class="text-muted">SKU: {{ $product->sku ?? 'N/A' }}</small>
                                </div>
                            </div>
                    </div>
                </div>
                </td>
                <td class="text-success fw-bold">
                    ${{ number_format($product->total_revenue ?? 0, 2) }}
            </div>
        </div>
        </td>
        <td>
            <span class="badge bg-warning">{{ number_format($product->total_orders ?? 0) }}</span>
    </div>
    </div>
    </td>
    <td>
        <span class="badge bg-info">{{ number_format($product->total_views ?? 0) }}</span>
        </div>
        </div>
    </td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="text-center py-4">
            <i class="ti ti-chart-line" style="font-size: 48px; opacity: 0.5;"></i>
            <p class="mt-2">No revenue data available</p>
            </div>
            </div>
        </td>
    </tr>
    @endforelse
    </tbody>
    </table>
    </div>
    </div>
    </div>
    </div>

    {{-- Top Products by Views --}}
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ti ti-eye me-1"></i> Top Products by Views
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Product</th>
                                <th>Total Views</th>
                                <th>Avg Daily</th>
                                <th>Orders</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topViewsProducts ?? [] as $index => $product)
                                <tr>
                                    <td>{{ $index + 1 }}
                </div>
            </div>
            </td>
            <td>
                <div class="d-flex align-items-center gap-2">
                    @php $mainImage = $product->images->where('is_main', true)->first(); @endphp
                    @if ($mainImage)
                        <img src="{{ asset('storage/products/' . $mainImage->image) }}" alt="{{ $product->name }}"
                            style="width: 30px; height: 30px; object-fit: cover;" class="rounded">
                    @else
                        <div class="bg-light rounded d-flex align-items-center justify-content-center"
                            style="width: 30px; height: 30px;">
                            <i class="ti ti-package text-primary"></i>
                        </div>
                    @endif
                    <div>
                        <a href="{{ route('vendor.products.show', $product->id) }}"
                            class="text-decoration-none fw-semibold">
                            {{ $product->name }}
                        </a>
                        <br>
                        <small class="text-muted">SKU: {{ $product->sku ?? 'N/A' }}</small>
                    </div>
                </div>
        </div>
    </div>
    </td>
    <td>
        <span class="fw-bold text-primary">{{ number_format($product->total_views ?? 0) }}</span>
        </div>
        </div>
    </td>
    <td>
        <span class="badge bg-info">{{ number_format(($product->total_views ?? 0) / 30, 1) }}</span>
        </div>
        </div>
    </td>
    <td>
        <span class="badge bg-warning">{{ number_format($product->total_orders ?? 0) }}</span>
        </div>
        </div>
    </td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="text-center py-4">
            <i class="ti ti-eye-off" style="font-size: 48px; opacity: 0.5;"></i>
            <p class="mt-2">No view data available</p>
            </div>
            </div>
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
        {{-- Top Products by Orders --}}
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ti ti-shopping-cart me-1"></i> Top Products by Orders
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Product</th>
                                    <th>Orders</th>
                                    <th>Revenue</th>
                                    <th>Conversion</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topOrdersProducts ?? [] as $index => $product)
                                    @php
                                        $conversionRate =
                                            $product->total_views > 0
                                                ? ($product->total_orders / $product->total_views) * 100
                                                : 0;
                                    @endphp
                                    <tr>
                                        <td>{{ $index + 1 }}
                    </div>
                </div>
                </td>
                <td>
                    <div class="d-flex align-items-center gap-2">
                        @php $mainImage = $product->images->where('is_main', true)->first(); @endphp
                        @if ($mainImage)
                            <img src="{{ asset('storage/products/' . $mainImage->image) }}" alt="{{ $product->name }}"
                                style="width: 30px; height: 30px; object-fit: cover;" class="rounded">
                        @else
                            <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                style="width: 30px; height: 30px;">
                                <i class="ti ti-package text-primary"></i>
                            </div>
                        @endif
                        <div>
                            <a href="{{ route('vendor.products.show', $product->id) }}"
                                class="text-decoration-none fw-semibold">
                                {{ $product->name }}
                            </a>
                            <br>
                            <small class="text-muted">SKU: {{ $product->sku ?? 'N/A' }}</small>
                        </div>
                    </div>
            </div>
        </div>
        </td>
        <td>
            <span class="fw-bold text-warning">{{ number_format($product->total_orders ?? 0) }}</span>
    </div>
    </div>
    </td>
    <td class="text-success">
        ${{ number_format($product->total_revenue ?? 0, 2) }}
        </div>
        </div>
    </td>
    <td>
        <span class="badge bg-info">{{ number_format($conversionRate, 1) }}%</span>
        </div>
        </div>
    </td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="text-center py-4">
            <i class="ti ti-shopping-cart-off" style="font-size: 48px; opacity: 0.5;"></i>
            <p class="mt-2">No order data available</p>
            </div>
            </div>
        </td>
    </tr>
    @endforelse
    </tbody>
    </table>
    </div>
    </div>
    </div>
    </div>

    {{-- Low Stock Products --}}
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ti ti-alert-triangle me-1"></i> Low Stock Products
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Product</th>
                                <th>Current Stock</th>
                                <th>Threshold</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($lowStockProducts ?? [] as $product)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            @php $mainImage = $product->images->where('is_main', true)->first(); @endphp
                                            @if ($mainImage)
                                                <img src="{{ asset('storage/products/' . $mainImage->image) }}"
                                                    alt="{{ $product->name }}"
                                                    style="width: 30px; height: 30px; object-fit: cover;" class="rounded">
                                            @else
                                                <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                    style="width: 30px; height: 30px;">
                                                    <i class="ti ti-package text-primary"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <a href="{{ route('vendor.products.show', $product->id) }}"
                                                    class="text-decoration-none fw-semibold">
                                                    {{ $product->name }}
                                                </a>
                                                <br>
                                                <small class="text-muted">SKU: {{ $product->sku ?? 'N/A' }}</small>
                                            </div>
                                        </div>
                </div>
            </div>
            </td>
            <td class="fw-bold text-danger">
                {{ number_format($product->stock_quantity) }}
        </div>
    </div>
    </td>
    <td>
        <span class="badge bg-warning">{{ number_format($product->low_stock_threshold) }}</span>
        </div>
        </div>
    </td>
    <td>
        <span class="badge bg-warning text-dark">
            <i class="ti ti-alert-triangle"></i> Low Stock
        </span>
        </div>
        </div>
    </td>
    <td>
        <a href="{{ route('vendor.products.edit', $product->id) }}" class="btn btn-sm btn-primary">
            <i class="ti ti-edit"></i> Update Stock
        </a>
        </div>
        </div>
    </td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="text-center py-4">
            <i class="ti ti-package" style="font-size: 48px; opacity: 0.5;"></i>
            <p class="mt-2">No low stock products</p>
            </div>
            </div>
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

        {{-- Product Performance Summary --}}
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ti ti-chart-pie me-1"></i> Product Performance Summary
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center p-3">
                        <div class="row mb-3">
                            <div class="col-6">
                                <div class="bg-light rounded p-2">
                                    <h4 class="text-primary mb-0">{{ $totalProducts ?? 0 }}</h4>
                                    <small class="text-muted">Total Products</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="bg-light rounded p-2">
                                    <h4 class="text-success mb-0">{{ $activeProducts ?? 0 }}</h4>
                                    <small class="text-muted">Active</small>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-6">
                                <div class="bg-light rounded p-2">
                                    <h4 class="text-info mb-0">{{ number_format($avgViewsPerProduct ?? 0, 0) }}</h4>
                                    <small class="text-muted">Avg Views/Product</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="bg-light rounded p-2">
                                    <h4 class="text-warning mb-0">${{ number_format($avgRevenuePerProduct ?? 0, 2) }}</h4>
                                    <small class="text-muted">Avg Revenue/Product</small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="bg-light rounded p-2">
                                    <h4 class="text-danger mb-0">{{ number_format($avgOrdersPerProduct ?? 0, 0) }}</h4>
                                    <small class="text-muted">Avg Orders/Product</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="bg-light rounded p-2">
                                    <h4 class="text-purple mb-0">{{ number_format($conversionRate ?? 0, 1) }}%</h4>
                                    <small class="text-muted">Conversion Rate</small>
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
                        <a href="{{ route('vendor.products.create') }}" class="btn btn-primary">
                            <i class="ti ti-plus me-1"></i> Add New Product
                        </a>
                        <a href="{{ route('vendor.products.index') }}" class="btn btn-secondary">
                            <i class="ti ti-list me-1"></i> Manage Products
                        </a>
                        @if (($lowStockCount ?? 0) > 0)
                            <a href="{{ route('vendor.products.export-low-stock') }}" class="btn btn-warning">
                                <i class="ti ti-download me-1"></i> Export Low Stock Report
                            </a>
                        @endif
                        <button type="button" class="btn btn-info" onclick="refreshAnalytics()">
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
