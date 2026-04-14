{{-- resources/views/admin/pages/attribute-groups/edit.blade.php --}}
@extends('management.layouts.app')

@section('title', 'Edit Attribute Group - ' . $group->name)

@section('content')
    <div class="page-content">
        <div class="page-container">
            <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                <div class="flex-grow-1">
                    <h4 class="fs-18 text-uppercase fw-bold mb-0">Edit Attribute Group: {{ $group->name }}</h4>
                </div>
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.attribute-groups.index') }}">Attribute Groups</a></li>
                        <li class="breadcrumb-item active">Edit Group</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header border-bottom border-dashed">
                            <h4 class="card-title">Attribute Group Information</h4>
                            <p class="text-muted mb-0">Update attribute group information</p>
                        </div>
                        <div class="card-body">
                            <form id="groupForm" method="POST" action="{{ route('admin.attribute-groups.update', $group->id) }}">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Group Name <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light">
                                                    <i class="ti ti-layout-sidebar"></i>
                                                </span>
                                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $group->name) }}">
                                            </div>
                                            <div class="invalid-feedback" id="name-error"></div>
                                            <small class="text-muted">
                                                <i class="ti ti-link"></i> URL slug: <span id="slug-preview" class="text-primary">{{ $group->slug }}</span>
                                            </small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="icon" class="form-label">Icon Class</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light">
                                                    <i class="ti ti-icon"></i>
                                                </span>
                                                <input type="text" class="form-control" id="icon" name="icon" value="{{ old('icon', $group->icon) }}" placeholder="ti ti-brand">
                                            </div>
                                            <div class="invalid-feedback" id="icon-error"></div>
                                            <small class="text-muted">e.g., ti ti-brand, ti ti-device-mobile</small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="order" class="form-label">Display Order</label>
                                            <input type="number" class="form-control" id="order" name="order" value="{{ old('order', $group->order) }}">
                                            <div class="invalid-feedback" id="order-error"></div>
                                            <small class="text-muted">Lower numbers appear first</small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="position" class="form-label">Display Position</label>
                                            <select class="form-select" id="position" name="position">
                                                <option value="top" {{ $group->position == 'top' ? 'selected' : '' }}>Top (Above Product Details)</option>
                                                <option value="sidebar" {{ $group->position == 'sidebar' ? 'selected' : '' }}>Sidebar (Filter Area)</option>
                                                <option value="bottom" {{ $group->position == 'bottom' ? 'selected' : '' }}>Bottom (Below Product Details)</option>
                                            </select>
                                            <div class="invalid-feedback" id="position-error"></div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="description" class="form-label">Description</label>
                                            <textarea class="form-control" id="description" name="description" rows="3"
                                                placeholder="Describe what this attribute group is for">{{ old('description', $group->description) }}</textarea>
                                            <div class="invalid-feedback" id="description-error"></div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label">Display Settings</label>
                                            <div class="d-flex flex-wrap gap-3">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="status" name="status" value="1" {{ $group->status ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="status">
                                                        <i class="ti ti-circle-check"></i> Active
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="is_collapsible" name="is_collapsible" value="1" {{ $group->is_collapsible ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="is_collapsible">
                                                        <i class="ti ti-chevron-down"></i> Collapsible
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="is_open_by_default" name="is_open_by_default" value="1" {{ $group->is_open_by_default ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="is_open_by_default">
                                                        <i class="ti ti-eye"></i> Open by Default
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2 mt-4">
                                    <a href="{{ route('admin.attribute-groups.index') }}" class="btn btn-danger">
                                        <i class="ti ti-x me-1"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary" id="submitBtn">
                                        <i class="ti ti-edit me-1"></i> Update Group
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    let formSubmitting = false;

    $('#name').on('keyup', function() {
        let slug = $(this).val().toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
        if (slug) $('#slug-preview').text(slug);
        $(this).removeClass('is-invalid');
        $('#name-error').text('');
    });

    $('input, select, textarea').on('input change', function() {
        $(this).removeClass('is-invalid');
        $('#' + $(this).attr('name') + '-error').text('');
    });

    $('#groupForm').on('submit', function(e) {
        e.preventDefault();
        if (formSubmitting) return false;

        let isValid = true;
        let nameValue = $('#name').val().trim();

        if (!nameValue) {
            $('#name').addClass('is-invalid');
            $('#name-error').text('Group name is required');
            isValid = false;
        }

        if (!isValid) {
            $('html, body').animate({ scrollTop: $('.is-invalid:first').offset().top - 100 }, 500);
            return false;
        }

        formSubmitting = true;
        let btn = $('#submitBtn');
        let originalText = btn.html();
        btn.html('<span class="spinner-border spinner-border-sm me-1"></span> Updating...');
        btn.prop('disabled', true);

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            success: function(response) {
                if (response.success) {
                    Swal.fire({ icon: 'success', title: 'Success!', text: response.message, timer: 2000, showConfirmButton: false })
                        .then(() => { window.location.href = '{{ route("admin.attribute-groups.index") }}'; });
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    $.each(errors, function(field, messages) {
                        $('#' + field).addClass('is-invalid');
                        $('#' + field + '-error').text(messages[0]);
                    });
                    $('html, body').animate({ scrollTop: $('.is-invalid:first').offset().top - 100 }, 500);
                } else {
                    Swal.fire({ icon: 'error', title: 'Error!', text: xhr.responseJSON?.message || 'Something went wrong.', confirmButtonColor: '#d33' });
                }
            },
            complete: function() {
                formSubmitting = false;
                btn.html(originalText);
                btn.prop('disabled', false);
            }
        });
    });
});
</script>
@endpush