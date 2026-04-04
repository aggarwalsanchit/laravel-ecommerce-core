@extends('management.layouts.app')

@section('title', 'Activity Logs')

@section('content')
    <div class="page-content">
        <div class="page-container">
            <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                <div class="flex-grow-1">
                    <h4 class="fs-18 text-uppercase fw-bold mb-0">Activity Logs</h4>
                    <p class="text-muted mb-0">Track all your account activities</p>
                </div>
                <div class="text-end">
                    <a href="{{ route('vendor.activity-logs.export') }}" class="btn btn-success">
                        <i class="ti ti-download"></i> Export CSV
                    </a>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    {{-- Filters --}}
                    <form method="GET" class="mb-4">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Action</label>
                                <select name="action" class="form-select">
                                    <option value="">All Actions</option>
                                    @foreach ($actions as $action)
                                        <option value="{{ $action }}"
                                            {{ request('action') == $action ? 'selected' : '' }}>
                                            {{ ucfirst($action) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Entity Type</label>
                                <select name="entity_type" class="form-select">
                                    <option value="">All Entities</option>
                                    @foreach ($entityTypes as $type)
                                        <option value="{{ $type }}"
                                            {{ request('entity_type') == $type ? 'selected' : '' }}>
                                            {{ ucfirst(str_replace('_', ' ', $type)) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">From Date</label>
                                <input type="date" name="from_date" class="form-control"
                                    value="{{ request('from_date') }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">To Date</label>
                                <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">Filter</button>
                            </div>
                        </div>
                    </form>

                    {{-- Logs Table --}}
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date & Time</th>
                                    <th>Action</th>
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
                                        <td>
                                            <span class="badge bg-{{ $log->action_badge_class }}">
                                                {{ $log->human_action }}
                                            </span>
                                        </td>
                                        <td>
                                            @if ($log->entity_type)
                                                <span class="badge bg-secondary">
                                                    {{ ucfirst(str_replace('_', ' ', $log->entity_type)) }}
                                                </span>
                                                @if ($log->entity_name)
                                                    <div class="small">{{ $log->entity_name }}</div>
                                                @endif
                                            @else
                                                —
                                            @endif
                                        </td>
                                        <td>{{ $log->description ?? '—' }}</td>
                                        <td>{{ $log->ip_address ?? '—' }}</td>
                                        <td>
                                            @if ($log->device == 'mobile')
                                                <i class="ti ti-device-mobile"></i> Mobile
                                            @elseif($log->device == 'tablet')
                                                <i class="ti ti-device-tablet"></i> Tablet
                                            @else
                                                <i class="ti ti-device-desktop"></i> Desktop
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('vendor.activity-logs.show', $log->id) }}"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="ti ti-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <i class="ti ti-activity fs-1 text-muted"></i>
                                            <p class="mt-2">No activity logs found</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $logs->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
