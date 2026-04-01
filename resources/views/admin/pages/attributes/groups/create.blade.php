{{-- resources/views/admin/attributes/groups/create.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Create Attribute Group')

@section('content')
    <div class="page-content">
        <div class="page-container">
            <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                <div class="flex-grow-1">
                    <h4 class="fs-18 text-uppercase fw-bold mb-0">Create Attribute Group</h4>
                </div>
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.attribute-groups.index') }}">Attribute
                                Groups</a></li>
                        <li class="breadcrumb-item active">Create Group</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="card">
                        <div class="card-header border-bottom border-dashed">
                            <h4 class="card-title">Group Information</h4>
                            <p class="text-muted mb-0">Create a new group to organize your attributes</p>
                        </div>
                        <div class="card-body">
                            <form id="groupForm">
                                @csrf

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Group Name <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light">
                                                    <i class="ti ti-category"></i>
                                                </span>
                                                <input type="text" class="form-control" id="name" name="name"
                                                    placeholder="Enter group name (e.g., Electronics, Clothing, Furniture)"
                                                    autofocus>
                                            </div>
                                            <div class="invalid-feedback" id="name-error"></div>
                                            <small class="text-muted">
                                                <i class="ti ti-link"></i> URL slug: <span id="slug-preview"
                                                    class="text-primary"></span>
                                            </small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="icon" class="form-label">Icon</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light">
                                                    <i class="ti ti-icon"></i>
                                                </span>
                                                <input type="text" class="form-control" id="icon" name="icon"
                                                    placeholder="ti ti-category, ti ti-device-laptop">
                                            </div>
                                            <div class="invalid-feedback" id="icon-error"></div>
                                            <small class="text-muted">Tabler icon name (e.g., ti ti-category)</small>
                                            <div class="mt-2" id="icon-preview"></div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="color" class="form-label">Group Color</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light">
                                                    <i class="ti ti-color-swatch"></i>
                                                </span>
                                                <input type="color" class="form-control" id="color" name="color"
                                                    value="#0d6efd" style="height: 38px;">
                                            </div>
                                            <div class="invalid-feedback" id="color-error"></div>
                                            <small class="text-muted">Color for visual identification</small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="display_order" class="form-label">Display Order</label>
                                            <input type="number" class="form-control" id="display_order"
                                                name="display_order" value="0">
                                            <div class="invalid-feedback" id="display_order-error"></div>
                                            <small class="text-muted">Lower numbers appear first</small>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="description" class="form-label">Description</label>
                                            <textarea class="form-control" id="description" name="description" rows="3"
                                                placeholder="Describe what this attribute group is for..."></textarea>
                                            <div class="invalid-feedback" id="description-error"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <div class="form-check form-switch mb-3">
                                            <input type="checkbox" class="form-check-input" id="is_collapsible"
                                                name="is_collapsible" value="1" checked>
                                            <label class="form-check-label" for="is_collapsible">Collapsible</label>
                                            <small class="text-muted d-block">Allow this group to be collapsed on
                                                frontend</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check form-switch mb-3">
                                            <input type="checkbox" class="form-check-input" id="is_collapsed_by_default"
                                                name="is_collapsed_by_default" value="1">
                                            <label class="form-check-label" for="is_collapsed_by_default">Collapsed by
                                                Default</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check form-switch mb-3">
                                            <input type="checkbox" class="form-check-input" id="show_in_sidebar"
                                                name="show_in_sidebar" value="1" checked>
                                            <label class="form-check-label" for="show_in_sidebar">Show in Sidebar</label>
                                            <small class="text-muted d-block">Display this group in filter sidebar</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check form-switch mb-3">
                                            <input type="checkbox" class="form-check-input" id="show_in_compare"
                                                name="show_in_compare" value="1" checked>
                                            <label class="form-check-label" for="show_in_compare">Show in Compare</label>
                                            <small class="text-muted d-block">Include in product comparison</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check form-switch mb-3">
                                            <input type="checkbox" class="form-check-input" id="status"
                                                name="status" value="1" checked>
                                            <label class="form-check-label" for="status">Active</label>
                                            <small class="text-muted d-block">Inactive groups won't be displayed</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2 mt-4">
                                    <a href="{{ route('admin.attribute-groups.index') }}" class="btn btn-danger">
                                        <i class="ti ti-x me-1"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary" id="submitBtn">
                                        <i class="ti ti-plus me-1"></i> Create Group
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

            // Auto-generate slug preview
            $('#name').on('keyup', function() {
                let slug = $(this).val().toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
                $('#slug-preview').text(slug || 'group-slug');
            });

            // Icon preview
            $('#icon').on('keyup', function() {
                let icon = $(this).val();
                if (icon) {
                    $('#icon-preview').html(
                        `<i class="${icon} fs-2 text-primary"></i> <span class="text-muted ms-2">Preview: ${icon}</span>`
                        );
                } else {
                    $('#icon-preview').html('');
                }
            });

            // Color preview
            $('#color').on('change', function() {
                let color = $(this).val();
                $(this).css('background', color);
            });

            // Remove error on input
            $('input, select, textarea').on('input change', function() {
                $(this).removeClass('is-invalid');
                $('#' + $(this).attr('name') + '-error').text('');
            });

            // Form submission
            $('#groupForm').on('submit', function(e) {
                e.preventDefault();

                if (formSubmitting) return false;

                let isValid = true;

                if (!$('#name').val().trim()) {
                    $('#name').addClass('is-invalid');
                    $('#name-error').text('Group name is required');
                    isValid = false;
                }

                if (!isValid) {
                    $('html, body').animate({
                        scrollTop: $('.is-invalid:first').offset().top - 100
                    }, 500);
                    return false;
                }

                formSubmitting = true;
                let btn = $('#submitBtn');
                let originalText = btn.html();
                btn.html('<span class="spinner-border spinner-border-sm me-1"></span> Creating...');
                btn.prop('disabled', true);

                $.ajax({
                    url: '{{ route('admin.attribute-groups.store') }}',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: response.message,
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.href =
                                    '{{ route('admin.attribute-groups.index') }}';
                            });
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function(field, messages) {
                                $('#' + field).addClass('is-invalid');
                                $('#' + field + '-error').text(messages[0]);
                            });

                            $('html, body').animate({
                                scrollTop: $('.is-invalid:first').offset().top - 100
                            }, 500);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: xhr.responseJSON?.message ||
                                    'Something went wrong. Please try again.',
                                confirmButtonColor: '#d33'
                            });
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
