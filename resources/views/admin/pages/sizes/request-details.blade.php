{{-- resources/views/admin/pages/sizes/request-details.blade.php --}}
@extends('management.layouts.app')

@section('title', 'Size Request Details')

@section('content')
<div class="page-content">
    <div class="page-container">
        <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
            <div class="flex-grow-1">
                <h4 class="fs-18 text-uppercase fw-bold mb-0">Size Request Details</h4>
            </div>
            <div class="text-end">
                <ol class="breadcrumb m-0 py-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.sizes.index') }}">Sizes</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.sizes.requests') }}">Requests</a></li>
                    <li class="breadcrumb-item active">Request #{{ $sizeRequest->id }}</li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4">
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ti ti-ruler"></i> Size Preview</h5>
                    </div>
                    <div class="card-body text-center">
                        @if($sizeRequest->image)
                            <img src="{{ asset('storage/size-requests/' . $sizeRequest->image) }}" alt="{{ $sizeRequest->requested_name }}" class="img-fluid rounded mb-3" style="max-height: 150px;">
                        @else
                            <div class="rounded mb-3 mx-auto bg-light d-flex align-items-center justify-content-center" style="width: 150px; height: 150px;">
                                <i class="ti ti-ruler fs-1 text-primary"></i>
                            </div>
                        @endif
                        <h3>{{ $sizeRequest->requested_name }}</h3>
                        <code class="fs-4">{{ $sizeRequest->requested_code }}</code>
                        <div class="mt-2">
                            <span class="badge bg-{{ $sizeRequest->requested_gender == 'Men' ? 'primary' : ($sizeRequest->requested_gender == 'Women' ? 'danger' : ($sizeRequest->requested_gender == 'Unisex' ? 'info' : 'success')) }} fs-6">{{ $sizeRequest->requested_gender }}</span>
                        </div>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ti ti-info-circle"></i> Request Information</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr><td width="120"><strong>Request ID:</strong></td><td>#{{ $sizeRequest->id }}</td></tr>
                            <tr><td><strong>Status:</strong></td><td>{!! $sizeRequest->status_badge !!}</td></tr>
                            <tr><td><strong>Requested By:</strong></td><td>Vendor #{{ $sizeRequest->vendor_id }}<br><small>{{ $sizeRequest->vendor->name ?? 'N/A' }}</small></td></tr>
                            <tr><td><strong>Request Date:</strong></td><td>{{ $sizeRequest->created_at->format('F d, Y H:i:s') }}</td></tr>
                            @if($sizeRequest->approved_at)
                            <tr><td><strong>Processed Date:</strong></td><td>{{ $sizeRequest->approved_at->format('F d, Y H:i:s') }}</td></tr>
                            @endif
                            <tr><td><strong>Processed By:</strong></td><td>@if($sizeRequest->approvedBy) Admin #{{ $sizeRequest->approved_by }}@else Not processed yet @endif</td></tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                @if($sizeRequest->description)
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ti ti-align-left"></i> Description</h5>
                    </div>
                    <div class="card-body">
                        <p>{{ $sizeRequest->description }}</p>
                    </div>
                </div>
                @endif

                @if($sizeRequest->reason)
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ti ti-message"></i> Reason for Request</h5>
                    </div>
                    <div class="card-body">
                        <p>{{ $sizeRequest->reason }}</p>
                    </div>
                </div>
                @endif

                {{-- Measurements Section --}}
                @if($sizeRequest->requested_chest || $sizeRequest->requested_waist || $sizeRequest->requested_hip || $sizeRequest->requested_inseam || $sizeRequest->requested_shoulder || $sizeRequest->requested_sleeve || $sizeRequest->requested_neck)
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ti ti-ruler"></i> Requested Measurements (inches)</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @if($sizeRequest->requested_chest)<div class="col-md-4 mb-2"><strong>Chest/Bust:</strong> {{ $sizeRequest->requested_chest }}"</div>@endif
                            @if($sizeRequest->requested_waist)<div class="col-md-4 mb-2"><strong>Waist:</strong> {{ $sizeRequest->requested_waist }}"</div>@endif
                            @if($sizeRequest->requested_hip)<div class="col-md-4 mb-2"><strong>Hip:</strong> {{ $sizeRequest->requested_hip }}"</div>@endif
                            @if($sizeRequest->requested_inseam)<div class="col-md-4 mb-2"><strong>Inseam:</strong> {{ $sizeRequest->requested_inseam }}"</div>@endif
                            @if($sizeRequest->requested_shoulder)<div class="col-md-4 mb-2"><strong>Shoulder:</strong> {{ $sizeRequest->requested_shoulder }}"</div>@endif
                            @if($sizeRequest->requested_sleeve)<div class="col-md-4 mb-2"><strong>Sleeve:</strong> {{ $sizeRequest->requested_sleeve }}"</div>@endif
                            @if($sizeRequest->requested_neck)<div class="col-md-4 mb-2"><strong>Neck:</strong> {{ $sizeRequest->requested_neck }}"</div>@endif
                            @if($sizeRequest->requested_height)<div class="col-md-4 mb-2"><strong>Height:</strong> {{ $sizeRequest->requested_height }}'</div>@endif
                            @if($sizeRequest->requested_weight)<div class="col-md-4 mb-2"><strong>Weight:</strong> {{ $sizeRequest->requested_weight }} lbs</div>@endif
                        </div>
                    </div>
                </div>
                @endif

                {{-- Size Conversion Section --}}
                @if($sizeRequest->requested_us_size || $sizeRequest->requested_uk_size || $sizeRequest->requested_eu_size || $sizeRequest->requested_au_size || $sizeRequest->requested_jp_size || $sizeRequest->requested_cn_size || $sizeRequest->requested_int_size)
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ti ti-exchange"></i> Requested Size Conversion</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>US</th>
                                        <th>UK</th>
                                        <th>EU</th>
                                        <th>AU</th>
                                        <th>JP</th>
                                        <th>CN</th>
                                        <th>International</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="fw-semibold">{{ $sizeRequest->requested_us_size ?? '-' }}</td>
                                        <td>{{ $sizeRequest->requested_uk_size ?? '-' }}</td>
                                        <td>{{ $sizeRequest->requested_eu_size ?? '-' }}</td>
                                        <td>{{ $sizeRequest->requested_au_size ?? '-' }}</td>
                                        <td>{{ $sizeRequest->requested_jp_size ?? '-' }}</td>
                                        <td>{{ $sizeRequest->requested_cn_size ?? '-' }}</td>
                                        <td>{{ $sizeRequest->requested_int_size ?? '-' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Requested Categories --}}
                @if($sizeRequest->requested_category_ids && count($sizeRequest->requested_category_ids) > 0)
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ti ti-folder"></i> Requested Categories</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($sizeRequest->requestedCategories() as $category)
                                <div class="col-md-6 mb-2">
                                    <div class="d-flex align-items-center p-2 bg-light rounded">
                                        <i class="ti ti-folder text-primary me-2"></i>
                                        <span>{{ $category->name }}</span>
                                        @if($category->parent)
                                            <small class="text-muted ms-2">({{ $category->parent->name }})</small>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                @if($sizeRequest->rejection_reason)
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0 text-danger"><i class="ti ti-alert-circle"></i> Rejection Reason</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-danger">{{ $sizeRequest->rejection_reason }}</p>
                    </div>
                </div>
                @endif

                @if($sizeRequest->admin_notes)
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ti ti-notes"></i> Admin Notes</h5>
                    </div>
                    <div class="card-body">
                        <p>{{ $sizeRequest->admin_notes }}</p>
                    </div>
                </div>
                @endif

                @if($sizeRequest->status === 'approved' && $sizeRequest->createdSize)
                <div class="card mb-3">
                    <div class="card-header bg-success text-white">
                        <h5 class="card-title mb-0"><i class="ti ti-check-circle"></i> Created Size</h5>
                    </div>
                    <div class="card-body">
                        <p>The following size has been created from this request:</p>
                        <div class="d-flex align-items-center gap-3">
                            <i class="ti ti-ruler text-primary fs-1"></i>
                            <div>
                                <strong>{{ $sizeRequest->createdSize->name }}</strong><br>
                                <small class="text-muted">Code: {{ $sizeRequest->createdSize->code }} | Gender: {{ $sizeRequest->createdSize->gender }}</small>
                            </div>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('admin.sizes.show', $sizeRequest->createdSize->id) }}" class="btn btn-sm btn-primary">
                                <i class="ti ti-eye"></i> View Size
                            </a>
                        </div>
                    </div>
                </div>
                @endif

                <div class="card">
                    <div class="card-footer d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.sizes.requests') }}" class="btn btn-secondary">
                            <i class="ti ti-arrow-left me-1"></i> Back to Requests
                        </a>
                        @if($sizeRequest->status === 'pending')
                            @can('approve_sizes')
                                <button type="button" class="btn btn-success" onclick="approveRequest({{ $sizeRequest->id }})">
                                    <i class="ti ti-check me-1"></i> Approve Request
                                </button>
                                <button type="button" class="btn btn-warning" onclick="showRejectModal({{ $sizeRequest->id }}, '{{ $sizeRequest->requested_name }}')">
                                    <i class="ti ti-x me-1"></i> Reject Request
                                </button>
                            @endcan
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
function approveRequest(requestId) {
    Swal.fire({
        title: 'Approve Size Request?',
        text: 'This will create a new size and make it available to all vendors.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        confirmButtonText: 'Yes, approve it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '{{ url("admin/sizes/requests") }}/' + requestId + '/approve',
                type: 'POST',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({ icon: 'success', title: 'Approved!', text: response.message, timer: 1500, showConfirmButton: false })
                            .then(() => location.reload());
                    }
                },
                error: function(xhr) {
                    Swal.fire({ icon: 'error', title: 'Error!', text: xhr.responseJSON?.message || 'Failed to approve request.', confirmButtonColor: '#d33' });
                }
            });
        }
    });
}

function showRejectModal(requestId, requestName) {
    Swal.fire({
        title: 'Reject Size Request',
        html: `<p>Reject "${requestName}"?</p><textarea id="rejectionReason" class="swal2-textarea" placeholder="Provide rejection reason..." rows="3"></textarea>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        confirmButtonText: 'Yes, reject it!',
        preConfirm: () => {
            const reason = document.getElementById('rejectionReason').value;
            if (!reason) { Swal.showValidationMessage('Please provide a reason'); return false; }
            return { reason: reason };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '{{ url("admin/sizes/requests") }}/' + requestId + '/reject',
                type: 'POST',
                data: { _token: '{{ csrf_token() }}', rejection_reason: result.value.reason },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({ icon: 'success', title: 'Rejected!', text: response.message, timer: 1500, showConfirmButton: false })
                            .then(() => location.reload());
                    }
                },
                error: function(xhr) {
                    Swal.fire({ icon: 'error', title: 'Error!', text: xhr.responseJSON?.message || 'Failed to reject request.', confirmButtonColor: '#d33' });
                }
            });
        }
    });
}
</script>
@endpush