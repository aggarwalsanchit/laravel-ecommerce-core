{{-- resources/views/admin/seasons/show.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Season Details')

@section('content')
    <div class="page-content">
        <div class="page-container">
            <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                <div class="flex-grow-1">
                    <h4 class="fs-18 text-uppercase fw-bold mb-0">Season Details: {{ $season->name }}</h4>
                </div>
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.seasons.index') }}">Seasons</a></li>
                        <li class="breadcrumb-item active">Season Details</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-4">
                    {{-- Season Information Card --}}
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Season Information</h5>
                        </div>
                        <div class="card-body">
                            @php
                                $imageExists = false;
                                $imageUrl = null;
                                if ($season->image && Storage::disk('public')->exists('seasons/' . $season->image)) {
                                    $imageExists = true;
                                    $imageUrl = Storage::disk('public')->url('seasons/' . $season->image);
                                    $imageSize = Storage::disk('public')->size('seasons/' . $season->image);
                                }
                            @endphp

                            @if ($imageExists)
                                <div class="text-center mb-4">
                                    <img src="{{ $imageUrl }}" alt="{{ $season->name }}" class="img-fluid rounded"
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

                            @if ($season->icon)
                                <div class="text-center mb-3">
                                    <i class="ti ti-{{ $season->icon }} fs-1 text-primary"></i>
                                    <div class="small text-muted">Icon: {{ $season->icon }}</div>
                                </div>
                            @endif

                            <table class="table table-borderless">
                                32
                                <td width="120"><strong>ID:</strong>64
                                <td>#{{ $season->id }}64
                                    </tr>
                                    32
                                <td><strong>Name:</strong>64
                                <td>
                                    <span class="fw-semibold">{{ $season->name }}</span>
                                    <br><small class="text-muted">{{ $season->slug }}</small>
                                    64
                                    </tr>
                                    32
                                <td><strong>Code:</strong>64
                                <td>
                                    <span class="badge bg-secondary-subtle text-secondary p-2">
                                        <i class="ti ti-barcode"></i> {{ $season->code }}
                                    </span>
                                    64
                                    </tr>
                                    32
                                <td><strong>Date Range:</strong>64
                                <td>
                                    @if ($season->start_date || $season->end_date)
                                        <div>
                                            <i class="ti ti-calendar-start me-1"></i>
                                            {{ $season->start_date ? $season->start_date->format('F d, Y') : 'No start date' }}
                                        </div>
                                        <div>
                                            <i class="ti ti-calendar-end me-1"></i>
                                            {{ $season->end_date ? $season->end_date->format('F d, Y') : 'No end date' }}
                                        </div>
                                    @else
                                        <span class="text-muted">No date range set</span>
                                    @endif
                                    64
                                    </tr>
                                    32
                                <td><strong>Status:</strong>64
                                <td>
                                    @php
                                        $isActive = $season->isActive();
                                        $statusBadge = $isActive ? 'success' : ($season->status ? 'warning' : 'danger');
                                        $statusText = $isActive
                                            ? 'Active'
                                            : ($season->status
                                                ? 'Scheduled'
                                                : 'Inactive');
                                    @endphp
                                    <span class="badge bg-{{ $statusBadge }}-subtle text-{{ $statusBadge }}">
                                        <i
                                            class="ti ti-{{ $isActive ? 'circle-check' : ($season->status ? 'clock' : 'circle-x') }}"></i>
                                        {{ $statusText }}
                                    </span>
                                    @if (!$isActive && $season->status && $season->start_date && $season->start_date > now())
                                        <br><small class="text-muted">Starts on
                                            {{ $season->start_date->format('M d, Y') }}</small>
                                    @endif
                                    @if (!$isActive && $season->status && $season->end_date && $season->end_date < now())
                                        <br><small class="text-muted">Ended on
                                            {{ $season->end_date->format('M d, Y') }}</small>
                                    @endif
                                    64
                                    </tr>
                                    32
                                <td><strong>Current Season:</strong>64
                                <td>
                                    @if ($season->is_current)
                                        <span class="badge bg-warning">
                                            <i class="ti ti-star"></i> Current Season
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            Not Current
                                        </span>
                                    @endif
                                    64
                                    </tr>
                                    32
                                <td><strong>Display Order:</strong>64
                                <td>{{ $season->order }}64
                                    </tr>
                                    32
                                <td><strong>Created:</strong>64
                                <td>
                                    {{ $season->created_at->format('F d, Y H:i') }}<br>
                                    <small class="text-muted">{{ $season->created_at->diffForHumans() }}</small>
                                    64
                                    </tr>
                                    32
                                <td><strong>Last Updated:</strong>64
                                <td>{{ $season->updated_at->diffForHumans() }}64
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
                                        <h3 class="mb-0">{{ number_format($season->view_count) }}</h3>
                                        <small class="text-muted">Total Views</small>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="bg-success-subtle rounded p-3">
                                        <h3 class="mb-0">{{ number_format($season->product_count) }}</h3>
                                        <small class="text-muted">Products</small>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="bg-warning-subtle rounded p-3">
                                        <h3 class="mb-0">{{ number_format($season->order_count) }}</h3>
                                        <small class="text-muted">Orders</small>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="bg-info-subtle rounded p-3">
                                        <h3 class="mb-0">${{ number_format($season->total_revenue, 2) }}</h3>
                                        <small class="text-muted">Revenue</small>
                                    </div>
                                </div>
                            </div>
                            @if ($season->avg_rating > 0)
                                <div class="text-center mt-2">
                                    <div class="d-flex align-items-center justify-content-center gap-2">
                                        <span class="text-warning fs-4">{{ number_format($season->avg_rating, 1) }}</span>
                                        <div>
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= round($season->avg_rating))
                                                    <i class="ti ti-star-filled text-warning"></i>
                                                @elseif($i <= ceil($season->avg_rating))
                                                    <i class="ti ti-star-half text-warning"></i>
                                                @else
                                                    <i class="ti ti-star text-muted"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <small class="text-muted">({{ $season->review_count }} reviews)</small>
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

                    {{-- Seasonal Info Card --}}
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ti ti-calendar"></i> Seasonal Information
                            </h5>
                        </div>
                        <div class="card-body">
                            @php
                                $months = [
                                    'December',
                                    'January',
                                    'February' => 'Winter',
                                    'March',
                                    'April',
                                    'May' => 'Spring',
                                    'June',
                                    'July',
                                    'August' => 'Summer',
                                    'September',
                                    'October',
                                    'November' => 'Fall',
                                ];
                                $currentMonth = now()->format('F');
                                $typicalSeason = '';
                                foreach ($months as $key => $seasonName) {
                                    if (is_array($seasonName)) {
                                        if (in_array($currentMonth, $seasonName)) {
                                            $typicalSeason = $key;
                                            break;
                                        }
                                    } elseif ($key == $currentMonth) {
                                        $typicalSeason = $seasonName;
                                        break;
                                    }
                                }
                            @endphp
                            <div class="text-center">
                                <i class="ti ti-{{ $season->icon ?? 'calendar' }} fs-2 text-primary mb-2 d-block"></i>
                                <p class="mb-1"><strong>Typical Months:</strong>
                                    @if ($season->name == 'Summer')
                                        June, July, August
                                    @elseif($season->name == 'Winter')
                                        December, January, February
                                    @elseif($season->name == 'Spring')
                                        March, April, May
                                    @elseif($season->name == 'Fall' || $season->name == 'Autumn')
                                        September, October, November
                                    @else
                                        Varies by region
                                    @endif
                                </p>
                                <p class="mb-0"><strong>Current Season:</strong> {{ $typicalSeason }}</p>
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
                            @if ($season->meta_title || $season->meta_description)
                                <div class="mb-3">
                                    <label class="text-muted small">Meta Title</label>
                                    <p class="mb-0">{{ $season->meta_title ?: 'Not set' }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="text-muted small">Meta Description</label>
                                    <p class="mb-0">{{ $season->meta_description ?: 'Not set' }}</p>
                                </div>
                                @if ($season->meta_keywords)
                                    <div class="mb-3">
                                        <label class="text-muted small">Meta Keywords</label>
                                        <p class="mb-0">{{ $season->meta_keywords }}</p>
                                    </div>
                                @endif
                                <div class="alert alert-info mt-2">
                                    <i class="ti ti-eye me-1"></i>
                                    <strong>SEO Preview:</strong>
                                    <div class="mt-2">
                                        <div class="text-primary">{{ $season->meta_title ?: $season->name }}</div>
                                        <div class="text-muted small">{{ url('/season') }}/{{ $season->slug }}</div>
                                        <div class="text-muted small">
                                            {{ Str::limit($season->meta_description ?: $season->description ?: 'Season description will appear here...', 160) }}
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-3">
                                    <i class="ti ti-chart-line-off" style="font-size: 48px; opacity: 0.5;"></i>
                                    <p class="text-muted mt-2">No SEO settings configured.</p>
                                    <a href="{{ route('admin.seasons.edit', $season->id) }}"
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
                            @if ($season->description)
                                <div class="p-3 bg-light rounded">
                                    {!! nl2br(e($season->description)) !!}
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="ti ti-file-description" style="font-size: 48px; opacity: 0.5;"></i>
                                    <p class="text-muted mt-2">No description provided.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Products in This Season --}}
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ti ti-package"></i> Products for {{ $season->name }} Season
                                <span class="badge bg-primary ms-2">{{ $season->products()->count() }}</span>
                            </h5>
                        </div>
                        <div class="card-body">
                            @if ($season->products()->count() > 0)
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
                                            @foreach ($season->products()->take(10)->get() as $product)
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
                                @if ($season->products()->count() > 10)
                                    <div class="text-center mt-3">
                                        <a href="#" class="btn btn-sm btn-outline-primary">View All
                                            {{ $season->products()->count() }} Products</a>
                                    </div>
                                @endif
                            @else
                                <div class="text-center py-4">
                                    <i class="ti ti-package-off" style="font-size: 48px; opacity: 0.5;"></i>
                                    <p class="text-muted mt-2">No products currently in this season.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Related Seasons --}}
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ti ti-link"></i> Related Seasons
                            </h5>
                        </div>
                        <div class="card-body">
                            @php
                                $relatedSeasons = App\Models\Season::where('status', true)
                                    ->where('id', '!=', $season->id)
                                    ->orderBy('order')
                                    ->take(6)
                                    ->get();
                            @endphp

                            @if ($relatedSeasons->count() > 0)
                                <div class="row">
                                    @foreach ($relatedSeasons as $related)
                                        <div class="col-md-4 mb-2">
                                            <a href="{{ route('admin.seasons.show', $related->id) }}"
                                                class="text-decoration-none">
                                                <div class="d-flex align-items-center gap-2 p-2 bg-light rounded">
                                                    @if ($related->image && Storage::disk('public')->exists('seasons/' . $related->image))
                                                        <img src="{{ Storage::disk('public')->url('seasons/' . $related->image) }}"
                                                            style="width: 30px; height: 30px; object-fit: cover; border-radius: 6px;">
                                                    @else
                                                        <div
                                                            style="width: 30px; height: 30px; background: #e9ecef; border-radius: 6px; display: flex; align-items: center; justify-content: center;">
                                                            <i class="ti ti-{{ $related->icon ?? 'calendar' }}"></i>
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
                                    <p class="text-muted">No related seasons found.</p>
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
                            <canvas id="seasonPerformanceChart" height="200"></canvas>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="card mt-3">
                        <div class="card-footer text-end">
                            <a href="{{ route('admin.seasons.index') }}" class="btn btn-secondary">
                                <i class="ti ti-arrow-left me-1"></i> Back
                            </a>
                            @can('edit seasons')
                                <a href="{{ route('admin.seasons.edit', $season->id) }}" class="btn btn-primary">
                                    <i class="ti ti-edit me-1"></i> Edit Season
                                </a>
                            @endcan
                            @if (!$season->is_current && $season->status)
                                <button type="button" class="btn btn-warning"
                                    onclick="setCurrentSeason({{ $season->id }})">
                                    <i class="ti ti-star me-1"></i> Set as Current Season
                                </button>
                            @endif
                            @can('delete seasons')
                                @if ($season->product_count == 0)
                                    <button type="button" class="btn btn-danger"
                                        onclick="confirmDelete({{ $season->id }})">
                                        <i class="ti ti-trash me-1"></i> Delete Season
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
            const ctx = document.getElementById('seasonPerformanceChart').getContext('2d');

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

        // Set as Current Season
        function setCurrentSeason(seasonId) {
            Swal.fire({
                title: 'Set as Current Season?',
                text: "This will set this season as the current active season for your store.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, set as current!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ url('admin/seasons') }}/' + seasonId + '/set-current',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Updated!',
                                    text: response.message,
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => {
                                    window.location.reload();
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Failed to set current season.',
                                confirmButtonColor: '#d33'
                            });
                        }
                    });
                }
            });
        }

        // Confirm Delete
        function confirmDelete(seasonId) {
            Swal.fire({
                title: 'Delete Season?',
                text: "Are you sure you want to delete this season? This action cannot be undone.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    let form = $('#deleteForm');
                    form.attr('action', '{{ url('admin/seasons') }}/' + seasonId);

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
                                    '{{ route('admin.seasons.index') }}';
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

        .current-badge {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                opacity: 1;
            }

            50% {
                opacity: 0.7;
            }

            100% {
                opacity: 1;
            }
        }
    </style>
@endpush
