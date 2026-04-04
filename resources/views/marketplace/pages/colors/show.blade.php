{{-- resources/views/admin/colors/show.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Color Details')

@section('content')
    <div class="page-content">
        <div class="page-container">
            <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                <div class="flex-grow-1">
                    <h4 class="fs-18 text-uppercase fw-bold mb-0">Color Details: {{ $color->name }}</h4>
                </div>
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.colors.index') }}">Colors</a></li>
                        <li class="breadcrumb-item active">Color Details</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-4">
                    {{-- Color Information Card --}}
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Color Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="text-center mb-4">
                                {{-- Color Preview Circle --}}
                                <div
                                    style="width: 120px; height: 120px; background: {{ $color->hex_code }}; border-radius: 50%; margin: 0 auto; border: 3px solid #fff; box-shadow: 0 0 0 2px #dee2e6; transition: transform 0.3s ease;">
                                </div>
                                <div class="mt-3">
                                    <code class="fs-5">{{ $color->hex_code }}</code>
                                </div>
                            </div>

                            <table class="table table-borderless">
                                32
                                <td width="120"><strong>ID:</strong>64
                                <td>#{{ $color->id }}64
                                    </tr>
                                    32
                                <td><strong>Name:</strong>64
                                <td>
                                    <span class="fw-semibold">{{ $color->name }}</span>
                                    <br><small class="text-muted">{{ $color->slug }}</small>
                                    64
                                    </tr>
                                    32
                                <td><strong>Code:</strong>64
                                <td>
                                    <span class="badge bg-secondary-subtle text-secondary p-2">
                                        <i class="ti ti-barcode"></i> {{ $color->code }}
                                    </span>
                                    64
                                    </tr>
                                    32
                                <td><strong>Status:</strong>64
                                <td>
                                    @if ($color->status)
                                        <span class="badge bg-success-subtle text-success">
                                            <i class="ti ti-circle-check"></i> Active
                                        </span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger">
                                            <i class="ti ti-circle-x"></i> Inactive
                                        </span>
                                    @endif
                                    64
                                    </tr>
                                    32
                                <td><strong>Display Order:</strong>64
                                <td>{{ $color->order }}64
                                    </tr>
                                    32
                                <td><strong>Created:</strong>64
                                <td>
                                    {{ $color->created_at->format('F d, Y H:i') }}<br>
                                    <small class="text-muted">{{ $color->created_at->diffForHumans() }}</small>
                                    64
                                    </tr>
                                    32
                                <td><strong>Last Updated:</strong>64
                                <td>{{ $color->updated_at->diffForHumans() }}64
                                    </tr>
                            </table>
                        </div>
                    </div>

                    {{-- Analytics Card --}}
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ti ti-chart-bar"></i> Analytics
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-6 mb-3">
                                    <div class="bg-primary-subtle rounded p-3">
                                        <h3 class="mb-0">{{ number_format($color->view_count) }}</h3>
                                        <small class="text-muted">Total Views</small>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="bg-success-subtle rounded p-3">
                                        <h3 class="mb-0">{{ number_format($color->product_count) }}</h3>
                                        <small class="text-muted">Products</small>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="bg-warning-subtle rounded p-3">
                                        <h3 class="mb-0">{{ number_format($color->order_count) }}</h3>
                                        <small class="text-muted">Orders</small>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="bg-info-subtle rounded p-3">
                                        <h3 class="mb-0">${{ number_format($color->total_revenue, 2) }}</h3>
                                        <small class="text-muted">Revenue</small>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="small text-muted text-center">
                                <i class="ti ti-info-circle"></i>
                                Performance data updated in real-time
                            </div>
                        </div>
                    </div>

                    {{-- SEO Information Card --}}
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ti ti-chart-line"></i> SEO Information
                            </h5>
                        </div>
                        <div class="card-body">
                            @if ($color->meta_title || $color->meta_description)
                                <div class="mb-3">
                                    <label class="text-muted small">Meta Title</label>
                                    <p class="mb-0">{{ $color->meta_title ?: 'Not set' }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="text-muted small">Meta Description</label>
                                    <p class="mb-0">{{ $color->meta_description ?: 'Not set' }}</p>
                                </div>
                                <div class="alert alert-info mt-2">
                                    <i class="ti ti-eye me-1"></i>
                                    <strong>SEO Preview:</strong>
                                    <div class="mt-2">
                                        <div class="text-primary">{{ $color->meta_title ?: $color->name }}</div>
                                        <div class="text-muted small">{{ url('/color') }}/{{ $color->slug }}</div>
                                        <div class="text-muted small">
                                            {{ Str::limit($color->meta_description ?: $color->description ?: 'Color description will appear here...', 160) }}
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-3">
                                    <i class="ti ti-chart-line-off" style="font-size: 48px; opacity: 0.5;"></i>
                                    <p class="text-muted mt-2">No SEO settings configured.</p>
                                    <a href="{{ route('admin.colors.edit', $color->id) }}" class="btn btn-sm btn-primary">
                                        <i class="ti ti-edit"></i> Add SEO
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    {{-- Description Card --}}
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Description</h5>
                        </div>
                        <div class="card-body">
                            @if ($color->description)
                                <div class="p-3 bg-light rounded">
                                    {!! nl2br(e($color->description)) !!}
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="ti ti-file-description" style="font-size: 48px; opacity: 0.5;"></i>
                                    <p class="text-muted mt-2">No description provided.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Products Using This Color --}}
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ti ti-package"></i> Products Using This Color
                                <span class="badge bg-primary ms-2">{{ $color->products()->count() }}</span>
                            </h5>
                        </div>
                        <div class="card-body">
                            @if ($color->products()->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            32
                                            <th>ID</th>
                                            <th>Product</th>
                                            <th>Price</th>
                                            <th>Stock</th>
                                            <th>Orders</th>
                                            <th>Revenue</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($color->products()->take(10)->get() as $product)
                                                <tr>
                                                    <td>#{{ $product->id }}</td>
                                                    <td>
                                                        <a href="{{ route('admin.products.show', $product->id) }}">
                                                            {{ $product->name }}
                                                        </a>
                                                    </td>
                                                    <td>${{ number_format($product->price, 2) }}</td>
                                                    <td>
                                                        @if ($product->stock > 0)
                                                            <span class="badge bg-success">In Stock</span>
                                                        @else
                                                            <span class="badge bg-danger">Out of Stock</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ number_format($product->order_count ?? 0) }}</td>
                                                    <td>${{ number_format($product->total_sold_value ?? 0, 2) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @if ($color->products()->count() > 10)
                                    <div class="text-center mt-3">
                                        <a href="#" class="btn btn-sm btn-outline-primary">View All
                                            {{ $color->products()->count() }} Products</a>
                                    </div>
                                @endif
                            @else
                                <div class="text-center py-4">
                                    <i class="ti ti-package-off" style="font-size: 48px; opacity: 0.5;"></i>
                                    <p class="text-muted mt-2">No products currently use this color.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Color Variations Preview --}}
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ti ti-palette"></i> Color Variations
                            </h5>
                        </div>
                        <div class="card-body">
                            @php
                                $variations = [
                                    'lighter' => $color->hex_code,
                                    'darker' => $color->hex_code,
                                    'complementary' => $color->hex_code,
                                ];
                            @endphp
                            <div class="row text-center">
                                <div class="col-4">
                                    <div
                                        style="width: 80px; height: 80px; background: {{ $color->hex_code }}; border-radius: 12px; margin: 0 auto; border: 1px solid #dee2e6;">
                                    </div>
                                    <small class="text-muted mt-2 d-block">Original</small>
                                </div>
                                <div class="col-4">
                                    <div
                                        style="width: 80px; height: 80px; background: {{ $color->hex_code }}; opacity: 0.7; border-radius: 12px; margin: 0 auto; border: 1px solid #dee2e6;">
                                    </div>
                                    <small class="text-muted mt-2 d-block">Light (70%)</small>
                                </div>
                                <div class="col-4">
                                    <div
                                        style="width: 80px; height: 80px; background: {{ $color->hex_code }}; opacity: 0.3; border-radius: 12px; margin: 0 auto; border: 1px solid #dee2e6;">
                                    </div>
                                    <small class="text-muted mt-2 d-block">Very Light (30%)</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Performance Chart --}}
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ti ti-chart-bar"></i> Performance (Last 30 Days)
                            </h5>
                        </div>
                        <div class="card-body">
                            <canvas id="colorPerformanceChart" height="200"></canvas>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="card mt-3">
                        <div class="card-footer text-end">
                            <a href="{{ route('admin.colors.index') }}" class="btn btn-secondary">
                                <i class="ti ti-arrow-left me-1"></i> Back
                            </a>
                            @can('edit colors')
                                <a href="{{ route('admin.colors.edit', $color->id) }}" class="btn btn-primary">
                                    <i class="ti ti-edit me-1"></i> Edit Color
                                </a>
                            @endcan
                            @can('delete colors')
                                @if ($color->product_count == 0)
                                    <button type="button" class="btn btn-danger"
                                        onclick="confirmDelete({{ $color->id }})">
                                        <i class="ti ti-trash me-1"></i> Delete Color
                                    </button>
                                @endif
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Delete Form --}}
    <form id="deleteForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize tooltips
            $('[data-bs-toggle="tooltip"]').tooltip();

            // Performance Chart
            const ctx = document.getElementById('colorPerformanceChart').getContext('2d');

            // Generate sample data for last 30 days
            const labels = [];
            const viewsData = [];
            const ordersData = [];

            for (let i = 29; i >= 0; i--) {
                const date = new Date();
                date.setDate(date.getDate() - i);
                labels.push(date.toLocaleDateString('en-US', {
                    month: 'short',
                    day: 'numeric'
                }));

                // Sample data - in real app, fetch from backend
                viewsData.push(Math.floor(Math.random() * 100) + 10);
                ordersData.push(Math.floor(Math.random() * 20) + 1);
            }

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                            label: 'Views',
                            data: viewsData,
                            borderColor: '#{{ substr($color->hex_code, 1) }}',
                            backgroundColor: 'rgba({{ hexdec(substr($color->hex_code, 1, 2)) }}, {{ hexdec(substr($color->hex_code, 3, 2)) }}, {{ hexdec(substr($color->hex_code, 5, 2)) }}, 0.1)',
                            tension: 0.4,
                            fill: true
                        },
                        {
                            label: 'Orders',
                            data: ordersData,
                            borderColor: '#28a745',
                            backgroundColor: 'rgba(40, 167, 69, 0.1)',
                            tension: 0.4,
                            fill: true
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Count'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Date'
                            }
                        }
                    }
                }
            });
        });

        // Confirm Delete
        function confirmDelete(colorId) {
            Swal.fire({
                title: 'Delete Color?',
                text: "Are you sure you want to delete this color? This action cannot be undone.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    let form = $('#deleteForm');
                    form.attr('action', '{{ url('admin/colors') }}/' + colorId);

                    $.ajax({
                        url: form.attr('action'),
                        type: 'POST',
                        data: form.serialize(),
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: response.message,
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => {
                                    window.location.href = '{{ route('admin.colors.index') }}';
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Cannot Delete!',
                                    text: response.message,
                                    confirmButtonColor: '#d33'
                                });
                            }
                        }
                    });
                }
            });
        }
    </script>
@endpush

@push('styles')
    <style>
        .table-borderless td {
            padding: 8px 0;
        }

        .bg-primary-subtle {
            background-color: rgba(13, 110, 253, 0.1);
        }

        .bg-success-subtle {
            background-color: rgba(25, 135, 84, 0.1);
        }

        .bg-warning-subtle {
            background-color: rgba(255, 193, 7, 0.1);
        }

        .bg-info-subtle {
            background-color: rgba(13, 202, 240, 0.1);
        }

        .rounded {
            border-radius: 0.5rem;
        }

        .card {
            transition: box-shadow 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        /* Color preview circle animation */
        [style*="border-radius: 50%"]:hover {
            transform: scale(1.05);
            transition: transform 0.3s ease;
        }
    </style>
@endpush
