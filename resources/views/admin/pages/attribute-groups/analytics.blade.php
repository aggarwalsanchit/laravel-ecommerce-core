{{-- resources/views/admin/pages/attribute-groups/analytics.blade.php --}}
@extends('management.layouts.app')

@section('title', 'Attribute Groups Analytics')

@section('content')
<div class="page-content">
    <div class="page-container">
        <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
            <div class="flex-grow-1">
                <h4 class="fs-18 text-uppercase fw-bold mb-0">Attribute Groups Analytics Dashboard</h4>
            </div>
            <div class="text-end">
                <ol class="breadcrumb m-0 py-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.attribute-groups.index') }}">Attribute Groups</a></li>
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
                                <h6 class="mb-0">Total Groups</h6>
                                <h2 class="mb-0">{{ $totalGroups ?? 0 }}</h2>
                            </div>
                            <i class="ti ti-layout-sidebar" style="font-size: 40px; opacity: 0.5;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">Active Groups</h6>
                                <h2 class="mb-0">{{ $activeGroups ?? 0 }}</h2>
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
                                <h6 class="mb-0">Groups with Attributes</h6>
                                <h2 class="mb-0">{{ $groupsWithAttributes ?? 0 }}</h2>
                            </div>
                            <i class="ti ti-list" style="font-size: 40px; opacity: 0.5;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-dark">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">Avg Attributes/Group</h6>
                                <h2 class="mb-0">{{ number_format($avgAttributesPerGroup ?? 0, 1) }}</h2>
                            </div>
                            <i class="ti ti-chart-bar" style="font-size: 40px; opacity: 0.5;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Second Row - Additional Stats --}}
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card bg-secondary text-white">
                    <div class="card-body">
                        <div class="text-center">
                            <h6 class="mb-0">Inactive Groups</h6>
                            <h2 class="mb-0">{{ ($totalGroups ?? 0) - ($activeGroups ?? 0) }}</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-purple text-white">
                    <div class="card-body">
                        <div class="text-center">
                            <h6 class="mb-0">Total Attributes</h6>
                            <h2 class="mb-0">{{ $totalAttributes ?? 0 }}</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-pink text-white">
                    <div class="card-body">
                        <div class="text-center">
                            <h6 class="mb-0">Filterable Attributes</h6>
                            <h2 class="mb-0">{{ $filterableAttributes ?? 0 }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- Groups by Position Chart --}}
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ti ti-chart-pie"></i> Groups by Position</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="positionChart" height="250"></canvas>
                    </div>
                </div>
            </div>

            {{-- Groups by Status Chart --}}
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ti ti-chart-pie"></i> Groups by Status</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="statusChart" height="250"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            {{-- Groups with Most Attributes --}}
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ti ti-list"></i> Groups with Most Attributes</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Group Name</th>
                                        <th>Attributes Count</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($groupsWithMostAttributes ?? [] as $index => $group)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    @if($group->icon)
                                                        <i class="{{ $group->icon }} text-primary"></i>
                                                    @else
                                                        <i class="ti ti-layout-sidebar text-primary"></i>
                                                    @endif
                                                    <span>{{ $group->name }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ $group->attributes_count }}</span>
                                            </td>
                                            <td>
                                                @if($group->status)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-danger">Inactive</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-4">
                                                <i class="ti ti-chart-bar" style="font-size: 48px; opacity: 0.5;"></i>
                                                <p class="mt-2">No data available</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Group Distribution --}}
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ti ti-chart-pie"></i> Group Distribution</h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6 mb-3">
                                <div class="bg-primary-subtle rounded p-3">
                                    <h3 class="mb-0 text-primary">{{ $groupsWithAttributes ?? 0 }}</h3>
                                    <p class="text-muted mb-0">With Attributes</p>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="bg-secondary-subtle rounded p-3">
                                    <h3 class="mb-0 text-secondary">{{ ($totalGroups ?? 0) - ($groupsWithAttributes ?? 0) }}</h3>
                                    <p class="text-muted mb-0">Empty Groups</p>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="bg-success-subtle rounded p-3">
                                    <h3 class="mb-0 text-success">{{ $activeGroups ?? 0 }}</h3>
                                    <p class="text-muted mb-0">Active</p>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="bg-warning-subtle rounded p-3">
                                    <h3 class="mb-0 text-warning">{{ $pendingCount ?? 0 }}</h3>
                                    <p class="text-muted mb-0">Pending Approval</p>
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
                    <div class="card-footer d-flex gap-3 flex-wrap">
                        <a href="{{ route('admin.attribute-groups.create') }}" class="btn btn-primary">
                            <i class="ti ti-plus me-1"></i> Add New Group
                        </a>
                        <a href="{{ route('admin.attribute-groups.index') }}" class="btn btn-secondary">
                            <i class="ti ti-list me-1"></i> Manage Groups
                        </a>
                        <a href="{{ route('admin.attributes.create') }}" class="btn btn-info">
                            <i class="ti ti-plus me-1"></i> Add New Attribute
                        </a>
                        @if(($pendingCount ?? 0) > 0)
                            <button type="button" class="btn btn-warning" onclick="approveAllPending()">
                                <i class="ti ti-check-all me-1"></i> Approve All Pending ({{ $pendingCount ?? 0 }})
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Groups by Position Chart
@if(isset($groupsByPosition) && $groupsByPosition->count() > 0)
const positionCtx = document.getElementById('positionChart').getContext('2d');
const positionData = @json($groupsByPosition);
new Chart(positionCtx, {
    type: 'doughnut',
    data: {
        labels: positionData.map(item => item.position.charAt(0).toUpperCase() + item.position.slice(1)),
        datasets: [{
            data: positionData.map(item => item.count),
            backgroundColor: ['#0d6efd', '#20c997', '#6c757d'],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: { position: 'bottom' },
            tooltip: { callbacks: { label: function(context) { return `${context.label}: ${context.raw} groups`; } } }
        }
    }
});
@endif

// Groups by Status Chart
@if(isset($groupsByStatus) && count($groupsByStatus) > 0)
const statusCtx = document.getElementById('statusChart').getContext('2d');
const statusData = @json($groupsByStatus);
new Chart(statusCtx, {
    type: 'pie',
    data: {
        labels: statusData.map(item => item.status === 'active' ? 'Active' : 'Inactive'),
        datasets: [{
            data: statusData.map(item => item.count),
            backgroundColor: ['#198754', '#dc3545'],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: { position: 'bottom' },
            tooltip: { callbacks: { label: function(context) { return `${context.label}: ${context.raw} groups`; } } }
        }
    }
});
@endif

function approveAllPending() {
    let pendingIds = @json($pendingIds ?? []);
    if (pendingIds.length === 0) {
        Swal.fire({
            icon: 'info',
            title: 'No Pending Groups',
            text: 'There are no pending attribute groups to approve.',
            confirmButtonColor: '#6c757d'
        });
        return;
    }
    
    Swal.fire({
        title: 'Approve All Pending?',
        text: `Are you sure you want to approve ${pendingIds.length} pending group(s)?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, approve all!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '{{ route("admin.attribute-groups.bulk-action") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    action: 'approve',
                    group_ids: JSON.stringify(pendingIds)
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
                        text: xhr.responseJSON?.message || 'Failed to approve groups.',
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
    .bg-purple { background-color: #6f42c1 !important; }
    .bg-pink { background-color: #d63384 !important; }
    .bg-primary-subtle { background-color: rgba(13, 110, 253, 0.1); }
    .bg-success-subtle { background-color: rgba(25, 135, 84, 0.1); }
    .bg-warning-subtle { background-color: rgba(255, 193, 7, 0.1); }
    .bg-secondary-subtle { background-color: rgba(108, 117, 125, 0.1); }
</style>
@endpush