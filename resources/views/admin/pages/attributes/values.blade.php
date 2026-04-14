{{-- resources/views/admin/pages/attributes/values.blade.php --}}
@extends('management.layouts.app')

@section('title', 'Manage Values - ' . $attribute->name)

@section('content')
<div class="page-content">
    <div class="page-container">
        <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
            <div class="flex-grow-1">
                <h4 class="fs-18 text-uppercase fw-bold mb-0">Manage Values: {{ $attribute->name }}</h4>
                <p class="text-muted mb-0">{{ $attribute->type_label }} - {{ $attribute->description ?? 'No description' }}</p>
            </div>
            <div class="text-end">
                <ol class="breadcrumb m-0 py-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.attributes.index') }}">Attributes</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.attributes.show', $attribute->id) }}">{{ $attribute->name }}</a></li>
                    <li class="breadcrumb-item active">Manage Values</li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header border-bottom border-dashed">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title mb-0">
                                    <i class="ti ti-list"></i> Predefined Values
                                </h5>
                                <p class="text-muted small mb-0">Manage options for this {{ $attribute->type_label }} attribute</p>
                            </div>
                            <button type="button" class="btn btn-primary btn-sm" id="addValueBtn">
                                <i class="ti ti-plus"></i> Add Value
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        
                        {{-- Info Alert --}}
                        <div class="alert alert-info mb-3">
                            <div class="d-flex">
                                <i class="ti ti-info-circle me-2 fs-5"></i>
                                <div>
                                    <strong>About {{ $attribute->type_label }} Values</strong><br>
                                    @if($attribute->type == 'select')
                                        Customers can select ONE option from this list.
                                    @elseif($attribute->type == 'multiselect')
                                        Customers can select MULTIPLE options from this list.
                                    @elseif($attribute->type == 'radio')
                                        Customers can select ONE option from this list (displayed as radio buttons).
                                    @endif
                                    You can drag and drop to reorder values. The default value will be pre-selected.
                                </div>
                            </div>
                        </div>

                        {{-- Values Table --}}
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="valuesTable">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 40px;">#</th>
                                        <th>Value</th>
                                        <th>Label (Display Name)</th>
                                        <th style="width: 80px;">Color</th>
                                        <th style="width: 100px;">Price Adj.</th>
                                        <th style="width: 100px;">Weight Adj.</th>
                                        <th style="width: 80px;">Default</th>
                                        <th style="width: 80px;">Status</th>
                                        <th style="width: 80px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="values-tbody">
                                    @forelse($attribute->values as $index => $value)
                                    <tr data-id="{{ $value->id }}" data-order="{{ $value->order }}">
                                        <td class="drag-handle text-center" style="cursor: move;">
                                            <i class="ti ti-grip-vertical text-muted"></i>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control form-control-sm value-input" 
                                                   data-id="{{ $value->id }}" 
                                                   data-field="value" 
                                                   value="{{ $value->value }}">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control form-control-sm" 
                                                   data-id="{{ $value->id }}" 
                                                   data-field="label" 
                                                   value="{{ $value->label }}">
                                        </td>
                                        <td>
                                            <input type="color" class="form-control form-control-sm color-input" 
                                                   data-id="{{ $value->id }}" 
                                                   data-field="color_code" 
                                                   value="{{ $value->color_code }}" 
                                                   style="height: 38px;">
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" class="form-control form-control-sm" 
                                                   data-id="{{ $value->id }}" 
                                                   data-field="price_adjustment" 
                                                   value="{{ $value->price_adjustment }}">
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" class="form-control form-control-sm" 
                                                   data-id="{{ $value->id }}" 
                                                   data-field="weight_adjustment" 
                                                   value="{{ $value->weight_adjustment }}">
                                        </td>
                                        <td class="text-center">
                                            <input type="radio" name="default_value" 
                                                   class="form-check-input default-radio" 
                                                   value="{{ $value->id }}" 
                                                   {{ $value->is_default ? 'checked' : '' }}>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-check form-switch d-inline-block">
                                                <input type="checkbox" class="form-check-input status-toggle" 
                                                       data-id="{{ $value->id }}" 
                                                       data-field="status" 
                                                       {{ $value->status ? 'checked' : '' }}>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-danger delete-value" 
                                                    data-id="{{ $value->id }}" 
                                                    data-name="{{ $value->value }}">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr id="no-records-row">
                                        <td colspan="9" class="text-center py-5">
                                            <i class="ti ti-list-off" style="font-size: 48px; opacity: 0.5;"></i>
                                            <h5 class="mt-3">No Values Added Yet</h5>
                                            <p class="text-muted">Click "Add Value" to create options for this attribute.</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3 text-muted small">
                            <i class="ti ti-info-circle"></i> 
                            Drag the <i class="ti ti-grip-vertical"></i> icon to reorder values. The order determines how they appear to customers.
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="badge bg-info">{{ $attribute->values->count() }} total values</span>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.attributes.edit', $attribute->id) }}" class="btn btn-secondary">
                                    <i class="ti ti-arrow-left me-1"></i> Back to Attribute
                                </a>
                                <a href="{{ route('admin.attributes.index') }}" class="btn btn-primary">
                                    <i class="ti ti-list me-1"></i> All Attributes
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Add Value Modal --}}
<div class="modal fade" id="addValueModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ti ti-plus"></i> Add New Value</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addValueForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Value <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="value" placeholder="e.g., red, intel-i7, large" required>
                        <small class="text-muted">The actual value stored in database</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Label (Display Name)</label>
                        <input type="text" class="form-control" name="label" placeholder="e.g., Red, Intel i7, Large">
                        <small class="text-muted">Optional. If empty, value will be displayed</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Color Code</label>
                        <input type="color" class="form-control" name="color_code" style="height: 45px;">
                        <small class="text-muted">For color attributes only</small>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Price Adjustment</label>
                                <input type="number" step="0.01" class="form-control" name="price_adjustment" value="0">
                                <small class="text-muted">Additional cost for this option</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Weight Adjustment</label>
                                <input type="number" step="0.01" class="form-control" name="weight_adjustment" value="0">
                                <small class="text-muted">Additional weight (kg)</small>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="is_default" value="1" id="is_default">
                            <label class="form-check-label" for="is_default">Set as default value</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="status" value="1" checked id="status">
                            <label class="form-check-label" for="status">Active</label>
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

