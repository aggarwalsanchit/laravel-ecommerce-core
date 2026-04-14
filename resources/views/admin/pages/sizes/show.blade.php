{{-- resources/views/admin/pages/sizes/show.blade.php --}}
@extends('management.layouts.app')

@section('title', 'Size Details - ' . $size->name)

@section('content')
<div class="page-content">
    <div class="page-container">
        <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
            <div class="flex-grow-1">
                <h4 class="fs-18 text-uppercase fw-bold mb-0">Size Details: {{ $size->name }}</h4>
            </div>
            <div class="text-end">
                <ol class="breadcrumb m-0 py-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.sizes.index') }}">Sizes</a></li>
                    <li class="breadcrumb-item active">{{ $size->name }}</li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4">
                {{-- Size Preview Card --}}
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ti ti-ruler"></i> Size Preview</h5>
                    </div>
                    <div class="card-body text-center">
                        @if($size->image)
                            <img src="{{ asset('storage/sizes/' . $size->image) }}" alt="{{ $size->image_alt ?? $size->name }}" class="img-fluid rounded mb-3" style="max-height: 150px;">
                        @else
                            <div class="rounded mb-3 mx-auto bg-light d-flex align-items-center justify-content-center" style="width: 150px; height: 150px;">
                                <i class="ti ti-ruler fs-1 text-primary"></i>
                            </div>
                        @endif
                        <h3>{{ $size->name }}</h3>
                        <code class="fs-4">{{ $size->code }}</code>
                        <div class="mt-2">
                            <span class="badge bg-{{ $size->gender == 'Men' ? 'primary' : ($size->gender == 'Women' ? 'danger' : ($size->gender == 'Unisex' ? 'info' : 'success')) }} fs-6">{{ $size->gender }}</span>
                        </div>
                    </div>
                </div>

                {{-- Size Information Card --}}
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ti ti-info-circle"></i> Size Information</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr><td width="120"><strong>ID:</strong></td><td>#{{ $size->id }}</td></tr>
                            <tr><td><strong>Name:</strong></td><td>{{ $size->name }}</td></tr>
                            <tr><td><strong>Slug:</strong></td><td><code>{{ $size->slug }}</code></td></tr>
                            <tr><td><strong>Code:</strong></td><td><code>{{ $size->code }}</code></td></tr>
                            <tr><td><strong>Gender:</strong></td><td>{!! $size->gender_badge !!}</td></tr>
                            <tr><td><strong>Order:</strong></td><td>{{ $size->order }}</td></tr>
                            <tr><td><strong>Status:</strong></td><td>{!! $size->status_badge !!}</td></tr>
                            <tr><td><strong>Featured:</strong></td><td>@if($size->is_featured)<span class="badge bg-warning">Yes</span>@else<span class="badge bg-secondary">No</span>@endif</td></tr>
                            <tr><td><strong>Popular:</strong></td><td>@if($size->is_popular)<span class="badge bg-danger">Yes</span>@else<span class="badge bg-secondary">No</span>@endif</td></tr>
                            <tr><td><strong>Created:</strong></td><td>{{ $size->created_at->format('F d, Y H:i') }}</td></tr>
                            <tr><td><strong>Updated:</strong></td><td>{{ $size->updated_at->diffForHumans() }}</td></tr>
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
                                    <h3 class="mb-0">{{ number_format($size->usage_count) }}</h3>
                                    <small class="text-muted">Products Using</small>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="bg-info-subtle rounded p-3">
                                    <h3 class="mb-0">{{ number_format($size->analytics->sum('view_count')) }}</h3>
                                    <small class="text-muted">Total Views</small>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="bg-success-subtle rounded p-3">
                                    <h3 class="mb-0">{{ number_format($size->analytics->sum('order_count')) }}</h3>
                                    <small class="text-muted">Orders</small>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="bg-warning-subtle rounded p-3">
                                    <h3 class="mb-0">${{ number_format($size->analytics->sum('total_revenue'), 2) }}</h3>
                                    <small class="text-muted">Revenue</small>
                                </div>
                            </div>
                        </div>
                        @if($size->last_used_at)
                            <hr><div class="text-center"><small class="text-muted">Last used: {{ $size->last_used_at->diffForHumans() }}</small></div>
                        @endif
                    </div>
                </div>

                {{-- Approval Info Card --}}
                @if($size->approval_status !== 'approved')
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ti ti-check-circle"></i> Approval Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-2"><strong>Status:</strong> {!! $size->status_badge !!}</div>
                        @if($size->requested_by)<div class="mb-2"><strong>Requested by:</strong> Vendor #{{ $size->requested_by }} @if($size->requested_at)<br><small>{{ $size->requested_at->format('F d, Y') }}</small>@endif</div>@endif
                        @if($size->rejection_reason)<div class="mb-2"><strong>Rejection Reason:</strong><br><span class="text-danger">{{ $size->rejection_reason }}</span></div>@endif
                        @if($size->approved_by)<div class="mb-2"><strong>Processed by:</strong> Admin #{{ $size->approved_by }} on {{ $size->approved_at->format('F d, Y') }}</div>@endif
                    </div>
                </div>
                @endif
            </div>

            <div class="col-lg-8">
                {{-- Description Card --}}
                @if($size->description)
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ti ti-align-left"></i> Description</h5>
                    </div>
                    <div class="card-body">
                        <p>{{ $size->description }}</p>
                    </div>
                </div>
                @endif

                {{-- Associated Categories Card --}}
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ti ti-folder"></i> Associated Categories ({{ $size->categories->count() }})</h5>
                    </div>
                    <div class="card-body">
                        @if($size->categories->count() > 0)
                            <div class="row">
                                @foreach($size->categories as $category)
                                    <div class="col-md-6 mb-2">
                                        <div class="d-flex align-items-center p-2 bg-light rounded">
                                            <i class="ti ti-folder text-primary me-2"></i>
                                            <a href="{{ route('admin.categories.show', $category->id) }}" class="text-decoration-none">
                                                {{ $category->name }}
                                            </a>
                                            @if($category->parent)
                                                <small class="text-muted ms-2">({{ $category->parent->name }})</small>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="ti ti-folder-off" style="font-size: 48px; opacity: 0.5;"></i>
                                <p class="text-muted mt-2">No categories associated with this size.</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Measurements Card --}}
                @if($size->chest || $size->waist || $size->hip || $size->inseam || $size->shoulder || $size->sleeve || $size->neck)
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ti ti-ruler"></i> Measurements (inches)</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @if($size->chest)<div class="col-md-4 mb-2"><strong>Chest/Bust:</strong> {{ $size->chest }}"</div>@endif
                            @if($size->waist)<div class="col-md-4 mb-2"><strong>Waist:</strong> {{ $size->waist }}"</div>@endif
                            @if($size->hip)<div class="col-md-4 mb-2"><strong>Hip:</strong> {{ $size->hip }}"</div>@endif
                            @if($size->inseam)<div class="col-md-4 mb-2"><strong>Inseam:</strong> {{ $size->inseam }}"</div>@endif
                            @if($size->shoulder)<div class="col-md-4 mb-2"><strong>Shoulder:</strong> {{ $size->shoulder }}"</div>@endif
                            @if($size->sleeve)<div class="col-md-4 mb-2"><strong>Sleeve:</strong> {{ $size->sleeve }}"</div>@endif
                            @if($size->neck)<div class="col-md-4 mb-2"><strong>Neck:</strong> {{ $size->neck }}"</div>@endif
                            @if($size->height)<div class="col-md-4 mb-2"><strong>Height:</strong> {{ $size->height }}'</div>@endif
                            @if($size->weight)<div class="col-md-4 mb-2"><strong>Weight:</strong> {{ $size->weight }} lbs</div>@endif
                        </div>
                    </div>
                </div>
                @endif

                {{-- Size Conversion Card --}}
                @if($size->us_size || $size->uk_size || $size->eu_size || $size->au_size || $size->jp_size || $size->cn_size || $size->int_size)
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ti ti-exchange"></i> Size Conversion Chart</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>US</th>
                                        <th>UK</th>
                                        <th>EU</th>
                                        <th>AU</th>
                                        <th>JP</th>
                                        <th>CN</th>
                                        <th>International</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="fw-semibold">{{ $size->us_size ?? '-' }}</td>
                                        <td>{{ $size->uk_size ?? '-' }}</td>
                                        <td>{{ $size->eu_size ?? '-' }}</td>
                                        <td>{{ $size->au_size ?? '-' }}</td>
                                        <td>{{ $size->jp_size ?? '-' }}</td>
                                        <td>{{ $size->cn_size ?? '-' }}</td>
                                        <td>{{ $size->int_size ?? '-' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Products Using This Size Card --}}
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ti ti-package"></i> Products Using This Size ({{ $size->products->count() }})</h5>
                    </div>
                    <div class="card-body">
                        @if($size->products->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr><th>Product</th><th>Vendor</th><th>Stock</th><th>Price Adj.</th></tr>
                                    </thead>
                                    <tbody>
                                        @foreach($size->products as $product)
                                            <tr>
                                                <td><a href="{{ route('admin.products.show', $product->id) }}">{{ $product->name }}</a></td>
                                                <td>{{ $product->pivot->vendor_id ? 'Vendor #'.$product->pivot->vendor_id : 'N/A' }}</td>
                                                <td>{{ number_format($product->pivot->stock_quantity) }}</td>
                                                <td>${{ number_format($product->pivot->price_adjustment, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="ti ti-package-off" style="font-size: 48px; opacity: 0.5;"></i>
                                <p class="text-muted mt-2">No products using this size yet.</p>
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
                            if ($size->meta_title && strlen($size->meta_title) >= 30 && strlen($size->meta_title) <= 60) $seoScore += 34;
                            if ($size->meta_description && strlen($size->meta_description) >= 120 && strlen($size->meta_description) <= 160) $seoScore += 33;
                            if ($size->focus_keyword) $seoScore += 33;
                            $badgeColor = $seoScore >= 80 ? 'success' : ($seoScore >= 60 ? 'info' : ($seoScore >= 40 ? 'warning' : 'secondary'));
                        @endphp
                        <div class="row">
                            <div class="col-md-4 text-center mb-3">
                                <div class="bg-light rounded p-3">
                                    <div class="display-4 text-{{ $badgeColor }}">{{ $seoScore }}%</div>
                                    <div class="progress mt-2" style="height: 5px;">
                                        <div class="progress-bar bg-{{ $badgeColor }}" style="width: {{ $seoScore }}%;"></div>
                                    </div>
                                    <span class="badge bg-{{ $badgeColor }} mt-2">{{ $seoScore >= 80 ? 'Excellent' : ($seoScore >= 60 ? 'Good' : ($seoScore >= 40 ? 'Average' : 'Poor')) }}</span>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-6"><small>Meta Title</small><div class="{{ $size->meta_title ? 'text-success' : 'text-danger' }}">{{ $size->meta_title ? '✓ Present' : '✗ Missing' }}</div></div>
                                    <div class="col-6"><small>Meta Description</small><div class="{{ $size->meta_description ? 'text-success' : 'text-danger' }}">{{ $size->meta_description ? '✓ Present' : '✗ Missing' }}</div></div>
                                    <div class="col-6 mt-2"><small>Focus Keyword</small><div class="{{ $size->focus_keyword ? 'text-success' : 'text-danger' }}">{{ $size->focus_keyword ? '✓ Present' : '✗ Missing' }}</div></div>
                                    <div class="col-6 mt-2"><small>OG Image</small><div class="{{ $size->og_image ? 'text-success' : 'text-danger' }}">{{ $size->og_image ? '✓ Set' : '✗ Not set' }}</div></div>
                                </div>
                            </div>
                        </div>
                        @if($size->meta_title || $size->meta_description)
                            <hr>
                            @if($size->meta_title)<div><strong>Meta Title:</strong> {{ $size->meta_title }}</div>@endif
                            @if($size->meta_description)<div class="mt-1"><strong>Meta Description:</strong> {{ $size->meta_description }}</div>@endif
                            @if($size->focus_keyword)<div class="mt-1"><strong>Focus Keyword:</strong> {{ $size->focus_keyword }}</div>@endif
                        @endif
                    </div>
                </div>

                {{-- Recent Analytics Chart --}}
                @if(isset($recentAnalytics) && $recentAnalytics->count() > 0)
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ti ti-chart-line"></i> Performance Trends (Last 30 Days)</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="analyticsChart" height="250"></canvas>
                    </div>
                </div>
                @endif

                {{-- Action Buttons --}}
                <div class="card">
                    <div class="card-footer d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.sizes.index') }}" class="btn btn-secondary"><i class="ti ti-arrow-left me-1"></i> Back</a>
                        @can('edit_sizes')<a href="{{ route('admin.sizes.edit', $size->id) }}" class="btn btn-primary"><i class="ti ti-edit me-1"></i> Edit Size</a>@endcan
                        @if($size->approval_status === 'pending' && auth()->guard('admin')->user()->can('edit_sizes'))
                            <button type="button" class="btn btn-info" onclick="approveSize({{ $size->id }})"><i class="ti ti-check"></i> Approve</button>
                            <button type="button" class="btn btn-warning" onclick="showRejectModal({{ $size->id }})"><i class="ti ti-x"></i> Reject</button>
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
@if(isset($recentAnalytics) && $recentAnalytics->count() > 0)
const analyticsData = @json($recentAnalytics->sortBy('date')->values());
new Chart(document.getElementById('analyticsChart'), {
    type: 'line',
    data: {
        labels: analyticsData.map(item => new Date(item.date).toLocaleDateString('en-US', { month: 'short', day: 'numeric' })),
        datasets: [
            { label: 'Views', data: analyticsData.map(item => item.view_count), borderColor: '#0d6efd', backgroundColor: 'rgba(13, 110, 253, 0.1)', tension: 0.4, fill: true, yAxisID: 'y' },
            { label: 'Orders', data: analyticsData.map(item => item.order_count), borderColor: '#198754', backgroundColor: 'rgba(25, 135, 84, 0.1)', tension: 0.4, fill: true, yAxisID: 'y' },
            { label: 'Revenue ($)', data: analyticsData.map(item => item.total_revenue), borderColor: '#ffc107', backgroundColor: 'rgba(255, 193, 7, 0.1)', tension: 0.4, fill: true, yAxisID: 'y1' }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        interaction: { mode: 'index', intersect: false },
        scales: {
            y: { beginAtZero: true, title: { display: true, text: 'Views & Orders' } },
            y1: { beginAtZero: true, position: 'right', title: { display: true, text: 'Revenue ($)' }, grid: { drawOnChartArea: false } }
        }
    }
});
@endif

function approveSize(sizeId) {
    Swal.fire({
        title: 'Approve Size?',
        text: 'Are you sure you want to approve this size?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        confirmButtonText: 'Yes, approve it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '{{ url("admin/sizes/requests") }}/' + sizeId + '/approve',
                type: 'POST',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({ icon: 'success', title: 'Approved!', text: response.message, timer: 1500, showConfirmButton: false })
                            .then(() => location.reload());
                    }
                },
                error: function() {
                    Swal.fire({ icon: 'error', title: 'Error!', text: 'Failed to approve size.', confirmButtonColor: '#d33' });
                }
            });
        }
    });
}

function showRejectModal(sizeId) {
    Swal.fire({
        title: 'Reject Size',
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
            return { reason: reason };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '{{ url("admin/sizes/requests") }}/' + sizeId + '/reject',
                type: 'POST',
                data: { _token: '{{ csrf_token() }}', rejection_reason: result.value.reason },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({ icon: 'success', title: 'Rejected!', text: response.message, timer: 1500, showConfirmButton: false })
                            .then(() => location.reload());
                    }
                },
                error: function() {
                    Swal.fire({ icon: 'error', title: 'Error!', text: 'Failed to reject size.', confirmButtonColor: '#d33' });
                }
            });
        }
    });
}
</script>
@endpush