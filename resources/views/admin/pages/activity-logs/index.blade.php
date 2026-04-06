@extends('management.layouts.app')

@section('title', 'Admin Activity Logs')

@section('content')
<div class="page-content">
    <div class="page-container">
        <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
            <div class="flex-grow-1">
                <h4 class="fs-18 text-uppercase fw-bold mb-0">Admin Activity Logs</h4>
                <p class="text-muted mb-0">Track all admin activities</p>
            </div>
            <div class="text-end">
                <ol class="breadcrumb m-0 py-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Activity Logs</li>
                </ol>
            </div>
        </div>

        {{-- Stats Cards --}}
        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="text-muted mb-1">Total Activities</p>
                                <h3 class="mb-0">{{ $stats['total'] }}</h3>
                            </div>
                            <div class="avatar-sm bg-primary-subtle rounded">
                                <i class="ti ti-activity fs-24 text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="text-muted mb-1">Today</p>
                                <h3 class="mb-0">{{ $stats['today'] }}</h3>
                            </div>
                            <div class="avatar-sm bg-success-subtle rounded">
                                <i class="ti ti-calendar fs-24 text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="text-muted mb-1">This Week</p>
                                <h3 class="mb-0">{{ $stats['this_week'] }}</h3>
                            </div>
                            <div class="avatar-sm bg-info-subtle rounded">
                                <i class="ti ti-calendar-week fs-24 text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="text-muted mb-1">This Month</p>
                                <h3 class="mb-0">{{ $stats['this_month'] }}</h3>
                            </div>
                            <div class="avatar-sm bg-warning-subtle rounded">
                                <i class="ti ti-calendar-month fs-24 text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Activity Logs</h5>
                    <a href="{{ route('admin.activity-logs.export', request()->query()) }}" class="btn btn-sm btn-success">
                        <i class="ti ti-download"></i> Export CSV
                    </a>
                </div>
            </div>
            <div class="card-body">
                {{-- Filters --}}
                <form method="GET" class="mb-4">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Admin</label>
                            <select name="admin_id" class="form-select">
                                <option value="">All Admins</option>
                                @foreach($admins as $admin)
                                    <option value="{{ $admin->id }}" {{ request('admin_id') == $admin->id ? 'selected' : '' }}>
                                        {{ $admin->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Action</label>
                            <select name="action" class="form-select">
                                <option value="">All Actions</option>
                                @foreach($actions as $action)
                                    <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                                        {{ ucfirst($action) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Module</label>
                            <select name="module" class="form-select">
                                <option value="">All Modules</option>
                                @foreach($modules as $module)
                                    <option value="{{ $module }}" {{ request('module') == $module ? 'selected' : '' }}>
                                        {{ ucfirst($module) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">From Date</label>
                            <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">To Date</label>
                            <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">Filter</button>
                        </div>
                    </div>
                </form>

                {{-- Logs Table --}}
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Date & Time</th>
                                <th>Admin</th>
                                <th>Action</th>
                                <th>Module</th>
                                <th>Entity</th>
                                <th>Description</th>
                                <th>IP Address</th>
                                <th>Device</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $log)
                            <tr>
                                <td>{{ $log->created_at->format('d M Y, h:i A') }}</td>
                                <td>{{ $log->admin->name ?? 'Unknown' }}</td>
                                <td>
                                    <span class="badge bg-{{ $log->action_badge_class }}">
                                        {{ $log->human_action }}
                                    </span>
                                </td>
                                <td>{{ ucfirst($log->module ?? '—') }}</td>
                                <td>
                                    @if($log->entity_name)
                                        <strong>{{ $log->entity_name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $log->entity_type }}</small>
                                    @else
                                        —
                                    @endif
                                </td>
                                <td>{{ $log->description ?? '—' }}</td>
                                <td>{{ $log->ip_address ?? '—' }}</td>
                                <td>
                                    @if($log->device == 'mobile')
                                        <i class="ti ti-device-mobile"></i> Mobile
                                    @elseif($log->device == 'tablet')
                                        <i class="ti ti-device-tablet"></i> Tablet
                                    @else
                                        <i class="ti ti-device-desktop"></i> Desktop
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.activity-logs.show', $log->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <i class="ti ti-activity fs-1 text-muted"></i>
                                    <p class="mt-2">No activity logs found</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $logs->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection