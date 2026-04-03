{{-- resources/views/admin/attributes/values/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Attribute Values')

@section('content')
<div class="page-content">
    <div class="page-container">
        <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
            <div class="flex-grow-1">
                <h4 class="fs-18 text-uppercase fw-bold mb-0">Attribute Values: {{ $attribute->name }}</h4>
            </div>
            <div class="text-end">
                <ol class="breadcrumb m-0 py-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.attributes.index') }}">Attributes</a></li>
                    <li class="breadcrumb-item active">{{ $attribute->name }} Values</li>
                </ol>
            </div>
        </div>

        {{-- Statistics Cards --}}
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6>Total Values</h6>
                                <h2 class="mb-0">{{ $attribute->values->count() }}</h2>
                            </div>
                            <i class="ti ti-list-check fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6>Products Using</h6>
                                <h2 class="mb-0">{{ number_format($attribute->total_products) }}</h2>
                            </div>
                            <i class="ti ti-package fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6>Total Revenue</h6>
                                <h2 class="mb-0">${{ number_format($attribute->total_revenue, 2) }}</h2>
                            </div>
                            <i class="ti ti-chart-line fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-dark">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6>Default Value</h6>
                                <h2 class="mb-0">
                                    @php
                                        $default = $attribute->values->where('is_default', true)->first();
                                    @endphp
                                    {{ $default ? $default->value : 'Not Set' }}
                                </h2>
                            </div>
                            <i class="ti ti-star fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div>
                            <h3 class="card-title mb-0">Values for {{ $attribute->name }}</h3>
                            <p class="text-muted mb-0 mt-1">
                                <i class="ti ti-{{ $attribute->type }} me-1"></i> 
                                Type: <span class="fw-semibold">{{ ucfirst($attribute->type) }}</span>
                                @if($attribute->unit)
                                    | Unit: <span class="fw-semibold">{{ $attribute->unit }}</span>
                                @endif
                                @if($attribute->is_variant)
                                    | <span class="badge bg-info">Variant Attribute</span>
                                @endif
                            </p>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-outline-secondary" onclick="exportValues()">
                                <i class="ti ti-download me-1"></i> Export
                            </button>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addValueModal">
                                <i class="ti ti-plus me-1"></i> Add New Value
                            </button>
                        </div>
                    </div>
                    <div class="card-body">

                        {{-- Values Table --}}
                        <div class="table-responsive">
                            <table class="table table-hover text-nowrap mb-0">
                                <thead class="bg-dark-subtle">
                                    <tr>
                                        <th style="width: 50px;">#</th>
                                        <th>Value</th>
                                        <th>Slug</th>
                                        @if($attribute->type == 'color')
                                            <th>Color Preview</th>
                                        @endif
                                        @if($attribute->has_image)
                                            <th>Image</th>
                                        @endif
                                        @if($attribute->is_variant)
                                            <th>Price Adj.</th>
                                            <th>Stock</th>
                                            <th>SKU</th>
                                        @endif
                                        <th>Products</th>
                                        <th>Revenue</th>
                                        <th>Order</th>
                                        <th>Default</th>
                                        <th>Status</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($values as $value)
                                    <tr data-id="{{ $value->id }}">
                                        <td>
                                            <span class="fw-semibold">#{{ $value->id }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                @if($attribute->type == 'color' && $value->color_code)
                                                    <div style="width: 24px; height: 24px; background: {{ $value->color_code }}; border-radius: 4px; border: 1px solid #dee2e6;"></div>
                                                @endif
                                                <span class="fw-semibold">{{ $value->value }}</span>
                                                @if($value->is_default)
                                                    <span class="badge bg-success">Default</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <code class="small">{{ $value->slug }}</code>
                                        </td>
                                        @if($attribute->type == 'color')
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div style="width: 30px; height: 30px; background: {{ $value->color_code ?: '#e9ecef' }}; border-radius: 6px; border: 1px solid #dee2e6;"></div>
                                                <code>{{ $value->color_code ?: '—' }}</code>
                                            </div>
                                        </td>
                                        @endif
                                        @if($attribute->has_image)
                                        <td>
                                            @if($value->image && Storage::disk('public')->exists('attributes/' . $attribute->slug . '/values/' . $value->image))
                                                <img src="{{ Storage::disk('public')->url('attributes/' . $attribute->slug . '/values/' . $value->image) }}" 
                                                     style="width: 40px; height: 40px; object-fit: cover; border-radius: 6px;">
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        @endif
                                        @if($attribute->is_variant)
                                        <td class="{{ $value->price_adjustment > 0 ? 'text-success' : ($value->price_adjustment < 0 ? 'text-danger' : '') }}">
                                            @if($value->price_adjustment > 0)
                                                +${{ number_format($value->price_adjustment, 2) }}
                                            @elseif($value->price_adjustment < 0)
                                                -${{ number_format(abs($value->price_adjustment), 2) }}
                                            @else
                                                $0.00
                                            @endif
                                        </td>
                                        <td>
                                            @if($value->stock > 0)
                                                <span class="badge bg-success">{{ number_format($value->stock) }}</span>
                                            @elseif($value->stock == 0)
                                                <span class="badge bg-warning">0</span>
                                            @else
                                                <span class="badge bg-secondary">∞</span>
                                            @endif
                                        </td>
                                        <td><code class="small">{{ $value->sku ?: '—' }}</code></td>
                                        @endif
                                        <td>
                                            <a href="#" class="text-primary text-decoration-none">
                                                <span class="fw-semibold">{{ number_format($value->usage_count) }}</span>
                                                <small class="text-muted"> products</small>
                                            </a>
                                        </td>
                                        <td class="text-success">
                                            ${{ number_format($value->total_revenue, 2) }}
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="badge bg-secondary">{{ $value->display_order }}</span>
                                                @if($value->display_order > 0)
                                                    <i class="ti ti-arrow-up text-muted" style="cursor: pointer;" onclick="reorderValue({{ $value->id }}, 'up')"></i>
                                                    <i class="ti ti-arrow-down text-muted" style="cursor: pointer;" onclick="reorderValue({{ $value->id }}, 'down')"></i>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-check form-switch">
                                                <input type="checkbox" class="form-check-input toggle-default" 
                                                       data-id="{{ $value->id }}"
                                                       {{ $value->is_default ? 'checked' : '' }}>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-check form-switch">
                                                <input type="checkbox" class="form-check-input toggle-visibility" 
                                                       data-id="{{ $value->id }}"
                                                       {{ $value->is_visible ? 'checked' : '' }}>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="hstack gap-1 justify-content-center">
                                                <button type="button" 
                                                        class="btn btn-soft-info btn-icon btn-sm rounded-circle" 
                                                        onclick="viewValueAnalytics({{ $value->id }})"
                                                        data-bs-toggle="tooltip" 
                                                        title="View Analytics">
                                                    <i class="ti ti-chart-bar"></i>
                                                </button>
                                                <button type="button" 
                                                        class="btn btn-soft-success btn-icon btn-sm rounded-circle" 
                                                        onclick="editValue({{ $value->id }})"
                                                        data-bs-toggle="tooltip" 
                                                        title="Edit Value">
                                                    <i class="ti ti-edit"></i>
                                                </button>
                                                @can('delete attributes')
                                                    @if($value->usage_count == 0)
                                                        <button type="button" 
                                                                class="btn btn-soft-danger btn-icon btn-sm rounded-circle" 
                                                                onclick="deleteValue({{ $value->id }})"
                                                                data-bs-toggle="tooltip" 
                                                                title="Delete Value">
                                                            <i class="ti ti-trash"></i>
                                                        </button>
                                                    @else
                                                        <button type="button" 
                                                                class="btn btn-soft-secondary btn-icon btn-sm rounded-circle" 
                                                                disabled
                                                                data-bs-toggle="tooltip" 
                                                                title="Cannot delete - used by {{ $value->usage_count }} products">
                                                            <i class="ti ti-lock"></i>
                                                        </button>
                                                    @endif
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="{{ $attribute->type == 'color' ? ($attribute->is_variant ? 12 : 10) : ($attribute->has_image ? ($attribute->is_variant ? 12 : 10) : ($attribute->is_variant ? 11 : 9)) }}" class="text-center py-5">
                                            <div class="empty-state">
                                                <i class="ti ti-list-off" style="font-size: 48px; opacity: 0.5;"></i>
                                                <h5 class="mt-3">No Values Found</h5>
                                                <p class="text-muted">Add values for this attribute to get started.</p>
                                                <button class="btn btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#addValueModal">
                                                    <i class="ti ti-plus me-1"></i> Add First Value
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- Pagination if needed --}}
                        @if(isset($values) && method_exists($values, 'links'))
                            <div class="mt-3">
                                {{ $values->links('pagination::bootstrap-5') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Add Value Modal --}}
