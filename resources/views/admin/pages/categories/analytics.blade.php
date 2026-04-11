{{-- resources/views/admin/pages/categories/analytics.blade.php --}}
@extends('management.layouts.app')

@section('title', 'Category Analytics')

@section('content')
    <div class="page-content">
        <div class="page-container">
            <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                <div class="flex-grow-1">
                    <h4 class="fs-18 text-uppercase fw-bold mb-0">Category Analytics Dashboard</h4>
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
                                    <h6 class="mb-0">Active Categories</h6>
                                    <h2 class="mb-0">{{ $activeCategories ?? 0 }}</h2>
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
                                <i class="ti ti-chart-line" style="font-size: 40px; opacity: 0.5;"></i>
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
                                    <h6 class="mb-0">Total Products</h6>
                                    <h2 class="mb-0">{{ number_format($totalProducts ?? 0) }}</h2>
                                </div>
                                <i class="ti ti-package" style="font-size: 40px; opacity: 0.5;"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-danger text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">Inactive Categories</h6>
                                    <h2 class="mb-0">{{ $inactiveCategories ?? 0 }}</h2>
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
                                    <h6 class="mb-0">Featured</h6>
                                    <h2 class="mb-0">{{ $featuredCategories ?? 0 }}</h2>
                                </div>
                                <i class="ti ti-star" style="font-size: 40px; opacity: 0.5;"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-pink text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">Popular</h6>
                                    <h2 class="mb-0">{{ $popularCategories ?? 0 }}</h2>
                                </div>
                                <i class="ti ti-fire" style="font-size: 40px; opacity: 0.5;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Approval Status Row --}}
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">Approved Categories</h6>
                                    <h2 class="mb-0">{{ $approvedCount ?? 0 }}</h2>
                                </div>
                                <i class="ti ti-check-circle" style="font-size: 40px; opacity: 0.5;"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-warning text-dark">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">Pending Approval</h6>
                                    <h2 class="mb-0">{{ $pendingCount ?? 0 }}</h2>
                                </div>
                                <i class="ti ti-clock" style="font-size: 40px; opacity: 0.5;"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-danger text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">Rejected Categories</h6>
                                    <h2 class="mb-0">{{ $rejectedCount ?? 0 }}</h2>
                                </div>
                                <i class="ti ti-x-circle" style="font-size: 40px; opacity: 0.5;"></i>
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
                                            <th>Total Views</th>
                                            <th>Avg Daily</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($topViewsCategories ?? [] as $index => $category)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <a href="{{ route('admin.categories.show', $category->id) }}"
                                                        class="text-decoration-none fw-semibold">
                                                        {{ $category->name }}
                                                    </a>
                                                    @if ($category->parent)
                                                        <br><small class="text-muted">Parent:
                                                            {{ $category->parent->name ?? 'N/A' }}</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span
                                                        class="fw-bold text-primary">{{ number_format($category->total_views ?? 0) }}</span>
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge bg-info">{{ number_format(($category->total_views ?? 0) / 30, 1) }}</span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center py-4">
                                                    <i class="ti ti-chart-bar" style="font-size: 48px; opacity: 0.5;"></i>
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
                                            <th>Orders</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($topRevenueCategories ?? [] as $index => $category)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <a href="{{ route('admin.categories.show', $category->id) }}"
                                                        class="text-decoration-none fw-semibold">
                                                        {{ $category->name }}
                                                    </a>
                                                </td>
                                                <td class="text-success fw-bold">
                                                    ${{ number_format($category->total_revenue ?? 0, 2) }}
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge bg-warning">{{ number_format($category->total_orders ?? 0) }}</span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center py-4">
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
                                            <th>Avg Products</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($topProductCategories ?? [] as $index => $category)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <a href="{{ route('admin.categories.show', $category->id) }}"
                                                        class="text-decoration-none fw-semibold">
                                                        {{ $category->name }}
                                                    </a>
                                                </td>
                                                <td>
                                                    <span
                                                        class="fw-bold text-info">{{ number_format($category->avg_products ?? 0) }}</span>
                                                </td>
                                                <td>
                                                    @if ($category->status ?? true)
                                                        <span class="badge bg-success">Active</span>
                                                    @else
                                                        <span class="badge bg-danger">Inactive</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center py-4">
                                                    <i class="ti ti-package-off"
                                                        style="font-size: 48px; opacity: 0.5;"></i>
                                                    <p class="mt-2">No product data available</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Pending Approval Categories --}}
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ti ti-clock me-1"></i> Pending Approval Categories
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Category Name</th>
                                            <th>Requested By</th>
                                            <th>Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($pendingCategories ?? [] as $category)
                                            <tr>
                                                <td>
                                                    <strong>{{ $category->name }}</strong>
                                                    @if ($category->parent)
                                                        <br><small class="text-muted">Parent:
                                                            {{ $category->parent->name ?? 'N/A' }}</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($category->requested_by)
                                                        Vendor #{{ $category->requested_by }}
                                                    @else
                                                        <span class="text-muted">System</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <small>{{ $category->created_at->format('M d, Y') }}</small>
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <button type="button" class="btn btn-success"
                                                            onclick="approveCategory({{ $category->id }})"
                                                            title="Approve">
                                                            <i class="ti ti-check"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-danger"
                                                            onclick="showRejectModal({{ $category->id }}, '{{ $category->name }}')"
                                                            title="Reject">
                                                            <i class="ti ti-x"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center py-4">
                                                    <i class="ti ti-check-circle"
                                                        style="font-size: 48px; opacity: 0.5;"></i>
                                                    <p class="mt-2">No pending categories</p>
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
                {{-- Rejected Categories --}}
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ti ti-x-circle me-1"></i> Rejected Categories
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Category Name</th>
                                            <th>Rejection Reason</th>
                                            <th>Rejected Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($rejectedCategories ?? [] as $category)
                                            <tr>
                                                <td>
                                                    <strong>{{ $category->name }}</strong>
                                                    @if ($category->parent)
                                                        <br><small class="text-muted">Parent:
                                                            {{ $category->parent->name ?? 'N/A' }}</small>
                                                    @endif
                                                </td>
                                                <td class="text-danger">
                                                    <small>{{ Str::limit($category->rejection_reason, 50) }}</small>
                                                </td>
                                                <td>
                                                    <small>{{ $category->updated_at->format('M d, Y') }}</small>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center py-4">
                                                    <i class="ti ti-check" style="font-size: 48px; opacity: 0.5;"></i>
                                                    <p class="mt-2">No rejected categories</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Category Growth Chart --}}
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ti ti-chart-bar me-1"></i> Category Growth (Last 30 Days)
                            </h5>
                        </div>
                        <div class="card-body">
                            <canvas id="categoryGrowthChart" height="250"></canvas>
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
                                <i class="ti ti-chart-pie me-1"></i> Category Hierarchy Summary
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="text-center p-3 bg-light rounded">
                                        <h3 class="text-primary mb-0">{{ $parentCategories ?? 0 }}</h3>
                                        <p class="text-muted mb-0">Parent Categories</p>
                                        <small class="text-muted">Level 0</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center p-3 bg-light rounded">
                                        <h3 class="text-success mb-0">{{ $childCategories ?? 0 }}</h3>
                                        <p class="text-muted mb-0">Child Categories</p>
                                        <small class="text-muted">Level 1+</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center p-3 bg-light rounded">
                                        <h3 class="text-info mb-0">{{ number_format($avgProductsPerCategory ?? 0, 1) }}
                                        </h3>
                                        <p class="text-muted mb-0">Avg Products/Category</p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center p-3 bg-light rounded">
                                        <h3 class="text-warning mb-0">{{ number_format($avgViewsPerCategory ?? 0, 0) }}
                                        </h3>
                                        <p class="text-muted mb-0">Avg Views/Category</p>
                                    </div>
                                </div>
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
                                <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                                    <i class="ti ti-plus me-1"></i> Add New Category
                                </a>
                                <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                                    <i class="ti ti-list me-1"></i> Manage Categories
                                </a>
                                @if (($pendingCount ?? 0) > 0)
                                    <button type="button" class="btn btn-warning" onclick="approveAllPending()">
                                        <i class="ti ti-check-all me-1"></i> Approve All Pending
                                    </button>
                                @endif
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
            // Category Growth Chart
            @if (isset($growthLabels) && isset($growthData) && count($growthLabels) > 0)
                var ctx = document.getElementById('categoryGrowthChart').getContext('2d');
                var categoryGrowthChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($growthLabels ?? []) !!},
                        datasets: [{
                            label: 'Products Added',
                            data: {!! json_encode($growthData ?? []) !!},
                            borderColor: '#0d6efd',
                            backgroundColor: 'rgba(13, 110, 253, 0.1)',
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: '#0d6efd',
                            pointBorderColor: '#fff',
                            pointRadius: 4,
                            pointHoverRadius: 6
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
                                callbacks: {
                                    label: function(context) {
                                        return context.dataset.label + ': ' + context.raw
                                            .toLocaleString() + ' products';
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Number of Products',
                                    color: '#6c757d'
                                },
                                ticks: {
                                    callback: function(value) {
                                        return value.toLocaleString();
                                    }
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: 'Date',
                                    color: '#6c757d'
                                }
                            }
                        }
                    }
                });
            @endif
        });

        // Approve Category
        function approveCategory(categoryId) {
            Swal.fire({
                title: 'Approve Category?',
                text: 'Are you sure you want to approve this category?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, approve it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ url('admin/categories') }}/' + categoryId + '/approve',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Approved!',
                                    text: response.message,
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => {
                                    location.reload();
                                });
                            }
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: xhr.responseJSON?.message ||
                                    'Failed to approve category.',
                                confirmButtonColor: '#d33'
                            });
                        }
                    });
                }
            });
        }

        // Show Reject Modal
        function showRejectModal(categoryId, categoryName) {
            Swal.fire({
                title: 'Reject Category',
                html: `
                    <p>Are you sure you want to reject <strong>"${categoryName}"</strong>?</p>
                    <textarea id="rejectionReason" class="swal2-textarea" placeholder="Please provide a reason for rejection..." rows="3"></textarea>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, reject it!',
                preConfirm: () => {
                    const reason = document.getElementById('rejectionReason').value;
                    if (!reason) {
                        Swal.showValidationMessage('Please provide a rejection reason');
                        return false;
                    }
                    return {
                        reason: reason
                    };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ url('admin/categories') }}/' + categoryId + '/reject',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            rejection_reason: result.value.reason
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Rejected!',
                                    text: response.message,
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => {
                                    location.reload();
                                });
                            }
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: xhr.responseJSON?.message || 'Failed to reject category.',
                                confirmButtonColor: '#d33'
                            });
                        }
                    });
                }
            });
        }

        // Approve All Pending
        function approveAllPending() {
            Swal.fire({
                title: 'Approve All Pending?',
                text: 'Are you sure you want to approve all pending categories?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, approve all!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route('admin.categories.bulk-action') }}',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            action: 'approve',
                            category_ids: JSON.stringify(@json($pendingCategories->pluck('id')->toArray() ?? []))
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success!',
                                    text: response.message,
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => {
                                    location.reload();
                                });
                            }
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: xhr.responseJSON?.message ||
                                    'Failed to approve categories.',
                                confirmButtonColor: '#d33'
                            });
                        }
                    });
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

        .bg-purple-subtle {
            background-color: rgba(111, 66, 193, 0.1);
        }

        .bg-pink-subtle {
            background-color: rgba(214, 51, 132, 0.1);
        }

        .table-responsive {
            max-height: 400px;
            overflow-y: auto;
        }

        .btn-group-sm .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }
    </style>
@endpush
