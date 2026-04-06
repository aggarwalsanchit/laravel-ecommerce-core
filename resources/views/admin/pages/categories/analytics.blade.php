{{-- resources/views/admin/categories/analytics.blade.php --}}
@extends('management.layouts.app')

@section('title', 'Category Analytics')

@section('content')
    <div class="page-content">
        <div class="page-container">
            <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                <div class="flex-grow-1">
                    <h4 class="fs-18 text-uppercase fw-bold mb-0">Category Analytics</h4>
                </div>
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">Categories</a></li>
                        <li class="breadcrumb-item active">Analytics</li>
                    </ol>
                </div>
            </div>

            {{-- Statistics Overview --}}
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">Total Categories</h6>
                                    <h2 class="mb-0">{{ $totalCategories ?? 0 }}</h2>
                                </div>
                                <i class="ti ti-folder" style="font-size: 40px; opacity: 0.5;"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
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
                    <div class="card bg-info text-white">
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
                <div class="col-md-3">
                    <div class="card bg-warning text-dark">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">Total Revenue</h6>
                                    <h2 class="mb-0">${{ number_format($totalRevenue ?? 0, 2) }}</h2>
                                </div>
                                <i class="ti ti-chart-line" style="font-size: 40px; opacity: 0.5;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                {{-- Top Categories by Views --}}
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ti ti-eye me-1"></i> Top Categories by Views
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Category</th>
                                            <th>Views</th>
                                            <th>Growth</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($topViewsCategories ?? [] as $index => $category)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <a href="{{ route('admin.categories.show', $category->id) }}">
                                                        {{ $category->name }}
                                                    </a>
                                                    <br><small class="text-muted">{{ $category->path ?? '' }}</small>
                                                </td>
                                                <td>
                                                    <span class="fw-bold">{{ number_format($category->view_count) }}</span>
                                                </td>
                                                <td>
                                                    @if ($category->view_growth ?? 0 > 0)
                                                        <span class="badge bg-success">
                                                            <i class="ti ti-trending-up"></i>
                                                            +{{ $category->view_growth }}%
                                                        </span>
                                                    @elseif(($category->view_growth ?? 0) < 0)
                                                        <span class="badge bg-danger">
                                                            <i class="ti ti-trending-down"></i>
                                                            {{ $category->view_growth }}%
                                                        </span>
                                                    @else
                                                        <span class="badge bg-secondary">0%</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center">No data available</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Top Categories by Revenue --}}
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ti ti-chart-line me-1"></i> Top Categories by Revenue
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Category</th>
                                            <th>Revenue</th>
                                            <th>Products</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($topRevenueCategories ?? [] as $index => $category)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <a href="{{ route('admin.categories.show', $category->id) }}">
                                                        {{ $category->name }}
                                                    </a>
                                                </td>
                                                <td class="text-success fw-bold">
                                                    ${{ number_format($category->total_revenue, 2) }}
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge bg-info">{{ number_format($category->product_count) }}</span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center">No data available</td>
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
                {{-- Categories with Most Products --}}
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ti ti-package me-1"></i> Categories with Most Products
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Category</th>
                                            <th>Products</th>
                                            <th>Active Products</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($topProductCategories ?? [] as $index => $category)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <a href="{{ route('admin.categories.show', $category->id) }}">
                                                        {{ $category->name }}
                                                    </a>
                                                </td>
                                                <td>
                                                    <span
                                                        class="fw-bold">{{ number_format($category->product_count) }}</span>
                                                </td>
                                                <td>
                                                    {{ number_format($category->active_products ?? 0) }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center">No data available</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SEO Performance --}}
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ti ti-chart-line me-1"></i> SEO Performance
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Category</th>
                                            <th>SEO Score</th>
                                            <th>Status</th>
                                            <th>Missing Fields</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($seoPerformance ?? [] as $category)
                                            @php $seoStatus = $category->seo_status; @endphp
                                            <tr>
                                                <td>
                                                    <a href="{{ route('admin.categories.edit', $category->id) }}">
                                                        {{ $category->name }}
                                                    </a>
                                                </td>
                                                <td>
                                                    <div class="progress" style="height: 20px; width: 80px;">
                                                        <div class="progress-bar bg-{{ $seoStatus['badge'] }}"
                                                            style="width: {{ $category->seo_score }}%;">
                                                            {{ $category->seo_score }}%
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge bg-{{ $seoStatus['badge'] }}-subtle text-{{ $seoStatus['badge'] }}">
                                                        {{ $seoStatus['text'] }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @php
                                                        $missing = [];
                                                        if (!$category->meta_title) {
                                                            $missing[] = 'Meta Title';
                                                        }
                                                        if (!$category->meta_description) {
                                                            $missing[] = 'Meta Desc';
                                                        }
                                                        if (!$category->focus_keyword) {
                                                            $missing[] = 'Focus Keyword';
                                                        }
                                                    @endphp
                                                    @if (count($missing) > 0)
                                                        <small class="text-muted">{{ implode(', ', $missing) }}</small>
                                                    @else
                                                        <span class="text-success">Complete</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center">No data available</td>
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
                {{-- Category Growth Chart --}}
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ti ti-chart-bar me-1"></i> Category Growth (Last 30 Days)
                            </h5>
                        </div>
                        <div class="card-body">
                            <canvas id="categoryGrowthChart" height="100"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                {{-- Category Performance Summary --}}
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ti ti-chart-pie me-1"></i> Category Performance Summary
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <h3 class="text-primary">{{ $activeCategories ?? 0 }}</h3>
                                        <p class="text-muted">Active Categories</p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <h3 class="text-success">{{ $inactiveCategories ?? 0 }}</h3>
                                        <p class="text-muted">Inactive Categories</p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <h3 class="text-warning">{{ $featuredCategories ?? 0 }}</h3>
                                        <p class="text-muted">Featured Categories</p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <h3 class="text-info">{{ $popularCategories ?? 0 }}</h3>
                                        <p class="text-muted">Popular Categories</p>
                                    </div>
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
        $(document).ready(function() {
            // Category Growth Chart
            var ctx = document.getElementById('categoryGrowthChart').getContext('2d');
            var categoryGrowthChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($growthLabels ?? []) !!},
                    datasets: [{
                        label: 'New Categories',
                        data: {!! json_encode($growthData ?? []) !!},
                        borderColor: '#0d6efd',
                        backgroundColor: 'rgba(13, 110, 253, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Number of Categories'
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
        });
    </script>
@endpush
