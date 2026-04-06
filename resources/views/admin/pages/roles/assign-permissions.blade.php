{{-- resources/views/admin/roles/assign-permissions.blade.php --}}
@extends('management.layouts.app')

@section('title', 'Assign Permissions')

@section('content')
    <div class="page-content">
        <div class="page-container">
            <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                <div class="flex-grow-1">
                    <h4 class="fs-18 text-uppercase fw-bold mb-0">Assign Permissions to: {{ $role->name }}</h4>
                </div>
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.roles.index') }}">Roles</a></li>
                        <li class="breadcrumb-item"><a
                                href="{{ route('admin.roles.show', $role->id) }}">{{ $role->name }}</a></li>
                        <li class="breadcrumb-item active">Assign Permissions</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header border-bottom border-dashed">
                            <h4 class="card-title">Select Permissions</h4>
                            <p class="text-muted mb-0">Select the permissions you want to assign to this role.</p>
                        </div>
                        <div class="card-body">
                            <form id="permissionForm">
                                @csrf
                                @method('POST')

                                <div class="row">
                                    @foreach ($permissions as $module => $modulePermissions)
                                        @php
                                            $moduleChecked = true;
                                            foreach ($modulePermissions as $perm) {
                                                if (!in_array($perm->id, $rolePermissions)) {
                                                    $moduleChecked = false;
                                                    break;
                                                }
                                            }
                                            $moduleIndeterminate =
                                                !$moduleChecked &&
                                                collect($modulePermissions)->contains(function ($perm) use (
                                                    $rolePermissions,
                                                ) {
                                                    return in_array($perm->id, $rolePermissions);
                                                });
                                        @endphp
                                        <div class="col-md-6 col-lg-4 mb-3">
                                            <div class="card border">
                                                <div class="card-header bg-light">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input module-checkbox"
                                                            id="module_{{ Str::slug($module) }}"
                                                            data-module="{{ $module }}"
                                                            {{ $moduleChecked ? 'checked' : '' }}
                                                            {{ $moduleIndeterminate ? 'data-indeterminate="true"' : '' }}>
                                                        <label class="form-check-label fw-semibold"
                                                            for="module_{{ Str::slug($module) }}">
                                                            {{ ucfirst($module) }}
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="card-body" style="max-height: 300px; overflow-y: auto;">
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

                                <div class="d-flex justify-content-end gap-2 mt-4">
                                    <a href="{{ route('admin.roles.show', $role->id) }}" class="btn btn-danger">
                                        <i class="ti ti-x me-1"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary" id="submitBtn">
                                        <i class="ti ti-key me-1"></i> Save Permissions
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
            $('.module-checkbox[data-indeterminate="true"]').prop('indeterminate', true);

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

            // Form submission
            $('#permissionForm').on('submit', function(e) {
                e.preventDefault();

                if (formSubmitting) return false;

                formSubmitting = true;
                let btn = $('#submitBtn');
                let originalText = btn.html();
                btn.html('<span class="spinner-border spinner-border-sm me-1"></span> Saving...');
                btn.prop('disabled', true);

                $.ajax({
                    url: '{{ route('admin.roles.sync-permissions', $role->id) }}',
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
                                    '{{ route('admin.roles.show', $role->id) }}';
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Something went wrong. Please try again.',
                            confirmButtonColor: '#d33'
                        });
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
