{{-- resources/views/marketplace/pages/categories/show.blade.php --}}
@extends('management.layouts.app')

@section('title', 'Category Details - ' . ($category->name ?? 'Not Found'))

@section('content')
<div class="page-content">
    <div class="page-container">
        <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
            <div class="flex-grow-1">
                <h4 class="fs-18 text-uppercase fw-bold mb-0">Category Details: {{ $category->name ?? 'N/A' }}</h4>
            </div>
            <div class="text-end">
                <ol class="breadcrumb m-0 py-0">
                    <li class="breadcrumb-item"><a href="{{ route('vendor.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('vendor.categories.index') }}">Categories</a></li>
                    <li class="breadcrumb-item active">{{ $category->name ?? 'Details' }}</li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4">
                {{-- Category Image Card --}}
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ti ti-photo"></i> Category Image</h5>
                    </div>
                    <div class="card-body text-center">
                        @php
                            $imagePath = null;
                            if (!empty($category->image) && Storage::disk('public')->exists('categories/' . $category->image)) {
                                $imagePath = Storage::disk('public')->url('categories/' . $category->image);
                            } elseif (!empty($category->thumbnail_image) && Storage::disk('public')->exists('categories/thumbnails/' . $category->thumbnail_image)) {
                                $imagePath = Storage::disk('public')->url('categories/thumbnails/' . $category->thumbnail_image);
                            }
                        @endphp
                        @if($imagePath)
                            <img src="{{ $imagePath }}" alt="{{ $category->image_alt ?? $category->name }}" class="img-fluid rounded" style="max-height: 200px;">
                        @else
                            <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 150px;">
                                <i class="ti ti-folder fs-1 text-muted"></i>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Category Information Card --}}
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ti ti-info-circle"></i> Category Information</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <td width="120"><strong>ID:</strong></td>
                                <td>#{{ $category->id ?? 'N/A' }}</div></div></td>
                            </tr>
                            <tr>
                                <td><strong>Name:</strong></td>
                                <td>{{ $category->name ?? 'N/A' }}</div></div></td>
                            </tr>
                            <tr>
                                <td><strong>Slug:</strong></td>
                                <td><code>{{ $category->slug ?? 'N/A' }}</code></div></div></td>
                            </tr>
                            @if(!empty($category->parent))
                            <tr>
                                <td><strong>Parent Category:</strong></td>
                                <td>
                                    <a href="{{ route('vendor.categories.show', $category->parent->id) }}" class="text-decoration-none">
                                        <i class="ti ti-arrow-narrow-up text-success me-1"></i>
                                        {{ $category->parent->name }}
                                    </a>
                                 </div></div></td>
                            </tr>
                            @else
                            <tr>
                                <td><strong>Parent Category:</strong></td>
                                <td><span class="badge bg-secondary">Main Category</span></div></div></td>
                            </tr>
                            @endif
                            <tr>
                                <td><strong>Level:</strong></td>
                                <td>
                                    @if(isset($category->level) && $category->level > 0)
                                        Level {{ $category->level }}
                                    @else
                                        Top Level
                                    @endif
                                 </div></div></td>
                            </tr>
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td>
                                    @if(isset($category->status) && $category->status)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                 </div></div></td>
                            </tr>
                            <tr>
                                <td><strong>Created:</strong></td>
                                <td>
                                    @if($category->created_at)
                                        {{ $category->created_at->format('F d, Y') }}
                                    @else
                                        —
                                    @endif
                                 </div></div></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                {{-- Description Card --}}
                @if(!empty($category->description) || !empty($category->short_description))
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ti ti-align-left"></i> Description</h5>
                    </div>
                    <div class="card-body">
                        @if(!empty($category->short_description))
                            <div class="alert alert-info mb-3">
                                <strong>Short Description:</strong><br>
                                {{ $category->short_description }}
                            </div>
                        @endif
                        @if(!empty($category->description))
                            <div>
                                {!! nl2br(e($category->description)) !!}
                            </div>
                        @endif
                    </div>
                </div>
                @endif

                {{-- Subcategories Card --}}
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ti ti-folder"></i> Subcategories 
                            <span class="badge bg-info ms-1">{{ $category->children->count() ?? 0 }}</span>
                        </h5>
                    </div>
                    <div class="card-body">
                        @if(isset($category->children) && $category->children->count() > 0)
                            <div class="row">
                                @foreach($category->children as $child)
                                    <div class="col-md-6 mb-2">
                                        <div class="d-flex align-items-center p-2 bg-light rounded">
                                            <i class="ti ti-folder text-primary me-2"></i>
                                            <a href="{{ route('vendor.categories.show', $child->id) }}" class="text-decoration-none">
                                                {{ $child->name }}
                                            </a>
                                            @if($child->status)
                                                <span class="badge bg-success ms-2">Active</span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="ti ti-folder-off" style="font-size: 48px; opacity: 0.5;"></i>
                                <p class="text-muted mt-2">No subcategories found.</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="card">
                    <div class="card-footer d-flex justify-content-end gap-2">
                        <a href="{{ route('vendor.categories.index') }}" class="btn btn-secondary">
                            <i class="ti ti-arrow-left me-1"></i> Back to Categories
                        </a>
                        <a href="{{ route('vendor.products.create') }}?category={{ $category->id }}" class="btn btn-primary">
                            <i class="ti ti-package me-1"></i> Add Product in this Category
                        </a>
                        @if(isset($category->approval_status) && $category->approval_status === 'pending')
                            <span class="badge bg-warning p-2">Pending Approval</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection