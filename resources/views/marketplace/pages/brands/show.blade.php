{{-- resources/views/marketplace/pages/brands/show.blade.php --}}
@extends('management.layouts.app')

@section('title', 'Brand Details - ' . ($brand->name ?? 'Not Found'))

@section('content')
    <div class="page-content">
        <div class="page-container">
            <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                <div class="flex-grow-1">
                    <h4 class="fs-18 text-uppercase fw-bold mb-0">Brand Details: {{ $brand->name ?? 'N/A' }}</h4>
                </div>
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('vendor.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('vendor.brands.index') }}">Brands</a></li>
                        <li class="breadcrumb-item active">{{ $brand->name ?? 'Details' }}</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-4">
                    {{-- Brand Logo Card --}}
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="card-title mb-0"><i class="ti ti-photo"></i> Brand Logo</h5>
                        </div>
                        <div class="card-body text-center">
                            @if ($brand->logo)
                                <img src="{{ asset('storage/brands/' . $brand->logo) }}"
                                    alt="{{ $brand->logo_alt ?? $brand->name }}" class="img-fluid rounded"
                                    style="max-height: 200px;">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                    style="height: 150px;">
                                    <i class="ti ti-brand-airbnb fs-1 text-muted"></i>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Brand Information Card --}}
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="card-title mb-0"><i class="ti ti-info-circle"></i> Brand Information</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="120"><strong>ID:</strong></td>
                                    <td>#{{ $brand->id ?? 'N/A' }}
                        </div>
                    </div>
                    </td>
                    </tr>
                    <tr>
                        <td><strong>Name:</strong></td>
                        <td>{{ $brand->name ?? 'N/A' }}
                </div>
            </div>
            </td>
            </tr>
            <tr>
                <td><strong>Code:</strong></td>
                <td><code>{{ $brand->code ?? 'N/A' }}</code>
        </div>
    </div>
    </td>
    </tr>
    <tr>
        <td><strong>Slug:</strong></td>
        <td><code>{{ $brand->slug ?? 'N/A' }}</code></div>
            </div>
        </td>
    </tr>
    <tr>
        <td><strong>Status:</strong></td>
        <td>
            @if (isset($brand->status) && $brand->status)
                <span class="badge bg-success">Active</span>
            @else
                <span class="badge bg-danger">Inactive</span>
            @endif
            </div>
            </div>
        </td>
    </tr>
    <tr>
        <td><strong>Featured:</strong></td>
        <td>
            @if ($brand->is_featured)
                <span class="badge bg-warning"><i class="ti ti-star"></i> Featured</span>
            @else
                <span class="badge bg-secondary">Not Featured</span>
            @endif
            </div>
            </div>
        </td>
    </tr>
    <tr>
        <td><strong>Total Products:</strong></td>
        <td>{{ number_format($productsCount ?? 0) }} products</div>
            </div>
        </td>
    </tr>
    <tr>
        <td><strong>Created:</strong></td>
        <td>
            @if ($brand->created_at)
                {{ $brand->created_at->format('F d, Y') }}
            @else
                —
            @endif
            </div>
            </div>
        </td>
    </tr>
    </table>
    </div>
    </div>

    {{-- Categories Card --}}
    @if ($brand->categories->count() > 0)
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="ti ti-folder"></i> Associated Categories</h5>
            </div>
            <div class="card-body">
                <div class="d-flex flex-wrap gap-2">
                    @foreach ($brand->categories as $category)
                        <span class="badge bg-primary p-2">
                            <i class="ti ti-folder"></i> {{ $category->name }}
                        </span>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
    </div>

    <div class="col-lg-8">
        {{-- Description Card --}}
        @if (!empty($brand->description))
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="ti ti-align-left"></i> Description</h5>
                </div>
                <div class="card-body">
                    <div>
                        {!! nl2br(e($brand->description)) !!}
                    </div>
                </div>
            </div>
        @endif

        {{-- Action Buttons --}}
        <div class="card">
            <div class="card-footer d-flex justify-content-end gap-2">
                <a href="{{ route('vendor.brands.index') }}" class="btn btn-secondary">
                    <i class="ti ti-arrow-left me-1"></i> Back to Brands
                </a>
                <a href="{{ route('vendor.products.create') }}?brand_id={{ $brand->id }}" class="btn btn-primary">
                    <i class="ti ti-package me-1"></i> Add Product with this Brand
                </a>
            </div>
        </div>
    </div>
    </div>
    </div>
    </div>
@endsection
