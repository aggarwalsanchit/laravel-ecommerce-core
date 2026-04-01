{{-- resources/views/admin/brands/show.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Brand Details')

@section('content')
    <div class="page-content">
        <div class="page-container">
            <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                <div class="flex-grow-1">
                    <h4 class="fs-18 text-uppercase fw-bold mb-0">Brand Details: {{ $brand->name }}</h4>
                </div>
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.brands.index') }}">Brands</a></li>
                        <li class="breadcrumb-item active">Brand Details</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-4">
                    {{-- Brand Information Card --}}
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Brand Information</h5>
                        </div>
                        <div class="card-body">
                            @php
                                $logoExists = false;
                                $logoUrl = null;
                                if ($brand->logo && Storage::disk('public')->exists('brands/logos/' . $brand->logo)) {
                                    $logoExists = true;
                                    $logoUrl = Storage::disk('public')->url('brands/logos/' . $brand->logo);
                                    $logoSise = Storage::disk('public')->size('brands/logos/' . $brand->logo);
                                }

                                $bannerExists = false;
                                $bannerUrl = null;
                                if (
                                    $brand->banner &&
                                    Storage::disk('public')->exists('brands/banners/' . $brand->banner)
                                ) {
                                    $bannerExists = true;
                                    $bannerUrl = Storage::disk('public')->url('brands/banners/' . $brand->banner);
                                }
                            @endphp

                            @if ($logoExists)
                                <div class="text-center mb-4">
                                    <img src="{{ $logoUrl }}" alt="{{ $brand->name }}" class="img-fluid rounded"
                                        style="max-height: 150px; object-fit: contain;">
                                    <div class="small text-muted mt-2">
                                        <i class="ti ti-database"></i>
                                        Size:
                                        @if ($logoSise >= 1048576)
                                            {{ round($logoSise / 1048576, 2) }} MB
                                        @elseif($logoSise >= 1024)
                                            {{ round($logoSise / 1024, 2) }} KB
                                        @else
                                            {{ $logoSise }} bytes
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <table class="table table-borderless">
                                32
                                <td width="120"><strong>ID:</strong>64
                                <td>#{{ $brand->id }}64
                                    </tr>
                                    32
                                <td><strong>Name:</strong>64
                                <td>
                                    <span class="fw-semibold">{{ $brand->name }}</span>
                                    <br><small class="text-muted">{{ $brand->slug }}</small>
                                    64
                                    </tr>
                                    32
                                <td><strong>Code:</strong>64
                                <td>
                                    <span class="badge bg-secondary-subtle text-secondary p-2">
                                        <i class="ti ti-barcode"></i> {{ $brand->code }}
                                    </span>
                                    64
                                    </tr>
                                    32
                                <td><strong>Website:</strong>64
                                <td>
                                    @if ($brand->website)
                                        <a href="{{ $brand->website }}" target="_blank" class="text-primary">
                                            <i class="ti ti-external-link"></i> {{ $brand->website }}
                                        </a>
                                    @else
                                        <span class="text-muted">Not provided</span>
                                    @endif
                                    64
                                    </tr>
                                    32
                                <td><strong>Email:</strong>64
                                <td>
                                    @if ($brand->email)
                                        <a href="mailto:{{ $brand->email }}" class="text-primary">
                                            <i class="ti ti-mail"></i> {{ $brand->email }}
                                        </a>
                                    @else
                                        <span class="text-muted">Not provided</span>
                                    @endif
                                    64
                                    </tr>
                                    32
                                <td><strong>Phone:</strong>64
                                <td>
                                    @if ($brand->phone)
                                        <a href="tel:{{ $brand->phone }}" class="text-primary">
                                            <i class="ti ti-phone"></i> {{ $brand->phone }}
                                        </a>
                                    @else
                                        <span class="text-muted">Not provided</span>
                                    @endif
                                    64
                                    </tr>
                                    @if ($brand->address)
                                        32
                                <td><strong>Address:</strong>64
                                <td>{{ $brand->address }}64
                                    </tr>
                                    @endif
                                    32
                                <td><strong>Status:</strong>64
                                <td>
                                    @if ($brand->status)
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
                                <td><strong>Featured:</strong>64
                                <td>
                                    @if ($brand->is_featured)
                                        <span class="badge bg-warning-subtle text-warning">
                                            <i class="ti ti-star"></i> Featured
                                        </span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary">
                                            Not Featured
                                        </span>
                                    @endif
                                    64
                                    </tr>
                                    32
                                <td><strong>Display Order:</strong>64
                                <td>{{ $brand->order }}64
                                    </tr>
                                    32
                                <td><strong>Created:</strong>64
                                <td>
                                    {{ $brand->created_at->format('F d, Y H:i') }}<br>
                                    <small class="text-muted">{{ $brand->created_at->diffForHumans() }}</small>
                                    64
                                    </tr>
                                    32
                                <td><strong>Last Updated:</strong>64
                                <td>{{ $brand->updated_at->diffForHumans() }}64
                                    </tr>
                            </table>
                        </div>
                    </div>

                    {{-- Banner Image Card --}}
                    @if ($bannerExists)
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Brand Banner</h5>
                            </div>
                            <div class="card-body text-center">
                                <img src="{{ $bannerUrl }}" alt="{{ $brand->name }} Banner" class="img-fluid rounded"
                                    style="max-height: 150px; width: 100%; object-fit: cover;">
                            </div>
                        </div>
                    @endif

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
                                        <h3 class="mb-0">{{ number_format($brand->view_count) }}</h3>
                                        <small class="text-muted">Total Views</small>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="bg-success-subtle rounded p-3">
                                        <h3 class="mb-0">{{ number_format($brand->product_count) }}</h3>
                                        <small class="text-muted">Products</small>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="bg-warning-subtle rounded p-3">
                                        <h3 class="mb-0">{{ number_format($brand->order_count) }}</h3>
                                        <small class="text-muted">Orders</small>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="bg-info-subtle rounded p-3">
                                        <h3 class="mb-0">${{ number_format($brand->total_revenue, 2) }}</h3>
                                        <small class="text-muted">Revenue</small>
                                    </div>
                                </div>
                            </div>
                            @if ($brand->avg_rating > 0)
                                <div class="text-center mt-2">
                                    <div class="d-flex align-items-center justify-content-center gap-2">
                                        <span class="text-warning fs-4">{{ number_format($brand->avg_rating, 1) }}</span>
                                        <div>
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= round($brand->avg_rating))
                                                    <i class="ti ti-star-filled text-warning"></i>
                                                @elseif($i <= ceil($brand->avg_rating))
                                                    <i class="ti ti-star-half text-warning"></i>
                                                @else
                                                    <i class="ti ti-star text-muted"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <small class="text-muted">({{ $brand->review_count }} reviews)</small>
                                    </div>
                                </div>
                            @endif
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
                            @if ($brand->meta_title || $brand->meta_description)
                                <div class="mb-3">
                                    <label class="text-muted small">Meta Title</label>
                                    <p class="mb-0">{{ $brand->meta_title ?: 'Not set' }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="text-muted small">Meta Description</label>
                                    <p class="mb-0">{{ $brand->meta_description ?: 'Not set' }}</p>
                                </div>
                                @if ($brand->meta_keywords)
                                    <div class="mb-3">
                                        <label class="text-muted small">Meta Keywords</label>
                                        <p class="mb-0">{{ $brand->meta_keywords }}</p>
                                    </div>
                                @endif
                                <div class="alert alert-info mt-2">
                                    <i class="ti ti-eye me-1"></i>
                                    <strong>SEO Preview:</strong>
                                    <div class="mt-2">
                                        <div class="text-primary">{{ $brand->meta_title ?: $brand->name }}</div>
                                        <div class="text-muted small">{{ url('/brand') }}/{{ $brand->slug }}</div>
                                        <div class="text-muted small">
                                            {{ Str::limit($brand->meta_description ?: $brand->description ?: 'Brand description will appear here...', 160) }}
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-3">
                                    <i class="ti ti-chart-line-off" style="font-size: 48px; opacity: 0.5;"></i>
                                    <p class="text-muted mt-2">No SEO settings configured.</p>
                                    <a href="{{ route('admin.brands.edit', $brand->id) }}"
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
                            @if ($brand->description)
                                <div class="p-3 bg-light rounded">
                                    {!! nl2br(e($brand->description)) !!}
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="ti ti-file-description" style="font-size: 48px; opacity: 0.5;"></i>
                                    <p class="text-muted mt-2">No description provided.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Products by This Brand --}}
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ti ti-package"></i> Products by {{ $brand->name }}
                                <span class="badge bg-primary ms-2">{{ $brand->products()->count() }}</span>
                            </h5>
                        </div>
                        <div class="card-body">
                            @if ($brand->products()->count() > 0)
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
                                            @foreach ($brand->products()->take(10)->get() as $product)
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
                                @if ($brand->products()->count() > 10)
                                    <div class="text-center mt-3">
                                        <a href="#" class="btn btn-sm btn-outline-primary">View All
                                            {{ $brand->products()->count() }} Products</a>
                                    </div>
                                @endif
                            @else
                                <div class="text-center py-4">
                                    <i class="ti ti-package-off" style="font-size: 48px; opacity: 0.5;"></i>
                                    <p class="text-muted mt-2">No products currently from this brand.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Related Brands --}}
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ti ti-link"></i> Related Brands
                            </h5>
                        </div>
                        <div class="card-body">
                            @php
                                $relatedBrands = App\Models\Brand::where('status', true)
                                    ->where('id', '!=', $brand->id)
                                    ->orderBy('order')
                                    ->take(6)
                                    ->get();
                            @endphp

                            @if ($relatedBrands->count() > 0)
                                <div class="row">
                                    @foreach ($relatedBrands as $related)
                                        <div class="col-md-4 mb-2">
                                            <a href="{{ route('admin.brands.show', $related->id) }}"
                                                class="text-decoration-none">
                                                <div class="d-flex align-items-center gap-2 p-2 bg-light rounded">
                                                    @if ($related->logo && Storage::disk('public')->exists('brands/logos/' . $related->logo))
                                                        <img src="{{ Storage::disk('public')->url('brands/logos/' . $related->logo) }}"
                                                            style="width: 30px; height: 30px; object-fit: cover; border-radius: 6px;">
                                                    @else
                                                        <div
                                                            style="width: 30px; height: 30px; background: #e9ecef; border-radius: 6px; display: flex; align-items: center; justify-content: center;">
                                                            <i class="ti ti-brand"></i>
                                                        </div>
                                                    @endif
                                                    <span class="small">{{ $related->name }}</span>
                                                </div>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-3">
                                    <p class="text-muted">No related brands found.</p>
                                </div>
                            @endif
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
                            <canvas id="brandPerformanceChart" height="200"></canvas>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="card mt-3">
                        <div class="card-footer text-end">
                            <a href="{{ route('admin.brands.index') }}" class="btn btn-secondary">
                                <i class="ti ti-arrow-left me-1"></i> Back
                            </a>
                            @can('edit brands')
                                <a href="{{ route('admin.brands.edit', $brand->id) }}" class="btn btn-primary">
                                    <i class="ti ti-edit me-1"></i> Edit Brand
                                </a>
                            @endcan
                            @can('delete brands')
                                @if ($brand->product_count == 0)
                                    <button type="button" class="btn btn-danger"
                                        onclick="confirmDelete({{ $brand->id }})">
                                        <i class="ti ti-trash me-1"></i> Delete Brand
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
            const ctx = document.getElementById('brandPerformanceChart').getContext('2d');

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
        function confirmDelete(brandId) {
            Swal.fire({
                title: 'Delete Brand?',
                text: "Are you sure you want to delete this brand? This action cannot be undone.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    let form = $('#deleteForm');
                    form.attr('action', '{{ url('admin/brands') }}/' + brandId);

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
                                    window.location.href = '{{ route('admin.brands.index') }}';
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

        /* Star rating */
        .ti-star-filled {
            color: #ffc107;
        }

        .ti-star-half {
            color: #ffc107;
        }
    </style>
@endpush
