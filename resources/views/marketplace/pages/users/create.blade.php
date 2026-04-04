@extends('admin.layouts.app')

@section('title', 'Create User')

@section('content')
    <!-- ============================================================== -->
    <!-- Start Page Content here -->
    <!-- ============================================================== -->

    <div class="page-content">

        <!-- Start Content-->
        <div class="page-container">

            <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                <div class="flex-grow-1">
                    <h4 class="fs-18 text-uppercase fw-bold mb-0">Create New User</h4>
                </div>

                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Users</a></li>
                        <li class="breadcrumb-item active">Create User</li>
                    </ol>
                </div>
            </div>

            <form id="userForm" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-lg-7">
                        <div class="card">
                            <div class="card-header border-bottom border-dashed">
                                <h4 class="card-title">Personal Information</h4>
                                <p class="text-muted mb-0">Enter the user's basic information and contact details.</p>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Full Name <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="name" name="name"
                                                placeholder="Enter full name">
                                            <div class="invalid-feedback" id="name-error"></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email Address <span
                                                    class="text-danger">*</span></label>
                                            <input type="email" class="form-control" id="email" name="email"
                                                placeholder="Enter email address">
                                            <div class="invalid-feedback" id="email-error"></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="password" class="form-label">Password <span
                                                    class="text-danger">*</span></label>
                                            <input type="password" class="form-control" id="password" name="password"
                                                placeholder="Enter password">
                                            <div class="invalid-feedback" id="password-error"></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="password_confirmation" class="form-label">Confirm Password <span
                                                    class="text-danger">*</span></label>
                                            <input type="password" class="form-control" id="password_confirmation"
                                                name="password_confirmation" placeholder="Confirm password">
                                            <div class="invalid-feedback" id="password_confirmation-error"></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="phone" class="form-label">Phone Number</label>
                                            <input type="text" class="form-control" id="phone" name="phone"
                                                placeholder="Enter phone number">
                                            <div class="invalid-feedback" id="phone-error"></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="is_active" class="form-label">Status</label>
                                            <select class="form-select" id="is_active" name="is_active">
                                                <option value="1" selected>Active</option>
                                                <option value="0">Inactive</option>
                                            </select>
                                            <div class="invalid-feedback" id="is_active-error"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header border-bottom border-dashed">
                                <h4 class="card-title">Additional Information</h4>
                                <p class="text-muted mb-0">Optional information about the user.</p>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label for="address" class="form-label">Address</label>
                                            <textarea class="form-control" id="address" name="address" rows="3" placeholder="Enter user address"></textarea>
                                            <div class="invalid-feedback" id="address-error"></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="city" class="form-label">City</label>
                                            <input type="text" class="form-control" id="city" name="city"
                                                placeholder="Enter city">
                                            <div class="invalid-feedback" id="city-error"></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="country" class="form-label">Country</label>
                                            <input type="text" class="form-control" id="country" name="country"
                                                placeholder="Enter country">
                                            <div class="invalid-feedback" id="country-error"></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="postal_code" class="form-label">Postal Code</label>
                                            <input type="text" class="form-control" id="postal_code"
                                                name="postal_code" placeholder="Enter postal code">
                                            <div class="invalid-feedback" id="postal_code-error"></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="birth_date" class="form-label">Birth Date</label>
                                            <input type="date" class="form-control" id="birth_date"
                                                name="birth_date">
                                            <div class="invalid-feedback" id="birth_date-error"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-5">
                        <div class="card">
                            <div class="card-header border-bottom border-dashed">
                                <h4 class="card-title">Role Assignment</h4>
                                <p class="text-muted mb-0">Assign a role to determine user permissions in the system.</p>
                            </div>
                            <div class="card-body">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="role" class="form-label">Select Role <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select" id="role" name="role">
                                            <option value="">-- Select Role --</option>
                                            @foreach ($roles as $role)
                                                <option value="{{ $role->name }}"
                                                    data-permissions="{{ json_encode($role->permissions->pluck('name')) }}">
                                                    {{ $role->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback" id="role-error"></div>
                                    </div>

                                    <!-- Role Permissions Preview -->
                                    <div class="mt-3" id="permissionsPreview" style="display: none;">
                                        <div class="alert alert-info">
                                            <h6 class="mb-2"><i class="ti ti-shield me-1"></i> Role Permissions</h6>
                                            <div id="permissionsList" class="d-flex flex-wrap gap-1"></div>
                                        </div>
                                    </div>

                                    <div class="alert alert-warning mt-3">
                                        <i class="ti ti-info-circle me-1"></i>
                                        <small>Note: Permissions are controlled through roles. Assigning a role will give
                                            the user all associated permissions.</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header border-bottom border-dashed">
                                <h4 class="card-title">Profile Picture</h4>
                                <p class="text-muted mb-0">Upload a profile picture for the user (optional).</p>
                            </div>
                            <div class="card-body">
                                <div class="col-12">
                                    <div class="text-center mb-3">
                                        <div id="avatarPreview" class="mb-3">
                                            <img id="preview"
                                                src="https://ui-avatars.com/api/?name=User&background=0D6EFD&color=fff&size=100"
                                                class="rounded-circle"
                                                style="width: 100px; height: 100px; object-fit: cover;">
                                        </div>
                                        <div class="mb-3">
                                            <input type="file" class="form-control" id="avatar" name="avatar"
                                                accept="image/*">
                                            <small class="text-muted">Allowed: jpeg, png, jpg, gif. Max 2MB</small>
                                            <div class="invalid-feedback" id="avatar-error"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-footer border-top border-dashed text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.users.index') }}" class="btn btn-danger">
                                        <i class="ti ti-x me-1"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary" id="submitBtn">
                                        <i class="ti ti-plus me-1"></i> Create User
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

        </div> <!-- container -->

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->
    @endsection

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            $(document).ready(function() {
                let formSubmitting = false;

                // Real-time password confirmation validation
                function validatePasswordConfirmation() {
                    let password = $('#password').val();
                    let confirm = $('#password_confirmation').val();

                    if (confirm !== '') {
                        if (password !== confirm) {
                            $('#password_confirmation').addClass('is-invalid');
                            $('#password_confirmation-error').text('Passwords do not match!');
                            return false;
                        } else {
                            $('#password_confirmation').removeClass('is-invalid');
                            $('#password_confirmation-error').text('');
                            return true;
                        }
                    }
                    return true;
                }

                // Real-time validation for password confirmation
                $('#password_confirmation').on('keyup', function() {
                    validatePasswordConfirmation();
                });

                $('#password').on('keyup', function() {
                    if ($('#password_confirmation').val() !== '') {
                        validatePasswordConfirmation();
                    }
                });

                // Remove error on input
                $('input, select, textarea').on('input change', function() {
                    $(this).removeClass('is-invalid');
                    $('#' + $(this).attr('name') + '-error').text('');
                });

                // Role selection with permissions preview
                $('#role').on('change', function() {
                    let selectedOption = $(this).find(':selected');
                    let permissions = selectedOption.data('permissions');

                    if (selectedOption.val() && permissions && permissions.length > 0) {
                        let permissionsHtml = '';
                        permissions.forEach(function(permission) {
                            permissionsHtml += '<span class="badge bg-info-subtle text-info p-2">' +
                                '<i class="ti ti-lock me-1"></i>' + permission +
                                '</span>';
                        });
                        $('#permissionsList').html(permissionsHtml);
                        $('#permissionsPreview').fadeIn();
                    } else {
                        $('#permissionsPreview').fadeOut();
                    }
                });

                // Avatar preview
                $('#avatar').on('change', function(event) {
                    let file = event.target.files[0];
                    if (file) {
                        let reader = new FileReader();
                        reader.onload = function(e) {
                            $('#preview').attr('src', e.target.result);
                        };
                        reader.readAsDataURL(file);

                        // Validate file size
                        if (file.size > 2 * 1024 * 1024) {
                            $('#avatar').addClass('is-invalid');
                            $('#avatar-error').text('File size must be less than 2MB');
                            $(this).val('');
                            $('#preview').attr('src',
                                'https://ui-avatars.com/api/?name=User&background=0D6EFD&color=fff&size=100'
                            );
                        } else {
                            $('#avatar').removeClass('is-invalid');
                            $('#avatar-error').text('');
                        }
                    }
                });

                // Form submission with AJAX
                $('#userForm').on('submit', function(e) {
                    e.preventDefault();

                    if (formSubmitting) {
                        return false;
                    }

                    // Clear all previous errors
                    $('.is-invalid').removeClass('is-invalid');
                    $('.invalid-feedback').text('');

                    // Validate required fields
                    let isValid = true;

                    if (!$('#name').val().trim()) {
                        $('#name').addClass('is-invalid');
                        $('#name-error').text('Name is required');
                        isValid = false;
                    }

                    if (!$('#email').val().trim()) {
                        $('#email').addClass('is-invalid');
                        $('#email-error').text('Email is required');
                        isValid = false;
                    } else if (!isValidEmail($('#email').val())) {
                        $('#email').addClass('is-invalid');
                        $('#email-error').text('Please enter a valid email address');
                        isValid = false;
                    }

                    if (!$('#password').val()) {
                        $('#password').addClass('is-invalid');
                        $('#password-error').text('Password is required');
                        isValid = false;
                    } else if ($('#password').val().length < 8) {
                        $('#password').addClass('is-invalid');
                        $('#password-error').text('Password must be at least 8 characters');
                        isValid = false;
                    }

                    if (!$('#password_confirmation').val()) {
                        $('#password_confirmation').addClass('is-invalid');
                        $('#password_confirmation-error').text('Please confirm your password');
                        isValid = false;
                    } else if ($('#password').val() !== $('#password_confirmation').val()) {
                        $('#password_confirmation').addClass('is-invalid');
                        $('#password_confirmation-error').text('Passwords do not match!');
                        isValid = false;
                    }

                    if (!$('#role').val()) {
                        $('#role').addClass('is-invalid');
                        $('#role-error').text('Please select a role');
                        isValid = false;
                    }

                    if (!isValid) {
                        // Scroll to first error
                        $('html, body').animate({
                            scrollTop: $('.is-invalid:first').offset().top - 100
                        }, 500);
                        return false;
                    }

                    // Submit via AJAX
                    formSubmitting = true;
                    let submitBtn = $('#submitBtn');
                    let originalText = submitBtn.html();
                    submitBtn.html(
                        '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Creating...'
                    );
                    submitBtn.prop('disabled', true);

                    let formData = new FormData(this);

                    $.ajax({
                        url: '{{ route('admin.users.store') }}',
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            if (response.success) {
                                // Show success message
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success!',
                                    text: response.message,
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(function() {
                                    window.location.href =
                                        '{{ route('admin.users.index') }}';
                                });
                            }
                        },
                        error: function(xhr) {
                            if (xhr.status === 422) {
                                let errors = xhr.responseJSON.errors;

                                // Display validation errors
                                $.each(errors, function(field, messages) {
                                    let input = $('[name="' + field + '"]');
                                    input.addClass('is-invalid');

                                    let errorDiv = $('#' + field + '-error');
                                    if (errorDiv.length) {
                                        errorDiv.text(messages[0]);
                                    } else {
                                        input.after('<div class="invalid-feedback">' +
                                            messages[0] + '</div>');
                                    }
                                });

                                // Scroll to first error
                                $('html, body').animate({
                                    scrollTop: $('.is-invalid:first').offset().top - 100
                                }, 500);
                            } else if (xhr.status === 403) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: 'You do not have permission to create users.',
                                    confirmButtonColor: '#d33'
                                });
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
                            submitBtn.html(originalText);
                            submitBtn.prop('disabled', false);
                        }
                    });
                });

                // Email validation helper
                function isValidEmail(email) {
                    let regex = /^[^\s@]+@([^\s@]+\.)+[^\s@]+$/;
                    return regex.test(email);
                }
            });
        </script>

        <!-- SweetAlert2 for better alerts -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @endpush

    @push('styles')
        <style>
            .badge.bg-info-subtle {
                background-color: rgba(13, 202, 240, 0.1);
                border: 1px solid rgba(13, 202, 240, 0.2);
            }

            .alert-info {
                background-color: rgba(13, 110, 253, 0.05);
                border-color: rgba(13, 110, 253, 0.1);
            }

            /* Password validation styles */
            .is-invalid {
                border-color: #dc3545 !important;
            }

            .invalid-feedback {
                display: block;
                font-size: 0.875rem;
                margin-top: 0.25rem;
                width: 100%;
                color: #dc3545;
            }

            /* Loading state */
            .btn:disabled {
                opacity: 0.65;
                cursor: not-allowed;
            }

            /* Form group spacing */
            .mb-3 {
                margin-bottom: 1rem;
            }
        </style>
    @endpush
