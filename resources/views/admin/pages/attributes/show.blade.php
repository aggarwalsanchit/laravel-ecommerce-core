{{-- resources/views/admin/pages/attributes/show.blade.php --}}
@extends('management.layouts.app')

@section('title', 'Attribute Details - ' . $attribute->name)

@section('content')
<div class="page-content">
    <div class="page-container">
        <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
            <div class="flex-grow-1">
                <h4 class="fs-18 text-uppercase fw-bold mb-0">Attribute Details: {{ $attribute->name }}</h4>
            </div>
            <div class="text-end">
                <ol class="breadcrumb m-0 py-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.attributes.index') }}">Attributes</a></li>
                    <li class="breadcrumb-item active">{{ $attribute->name }}</li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4">
                {{-- Attribute Preview Card --}}
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ti ti-info-circle"></i> Attribute Preview</h5>
                    </div>
                    <div class="card-body text-center">
                        <div class="rounded mb-3 mx-auto bg-light d-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                            @if($attribute->icon)
                                <i class="{{ $attribute->icon }} fs-1 text-primary"></i>
                            @else
                                <i class="ti ti-input fs-1 text-primary"></i>
                            @endif
                        </div>
                        <h3>{{ $attribute->name }}</h3>
                        <code class="fs-6">{{ $attribute->slug }}</code>
                        <div class="mt-2">
                            <span class="badge bg-info">{{ $attribute->type_label }}</span>
                        </div>
                    </div>
                </div>

                {{-- Attribute Information Card --}}
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ti ti-info-circle"></i> Attribute Information</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr><td width="120"><strong>ID:</strong></td><td>#{{ $attribute->id }}</td></tr>
                            <tr><td><strong>Name:</strong></td><td>{{ $attribute->name }}</td></tr>
                            <tr><td><strong>Slug:</strong></td><td><code>{{ $attribute->slug }}</code></td></tr>
                            <tr><td><strong>Type:</strong></td><td><span class="badge bg-info">{{ $attribute->type_label }}</span></td></tr>
                            @if($attribute->unit)<tr><td><strong>Unit:</strong></td><td>{{ $attribute->unit }}</td></tr>@endif
                            @if($attribute->group)<tr><td><strong>Group:</strong></td><td>{{ $attribute->group->name }}</td></tr>@endif
                            <tr><td><strong>Order:</strong></td><td>{{ $attribute->order }}</td></tr>
                            <tr><td><strong>Status:</strong></td><td>{!! $attribute->status_badge !!}</td></tr>
                            <tr><td><strong>Featured:</strong></td><td>@if($attribute->is_featured)<span class="badge bg-warning">Yes</span>@else<span class="badge bg-secondary">No</span>@endif</td></tr>
                            <tr><td><strong>Required:</strong></td><td>@if($attribute->is_required)<span class="badge bg-danger">Yes</span>@else<span class="badge bg-secondary">No</span>@endif</td></tr>
                            <tr><td><strong>Filterable:</strong></td><td>@if($attribute->is_filterable)<span class="badge bg-success">Yes</span>@else<span class="badge bg-secondary">No</span>@endif</td></tr>
                            <tr><td><strong>Searchable:</strong></td><td>@if($attribute->is_searchable)<span class="badge bg-success">Yes</span>@else<span class="badge bg-secondary">No</span>@endif</td></tr>
                            <tr><td><strong>Comparable:</strong></td><td>@if($attribute->is_comparable)<span class="badge bg-success">Yes</span>@else<span class="badge bg-secondary">No</span>@endif</td></tr>
                            <tr><td><strong>Show on Product Page:</strong></td><td>@if($attribute->show_on_product_page)<span class="badge bg-success">Yes</span>@else<span class="badge bg-secondary">No</span>@endif</td></tr>
                            <tr><td><strong>Created:</strong></td><td>{{ $attribute->created_at->format('F d, Y H:i') }}</td></tr>
                            <tr><td><strong>Updated:</strong></td><td>{{ $attribute->updated_at->diffForHumans() }}</td></tr>
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
                                    <h3 class="mb-0">{{ number_format($attribute->productValues()->count()) }}</h3>
                                    <small class="text-muted">Products Using</small>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="bg-info-subtle rounded p-3">
                                    <h3 class="mb-0">{{ number_format($attribute->analytics->sum('view_count')) }}</h3>
                                    <small class="text-muted">Total Views</small>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="bg-success-subtle rounded p-3">
                                    <h3 class="mb-0">{{ number_format($attribute->analytics->sum('order_count')) }}</h3>
                                    <small class="text-muted">Orders</small>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="bg-warning-subtle rounded p-3">
                                    <h3 class="mb-0">${{ number_format($attribute->analytics->sum('total_revenue'), 2) }}</h3>
                                    <small class="text-muted">Revenue</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Approval Info Card --}}
                @if($attribute->approval_status !== 'approved')
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ti ti-check-circle"></i> Approval Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-2"><strong>Status:</strong> {!! $attribute->status_badge !!}</div>
                        @if($attribute->requested_by)<div class="mb-2"><strong>Requested by:</strong> Vendor #{{ $attribute->requested_by }} @if($attribute->requested_at)<br><small>{{ $attribute->requested_at->format('F d, Y') }}</small>@endif</div>@endif
                        @if($attribute->rejection_reason)<div class="mb-2"><strong>Rejection Reason:</strong><br><span class="text-danger">{{ $attribute->rejection_reason }}</span></div>@endif
                        @if($attribute->approved_by)<div class="mb-2"><strong>Processed by:</strong> Admin #{{ $attribute->approved_by }} on {{ $attribute->approved_at->format('F d, Y') }}</div>@endif
                    </div>
                </div>
                @endif
            </div>

            <div class="col-lg-8">
                {{-- Description Card --}}
                @if($attribute->description)
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ti ti-align-left"></i> Description</h5>
                    </div>
                    <div class="card-body">
                        <p>{{ $attribute->description }}</p>
                    </div>
                </div>
                @endif

                {{-- Help Text Card --}}
                @if($attribute->help_text)
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ti ti-message"></i> Help Text</h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info mb-0">
                            <i class="ti ti-info-circle me-2"></i> {{ $attribute->help_text }}
                        </div>
                    </div>
                </div>
                @endif

                {{-- Associated Categories Card --}}
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ti ti-folder"></i> Associated Categories ({{ $attribute->categories->count() }})</h5>
                    </div>
                    <div class="card-body">
                        @if($attribute->categories->count() > 0)
                            <div class="row">
                                @foreach($attribute->categories as $category)
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
                                <p class="text-muted mt-2">No categories associated with this attribute.</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Validation Rules Card --}}
                @if($attribute->min_value || $attribute->max_value || $attribute->max_length || $attribute->regex_pattern || $attribute->is_required)
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ti ti-checklist"></i> Validation Rules</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @if($attribute->is_required)
                            <div class="col-md-6 mb-2">
                                <span class="badge bg-danger"><i class="ti ti-asterisk"></i> Required Field</span>
                            </div>
                            @endif
                            @if($attribute->min_value)
                            <div class="col-md-6 mb-2">
                                <span class="badge bg-info"><i class="ti ti-arrow-up"></i> Min Value: {{ $attribute->min_value }}</span>
                            </div>
                            @endif
                            @if($attribute->max_value)
                            <div class="col-md-6 mb-2">
                                <span class="badge bg-info"><i class="ti ti-arrow-down"></i> Max Value: {{ $attribute->max_value }}</span>
                            </div>
                            @endif
                            @if($attribute->max_length)
                            <div class="col-md-6 mb-2">
                                <span class="badge bg-info"><i class="ti ti-text-size"></i> Max Length: {{ $attribute->max_length }} chars</span>
                            </div>
                            @endif
                            @if($attribute->regex_pattern)
                            <div class="col-md-12 mb-2">
                                <span class="badge bg-secondary"><i class="ti ti-code"></i> Regex: <code>{{ $attribute->regex_pattern }}</code></span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                {{-- Predefined Values Card (for select/multiselect/radio) --}}
                @if(in_array($attribute->type, ['select', 'multiselect', 'radio']) && $attribute->values->count() > 0)
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ti ti-list"></i> Predefined Values ({{ $attribute->values->count() }})</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Value</th>
                                        <th>Label</th>
                                        <th>Color</th>
                                        <th>Price Adj.</th>
                                        <th>Weight Adj.</th>
                                        <th>Default</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($attribute->values as $value)
                                    <tr>
                                        <td><code>{{ $value->value }}</code></td>
                                        <td>{{ $value->label ?? $value->value }}</td>
                                        <td>
                                            @if($value->color_code)
                                                <div style="width: 30px; height: 30px; background-color: {{ $value->color_code }}; border-radius: 50%; border: 1px solid #ddd;"></div>
                                            @else
                                                —
                                            @endif
                                        </td>
                                        <td>{{ $value->price_adjustment ? '$'.$value->price_adjustment : '—' }}</td>
                                        <td>{{ $value->weight_adjustment ? $value->weight_adjustment.' kg' : '—' }}</td>
                                        <td class="text-center">@if($value->is_default)<i class="ti ti-check-circle text-success"></i>@else—@endif</td>
                                        <td>@if($value->status)<span class="badge bg-success">Active</span>@else<span class="badge bg-danger">Inactive</span>@endif</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Default Value Preview --}}
                @if($attribute->default_value && !in_array($attribute->type, ['select', 'multiselect', 'radio']))
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ti ti-star"></i> Default Value</h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-secondary mb-0">
                            <strong>Default:</strong> {{ $attribute->default_value }}
                            @if($attribute->placeholder)
                                <br><strong>Placeholder:</strong> {{ $attribute->placeholder }}
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                {{-- SEO Information Card --}}
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ti ti-meta-tag"></i> SEO Information</h5>
                    </div>
                    <div class="card-body">
                        @php 
                            $seoScore = 0;
                            if ($attribute->meta_title && strlen($attribute->meta_title) >= 30 && strlen($attribute->meta_title) <= 60) $seoScore += 34;
                            if ($attribute->meta_description && strlen($attribute->meta_description) >= 120 && strlen($attribute->meta_description) <= 160) $seoScore += 33;
                            if ($attribute->focus_keyword) $seoScore += 33;
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
                                    <div class="col-6"><small>Meta Title</small><div class="{{ $attribute->meta_title ? 'text-success' : 'text-danger' }}">{{ $attribute->meta_title ? '✓ Present' : '✗ Missing' }}</div></div>
                                    <div class="col-6"><small>Meta Description</small><div class="{{ $attribute->meta_description ? 'text-success' : 'text-danger' }}">{{ $attribute->meta_description ? '✓ Present' : '✗ Missing' }}</div></div>
                                    <div class="col-6 mt-2"><small>Focus Keyword</small><div class="{{ $attribute->focus_keyword ? 'text-success' : 'text-danger' }}">{{ $attribute->focus_keyword ? '✓ Present' : '✗ Missing' }}</div></div>
                                </div>
                            </div>
                        </div>
                        @if($attribute->meta_title || $attribute->meta_description)
                            <hr>
                            @if($attribute->meta_title)<div><strong>Meta Title:</strong> {{ $attribute->meta_title }}</div>@endif
                            @if($attribute->meta_description)<div class="mt-1"><strong>Meta Description:</strong> {{ $attribute->meta_description }}</div>@endif
                            @if($attribute->focus_keyword)<div class="mt-1"><strong>Focus Keyword:</strong> {{ $attribute->focus_keyword }}</div>@endif
                        @endif
                    </div>
                </div>

                {{-- CSS Classes Card --}}
                @if($attribute->input_class || $attribute->wrapper_class)
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ti ti-code"></i> CSS Classes</h5>
                    </div>
                    <div class="card-body">
                        @if($attribute->input_class)
                            <div><strong>Input Class:</strong> <code>{{ $attribute->input_class }}</code></div>
                        @endif
                        @if($attribute->wrapper_class)
                            <div class="mt-2"><strong>Wrapper Class:</strong> <code>{{ $attribute->wrapper_class }}</code></div>
                        @endif
                    </div>
                </div>
                @endif

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
                        <a href="{{ route('admin.attributes.index') }}" class="btn btn-secondary"><i class="ti ti-arrow-left me-1"></i> Back</a>
                        @can('edit_attributes')<a href="{{ route('admin.attributes.edit', $attribute->id) }}" class="btn btn-primary"><i class="ti ti-edit me-1"></i> Edit Attribute</a>@endcan
                        @if($attribute->approval_status === 'pending' && auth()->guard('admin')->user()->can('edit_attributes'))
                            <button type="button" class="btn btn-info" onclick="approveAttribute({{ $attribute->id }})"><i class="ti ti-check"></i> Approve</button>
                            <button type="button" class="btn btn-warning" onclick="showRejectModal({{ $attribute->id }})"><i class="ti ti-x"></i> Reject</button>
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