<div class="modal fade" id="addValueModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Value to {{ $attribute->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addValueForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Value <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="value" required>
                                <div class="invalid-feedback" id="value-error"></div>
                                <small class="text-muted">The actual value (e.g., Red, 8GB, Cotton)</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Display Order</label>
                                <input type="number" class="form-control" name="display_order" value="0">
                                <small class="text-muted">Lower numbers appear first</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Description <small class="text-muted">(Optional)</small></label>
                        <textarea class="form-control" name="description" rows="2"></textarea>
                    </div>
                    
                    @if($attribute->type == 'color')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Color Code</label>
                                <input type="color" class="form-control" name="color_code" style="height: 50px;">
                                <small class="text-muted">Hex color code (e.g., #FF0000 for Red)</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Color Name</label>
                                <input type="text" class="form-control" name="color_name" placeholder="e.g., Crimson Red">
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    @if($attribute->has_image)
                    <div class="mb-3">
                        <label class="form-label">Image</label>
                        <input type="file" class="form-control" name="image" accept="image/*">
                        <small class="text-muted">Max 1MB, Recommended: 150x150px</small>
                        <div id="imagePreview" class="mt-2"></div>
                    </div>
                    @endif
                    
                    @if($attribute->is_variant)
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Price Adjustment</label>
                                <input type="number" class="form-control" name="price_adjustment" step="0.01" value="0">
                                <small class="text-muted">Add or subtract from base price</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Stock</label>
                                <input type="number" class="form-control" name="stock" value="0">
                                <small class="text-muted">Leave empty for unlimited</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">SKU</label>
                                <input type="text" class="form-control" name="sku">
                                <small class="text-muted">Unique SKU for this variant</small>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    @if($attribute->type == 'range')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Minimum Value</label>
                                <input type="number" class="form-control" name="min_value" step="0.01">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Maximum Value</label>
                                <input type="number" class="form-control" name="max_value" step="0.01">
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check form-switch mt-2">
                                <input type="checkbox" class="form-check-input" id="is_default" name="is_default" value="1">
                                <label class="form-check-label" for="is_default">Set as Default Value</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-switch mt-2">
                                <input type="checkbox" class="form-check-input" id="is_visible" name="is_visible" value="1" checked>
                                <label class="form-check-label" for="is_visible">Visible on Frontend</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Value</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Value Modal --}}
<div class="modal fade" id="editValueModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Value</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editValueForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_value_id" name="value_id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Value <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="edit_value" name="value" required>
                                <div class="invalid-feedback" id="edit_value-error"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Display Order</label>
                                <input type="number" class="form-control" id="edit_display_order" name="display_order">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="2"></textarea>
                    </div>
                    
                    @if($attribute->type == 'color')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Color Code</label>
                                <input type="color" class="form-control" id="edit_color_code" name="color_code" style="height: 50px;">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Color Name</label>
                                <input type="text" class="form-control" id="edit_color_name" name="color_name">
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    @if($attribute->has_image)
                    <div class="mb-3">
                        <label class="form-label">Image</label>
                        <input type="file" class="form-control" id="edit_image" name="image" accept="image/*">
                        <div id="editImagePreview" class="mt-2"></div>
                        <div class="form-check mt-2">
                            <input type="checkbox" class="form-check-input" id="remove_image" name="remove_image" value="1">
                            <label class="form-check-label text-danger" for="remove_image">Remove current image</label>
                        </div>
                    </div>
                    @endif
                    
                    @if($attribute->is_variant)
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Price Adjustment</label>
                                <input type="number" class="form-control" id="edit_price_adjustment" name="price_adjustment" step="0.01">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Stock</label>
                                <input type="number" class="form-control" id="edit_stock" name="stock">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">SKU</label>
                                <input type="text" class="form-control" id="edit_sku" name="sku">
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    @if($attribute->type == 'range')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Minimum Value</label>
                                <input type="number" class="form-control" id="edit_min_value" name="min_value" step="0.01">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Maximum Value</label>
                                <input type="number" class="form-control" id="edit_max_value" name="max_value" step="0.01">
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check form-switch mt-2">
                                <input type="checkbox" class="form-check-input" id="edit_is_default" name="is_default" value="1">
                                <label class="form-check-label" for="edit_is_default">Set as Default Value</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-switch mt-2">
                                <input type="checkbox" class="form-check-input" id="edit_is_visible" name="is_visible" value="1">
                                <label class="form-check-label" for="edit_is_visible">Visible on Frontend</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Value</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Value Analytics Modal --}}
