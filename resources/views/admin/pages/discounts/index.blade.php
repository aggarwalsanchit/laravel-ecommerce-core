{{-- resources/views/admin/pages/discounts/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Discounts')

@section('content')
    <div class="page-content">
        <div class="page-container">
            <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                <div class="flex-grow-1">
                    <h4 class="fs-18 text-uppercase fw-bold mb-0">Discounts</h4>
                </div>
                <div class="text-end">
                    <a href="{{ route('admin.discounts.create') }}" class="btn btn-primary">
                        <i class="ti ti-plus"></i> Add Discount
                    </a>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Code</th>
                                    <th>Type</th>
                                    <th>Value</th>
                                    <th>Target</th>
                                    <th>Dates</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($discounts as $discount)
                                    <tr>
                                        <td>{{ $discount->id }}</td>
                                        <td>{{ $discount->name }}</td>
                                        <td>{{ $discount->code }}</td>
                                        <td>
                                            <span
                                                class="badge bg-info">{{ str_replace('_', ' ', $discount->discount_type) }}</span>
                                        </td>
                                        <td>
                                            @if ($discount->discount_type == 'percentage')
                                                {{ $discount->discount_value }}%
                                            @elseif($discount->discount_type == 'fixed_amount')
                                                ${{ number_format($discount->discount_value, 2) }}
                                            @elseif($discount->discount_type == 'buy_x_get_y')
                                                Buy {{ $discount->buy_quantity }} Get {{ $discount->get_quantity }} Free
                                            @else
                                                Free Shipping
                                            @endif
                                        </td>
                                        <td>
                                            <span
                                                class="badge bg-secondary">{{ str_replace('_', ' ', $discount->target_type) }}</span>
                                        </td>
                                        <td>
                                            @if ($discount->start_date)
                                                {{ \Carbon\Carbon::parse($discount->start_date)->format('Y-m-d') }}
                                            @else
                                                Any
                                            @endif
                                            →
                                            @if ($discount->end_date)
                                                {{ \Carbon\Carbon::parse($discount->end_date)->format('Y-m-d') }}
                                            @else
                                                Any
                                            @endif
                                        </td>
                                        <td>
                                            @if ($discount->status)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.discounts.edit', $discount) }}"
                                                class="btn btn-sm btn-primary">
                                                <i class="ti ti-edit"></i>
                                            </a>
                                            <button class="btn btn-sm btn-danger delete-discount"
                                                data-id="{{ $discount->id }}">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">No discounts found. <a
                                                href="{{ route('admin.discounts.create') }}">Create one</a></td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $discounts->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.delete-discount').on('click', function() {
                let id = $(this).data('id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/admin/discounts/' + id,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire('Deleted!', response.message, 'success');
                                    location.reload();
                                }
                            },
                            error: function(xhr) {
                                Swal.fire('Error!', xhr.responseJSON?.message ||
                                    'Something went wrong!', 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
