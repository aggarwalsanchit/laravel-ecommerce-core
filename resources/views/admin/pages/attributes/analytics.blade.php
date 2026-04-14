{{-- resources/views/admin/pages/attributes/analytics.blade.php --}}
@extends('management.layouts.app')

@section('title', 'Attribute Analytics')

@section('content')
<div class="page-content">
    <div class="page-container">
        <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
            <div class="flex-grow-1">
                <h4 class="fs-18 text-uppercase fw-bold mb-0">Attribute Analytics Dashboard</h4>
            </div>
            <div class="text-end">
                <ol class="breadcrumb m-0 py-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.attributes.index') }}">Attributes</a></li>
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
                                <h6 class="mb-0">Total Attributes</h6>
                                <h2 class="mb-0">{{ $totalAttributes ?? 0 }}</h2>
                            </div>
                            <i class="ti ti-list" style="font-size: 40px; opacity: 0.5;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="mb-0">Active Attributes</h6>
                                <h2 class="mb-0">{{ $activeAttributes ?? 0 }}</h2>
                            </div>
                            <i class="ti ti-circle-check" style="font-size: 40px; opacity: 0.5;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="mb-0">Filterable</h6>
                                <h2 class="mb-0">{{ $filterableAttributes ?? 0 }}</h2>
                            </div>
                            <i class="ti ti-filter" style="font-size: 40px; opacity: 0.5;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-dark">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="mb-0">Required</h6>
                                <h2 class="mb-0">{{ $requiredAttributes ?? 0 }}</h2>
                            </div>
                            <i class="ti ti-asterisk" style="font-size: 40px; opacity: 0.5;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Attributes by Type Chart --}}
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ti ti-chart-pie"></i> Attributes by Type</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="typeChart" height="250"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ti ti-chart-bar"></i> Attribute Distribution</h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6 mb-3">
                                <div class="bg-success-subtle rounded p-3">
                                    <h3 class="mb-0">{{ $activeAttributes ?? 0 }}</h3>
                                    <small class="text-muted">Active</small>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="bg-secondary-subtle rounded p-3">
                                    <h3 class="mb-0">{{ ($totalAttributes ?? 0) - ($activeAttributes ?? 0) }}</h3>
                                    <small class="text-muted">Inactive</small>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="bg-info-subtle rounded p-3">
                                    <h3 class="mb-0">{{ $filterableAttributes ?? 0 }}</h3>
                                    <small class="text-muted">Filterable</small>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="bg-danger-subtle rounded p-3">
                                    <h3 class="mb-0">{{ $requiredAttributes ?? 0 }}</h3>
                                    <small class="text-muted">Required</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- Top Attributes by Usage --}}
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ti ti-package me-1"></i> Most Used Attributes</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr><th>#</th><th>Attribute</th><th>Type</th><th>Usage Count</th></tr>
                                </thead>
                                <tbody>
                                    @forelse($topUsedAttributes ?? [] as $index => $attribute)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    @if($attribute->icon)<i class="{{ $attribute->icon }}"></i>@else<i class="ti ti-input"></i>@endif
                                                    <span>{{ $attribute->name }}</span>
                                                </div>
                                            </td>
                                            <td><span class="badge bg-info">{{ $attribute->type_label }}</span></td>
                                            <td><span class="fw-bold">{{ number_format($attribute->analytics_sum_usage_count ?? 0) }} uses</span></td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="text-center py-4"><i class="ti ti-chart-bar" style="font-size: 48px; opacity: 0.5;"></i><p class="mt-2">No usage data available</p></td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Top Attributes by Views --}}
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ti ti-eye me-1"></i> Most Viewed Attributes</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr><th>#</th><th>Attribute</th><th>Type</th><th>Views</th></tr>
                                </thead>
                                <tbody>
                                    @forelse($topViewedAttributes ?? [] as $index => $attribute)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    @if($attribute->icon)<i class="{{ $attribute->icon }}"></i>@else<i class="ti ti-input"></i>@endif
                                                    <span>{{ $attribute->name }}</span>
                                                </div>
                                            </td>
                                            <td><span class="badge bg-info">{{ $attribute->type_label }}</span></td>
                                            <td><span class="fw-bold">{{ number_format($attribute->analytics_sum_view_count ?? 0) }} views</span></td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="text-center py-4"><i class="ti ti-eye-off" style="font-size: 48px; opacity: 0.5;"></i><p class="mt-2">No view data available</p></td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-footer d-flex gap-3">
                        <a href="{{ route('admin.attributes.create') }}" class="btn btn-primary"><i class="ti ti-plus me-1"></i> Add New Attribute</a>
                        <a href="{{ route('admin.attributes.index') }}" class="btn btn-secondary"><i class="ti ti-list me-1"></i> Manage Attributes</a>
                        <a href="{{ route('admin.attribute-groups.index') }}" class="btn btn-info"><i class="ti ti-layout-sidebar me-1"></i> Manage Groups</a>
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
// Attributes by Type Chart
@if(isset($attributesByType) && $attributesByType->count() > 0)
const typeCtx = document.getElementById('typeChart').getContext('2d');
const typeData = @json($attributesByType);
new Chart(typeCtx, {
    type: 'pie',
    data: {
        labels: typeData.map(item => {
            const types = {
                'text': 'Text', 'textarea': 'Textarea', 'number': 'Number', 'decimal': 'Decimal',
                'select': 'Select', 'multiselect': 'Multi-Select', 'checkbox': 'Checkbox', 'radio': 'Radio',
                'date': 'Date', 'datetime': 'Date/Time', 'color': 'Color', 'image': 'Image',
                'file': 'File', 'url': 'URL', 'email': 'Email', 'phone': 'Phone'
            };
            return types[item.type] || item.type;
        }),
        datasets: [{
            data: typeData.map(item => item.count),
            backgroundColor: [
                '#0d6efd', '#198754', '#ffc107', '#dc3545', '#6f42c1',
                '#fd7e14', '#20c997', '#d63384', '#0dcaf0', '#6c757d',
                '#adb5bd', '#343a40', '#f8f9fa', '#2c3e50', '#3498db', '#9b59b6'
            ],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: { position: 'right' },
            tooltip: { callbacks: { label: function(context) { return `${context.label}: ${context.raw} attributes`; } } }
        }
    }
});
@endif
</script>
@endpush

@push('styles')
<style>
    .bg-primary-subtle { background-color: rgba(13, 110, 253, 0.1); }
    .bg-success-subtle { background-color: rgba(25, 135, 84, 0.1); }
    .bg-warning-subtle { background-color: rgba(255, 193, 7, 0.1); }
    .bg-info-subtle { background-color: rgba(13, 202, 240, 0.1); }
    .bg-danger-subtle { background-color: rgba(220, 53, 69, 0.1); }
    .bg-secondary-subtle { background-color: rgba(108, 117, 125, 0.1); }
    .display-4 { font-size: 2.5rem; font-weight: 300; line-height: 1.2; }
</style>
@endpush