{{-- resources/views/vendor/roles/assign-permissions.blade.php --}}
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
                        <li class="breadcrumb-item"><a href="{{ route('vendor.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('vendor.roles.index') }}">Roles</a></li>
                        <li class="breadcrumb-item"><a
                                href="{{ route('vendor.roles.show', $role->id) }}">{{ $role->name }}</a></li>
                        <li class="breadcrumb-item active">Assign Permissions</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header border-bottom border-dashed">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h4 class="card-title">Select Permissions</h4>
                                    <p class="text-muted mb-0">Select the permissions you want to assign to this role.</p>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-sm btn-outline-primary" id="selectAllPermissions">
                                        <i class="ti ti-check-all"></i> Select All
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary ms-2"
                                        id="deselectAllPermissions">
                                        <i class="ti ti-check"></i> Deselect All
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form id="permissionForm">
                                @csrf
                                @method('POST')

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
                                                            class="badge bg-secondary ms-2">{{ count($groupPermissions) }}</span>
                                                    </div>
                                                </div>
                                                <div class="card-body" style="max-height: 300px; overflow-y: auto;">
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

                                <div class="d-flex justify-content-end gap-2 mt-4">
                                    <a href="{{ route('vendor.roles.show', $role->id) }}" class="btn btn-danger">
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
                    url: '{{ route('vendor.roles.sync-permissions', $role->id) }}',
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
                                    '{{ route('vendor.roles.show', $role->id) }}';
                            });
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'Something went wrong. Please try again.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: errorMessage,
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

        .card.h-100 {
            height: 100%;
        }
    </style>
@endpush
