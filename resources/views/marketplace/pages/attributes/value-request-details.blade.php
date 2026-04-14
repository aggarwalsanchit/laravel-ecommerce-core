{{-- resources/views/marketplace/pages/attributes/value-request-details.blade.php --}}
@extends('management.layouts.app')

@section('title', 'Attribute Value Request Details')

@section('content')
<div class="page-content">
    <div class="page-container">
        <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
            <div class="flex-grow-1">
                <h4 class="fs-18 text-uppercase fw-bold mb-0">Attribute Value Request Details</h4>
            </div>
            <div class="text-end">
                <ol class="breadcrumb m-0 py-0">
                    <li class="breadcrumb-item"><a href="{{ route('vendor.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('vendor.attributes.index') }}">Attributes</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('vendor.attributes.value-requests.index') }}">Value Requests</a></li>
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
                            @if($request->requested_color_code)
                                <div style="width: 60px; height: 60px; background-color: {{ $request->requested_color_code }}; border-radius: 50%; border: 2px solid #ddd;"></div>
                            @else
                                <i class="ti ti-tag text-primary fs-1"></i>
                            @endif
                            <div>
                                <h4 class="mb-0">{{ $request->requested_value }}</h4>
                                @if($request->requested_label)
                                    <div class="text-muted">Display as: {{ $request->requested_label }}</div>
                                @endif
                                <span class="badge bg-primary">{{ $request->attribute->name ?? 'Unknown' }}</span>
                            </div>
                        </div>

                        <table class="table table-borderless">
                            <tr>
                                <th width="150">Attribute:</th>
                                <td>{{ $request->attribute->name ?? 'Unknown' }} ({{ $request->attribute->type_label ?? 'Unknown' }})</div></div></td>
                            </tr>
                            @if($request->requested_color_code)
                            <tr>
                                <th>Color Code:</th>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div style="width: 30px; height: 30px; background-color: {{ $request->requested_color_code }}; border-radius: 50%; border: 1px solid #ddd;"></div>
                                        <code>{{ $request->requested_color_code }}</code>
                                    </div>
                                 </div></div></td>
                            </tr>
                            @endif
                            <tr>
                                <th>Reason:</th>
                                <td>{{ $request->reason }}</div></div></td>
                            </tr>
                        </table>
                    </div>
                </div>

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

                {{-- Created Value Card --}}
                @if($request->status === 'approved' && $request->createdValue)
                <div class="card mb-3 bg-success-subtle">
                    <div class="card-header bg-success text-white">
                        <h5 class="card-title mb-0">Value Created</h5>
                    </div>
                    <div class="card-body">
                        <p>Your requested value has been created!</p>
                        <div class="d-flex align-items-center gap-3">
                            @if($request->createdValue->color_code)
                                <div style="width: 50px; height: 50px; background-color: {{ $request->createdValue->color_code }}; border-radius: 50%; border: 1px solid #ddd;"></div>
                            @elseif($request->createdValue->image)
                                <img src="{{ asset('storage/' . $request->createdValue->image) }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                            @else
                                <i class="ti ti-tag text-success fs-1"></i>
                            @endif
                            <div>
                                <strong>{{ $request->createdValue->value }}</strong>
                                <br>
                                <small class="text-muted">
                                    @if($request->createdValue->label) Label: {{ $request->createdValue->label }}<br>@endif
                                    @if($request->createdValue->price_adjustment != 0) Price: +${{ number_format($request->createdValue->price_adjustment, 2) }} @endif
                                    @if($request->createdValue->weight_adjustment != 0) Weight: +{{ $request->createdValue->weight_adjustment }} kg @endif
                                </small>
                            </div>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('vendor.products.create') }}?attribute={{ $request->attribute_id }}&value={{ $request->createdValue->id }}" class="btn btn-sm btn-primary">
                                <i class="ti ti-package"></i> Add Product with this Value
                            </a>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Action Buttons --}}
                <div class="card">
                    <div class="card-footer d-flex justify-content-end gap-2">
                        <a href="{{ route('vendor.attributes.value-requests.index') }}" class="btn btn-secondary">
                            <i class="ti ti-arrow-left me-1"></i> Back to Requests
                        </a>
                        @if($request->status === 'pending')
                            <button type="button" class="btn btn-danger" onclick="cancelValueRequest({{ $request->id }}, '{{ $request->requested_value }}')">
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
                        window.location.href = '{{ route("vendor.attributes.value-requests.index") }}';
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
    .table-borderless td, .table-borderless th {
        padding: 0.5rem 0;
    }
    .bg-success-subtle {
        background-color: rgba(25, 135, 84, 0.1);
    }
</style>
@endpush