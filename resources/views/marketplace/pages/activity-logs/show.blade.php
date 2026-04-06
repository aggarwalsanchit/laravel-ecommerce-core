@extends('management.layouts.app')

@section('title', 'Activity Log Details')

@section('content')
    <div class="page-content">
        <div class="page-container">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Activity Log Details</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">Date & Time</th>
                                    <td>{{ $log->created_at->format('d M Y, h:i:s A') }}</td>
                                </tr>
                                <tr>
                                    <th>Action</th>
                                    <td><span
                                            class="badge bg-{{ $log->action_badge_class }}">{{ $log->human_action }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Entity Type</th>
                                    <td>{{ ucfirst(str_replace('_', ' ', $log->entity_type ?? '—')) }}</td>
                                </tr>
                                <tr>
                                    <th>Entity Name</th>
                                    <td>{{ $log->entity_name ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <th>Description</th>
                                    <td>{{ $log->description ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <th>IP Address</th>
                                    <td>{{ $log->ip_address ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <th>Device</th>
                                    <td>{{ ucfirst($log->device ?? '—') }}</td>
                                </tr>
                                <tr>
                                    <th>User Agent</th>
                                    <td><small>{{ $log->user_agent ?? '—' }}</small></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            @if ($log->old_values || $log->new_values)
                                <h6>Changes</h6>
                                <div class="row">
                                    @if ($log->old_values)
                                        <div class="col-md-6">
                                            <div class="card bg-light">
                                                <div class="card-header">Old Values</div>
                                                <div class="card-body">
                                                    <pre class="small">{{ json_encode($log->old_values, JSON_PRETTY_PRINT) }}</pre>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @if ($log->new_values)
                                        <div class="col-md-6">
                                            <div class="card bg-light">
                                                <div class="card-header">New Values</div>
                                                <div class="card-body">
                                                    <pre class="small">{{ json_encode($log->new_values, JSON_PRETTY_PRINT) }}</pre>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('vendor.activity-logs.index') }}" class="btn btn-secondary">Back to Logs</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