function approveAttribute(attributeId) {
    Swal.fire({
        title: 'Approve Attribute?',
        text: 'Are you sure you want to approve this attribute?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        confirmButtonText: 'Yes, approve it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '{{ url("admin/attributes/requests") }}/' + attributeId + '/approve',
                type: 'POST',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({ icon: 'success', title: 'Approved!', text: response.message, timer: 1500, showConfirmButton: false })
                            .then(() => location.reload());
                    }
                },
                error: function() {
                    Swal.fire({ icon: 'error', title: 'Error!', text: 'Failed to approve attribute.', confirmButtonColor: '#d33' });
                }
            });
        }
    });
}

function showRejectModal(attributeId) {
    Swal.fire({
        title: 'Reject Attribute',
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
                url: '{{ url("admin/attributes/requests") }}/' + attributeId + '/reject',
                type: 'POST',
                data: { _token: '{{ csrf_token() }}', rejection_reason: result.value.reason },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({ icon: 'success', title: 'Rejected!', text: response.message, timer: 1500, showConfirmButton: false })
                            .then(() => location.reload());
                    }
                },
                error: function() {
                    Swal.fire({ icon: 'error', title: 'Error!', text: 'Failed to reject attribute.', confirmButtonColor: '#d33' });
                }
            });
        }
    });
}
</script>
@endpush