{{-- resources/views/marketplace/pages/sizes/show.blade.php --}}
@extends('management.layouts.app')

@section('title', 'Size Details - ' . $size->name)

@section('content')
<div class="page-content">
    <div class="page-container">
        <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
            <div class="flex-grow-1">
                <h4 class="fs-18 text-uppercase fw-bold mb-0">Size Details: {{ $size->name }}</h4>
            </div>
            <div class="text-end">
                <ol class="breadcrumb m-0 py-0">
                    <li class="breadcrumb-item"><a href="{{ route('vendor.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('vendor.sizes.index') }}">Sizes</a></li>
                    <li class="breadcrumb-item active">{{ $size->name }}</li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4">
                {{-- Size Preview Card --}}
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ti ti-ruler"></i> Size Preview</h5>
                    </div>
                    <div class="card-body text-center">
                        @if($size->image)
                            <img src="{{ asset('storage/sizes/' . $size->image) }}" alt="{{ $size->name }}" class="img-fluid rounded mb-3" style="max-height: 100px;">
                        @else
                            <div class="size-icon mb-3">
                                <i class="ti ti-ruler fs-1 text-primary" style="font-size: 60px;"></i>
                            </div>
                        @endif
                        <h3>{{ $size->name }}</h3>
                        <code class="fs-4">{{ $size->code }}</code>
                        <div class="mt-2">
                            <span class="badge bg-{{ $size->gender == 'Men' ? 'primary' : ($size->gender == 'Women' ? 'danger' : ($size->gender == 'Unisex' ? 'info' : 'success')) }} fs-6">
                                {{ $size->gender }}
                            </span>
                        </div>
                        <div class="mt-2">
                            @if($size->is_featured)
                                <span class="badge bg-warning text-dark"><i class="ti ti-star"></i> Featured</span>
                            @endif
                            @if($size->is_popular)
                                <span class="badge bg-danger"><i class="ti ti-fire"></i> Popular</span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Size Information Card --}}
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ti ti-info-circle"></i> Size Information</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <td width="120"><strong>ID:</strong></td>
                                <td>#{{ $size->id }}</td>
                            </tr>
                            <tr>
                                <td><strong>Name:</strong></td>
                                <td>{{ $size->name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Slug:</strong></td>
                                <td><code>{{ $size->slug }}</code></td>
                            </tr>
                            <tr>
                                <td><strong>Code:</strong></td>
                                <td><code>{{ $size->code }}</code></td>
                            </tr>
                            <tr>
                                <td><strong>Gender:</strong></td>
                                <td>
                                    <span class="badge bg-{{ $size->gender == 'Men' ? 'primary' : ($size->gender == 'Women' ? 'danger' : ($size->gender == 'Unisex' ? 'info' : 'success')) }}">
                                        {{ $size->gender }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Order:</strong></td>
                                <td>{{ $size->order }}</td>
                            </tr>
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td>
                                    @if($size->status)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Usage Count:</strong></td>
                                <td>{{ number_format($size->usage_count) }} products</span></td>
                            </tr>
                            <tr>
                                <td><strong>Created:</strong></td>
                                <td>{{ $size->created_at->format('F d, Y') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Last Updated:</strong></td>
                                <td>{{ $size->updated_at->diffForHumans() }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                {{-- Associated Categories Card --}}
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ti ti-folder"></i> Associated Categories</h5>
                    </div>
                    <div class="card-body">
                        @if($size->categories->count() > 0)
                            <div class="d-flex flex-wrap gap-1">
                                @foreach($size->categories as $category)
                                    <span class="badge bg-primary">{{ $category->name }}</span>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted mb-0">No categories associated</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                {{-- Description Card --}}
                @if($size->description)
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ti ti-align-left"></i> Description</h5>
                    </div>
                    <div class="card-body">
                        <p>{{ $size->description }}</p>
                    </div>
                </div>
                @endif

                {{-- Measurements Card --}}
                @if($size->chest || $size->waist || $size->hip || $size->inseam || $size->shoulder || $size->sleeve || $size->neck)
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ti ti-ruler"></i> Measurements (inches)</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @if($size->chest)
                                <div class="col-md-6 mb-2">
                                    <strong>Chest/Bust:</strong> {{ $size->chest }}"
                                </div>
                            @endif
                            @if($size->waist)
                                <div class="col-md-6 mb-2">
                                    <strong>Waist:</strong> {{ $size->waist }}"
                                </div>
                            @endif
                            @if($size->hip)
                                <div class="col-md-6 mb-2">
                                    <strong>Hip:</strong> {{ $size->hip }}"
                                </div>
                            @endif
                            @if($size->inseam)
                                <div class="col-md-6 mb-2">
                                    <strong>Inseam:</strong> {{ $size->inseam }}"
                                </div>
                            @endif
                            @if($size->shoulder)
                                <div class="col-md-6 mb-2">
                                    <strong>Shoulder:</strong> {{ $size->shoulder }}"
                                </div>
                            @endif
                            @if($size->sleeve)
                                <div class="col-md-6 mb-2">
                                    <strong>Sleeve:</strong> {{ $size->sleeve }}"
                                </div>
                            @endif
                            @if($size->neck)
                                <div class="col-md-6 mb-2">
                                    <strong>Neck:</strong> {{ $size->neck }}"
                                </div>
                            @endif
                            @if($size->height)
                                <div class="col-md-6 mb-2">
                                    <strong>Height:</strong> {{ $size->height }}'"
                                </div>
                            @endif
                            @if($size->weight)
                                <div class="col-md-6 mb-2">
                                    <strong>Weight:</strong> {{ $size->weight }} lbs
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                {{-- Size Conversion Chart Card --}}
                @if($size->us_size || $size->uk_size || $size->eu_size || $size->au_size || $size->jp_size || $size->cn_size || $size->int_size)
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ti ti-exchange"></i> Size Conversion Chart</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>US</th>
                                        <th>UK</th>
                                        <th>EU</th>
                                        <th>AU</th>
                                        <th>JP</th>
                                        <th>CN</th>
                                        <th>International</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="fw-semibold">{{ $size->us_size ?? '-' }}</td>
                                        <td>{{ $size->uk_size ?? '-' }}</td>
                                        <td>{{ $size->eu_size ?? '-' }}</td>
                                        <td>{{ $size->au_size ?? '-' }}</td>
                                        <td>{{ $size->jp_size ?? '-' }}</td>
                                        <td>{{ $size->cn_size ?? '-' }}</td>
                                        <td>{{ $size->int_size ?? '-' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Action Buttons --}}
                <div class="card">
                    <div class="card-footer d-flex justify-content-between gap-2">
                        <a href="{{ route('vendor.sizes.index') }}" class="btn btn-secondary">
                            <i class="ti ti-arrow-left me-1"></i> Back to Sizes
                        </a>
                        <div>
                            <a href="{{ route('vendor.products.create') }}?size={{ $size->id }}" class="btn btn-primary">
                                <i class="ti ti-package me-1"></i> Add Product with this Size
                            </a>
                            @if(!$size->is_popular && !$size->is_featured)
                                <a href="{{ route('vendor.sizes.request.create') }}?suggest={{ $size->id }}" class="btn btn-outline-info ms-2">
                                    <i class="ti ti-message"></i> Suggest Improvement
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .table-borderless td, .table-borderless th {
        padding: 0.5rem 0;
    }
    .size-icon {
        transition: transform 0.2s;
    }
    .size-icon:hover {
        transform: scale(1.05);
    }
    .badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }
</style>
@endpush