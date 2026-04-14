{{-- resources/views/marketplace/pages/attributes/my-value-requests.blade.php --}}
@extends('management.layouts.app')

@section('title', 'My Attribute Value Requests')

@section('content')
<div class="page-content">
    <div class="page-container">
        <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
            <div class="flex-grow-1">
                <h4 class="fs-18 text-uppercase fw-bold mb-0">My Attribute Value Requests</h4>
            </div>
            <div class="text-end">
                <ol class="breadcrumb m-0 py-0">
                    <li class="breadcrumb-item"><a href="{{ route('vendor.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('vendor.attributes.index') }}">Attributes</a></li>
                    <li class="breadcrumb-item active">Value Requests</li>
                </ol>
            </div>
        </div>

        {{-- Statistics Cards --}}
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div><h6 class="mb-0">Total Requests</h6><h2 class="mb-0">{{ $statistics['total'] ?? 0 }}</h2></div>
                            <i class="ti ti-file" style="font-size: 40px; opacity: 0.5;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-dark">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div><h6 class="mb-0">Pending</h6><h2 class="mb-0">{{ $statistics['pending'] ?? 0 }}</h2></div>
                            <i class="ti ti-clock" style="font-size: 40px; opacity: 0.5;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div><h6 class="mb-0">Approved</h6><h2 class="mb-0">{{ $statistics['approved'] ?? 0 }}</h2></div>
                            <i class="ti ti-check-circle" style="font-size: 40px; opacity: 0.5;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-danger text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div><h6 class="mb-0">Rejected</h6><h2 class="mb-0">{{ $statistics['rejected'] ?? 0 }}</h2></div>
                            <i class="ti ti-x-circle" style="font-size: 40px; opacity: 0.5;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filter Section --}}
        <div class="card mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Filter by Status</label>
                        <select class="form-select" id="statusFilter">
                            <option value="">All Requests</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Filter by Attribute</label>
                        <select class="form-select" id="attributeFilter">
                            <option value="">All Attributes</option>
                            @foreach($attributes as $attribute)
                                <option value="{{ $attribute->id }}" {{ request('attribute_id') == $attribute->id ? 'selected' : '' }}>
                                    {{ $attribute->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">&nbsp;</label>
                        <a href="{{ route('vendor.attributes.value-request.create') }}" class="btn btn-primary w-100">
                            <i class="ti ti-plus"></i> Request New Value
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Requests Table --}}
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Value</th>
                                <th>Attribute</th>
                                <th>Label</th>
                                <th>Color</th>
                                <th>Request Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($requests as $request)
                                @php
                                    $statusClass = [
                                        'pending' => 'bg-warning',
                                        'approved' => 'bg-success',
                                        'rejected' => 'bg-danger',
                                    ][$request->status] ?? 'bg-secondary';
                                @endphp
                                <tr>
                                    <td>#{{ $request->id }}</div></div></td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            @if($request->requested_color_code)
                                                <div style="width: 25px; height: 25px; background-color: {{ $request->requested_color_code }}; border-radius: 50%; border: 1px solid #ddd;"></div>
                                            @else
                                                <i class="ti ti-tag text-primary"></i>
                                            @endif
                                            <strong>{{ $request->requested_value }}</strong>
                                        </div>
                                    </div></div></td>
                                    <td>
                                        <span class="badge bg-primary">{{ $request->attribute->name ?? 'Unknown' }}</span>
                                    </div></div></td>
                                    <td>{{ $request->requested_label ?? '—' }}</div></div></td>
                                    <td>
                                        @if($request->requested_color_code)
                                            <div style="width: 30px; height: 30px; background-color: {{ $request->requested_color_code }}; border-radius: 50%; border: 1px solid #ddd;"></div>
                                        @else
                                            —
                                        @endif
                                    </div></div></td>
                                    <td>
                                        {{ $request->created_at->format('M d, Y') }}<br>
                                        <small class="text-muted">{{ $request->created_at->diffForHumans() }}</small>
                                    </div></div></td>
                                    <td><span class="badge {{ $statusClass }}">{{ ucfirst($request->status) }}</span></div></div></td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('vendor.attributes.value-requests.show', $request->id) }}" class="btn btn-info" title="View Details">
                                                <i class="ti ti-eye"></i>
                                            </a>
                                            @if($request->status === 'pending')
                                                <button type="button" class="btn btn-danger" onclick="cancelValueRequest({{ $request->id }}, '{{ $request->requested_value }}')" title="Cancel Request">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </div></div></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="text-center">
                                            <i class="ti ti-inbox" style="font-size: 48px; opacity: 0.5;"></i>
                                            <h5 class="mt-3">No Value Requests Found</h5>
                                            <p class="text-muted">You haven't submitted any attribute value requests yet.</p>
                                            <a href="{{ route('vendor.attributes.value-request.create') }}" class="btn btn-primary mt-2">
                                                <i class="ti ti-plus"></i> Request New Value
                                            </a>
                                        </div>
                                    </div></div></td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-3">
                    {{ $requests->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>

<form id="cancelForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Filter handlers
    $('#statusFilter').on('change', function() {
        let status = $(this).val();
        let attributeId = $('#attributeFilter').val();
        let url = '{{ route("vendor.attributes.value-requests.index") }}?';
        let params = [];
        
        if (status) params.push('status=' + status);
        if (attributeId) params.push('attribute_id=' + attributeId);
        
        window.location.href = url + params.join('&');
    });

    $('#attributeFilter').on('change', function() {
        let status = $('#statusFilter').val();
        let attributeId = $(this).val();
        let url = '{{ route("vendor.attributes.value-requests.index") }}?';
        let params = [];
        
        if (status) params.push('status=' + status);
        if (attributeId) params.push('attribute_id=' + attributeId);
        
        window.location.href = url + params.join('&');
    });

    // Cancel request function
    function cancelValueRequest(requestId, requestValue) {
        Swal.fire({
            title: 'Cancel Request?',
            text: `Are you sure you want to cancel the request for "${requestValue}"?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, cancel it!'
        }).then((result) => {
            if (result.isConfirmed) {
                let form = $('#cancelForm');
                form.attr('action', '{{ url("vendor/attributes/value-requests") }}/' + requestId);
                
                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: form.serialize(),
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Cancelled!',
                            text: 'Request cancelled successfully.',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: xhr.responseJSON?.message || 'Failed to cancel request.',
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
    .table th, .table td {
        vertical-align: middle;
    }
    .btn-group-sm .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
</style>
@endpush