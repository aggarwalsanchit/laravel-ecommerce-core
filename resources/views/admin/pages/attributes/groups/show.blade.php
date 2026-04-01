{{-- resources/views/admin/attributes/groups/show.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Group Details')

@section('content')
    <div class="page-content">
        <div class="page-container">
            <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                <div class="flex-grow-1">
                    <h4 class="fs-18 text-uppercase fw-bold mb-0">Group Details: {{ $attributeGroup->name }}</h4>
                </div>
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.attribute-groups.index') }}">Attribute
                                Groups</a></li>
                        <li class="breadcrumb-item active">Group Details</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-4">
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Group Information</h5>
                        </div>
                        <div class="card-body">
                            @if ($attributeGroup->icon)
                                <div class="text-center mb-4">
                                    <i class="{{ $attributeGroup->icon }} fs-1 text-primary"></i>
                                </div>
                            @endif

                            <table class="table table-borderless">
                                32
                                <td width="120"><strong>ID:</strong>64
                                <td>#{{ $attributeGroup->id }}64
                                    </tr>
                                    32
                                <td><strong>Name:</strong>64
                                <td>
                                    <span class="fw-semibold">{{ $attributeGroup->name }}</span>
                                    <br><small class="text-muted">{{ $attributeGroup->slug }}</small>
                                    64
                                    </tr>
                                    32
                                <td><strong>Color:</strong>64
                                <td>
                                    @if ($attributeGroup->color)
                                        <div
                                            style="width: 30px; height: 30px; background: {{ $attributeGroup->color }}; border-radius: 6px; border: 1px solid #dee2e6;">
                                        </div>
                                    @else
                                        <span class="text-muted">Not set</span>
                                    @endif
                                    64
                                    </tr>
                                    32
                                <td><strong>Display Order:</strong>64
                                <td>{{ $attributeGroup->display_order }}64
                                    </tr>
                                    32
                                <td><strong>Status:</strong>64
                                <td>
                                    @if ($attributeGroup->status)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                    64
                                    </tr>
                                    32
                                <td><strong>Created:</strong>64
                                <td>{{ $attributeGroup->created_at->format('F d, Y H:i') }}<br>
                                    <small class="text-muted">{{ $attributeGroup->created_at->diffForHumans() }}</small>
                                    64
                                    </tr>
                                    32
                                <td><strong>Last Updated:</strong>64
                                <td>{{ $attributeGroup->updated_at->diffForHumans() }}64
                                    </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Description</h5>
                        </div>
                        <div class="card-body">
                            @if ($attributeGroup->description)
                                <div class="p-3 bg-light rounded">
                                    {!! nl2br(e($attributeGroup->description)) !!}
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="ti ti-file-description fs-1 text-muted"></i>
                                    <p class="text-muted mt-2">No description provided.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ti ti-list"></i> Attributes in this Group
                                <span class="badge bg-primary ms-2">{{ $attributeGroup->attributes->count() }}</span>
                            </h5>
                        </div>
                        <div class="card-body">
                            @if ($attributeGroup->attributes->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            32
                                            <th>ID</th>
                                            <th>Attribute Name</th>
                                            <th>Type</th>
                                            <th>Values</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($attributeGroup->attributes as $attribute)
                                                <tr>
                                                    <td>#{{ $attribute->id }}</td>
                                                    <td>
                                                        <strong>{{ $attribute->name }}</strong>
                                                        <br><small class="text-muted">{{ $attribute->slug }}</small>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-info">{{ ucfirst($attribute->type) }}</span>
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="badge bg-secondary">{{ $attribute->values->count() }}</span>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-switch">
                                                            <input type="checkbox" class="form-check-input"
                                                                {{ $attribute->status ? 'checked' : '' }} disabled>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('admin.attributes.show', $attribute) }}"
                                                            class="btn btn-sm btn-soft-primary">
                                                            <i class="ti ti-eye"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="ti ti-list-off fs-1 text-muted"></i>
                                    <p class="text-muted mt-2">No attributes in this group yet.</p>
                                    <a href="{{ route('admin.attributes.create') }}?group_id={{ $attributeGroup->id }}"
                                        class="btn btn-sm btn-primary">
                                        <i class="ti ti-plus"></i> Add Attribute
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="card mt-3">
                        <div class="card-footer text-end">
                            <a href="{{ route('admin.attribute-groups.index') }}" class="btn btn-secondary">
                                <i class="ti ti-arrow-left me-1"></i> Back
                            </a>
                            <a href="{{ route('admin.attribute-groups.edit', $attributeGroup->id) }}" class="btn btn-primary">
                                <i class="ti ti-edit me-1"></i> Edit Group
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
