{{-- resources/views/marketplace/pages/attributes/request-details.blade.php --}}
@extends('management.layouts.app')

@section('title', 'Attribute Request Details')

@section('content')
<div class="page-content">
    <div class="page-container">
        <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
            <div class="flex-grow-1">
                <h4 class="fs-18 text-uppercase fw-bold mb-0">Attribute Request Details</h4>
            </div>
            <div class="text-end">
                <ol class="breadcrumb m-0 py-0">
                    <li class="breadcrumb-item"><a href="{{ route('vendor.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('vendor.attributes.index') }}">Attributes</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('vendor.attributes.requests.index') }}">My Requests</a></li>
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
                            <i class="ti ti-input text-primary fs-1"></i>
                            <div>
                                <h4 class="mb-0">{{ $request->requested_name }}</h4>
                                <span class="badge bg-info">{{ ucfirst($request->requested_type) }}</span>
                                @if($request->requested_group_id)
                                    <span class="badge bg-secondary ms-1">Group #{{ $request->requested_group_id }}</span>
                                @endif
                            </div>
                        </div>

                        <table class="table table-borderless">
                            @if($request->description)
                            <tr>
                                <th width="150">Description:</th>
                                <td>{{ $request->description }}</div></div></td>
                            </tr>
                            @endif
                            <tr>
                                <th>Reason:</th>
                                <td>{{ $request->reason }}</div></div></td>
                            </tr>
                            <tr>
                                <th>Required:</th>
                                <td>{{ $request->is_required ? 'Yes' : 'No' }}</div></div></td>
                            </tr>
                            <tr>
                                <th>Filterable:</th>
                                <td>{{ $request->is_filterable ? 'Yes' : 'No' }}</div></div></td>
                            </tr>
                        </table>
                    </div>
                </div>

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

                {{-- Requested Values Card (for select/multiselect/radio) --}}
                @if($request->requested_values && count($request->requested_values) > 0)
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ti ti-list"></i> Requested Values</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Value</th>
                                        <th>Label</th>
                                        <th>Color</th>
                                        <th>Price Adj.</th>
                                        <th>Weight Adj.</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($request->requested_values as $value)
                                    <tr>
                                        <td><code>{{ $value['value'] ?? $value }}</code></div></div></td>
                                        <td>{{ $value['label'] ?? $value }}</div></div></td>
                                        <td>
                                            @if(isset($value['color_code']) && $value['color_code'])
                                                <div style="width: 25px; height: 25px; background-color: {{ $value['color_code'] }}; border-radius: 50%; border: 1px solid #ddd;"></div>
                                            @else
                                                —
                                            @endif
                                        </div></div></td>
                                        <td>${{ number_format($value['price_adjustment'] ?? 0, 2) }}</div></div></td>
                                        <td>{{ $value['weight_adjustment'] ?? 0 }} kg</div></div></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
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

                {{-- Created Attribute Card --}}
                @if($request->status === 'approved' && $request->createdAttribute)
                <div class="card mb-3 bg-success-subtle">
                    <div class="card-header bg-success text-white">
                        <h5 class="card-title mb-0">Attribute Created</h5>
                    </div>
                    <div class="card-body">
                        <p>Your requested attribute has been created!</p>
                        <div class="d-flex align-items-center gap-3">
                            <i class="ti ti-input text-success fs-1"></i>
                            <div>
                                <strong>{{ $request->createdAttribute->name }}</strong>
                                <br>
                                <small class="text-muted">{{ $request->createdAttribute->type_label }} | {{ $request->createdAttribute->slug }}</small>
                            </div>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('vendor.products.create') }}?attribute={{ $request->createdAttribute->id }}" class="btn btn-sm btn-primary">
                                <i class="ti ti-package"></i> Add Product with this Attribute
                            </a>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Action Buttons --}}
                <div class="card">
                    <div class="card-footer d-flex justify-content-end gap-2">
                        <a href="{{ route('vendor.attributes.requests.index') }}" class="btn btn-secondary">
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
            form.attr('action', '{{ url("vendor/attributes/requests") }}/' + requestId);
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
                        window.location.href = '{{ route("vendor.attributes.requests.index") }}';
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