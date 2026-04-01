{{-- resources/views/admin/attributes/values/analytics.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Value Analytics')

@section('content')
<div class="page-content">
    <div class="page-container">
        <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
            <div class="flex-grow-1">
                <h4 class="fs-18 text-uppercase fw-bold mb-0">Value Analytics: {{ $value->value }}</h4>
            </div>
            <div class="text-end">
                <ol class="breadcrumb m-0 py-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.attributes.index') }}">Attributes</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.attributes.values.index', $value->attribute) }}">Values</a></li>
                    <li class="breadcrumb-item active">{{ $value->value }}</li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4">
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title">Value Information</h5>
                    </div>
                    <div class="card-body">
                        @if($value->image)
                            <div class="text-center mb-3">
                                <img src="{{ Storage::disk('public')->url('attributes/' . $value->attribute->slug . '/values/' . $value->image) }}" 
                                     style="max-width: 150px; border-radius: 8px;">
                            </div>
                        @endif
                        
                        <table class="table table-borderless">
                            32
                                <td width="120"><strong>Attribute:</strong>64
                                <td><a href="{{ route('admin.attributes.show', $value->attribute) }}">{{ $value->attribute->name }}</a>64
                            </tr>
                            <tr>
                                <td><strong>Value:</strong></td>
                                <td>{{ $value->value }}</td>
                            </tr>
                            @if($value->color_code)
                            <tr>
                                <td><strong>Color:</strong></td>
                                <td>
                                    <div style="width: 30px; height: 30px; background: {{ $value->color_code }}; border-radius: 6px; display: inline-block;"></div>
                                    <code>{{ $value->color_code }}</code>
                                </td>
                            </tr>
                            @endif
                            <tr>
                                <td><strong>Products:</strong></td>
                                <td>{{ $value->usage_count }} products</td>
                            </tr>
                            <tr>
                                <td><strong>Created:</strong></td>
                                <td>{{ $value->created_at->format('F d, Y') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-8">
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title">Performance Metrics</h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-4">
                                <div class="bg-primary-subtle rounded p-3">
                                    <h3>{{ number_format($value->view_count) }}</h3>
                                    <small class="text-muted">Total Views</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="bg-success-subtle rounded p-3">
                                    <h3>{{ number_format($value->order_count) }}</h3>
                                    <small class="text-muted">Orders</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="bg-warning-subtle rounded p-3">
                                    <h3>${{ number_format($value->total_revenue, 2) }}</h3>
                                    <small class="text-muted">Revenue Generated</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <h6>Conversion Rate</h6>
                            <div class="progress" style="height: 25px;">
                                @php
                                    $conversionRate = $value->view_count > 0 ? round(($value->order_count / $value->view_count) * 100, 2) : 0;
                                @endphp
                                <div class="progress-bar bg-{{ $conversionRate >= 10 ? 'success' : ($conversionRate >= 5 ? 'warning' : 'danger') }}" 
                                     style="width: {{ $conversionRate }}%">
                                    {{ $conversionRate }}%
                                </div>
                            </div>
                            <small class="text-muted">{{ $value->order_count }} orders out of {{ $value->view_count }} views</small>
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Performance Trend (Last 30 Days)</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="performanceChart" height="250"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Products Using This Value</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    32
                                        <th>ID</th>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Stock</th>
                                        <th>Orders</th>
                                        <th>Revenue</th>
                                    </thead>
                                <tbody>
                                    @foreach($value->products()->take(10)->get() as $product)
                                    <tr>
                                        <td>#{{ $product->id }}</td>
                                        <td>
                                            <a href="{{ route('admin.products.show', $product) }}">
                                                {{ $product->name }}
                                            </a>
                                        </td>
                                        <td>${{ number_format($product->price, 2) }}</td>
                                        <td>
                                            @if($product->stock > 0)
                                                <span class="badge bg-success">In Stock</span>
                                            @else
                                                <span class="badge bg-danger">Out of Stock</span>
                                            @endif
                                        </td>
                                        <td>{{ $product->order_count ?? 0 }}</td>
                                        <td>${{ number_format($product->total_sold_value ?? 0, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-3">
            <a href="{{ route('admin.attributes.values.index', $value->attribute) }}" class="btn btn-secondary">
                <i class="ti ti-arrow-left"></i> Back to Values
            </a>
            <a href="{{ route('admin.attributes.show', $value->attribute) }}" class="btn btn-info">
                <i class="ti ti-eye"></i> View Attribute
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('performanceChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: @json($chartLabels),
        datasets: [{
            label: 'Views',
            data: @json($chartViews),
            borderColor: '#0d6efd',
            backgroundColor: 'rgba(13, 110, 253, 0.1)',
            fill: true,
            tension: 0.4
        }, {
            label: 'Orders',
            data: @json($chartOrders),
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
</script>
@endpush