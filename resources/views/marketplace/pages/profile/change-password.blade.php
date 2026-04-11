{{-- resources/views/marketplace/pages/profile/change-password.blade.php --}}

@extends('management.layouts.app')

@section('title', 'Change Password')

@section('content')
    <div class="page-content">
        <div class="page-container">
            <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                <div class="flex-grow-1">
                    <h4 class="fs-18 text-uppercase fw-bold mb-0">Change Password</h4>
                    <p class="text-muted mb-0">Update your account password</p>
                </div>
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('vendor.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('vendor.profile.index') }}">Profile</a></li>
                        <li class="breadcrumb-item active">Change Password</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6 mx-auto">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Change Password</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('vendor.profile.update-password') }}" id="passwordForm">
                                @csrf

                                <div class="mb-3">
                                    <label class="form-label">Current Password <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <i class="ti ti-lock"></i>
                                        </span>
                                        <input type="password" name="current_password" id="current_password"
                                            class="form-control @error('current_password') is-invalid @enderror"
                                            placeholder="Enter current password" required>
                                        <button class="btn btn-outline-secondary" type="button"
                                            onclick="togglePassword('current_password')">
                                            <i class="ti ti-eye"></i>
                                        </button>
                                    </div>
                                    @error('current_password')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">New Password <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <i class="ti ti-lock"></i>
                                        </span>
                                        <input type="password" name="password" id="password"
                                            class="form-control @error('password') is-invalid @enderror"
                                            placeholder="Enter new password" required>
                                        <button class="btn btn-outline-secondary" type="button"
                                            onclick="togglePassword('password')">
                                            <i class="ti ti-eye"></i>
                                        </button>
                                    </div>
                                    @error('password')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Minimum 8 characters</small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Confirm New Password <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <i class="ti ti-lock"></i>
                                        </span>
                                        <input type="password" name="password_confirmation" id="password_confirmation"
                                            class="form-control" placeholder="Confirm new password" required>
                                        <button class="btn btn-outline-secondary" type="button"
                                            onclick="togglePassword('password_confirmation')">
                                            <i class="ti ti-eye"></i>
                                        </button>
                                    </div>
                                    <div id="password-match-error" class="invalid-feedback" style="display: none;">Passwords
                                        do not match!</div>
                                </div>

                                <div class="d-flex justify-content-end gap-2 mt-3">
                                    <a href="{{ route('vendor.profile.index') }}" class="btn btn-secondary">
                                        <i class="ti ti-x"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary" id="submitBtn">
                                        <i class="ti ti-lock"></i> Change Password
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="alert alert-info mt-3">
                        <i class="ti ti-info-circle"></i>
                        <strong>Password Guidelines:</strong>
                        <ul class="mb-0 mt-2">
                            <li>Use at least 8 characters</li>
                            <li>Use a mix of letters, numbers, and symbols</li>
                            <li>Avoid using common words or personal information</li>
                            <li>Don't use the same password across multiple sites</li>
                        </ul>
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
        // Toggle password visibility
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const type = field.getAttribute('type') === 'password' ? 'text' : 'password';
            field.setAttribute('type', type);
        }

        $(document).ready(function() {
            let isSubmitting = false;

            // Real-time password match validation
            $('#password, #password_confirmation').on('keyup', function() {
                const password = $('#password').val();
                const confirm = $('#password_confirmation').val();

                if (confirm !== '') {
                    if (password !== confirm) {
                        $('#password_confirmation').addClass('is-invalid');
                        $('#password-match-error').show();
                    } else {
                        $('#password_confirmation').removeClass('is-invalid');
                        $('#password-match-error').hide();
                    }
                } else {
                    $('#password_confirmation').removeClass('is-invalid');
                    $('#password-match-error').hide();
                }
            });

            // Password strength indicator (optional)
            $('#password').on('keyup', function() {
                const password = $(this).val();
                const strength = getPasswordStrength(password);

                // Remove existing strength indicator
                $('.password-strength').remove();

                if (password.length > 0) {
                    let strengthClass = '';
                    let strengthText = '';

                    if (strength === 'weak') {
                        strengthClass = 'text-danger';
                        strengthText = 'Weak';
                    } else if (strength === 'medium') {
                        strengthClass = 'text-warning';
                        strengthText = 'Medium';
                    } else if (strength === 'strong') {
                        strengthClass = 'text-success';
                        strengthText = 'Strong';
                    }

                    $(this).after(
                        `<small class="password-strength ${strengthClass} d-block mt-1">Password Strength: ${strengthText}</small>`
                        );
                }
            });

            function getPasswordStrength(password) {
                let strength = 0;
                if (password.length >= 8) strength++;
                if (password.match(/[a-z]+/)) strength++;
                if (password.match(/[A-Z]+/)) strength++;
                if (password.match(/[0-9]+/)) strength++;
                if (password.match(/[$@#&!]+/)) strength++;

                if (strength <= 2) return 'weak';
                if (strength <= 4) return 'medium';
                return 'strong';
            }

            // Remove error on input
            $('input').on('input', function() {
                $(this).removeClass('is-invalid');
                $('.invalid-feedback').hide();
            });

            // Form submission
            $('#passwordForm').on('submit', function(e) {
                e.preventDefault();

                if (isSubmitting) {
                    return false;
                }

                let isValid = true;
                let firstInvalid = null;

                // Validate current password
                if (!$('#current_password').val()) {
                    $('#current_password').addClass('is-invalid');
                    isValid = false;
                    if (!firstInvalid) firstInvalid = $('#current_password');
                }

                // Validate new password
                const password = $('#password').val();
                if (!password) {
                    $('#password').addClass('is-invalid');
                    isValid = false;
                    if (!firstInvalid) firstInvalid = $('#password');
                } else if (password.length < 8) {
                    $('#password').addClass('is-invalid');
                    $('#password').after(
                        '<div class="invalid-feedback d-block">Password must be at least 8 characters</div>'
                        );
                    isValid = false;
                    if (!firstInvalid) firstInvalid = $('#password');
                }

                // Validate password confirmation
                const confirm = $('#password_confirmation').val();
                if (!confirm) {
                    $('#password_confirmation').addClass('is-invalid');
                    isValid = false;
                    if (!firstInvalid) firstInvalid = $('#password_confirmation');
                } else if (password !== confirm) {
                    $('#password_confirmation').addClass('is-invalid');
                    $('#password-match-error').show();
                    isValid = false;
                    if (!firstInvalid) firstInvalid = $('#password_confirmation');
                }

                if (!isValid) {
                    if (firstInvalid) {
                        $('html, body').animate({
                            scrollTop: firstInvalid.offset().top - 100
                        }, 500);
                        firstInvalid.focus();
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        text: 'Please check the form for errors',
                        confirmButtonColor: '#d33'
                    });
                    return false;
                }

                isSubmitting = true;
                const $submitBtn = $('#submitBtn');
                const originalText = $submitBtn.html();

                // Disable button and show loading
                $submitBtn.prop('disabled', true);
                $submitBtn.html(
                    '<span class="spinner-border spinner-border-sm me-1"></span> Changing Password...');

                // Submit via AJAX
                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: $(this).serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: response.message ||
                                    'Password changed successfully!',
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.href = response.redirect_url ||
                                    '{{ route('vendor.profile.index') }}';
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: response.message || 'Something went wrong!',
                                confirmButtonColor: '#d33'
                            });
                            $submitBtn.prop('disabled', false);
                            $submitBtn.html(originalText);
                            isSubmitting = false;
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            $.each(errors, function(field, messages) {
                                const $input = $('[name="' + field + '"]');
                                $input.addClass('is-invalid');
                                if (!$input.next('.invalid-feedback').length) {
                                    $input.after(
                                        '<div class="invalid-feedback d-block">' +
                                        messages[0] + '</div>');
                                }
                            });
                            Swal.fire({
                                icon: 'error',
                                title: 'Validation Error',
                                text: 'Please check the form for errors',
                                confirmButtonColor: '#d33'
                            });
                        } else if (xhr.status === 401) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Current password is incorrect',
                                confirmButtonColor: '#d33'
                            });
                            $('#current_password').addClass('is-invalid');
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: xhr.responseJSON?.message ||
                                    'Something went wrong. Please try again.',
                                confirmButtonColor: '#d33'
                            });
                        }
                        $submitBtn.prop('disabled', false);
                        $submitBtn.html(originalText);
                        isSubmitting = false;
                    }
                });
            });
        });
    </script>
@endpush

@push('styles')
    <style>
        .input-group .btn-outline-secondary {
            border-left: none;
        }

        .input-group .form-control:focus {
            border-right: none;
        }

        .input-group .form-control:focus+.btn-outline-secondary {
            border-color: #86b7fe;
        }

        .btn-outline-secondary:hover {
            background-color: #f8f9fa;
            border-color: #ced4da;
        }

        .btn-primary:disabled {
            opacity: 0.65;
            cursor: not-allowed;
        }

        .password-strength {
            font-size: 12px;
            margin-top: 5px;
        }
    </style>
@endpush
