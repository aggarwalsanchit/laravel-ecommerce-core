{{-- resources/views/admin/attributes/show.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Attribute Details')

@section('content')
<div class="page-content">
    <div class="page-container">
        <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
            <div class="flex-grow-1">
                <h4 class="fs-18 text-uppercase fw-bold mb-0">Attribute Details: {{ $attribute->name }}</h4>
            </div>
            <div class="text-end">
                <ol class="breadcrumb m-0 py-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.attributes.index') }}">Attributes</a></li>
                    <li class="breadcrumb-item active">{{ $attribute->name }}</li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4">
                {{-- Attribute Information Card --}}
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title">Attribute Information</h5>
                    </div>
                    <div class="card-body">
                        @if($attribute->icon)
                            <div class="text-center mb-4">
                                <img src="{{ asset('storage/attributes/icons/' . $attribute->icon) }}" 
                                     style="max-width: 100px; border-radius: 8px;">
                            </div>
                        @endif
                        
                        <table class="table table-borderless">
                            32
                                <td width="120"><strong>ID:</strong>64
                                <td>#{{ $attribute->id }}64
                            </tr>
                            <tr>
                                <td><strong>Name:</strong></td>
                                <td>
                                    <span class="fw-semibold">{{ $attribute->name }}</span>
                                    <br><small class="text-muted">{{ $attribute->slug }}</small>
                                </td>
                            </tr>
                            @if($attribute->code)
                            <tr>
                                <td><strong>Code:</strong></td>
                                <td><code>{{ $attribute->code }}</code></td>
                            </tr>
                            @endif
                            <tr>
                                <td><strong>Type:</strong></td>
                                <td>
                                    <span class="badge bg-info-subtle text-info">
                                        <i class="ti ti-{{ $attribute->type }}"></i> {{ ucfirst($attribute->type) }}
                                    </span>
                                    @if($attribute->unit)
                                        <br><small class="text-muted">Unit: {{ $attribute->unit }}</small>
                                    @endif
                                </td>
                            </tr>
                            @if($attribute->group)
                            <tr>
                                <td><strong>Group:</strong></td>
                                <td>
                                    <a href="{{ route('admin.attribute-groups.show', $attribute->group) }}">
                                        <i class="ti ti-category"></i> {{ $attribute->group->name }}
                                    </a>
                                </td>
                            </tr>
                            @endif
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td>
                                    @if($attribute->status)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                    @if($attribute->is_featured)
                                        <span class="badge bg-warning ms-1">Featured</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Created:</strong></td>
                                <td>{{ $attribute->created_at->format('F d, Y H:i') }}<br>
                                    <small class="text-muted">{{ $attribute->created_at->diffForHumans() }}</small>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Last Updated:</strong></td>
                                <td>{{ $attribute->updated_at->diffForHumans() }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                {{-- Analytics Card --}}
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title">Analytics</h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6 mb-3">
                                <div class="bg-primary-subtle rounded p-3">
                                    <h3>{{ number_format($attribute->total_views) }}</h3>
                                    <small>Total Views</small>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="bg-success-subtle rounded p-3">
                                    <h3>{{ number_format($attribute->total_products) }}</h3>
                                    <small>Products</small>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="bg-warning-subtle rounded p-3">
                                    <h3>${{ number_format($attribute->total_revenue, 2) }}</h3>
                                    <small>Revenue</small>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="bg-info-subtle rounded p-3">
                                    <h3>{{ number_format($attribute->values->count()) }}</h3>
                                    <small>Values</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Settings Card --}}
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Settings</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-check form-switch">
                                    <input type="checkbox" class="form-check-input" disabled {{ $attribute->is_required ? 'checked' : '' }}>
                                    <label class="form-check-label">Required</label>
                                </div>
                                <div class="form-check form-switch mt-2">
                                    <input type="checkbox" class="form-check-input" disabled {{ $attribute->is_filterable ? 'checked' : '' }}>
                                    <label class="form-check-label">Filterable</label>
                                </div>
                                <div class="form-check form-switch mt-2">
                                    <input type="checkbox" class="form-check-input" disabled {{ $attribute->is_variant ? 'checked' : '' }}>
                                    <label class="form-check-label">Used for Variants</label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-check form-switch">
                                    <input type="checkbox" class="form-check-input" disabled {{ $attribute->has_image ? 'checked' : '' }}>
                                    <label class="form-check-label">Has Images</label>
                                </div>
                                <div class="form-check form-switch mt-2">
                                    <input type="checkbox" class="form-check-input" disabled {{ $attribute->discount_applicable ? 'checked' : '' }}>
                                    <label class="form-check-label">Discount Applicable</label>
                                </div>
                                <div class="form-check form-switch mt-2">
                                    <input type="checkbox" class="form-check-input" disabled {{ $attribute->track_analytics ? 'checked' : '' }}>
                                    <label class="form-check-label">Track Analytics</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                {{-- Description Card --}}
                @if($attribute->description)
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title">Description</h5>
                    </div>
                    <div class="card-body">
                        <div class="p-3 bg-light rounded">
                            {!! nl2br(e($attribute->description)) !!}
                        </div>
                    </div>
                </div>
                @endif

                {{-- Values Card --}}
                <div class="card mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Attribute Values</h5>
                        <a href="{{ route('admin.attributes.values.index', $attribute) }}" class="btn btn-sm btn-primary">
                            <i class="ti ti-list-check"></i> Manage All Values ({{ $attribute->values->count() }})
                        </a>
                    </div>
                    <div class="card-body">
                        @if($attribute->values->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Value</th>
                                            @if($attribute->type == 'color')
                                                <th>Color</th>
                                            @endif
                                            @if($attribute->has_image)
                                                <th>Image</th>
                                            @endif
                                            <th>Products</th>
                                            <th>Order</th>
                                            <th>Default</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($attribute->values->take(10) as $value)
                                        <tr>
                                            <td>
                                                @if($attribute->type == 'color' && $value->color_code)
                                                    <span style="display: inline-block; width: 16px; height: 16px; background: {{ $value->color_code }}; border-radius: 3px; margin-right: 8px;"></span>
                                                @endif
                                                {{ $value->value }}
                                            </td>
                                            @if($attribute->type == 'color')
                                                <td><code>{{ $value->color_code ?: '—' }}</code></td>
                                            @endif
                                            @if($attribute->has_image)
                                                <td>
                                                    @if($value->image)
                                                        <img src="{{ Storage::disk('public')->url('attributes/' . $attribute->slug . '/values/' . $value->image) }}" 
                                                             style="width: 30px; height: 30px; object-fit: cover; border-radius: 4px;">
                                                    @else
                                                        —
                                                    @endif
                                                </td>
                                            @endif
                                            <td>{{ $value->usage_count }}</td>
                                            <td>{{ $value->display_order }}</td>
                                            <td>
                                                @if($value->is_default)
                                                    <span class="badge bg-success">Default</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if($attribute->values->count() > 10)
                                <div class="text-center mt-3">
                                    <a href="{{ route('admin.attributes.values.index', $attribute) }}" class="btn btn-sm btn-outline-primary">
                                        View All {{ $attribute->values->count() }} Values
                                    </a>
                                </div>
                            @endif
                        @else
                            <div class="text-center py-4">
                                <i class="ti ti-list-off fs-1 text-muted"></i>
                                <p class="text-muted mt-2">No values added for this attribute yet.</p>
                                <a href="{{ route('admin.attributes.values.index', $attribute) }}" class="btn btn-sm btn-primary">
                                    <i class="ti ti-plus"></i> Add First Value
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- SEO Preview Card --}}
                @if($attribute->meta_title || $attribute->meta_description)
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">SEO Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="text-muted small">Meta Title</label>
                            <p>{{ $attribute->meta_title ?: 'Not set' }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted small">Meta Description</label>
                            <p>{{ $attribute->meta_description ?: 'Not set' }}</p>
                        </div>
                        @if($attribute->meta_keywords)
                        <div class="mb-3">
                            <label class="text-muted small">Meta Keywords</label>
                            <p>{{ $attribute->meta_keywords }}</p>
                        </div>
                        @endif
                        <div class="alert alert-info mt-2">
                            <i class="ti ti-eye me-1"></i>
                            <strong>SEO Preview:</strong>
                            <div class="mt-2">
                                <div class="text-primary">{{ $attribute->meta_title ?: $attribute->name }}</div>
                                <div class="text-muted small">{{ url('/attribute') }}/{{ $attribute->slug }}</div>
                                <div class="text-muted small">{{ Str::limit($attribute->meta_description ?: $attribute->description ?: 'Attribute description...', 160) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Action Buttons --}}
                <div class="card mt-3">
                    <div class="card-footer text-end">
                        <a href="{{ route('admin.attributes.index') }}" class="btn btn-secondary">
                            <i class="ti ti-arrow-left"></i> Back
                        </a>
                        <a href="{{ route('admin.attributes.analytics', $attribute) }}" class="btn btn-info">
                            <i class="ti ti-chart-bar"></i> View Analytics
                        </a>
                        <a href="{{ route('admin.attributes.edit', $attribute) }}" class="btn btn-primary">
                            <i class="ti ti-edit"></i> Edit Attribute
                        </a>
                        @can('delete attributes')
                            @if($attribute->total_products == 0)
                                <button type="button" class="btn btn-danger" onclick="confirmDelete({{ $attribute->id }})">
                                    <i class="ti ti-trash"></i> Delete Attribute
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
<script>
function confirmDelete(id) {
    Swal.fire({
        title: 'Delete Attribute?',
        text: "Are you sure? This will also delete all its values!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            let form = $('#deleteForm');
            form.attr('action', '{{ url("admin/attributes") }}/' + id);
            form.submit();
        }
    });
}
</script>
@endpush