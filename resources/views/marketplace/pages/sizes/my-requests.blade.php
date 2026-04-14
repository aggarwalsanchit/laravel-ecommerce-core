{{-- resources/views/marketplace/pages/sizes/my-requests.blade.php --}}
@extends('management.layouts.app')

@section('title', 'My Size Requests')

@section('content')
<div class="page-content">
    <div class="page-container">
        <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
            <div class="flex-grow-1">
                <h4 class="fs-18 text-uppercase fw-bold mb-0">My Size Requests</h4>
            </div>
            <div class="text-end">
                <ol class="breadcrumb m-0 py-0">
                    <li class="breadcrumb-item"><a href="{{ route('vendor.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('vendor.sizes.index') }}">Sizes</a></li>
                    <li class="breadcrumb-item active">My Requests</li>
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
                                <h6 class="mb-0">Total Requests</h6>
                                <h2 class="mb-0">{{ $statistics['total'] ?? 0 }}</h2>
                            </div>
                            <i class="ti ti-file" style="font-size: 40px; opacity: 0.5;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-dark">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="mb-0">Pending</h6>
                                <h2 class="mb-0">{{ $statistics['pending'] ?? 0 }}</h2>
                            </div>
                            <i class="ti ti-clock" style="font-size: 40px; opacity: 0.5;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="mb-0">Approved</h6>
                                <h2 class="mb-0">{{ $statistics['approved'] ?? 0 }}</h2>
                            </div>
                            <i class="ti ti-check-circle" style="font-size: 40px; opacity: 0.5;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-danger text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="mb-0">Rejected</h6>
                                <h2 class="mb-0">{{ $statistics['rejected'] ?? 0 }}</h2>
                            </div>
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
                        <label class="form-label">Filter by Gender</label>
                        <select class="form-select" id="genderFilter">
                            <option value="">All Genders</option>
                            <option value="Men" {{ request('gender') == 'Men' ? 'selected' : '' }}>Men</option>
                            <option value="Women" {{ request('gender') == 'Women' ? 'selected' : '' }}>Women</option>
                            <option value="Unisex" {{ request('gender') == 'Unisex' ? 'selected' : '' }}>Unisex</option>
                            <option value="Kids" {{ request('gender') == 'Kids' ? 'selected' : '' }}>Kids</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">&nbsp;</label>
                        <a href="{{ route('vendor.sizes.request.create') }}" class="btn btn-primary w-100">
                            <i class="ti ti-plus"></i> Request New Size
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
                                <th>Requested Size</th>
                                <th>Code</th>
                                <th>Gender</th>
                                <th>Categories</th>
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
                                    
                                    $genderClass = [
                                        'Men' => 'primary',
                                        'Women' => 'danger',
                                        'Unisex' => 'info',
                                        'Kids' => 'success',
                                    ][$request->requested_gender] ?? 'secondary';
                                @endphp
                                <tr>
                                    <td>#{{ $request->id }}</div></div></td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="ti ti-ruler text-primary"></i>
                                            <div>
                                                <strong>{{ $request->requested_name }}</strong>
                                                @if($request->description)
                                                    <br><small class="text-muted">{{ Str::limit($request->description, 50) }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </div></div></td>
                                    <td><code>{{ $request->requested_code }}</code></div></div></td>
                                    <td>
                                        <span class="badge bg-{{ $genderClass }}">{{ $request->requested_gender }}</span>
                                    </div></div></td>
                                    <td>
                                        @if($request->requested_category_ids && count($request->requested_category_ids) > 0)
                                            <span class="badge bg-info">{{ count($request->requested_category_ids) }} categories</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </div></div></td>
                                    <td>
                                        {{ $request->created_at->format('M d, Y') }}<br>
                                        <small class="text-muted">{{ $request->created_at->diffForHumans() }}</small>
                                    </div></div></td>
                                    <td><span class="badge {{ $statusClass }}">{{ ucfirst($request->status) }}</span></div></div></td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('vendor.sizes.requests.show', $request->id) }}" class="btn btn-info" title="View Details">
                                                <i class="ti ti-eye"></i>
                                            </a>
                                            @if($request->status === 'pending')
                                                <button type="button" class="btn btn-danger" onclick="cancelRequest({{ $request->id }}, '{{ $request->requested_name }}')" title="Cancel Request">
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
                                            <h5 class="mt-3">No Size Requests Found</h5>
                                            <p class="text-muted">You haven't submitted any size requests yet.</p>
                                            <a href="{{ route('vendor.sizes.request.create') }}" class="btn btn-primary mt-2">
                                                <i class="ti ti-plus"></i> Request New Size
                                            </a>
                                        </div>
                                    </td>
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
        let gender = $('#genderFilter').val();
        let url = '{{ route("vendor.sizes.requests.index") }}?';
        let params = [];
        
        if (status) params.push('status=' + status);
        if (gender) params.push('gender=' + gender);
        
        window.location.href = url + params.join('&');
    });

    $('#genderFilter').on('change', function() {
        let status = $('#statusFilter').val();
        let gender = $(this).val();
        let url = '{{ route("vendor.sizes.requests.index") }}?';
        let params = [];
        
        if (status) params.push('status=' + status);
        if (gender) params.push('gender=' + gender);
        
        window.location.href = url + params.join('&');
    });

    // Cancel request function
    function cancelRequest(requestId, requestName) {
        Swal.fire({
            title: 'Cancel Request?',
            text: `Are you sure you want to cancel the request for "${requestName}"?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, cancel it!'
        }).then((result) => {
            if (result.isConfirmed) {
                let form = $('#cancelForm');
                form.attr('action', '{{ url("vendor/sizes/requests") }}/' + requestId);
                
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