{{-- resources/views/admin/products/analytics.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Product Analytics')

@section('content')
<div class="page-content">
    <div class="page-container">
        <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
            <div class="flex-grow-1">
                <h4 class="fs-18 text-uppercase fw-bold mb-0">Product Analytics: {{ $product->name }}</h4>
            </div>
            <div class="text-end">
                <ol class="breadcrumb m-0 py-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Products</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.products.show', $product) }}">{{ $product->name }}</a></li>
                    <li class="breadcrumb-item active">Analytics</li>
                </ol>
            </div>
        </div>

        {{-- Date Range Filter --}}
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <label class="form-label">Date Range</label>
                        <select id="dateRange" class="form-select">
                            <option value="7days">Last 7 Days</option>
                            <option value="30days" selected>Last 30 Days</option>
                            <option value="90days">Last 90 Days</option>
                            <option value="year">Last Year</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        {{-- Overview Statistics --}}
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h6>Total Views</h6>
                        <h2 id="totalViews">{{ number_format($product->view_count) }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h6>Total Orders</h6>
                        <h2 id="totalOrders">{{ number_format($product->order_count) }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h6>Total Revenue</h6>
                        <h2 id="totalRevenue">${{ number_format($product->total_sold, 2) }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-dark">
                    <div class="card-body">
                        <h6>Conversion Rate</h6>
                        <h2 id="conversionRate">{{ $product->view_count > 0 ? round(($product->order_count / $product->view_count) * 100, 2) : 0 }}%</h2>
                    </div>
                </div>
            </div>
        </div>

        {{-- Performance Chart --}}
        <div class="card mb-4">
            <div class="card-header">
                <h5>Performance Trend</h5>
            </div>
            <div class="card-body">
                <canvas id="performanceChart" height="300"></canvas>
            </div>
        </div>

        {{-- Customer Ratings --}}
        @if($product->review_count > 0)
        <div class="card mb-4">
            <div class="card-header">
                <h5>Customer Ratings</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 text-center">
                        <h1 class="display-1 text-warning">{{ number_format($product->avg_rating, 1) }}</h1>
                        <div>
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= round($product->avg_rating))
                                    <i class="ti ti-star-filled text-warning"></i>
                                @else
                                    <i class="ti ti-star text-muted"></i>
                                @endif
                            @endfor
                        </div>
                        <p class="text-muted">Based on {{ number_format($product->review_count) }} reviews</p>
                    </div>
                    <div class="col-md-8">
                        <canvas id="ratingChart" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Action Buttons --}}
        <div class="mt-3">
            <a href="{{ route('admin.products.show', $product) }}" class="btn btn-secondary">
                <i class="ti ti-arrow-left"></i> Back to Product
            </a>
            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-primary">
                <i class="ti ti-edit"></i> Edit Product
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let chart;

function loadAnalytics() {
    let dateRange = $('#dateRange').val();
    
    $.ajax({
        url: '{{ route("admin.products.analytics", $product) }}',
        data: { date_range: dateRange },
        success: function(response) {
            // Update statistics
            $('#totalViews').text(response.view_count.toLocaleString());
            $('#totalOrders').text(response.order_count.toLocaleString());
            $('#totalRevenue').text('$' + response.total_sold.toLocaleString());
            $('#conversionRate').text(response.conversion_rate + '%');
            
            // Update chart
            if (chart) {
                chart.destroy();
            }
            
            chart = new Chart(document.getElementById('performanceChart'), {
                type: 'line',
                data: {
                    labels: response.chart_labels,
                    datasets: [{
                        label: 'Views',
                        data: response.chart_views,
                        borderColor: '#0d6efd',
                        backgroundColor: 'rgba(13, 110, 253, 0.1)',
                        fill: true,
                        tension: 0.4
                    }, {
                        label: 'Orders',
                        data: response.chart_orders,
                        borderColor: '#28a745',
                        backgroundColor: 'rgba(40, 167, 69, 0.1)',
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: { position: 'top' }
                    }
                }
            });
        }
    });
}

$(document).ready(function() {
    loadAnalytics();
    $('#dateRange').on('change', loadAnalytics);
});
</script>
@endpush