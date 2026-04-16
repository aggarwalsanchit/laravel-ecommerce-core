{{-- resources/views/marketplace/pages/brands/my-requests.blade.php --}}
@extends('management.layouts.app')

@section('title', 'My Brand Requests')

@section('content')
    <div class="page-content">
        <div class="page-container">
            <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                <div class="flex-grow-1">
                    <h4 class="fs-18 text-uppercase fw-bold mb-0">My Brand Requests</h4>
                </div>
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('vendor.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('vendor.brands.index') }}">Brands</a></li>
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

            {{-- Filter --}}
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <select class="form-select" id="statusFilter">
                                <option value="">All Requests</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending
                                </option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved
                                </option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Requests Table --}}
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Requested Brand</th>
                                    <th>Code</th>
                                    <th>Categories</th>
                                    <th>Request Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($requests as $request)
                                    @php
                                        $statusClass =
                                            [
                                                'pending' => 'bg-warning',
                                                'approved' => 'bg-success',
                                                'rejected' => 'bg-danger',
                                            ][$request->status] ?? 'bg-secondary';
                                    @endphp
                                    <tr>
                                        <td>#{{ $request->id }}
                    </div>
                </div>
                </td>
                <td>
                    <strong>{{ $request->requested_name }}</strong>
                    @if ($request->description)
                        <br><small class="text-muted">{{ Str::limit($request->description, 60) }}</small>
                    @endif
            </div>
        </div>
        </td>
        <td>
            <code class="small">{{ $request->requested_code ?? 'N/A' }}</code>
    </div>
    </div>
    </td>
    <td>
        @php
            $categoryNames = [];
            if (!empty($request->requested_category_ids)) {
                $categories = App\Models\Category::whereIn('id', $request->requested_category_ids)->get();
                $categoryNames = $categories->pluck('name')->toArray();
            }
        @endphp
        @if (count($categoryNames) > 0)
            <div class="d-flex flex-wrap gap-1">
                @foreach (array_slice($categoryNames, 0, 2) as $catName)
                    <span class="badge bg-info-subtle text-info" style="font-size: 10px;">{{ $catName }}</span>
                @endforeach
                @if (count($categoryNames) > 2)
                    <span class="badge bg-secondary-subtle text-secondary"
                        style="font-size: 10px;">+{{ count($categoryNames) - 2 }} more</span>
                @endif
            </div>
        @else
            <span class="text-muted small">No categories</span>
        @endif
        </div>
        </div>
    </td>
    <td>
        {{ $request->created_at->format('M d, Y') }}<br>
        <small class="text-muted">{{ $request->created_at->diffForHumans() }}</small>
        </div>
        </div>
    </td>
    <td><span class="badge {{ $statusClass }}">{{ ucfirst($request->status) }}</span></div>
        </div>
    </td>
    <td>
        <div class="btn-group btn-group-sm">
            <a href="{{ route('vendor.brands.requests.show', $request->id) }}" class="btn btn-info">
                <i class="ti ti-eye"></i>
            </a>
            @if ($request->status === 'pending')
                <button type="button" class="btn btn-danger"
                    onclick="cancelRequest({{ $request->id }}, '{{ $request->requested_name }}')">
                    <i class="ti ti-trash"></i>
                </button>
            @endif
        </div>
        </div>
        </div>
    </td>
    </tr>
@empty
    <tr>
        <td colspan="7" class="text-center py-5">
            <i class="ti ti-inbox" style="font-size: 48px; opacity: 0.5;"></i>
            <h5 class="mt-3">No Requests Found</h5>
            <p class="text-muted">You haven't submitted any brand requests yet.</p>
            <a href="{{ route('vendor.brands.request.create') }}" class="btn btn-primary mt-2">
                <i class="ti ti-plus"></i> Request New Brand
            </a>
            </div>
            </div>
        </td>
    </tr>
    @endforelse
    </tbody>
    </table>
    </div>
    {{ $requests->appends(request()->query())->links('pagination::bootstrap-5') }}
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
        $('#statusFilter').on('change', function() {
            let status = $(this).val();
            window.location.href = '{{ route('vendor.brands.requests.index') }}?status=' + status;
        });

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
                    form.attr('action', '{{ url('vendor/brands/requests') }}/' + requestId);
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
                        }
                    });
                }
            });
        }
    </script>
@endpush
