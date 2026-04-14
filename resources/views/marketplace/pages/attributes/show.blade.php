{{-- resources/views/marketplace/pages/attributes/show.blade.php --}}
@extends('management.layouts.app')

@section('title', 'Attribute Details - ' . $attribute->name)

@section('content')
<div class="page-content">
    <div class="page-container">
        <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
            <div class="flex-grow-1">
                <h4 class="fs-18 text-uppercase fw-bold mb-0">Attribute Details: {{ $attribute->name }}</h4>
                <p class="text-muted mb-0">{{ $attribute->type_label }} attribute</p>
            </div>
            <div class="text-end">
                <ol class="breadcrumb m-0 py-0">
                    <li class="breadcrumb-item"><a href="{{ route('vendor.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('vendor.attributes.index') }}">Attributes</a></li>
                    <li class="breadcrumb-item active">{{ $attribute->name }}</li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4">
                {{-- Attribute Preview Card --}}
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ti ti-info-circle"></i> Attribute Preview</h5>
                    </div>
                    <div class="card-body text-center">
                        <div class="attribute-icon mb-3 mx-auto">
                            @if($attribute->icon)
                                <i class="{{ $attribute->icon }} fs-1 text-primary"></i>
                            @else
                                <i class="ti ti-input fs-1 text-primary"></i>
                            @endif
                        </div>
                        <h3>{{ $attribute->name }}</h3>
                        <code class="fs-6">{{ $attribute->slug }}</code>
                        <div class="mt-2">
                            <span class="badge bg-info">{{ $attribute->type_label }}</span>
                        </div>
                        <div class="mt-2">
                            @if($attribute->is_required)
                                <span class="badge bg-danger"><i class="ti ti-asterisk"></i> Required</span>
                            @endif
                            @if($attribute->is_filterable)
                                <span class="badge bg-success"><i class="ti ti-filter"></i> Filterable</span>
                            @endif
                            @if($attribute->is_searchable)
                                <span class="badge bg-info"><i class="ti ti-search"></i> Searchable</span>
                            @endif
                            @if($attribute->is_comparable)
                                <span class="badge bg-primary"><i class="ti ti-chart-line"></i> Comparable</span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Attribute Information Card --}}
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ti ti-info-circle"></i> Attribute Information</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <td width="120"><strong>ID:</strong></td>
                                <td>#{{ $attribute->id }}</div></div></td>
                            </tr>
                            <tr>
                                <td><strong>Name:</strong></td>
                                <td>{{ $attribute->name }}</div></div></td>
                            </tr>
                            <tr>
                                <td><strong>Slug:</strong></td>
                                <td><code>{{ $attribute->slug }}</code></div></div></td>
                            </tr>
                            <tr>
                                <td><strong>Type:</strong></td>
                                <td><span class="badge bg-info">{{ $attribute->type_label }}</span></div></div></td>
                            </tr>
                            @if($attribute->unit)
                            <tr>
                                <td><strong>Unit:</strong></td>
                                <td>{{ $attribute->unit }}</div></div></td>
                            </tr>
                            @endif
                            @if($attribute->group)
                            <tr>
                                <td><strong>Group:</strong></td>
                                <td>{{ $attribute->group->name }}</div></div></td>
                            </tr>
                            @endif
                            @if($attribute->default_value)
                            <tr>
                                <td><strong>Default Value:</strong></td>
                                <td>{{ $attribute->default_value }}</div></div></td>
                            </tr>
                            @endif
                            @if($attribute->placeholder)
                            <tr>
                                <td><strong>Placeholder:</strong></td>
                                <td>{{ $attribute->placeholder }}</div></div></td>
                            </tr>
                            @endif
                            @if($attribute->help_text)
                            <tr>
                                <td><strong>Help Text:</strong></td>
                                <td>{{ $attribute->help_text }}</div></div></td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>

                {{-- Associated Categories Card --}}
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ti ti-folder"></i> Associated Categories</h5>
                    </div>
                    <div class="card-body">
                        @if($attribute->categories->count() > 0)
                            <div class="d-flex flex-wrap gap-1">
                                @foreach($attribute->categories as $category)
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
                @if($attribute->description)
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ti ti-align-left"></i> Description</h5>
                    </div>
                    <div class="card-body">
                        <p>{{ $attribute->description }}</p>
                    </div>
                </div>
                @endif

                {{-- Validation Rules Card --}}
                @if($attribute->min_value || $attribute->max_value || $attribute->max_length || $attribute->regex_pattern)
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ti ti-checklist"></i> Validation Rules</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @if($attribute->min_value)
                                <div class="col-md-6 mb-2">
                                    <span class="badge bg-info"><i class="ti ti-arrow-up"></i> Min: {{ $attribute->min_value }}</span>
                                </div>
                            @endif
                            @if($attribute->max_value)
                                <div class="col-md-6 mb-2">
                                    <span class="badge bg-info"><i class="ti ti-arrow-down"></i> Max: {{ $attribute->max_value }}</span>
                                </div>
                            @endif
                            @if($attribute->max_length)
                                <div class="col-md-6 mb-2">
                                    <span class="badge bg-info"><i class="ti ti-text-size"></i> Max Length: {{ $attribute->max_length }}</span>
                                </div>
                            @endif
                            @if($attribute->regex_pattern)
                                <div class="col-md-12 mb-2">
                                    <span class="badge bg-secondary"><i class="ti ti-code"></i> Regex: <code>{{ $attribute->regex_pattern }}</code></span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                {{-- Predefined Values Card (for select/multiselect/radio/color) --}}
                @if(in_array($attribute->type, ['select', 'multiselect', 'radio', 'color']) && $values->count() > 0)
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ti ti-list"></i> Available Values ({{ $values->count() }})</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($values as $value)
                                <div class="col-md-6 mb-2">
                                    <div class="d-flex align-items-center gap-2 p-2 bg-light rounded">
                                        @if($attribute->type == 'color' && $value->color_code)
                                            <div style="width: 30px; height: 30px; background-color: {{ $value->color_code }}; border-radius: 50%; border: 1px solid #ddd;"></div>
                                        @elseif($attribute->type == 'color')
                                            <div style="width: 30px; height: 30px; background-color: #ddd; border-radius: 50%; border: 1px solid #ddd;"></div>
                                        @else
                                            <i class="ti ti-tag text-primary"></i>
                                        @endif
                                        <div>
                                            <div class="fw-semibold">{{ $value->display_name }}</div>
                                            @if($value->price_adjustment != 0)
                                                <small class="text-muted">Price: +${{ number_format($value->price_adjustment, 2) }}</small>
                                            @endif
                                            @if($value->weight_adjustment != 0)
                                                <small class="text-muted"> | Weight: +{{ $value->weight_adjustment }} kg</small>
                                            @endif
                                            @if($value->is_default)
                                                <span class="badge bg-warning text-dark ms-1">Default</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if($attribute->type == 'select' || $attribute->type == 'multiselect' || $attribute->type == 'radio')
                            <div class="mt-3 text-muted small">
                                <i class="ti ti-info-circle"></i> 
                                @if($attribute->type == 'select')
                                    Customers can select ONE option from this list.
                                @elseif($attribute->type == 'multiselect')
                                    Customers can select MULTIPLE options from this list.
                                @else
                                    Customers can select ONE option from this list (radio buttons).
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
                @elseif(in_array($attribute->type, ['select', 'multiselect', 'radio', 'color']) && $values->count() == 0)
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ti ti-list"></i> Available Values</h5>
                    </div>
                    <div class="card-body text-center py-4">
                        <i class="ti ti-list-off" style="font-size: 48px; opacity: 0.5;"></i>
                        <p class="text-muted mt-2">No values available for this attribute yet.</p>
                        <a href="{{ route('vendor.attributes.value-request.create') }}?attribute_id={{ $attribute->id }}" class="btn btn-sm btn-outline-primary">
                            <i class="ti ti-plus"></i> Request a Value
                        </a>
                    </div>
                </div>
                @endif

                {{-- CSS Classes Card --}}
                @if($attribute->input_class || $attribute->wrapper_class)
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ti ti-code"></i> CSS Classes</h5>
                    </div>
                    <div class="card-body">
                        @if($attribute->input_class)
                            <div><strong>Input Class:</strong> <code>{{ $attribute->input_class }}</code></div>
                        @endif
                        @if($attribute->wrapper_class)
                            <div class="mt-2"><strong>Wrapper Class:</strong> <code>{{ $attribute->wrapper_class }}</code></div>
                        @endif
                    </div>
                </div>
                @endif

                {{-- Action Buttons --}}
                <div class="card">
                    <div class="card-footer d-flex justify-content-between gap-2">
                        <a href="{{ route('vendor.attributes.index') }}" class="btn btn-secondary">
                            <i class="ti ti-arrow-left me-1"></i> Back to Attributes
                        </a>
                        <div>
                            @if(in_array($attribute->type, ['select', 'multiselect', 'radio', 'color']))
                                <a href="{{ route('vendor.attributes.value-request.create') }}?attribute_id={{ $attribute->id }}" class="btn btn-outline-info">
                                    <i class="ti ti-plus"></i> Request New Value
                                </a>
                            @endif
                            @if(!$attribute->is_popular && !$attribute->is_featured)
                                <a href="{{ route('vendor.attributes.request.create') }}?suggest={{ $attribute->id }}" class="btn btn-outline-primary ms-2">
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
    .attribute-icon {
        width: 80px;
        height: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: rgba(13, 110, 253, 0.1);
        border-radius: 50%;
    }
    .bg-light {
        background-color: #f8f9fa !important;
    }
    .badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }
</style>
@endpush