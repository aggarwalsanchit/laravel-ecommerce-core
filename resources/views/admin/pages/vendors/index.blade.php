{{-- resources/views/admin/pages/vendors/index.blade.php --}}

@extends('management.layouts.app')

@section('title', ($websiteSettings->website_name ?? 'Boron') . ' - Vendors')

@section('content')
    <div class="page-content">
        <div class="page-container">

            <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                <div class="flex-grow-1">
                    <h4 class="fs-18 text-uppercase fw-bold mb-0">Vendors</h4>
                </div>
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a
                                href="{{ route('admin.dashboard') }}">{{ $websiteSettings->website_name ?? 'Boron' }}</a>
                        </li>
                        <li class="breadcrumb-item active">Vendors</li>
                    </ol>
                </div>
            </div>

            {{-- Statistics Cards --}}
            <div class="row">
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="text-muted fs-13 text-uppercase">Total Shops</h5>
                                    <h3 class="mb-0 fw-bold">{{ $stats['total'] ?? 0 }}</h3>
                                </div>
                                <div class="avatar-sm bg-primary-subtle rounded-circle p-2">
                                    <i class="ti ti-building-store fs-24 text-primary"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="text-muted fs-13 text-uppercase">Pending</h5>
                                    <h3 class="mb-0 fw-bold text-success">{{ $stats['pending'] ?? 0 }}</h3>
                                </div>
                                <div class="avatar-sm bg-success-subtle rounded-circle p-2">
                                    <i class="ti ti-circle-check fs-24 text-success"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="text-muted fs-13 text-uppercase">Verified</h5>
                                    <h3 class="mb-0 fw-bold text-danger">{{ $stats['verified'] ?? 0 }}</h3>
                                </div>
                                <div class="avatar-sm bg-danger-subtle rounded-circle p-2">
                                    <i class="ti ti-circle-x fs-24 text-danger"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="text-muted fs-13 text-uppercase">Rejected</h5>
                                    <h3 class="mb-0 fw-bold text-info">{{ $stats['rejected'] ?? 0 }}</h3>
                                </div>
                                <div class="avatar-sm bg-info-subtle rounded-circle p-2">
                                    <i class="ti ti-check fs-24 text-info"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="text-muted fs-13 text-uppercase">Suspended</h5>
                                    <h3 class="mb-0 fw-bold text-info">{{ $stats['suspended'] ?? 0 }}</h3>
                                </div>
                                <div class="avatar-sm bg-info-subtle rounded-circle p-2">
                                    <i class="ti ti-check fs-24 text-info"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="card-title">Vendor Management</h3>
                        </div>
                        <div class="card-body">

                            {{-- Search and Filter --}}
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <form action="{{ route('admin.vendors.index') }}" method="GET" class="d-flex gap-2"
                                        id="searchForm">
                                        <input type="text" name="search" class="form-control" id="searchInput"
                                            placeholder="Search by shop name, owner, email..."
                                            value="{{ request('search') }}">
                                        <button type="submit" class="btn btn-primary" id="searchBtn">
                                            <i class="ti ti-search"></i>
                                        </button>
                                        @if (request('search'))
                                            <a href="{{ route('admin.vendors.index') }}" class="btn btn-secondary"
                                                id="clearSearch">Clear</a>
                                        @endif
                                    </form>
                                </div>
                                <div class="col-md-8 text-end">
                                    <div class="btn-group me-2">
                                        <button type="button" class="btn btn-outline-secondary dropdown-toggle"
                                            data-bs-toggle="dropdown">
                                            Verification
                                        </button>
                                        <ul class="dropdown-menu" id="verificationFilter">
                                            <li><a class="dropdown-item {{ !request('verification') ? 'active' : '' }}"
                                                    href="#" data-verification="">All</a></li>
                                            <li><a class="dropdown-item {{ request('verification') == 'verified' ? 'active' : '' }}"
                                                    href="#" data-verification="verified">Verified</a></li>
                                            <li><a class="dropdown-item {{ request('verification') == 'pending' ? 'active' : '' }}"
                                                    href="#" data-verification="pending">Pending</a></li>
                                            <li><a class="dropdown-item {{ request('verification') == 'rejected' ? 'active' : '' }}"
                                                    href="#" data-verification="rejected">Rejected</a></li>
                                            <li><a class="dropdown-item {{ request('verification') == 'suspended' ? 'active' : '' }}"
                                                    href="#" data-verification="rejected">Suspended</a></li>
                                        </ul>
                                    </div>

                                    <div class="btn-group">
                                        <button type="button" class="btn btn-outline-secondary dropdown-toggle"
                                            data-bs-toggle="dropdown">
                                            Ready For Approve
                                        </button>
                                        <ul class="dropdown-menu" id="readyForApproveFilter">
                                            <li><a class="dropdown-item {{ !request('ready_for_approve') ? 'active' : '' }}"
                                                    href="#" data-ready-for-approve="">All</a></li>
                                            <li><a class="dropdown-item {{ request('ready_for_approve') == 'yes' ? 'active' : '' }}"
                                                    href="#" data-ready-for-approve="yes">Yes (Ready)</a></li>
                                            <li><a class="dropdown-item {{ request('ready_for_approve') == 'no' ? 'active' : '' }}"
                                                    href="#" data-ready-for-approve="no">No (Not Ready)</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            {{-- Shops Table --}}
                            <div class="table-responsive" id="vendorsTableContainer">
                                @include('admin.pages.vendors.partials.vendors-table', ['shops' => $shops])
                            </div>

                            {{-- Pagination --}}
                            <div class="mt-3" id="paginationContainer">
                                <div class="d-flex justify-content-end">
                                    {{ $shops->appends(request()->query())->links('pagination::bootstrap-5') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Delete Form --}}
    <form id="deleteForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    {{-- Message Modal --}}
    <div class="modal fade" id="messageModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Send Message to Vendor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="messageShopId">
                    <div class="mb-3">
                        <label class="form-label">Message Type</label>
                        <select id="messageType" class="form-select">
                            <option value="general">General</option>
                            <option value="approval">Approval</option>
                            <option value="suspension">Suspension</option>
                            <option value="verification">Verification</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Message</label>
                        <textarea id="messageText" class="form-control" rows="4" placeholder="Enter your message..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="sendMessage()">Send Message</button>
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
            let currentFilters = {
                search: '{{ request('search') }}',
                status: '{{ request('status') }}',
                verification: '{{ request('verification') }}',
                vendor_type: '{{ request('vendor_type') }}',
                ready_for_approve: '{{ request('ready_for_approve') }}',
                page: {{ request('page', 1) }}
            };

            // Search with debounce
            let searchTimer;
            $('#searchInput').on('keyup', function() {
                clearTimeout(searchTimer);
                searchTimer = setTimeout(() => {
                    currentFilters.search = $(this).val();
                    currentFilters.page = 1;
                    loadShops();
                }, 500);
            });

            $('#searchForm').on('submit', function(e) {
                e.preventDefault();
                currentFilters.search = $('#searchInput').val();
                currentFilters.page = 1;
                loadShops();
            });

            $('#clearSearch').on('click', function(e) {
                e.preventDefault();
                $('#searchInput').val('');
                currentFilters.search = '';
                currentFilters.page = 1;
                loadShops();
            });

            // Status filter
            $('#statusFilter .dropdown-item').on('click', function(e) {
                e.preventDefault();
                let status = $(this).data('status');
                $('#statusFilter .dropdown-item').removeClass('active');
                $(this).addClass('active');
                let buttonText = $(this).text();
                $('#statusFilter').closest('.btn-group').find('.dropdown-toggle').html(buttonText +
                    ' <i class="ti ti-chevron-down"></i>');
                currentFilters.status = status;
                currentFilters.page = 1;
                loadShops();
            });

            // Verification filter
            $('#verificationFilter .dropdown-item').on('click', function(e) {
                e.preventDefault();
                let verification = $(this).data('verification');
                $('#verificationFilter .dropdown-item').removeClass('active');
                $(this).addClass('active');
                let buttonText = $(this).text();
                $('#verificationFilter').closest('.btn-group').find('.dropdown-toggle').html(buttonText +
                    ' <i class="ti ti-chevron-down"></i>');
                currentFilters.verification = verification;
                currentFilters.page = 1;
                loadShops();
            });

            // Vendor type filter
            $('#vendorTypeFilter .dropdown-item').on('click', function(e) {
                e.preventDefault();
                let vendorType = $(this).data('vendor-type');
                $('#vendorTypeFilter .dropdown-item').removeClass('active');
                $(this).addClass('active');
                let buttonText = $(this).text();
                $('#vendorTypeFilter').closest('.btn-group').find('.dropdown-toggle').html(buttonText +
                    ' <i class="ti ti-chevron-down"></i>');
                currentFilters.vendor_type = vendorType;
                currentFilters.page = 1;
                loadShops();
            });

            // Ready For Approve filter
            $('#readyForApproveFilter .dropdown-item').on('click', function(e) {
                e.preventDefault();
                let readyForApprove = $(this).data('ready-for-approve');
                $('#readyForApproveFilter .dropdown-item').removeClass('active');
                $(this).addClass('active');
                let buttonText = $(this).text();
                $('#readyForApproveFilter').closest('.btn-group').find('.dropdown-toggle').html(buttonText +
                    ' <i class="ti ti-chevron-down"></i>');
                currentFilters.ready_for_approve = readyForApprove;
                currentFilters.page = 1;
                loadShops();
            });

            function loadShops() {
                $.ajax({
                    url: '{{ route('admin.vendors.index') }}',
                    type: 'GET',
                    data: currentFilters,
                    beforeSend: function() {
                        $('#vendorsTableContainer').html(
                            '<div class="text-center py-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>'
                        );
                    },
                    success: function(response) {
                        $('#vendorsTableContainer').html(response.table);
                        $('#paginationContainer').html(response.pagination);
                        $('[data-bs-toggle="tooltip"]').tooltip();
                        let url = new URL(window.location);
                        url.searchParams.set('search', currentFilters.search || '');
                        url.searchParams.set('status', currentFilters.status || '');
                        url.searchParams.set('verification', currentFilters.verification || '');
                        url.searchParams.set('vendor_type', currentFilters.vendor_type || '');
                        url.searchParams.set('ready_for_approve', currentFilters.ready_for_approve ||
                            '');
                        url.searchParams.set('page', currentFilters.page);
                        window.history.pushState({}, '', url);
                    },
                    error: function(xhr) {
                        console.error('Error loading shops:', xhr);
                        $('#vendorsTableContainer').html(
                            '<div class="alert alert-danger">Error loading shops. Please try again.</div>'
                        );
                    }
                });
            }

            // Select All Checkbox
            $(document).on('change', '#selectAll', function() {
                $('.shop-checkbox').prop('checked', $(this).prop('checked'));
            });

            $('[data-bs-toggle="tooltip"]').tooltip();
        });

        // Send Message
        function showMessageModal(shopId, shopName) {
            $('#messageShopId').val(shopId);
            $('#messageModal').modal('show');
        }

        function sendMessage() {
            let shopId = $('#messageShopId').val();
            let type = $('#messageType').val();
            let message = $('#messageText').val();

            if (!message) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Missing Message',
                    text: 'Please enter a message.'
                });
                return;
            }

            Swal.fire({
                title: 'Sending...',
                text: 'Please wait',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            $.ajax({
                url: '{{ url('admin/vendors') }}/' + shopId + '/send-message',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    type: type,
                    message: message
                },
                success: function(response) {
                    if (response.success) {
                        $('#messageModal').modal('hide');
                        $('#messageText').val('');
                        Swal.fire({
                            icon: 'success',
                            title: 'Sent!',
                            text: response.message,
                            timer: 1500,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: response.message
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Something went wrong.'
                    });
                }
            });
        }

        // Delete Shop
        function confirmDelete(shopId, shopName) {
            Swal.fire({
                title: 'Delete Shop',
                text: `Are you sure you want to delete "${shopName}"? This action cannot be undone!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    let form = $('#deleteForm');
                    form.attr('action', '{{ url('admin/vendors') }}/' + shopId);
                    form.submit();
                }
            });
        }
    </script>
@endpush

@push('styles')
    <style>
        .avatar-sm {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .avatar-sm i {
            font-size: 24px;
        }

        .bg-primary-subtle {
            background-color: rgba(13, 110, 253, 0.1);
        }

        .bg-success-subtle {
            background-color: rgba(25, 135, 84, 0.1);
        }

        .bg-danger-subtle {
            background-color: rgba(220, 53, 69, 0.1);
        }

        .bg-warning-subtle {
            background-color: rgba(255, 193, 7, 0.1);
        }

        .bg-info-subtle {
            background-color: rgba(13, 202, 240, 0.1);
        }

        .btn-icon {
            width: 32px;
            height: 32px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .table> :not(caption)>*>* {
            vertical-align: middle;
        }

        .profile-completion {
            width: 80px;
        }

        .profile-completion .progress {
            height: 6px;
        }

        .page-link {
            cursor: pointer;
        }
    </style>
@endpush
