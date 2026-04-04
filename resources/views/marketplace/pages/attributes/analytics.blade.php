{{-- resources/views/admin/attributes/analytics.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Attribute Analytics')

@section('content')
<div class="page-content">
    <div class="page-container">
        <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
            <div class="flex-grow-1">
                <h4 class="fs-18 text-uppercase fw-bold mb-0">Attribute Analytics: {{ $attribute->name }}</h4>
            </div>
            <div class="text-end">
                <ol class="breadcrumb m-0 py-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.attributes.index') }}">Attributes</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.attributes.show', $attribute) }}">{{ $attribute->name }}</a></li>
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
                        <h2 id="totalViews">{{ number_format($attribute->total_views) }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h6>Total Products</h6>
                        <h2>{{ number_format($attribute->total_products) }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h6>Total Values</h6>
                        <h2>{{ number_format($attribute->values->count()) }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-dark">
                    <div class="card-body">
                        <h6>Total Revenue</h6>
                        <h2>${{ number_format($attribute->total_revenue, 2) }}</h2>
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

        {{-- Value Performance Table --}}
        <div class="card">
            <div class="card-header">
                <h5>Value Performance</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            32
                                <th>Value</th>
                                @if($attribute->type == 'color')
                                    <th>Color</th>
                                @endif
                                @if($attribute->has_image)
                                    <th>Image</th>
                                @endif
                                <th>Products</th>
                                <th>Views</th>
                                <th>Orders</th>
                                <th>Revenue</th>
                                <th>Conversion</th>
                            </thead>
                        <tbody>
                            @foreach($attribute->values->sortByDesc('order_count') as $value)
                            @php
                                $conversionRate = $value->view_count > 0 ? round(($value->order_count / $value->view_count) * 100, 2) : 0;
                            @endphp
                            早点
                                <td>
                                    @if($attribute->type == 'color' && $value->color_code)
                                        <span style="display: inline-block; width: 16px; height: 16px; background: {{ $value->color_code }}; border-radius: 3px; margin-right: 8px;"></span>
                                    @endif
                                    <a href="{{ route('admin.attribute-values.analytics', $value) }}">{{ $value->value }}</a>
                                    @if($value->is_default)
                                        <span class="badge bg-success ms-1">Default</span>
                                    @endif
                                </td>
                                @if($attribute->type == 'color')
                                    <td><code>{{ $value->color_code ?: '—' }}</code></td>
                                @endif
                                @if($attribute->has_image)
                                    <td>
                                        @if($value->image)
                                            <img src="{{ Storage::disk('public')->url('attributes/' . $attribute->slug . '/values/' . $value->image) }}" 
                                                 style="width: 35px; height: 35px; object-fit: cover; border-radius: 4px;">
                                        @else
                                            —
                                        @endif
                                    </td>
                                @endif
                                <td>{{ number_format($value->usage_count) }}</td>
                                <td>{{ number_format($value->view_count) }}</td>
                                <td>{{ number_format($value->order_count) }}</td>
                                <td class="text-success">${{ number_format($value->total_revenue, 2) }}</td>
                                <td>
                                    <div class="progress" style="height: 20px; width: 80px;">
                                        <div class="progress-bar bg-{{ $conversionRate >= 10 ? 'success' : ($conversionRate >= 5 ? 'warning' : 'danger') }}" 
                                             style="width: {{ $conversionRate }}%">
                                            {{ $conversionRate }}%
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="mt-3">
            <a href="{{ route('admin.attributes.show', $attribute) }}" class="btn btn-secondary">
                <i class="ti ti-arrow-left"></i> Back to Attribute
            </a>
            <a href="{{ route('admin.attributes.values.index', $attribute) }}" class="btn btn-info">
                <i class="ti ti-list-check"></i> Manage Values
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
        url: '{{ route("admin.attributes.analytics", $attribute) }}',
        data: { date_range: dateRange },
        success: function(response) {
            // Update total views
            $('#totalViews').text(response.total_views.toLocaleString());
            
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