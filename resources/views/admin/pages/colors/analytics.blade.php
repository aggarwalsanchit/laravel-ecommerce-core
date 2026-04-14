{{-- resources/views/admin/pages/colors/analytics.blade.php --}}
@extends('management.layouts.app')

@section('title', 'Color Analytics')

@section('content')
    <div class="page-content">
        <div class="page-container">
            <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                <div class="flex-grow-1">
                    <h4 class="fs-18 text-uppercase fw-bold mb-0">Color Analytics Dashboard</h4>
                </div>
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.colors.index') }}">Colors</a></li>
                        <li class="breadcrumb-item active">Analytics</li>
                    </ol>
                </div>
            </div>

            {{-- Statistics Cards --}}
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">Total Colors</h6>
                                    <h2 class="mb-0">{{ $totalColors ?? 0 }}</h2>
                                </div>
                                <i class="ti ti-palette" style="font-size: 40px; opacity: 0.5;"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">Active Colors</h6>
                                    <h2 class="mb-0">{{ $activeColors ?? 0 }}</h2>
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

            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card bg-secondary text-white">
                        <div class="card-body">
                            <div class="text-center">
                                <h6 class="mb-0">Featured Colors</h6>
                                <h2 class="mb-0">{{ $featuredColors ?? 0 }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-danger text-white">
                        <div class="card-body">
                            <div class="text-center">
                                <h6 class="mb-0">Popular Colors</h6>
                                <h2 class="mb-0">{{ $popularColors ?? 0 }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-warning text-dark">
                        <div class="card-body">
                            <div class="text-center">
                                <h6 class="mb-0">Pending Approval</h6>
                                <h2 class="mb-0">{{ $pendingCount ?? 0 }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                {{-- Top Colors by Views --}}
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ti ti-eye me-1"></i> Top Colors by Views
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Color</th>
                                            <th>Hex</th>
                                            <th>Views</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($topViewsColors ?? [] as $index => $color)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div
                                                            style="width: 25px; height: 25px; background-color: {{ $color->code }}; border-radius: 50%; border: 1px solid #ddd;">
                                                        </div>
                                                        <span>{{ $color->name }}</span>
                                                    </div>
                                                </td>
                                                <td><code>{{ $color->code }}</code></td>
                                                <td><span
                                                        class="fw-bold">{{ number_format($color->total_views ?? 0) }}</span>
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

                {{-- Top Colors by Revenue --}}
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ti ti-chart-line me-1"></i> Top Colors by Revenue
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Color</th>
                                            <th>Hex</th>
                                            <th>Revenue</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($topRevenueColors ?? [] as $index => $color)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div
                                                            style="width: 25px; height: 25px; background-color: {{ $color->code }}; border-radius: 50%; border: 1px solid #ddd;">
                                                        </div>
                                                        <span>{{ $color->name }}</span>
                                                    </div>
                                                </td>
                                                <td><code>{{ $color->code }}</code></td>
                                                <td class="text-success fw-bold">
                                                    ${{ number_format($color->total_revenue ?? 0, 2) }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center py-4">
                                                    <i class="ti ti-chart-line" style="font-size: 48px; opacity: 0.5;"></i>
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
                {{-- Most Used Colors --}}
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ti ti-package me-1"></i> Most Used Colors
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Color</th>
                                            <th>Hex</th>
                                            <th>Usage Count</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($mostUsedColors ?? [] as $index => $color)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div
                                                            style="width: 25px; height: 25px; background-color: {{ $color->code }}; border-radius: 50%; border: 1px solid #ddd;">
                                                        </div>
                                                        <span>{{ $color->name }}</span>
                                                    </div>
                                                </td>
                                                <td><code>{{ $color->code }}</code></td>
                                                <td><span class="badge bg-info">{{ number_format($color->usage_count) }}
                                                        products</span></td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center py-4">
                                                    <i class="ti ti-package-off"
                                                        style="font-size: 48px; opacity: 0.5;"></i>
                                                    <p class="mt-2">No usage data available</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Pending Approval Colors --}}
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ti ti-clock me-1"></i> Pending Approval Colors
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Color Name</th>
                                            <th>Hex Code</th>
                                            <th>Requested By</th>
                                            <th>Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($pendingColors ?? [] as $color)
                                            <tr>
                                                <td><strong>{{ $color->name }}</strong></td>
                                                <td>
                                                    <code>{{ $color->code }}</code>
                                                    <div
                                                        style="width: 20px; height: 20px; background-color: {{ $color->code }}; border-radius: 50%; display: inline-block; margin-left: 5px; border: 1px solid #ddd;">
                                                    </div>
                                                </td>
                                                <td>
                                                    @if ($color->requested_by)
                                                        Vendor #{{ $color->requested_by }}
                                                    @else
                                                        <span class="text-muted">System</span>
                                                    @endif
                                                </td>
                                                <td>{{ $color->created_at->format('M d, Y') }}</td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <button type="button" class="btn btn-success"
                                                            onclick="approveColor({{ $color->id }})" title="Approve">
                                                            <i class="ti ti-check"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-danger"
                                                            onclick="showRejectModal({{ $color->id }}, '{{ $color->name }}')"
                                                            title="Reject">
                                                            <i class="ti ti-x"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-4">
                                                    <i class="ti ti-check-circle"
                                                        style="font-size: 48px; opacity: 0.5;"></i>
                                                    <p class="mt-2">No pending colors</p>
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
                {{-- Color Summary --}}
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ti ti-chart-pie me-1"></i> Color Summary
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 text-center">
                                    <div class="bg-primary-subtle rounded p-3">
                                        <h3 class="text-primary mb-0">{{ $approvedCount ?? 0 }}</h3>
                                        <p class="text-muted mb-0">Approved</p>
                                    </div>
                                </div>
                                <div class="col-md-3 text-center">
                                    <div class="bg-warning-subtle rounded p-3">
                                        <h3 class="text-warning mb-0">{{ $pendingCount ?? 0 }}</h3>
                                        <p class="text-muted mb-0">Pending</p>
                                    </div>
                                </div>
                                <div class="col-md-3 text-center">
                                    <div class="bg-danger-subtle rounded p-3">
                                        <h3 class="text-danger mb-0">{{ $rejectedCount ?? 0 }}</h3>
                                        <p class="text-muted mb-0">Rejected</p>
                                    </div>
                                </div>
                                <div class="col-md-3 text-center">
                                    <div class="bg-info-subtle rounded p-3">
                                        <h3 class="text-info mb-0">{{ $featuredColors ?? 0 }}</h3>
                                        <p class="text-muted mb-0">Featured</p>
                                    </div>
                                </div>
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
                            <a href="{{ route('admin.colors.create') }}" class="btn btn-primary">
                                <i class="ti ti-plus me-1"></i> Add New Color
                            </a>
                            <a href="{{ route('admin.colors.index') }}" class="btn btn-secondary">
                                <i class="ti ti-list me-1"></i> Manage Colors
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
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function approveColor(colorId) {
            Swal.fire({
                title: 'Approve Color?',
                text: 'Are you sure you want to approve this color?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
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
                                text: xhr.responseJSON?.message || 'Failed to approve color.',
                                confirmButtonColor: '#d33'
                            });
                        }
                    });
                }
            });
        }

        function showRejectModal(colorId, colorName) {
            Swal.fire({
                title: 'Reject Color',
                html: `
                    <p>Are you sure you want to reject <strong>"${colorName}"</strong>?</p>
                    <textarea id="rejectionReason" class="swal2-textarea" placeholder="Please provide a rejection reason..." rows="3"></textarea>
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
                        url: '{{ url('admin/colors') }}/' + colorId + '/reject',
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
                                text: xhr.responseJSON?.message || 'Failed to reject color.',
                                confirmButtonColor: '#d33'
                            });
                        }
                    });
                }
            });
        }

        function approveAllPending() {
            let pendingIds = @json($pendingColors->pluck('id')->toArray() ?? []);

            if (pendingIds.length === 0) {
                Swal.fire({
                    icon: 'info',
                    title: 'No Pending Colors',
                    text: 'There are no pending colors to approve.',
                    confirmButtonColor: '#6c757d'
                });
                return;
            }

            Swal.fire({
                title: 'Approve All Pending?',
                text: `Are you sure you want to approve ${pendingIds.length} pending color(s)?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, approve all!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route('admin.colors.bulk-action') }}',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            action: 'approve',
                            color_ids: JSON.stringify(pendingIds)
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
                                text: xhr.responseJSON?.message || 'Failed to approve colors.',
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
        .bg-primary-subtle {
            background-color: rgba(13, 110, 253, 0.1);
        }

        .bg-success-subtle {
            background-color: rgba(25, 135, 84, 0.1);
        }

        .bg-warning-subtle {
            background-color: rgba(255, 193, 7, 0.1);
        }

        .bg-danger-subtle {
            background-color: rgba(220, 53, 69, 0.1);
        }

        .bg-info-subtle {
            background-color: rgba(13, 202, 240, 0.1);
        }

        .btn-group-sm .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }
    </style>
@endpush