{{-- Loading Overlay --}}
<div id="loadingOverlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; justify-content: center; align-items: center;">
    <div class="spinner-border text-light" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- SortableJS for drag and drop -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

<script>
$(document).ready(function() {
    let attributeId = {{ $attribute->id }};
    let currentOrder = [];

    // Initialize Sortable for drag and drop reordering
    const tbody = document.getElementById('values-tbody');
    if (tbody && $('#values-tbody tr').length > 1) {
        new Sortable(tbody, {
            handle: '.drag-handle',
            animation: 150,
            onEnd: function() {
                saveOrder();
            }
        });
    }

    // Save order after drag and drop
    function saveOrder() {
        let orderData = [];
        $('#values-tbody tr').each(function(index) {
            let valueId = $(this).data('id');
            if (valueId) {
                orderData.push({
                    id: valueId,
                    order: index
                });
            }
        });

        if (orderData.length > 0) {
            $.ajax({
                url: '{{ route("admin.attributes.values.reorder") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    values: orderData
                },
                success: function(response) {
                    if (response.success) {
                        // Update the order attributes
                        orderData.forEach(item => {
                            $(`tr[data-id="${item.id}"]`).attr('data-order', item.order);
                        });
                    }
                }
            });
        }
    }

    // Add Value Modal
    $('#addValueBtn').on('click', function() {
        $('#addValueForm')[0].reset();
        $('#addValueModal').modal('show');
    });

    // Submit Add Value Form
    $('#addValueForm').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: '{{ route("admin.attributes.values.store", $attribute->id) }}',
            type: 'POST',
            data: $(this).serialize(),
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            beforeSend: function() {
                $('#loadingOverlay').show();
            },
            success: function(response) {
                if (response.success) {
                    $('#addValueModal').modal('hide');
                    
                    // Remove no records row if exists
                    if ($('#no-records-row').length) {
                        $('#no-records-row').remove();
                    }
                    
                    // Append new row
                    let newRow = `
                        <tr data-id="${response.value.id}" data-order="${response.value.order}">
                            <td class="drag-handle text-center" style="cursor: move;">
                                <i class="ti ti-grip-vertical text-muted"></i>
                            </td>
                            <td>
                                <input type="text" class="form-control form-control-sm value-input" 
                                       data-id="${response.value.id}" data-field="value" 
                                       value="${response.value.value}">
                            </td>
                            <td>
                                <input type="text" class="form-control form-control-sm" 
                                       data-id="${response.value.id}" data-field="label" 
                                       value="${response.value.label || ''}">
                            </td>
                            <td>
                                <input type="color" class="form-control form-control-sm color-input" 
                                       data-id="${response.value.id}" data-field="color_code" 
                                       value="${response.value.color_code || '#000000'}" style="height: 38px;">
                            </td>
                            <td>
                                <input type="number" step="0.01" class="form-control form-control-sm" 
                                       data-id="${response.value.id}" data-field="price_adjustment" 
                                       value="${response.value.price_adjustment || 0}">
                            </td>
                            <td>
                                <input type="number" step="0.01" class="form-control form-control-sm" 
                                       data-id="${response.value.id}" data-field="weight_adjustment" 
                                       value="${response.value.weight_adjustment || 0}">
                            </td>
                            <td class="text-center">
                                <input type="radio" name="default_value" class="form-check-input default-radio" 
                                       value="${response.value.id}">
                            </td>
                            <td class="text-center">
                                <div class="form-check form-switch d-inline-block">
                                    <input type="checkbox" class="form-check-input status-toggle" 
                                           data-id="${response.value.id}" data-field="status" checked>
                                </div>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-danger delete-value" 
                                        data-id="${response.value.id}" data-name="${response.value.value}">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                    $('#values-tbody').append(newRow);
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Added!',
                        text: 'Value added successfully.',
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    let errorMsg = Object.values(errors).flat().join('\n');
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        text: errorMsg,
                        confirmButtonColor: '#d33'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Failed to add value.',
                        confirmButtonColor: '#d33'
                    });
                }
            },
            complete: function() {
                $('#loadingOverlay').hide();
            }
        });
    });

    // Auto-save on input change (debounced)
    let debounceTimer;
    $(document).on('input', '.value-input, .color-input, [data-field="label"], [data-field="price_adjustment"], [data-field="weight_adjustment"]', function() {
        clearTimeout(debounceTimer);
        let $this = $(this);
        let valueId = $this.data('id');
        let field = $this.data('field');
        let value = $this.val();
        
        debounceTimer = setTimeout(function() {
            $.ajax({
                url: '{{ url("admin/attributes/values") }}/' + valueId,
                type: 'PUT',
                data: {
                    _token: '{{ csrf_token() }}',
                    field: field,
                    value: value
                },
                success: function(response) {
                    if (response.success) {
                        // Show subtle success indicator
                        $this.css('border-color', '#28a745');
                        setTimeout(() => {
                            $this.css('border-color', '');
                        }, 1000);
                    }
                },
                error: function() {
                    $this.css('border-color', '#dc3545');
                    setTimeout(() => {
                        $this.css('border-color', '');
                    }, 1000);
                }
            });
        }, 500);
    });

    // Toggle status
    $(document).on('change', '.status-toggle', function() {
        let $this = $(this);
        let valueId = $this.data('id');
        let status = $this.prop('checked') ? 1 : 0;
        
        $.ajax({
            url: '{{ url("admin/attributes/values") }}/' + valueId,
            type: 'PUT',
            data: {
                _token: '{{ csrf_token() }}',
                field: 'status',
                value: status
            },
            success: function(response) {
                if (response.success) {
                    // Success
                }
            }
        });
    });

    // Set default value
    $(document).on('change', '.default-radio', function() {
        let $this = $(this);
        let valueId = $this.val();
        
        // First, uncheck all others
        $('.default-radio').prop('checked', false);
        $this.prop('checked', true);
        
        $.ajax({
            url: '{{ url("admin/attributes/values") }}/' + valueId,
            type: 'PUT',
            data: {
                _token: '{{ csrf_token() }}',
                field: 'is_default',
                value: 1
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Default Set',
                        text: 'Default value updated successfully.',
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            }
        });
    });

    // Delete value
    $(document).on('click', '.delete-value', function() {
        let $this = $(this);
        let valueId = $this.data('id');
        let valueName = $this.data('name');
        
        Swal.fire({
            title: 'Delete Value?',
            text: `Are you sure you want to delete "${valueName}"? This cannot be undone.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ url("admin/attributes/values") }}/' + valueId,
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(response) {
                        if (response.success) {
                            $this.closest('tr').remove();
                            
                            if ($('#values-tbody tr').length === 0) {
                                $('#values-tbody').html(`
                                    <tr id="no-records-row">
                                        <td colspan="9" class="text-center py-5">
                                            <i class="ti ti-list-off" style="font-size: 48px; opacity: 0.5;"></i>
                                            <h5 class="mt-3">No Values Added Yet</h5>
                                            <p class="text-muted">Click "Add Value" to create options for this attribute.</p>
                                        </td>
                                    </tr>
                                `);
                            }
                            
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: response.message,
                                timer: 1500,
                                showConfirmButton: false
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Cannot Delete!',
                                text: response.message,
                                confirmButtonColor: '#d33'
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: xhr.responseJSON?.message || 'Failed to delete value.',
                            confirmButtonColor: '#d33'
                        });
                    }
                });
            }
        });
    });
});
</script>
@endpush

@push('styles')
<style>
    .drag-handle {
        cursor: move;
        vertical-align: middle;
    }
    .drag-handle:hover {
        background-color: #f8f9fa;
    }
    .table tbody tr:hover {
        background-color: #f8f9fa;
    }
    .value-input:focus, .color-input:focus {
        outline: none;
        box-shadow: 0 0 0 2px rgba(13, 110, 253, 0.25);
    }
    .status-toggle {
        width: 40px;
        height: 20px;
    }
</style>
@endpush