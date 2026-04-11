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
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="mb-0">Assign Permissions</h5>
                                        <div>
                                            <button type="button" class="btn btn-sm btn-outline-primary"
                                                id="selectAllPermissions">
                                                <i class="ti ti-check-all"></i> Select All
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-secondary ms-2"
                                                id="deselectAllPermissions">
                                                <i class="ti ti-check"></i> Deselect All
                                            </button>
                                        </div>
                                    </div>
                                    <p class="text-muted small mb-3">Select the permissions this role should have.</p>

                                    <div class="row">
                                        @php
                                            // Group permissions by the second part (after first underscore)
                                            $groupedPermissions = [];
                                            foreach ($permissions as $permission) {
                                                $parts = explode('_', $permission->name, 2);
                                                $group = isset($parts[1]) ? $parts[1] : $parts[0];
                                                $group = ucfirst(str_replace('_', ' ', $group));

                                                if (!isset($groupedPermissions[$group])) {
                                                    $groupedPermissions[$group] = [];
                                                }
                                                $groupedPermissions[$group][] = $permission;
                                            }

                                            // Sort groups alphabetically
                                            ksort($groupedPermissions);
                                        @endphp

                                        @forelse($groupedPermissions as $groupName => $groupPermissions)
                                            @php
                                                // Check if all permissions in this group are assigned
                                                $groupChecked = true;
                                                $groupCount = count($groupPermissions);
                                                $checkedCount = 0;

                                                foreach ($groupPermissions as $perm) {
                                                    if (in_array($perm->id, $rolePermissions)) {
                                                        $checkedCount++;
                                                    }
                                                }

                                                $groupChecked = $checkedCount === $groupCount && $groupCount > 0;
                                                $groupIndeterminate = $checkedCount > 0 && $checkedCount < $groupCount;
                                            @endphp
                                            <div class="col-md-6 col-lg-4 mb-3">
                                                <div class="card border h-100">
                                                    <div class="card-header bg-light">
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input module-checkbox"
                                                                id="module_{{ Str::slug($groupName) }}"
                                                                data-module="{{ $groupName }}"
                                                                {{ $groupChecked ? 'checked' : '' }}>
                                                            <label class="form-check-label fw-semibold"
                                                                for="module_{{ Str::slug($groupName) }}">
                                                                {{ ucfirst($groupName) }}
                                                            </label>
                                                            <span
                                                                class="badge bg-secondary float-end">{{ count($groupPermissions) }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="card-body" style="max-height: 250px; overflow-y: auto;">
                                                        @foreach ($groupPermissions as $permission)
                                                            @php
                                                                $action = explode('_', $permission->name, 2)[0];
                                                                $actionLabel = ucfirst(str_replace('_', ' ', $action));
                                                            @endphp
                                                            <div class="form-check mb-2">
                                                                <input type="checkbox"
                                                                    class="form-check-input permission-checkbox"
                                                                    name="permissions[]" value="{{ $permission->id }}"
                                                                    id="perm_{{ $permission->id }}"
                                                                    data-module="{{ $groupName }}"
                                                                    {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}>
                                                                <label class="form-check-label small"
                                                                    for="perm_{{ $permission->id }}">
                                                                    <i class="ti ti-lock me-1 text-muted"></i>
                                                                    {{ $actionLabel }}
                                                                </label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="col-12">
                                                <div class="alert alert-warning">
                                                    <i class="ti ti-alert-circle me-2"></i>
                                                    No permissions found. Please create permissions first.
                                                </div>
                                            </div>
                                        @endforelse
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

            // Update select all/deselect all button state
            function updateSelectAllState() {
                let totalPermissions = $('.permission-checkbox').length;
                let checkedPermissions = $('.permission-checkbox:checked').length;

                if (checkedPermissions === totalPermissions) {
                    $('#selectAllPermissions').html('<i class="ti ti-check-all"></i> All Selected');
                    $('#selectAllPermissions').removeClass('btn-outline-primary').addClass('btn-success');
                } else {
                    $('#selectAllPermissions').html('<i class="ti ti-check-all"></i> Select All');
                    $('#selectAllPermissions').removeClass('btn-success').addClass('btn-outline-primary');
                }
            }

            // Select All Permissions
            $('#selectAllPermissions').on('click', function() {
                $('.permission-checkbox').prop('checked', true);
                $('.module-checkbox').prop('checked', true);
                $('.module-checkbox').prop('indeterminate', false);
                updateSelectAllState();
            });

            // Deselect All Permissions
            $('#deselectAllPermissions').on('click', function() {
                $('.permission-checkbox').prop('checked', false);
                $('.module-checkbox').prop('checked', false);
                $('.module-checkbox').prop('indeterminate', false);
                updateSelectAllState();
            });

            // Module checkbox - select/deselect all permissions in module
            $('.module-checkbox').on('change', function() {
                let module = $(this).data('module');
                let isChecked = $(this).prop('checked');
                $(`.permission-checkbox[data-module="${module}"]`).prop('checked', isChecked);
                $(this).prop('indeterminate', false);
                updateSelectAllState();
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

                updateSelectAllState();
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

                if (!isValid) {
                    $('html, body').animate({
                        scrollTop: $('.is-invalid:first').offset().top - 100
                    }, 500);
                    return false;
                }

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

@push('styles')
    <style>
        .card-header.bg-light {
            background-color: #f8f9fa;
        }

        .card-body {
            padding: 1rem;
        }

        .form-check-label {
            cursor: pointer;
        }

        .form-check-label:hover {
            color: #0d6efd;
        }

        .badge.bg-secondary {
            background-color: #6c757d;
        }

        .btn-outline-primary,
        .btn-outline-secondary {
            transition: all 0.2s ease;
        }
    </style>
@endpush
