{{-- resources/views/admin/pages/vendors/partials/vendors-table.blade.php --}}

<div class="table-responsive">
    <table class="table table-hover mb-0">
        <thead class="table-light">
            <tr>
                <th width="30">
                    <input type="checkbox" id="selectAll">
                </th>
                <th>ID</th>
                <th>Shop</th>
                <th>Owner</th>
                <th>Contact</th>
                <th>Role</th>
                <th>Profile</th>
                <th>Status</th>
                <th>Joined</th>
                <th width="200">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($vendors as $vendor)
            <tr>
                <td>
                    <input type="checkbox" class="vendor-checkbox" value="{{ $vendor->id }}">
                </td>
                <td>#{{ $vendor->id }}</td>
                <td>
                    <div class="d-flex align-items-center">
                        @if($vendor->shop_logo)
                            <img src="{{ Storage::url($vendor->shop_logo) }}" width="40" class="rounded me-2">
                        @else
                            <div class="avatar-sm bg-light rounded me-2 d-flex align-items-center justify-content-center">
                                <i class="ti ti-building-store"></i>
                            </div>
                        @endif
                        <div>
                            <strong>{{ $vendor->shop_name }}</strong><br>
                            <small class="text-muted">{{ $vendor->shop_slug }}</small>
                        </div>
                    </div>
                </td>
                <td>
                    {{ $vendor->name }}<br>
                    <small class="text-muted">{{ $vendor->email }}</small>
                </td>
                <td>
                    {{ $vendor->shop_phone }}<br>
                    <small class="text-muted">{{ $vendor->shop_email }}</small>
                </td>
                <td>
                    @if($vendor->hasRole('store_owner'))
                        <span class="badge bg-success">Store Owner</span>
                    @elseif($vendor->hasRole('vendor'))
                        <span class="badge bg-warning">Vendor (Pending)</span>
                    @else
                        <span class="badge bg-secondary">{{ $vendor->roles->first()->name ?? 'No Role' }}</span>
                    @endif
                </td>
                <td>
                    <div class="progress" style="height: 5px; width: 80px;">
                        <div class="progress-bar bg-info" style="width: {{ $vendor->profile_completed }}%"></div>
                    </div>
                    <small>{{ $vendor->profile_completed }}% Complete</small>
                </td>
                <td>
                    @if($vendor->account_status == 'active')
                        <span class="badge bg-success">Active</span>
                    @elseif($vendor->account_status == 'pending')
                        <span class="badge bg-warning">Pending</span>
                    @elseif($vendor->account_status == 'suspended')
                        <span class="badge bg-danger">Suspended</span>
                    @elseif($vendor->account_status == 'rejected')
                        <span class="badge bg-dark">Rejected</span>
                    @endif
                </td>
                <td>{{ $vendor->created_at->format('d M Y') }}</td>
                <td>
                    <div class="d-flex gap-1 flex-wrap">
                        {{-- View Details Button --}}
                        <a href="{{ route('admin.vendors.show', $vendor->id) }}"
                            class="btn btn-soft-primary btn-icon btn-sm rounded-circle" 
                            data-bs-toggle="tooltip" 
                            title="View Vendor Details">
                            <i class="ti ti-eye"></i>
                        </a>

                        {{-- Approve Button (only for pending vendors with profile >= 80%) --}}
                        @if($vendor->hasRole('vendor') && $vendor->profile_completed >= 80)
                            <a href="{{ route('admin.vendors.approve.form', $vendor->id) }}"
                                class="btn btn-soft-success btn-icon btn-sm rounded-circle" 
                                data-bs-toggle="tooltip" 
                                title="Approve Vendor">
                                <i class="ti ti-check"></i>
                            </a>
                        @endif

                        {{-- Activate Button (for suspended vendors) --}}
                        @if($vendor->account_status == 'suspended')
                            <a href="{{ route('admin.vendors.activate', $vendor->id) }}"
                                class="btn btn-soft-success btn-icon btn-sm rounded-circle" 
                                data-bs-toggle="tooltip" 
                                title="Activate Vendor"
                                onclick="return confirm('Activate this vendor?')">
                                <i class="ti ti-player-play"></i>
                            </a>
                        @endif

                        {{-- Suspend Button (for active vendors) --}}
                        @if($vendor->account_status == 'active')
                            <button type="button"
                                class="btn btn-soft-warning btn-icon btn-sm rounded-circle" 
                                data-bs-toggle="tooltip" 
                                title="Suspend Vendor"
                                onclick="showSuspendModal({{ $vendor->id }}, '{{ addslashes($vendor->shop_name) }}')">
                                <i class="ti ti-pause"></i>
                            </button>
                        @endif

                        {{-- Reject Button (for pending vendors) --}}
                        @if($vendor->account_status == 'pending' || $vendor->hasRole('vendor'))
                            <button type="button"
                                class="btn btn-soft-danger btn-icon btn-sm rounded-circle" 
                                data-bs-toggle="tooltip" 
                                title="Reject Vendor"
                                onclick="showRejectModal({{ $vendor->id }}, '{{ addslashes($vendor->shop_name) }}')">
                                <i class="ti ti-x"></i>
                            </button>
                        @endif

                        {{-- Delete Button --}}
                        <button type="button"
                            class="btn btn-soft-danger btn-icon btn-sm rounded-circle" 
                            data-bs-toggle="tooltip" 
                            title="Delete Vendor"
                            onclick="confirmDelete({{ $vendor->id }})">
                            <i class="ti ti-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="10" class="text-center py-4">
                    <i class="ti ti-users fs-1 text-muted"></i>
                    <p class="mt-2">No vendors found</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>