<div class="modal fade" id="valueAnalyticsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Value Analytics</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="analyticsContent">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-2">Loading analytics...</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    // Image preview for add modal
    $('[name="image"]').on('change', function(e) {
        let file = e.target.files[0];
        if (file) {
            let reader = new FileReader();
            reader.onload = function(e) {
                $('#imagePreview').html('<img src="' + e.target.result + '" style="max-height: 100px; border-radius: 8px;">');
            };
            reader.readAsDataURL(file);
        }
    });
    
    // Add Value Form
    $('#addValueForm').on('submit', function(e) {
        e.preventDefault();
        let btn = $(this).find('button[type="submit"]');
        let originalText = btn.html();
        btn.html('<span class="spinner-border spinner-border-sm me-1"></span> Adding...').prop('disabled', true);
        
        let formData = new FormData(this);
        
        $.ajax({
            url: '{{ route("admin.attributes.values.store", $attribute) }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    $('#addValueModal').modal('hide');
                    Swal.fire({ icon: 'success', title: 'Success!', text: response.message, timer: 1500, showConfirmButton: false })
                        .then(() => location.reload());
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    $.each(errors, function(field, messages) {
                        $('[name="' + field + '"]').addClass('is-invalid');
                        $('#' + field + '-error').text(messages[0]);
                    });
                } else {
                    Swal.fire({ icon: 'error', title: 'Error!', text: xhr.responseJSON?.message || 'Something went wrong.' });
                }
                btn.html(originalText).prop('disabled', false);
            }
        });
    });
    
    // Reset form on modal close
    $('#addValueModal').on('hidden.bs.modal', function() {
        $('#addValueForm')[0].reset();
        $('#imagePreview').html('');
        $('.is-invalid').removeClass('is-invalid');
    });
});

// Edit Value
function editValue(id) {
    $.ajax({
        url: '{{ url("admin/attribute-values") }}/' + id,
        type: 'GET',
        success: function(response) {
            $('#edit_value_id').val(response.id);
            $('#edit_value').val(response.value);
            $('#edit_display_order').val(response.display_order);
            $('#edit_description').val(response.description);
            
            @if($attribute->type == 'color')
            $('#edit_color_code').val(response.color_code);
            $('#edit_color_name').val(response.color_name);
            @endif
            
            @if($attribute->is_variant)
            $('#edit_price_adjustment').val(response.price_adjustment);
            $('#edit_stock').val(response.stock);
            $('#edit_sku').val(response.sku);
            @endif
            
            @if($attribute->type == 'range')
            $('#edit_min_value').val(response.min_value);
            $('#edit_max_value').val(response.max_value);
            @endif
            
            $('#edit_is_default').prop('checked', response.is_default == 1);
            $('#edit_is_visible').prop('checked', response.is_visible == 1);
            
            @if($attribute->has_image && response.image)
                let imageUrl = '{{ Storage::disk('public')->url('attributes/' . $attribute->slug . '/values/') }}' + response.image;
                $('#editImagePreview').html('<img src="' + imageUrl + '" style="max-height: 100px; border-radius: 8px;">');
                $('#remove_image').prop('checked', false);
            @else
                $('#editImagePreview').html('');
            @endif
            
            $('#editValueModal').modal('show');
        }
    });
}

// Update Value
$('#editValueForm').on('submit', function(e) {
    e.preventDefault();
    let id = $('#edit_value_id').val();
    let btn = $(this).find('button[type="submit"]');
    let originalText = btn.html();
    btn.html('<span class="spinner-border spinner-border-sm me-1"></span> Updating...').prop('disabled', true);
    
    let formData = new FormData(this);
    
    $.ajax({
        url: '{{ url("admin/attribute-values") }}/' + id,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.success) {
                $('#editValueModal').modal('hide');
                Swal.fire({ icon: 'success', title: 'Updated!', text: response.message, timer: 1500, showConfirmButton: false })
                    .then(() => location.reload());
            }
        },
        error: function(xhr) {
            if (xhr.status === 422) {
                let errors = xhr.responseJSON.errors;
                $.each(errors, function(field, messages) {
                    $('#edit_' + field).addClass('is-invalid');
                    $('#edit_' + field + '-error').text(messages[0]);
                });
            } else {
                Swal.fire({ icon: 'error', title: 'Error!', text: 'Something went wrong.' });
            }
            btn.html(originalText).prop('disabled', false);
        }
    });
});

