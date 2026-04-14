{{-- resources/views/admin/pages/sizes/analytics.blade.php --}}
@extends('management.layouts.app')

@section('title', 'Size Analytics')

@section('content')
<div class="page-content">
    <div class="page-container">
        <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
            <div class="flex-grow-1">
                <h4 class="fs-18 text-uppercase fw-bold mb-0">Size Analytics Dashboard</h4>
            </div>
            <div class="text-end">
                <ol class="breadcrumb m-0 py-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.sizes.index') }}">Sizes</a></li>
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
                                <h6 class="mb-0">Total Sizes</h6>
                                <h2 class="mb-0">{{ $totalSizes ?? 0 }}</h2>
                            </div>
                            <i class="ti ti-ruler" style="font-size: 40px; opacity: 0.5;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="mb-0">Active Sizes</h6>
                                <h2 class="mb-0">{{ $activeSizes ?? 0 }}</h2>
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
                        <div class="d-flex justify-content-between">
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

        {{-- Gender Statistics Row --}}
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="text-center">
                            <h6 class="mb-0">Men's Sizes</h6>
                            <h2 class="mb-0">{{ $menSizes ?? 0 }}</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-danger text-white">
                    <div class="card-body">
                        <div class="text-center">
                            <h6 class="mb-0">Women's Sizes</h6>
                            <h2 class="mb-0">{{ $womenSizes ?? 0 }}</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="text-center">
                            <h6 class="mb-0">Unisex Sizes</h6>
                            <h2 class="mb-0">{{ $unisexSizes ?? 0 }}</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="text-center">
                            <h6 class="mb-0">Kids Sizes</h6>
                            <h2 class="mb-0">{{ $kidsSizes ?? 0 }}</h2>
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
                        <div class="text-center">
                            <h6 class="mb-0">Approved</h6>
                            <h2 class="mb-0">{{ $approvedCount ?? 0 }}</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-warning text-dark">
                    <div class="card-body">
                        <div class="text-center">
                            <h6 class="mb-0">Pending</h6>
                            <h2 class="mb-0">{{ $pendingCount ?? 0 }}</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-danger text-white">
                    <div class="card-body">
                        <div class="text-center">
                            <h6 class="mb-0">Rejected</h6>
                            <h2 class="mb-0">{{ $rejectedCount ?? 0 }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- Top Sizes by Views --}}
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ti ti-eye me-1"></i> Top Sizes by Views</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr><th>#</th><th>Size</th><th>Code</th><th>Gender</th><th>Views</th></tr>
                                </thead>
                                <tbody>
                                    @forelse($topViewsSizes ?? [] as $index => $size)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <i class="ti ti-ruler text-primary"></i>
                                                    <span>{{ $size->name }}</span>
                                                </div>
                                            </td>
                                            <td><code>{{ $size->code }}</code></td>
                                            <td><span class="badge bg-{{ $size->gender == 'Men' ? 'primary' : ($size->gender == 'Women' ? 'danger' : 'info') }}">{{ $size->gender }}</span></td>
                                            <td><span class="fw-bold">{{ number_format($size->total_views ?? 0) }}</span></td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="text-center py-4"><i class="ti ti-chart-bar" style="font-size: 48px; opacity: 0.5;"></i><p class="mt-2">No view data available</p></td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Top Sizes by Revenue --}}
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ti ti-chart-line me-1"></i> Top Sizes by Revenue</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr><th>#</th><th>Size</th><th>Code</th><th>Gender</th><th>Revenue</th></tr>
                                </thead>
                                <tbody>
                                    @forelse($topRevenueSizes ?? [] as $index => $size)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <i class="ti ti-ruler text-primary"></i>
                                                    <span>{{ $size->name }}</span>
                                                </div>
                                            </td>
                                            <td><code>{{ $size->code }}</code></td>
                                            <td><span class="badge bg-{{ $size->gender == 'Men' ? 'primary' : ($size->gender == 'Women' ? 'danger' : 'info') }}">{{ $size->gender }}</span></td>
                                            <td class="text-success fw-bold">${{ number_format($size->total_revenue ?? 0, 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="text-center py-4"><i class="ti ti-chart-line" style="font-size: 48px; opacity: 0.5;"></i><p class="mt-2">No revenue data available</p></td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            {{-- Most Used Sizes --}}
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ti ti-package me-1"></i> Most Used Sizes</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr><th>#</th><th>Size</th><th>Code</th><th>Gender</th><th>Usage Count</th></tr>
                                </thead>
                                <tbody>
                                    @forelse($mostUsedSizes ?? [] as $index => $size)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <i class="ti ti-ruler text-primary"></i>
                                                    <span>{{ $size->name }}</span>
                                                </div>
                                            </td>
                                            <td><code>{{ $size->code }}</code></td>
                                            <td><span class="badge bg-{{ $size->gender == 'Men' ? 'primary' : ($size->gender == 'Women' ? 'danger' : 'info') }}">{{ $size->gender }}</span></td>
                                            <td><span class="badge bg-info">{{ number_format($size->usage_count) }} products</span></td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="text-center py-4"><i class="ti ti-package-off" style="font-size: 48px; opacity: 0.5;"></i><p class="mt-2">No usage data available</p></td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Featured & Popular Stats --}}
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ti ti-star me-1"></i> Featured & Popular Sizes</h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="bg-warning-subtle rounded p-4">
                                    <i class="ti ti-star fs-1 text-warning"></i>
                                    <h2 class="mb-0 mt-2">{{ $featuredSizes ?? 0 }}</h2>
                                    <p class="text-muted mb-0">Featured Sizes</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="bg-danger-subtle rounded p-4">
                                    <i class="ti ti-fire fs-1 text-danger"></i>
                                    <h2 class="mb-0 mt-2">{{ $popularSizes ?? 0 }}</h2>
                                    <p class="text-muted mb-0">Popular Sizes</p>
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
                        <a href="{{ route('admin.sizes.create') }}" class="btn btn-primary"><i class="ti ti-plus me-1"></i> Add New Size</a>
                        <a href="{{ route('admin.sizes.index') }}" class="btn btn-secondary"><i class="ti ti-list me-1"></i> Manage Sizes</a>
                        @if(($pendingCount ?? 0) > 0)
                            <button type="button" class="btn btn-warning" onclick="approveAllPending()"><i class="ti ti-check-all me-1"></i> Approve All Pending</button>
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
function approveAllPending() {
    let pendingIds = @json($pendingIds ?? []);
    if (pendingIds.length === 0) {
        Swal.fire({ icon: 'info', title: 'No Pending Sizes', text: 'There are no pending sizes to approve.', confirmButtonColor: '#6c757d' });
        return;
    }
    Swal.fire({
        title: 'Approve All Pending?',
        text: `Are you sure you want to approve ${pendingIds.length} pending size(s)?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        confirmButtonText: 'Yes, approve all!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '{{ route("admin.sizes.bulk-action") }}',
                type: 'POST',
                data: { _token: '{{ csrf_token() }}', action: 'approve', size_ids: JSON.stringify(pendingIds) },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({ icon: 'success', title: 'Success!', text: response.message, timer: 1500, showConfirmButton: false }).then(() => location.reload());
                    }
                },
                error: function() {
                    Swal.fire({ icon: 'error', title: 'Error!', text: 'Failed to approve sizes.', confirmButtonColor: '#d33' });
                }
            });
        }
    });
}
</script>
@endpush

@push('styles')
<style>
    .bg-primary-subtle { background-color: rgba(13, 110, 253, 0.1); }
    .bg-success-subtle { background-color: rgba(25, 135, 84, 0.1); }
    .bg-warning-subtle { background-color: rgba(255, 193, 7, 0.1); }
    .bg-info-subtle { background-color: rgba(13, 202, 240, 0.1); }
    .bg-danger-subtle { background-color: rgba(220, 53, 69, 0.1); }
    .display-4 { font-size: 2.5rem; font-weight: 300; line-height: 1.2; }
</style>
@endpush