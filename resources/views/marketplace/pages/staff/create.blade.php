{{-- resources/views/vendor/staff/create.blade.php --}}

@extends('management.layouts.app')

@section('title', 'Add New Staff')

@section('content')
    <!-- ============================================================== -->
    <!-- Start Page Content here -->
    <!-- ============================================================== -->

    <div class="page-content">

        <!-- Start Content-->
        <div class="page-container">

            <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                <div class="flex-grow-1">
                    <h4 class="fs-18 text-uppercase fw-bold mb-0">Add New Staff</h4>
                </div>

                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('vendor.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('vendor.staff.index') }}">Staff</a></li>
                        <li class="breadcrumb-item active">Add Staff</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Staff Information</h3>
                            <p class="text-muted mb-0">Create a new staff member for your store</p>
                        </div>
                        <div class="card-body">
                            <form id="staffForm" action="{{ route('vendor.staff.store') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf

                                <div class="row">
                                    {{-- Personal Information --}}
                                    <div class="col-md-12">
                                        <h5 class="mb-3 border-bottom pb-2">Personal Information</h5>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Full Name <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light">
                                                    <i class="ti ti-user"></i>
                                                </span>
                                                <input type="text"
                                                    class="form-control @error('name') is-invalid @enderror" id="name"
                                                    name="name" value="{{ old('name') }}" placeholder="Enter full name"
                                                    required>
                                            </div>
                                            @error('name')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email Address <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light">
                                                    <i class="ti ti-mail"></i>
                                                </span>
                                                <input type="email"
                                                    class="form-control @error('email') is-invalid @enderror" id="email"
                                                    name="email" value="{{ old('email') }}"
                                                    placeholder="Enter email address" required>
                                            </div>
                                            @error('email')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">Staff will receive login credentials at this
                                                email</small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="phone" class="form-label">Phone Number</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light">
                                                    <i class="ti ti-phone"></i>
                                                </span>
                                                <input type="text"
                                                    class="form-control @error('phone') is-invalid @enderror" id="phone"
                                                    name="phone" value="{{ old('phone') }}"
                                                    placeholder="Enter phone number">
                                            </div>
                                            @error('phone')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="avatar" class="form-label">Profile Avatar</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light">
                                                    <i class="ti ti-photo"></i>
                                                </span>
                                                <input type="file"
                                                    class="form-control @error('avatar') is-invalid @enderror"
                                                    id="avatar" name="avatar" accept="image/*">
                                            </div>
                                            @error('avatar')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">Recommended size: 200x200px, Max: 2MB</small>
                                        </div>
                                    </div>

                                    {{-- Address Information --}}
                                    <div class="col-md-12 mt-2">
                                        <h5 class="mb-3 border-bottom pb-2">Address Information</h5>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="address" class="form-label">Address</label>
                                            <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="2"
                                                placeholder="Enter street address">{{ old('address') }}</textarea>
                                            @error('address')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="city" class="form-label">City</label>
                                            <input type="text" class="form-control @error('city') is-invalid @enderror"
                                                id="city" name="city" value="{{ old('city') }}"
                                                placeholder="City">
                                            @error('city')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="state" class="form-label">State</label>
                                            <input type="text"
                                                class="form-control @error('state') is-invalid @enderror" id="state"
                                                name="state" value="{{ old('state') }}" placeholder="State">
                                            @error('state')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="country" class="form-label">Country</label>
                                            <input type="text"
                                                class="form-control @error('country') is-invalid @enderror" id="country"
                                                name="country" value="{{ old('country') }}" placeholder="Country">
                                            @error('country')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="postal_code" class="form-label">Postal Code</label>
                                            <input type="text"
                                                class="form-control @error('postal_code') is-invalid @enderror"
                                                id="postal_code" name="postal_code" value="{{ old('postal_code') }}"
                                                placeholder="Postal Code">
                                            @error('postal_code')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- Role & Permissions --}}
                                    <div class="col-md-12 mt-2">
                                        <h5 class="mb-3 border-bottom pb-2">Role & Permissions</h5>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="role" class="form-label">Staff Role <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select @error('role') is-invalid @enderror" id="role"
                                                name="role" required>
                                                <option value="">Select Role</option>
                                                @foreach ($roles as $value => $label)
                                                    <option value="{{ $value }}"
                                                        {{ old('role') == $value ? 'selected' : '' }}>
                                                        {{ $label }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('role')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted" id="roleDescription"></small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="password" class="form-label">Password <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light">
                                                    <i class="ti ti-lock"></i>
                                                </span>
                                                <input type="password"
                                                    class="form-control @error('password') is-invalid @enderror"
                                                    id="password" name="password" placeholder="Enter password" required>
                                                <button class="btn btn-outline-secondary" type="button"
                                                    id="togglePassword">
                                                    <i class="ti ti-eye"></i>
                                                </button>
                                            </div>
                                            @error('password')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">Minimum 8 characters</small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="password_confirmation" class="form-label">Confirm Password <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light">
                                                    <i class="ti ti-lock"></i>
                                                </span>
                                                <input type="password" class="form-control" id="password_confirmation"
                                                    name="password_confirmation" placeholder="Confirm password" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Status</label>
                                            <div class="form-check form-switch mt-2">
                                                <input type="checkbox" class="form-check-input" id="is_active"
                                                    name="is_active" value="1" checked>
                                                <label class="form-check-label" for="is_active">Active</label>
                                            </div>
                                            <small class="text-muted">Inactive staff cannot login</small>
                                        </div>
                                    </div>

                                    {{-- Custom Permissions (visible only for non-admin roles) --}}
                                    <div class="col-md-12" id="customPermissionsSection" style="display: none;">
                                        <div class="card mt-2">
                                            <div class="card-header bg-light">
                                                <h6 class="mb-0">Custom Permissions</h6>
                                                <small class="text-muted">Grant additional permissions beyond default role
                                                    permissions</small>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    @foreach ($permissionGroups as $groupName => $permissions)
                                                        <div class="col-md-6 mb-3">
                                                            <h6 class="border-bottom pb-1">{{ $groupName }}</h6>
                                                            @foreach ($permissions as $permission)
                                                                <div class="form-check">
                                                                    <input type="checkbox"
                                                                        class="form-check-input custom-permission"
                                                                        name="custom_permissions[]"
                                                                        value="{{ $permission }}"
                                                                        id="perm_{{ $permission }}">
                                                                    <label class="form-check-label"
                                                                        for="perm_{{ $permission }}">
                                                                        {{ ucfirst(str_replace('_', ' ', $permission)) }}
                                                                    </label>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2 mt-4">
                                    <a href="{{ route('vendor.staff.index') }}" class="btn btn-danger">
                                        <i class="ti ti-x me-1"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary" id="submitBtn">
                                        <i class="ti ti-plus me-1"></i> Create Staff
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div> <!-- container -->

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Role descriptions
            const roleDescriptions = {
                'admin': 'Full access to all store features. Can manage staff, products, orders, and settings.',
                'manager': 'Can manage products, orders, and view reports. Cannot manage staff or store settings.',
                'inventory': 'Can manage products and inventory only. Cannot manage orders or staff.',
                'fulfillment': 'Can manage orders and update order status. Cannot manage products or staff.',
                'support': 'Can view orders and provide customer support. Limited access to other features.'
            };

            // Role change handler
            $('#role').on('change', function() {
                let role = $(this).val();
                let description = roleDescriptions[role] || '';
                $('#roleDescription').text(description);

                // Show custom permissions section for non-admin roles
                if (role && role !== 'admin') {
                    $('#customPermissionsSection').slideDown();
                } else {
                    $('#customPermissionsSection').slideUp();
                    $('.custom-permission').prop('checked', false);
                }
            });

            // Trigger role change on page load if role is selected
            if ($('#role').val()) {
                $('#role').trigger('change');
            }

            // Toggle password visibility
            $('#togglePassword').on('click', function() {
                let passwordField = $('#password');
                let type = passwordField.attr('type') === 'password' ? 'text' : 'password';
                passwordField.attr('type', type);
                $(this).find('i').toggleClass('ti-eye ti-eye-off');
            });

            // Form validation and submission
            $('#staffForm').on('submit', function(e) {
                let isValid = true;

                // Required fields validation
                $(this).find('[required]').each(function() {
                    if (!$(this).val()) {
                        $(this).addClass('is-invalid');
                        isValid = false;
                    } else {
                        $(this).removeClass('is-invalid');
                    }
                });

                // Password match validation
                let password = $('#password').val();
                let passwordConfirmation = $('#password_confirmation').val();

                if (password !== passwordConfirmation) {
                    $('#password_confirmation').addClass('is-invalid');
                    $('#password_confirmation').after(
                        '<div class="invalid-feedback d-block">Passwords do not match</div>');
                    isValid = false;
                }

                if (!isValid) {
                    e.preventDefault();
                    $('html, body').animate({
                        scrollTop: $('.is-invalid:first').offset().top - 100
                    }, 500);
                    return false;
                }
            });

            // Remove error on input
            $('input, select, textarea').on('input change', function() {
                $(this).removeClass('is-invalid');
                $(this).next('.invalid-feedback').remove();
            });
        });
    </script>
@endpush

@push('styles')
    <style>
        .form-check-input:checked {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        .custom-permission {
            margin-top: 8px;
        }

        .custom-permission+label {
            font-size: 14px;
            cursor: pointer;
        }

        .card-header.bg-light {
            background-color: #f8f9fa;
        }

        .input-group-text {
            min-width: 40px;
            justify-content: center;
        }

        #roleDescription {
            display: block;
            margin-top: 5px;
            font-size: 12px;
        }
    </style>
@endpush
