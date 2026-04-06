{{-- resources/views/admin/roles/edit.blade.php --}}
@extends('management.layouts.app')

@section('title', 'Edit Role')

@section('content')
    <div class="page-content">
        <div class="page-container">
            <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                <div class="flex-grow-1">
                    <h4 class="fs-18 text-uppercase fw-bold mb-0">Edit Role: {{ $role->name }}</h4>
                </div>
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.roles.index') }}">Roles</a></li>
                        <li class="breadcrumb-item active">Edit Role</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-10 mx-auto">
                    <div class="card">
                        <div class="card-header border-bottom border-dashed">
                            <h4 class="card-title">Edit Role Information</h4>
                            <p class="text-muted mb-0">Update role name and permissions</p>
                        </div>
                        <div class="card-body">
                            <form id="roleForm">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Role Name <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light">
                                                    <i class="ti ti-shield"></i>
                                                </span>
                                                <input type="text" class="form-control" id="name" name="name"
                                                    value="{{ $role->name }}" autofocus>
                                            </div>
                                            <div class="invalid-feedback" id="name-error"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="guard_name" class="form-label">Guard Name</label>
                                            <select class="form-select" id="guard_name" name="guard_name">
                                                <option value="web" {{ $role->guard_name == 'web' ? 'selected' : '' }}>
                                                    Web (User)</option>
                                                <option value="admin" {{ $role->guard_name == 'admin' ? 'selected' : '' }}>
                                                    Admin</option>
                                            </select>
                                            <div class="invalid-feedback" id="guard_name-error"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <h5 class="mb-3">Assign Permissions</h5>
                                    <p class="text-muted small mb-3">Select the permissions this role should have.</p>

                                    <div class="row">
                                        @foreach ($permissions as $module => $modulePermissions)
                                            @php
                                                // Check if all permissions in this module are assigned
                                                $moduleChecked = true;
                                                $moduleCount = 0;
                                                $checkedCount = 0;

                                                foreach ($modulePermissions as $perm) {
                                                    $moduleCount++;
                                                    if (in_array($perm->id, $rolePermissions)) {
                                                        $checkedCount++;
                                                    }
                                                }

                                                $moduleChecked = $checkedCount === $moduleCount && $moduleCount > 0;
                                                $moduleIndeterminate =
                                                    $checkedCount > 0 && $checkedCount < $moduleCount;
                                            @endphp
                                            <div class="col-md-6 col-lg-4 mb-3">
                                                <div class="card border">
                                                    <div class="card-header bg-light">
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input module-checkbox"
                                                                id="module_{{ Str::slug($module) }}"
                                                                data-module="{{ $module }}"
                                                                {{ $moduleChecked ? 'checked' : '' }}>
                                                            <label class="form-check-label fw-semibold"
                                                                for="module_{{ Str::slug($module) }}">
                                                                {{ ucfirst($module) }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="card-body" style="max-height: 250px; overflow-y: auto;">
                                                        @foreach ($modulePermissions as $permission)
                                                            <div class="form-check mb-2">
                                                                <input type="checkbox"
                                                                    class="form-check-input permission-checkbox"
                                                                    name="permissions[]" value="{{ $permission->id }}"
                                                                    id="perm_{{ $permission->id }}"
                                                                    data-module="{{ $module }}"
                                                                    {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}>
                                                                <label class="form-check-label small"
                                                                    for="perm_{{ $permission->id }}">
                                                                    <i class="ti ti-lock me-1 text-muted"></i>
                                                                    {{ $permission->name }}
                                                                </label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="alert alert-warning mt-3">
                                    <i class="ti ti-alert-triangle me-2"></i>
                                    <strong>Warning:</strong> Changing role permissions will affect all users with this
                                    role.
                                </div>

                                <div class="d-flex justify-content-end gap-2 mt-4">
                                    <a href="{{ route('admin.roles.index') }}" class="btn btn-danger">
                                        <i class="ti ti-x me-1"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary" id="submitBtn">
                                        <i class="ti ti-edit me-1"></i> Update Role
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

            // Set indeterminate state for module checkboxes
            $('.module-checkbox').each(function() {
                let module = $(this).data('module');
                let totalPermissions = $(`.permission-checkbox[data-module="${module}"]`).length;
                let checkedPermissions = $(`.permission-checkbox[data-module="${module}"]:checked`).length;

                if (checkedPermissions > 0 && checkedPermissions < totalPermissions) {
                    $(this).prop('indeterminate', true);
                }
            });

            // Module checkbox - select/deselect all permissions in module
            $('.module-checkbox').on('change', function() {
                let module = $(this).data('module');
                let isChecked = $(this).prop('checked');
                $(`.permission-checkbox[data-module="${module}"]`).prop('checked', isChecked);
                $(this).prop('indeterminate', false);
            });

            // Individual permission checkbox - update module checkbox
            $('.permission-checkbox').on('change', function() {
                let module = $(this).data('module');
                let totalPermissions = $(`.permission-checkbox[data-module="${module}"]`).length;
                let checkedPermissions = $(`.permission-checkbox[data-module="${module}"]:checked`).length;
                let moduleCheckbox = $(`#module_${module.replace(/\s+/g, '-')}`);

                if (checkedPermissions === 0) {
                    moduleCheckbox.prop('checked', false);
                    moduleCheckbox.prop('indeterminate', false);
                } else if (checkedPermissions === totalPermissions) {
                    moduleCheckbox.prop('checked', true);
                    moduleCheckbox.prop('indeterminate', false);
                } else {
                    moduleCheckbox.prop('checked', false);
                    moduleCheckbox.prop('indeterminate', true);
                }
            });

            // Remove error on input
            $('#name, #guard_name').on('input change', function() {
                $(this).removeClass('is-invalid');
                $('#' + $(this).attr('name') + '-error').text('');
            });

            // Form submission
            $('#roleForm').on('submit', function(e) {
                e.preventDefault();

                if (formSubmitting) return false;

                let isValid = true;

                if (!$('#name').val().trim()) {
                    $('#name').addClass('is-invalid');
                    $('#name-error').text('Role name is required');
                    isValid = false;
                }

                if (!isValid) return false;

                formSubmitting = true;
                let btn = $('#submitBtn');
                let originalText = btn.html();
                btn.html('<span class="spinner-border spinner-border-sm me-1"></span> Updating...');
                btn.prop('disabled', true);

                $.ajax({
                    url: '{{ route('admin.roles.update', $role->id) }}',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Updated!',
                                text: response.message,
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.href =
                                    '{{ route('admin.roles.index') }}';
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
