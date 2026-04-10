{{-- resources/views/admin/pages/vendors/partials/vendors-table.blade.php --}}

<div class="table-responsive">
    <table class="table table-hover text-nowrap mb-0">
        <thead class="bg-dark-subtle">
            <tr>
                <th class="ps-3" style="width: 50px;">
                    <input type="checkbox" class="form-check-input" id="selectAll">
                </th>
                <th>Shop ID</th>
                <th>Shop</th>
                <th>Owner</th>
                <th>Contact</th>
                <th>Profile</th>
                <th>Ready</th>
                <th>Status</th>
                <th>Joined</th>
                <th class="text-center" style="width: 130px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($shops ?? [] as $shop)
                <tr>
                    <td class="ps-3">
                        <input type="checkbox" class="form-check-input shop-checkbox" value="{{ $shop->id }}">
                    </td>
                    <td>
                        <span class="fw-semibold">#{{ str_pad($shop->id, 5, '0', STR_PAD_LEFT) }}</span>
                    </td>
                    <td>
                        <div>
                            <div class="fw-semibold">{{ $shop->shop_name ?? 'N/A' }}</div>
                            @if ($shop->shop_slug)
                                <small class="text-muted">Slug: {{ $shop->shop_slug }}</small>
                            @endif
                        </div>
                    </td>
                    <td>
                        <div class="d-flex justify-content-start align-items-center gap-3">
                            <div class="avatar-md">
                                @if ($shop->owner && $shop->owner->avatar)
                                    <img src="{{ Storage::url($shop->owner->avatar) }}" alt="{{ $shop->owner->name }}"
                                        class="img-fluid rounded-circle"
                                        style="width: 40px; height: 40px; object-fit: cover;">
                                @else
                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white"
                                        style="width: 40px; height: 40px; font-size: 16px;">
                                        {{ substr($shop->owner->name ?? 'S', 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            <div>
                                <div class="fw-semibold">{{ $shop->owner->name ?? 'N/A' }}</div>
                                <small class="text-muted">{{ $shop->owner->email ?? 'N/A' }}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div>
                            <div><i class="ti ti-mail me-1"></i> {{ $shop->shop_email ?? 'N/A' }}</div>
                            @if ($shop->shop_phone)
                                <small><i class="ti ti-phone me-1"></i> {{ $shop->shop_phone }}</small>
                            @endif
                        </div>
                    </td>
                    <td>
                        <div class="profile-completion">
                            <div class="d-flex justify-content-between small mb-1">
                                <span>Completion</span>
                                <span>{{ $shop->profile_completed ?? 0 }}%</span>
                            </div>
                            <div class="progress">
                                <div class="progress-bar bg-info" style="width: {{ $shop->profile_completed ?? 0 }}%">
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>
                        @if ($shop->ready_for_approve)
                            <span class="badge bg-success-subtle text-success">
                                <i class="ti ti-check"></i> Yes
                            </span>
                        @else
                            <span class="badge bg-secondary-subtle text-secondary">
                                <i class="ti ti-hourglass"></i> No
                            </span>
                        @endif
                    </td>
                    <td>
                        <div>
                            @if ($shop->owner && $shop->owner->is_active)
                                <span class="badge bg-success-subtle text-success fs-12 p-2">
                                    <i class="ti ti-circle-check me-1"></i>Active
                                </span>
                            @else
                                <span class="badge bg-danger-subtle text-danger fs-12 p-2">
                                    <i class="ti ti-circle-x me-1"></i>Inactive
                                </span>
                            @endif
                        </div>
                        <div class="mt-1">
                            @if ($shop->verification_status === 'verified')
                                <span class="badge bg-info-subtle text-info fs-10 p-1">Verified</span>
                            @elseif($shop->verification_status === 'pending')
                                <span class="badge bg-warning-subtle text-warning fs-10 p-1">Pending</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary fs-10 p-1">Rejected</span>
                            @endif
                        </div>
                    </td>
                    <td>
                        <div>{{ $shop->created_at->format('d M Y') }}</div>
                        <small class="text-muted">{{ $shop->created_at->diffForHumans() }}</small>
                    </td>
                    <td class="pe-3">
                        <div class="hstack gap-1 justify-content-end">
                            {{-- Show Button --}}
                            <a href="{{ route('admin.vendors.show', $shop->id) }}"
                                class="btn btn-soft-primary btn-icon btn-sm rounded-circle" data-bs-toggle="tooltip"
                                title="View Shop Details">
                                <i class="ti ti-eye"></i>
                            </a>

                            {{-- Send Message Button --}}
                            <button type="button" class="btn btn-soft-info btn-icon btn-sm rounded-circle"
                                onclick="showMessageModal({{ $shop->id }}, '{{ addslashes($shop->shop_name) }}')"
                                data-bs-toggle="tooltip" title="Send Message to Vendor">
                                <i class="ti ti-message"></i>
                            </button>

                            {{-- View Vendors (Staff) Button --}}
                            <a href="" class="btn btn-soft-success btn-icon btn-sm rounded-circle"
                                data-bs-toggle="tooltip" title="View Staff Members">
                                <i class="ti ti-users"></i>
                            </a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center py-5">
                        <div class="empty-state">
                            <i class="ti ti-building-store" style="font-size: 48px; opacity: 0.5;"></i>
                            <h5 class="mt-3">No Shops Found</h5>
                            <p class="text-muted">No shops have registered yet.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
