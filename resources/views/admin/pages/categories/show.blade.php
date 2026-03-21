{{-- resources/views/admin/categories/show.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Category Details')

@section('content')
    <div class="page-content">
        <div class="page-container">
            <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                <div class="flex-grow-1">
                    <h4 class="fs-18 text-uppercase fw-bold mb-0">Category Details</h4>
                </div>
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">Categories</a></li>
                        <li class="breadcrumb-item active">{{ $category->name }}</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-4">
                    {{-- Category Information Card --}}
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Category Information</h5>
                        </div>
                        <div class="card-body">
                            @if ($category->image)
                                <div class="text-center mb-3">
                                    <img src="{{ asset('storage/categories/' . $category->image) }}"
                                        alt="{{ $category->name }}" class="img-fluid rounded" style="max-height: 200px;">
                                </div>
                            @endif

                            <table class="table table-borderless">
                                32
                                <td width="120"><strong>ID:</strong>64
                                <td>#{{ $category->id }}64
                                    </tr>
                                    32
                                <td><strong>Name:</strong>64
                                <td>{{ $category->name }}64
                                    </tr>
                                    <tr>
                                        <td><strong>Slug:</strong></td>
                                        <td><code>{{ $category->slug }}</code></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Path:</strong></td>
                                        <td>{{ $category->path }}</td>
                                    </tr>
                                    @if ($category->parent)
                                        <tr>
                                            <td><strong>Parent:</strong></td>
                                            <td>
                                                <a href="{{ route('admin.categories.show', $category->parent->id) }}">
                                                    {{ $category->parent->name }}
                                                </a>
                                            </td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td><strong>Status:</strong></td>
                                        <td>
                                            @if ($category->status)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Inactive</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Menu:</strong></td>
                                        <td>
                                            @if ($category->show_in_menu)
                                                <span class="badge bg-info">Visible in Menu</span>
                                            @else
                                                <span class="badge bg-secondary">Hidden from Menu</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Created:</strong></td>
                                        <td>{{ $category->created_at->format('F d, Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Updated:</strong></td>
                                        <td>{{ $category->updated_at->diffForHumans() }}</td>
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
                                        <h3 class="mb-0">{{ number_format($category->view_count) }}</h3>
                                        <small class="text-muted">Total Views</small>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="bg-success-subtle rounded p-3">
                                        <h3 class="mb-0">{{ number_format($category->product_count) }}</h3>
                                        <small class="text-muted">Products</small>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="bg-warning-subtle rounded p-3">
                                        <h3 class="mb-0">{{ number_format($category->order_count) }}</h3>
                                        <small class="text-muted">Orders</small>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="bg-info-subtle rounded p-3">
                                        <h3 class="mb-0">${{ number_format($category->total_revenue, 2) }}</h3>
                                        <small class="text-muted">Revenue</small>
                                    </div>
                                </div>
                            </div>

                            @if ($category->last_viewed_at)
                                <hr>
                                <div class="text-center">
                                    <small class="text-muted">
                                        Last viewed: {{ $category->last_viewed_at->diffForHumans() }}
                                    </small>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- SEO Score Card --}}
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ti ti-chart-line"></i> SEO Score
                            </h5>
                        </div>
                        <div class="card-body text-center">
                            @php $seoStatus = $category->seo_status; @endphp
                            <div class="display-4 mb-2 text-{{ $seoStatus['badge'] }}">
                                {{ $category->seo_score }}%
                            </div>
                            <div class="progress mb-3" style="height: 10px;">
                                <div class="progress-bar bg-{{ $seoStatus['badge'] }}"
                                    style="width: {{ $category->seo_score }}%;"></div>
                            </div>
                            <span class="badge bg-{{ $seoStatus['badge'] }} mb-2">{{ $seoStatus['text'] }}</span>

                            <div class="mt-3">
                                <div class="d-flex justify-content-between small mb-1">
                                    <span>Meta Title</span>
                                    <span>{{ $category->meta_title ? '✓' : '✗' }}</span>
                                </div>
                                <div class="d-flex justify-content-between small mb-1">
                                    <span>Meta Description</span>
                                    <span>{{ $category->meta_description ? '✓' : '✗' }}</span>
                                </div>
                                <div class="d-flex justify-content-between small mb-1">
                                    <span>Focus Keyword</span>
                                    <span>{{ $category->focus_keyword ? '✓' : '✗' }}</span>
                                </div>
                                <div class="d-flex justify-content-between small">
                                    <span>OG Image</span>
                                    <span>{{ $category->og_image ? '✓' : '✗' }}</span>
                                </div>
                            </div>
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
                            @if ($category->short_description)
                                <div class="alert alert-info">
                                    <strong>Short Description:</strong><br>
                                    {{ $category->short_description }}
                                </div>
                            @endif
                            <div>
                                {!! nl2br(e($category->description)) !!}
                            </div>
                        </div>
                    </div>

                    {{-- Subcategories Card --}}
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ti ti-folder"></i> Subcategories ({{ $category->children->count() }})
                            </h5>
                        </div>
                        <div class="card-body">
                            @if ($category->children->count() > 0)
                                <div class="row">
                                    @foreach ($category->children as $child)
                                        <div class="col-md-6 mb-2">
                                            <div
                                                class="d-flex justify-content-between align-items-center p-2 bg-light rounded">
                                                <div>
                                                    <i class="ti ti-folder text-primary me-2"></i>
                                                    <a href="{{ route('admin.categories.show', $child->id) }}">
                                                        {{ $child->name }}
                                                    </a>
                                                    <small class="text-muted d-block">
                                                        {{ $child->product_count }} products
                                                    </small>
                                                </div>
                                                <span class="badge bg-info">{{ number_format($child->view_count) }}
                                                    views</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="ti ti-folder-off" style="font-size: 48px; opacity: 0.5;"></i>
                                    <p class="text-muted mt-2">No subcategories found.</p>
                                    <a href="{{ route('admin.categories.create') }}?parent={{ $category->id }}"
                                        class="btn btn-sm btn-primary">
                                        Add Subcategory
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Top Products Card --}}
                    @if (isset($topProducts) && $topProducts->count() > 0)
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="ti ti-package"></i> Top Products
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>Price</th>
                                                <th>Orders</th>
                                                <th>Revenue</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($topProducts as $product)
                                                <tr>
                                                    <td>{{ $product->name }}</td>
                                                    <td>${{ number_format($product->price, 2) }}</td>
                                                    <td>{{ number_format($product->order_count) }}</td>
                                                    <td>${{ number_format($product->total_sold_value, 2) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Action Buttons --}}
                    <div class="card">
                        <div class="card-footer text-end">
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                                <i class="ti ti-arrow-left me-1"></i> Back
                            </a>
                            @can('edit categories')
                                <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-primary">
                                    <i class="ti ti-edit me-1"></i> Edit Category
                                </a>
                            @endcan
                            @can('create categories')
                                <a href="{{ route('admin.categories.create') }}?parent={{ $category->id }}"
                                    class="btn btn-success">
                                    <i class="ti ti-plus me-1"></i> Add Subcategory
                                </a>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
