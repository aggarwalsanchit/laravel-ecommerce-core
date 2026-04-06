{{-- resources/views/admin/profile/edit.blade.php --}}

@extends('management.layouts.app')

@section('title', 'Edit Profile')

@section('content')
<div class="page-content">
    <div class="page-container">
        <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
            <div class="flex-grow-1">
                <h4 class="fs-18 text-uppercase fw-bold mb-0">Edit Profile</h4>
                <p class="text-muted mb-0">Update your profile information</p>
            </div>
            <div class="text-end">
                <ol class="breadcrumb m-0 py-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.profile.index') }}">Profile</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Edit Profile Information</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data" id="profileForm">
                            @csrf
                            @method('PUT')

                            {{-- Avatar Section --}}
                            <div class="text-center mb-4">
                                <div class="position-relative d-inline-block">
                                    @php
                                        $avatarUrl = $admin->avatar && Storage::disk('public')->exists($admin->avatar) 
                                            ? Storage::url($admin->avatar) 
                                            : asset('dummy-avatar.jpg');
                                    @endphp
                                    <img src="{{ $avatarUrl }}" id="avatarPreview" class="rounded-circle" width="100" height="100" style="object-fit: cover;">
                                    <div class="position-absolute bottom-0 end-0">
                                        <label for="avatar_input" class="btn btn-sm btn-primary rounded-circle" style="width: 32px; height: 32px; padding: 0; display: flex; align-items: center; justify-content: center; cursor: pointer;">
                                            <i class="ti ti-camera fs-14"></i>
                                        </label>
                                        <input type="file" id="avatar_input" name="avatar" class="d-none" accept="image/*">
                                    </div>
                                </div>
                                <p class="text-muted small mt-2">Click camera icon to change avatar</p>
                            </div>

                            <div class="row">
                                {{-- Personal Information --}}
                                <div class="col-12">
                                    <h6 class="border-bottom pb-2 mb-3">Personal Information</h6>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                            value="{{ old('name', $admin->name) }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Email Address <span class="text-danger">*</span></label>
                                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                            value="{{ old('email', $admin->email) }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                {{-- Phone with Country Code --}}
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">Phone Number</label>
                                        <div class="row g-2">
                                            <div class="col-md-3">
                                                <select name="phone_code" id="phone_code" class="form-select @error('phone_code') is-invalid @enderror">
                                                    <option value="">Code</option>
                                                    @foreach($countries as $country)
                                                        <option value="{{ $country->phonecode }}" 
                                                            data-country-id="{{ $country->id }}"
                                                            {{ old('phone_code', $admin->phone_code) == $country->phonecode ? 'selected' : '' }}>
                                                            +{{ $country->phonecode }} ({{ $country->iso2 }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-9">
                                                <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                                                    value="{{ old('phone', $admin->phone) }}" placeholder="Enter phone number">
                                            </div>
                                        </div>
                                        @error('phone_code')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Select country code and enter your phone number</small>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Birth Date</label>
                                        <input type="date" name="birth_date" class="form-control @error('birth_date') is-invalid @enderror" 
                                            value="{{ old('birth_date', $admin->birth_date ? \Carbon\Carbon::parse($admin->birth_date)->format('Y-m-d') : '') }}">
                                        @error('birth_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Address Information --}}
                                <div class="col-12">
                                    <h6 class="border-bottom pb-2 mb-3 mt-2">Address Information</h6>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Street Address</label>
                                        <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="2">{{ old('address', $admin->address) }}</textarea>
                                        @error('address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Country</label>
                                        <select name="country_id" id="country_id" class="form-select @error('country_id') is-invalid @enderror">
                                            <option value="">Select Country</option>
                                            @foreach($countries as $country)
                                                <option value="{{ $country->id }}" 
                                                    data-phone-code="{{ $country->phonecode }}"
                                                    {{ old('country_id', $admin->country_id) == $country->id ? 'selected' : '' }}>
                                                    {{ $country->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('country_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">State/Province</label>
                                        <select name="state_id" id="state_id" class="form-select @error('state_id') is-invalid @enderror">
                                            <option value="">Select State</option>
                                            @foreach($states as $state)
                                                <option value="{{ $state->id }}" {{ old('state_id', $admin->state_id) == $state->id ? 'selected' : '' }}>
                                                    {{ $state->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('state_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">City</label>
                                        <select name="city_id" id="city_id" class="form-select @error('city_id') is-invalid @enderror">
                                            <option value="">Select City</option>
                                            @foreach($cities as $city)
                                                <option value="{{ $city->id }}" {{ old('city_id', $admin->city_id) == $city->id ? 'selected' : '' }}>
                                                    {{ $city->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('city_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">Postal / ZIP Code</label>
                                        <input type="text" name="postal_code" class="form-control @error('postal_code') is-invalid @enderror" 
                                            value="{{ old('postal_code', $admin->postal_code) }}">
                                        @error('postal_code')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2 mt-3">
                                <a href="{{ route('admin.profile.index') }}" class="btn btn-secondary">
                                    <i class="ti ti-x"></i> Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="ti ti-save"></i> Update Profile
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
<script>
    $(document).ready(function() {
        // Avatar preview
        $('#avatar_input').on('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#avatarPreview').attr('src', e.target.result);
                }
                reader.readAsDataURL(file);
            }
        });
        
        // Store original values for state and city
        var originalCountryId = $('#country_id').val();
        var originalStateId = $('#state_id').val();
        var originalCityId = $('#city_id').val();
        
        // Country change - load states
        $('#country_id').on('change', function() {
            var countryId = $(this).val();
            var selectedOption = $(this).find('option:selected');
            var phoneCode = selectedOption.data('phone-code');
            
            // Update phone code
            if (phoneCode) {
                $('#phone_code').val(phoneCode);
            }
            
            if (countryId) {
                $.ajax({
                    url: '/admin/location/states/' + countryId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#state_id').empty();
                        $('#state_id').append('<option value="">Select State</option>');
                        $.each(data, function(key, state) {
                            var selected = (originalStateId == state.id) ? 'selected' : '';
                            $('#state_id').append('<option value="' + state.id + '" ' + selected + '>' + state.name + '</option>');
                        });
                        
                        // Trigger state change to load cities
                        if (originalStateId) {
                            $('#state_id').trigger('change');
                        } else {
                            $('#city_id').empty();
                            $('#city_id').append('<option value="">Select City</option>');
                        }
                    },
                    error: function(xhr) {
                        console.error('Error loading states:', xhr);
                    }
                });
            } else {
                $('#state_id').empty();
                $('#state_id').append('<option value="">Select State</option>');
                $('#city_id').empty();
                $('#city_id').append('<option value="">Select City</option>');
            }
        });
        
        // State change - load cities
        $('#state_id').on('change', function() {
            var stateId = $(this).val();
            if (stateId) {
                $.ajax({
                    url: '/admin/location/cities/' + stateId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#city_id').empty();
                        $('#city_id').append('<option value="">Select City</option>');
                        $.each(data, function(key, city) {
                            var selected = (originalCityId == city.id) ? 'selected' : '';
                            $('#city_id').append('<option value="' + city.id + '" ' + selected + '>' + city.name + '</option>');
                        });
                    },
                    error: function(xhr) {
                        console.error('Error loading cities:', xhr);
                    }
                });
            } else {
                $('#city_id').empty();
                $('#city_id').append('<option value="">Select City</option>');
            }
        });
        
        // Trigger country change on page load to load states and cities
        if (originalCountryId) {
            $('#country_id').trigger('change');
        }
    });
</script>
@endpush