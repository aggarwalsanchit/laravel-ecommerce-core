{{-- resources/views/admin/fabrics/show.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Fabric Details')

@section('content')
    <div class="page-content">
        <div class="page-container">
            <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                <div class="flex-grow-1">
                    <h4 class="fs-18 text-uppercase fw-bold mb-0">Fabric Details: {{ $fabric->name }}</h4>
                </div>
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.fabrics.index') }}">Fabrics</a></li>
                        <li class="breadcrumb-item active">Fabric Details</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-4">
                    {{-- Fabric Information Card --}}
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Fabric Information</h5>
                        </div>
                        <div class="card-body">
                            @php
                                $imageExists = false;
                                $imageUrl = null;
                                if ($fabric->image && Storage::disk('public')->exists('fabrics/' . $fabric->image)) {
                                    $imageExists = true;
                                    $imageUrl = Storage::disk('public')->url('fabrics/' . $fabric->image);
                                    $imageSize = Storage::disk('public')->size('fabrics/' . $fabric->image);
                                }
                            @endphp

                            @if ($imageExists)
                                <div class="text-center mb-4">
                                    <img src="{{ $imageUrl }}" alt="{{ $fabric->name }}" class="img-fluid rounded"
                                        style="max-height: 150px; object-fit: cover;">
                                    <div class="small text-muted mt-2">
                                        <i class="ti ti-database"></i>
                                        Size:
                                        @if ($imageSize >= 1048576)
                                            {{ round($imageSize / 1048576, 2) }} MB
                                        @elseif($imageSize >= 1024)
                                            {{ round($imageSize / 1024, 2) }} KB
                                        @else
                                            {{ $imageSize }} bytes
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <table class="table table-borderless">
                                <tr>
                                    <td width="120"><strong>ID:</strong></td>
                                    <td>#{{ $fabric->id }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Name:</strong></td>
                                    <td>
                                        <span class="fw-semibold">{{ $fabric->name }}</span>
                                        <br><small class="text-muted">{{ $fabric->slug }}</small>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Code:</strong></td>
                                    <td>
                                        <span class="badge bg-secondary-subtle text-secondary p-2">
                                            <i class="ti ti-barcode"></i> {{ $fabric->code }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        @if ($fabric->status)
                                            <span class="badge bg-success-subtle text-success">
                                                <i class="ti ti-circle-check"></i> Active
                                            </span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger">
                                                <i class="ti ti-circle-x"></i> Inactive
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Display Order:</strong></td>
                                    <td>{{ $fabric->order }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Created:</strong></td>
                                    <td>
                                        {{ $fabric->created_at->format('F d, Y H:i') }}<br>
                                        <small class="text-muted">{{ $fabric->created_at->diffForHumans() }}</small>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Last Updated:</strong></td>
                                    <td>{{ $fabric->updated_at->diffForHumans() }}</td>
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
                                        <h3 class="mb-0">{{ number_format($fabric->view_count) }}</h3>
                                        <small class="text-muted">Total Views</small>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="bg-success-subtle rounded p-3">
                                        <h3 class="mb-0">{{ number_format($fabric->product_count) }}</h3>
                                        <small class="text-muted">Products</small>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="bg-warning-subtle rounded p-3">
                                        <h3 class="mb-0">{{ number_format($fabric->order_count) }}</h3>
                                        <small class="text-muted">Orders</small>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="bg-info-subtle rounded p-3">
                                        <h3 class="mb-0">${{ number_format($fabric->total_revenue, 2) }}</h3>
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
                            @if ($fabric->meta_title || $fabric->meta_description)
                                <div class="mb-3">
                                    <label class="text-muted small">Meta Title</label>
                                    <p class="mb-0">{{ $fabric->meta_title ?: 'Not set' }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="text-muted small">Meta Description</label>
                                    <p class="mb-0">{{ $fabric->meta_description ?: 'Not set' }}</p>
                                </div>
                                <div class="alert alert-info mt-2">
                                    <i class="ti ti-eye me-1"></i>
                                    <strong>SEO Preview:</strong>
                                    <div class="mt-2">
                                        <div class="text-primary">{{ $fabric->meta_title ?: $fabric->name }}</div>
                                        <div class="text-muted small">{{ url('/fabric') }}/{{ $fabric->slug }}</div>
                                        <div class="text-muted small">
                                            {{ Str::limit($fabric->meta_description ?: $fabric->description ?: 'Fabric description will appear here...', 160) }}
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-3">
                                    <i class="ti ti-chart-line-off" style="font-size: 48px; opacity: 0.5;"></i>
                                    <p class="text-muted mt-2">No SEO settings configured.</p>
                                    <a href="{{ route('admin.fabrics.edit', $fabric->id) }}"
                                        class="btn btn-sm btn-primary">
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
                            @if ($fabric->description)
                                <div class="p-3 bg-light rounded">
                                    {!! nl2br(e($fabric->description)) !!}
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="ti ti-file-description" style="font-size: 48px; opacity: 0.5;"></i>
                                    <p class="text-muted mt-2">No description provided.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Products Using This Fabric --}}
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ti ti-package"></i> Products Using This Fabric
                                <span class="badge bg-primary ms-2">{{ $fabric->products()->count() }}</span>
                            </h5>
                        </div>
                        <div class="card-body">
                            @if ($fabric->products()->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Product</th>
                                                <th>Price</th>
                                                <th>Stock</th>
                                                <th>Orders</th>
                                                <th>Revenue</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($fabric->products()->take(10)->get() as $product)
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
                                @if ($fabric->products()->count() > 10)
                                    <div class="text-center mt-3">
                                        <a href="#" class="btn btn-sm btn-outline-primary">View All
                                            {{ $fabric->products()->count() }} Products</a>
                                    </div>
                                @endif
                            @else
                                <div class="text-center py-4">
                                    <i class="ti ti-package-off" style="font-size: 48px; opacity: 0.5;"></i>
                                    <p class="text-muted mt-2">No products currently use this fabric.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Fabric Care Instructions --}}
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ti ti-wash"></i> Fabric Care Instructions
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 text-center">
                                    <i class="ti ti-wash fs-1 text-primary"></i>
                                    <p class="mt-2">Machine Wash<br><small class="text-muted">Gentle cycle</small></p>
                                </div>
                                <div class="col-md-4 text-center">
                                    <i class="ti ti-iron fs-1 text-primary"></i>
                                    <p class="mt-2">Ironing<br><small class="text-muted">Low heat</small></p>
                                </div>
                                <div class="col-md-4 text-center">
                                    <i class="ti ti-dry fs-1 text-primary"></i>
                                    <p class="mt-2">Drying<br><small class="text-muted">Air dry</small></p>
                                </div>
                            </div>
                            <hr>
                            <div class="alert alert-info">
                                <i class="ti ti-info-circle me-1"></i>
                                <strong>Care Tips:</strong> For best results, wash inside out with similar colors. Do not
                                bleach.
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
                            <canvas id="fabricPerformanceChart" height="200"></canvas>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="card mt-3">
                        <div class="card-footer text-end">
                            <a href="{{ route('admin.fabrics.index') }}" class="btn btn-secondary">
                                <i class="ti ti-arrow-left me-1"></i> Back
                            </a>
                            @can('edit fabrics')
                                <a href="{{ route('admin.fabrics.edit', $fabric->id) }}" class="btn btn-primary">
                                    <i class="ti ti-edit me-1"></i> Edit Fabric
                                </a>
                            @endcan
                            @can('delete fabrics')
                                @if ($fabric->product_count == 0)
                                    <button type="button" class="btn btn-danger"
                                        onclick="confirmDelete({{ $fabric->id }})">
                                        <i class="ti ti-trash me-1"></i> Delete Fabric
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
            const ctx = document.getElementById('fabricPerformanceChart').getContext('2d');

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
                            borderColor: '#0d6efd',
                            backgroundColor: 'rgba(13, 110, 253, 0.1)',
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
        function confirmDelete(fabricId) {
            Swal.fire({
                title: 'Delete Fabric?',
                text: "Are you sure you want to delete this fabric? This action cannot be undone.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    let form = $('#deleteForm');
                    form.attr('action', '{{ url('admin/fabrics') }}/' + fabricId);

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
                                    window.location.href =
                                    '{{ route('admin.fabrics.index') }}';
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
    </style>
@endpush
