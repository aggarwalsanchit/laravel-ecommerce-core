{{-- resources/views/marketplace/pages/brands/request-details.blade.php --}}
@extends('management.layouts.app')

@section('title', 'Brand Request Details')

@section('content')
    <div class="page-content">
        <div class="page-container">
            <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                <div class="flex-grow-1">
                    <h4 class="fs-18 text-uppercase fw-bold mb-0">Brand Request Details</h4>
                </div>
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('vendor.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('vendor.brands.index') }}">Brands</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('vendor.brands.requests.index') }}">My Requests</a>
                        </li>
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
                                    @if ($request->status == 'pending')
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
                            @if ($request->approved_at)
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
                            <table class="table table-borderless">
                                <tr>
                                    <th width="150">Brand Name:</th>
                                    <td><strong>{{ $request->requested_name }}</strong>
                        </div>
                    </div>
                    </td>
                    </tr>
                    <tr>
                        <th>Brand Code:</th>
                        <td><code>{{ $request->requested_code ?? 'N/A' }}</code>
                </div>
            </div>
            </td>
            </tr>
            <tr>
                <th>Slug:</th>
                <td><code>{{ $request->requested_slug ?? 'N/A' }}</code>
        </div>
    </div>
    </tr>
    @if (!empty($request->requested_category_ids))
        <tr>
            <th>Requested Categories:</th>
            <td>
                @php
                    $requestedCategories = App\Models\Category::whereIn('id', $request->requested_category_ids)->get();
                @endphp
                @if ($requestedCategories->count() > 0)
                    <div class="d-flex flex-wrap gap-1">
                        @foreach ($requestedCategories as $cat)
                            <span class="badge bg-primary p-2">
                                <i class="ti ti-folder"></i> {{ $cat->name }}
                            </span>
                        @endforeach
                    </div>
                @else
                    <span class="text-muted">No categories selected</span>
                @endif
            </td>
        </tr>
    @endif
    @if ($request->description)
        <tr>
            <th>Description:</th>
            <td>{{ $request->description }}</div>
                </div>
            </td>
        </tr>
    @endif
    <tr>
        <th>Reason:</th>
        <td>{{ $request->reason }}</div>
            </div>
        </td>
    </tr>
    </table>
    </div>
    </div>

    {{-- Rejection Reason Card --}}
    @if ($request->rejection_reason)
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
    @if ($request->admin_notes)
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="card-title mb-0">Admin Notes</h5>
            </div>
            <div class="card-body">
                <p class="mb-0">{{ $request->admin_notes }}</p>
            </div>
        </div>
    @endif

    {{-- Created Brand Card --}}
    @if ($request->status === 'approved' && $request->createdBrand)
        <div class="card mb-3 bg-success-subtle">
            <div class="card-header bg-success text-white">
                <h5 class="card-title mb-0">Brand Created</h5>
            </div>
            <div class="card-body">
                <p>Your requested brand has been created!</p>
                <div class="d-flex align-items-center gap-3">
                    @if ($request->createdBrand->logo)
                        <img src="{{ asset('storage/brands/' . $request->createdBrand->logo) }}"
                            alt="{{ $request->createdBrand->name }}" style="width: 60px; height: 60px; object-fit: cover;"
                            class="rounded border">
                    @else
                        <i class="ti ti-brand-airbnb text-success fs-1"></i>
                    @endif
                    <div>
                        <strong>{{ $request->createdBrand->name }}</strong>
                        <br>
                        <small class="text-muted">Code: {{ $request->createdBrand->code }}</small>
                        <br>
                        <small class="text-muted">You can now use this brand for your products.</small>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="{{ route('vendor.products.create') }}?brand_id={{ $request->createdBrand->id }}"
                        class="btn btn-sm btn-primary">
                        <i class="ti ti-package"></i> Add Product with this Brand
                    </a>
                </div>
            </div>
        </div>
    @endif

    {{-- Action Buttons --}}
    <div class="card">
        <div class="card-footer d-flex justify-content-end gap-2">
            <a href="{{ route('vendor.brands.requests.index') }}" class="btn btn-secondary">
                <i class="ti ti-arrow-left me-1"></i> Back to Requests
            </a>
            @if ($request->status === 'pending')
                <button type="button" class="btn btn-danger"
                    onclick="cancelRequest({{ $request->id }}, '{{ $request->requested_name }}')">
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
                                window.location.href =
                                    '{{ route('vendor.brands.requests.index') }}';
                            });
                        }
                    });
                }
            });
        }
    </script>
@endpush
