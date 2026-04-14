{{-- resources/views/admin/pages/colors/show.blade.php --}}
@extends('management.layouts.app')

@section('title', 'Color Details - ' . $color->name)

@section('content')
    <div class="page-content">
        <div class="page-container">
            <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                <div class="flex-grow-1">
                    <h4 class="fs-18 text-uppercase fw-bold mb-0">Color Details: {{ $color->name }}</h4>
                </div>
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.colors.index') }}">Colors</a></li>
                        <li class="breadcrumb-item active">{{ $color->name }}</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-4">
                    {{-- Color Preview Card --}}
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="card-title mb-0"><i class="ti ti-palette"></i> Color Preview</h5>
                        </div>
                        <div class="card-body text-center">
                            <div class="rounded mb-3 mx-auto"
                                style="width: 150px; height: 150px; background-color: {{ $color->code }}; border: 1px solid #ddd;">
                            </div>
                            <h3>{{ $color->name }}</h3>
                            <code class="fs-4">{{ $color->code }}</code>
                            @if ($color->rgb)
                                <div class="text-muted mt-2">{{ $color->rgb }}</div>
                            @endif
                            @if ($color->hsl)
                                <div class="text-muted">{{ $color->hsl }}</div>
                            @endif
                        </div>
                    </div>

                    {{-- Color Information Card --}}
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="card-title mb-0"><i class="ti ti-info-circle"></i> Color Information</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="120"><strong>ID:</strong></td>
                                    <td>#{{ $color->id }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Name:</strong></td>
                                    <td>{{ $color->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Slug:</strong></td>
                                    <td><code>{{ $color->slug }}</code></td>
                                </tr>
                                <tr>
                                    <td><strong>Order:</strong></td>
                                    <td>{{ $color->order }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>{!! $color->status_badge !!}</td>
                                </tr>
                                <tr>
                                    <td><strong>Featured:</strong></td>
                                    <td>
                                        @if ($color->is_featured)
                                        <span class="badge bg-warning">Yes</span>@else<span
                                                class="badge bg-secondary">No</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Popular:</strong></td>
                                    <td>
                                        @if ($color->is_popular)
                                        <span class="badge bg-danger">Yes</span>@else<span
                                                class="badge bg-secondary">No</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Created:</strong></td>
                                    <td>{{ $color->created_at->format('F d, Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Updated:</strong></td>
                                    <td>{{ $color->updated_at->diffForHumans() }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    {{-- Usage Stats Card --}}
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="card-title mb-0"><i class="ti ti-chart-bar"></i> Usage Statistics</h5>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-6 mb-3">
                                    <div class="bg-primary-subtle rounded p-3">
                                        <h3 class="mb-0">{{ number_format($color->usage_count) }}</h3>
                                        <small class="text-muted">Products Using</small>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="bg-info-subtle rounded p-3">
                                        <h3 class="mb-0">{{ number_format($color->analytics->sum('view_count')) }}</h3>
                                        <small class="text-muted">Total Views</small>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="bg-success-subtle rounded p-3">
                                        <h3 class="mb-0">{{ number_format($color->analytics->sum('order_count')) }}</h3>
                                        <small class="text-muted">Orders</small>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="bg-warning-subtle rounded p-3">
                                        <h3 class="mb-0">${{ number_format($color->analytics->sum('total_revenue'), 2) }}
                                        </h3>
                                        <small class="text-muted">Revenue</small>
                                    </div>
                                </div>
                            </div>
                            @if ($color->last_used_at)
                                <hr>
                                <div class="text-center"><small class="text-muted">Last used:
                                        {{ $color->last_used_at->diffForHumans() }}</small></div>
                            @endif
                        </div>
                    </div>

                    {{-- Approval Info Card --}}
                    @if ($color->approval_status !== 'approved')
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5 class="card-title mb-0"><i class="ti ti-check-circle"></i> Approval Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-2"><strong>Status:</strong> {!! $color->status_badge !!}</div>
                                @if ($color->requested_by)
                                    <div class="mb-2"><strong>Requested by:</strong> Vendor #{{ $color->requested_by }}
                                        @if ($color->requested_at)
                                            <br><small>{{ $color->requested_at->format('F d, Y') }}</small>
                                        @endif
                                    </div>
                                @endif
                                @if ($color->rejection_reason)
                                    <div class="mb-2"><strong>Rejection Reason:</strong><br><span
                                            class="text-danger">{{ $color->rejection_reason }}</span></div>
                                @endif
                                @if ($color->approved_by)
                                    <div class="mb-2"><strong>Processed by:</strong> Admin #{{ $color->approved_by }} on
                                        {{ $color->approved_at->format('F d, Y') }}</div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                <div class="col-lg-8">
                    {{-- Description Card --}}
                    @if ($color->description)
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5 class="card-title mb-0"><i class="ti ti-align-left"></i> Description</h5>
                            </div>
                            <div class="card-body">
                                <p>{{ $color->description }}</p>
                            </div>
                        </div>
                    @endif

                    {{-- Products Using This Color Card --}}
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="card-title mb-0"><i class="ti ti-package"></i> Products Using This Color
                                ({{ $color->products->count() }})</h5>
                        </div>
                        <div class="card-body">
                            @if ($color->products->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>Variant SKU</th>
                                                <th>Stock</th>
                                                <th>Price Adj.</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($color->products as $product)
                                                <tr>
                                                    <td><a
                                                            href="{{ route('admin.products.show', $product->id) }}">{{ $product->name }}</a>
                                                    </td>
                                                    <td><code>{{ $product->pivot->color_image ?: 'N/A' }}</code></td>
                                                    <td>{{ number_format($product->pivot->stock_quantity) }}</td>
                                                    <td>${{ number_format($product->pivot->price_adjustment, 2) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4"><i class="ti ti-package-off"
                                        style="font-size: 48px; opacity: 0.5;"></i>
                                    <p class="mt-2">No products using this color yet.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- SEO Information Card --}}
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="card-title mb-0"><i class="ti ti-meta-tag"></i> SEO Information</h5>
                        </div>
                        <div class="card-body">
                            @php
                                $seoScore = 0;
                                if (
                                    $color->meta_title &&
                                    strlen($color->meta_title) >= 30 &&
                                    strlen($color->meta_title) <= 60
                                ) {
                                    $seoScore += 34;
                                }
                                if (
                                    $color->meta_description &&
                                    strlen($color->meta_description) >= 120 &&
                                    strlen($color->meta_description) <= 160
                                ) {
                                    $seoScore += 33;
                                }
                                if ($color->focus_keyword) {
                                    $seoScore += 33;
                                }
                                $badgeColor =
                                    $seoScore >= 80
                                        ? 'success'
                                        : ($seoScore >= 60
                                            ? 'info'
                                            : ($seoScore >= 40
                                                ? 'warning'
                                                : 'secondary'));
                            @endphp
                            <div class="row">
                                <div class="col-md-4 text-center mb-3">
                                    <div class="bg-light rounded p-3">
                                        <div class="display-4 text-{{ $badgeColor }}">{{ $seoScore }}%</div>
                                        <div class="progress mt-2" style="height: 5px;">
                                            <div class="progress-bar bg-{{ $badgeColor }}"
                                                style="width: {{ $seoScore }}%;"></div>
                                        </div><span
                                            class="badge bg-{{ $badgeColor }} mt-2">{{ $seoScore >= 80 ? 'Excellent' : ($seoScore >= 60 ? 'Good' : ($seoScore >= 40 ? 'Average' : 'Poor')) }}</span>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-6"><small>Meta Title</small>
                                            <div class="{{ $color->meta_title ? 'text-success' : 'text-danger' }}">
                                                {{ $color->meta_title ? '✓ Present' : '✗ Missing' }}</div>
                                        </div>
                                        <div class="col-6"><small>Meta Description</small>
                                            <div class="{{ $color->meta_description ? 'text-success' : 'text-danger' }}">
                                                {{ $color->meta_description ? '✓ Present' : '✗ Missing' }}</div>
                                        </div>
                                        <div class="col-6 mt-2"><small>Focus Keyword</small>
                                            <div class="{{ $color->focus_keyword ? 'text-success' : 'text-danger' }}">
                                                {{ $color->focus_keyword ? '✓ Present' : '✗ Missing' }}</div>
                                        </div>
                                        <div class="col-6 mt-2"><small>OG Image</small>
                                            <div class="{{ $color->og_image ? 'text-success' : 'text-danger' }}">
                                                {{ $color->og_image ? '✓ Set' : '✗ Not set' }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if ($color->meta_title || $color->meta_description)
                                <hr>
                                @if ($color->meta_title)
                                    <div><strong>Meta Title:</strong> {{ $color->meta_title }}</div>
                                @endif
                                @if ($color->meta_description)
                                    <div class="mt-1"><strong>Meta Description:</strong> {{ $color->meta_description }}
                                    </div>
                                @endif
                                @if ($color->focus_keyword)
                                    <div class="mt-1"><strong>Focus Keyword:</strong> {{ $color->focus_keyword }}</div>
                                @endif
                            @endif
                        </div>
                    </div>

                    {{-- Recent Analytics Chart --}}
                    @if (isset($recentAnalytics) && $recentAnalytics->count() > 0)
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5 class="card-title mb-0"><i class="ti ti-chart-line"></i> Performance Trends (Last 30
                                    Days)</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="analyticsChart" height="250"></canvas>
                            </div>
                        </div>
                    @endif

                    {{-- Action Buttons --}}
                    <div class="card">
                        <div class="card-footer d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.colors.index') }}" class="btn btn-secondary"><i
                                    class="ti ti-arrow-left me-1"></i> Back</a>
                            @can('edit_colors')
                                <a href="{{ route('admin.colors.edit', $color->id) }}" class="btn btn-primary"><i
                                        class="ti ti-edit me-1"></i> Edit Color</a>
                            @endcan
                            @if ($color->approval_status === 'pending' && auth()->guard('admin')->user()->can('edit_colors'))
                                <button type="button" class="btn btn-info"
                                    onclick="approveColor({{ $color->id }})"><i class="ti ti-check"></i>
                                    Approve</button>
                                <button type="button" class="btn btn-warning"
                                    onclick="showRejectModal({{ $color->id }})"><i class="ti ti-x"></i>
                                    Reject</button>
                            @endif
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
        @if (isset($recentAnalytics) && $recentAnalytics->count() > 0)
            const analyticsData = @json($recentAnalytics->sortBy('date')->values());
            new Chart(document.getElementById('analyticsChart'), {
                type: 'line',
                data: {
                    labels: analyticsData.map(item => new Date(item.date).toLocaleDateString('en-US', {
                        month: 'short',
                        day: 'numeric'
                    })),
                    datasets: [{
                            label: 'Views',
                            data: analyticsData.map(item => item.view_count),
                            borderColor: '#0d6efd',
                            backgroundColor: 'rgba(13, 110, 253, 0.1)',
                            tension: 0.4,
                            fill: true,
                            yAxisID: 'y'
                        },
                        {
                            label: 'Orders',
                            data: analyticsData.map(item => item.order_count),
                            borderColor: '#198754',
                            backgroundColor: 'rgba(25, 135, 84, 0.1)',
                            tension: 0.4,
                            fill: true,
                            yAxisID: 'y'
                        },
                        {
                            label: 'Revenue ($)',
                            data: analyticsData.map(item => item.total_revenue),
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
                        intersect: false
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Views & Orders'
                            }
                        },
                        y1: {
                            beginAtZero: true,
                            position: 'right',
                            title: {
                                display: true,
                                text: 'Revenue ($)'
                            },
                            grid: {
                                drawOnChartArea: false
                            }
                        }
                    }
                }
            });
        @endif

        function approveColor(colorId) {
            Swal.fire({
                title: 'Approve Color?',
                text: 'Are you sure you want to approve this color?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                confirmButtonText: 'Yes, approve it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ url('admin/colors') }}/' + colorId + '/approve',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) Swal.fire({
                                icon: 'success',
                                title: 'Approved!',
                                text: response.message,
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => location.reload());
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Failed to approve color.',
                                confirmButtonColor: '#d33'
                            });
                        }
                    });
                }
            });
        }

        function showRejectModal(colorId) {
            Swal.fire({
                title: 'Reject Color',
                html: '<textarea id="rejectionReason" class="swal2-textarea" placeholder="Please provide a reason for rejection..." rows="3"></textarea>',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
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
                        url: '{{ url('admin/colors') }}/' + colorId + '/reject',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            rejection_reason: result.value.reason
                        },
                        success: function(response) {
                            if (response.success) Swal.fire({
                                icon: 'success',
                                title: 'Rejected!',
                                text: response.message,
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => location.reload());
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Failed to reject color.',
                                confirmButtonColor: '#d33'
                            });
                        }
                    });
                }
            });
        }
    </script>
@endpush
