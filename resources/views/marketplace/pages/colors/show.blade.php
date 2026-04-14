{{-- resources/views/marketplace/pages/colors/show.blade.php --}}
@extends('management.layouts.app')

@section('title', 'Color Details - ' . $color->name)

@section('content')
<div class="page-content">
    <div class="page-container">
        <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
            <div class="flex-grow-1">
                <h4 class="fs-18 text-uppercase fw-bold mb-0">Color Details: {{ $color->name }}</h4>
            </div>
            <div class="text-end">
                <ol class="breadcrumb m-0 py-0">
                    <li class="breadcrumb-item"><a href="{{ route('vendor.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('vendor.colors.index') }}">Colors</a></li>
                    <li class="breadcrumb-item active">{{ $color->name }}</li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4">
                {{-- Color Preview Card --}}
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ti ti-palette"></i> Color Preview</h5>
                    </div>
                    <div class="card-body text-center">
                        <div class="rounded-circle mb-3 mx-auto shadow" 
                             style="width: 150px; 
                                    height: 150px; 
                                    background-color: {{ $color->code }}; 
                                    border: 3px solid #ddd;
                                    margin: 0 auto 20px;">
                        </div>
                        <h3>{{ $color->name }}</h3>
                        <code class="fs-4">{{ $color->code }}</code>
                        @if($color->rgb)
                            <div class="text-muted mt-2">{{ $color->rgb }}</div>
                        @endif
                        @if($color->hsl)
                            <div class="text-muted">{{ $color->hsl }}</div>
                        @endif
                        <div class="mt-3">
                            @if($color->is_featured)
                                <span class="badge bg-warning text-dark"><i class="ti ti-star"></i> Featured</span>
                            @endif
                            @if($color->is_popular)
                                <span class="badge bg-danger"><i class="ti ti-fire"></i> Popular</span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Color Information Card --}}
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ti ti-info-circle"></i> Color Information</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <td width="120"><strong>ID:</strong></td>
                                <td>#{{ $color->id }}</td>
                            </tr>
                            <tr>
                                <td><strong>Name:</strong></td>
                                <td>{{ $color->name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Slug:</strong></td>
                                <td><code>{{ $color->slug }}</code></td>
                            </tr>
                            <tr>
                                <td><strong>Hex Code:</strong></td>
                                <td><code>{{ $color->code }}</code></td>
                            </tr>
                            <tr>
                                <td><strong>Usage Count:</strong></td>
                                <td>{{ number_format($color->usage_count) }} products</span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Created:</strong></td>
                                <td>{{ $color->created_at->format('F d, Y') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                {{-- Description Card --}}
                @if($color->description)
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ti ti-align-left"></i> Description</h5>
                    </div>
                    <div class="card-body">
                        <p>{{ $color->description }}</p>
                    </div>
                </div>
                @endif

                {{-- Similar Colors Card --}}
                @if($color->getSimilarColorsAttribute()->count() > 0)
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ti ti-color-swatch"></i> Similar Colors</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($color->getSimilarColorsAttribute() as $similarColor)
                                <div class="col-md-4 col-sm-6 mb-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <div style="width: 30px; height: 30px; background-color: {{ $similarColor->code }}; border-radius: 50%; border: 1px solid #ddd;"></div>
                                        <div>
                                            <a href="{{ route('vendor.colors.show', $similarColor->id) }}" class="text-decoration-none">
                                                {{ $similarColor->name }}
                                            </a>
                                            <div class="small text-muted">{{ $similarColor->code }}</div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                {{-- Action Buttons --}}
                <div class="card">
                    <div class="card-footer d-flex justify-content-between gap-2">
                        <a href="{{ route('vendor.colors.index') }}" class="btn btn-secondary">
                            <i class="ti ti-arrow-left me-1"></i> Back to Colors
                        </a>
                        <div>
                            <a href="{{ route('vendor.products.create') }}?color={{ $color->id }}" class="btn btn-primary">
                                <i class="ti ti-package me-1"></i> Add Product with this Color
                            </a>
                            @if(!$color->is_popular && !$color->is_featured)
                                <a href="{{ route('vendor.colors.request.create') }}?suggest={{ $color->id }}" class="btn btn-outline-info ms-2">
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
    .rounded-circle {
        transition: transform 0.2s;
    }
    .rounded-circle:hover {
        transform: scale(1.02);
    }
</style>
@endpush