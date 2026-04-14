{{-- resources/views/marketplace/pages/sizes/request-details.blade.php --}}
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
                    <li class="breadcrumb-item"><a href="{{ route('vendor.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('vendor.sizes.index') }}">Sizes</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('vendor.sizes.requests.index') }}">My Requests</a></li>
                    <li class="breadcrumb-item active">Request #{{ $request->id }}</li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8 mx-auto">
                {{-- Status Card --}}
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Request Status</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                @if($request->status == 'pending')
                                    <span class="badge bg-warning fs-6">Pending Review</span>
                                @elseif($request->status == 'approved')
                                    <span class="badge bg-success fs-6">Approved</span>
                                @else
                                    <span class="badge bg-danger fs-6">Rejected</span>
                                @endif
                            </div>
                            <div class="text-muted">
                                Submitted: {{ $request->created_at->format('F d, Y H:i') }}
                            </div>
                        </div>
                        @if($request->approved_at)
                            <div class="mt-2 text-muted small">
                                Processed: {{ $request->approved_at->format('F d, Y H:i') }}
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Request Details Card --}}
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Request Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <i class="ti ti-ruler text-primary fs-1"></i>
                            <div>
                                <h4 class="mb-0">{{ $request->requested_name }}</h4>
                                <code>{{ $request->requested_code }}</code>
                                <span class="badge bg-{{ $request->requested_gender == 'Men' ? 'primary' : ($request->requested_gender == 'Women' ? 'danger' : ($request->requested_gender == 'Unisex' ? 'info' : 'success')) }} ms-2">
                                    {{ $request->requested_gender }}
                                </span>
                            </div>
                        </div>

                        <table class="table table-borderless">
                            @if($request->description)
                            <tr>
                                <th width="150">Description:</th>
                                <td>{{ $request->description }}</td>
                            </tr>
                            @endif
                            <tr>
                                <th>Reason:</th>
                                <td>{{ $request->reason }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                {{-- Requested Measurements Card --}}
                @if($request->requested_chest || $request->requested_waist || $request->requested_hip || $request->requested_inseam)
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ti ti-ruler"></i> Requested Measurements (inches)</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @if($request->requested_chest)
                                <div class="col-md-6 mb-2">
                                    <strong>Chest/Bust:</strong> {{ $request->requested_chest }}"
                                </div>
                            @endif
                            @if($request->requested_waist)
                                <div class="col-md-6 mb-2">
                                    <strong>Waist:</strong> {{ $request->requested_waist }}"
                                </div>
                            @endif
                            @if($request->requested_hip)
                                <div class="col-md-6 mb-2">
                                    <strong>Hip:</strong> {{ $request->requested_hip }}"
                                </div>
                            @endif
                            @if($request->requested_inseam)
                                <div class="col-md-6 mb-2">
                                    <strong>Inseam:</strong> {{ $request->requested_inseam }}"
                                </div>
                            @endif
                            @if($request->requested_shoulder)
                                <div class="col-md-6 mb-2">
                                    <strong>Shoulder:</strong> {{ $request->requested_shoulder }}"
                                </div>
                            @endif
                            @if($request->requested_sleeve)
                                <div class="col-md-6 mb-2">
                                    <strong>Sleeve:</strong> {{ $request->requested_sleeve }}"
                                </div>
                            @endif
                            @if($request->requested_neck)
                                <div class="col-md-6 mb-2">
                                    <strong>Neck:</strong> {{ $request->requested_neck }}"
                                </div>
                            @endif
                            @if($request->requested_height)
                                <div class="col-md-6 mb-2">
                                    <strong>Height:</strong> {{ $request->requested_height }}'
                                </div>
                            @endif
                            @if($request->requested_weight)
                                <div class="col-md-6 mb-2">
                                    <strong>Weight:</strong> {{ $request->requested_weight }} lbs
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                {{-- Requested Size Conversion Card --}}
                @if($request->requested_us_size || $request->requested_uk_size || $request->requested_eu_size || $request->requested_int_size)
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ti ti-exchange"></i> Requested Size Conversion</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
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
                                        <td class="fw-semibold">{{ $request->requested_us_size ?? '-' }}</td>
                                        <td>{{ $request->requested_uk_size ?? '-' }}</td>
                                        <td>{{ $request->requested_eu_size ?? '-' }}</td>
                                        <td>{{ $request->requested_au_size ?? '-' }}</td>
                                        <td>{{ $request->requested_jp_size ?? '-' }}</td>
                                        <td>{{ $request->requested_cn_size ?? '-' }}</td>
                                        <td>{{ $request->requested_int_size ?? '-' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Requested Categories Card --}}
                @if($request->requested_category_ids && count($request->requested_category_ids) > 0)
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ti ti-folder"></i> Requested Categories</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-1">
                            @php
                                $categories = App\Models\Category::whereIn('id', $request->requested_category_ids)->get();
                            @endphp
                            @foreach($categories as $category)
                                <span class="badge bg-primary">{{ $category->name }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                {{-- Rejection Reason Card --}}
                @if($request->rejection_reason)
                <div class="card mb-3 border-danger">
                    <div class="card-header bg-danger text-white">
                        <h5 class="card-title mb-0">Rejection Reason</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">{{ $request->rejection_reason }}</p>
                    </div>
                </div>
                @endif

                {{-- Admin Notes Card --}}
                @if($request->admin_notes)
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Admin Notes</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">{{ $request->admin_notes }}</p>
                    </div>
                </div>
                @endif

                {{-- Created Size Card --}}
                @if($request->status === 'approved' && $request->createdSize)
                <div class="card mb-3 bg-success-subtle">
                    <div class="card-header bg-success text-white">
                        <h5 class="card-title mb-0">Size Created</h5>
                    </div>
                    <div class="card-body">
                        <p>Your requested size has been created!</p>
                        <div class="d-flex align-items-center gap-3">
                            <i class="ti ti-ruler text-success fs-1"></i>
                            <div>
                                <strong>{{ $request->createdSize->name }}</strong>
                                <br>
                                <small class="text-muted">{{ $request->createdSize->code }} | {{ $request->createdSize->gender }}</small>
                            </div>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('vendor.products.create') }}?size={{ $request->createdSize->id }}" class="btn btn-sm btn-primary">
                                <i class="ti ti-package"></i> Add Product with this Size
                            </a>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Action Buttons --}}
                <div class="card">
                    <div class="card-footer d-flex justify-content-end gap-2">
                        <a href="{{ route('vendor.sizes.requests.index') }}" class="btn btn-secondary">
                            <i class="ti ti-arrow-left me-1"></i> Back to Requests
                        </a>
                        @if($request->status === 'pending')
                            <button type="button" class="btn btn-danger" onclick="cancelRequest({{ $request->id }}, '{{ $request->requested_name }}')">
                                <i class="ti ti-trash me-1"></i> Cancel Request
                            </button>
                        @endif
                    </div>
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
                        window.location.href = '{{ route("vendor.sizes.requests.index") }}';
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
    .table-borderless td, .table-borderless th {
        padding: 0.5rem 0;
    }
    .bg-success-subtle {
        background-color: rgba(25, 135, 84, 0.1);
    }
</style>
@endpush