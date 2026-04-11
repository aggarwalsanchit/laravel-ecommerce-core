{{-- resources/views/admin/pages/categories/show.blade.php --}}
@extends('management.layouts.app')

@section('title', 'Category Details - ' . $category->name)

@section('content')
    <div class="page-content">
        <div class="page-container">
            <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                <div class="flex-grow-1">
                    <h4 class="fs-18 text-uppercase fw-bold mb-0">Category Details: {{ $category->name }}</h4>
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
                <!-- Left Column -->
                <div class="col-lg-4">
                    {{-- Images Card --}}
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ti ti-photo"></i> Category Images
                            </h5>
                        </div>
                        <div class="card-body">
                            {{-- Main Image --}}
                            @if ($category->image)
                                <div class="mb-4">
                                    <label class="fw-semibold text-muted small mb-2">Main Image</label>
                                    <div class="text-center">
                                        <img src="{{ asset('storage/categories/' . $category->image) }}"
                                            alt="{{ $category->image_alt ?? $category->name }}"
                                            class="img-fluid rounded border" style="max-height: 200px; width: auto;">
                                    </div>
                                    @if ($category->image_alt)
                                        <div class="mt-2 small text-muted">
                                            <strong>Alt Text:</strong> {{ $category->image_alt }}
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="text-center mb-4">
                                    <div class="bg-light rounded p-4">
                                        <i class="ti ti-photo-off" style="font-size: 48px; opacity: 0.5;"></i>
                                        <p class="text-muted mt-2 mb-0">No main image uploaded</p>
                                    </div>
                                </div>
                            @endif

                            {{-- Banner Image --}}
                            @if ($category->banner_image)
                                <div class="mb-4">
                                    <label class="fw-semibold text-muted small mb-2">Banner Image</label>
                                    <div class="text-center">
                                        <img src="{{ asset('storage/categories/banners/' . $category->banner_image) }}"
                                            alt="{{ $category->banner_alt ?? $category->name }}"
                                            class="img-fluid rounded border"
                                            style="width: 100%; max-height: 150px; object-fit: cover;">
                                    </div>
                                    @if ($category->banner_alt)
                                        <div class="mt-2 small text-muted">
                                            <strong>Alt Text:</strong> {{ $category->banner_alt }}
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="text-center mb-4">
                                    <div class="bg-light rounded p-3">
                                        <i class="ti ti-banner-off" style="font-size: 32px; opacity: 0.5;"></i>
                                        <p class="text-muted mt-1 mb-0 small">No banner image uploaded</p>
                                    </div>
                                </div>
                            @endif

                            {{-- Thumbnail Image --}}
                            @if ($category->thumbnail_image)
                                <div class="mb-3">
                                    <label class="fw-semibold text-muted small mb-2">Thumbnail Image</label>
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="{{ asset('storage/categories/thumbnails/' . $category->thumbnail_image) }}"
                                            alt="{{ $category->thumbnail_alt ?? $category->name }}" class="rounded border"
                                            style="width: 80px; height: 80px; object-fit: cover;">
                                        @if ($category->thumbnail_alt)
                                            <div class="small text-muted">
                                                <strong>Alt Text:</strong> {{ $category->thumbnail_alt }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <div class="text-center mb-3">
                                    <div class="bg-light rounded p-2">
                                        <i class="ti ti-photo-off" style="font-size: 24px; opacity: 0.5;"></i>
                                        <p class="text-muted mt-1 mb-0 small">No thumbnail image uploaded</p>
                                    </div>
                                </div>
                            @endif

                            {{-- Icon --}}
                            @if ($category->icon)
                                <div class="mt-3">
                                    <label class="fw-semibold text-muted small mb-2">Icon</label>
                                    <div>
                                        <i class="{{ $category->icon }}" style="font-size: 32px;"></i>
                                        <code class="ms-2 small">{{ $category->icon }}</code>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Category Information Card --}}
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ti ti-info-circle"></i> Basic Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="120"><strong>ID:</strong></td>
                                    <td><span class="badge bg-secondary">#{{ $category->id }}</span></td>
                                </tr>
                                <tr>
                                    <td><strong>Name:</strong></td>
                                    <td>{{ $category->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Slug:</strong></td>
                                    <td><code>{{ $category->slug }}</code></td>
                                </tr>
                                <tr>
                                    <td><strong>Order:</strong></td>
                                    <td>{{ $category->order }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Path:</strong></td>
                                    <td>
                                        <span class="text-muted small" title="{{ $category->path }}">
                                            <i class="ti ti-link me-1"></i>
                                            {{ $category->path ?? 'N/A' }}
                                        </span>
                                    </td>
                                </tr>
                                @if ($category->parent)
                                    <tr>
                                        <td><strong>Parent:</strong></td>
                                        <td>
                                            <a href="{{ route('admin.categories.show', $category->parent->id) }}"
                                                class="text-decoration-none">
                                                <i class="ti ti-arrow-narrow-up text-success me-1"></i>
                                                {{ $category->parent->name }}
                                            </a>
                                            <br>
                                            <small class="text-muted">Level: {{ $category->parent->level }}</small>
                                        </td>
                                    </tr>
                                @else
                                    <tr>
                                        <td><strong>Level:</strong></td>
                                        <td><span class="badge bg-primary">Main Category (Level
                                                {{ $category->level }})</span></td>
                                    </tr>
                                @endif
                                <tr>
                                    <td><strong>Created:</strong></td>
                                    <td>{{ $category->created_at->format('F d, Y H:i:s') }}<br>
                                        <small class="text-muted">{{ $category->created_at->diffForHumans() }}</small>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Last Updated:</strong></td>
                                    <td>{{ $category->updated_at->format('F d, Y H:i:s') }}<br>
                                        <small class="text-muted">{{ $category->updated_at->diffForHumans() }}</small>
                                    </td>
                                </tr>
                                @if ($category->last_updated_at)
                                    <tr>
                                        <td><strong>Last Sync:</strong></td>
                                        <td>{{ $category->last_updated_at->diffForHumans() }}</td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                    </div>

                    {{-- Status Card --}}
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ti ti-flag"></i> Status & Visibility
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="fw-semibold text-muted small">Status</label>
                                <div>
                                    @if ($category->status)
                                        <span class="badge bg-success"><i class="ti ti-circle-check"></i> Active</span>
                                    @else
                                        <span class="badge bg-danger"><i class="ti ti-circle-x"></i> Inactive</span>
                                    @endif
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="fw-semibold text-muted small">Menu Visibility</label>
                                <div>
                                    @if ($category->show_in_menu)
                                        <span class="badge bg-info"><i class="ti ti-eye"></i> Visible in Menu</span>
                                    @else
                                        <span class="badge bg-secondary"><i class="ti ti-eye-off"></i> Hidden from
                                            Menu</span>
                                    @endif
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="fw-semibold text-muted small">Flags</label>
                                <div class="d-flex gap-2 flex-wrap">
                                    @if ($category->is_featured)
                                        <span class="badge bg-warning"><i class="ti ti-star"></i> Featured</span>
                                    @endif
                                    @if ($category->is_popular)
                                        <span class="badge bg-danger"><i class="ti ti-fire"></i> Popular</span>
                                    @endif
                                    @if ($category->is_trending)
                                        <span class="badge bg-info"><i class="ti ti-trending-up"></i> Trending</span>
                                    @endif
                                    @if (!$category->is_featured && !$category->is_popular && !$category->is_trending)
                                        <span class="text-muted small">No special flags assigned</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Approval Status Card --}}
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ti ti-check-circle"></i> Approval Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="fw-semibold text-muted small">Approval Status</label>
                                <div>
                                    @php
                                        $approvalBadge =
                                            [
                                                'approved' =>
                                                    '<span class="badge bg-success"><i class="ti ti-check"></i> Approved</span>',
                                                'pending' =>
                                                    '<span class="badge bg-warning"><i class="ti ti-clock"></i> Pending</span>',
                                                'rejected' =>
                                                    '<span class="badge bg-danger"><i class="ti ti-x"></i> Rejected</span>',
                                            ][$category->approval_status] ??
                                            '<span class="badge bg-secondary">Unknown</span>';
                                    @endphp
                                    {!! $approvalBadge !!}
                                </div>
                            </div>

                            @if ($category->requested_by)
                                <div class="mb-2">
                                    <label class="fw-semibold text-muted small">Requested By</label>
                                    <div class="text-muted small">
                                        Vendor ID: #{{ $category->requested_by }}
                                        @if ($category->requested_at)
                                            <br>on {{ $category->requested_at->format('F d, Y H:i') }}
                                        @endif
                                    </div>
                                </div>
                            @endif

                            @if ($category->request_notes)
                                <div class="mb-2">
                                    <label class="fw-semibold text-muted small">Request Notes</label>
                                    <div class="bg-light p-2 rounded small">
                                        {{ $category->request_notes }}
                                    </div>
                                </div>
                            @endif

                            @if ($category->rejection_reason)
                                <div class="mb-2">
                                    <label class="fw-semibold text-muted small text-danger">Rejection Reason</label>
                                    <div class="bg-danger-subtle p-2 rounded small text-danger">
                                        <i class="ti ti-alert-circle"></i> {{ $category->rejection_reason }}
                                    </div>
                                </div>
                            @endif

                            @if ($category->approved_by)
                                <div class="mb-2">
                                    <label class="fw-semibold text-muted small">Approved/Rejected By</label>
                                    <div class="text-muted small">
                                        Admin ID: #{{ $category->approved_by }}
                                        @if ($category->approved_at)
                                            <br>on {{ $category->approved_at->format('F d, Y H:i') }}
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-lg-8">
                    {{-- Analytics Card --}}
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ti ti-chart-bar"></i> Analytics Dashboard
                            </h5>
                        </div>
                        <div class="card-body">
                            @php
                                $totalViews = $category->analytics->sum('view_count');
                                $totalProducts = $category->analytics->sum('product_count');
                                $totalOrders = $category->analytics->sum('order_count');
                                $totalRevenue = $category->analytics->sum('total_revenue');
                                $avgPrice = $category->analytics->avg('avg_price');
                                $todayAnalytics = $category->analytics->where('date', today()->toDateString())->first();
                            @endphp

                            <div class="row mb-4">
                                <div class="col-md-3 col-6 mb-3">
                                    <div class="bg-primary-subtle rounded p-3 text-center">
                                        <i class="ti ti-eye fs-2 text-primary"></i>
                                        <h3 class="mb-0 mt-2">{{ number_format($totalViews) }}</h3>
                                        <small class="text-muted">Total Views</small>
                                        @if ($todayAnalytics)
                                            <small
                                                class="text-success d-block">+{{ number_format($todayAnalytics->view_count) }}
                                                today</small>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-3 col-6 mb-3">
                                    <div class="bg-success-subtle rounded p-3 text-center">
                                        <i class="ti ti-package fs-2 text-success"></i>
                                        <h3 class="mb-0 mt-2">{{ number_format($totalProducts) }}</h3>
                                        <small class="text-muted">Total Products</small>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6 mb-3">
                                    <div class="bg-warning-subtle rounded p-3 text-center">
                                        <i class="ti ti-shopping-cart fs-2 text-warning"></i>
                                        <h3 class="mb-0 mt-2">{{ number_format($totalOrders) }}</h3>
                                        <small class="text-muted">Total Orders</small>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6 mb-3">
                                    <div class="bg-info-subtle rounded p-3 text-center">
                                        <i class="ti ti-currency-dollar fs-2 text-info"></i>
                                        <h3 class="mb-0 mt-2">${{ number_format($totalRevenue, 2) }}</h3>
                                        <small class="text-muted">Total Revenue</small>
                                    </div>
                                </div>
                            </div>

                            @if ($avgPrice > 0)
                                <hr>
                                <div class="text-center">
                                    <span class="text-muted">Average Product Price:</span>
                                    <strong class="text-success fs-5">${{ number_format($avgPrice, 2) }}</strong>
                                </div>
                            @endif

                            @if ($category->last_viewed_at)
                                <hr>
                                <div class="text-center">
                                    <small class="text-muted">
                                        <i class="ti ti-eye"></i> Last viewed:
                                        {{ $category->last_viewed_at->diffForHumans() }}
                                    </small>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Description Card --}}
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ti ti-align-left"></i> Description
                            </h5>
                        </div>
                        <div class="card-body">
                            @if ($category->short_description)
                                <div class="alert alert-info mb-3">
                                    <strong class="d-block mb-1">Short Description:</strong>
                                    {{ $category->short_description }}
                                </div>
                            @endif

                            @if ($category->description)
                                <div class="bg-light p-3 rounded">
                                    {!! nl2br(e($category->description)) !!}
                                </div>
                            @else
                                <p class="text-muted text-center py-3 mb-0">No description provided.</p>
                            @endif
                        </div>
                    </div>

                    {{-- SEO Information Card --}}
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ti ti-meta-tag"></i> SEO & Meta Information
                            </h5>
                        </div>
                        <div class="card-body">
                            @php
                                $seoScore = 0;
                                if (
                                    $category->meta_title &&
                                    strlen($category->meta_title) >= 30 &&
                                    strlen($category->meta_title) <= 60
                                ) {
                                    $seoScore += 25;
                                }
                                if (
                                    $category->meta_description &&
                                    strlen($category->meta_description) >= 120 &&
                                    strlen($category->meta_description) <= 160
                                ) {
                                    $seoScore += 25;
                                }
                                if ($category->focus_keyword) {
                                    $seoScore += 25;
                                }
                                if ($category->og_image) {
                                    $seoScore += 25;
                                }

                                $badgeColor = 'secondary';
                                $statusText = 'Poor';
                                if ($seoScore >= 80) {
                                    $badgeColor = 'success';
                                    $statusText = 'Excellent';
                                } elseif ($seoScore >= 60) {
                                    $badgeColor = 'info';
                                    $statusText = 'Good';
                                } elseif ($seoScore >= 40) {
                                    $badgeColor = 'warning';
                                    $statusText = 'Average';
                                }
                            @endphp

                            <div class="row">
                                <div class="col-md-4 text-center mb-3">
                                    <div class="bg-light rounded p-3">
                                        <div class="display-4 text-{{ $badgeColor }}">{{ $seoScore }}%</div>
                                        <div class="progress mt-2" style="height: 5px;">
                                            <div class="progress-bar bg-{{ $badgeColor }}"
                                                style="width: {{ $seoScore }}%;"></div>
                                        </div>
                                        <span class="badge bg-{{ $badgeColor }} mt-2">{{ $statusText }}</span>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-6 mb-2">
                                            <small class="text-muted">Meta Title</small>
                                            <div class="{{ $category->meta_title ? 'text-success' : 'text-danger' }}">
                                                {{ $category->meta_title ? '✓ Present' : '✗ Missing' }}
                                            </div>
                                        </div>
                                        <div class="col-6 mb-2">
                                            <small class="text-muted">Meta Description</small>
                                            <div
                                                class="{{ $category->meta_description ? 'text-success' : 'text-danger' }}">
                                                {{ $category->meta_description ? '✓ Present' : '✗ Missing' }}
                                            </div>
                                        </div>
                                        <div class="col-6 mb-2">
                                            <small class="text-muted">Meta Keywords</small>
                                            <div class="{{ $category->meta_keywords ? 'text-success' : 'text-danger' }}">
                                                {{ $category->meta_keywords ? '✓ Present' : '✗ Missing' }}
                                            </div>
                                        </div>
                                        <div class="col-6 mb-2">
                                            <small class="text-muted">Focus Keyword</small>
                                            <div class="{{ $category->focus_keyword ? 'text-success' : 'text-danger' }}">
                                                {{ $category->focus_keyword ? '✓ Present' : '✗ Missing' }}
                                            </div>
                                        </div>
                                        <div class="col-6 mb-2">
                                            <small class="text-muted">Canonical URL</small>
                                            <div class="{{ $category->canonical_url ? 'text-success' : 'text-danger' }}">
                                                {{ $category->canonical_url ? '✓ Set' : '✗ Not set' }}
                                            </div>
                                        </div>
                                        <div class="col-6 mb-2">
                                            <small class="text-muted">OG Image</small>
                                            <div class="{{ $category->og_image ? 'text-success' : 'text-danger' }}">
                                                {{ $category->og_image ? '✓ Set' : '✗ Not set' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if ($category->meta_title || $category->meta_description || $category->focus_keyword)
                                <hr>
                                <div class="mt-3">
                                    <h6 class="mb-2">Meta Details</h6>
                                    @if ($category->meta_title)
                                        <div class="mb-2">
                                            <label class="fw-semibold text-muted small">Meta Title</label>
                                            <div class="bg-light p-2 rounded small">{{ $category->meta_title }}</div>
                                        </div>
                                    @endif
                                    @if ($category->meta_description)
                                        <div class="mb-2">
                                            <label class="fw-semibold text-muted small">Meta Description</label>
                                            <div class="bg-light p-2 rounded small">{{ $category->meta_description }}
                                            </div>
                                        </div>
                                    @endif
                                    @if ($category->meta_keywords)
                                        <div class="mb-2">
                                            <label class="fw-semibold text-muted small">Meta Keywords</label>
                                            <div class="bg-light p-2 rounded small">{{ $category->meta_keywords }}</div>
                                        </div>
                                    @endif
                                    @if ($category->focus_keyword)
                                        <div class="mb-2">
                                            <label class="fw-semibold text-muted small">Focus Keyword</label>
                                            <div class="bg-light p-2 rounded small">{{ $category->focus_keyword }}</div>
                                        </div>
                                    @endif
                                    @if ($category->canonical_url)
                                        <div class="mb-2">
                                            <label class="fw-semibold text-muted small">Canonical URL</label>
                                            <div class="bg-light p-2 rounded small">
                                                <a href="{{ $category->canonical_url }}"
                                                    target="_blank">{{ $category->canonical_url }}</a>
                                            </div>
                                        </div>
                                    @endif
                                    @if ($category->og_title || $category->og_description)
                                        <div class="mb-2">
                                            <label class="fw-semibold text-muted small">Open Graph</label>
                                            <div class="bg-light p-2 rounded small">
                                                @if ($category->og_title)
                                                    <div><strong>Title:</strong> {{ $category->og_title }}</div>
                                                @endif
                                                @if ($category->og_description)
                                                    <div><strong>Description:</strong> {{ $category->og_description }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                    @if ($category->og_image)
                                        <div class="mb-2">
                                            <label class="fw-semibold text-muted small">OG Image</label>
                                            <div>
                                                <img src="{{ asset('storage/' . $category->og_image) }}" alt="OG Image"
                                                    class="img-fluid rounded border" style="max-height: 100px;">
                                            </div>
                                        </div>
                                    @endif
                                    @if ($category->schema_markup)
                                        <div class="mb-2">
                                            <label class="fw-semibold text-muted small">Schema Markup (JSON-LD)</label>
                                            <pre class="bg-light p-2 rounded small overflow-auto" style="max-height: 200px;">{{ json_encode($category->schema_markup, JSON_PRETTY_PRINT) }}</pre>
                                        </div>
                                    @endif
                                </div>
                            @endif
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
                                        @php
                                            $childViews = $child->analytics->sum('view_count');
                                            $childProducts = $child->analytics->sum('product_count');
                                            $childRevenue = $child->analytics->sum('total_revenue');
                                        @endphp
                                        <div class="col-md-6 mb-2">
                                            <div
                                                class="d-flex justify-content-between align-items-center p-2 bg-light rounded">
                                                <div>
                                                    <i class="ti ti-folder text-primary me-2"></i>
                                                    <a href="{{ route('admin.categories.show', $child->id) }}"
                                                        class="text-decoration-none">
                                                        {{ $child->name }}
                                                    </a>
                                                    <small class="text-muted d-block">
                                                        <i class="ti ti-package"></i> {{ number_format($childProducts) }}
                                                        products
                                                        @if ($child->status)
                                                            <span class="badge bg-success ms-1"
                                                                style="font-size: 8px;">Active</span>
                                                        @else
                                                            <span class="badge bg-danger ms-1"
                                                                style="font-size: 8px;">Inactive</span>
                                                        @endif
                                                    </small>
                                                </div>
                                                <div class="text-end">
                                                    <div><span class="badge bg-info">{{ number_format($childViews) }}
                                                            views</span></div>
                                                    @if ($childRevenue > 0)
                                                        <div><small
                                                                class="text-success">${{ number_format($childRevenue, 2) }}</small>
                                                        </div>
                                                    @endif
                                                    @if ($child->is_featured)
                                                        <span class="badge bg-warning"><i class="ti ti-star"></i></span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="ti ti-folder-off" style="font-size: 48px; opacity: 0.5;"></i>
                                    <p class="text-muted mt-2">No subcategories found.</p>
                                    @can('create categories')
                                        <a href="{{ route('admin.categories.create') }}?parent={{ $category->id }}"
                                            class="btn btn-sm btn-primary">
                                            <i class="ti ti-plus"></i> Add Subcategory
                                        </a>
                                    @endcan
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Recent Analytics Chart Card --}}
                    @if ($category->analytics && $category->analytics->count() > 0)
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="ti ti-chart-line"></i> Performance Trends (Last 30 Days)
                                </h5>
                            </div>
                            <div class="card-body">
                                <canvas id="analyticsChart" height="250"></canvas>
                            </div>
                        </div>
                    @endif

                    {{-- Action Buttons --}}
                    <div class="card">
                        <div class="card-footer d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                                <i class="ti ti-arrow-left me-1"></i> Back to List
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
                            @if ($category->approval_status === 'pending' && auth()->guard('admin')->user()->can('edit_categories'))
                                <button type="button" class="btn btn-info"
                                    onclick="approveCategory({{ $category->id }})">
                                    <i class="ti ti-check"></i> Approve
                                </button>
                                <button type="button" class="btn btn-warning"
                                    onclick="showRejectModal({{ $category->id }})">
                                    <i class="ti ti-x"></i> Reject
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if ($category->analytics && $category->analytics->count() > 0)
            // Analytics Chart
            const analyticsData = @json($category->analytics->sortBy('date')->values());
            const ctx = document.getElementById('analyticsChart').getContext('2d');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: analyticsData.map(item => {
                        const date = new Date(item.date);
                        return date.toLocaleDateString('en-US', {
                            month: 'short',
                            day: 'numeric'
                        });
                    }),
                    datasets: [{
                        label: 'Views',
                        data: analyticsData.map(item => item.view_count),
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        fill: true,
                        yAxisID: 'y'
                    }, {
                        label: 'Orders',
                        data: analyticsData.map(item => item.order_count),
                        borderColor: 'rgb(34, 197, 94)',
                        backgroundColor: 'rgba(34, 197, 94, 0.1)',
                        tension: 0.4,
                        fill: true,
                        yAxisID: 'y'
                    }, {
                        label: 'Revenue ($)',
                        data: analyticsData.map(item => item.total_revenue),
                        borderColor: 'rgb(234, 179, 8)',
                        backgroundColor: 'rgba(234, 179, 8, 0.1)',
                        tension: 0.4,
                        fill: true,
                        yAxisID: 'y1'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    let value = context.raw;
                                    if (context.dataset.label === 'Revenue ($)') {
                                        return label + ': $' + value.toLocaleString();
                                    }
                                    return label + ': ' + value.toLocaleString();
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Views & Orders',
                                color: '#6c757d'
                            },
                            ticks: {
                                callback: function(value) {
                                    return value.toLocaleString();
                                }
                            }
                        },
                        y1: {
                            beginAtZero: true,
                            position: 'right',
                            title: {
                                display: true,
                                text: 'Revenue ($)',
                                color: '#6c757d'
                            },
                            ticks: {
                                callback: function(value) {
                                    return '$' + value.toLocaleString();
                                }
                            },
                            grid: {
                                drawOnChartArea: false,
                            }
                        }
                    }
                }
            });
        @endif

        // Approve Category
        function approveCategory(categoryId) {
            Swal.fire({
                title: 'Approve Category?',
                text: 'Are you sure you want to approve this category?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, approve it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ url('admin/categories') }}/' + categoryId + '/approve',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Approved!',
                                    text: response.message,
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => {
                                    location.reload();
                                });
                            }
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: xhr.responseJSON?.message ||
                                    'Failed to approve category.',
                                confirmButtonColor: '#d33'
                            });
                        }
                    });
                }
            });
        }

        // Show Reject Modal
        function showRejectModal(categoryId) {
            Swal.fire({
                title: 'Reject Category',
                html: `
                    <p>Please provide a reason for rejection:</p>
                    <textarea id="rejectionReason" class="swal2-textarea" placeholder="Enter rejection reason..." rows="3"></textarea>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, reject it!',
                preConfirm: () => {
                    const reason = document.getElementById('rejectionReason').value;
                    if (!reason) {
                        Swal.showValidationMessage('Please provide a rejection reason');
                        return false;
                    }
                    return {
                        reason: reason
                    };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ url('admin/categories') }}/' + categoryId + '/reject',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            rejection_reason: result.value.reason
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Rejected!',
                                    text: response.message,
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => {
                                    location.reload();
                                });
                            }
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: xhr.responseJSON?.message || 'Failed to reject category.',
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
        .bg-primary-subtle {
            background-color: rgba(59, 130, 246, 0.1);
        }

        .bg-success-subtle {
            background-color: rgba(34, 197, 94, 0.1);
        }

        .bg-warning-subtle {
            background-color: rgba(234, 179, 8, 0.1);
        }

        .bg-info-subtle {
            background-color: rgba(14, 165, 233, 0.1);
        }

        .bg-danger-subtle {
            background-color: rgba(220, 53, 69, 0.1);
        }

        .table-borderless td,
        .table-borderless th {
            padding: 0.5rem 0;
        }

        .display-4 {
            font-size: 2.5rem;
            font-weight: 300;
            line-height: 1.2;
        }
    </style>
@endpush
