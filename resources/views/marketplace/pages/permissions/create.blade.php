{{-- resources/views/vendor/permissions/create.blade.php --}}

@extends('management.layouts.app')

@section('title', 'Create Permissions')

@section('content')
    <div class="page-content">
        <div class="page-container">
            <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                <div class="flex-grow-1">
                    <h4 class="fs-18 text-uppercase fw-bold mb-0">Create Permissions</h4>
                </div>
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('vendor.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('vendor.permissions.index') }}">Permissions</a></li>
                        <li class="breadcrumb-item active">Create Permissions</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="card">
                        <div class="card-header border-bottom border-dashed">
                            <h4 class="card-title">Permission Information</h4>
                            <p class="text-muted mb-0">Create new permissions. You can add multiple permissions at once
                                using comma separation.</p>
                        </div>
                        <div class="card-body">
                            <form id="permissionForm">
                                @csrf

                                <div class="mb-4">
                                    <label for="permissions" class="form-label">Permission Names <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <i class="ti ti-lock"></i>
                                        </span>
                                        <textarea class="form-control form-control-lg" id="permissions" name="permissions" rows="4"
                                            placeholder="view_users, create_users, edit_users, delete_users&#10;view_roles, create_roles, edit_roles, delete_roles&#10;view_products, create_products, edit_products, delete_products"
                                            autofocus></textarea>
                                    </div>
                                    <small class="text-muted">
                                        <i class="ti ti-info-circle me-1"></i>
                                        Enter permissions separated by commas (e.g., "view_users, create_users, edit_users")
                                        <br>
                                        <strong>Note:</strong> Spaces will be automatically converted to underscores.
                                    </small>
                                    <div class="invalid-feedback" id="permissions-error"></div>
                                </div>

                                <div class="alert alert-info">
                                    <i class="ti ti-lightbulb me-2"></i>
                                    <strong>Examples (comma-separated):</strong>
                                    <div class="mt-2">
                                        <code>view_users, create_users, edit_users, delete_users</code><br>
                                        <code>view_roles, create_roles, edit_roles, delete_roles</code><br>
                                        <code>view_products, create_products, edit_products, delete_products</code><br>
                                        <code>view_orders, create_orders, edit_orders, delete_orders</code><br>
                                        <code>view categories, create categories, edit categories, delete categories</code>
                                        <span class="badge bg-warning ms-2">Will become: view_categories, create_categories,
                                            etc.</span>
                                    </div>
                                </div>

                                <div class="alert alert-warning">
                                    <i class="ti ti-alert-triangle me-2"></i>
                                    <strong>Note:</strong>
                                    <ul class="mb-0 mt-2">
                                        <li>Spaces in permission names will be replaced with underscores</li>
                                        <li>Example: "view users" becomes "view_users"</li>
                                        <li>Duplicate permissions will be automatically handled</li>
                                        <li>Already existing permissions will be skipped</li>
                                    </ul>
                                </div>

                                <div class="d-flex justify-content-end gap-2 mt-4">
                                    <a href="{{ route('vendor.permissions.index') }}" class="btn btn-danger">
                                        <i class="ti ti-x me-1"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary" id="submitBtn">
                                        <i class="ti ti-plus me-1"></i> Create Permissions
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

            // Remove error on input
            $('#permissions').on('input', function() {
                $(this).removeClass('is-invalid');
                $('#permissions-error').text('');
            });

            // Preview converted permissions
            $('#permissions').on('keyup', function() {
                let value = $(this).val();
                let permissions = value.split(',').map(p => p.trim()).filter(p => p);
                let converted = permissions.map(p => p.replace(/\s+/g, '_').toLowerCase());

                if (converted.length > 0) {
                    let previewHtml = '<div class="mt-2 p-2 bg-light rounded">';
                    previewHtml += '<strong>Preview (will be saved as):</strong><br>';
                    previewHtml += converted.map(p => '<span class="badge bg-primary me-1 mb-1">' + p +
                        '</span>').join('');
                    previewHtml += '</div>';

                    if ($('#preview').length) {
                        $('#preview').html(previewHtml);
                    } else {
                        $(this).after('<div id="preview" class="mt-2">' + previewHtml + '</div>');
                    }
                } else {
                    $('#preview').remove();
                }
            });

            // Form submission
            $('#permissionForm').on('submit', function(e) {
                e.preventDefault();

                if (formSubmitting) return false;

                let isValid = true;
                let permissionsInput = $('#permissions').val().trim();

                if (!permissionsInput) {
                    $('#permissions').addClass('is-invalid');
                    $('#permissions-error').text('Please enter at least one permission');
                    isValid = false;
                }

                if (!isValid) return false;

                formSubmitting = true;
                let btn = $('#submitBtn');
                let originalText = btn.html();
                btn.html('<span class="spinner-border spinner-border-sm me-1"></span> Creating...');
                btn.prop('disabled', true);

                $.ajax({
                    url: '{{ route('vendor.permissions.store') }}',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                html: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.href =
                                    '{{ route('vendor.permissions.index') }}';
                            });
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            if (errors.permissions) {
                                $('#permissions').addClass('is-invalid');
                                $('#permissions-error').text(errors.permissions[0]);
                            }
                            if (errors.existing) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Existing Permissions',
                                    html: errors.existing[0],
                                    confirmButtonColor: '#d33'
                                });
                            }
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

@push('styles')
    <style>
        textarea {
            font-family: monospace;
            font-size: 14px;
        }

        #preview {
            font-size: 12px;
        }

        .alert ul {
            padding-left: 20px;
        }
    </style>
@endpush