// Toggle Default
$(document).on('change', '.toggle-default', function() {
    let id = $(this).data('id');
    let isChecked = $(this).prop('checked');
    
    $.ajax({
        url: '{{ url("admin/attribute-values") }}/' + id + '/toggle-default',
        type: 'POST',
        data: { _token: '{{ csrf_token() }}' },
        success: function(response) {
            if (response.success) {
                location.reload();
            }
        },
        error: function() {
            $(this).prop('checked', !isChecked);
            Swal.fire({ icon: 'error', title: 'Error!', text: 'Failed to update default status.' });
        }
    });
});

// Toggle Visibility
$(document).on('change', '.toggle-visibility', function() {
    let id = $(this).data('id');
    let isChecked = $(this).prop('checked');
    
    $.ajax({
        url: '{{ url("admin/attribute-values") }}/' + id + '/toggle-visibility',
        type: 'POST',
        data: { _token: '{{ csrf_token() }}' },
        success: function(response) {
            if (response.success) {
                Swal.fire({ icon: 'success', title: 'Updated!', text: response.message, timer: 1500, showConfirmButton: false });
            }
        },
        error: function() {
            $(this).prop('checked', !isChecked);
            Swal.fire({ icon: 'error', title: 'Error!', text: 'Failed to update visibility.' });
        }
    });
});

// Delete Value
function deleteValue(id) {
    Swal.fire({
        title: 'Delete Value?',
        text: "Are you sure you want to delete this value?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '{{ url("admin/attribute-values") }}/' + id,
                type: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({ icon: 'success', title: 'Deleted!', text: response.message, timer: 1500, showConfirmButton: false })
                            .then(() => location.reload());
                    } else {
                        Swal.fire({ icon: 'error', title: 'Cannot Delete!', text: response.message });
                    }
                }
            });
        }
    });
}

// View Value Analytics
function viewValueAnalytics(id) {
    $('#valueAnalyticsModal').modal('show');
    $('#analyticsContent').html('<div class="text-center py-5"><div class="spinner-border text-primary" role="status"></div><p class="mt-2">Loading analytics...</p></div>');
    
    $.ajax({
        url: '{{ url("admin/attribute-values") }}/' + id + '/analytics',
        type: 'GET',
        success: function(response) {
            let html = `
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card bg-primary text-white">
                            <div class="card-body text-center">
                                <h5>Total Views</h5>
                                <h2>${response.view_count}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-success text-white">
                            <div class="card-body text-center">
                                <h5>Total Orders</h5>
                                <h2>${response.order_count}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-warning text-dark">
                            <div class="card-body text-center">
                                <h5>Revenue Generated</h5>
                                <h2>$${response.total_revenue}</h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <h6>Performance Trend</h6>
                        <canvas id="valuePerformanceChart" height="200"></canvas>
                    </div>
                </div>
            `;
            $('#analyticsContent').html(html);
            
            // Render chart
            new Chart(document.getElementById('valuePerformanceChart'), {
                type: 'line',
                data: {
                    labels: response.chart_labels,
                    datasets: [{
                        label: 'Views',
                        data: response.chart_views,
                        borderColor: '#0d6efd',
                        backgroundColor: 'rgba(13, 110, 253, 0.1)',
                        fill: true
                    }, {
                        label: 'Orders',
                        data: response.chart_orders,
                        borderColor: '#28a745',
                        backgroundColor: 'rgba(40, 167, 69, 0.1)',
                        fill: true
                    }]
                }
            });
        }
    });
}

// Export Values
function exportValues() {
    window.location.href = '{{ route("admin.attributes.values.export", $attribute) }}';
}

// Reorder Value
function reorderValue(id, direction) {
    $.ajax({
        url: '{{ url("admin/attribute-values") }}/' + id + '/reorder',
        type: 'POST',
        data: { _token: '{{ csrf_token() }}', direction: direction },
        success: function(response) {
            if (response.success) {
                location.reload();
            }
        }
    });
}
</script>
@endpush