{{-- resources/views/admin/permissions/create.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Create Permission')

@section('content')
    <div class="page-content">
        <div class="page-container">
            <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                <div class="flex-grow-1">
                    <h4 class="fs-18 text-uppercase fw-bold mb-0">Create New Permission</h4>
                </div>
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.permissions.index') }}">Permissions</a></li>
                        <li class="breadcrumb-item active">Create Permission</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="card">
                        <div class="card-header border-bottom border-dashed">
                            <h4 class="card-title">Permission Information</h4>
                            <p class="text-muted mb-0">Create a new permission following the format: "action module"</p>
                        </div>
                        <div class="card-body">
                            <form id="permissionForm">
                                @csrf

                                <div class="mb-4">
                                    <label for="name" class="form-label">Permission Name <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <i class="ti ti-lock"></i>
                                        </span>
                                        <input type="text" class="form-control form-control-lg" id="name"
                                            name="name" placeholder="e.g., view users, create posts, edit roles"
                                            autofocus>
                                    </div>
                                    <small class="text-muted">
                                        <i class="ti ti-info-circle me-1"></i>
                                        Format: "action module" (e.g., "view users", "create posts", "delete roles")
                                    </small>
                                    <div class="invalid-feedback" id="name-error"></div>
                                </div>

                                <div class="alert alert-info">
                                    <i class="ti ti-lightbulb me-2"></i>
                                    <strong>Examples:</strong>
                                    <div class="mt-2">
                                        <span class="badge bg-primary me-1 mb-1">view users</span>
                                        <span class="badge bg-primary me-1 mb-1">create users</span>
                                        <span class="badge bg-primary me-1 mb-1">edit users</span>
                                        <span class="badge bg-primary me-1 mb-1">delete users</span>
                                        <span class="badge bg-primary me-1 mb-1">view roles</span>
                                        <span class="badge bg-primary me-1 mb-1">create roles</span>
                                        <span class="badge bg-primary me-1 mb-1">view posts</span>
                                        <span class="badge bg-primary me-1 mb-1">publish posts</span>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2 mt-4">
                                    <a href="{{ route('admin.permissions.index') }}" class="btn btn-danger">
                                        <i class="ti ti-x me-1"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary" id="submitBtn">
                                        <i class="ti ti-plus me-1"></i> Create Permission
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
            $('#name').on('input', function() {
                $(this).removeClass('is-invalid');
                $('#name-error').text('');
            });

            // Form submission
            $('#permissionForm').on('submit', function(e) {
                e.preventDefault();

                if (formSubmitting) return false;

                let isValid = true;

                if (!$('#name').val().trim()) {
                    $('#name').addClass('is-invalid');
                    $('#name-error').text('Permission name is required');
                    isValid = false;
                }

                if (!isValid) return false;

                formSubmitting = true;
                let btn = $('#submitBtn');
                let originalText = btn.html();
                btn.html('<span class="spinner-border spinner-border-sm me-1"></span> Creating...');
                btn.prop('disabled', true);

                $.ajax({
                    url: '{{ route('admin.permissions.store') }}',
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
                                    '{{ route('admin.permissions.index') }}';
                            });
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            if (errors.name) {
                                $('#name').addClass('is-invalid');
                                $('#name-error').text(errors.name[0]);
                            }
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Something went wrong. Please try again.',
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
