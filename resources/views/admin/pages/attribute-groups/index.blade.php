{{-- resources/views/admin/pages/attribute-groups/index.blade.php --}}
@extends('management.layouts.app')

@section('title', 'Attribute Groups')

@section('content')
    <div class="page-content">
        <div class="page-container">
            <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                <div class="flex-grow-1">
                    <h4 class="fs-18 text-uppercase fw-bold mb-0">Attribute Groups Management</h4>
                </div>
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Attribute Groups</li>
                    </ol>
                </div>
            </div>

            {{-- Statistics Cards --}}
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">Total Groups</h6>
                                    <h2 class="mb-0">{{ $statistics['total'] ?? 0 }}</h2>
                                </div>
                                <i class="ti ti-layout-sidebar" style="font-size: 40px; opacity: 0.5;"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">Active Groups</h6>
                                    <h2 class="mb-0">{{ $statistics['active'] ?? 0 }}</h2>
                                </div>
                                <i class="ti ti-circle-check" style="font-size: 40px; opacity: 0.5;"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">With Attributes</h6>
                                    <h2 class="mb-0">{{ $statistics['with_attributes'] ?? 0 }}</h2>
                                </div>
                                <i class="ti ti-list" style="font-size: 40px; opacity: 0.5;"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-dark">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">Pending Approval</h6>
                                    <h2 class="mb-0">{{ $statistics['pending'] ?? 0 }}</h2>
                                </div>
                                <i class="ti ti-clock" style="font-size: 40px; opacity: 0.5;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <h3 class="card-title mb-0">Attribute Groups</h3>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.attribute-groups.analytics') }}" class="btn btn-info">
                                    <i class="ti ti-chart-bar me-1"></i> Analytics
                                </a>
                                
                                
                                    <a href="{{ route('admin.attribute-groups.create') }}" class="btn btn-primary">
                                        <i class="ti ti-plus me-1"></i> Add New Group
                                    </a>
                                
                            </div>
                        </div>
                        <div class="card-body">

                            {{-- Search and Filter --}}
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="searchInput"
                                            placeholder="Search by name..." value="{{ request('search') }}">
                                        <button class="btn btn-primary" type="button" id="searchBtn">
                                            <i class="ti ti-search"></i>
                                        </button>
                                        <button class="btn btn-secondary" type="button" id="clearSearch" style="display: none;">
                                            <i class="ti ti-x"></i> Clear
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="d-flex gap-2 justify-content-end flex-wrap">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                                <i class="ti ti-filter me-1"></i> Status
                                            </button>
                                            <ul class="dropdown-menu" id="statusFilter">
                                                <li><a class="dropdown-item" href="#" data-status="">All</a></li>
                                                <li><a class="dropdown-item" href="#" data-status="active">Active</a></li>
                                                <li><a class="dropdown-item" href="#" data-status="inactive">Inactive</a></li>
                                            </ul>
                                        </div>

                                        <div class="btn-group">
                                            <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                                <i class="ti ti-check-circle me-1"></i> Approval
                                            </button>
                                            <ul class="dropdown-menu" id="approvalFilter">
                                                <li><a class="dropdown-item" href="#" data-approval="">All</a></li>
                                                <li><a class="dropdown-item" href="#" data-approval="approved">Approved</a></li>
                                                <li><a class="dropdown-item" href="#" data-approval="pending">Pending</a></li>
                                                <li><a class="dropdown-item" href="#" data-approval="rejected">Rejected</a></li>
                                            </ul>
                                        </div>

                                        <div class="btn-group">
                                            <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                                <i class="ti ti-arrows-sort me-1"></i> Sort By
                                            </button>
                                            <ul class="dropdown-menu" id="sortFilter">
                                                <li><a class="dropdown-item" href="#" data-sort="order">Default Order</a></li>
                                                <li><a class="dropdown-item" href="#" data-sort="name">Name (A-Z)</a></li>
                                                <li><a class="dropdown-item" href="#" data-sort="attributes_count">Most Attributes</a></li>
                                                <li><a class="dropdown-item" href="#" data-sort="created_at">Newest First</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Bulk Actions --}}
                            @canany(['edit_attribute_groups', 'delete_attribute_groups'])
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <div class="btn-group flex-wrap gap-2">
                                            @can('edit_attribute_groups')
                                                <button type="button" class="btn btn-outline-success btn-sm" onclick="bulkAction('activate')">
                                                    <i class="ti ti-check"></i> Activate Selected
                                                </button>
                                                <button type="button" class="btn btn-outline-warning btn-sm" onclick="bulkAction('deactivate')">
                                                    <i class="ti ti-x"></i> Deactivate Selected
                                                </button>
                                                <button type="button" class="btn btn-outline-success btn-sm" onclick="bulkAction('approve')">
                                                    <i class="ti ti-check-circle"></i> Approve Selected
                                                </button>
                                                <button type="button" class="btn btn-outline-danger btn-sm" onclick="bulkAction('reject')">
                                                    <i class="ti ti-x-circle"></i> Reject Selected
                                                </button>
                                            @endcan
                                            @can('delete_attribute_groups')
                                                <button type="button" class="btn btn-outline-danger btn-sm" onclick="bulkAction('delete')">
                                                    <i class="ti ti-trash"></i> Delete Selected
                                                </button>
                                            @endcan
                                        </div>
                                    </div>
                                </div>
                            @endcanany

                            {{-- Groups Table Container --}}
                            <div id="groupsTableContainer">
                                @include('admin.pages.attribute-groups.partials.groups-table', compact('groups'))
                            </div>

                            {{-- Pagination Container --}}
                            <div id="paginationContainer" class="mt-3">
                                {{ $groups->appends(request()->query())->links('pagination::bootstrap-5') }}
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

    {{-- Bulk Action Form --}}
    <form id="bulkActionForm" method="POST" action="{{ route('admin.attribute-groups.bulk-action') }}" style="display: none;">
        @csrf
        <input type="hidden" name="action" id="bulkAction">
        <input type="hidden" name="group_ids" id="bulkGroupIds">
    </form>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            let currentFilters = {
                search: '{{ request('search') }}',
                status: '{{ request('status') }}',
                approval_status: '{{ request('approval_status') }}',
                sort_by: '{{ request('sort_by', 'order') }}',
                page: 1
            };

            function loadGroups() {
                $.ajax({
                    url: '{{ route("admin.attribute-groups.index") }}',
                    type: 'GET',
                    data: currentFilters,
                    beforeSend: function() {
                        $('#groupsTableContainer').html('<div class="text-center py-5"><div class="spinner-border text-primary" role="status"></div></div>');
                        $('#paginationContainer').html('');
                    },
                    success: function(response) {
                        $('#groupsTableContainer').html(response.table);
                        $('#paginationContainer').html(response.pagination);
                        if (response.statistics) updateStatistics(response.statistics);
                        let url = new URL(window.location);
                        Object.keys(currentFilters).forEach(key => {
                            if (currentFilters[key]) url.searchParams.set(key, currentFilters[key]);
                            else url.searchParams.delete(key);
                        });
                        window.history.pushState({}, '', url);
                        $('[data-bs-toggle="tooltip"]').tooltip();
                        $('#selectAll').off('change').on('change', function() {
                            $('.group-checkbox').prop('checked', $(this).prop('checked'));
                        });
                        $('.group-checkbox').off('change').on('change', function() {
                            let allChecked = $('.group-checkbox:checked').length === $('.group-checkbox').length;
                            $('#selectAll').prop('checked', allChecked);
                        });
                        $('.toggle-status').off('change').on('change', function() {
                            toggleStatus($(this).data('id'), this);
                        });
                    },
                    error: function() {
                        $('#groupsTableContainer').html('<div class="alert alert-danger">Error loading groups</div>');
                    }
                });
            }

            function updateStatistics(statistics) {
                $('.bg-primary .h2').text(statistics.total || 0);
                $('.bg-success .h2').text(statistics.active || 0);
                $('.bg-info .h2').text(statistics.with_attributes || 0);
                $('.bg-warning .h2').text(statistics.pending || 0);
            }

            $('#searchBtn').on('click', function() {
                currentFilters.search = $('#searchInput').val();
                currentFilters.page = 1;
                loadGroups();
                $('#clearSearch').toggle(currentFilters.search !== '');
            });

            $('#searchInput').on('keypress', function(e) {
                if (e.which === 13) $('#searchBtn').click();
            });

            $('#clearSearch').on('click', function() {
                $('#searchInput').val('');
                currentFilters.search = '';
                currentFilters.page = 1;
                loadGroups();
                $(this).hide();
            });

            $('#statusFilter .dropdown-item').on('click', function(e) {
                e.preventDefault();
                currentFilters.status = $(this).data('status');
                currentFilters.page = 1;
                loadGroups();
            });

            $('#approvalFilter .dropdown-item').on('click', function(e) {
                e.preventDefault();
                currentFilters.approval_status = $(this).data('approval');
                currentFilters.page = 1;
                loadGroups();
            });

            $('#sortFilter .dropdown-item').on('click', function(e) {
                e.preventDefault();
                currentFilters.sort_by = $(this).data('sort');
                currentFilters.page = 1;
                loadGroups();
            });

            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault();
                let page = $(this).attr('href').split('page=')[1];
                if (page) {
                    currentFilters.page = page;
                    loadGroups();
                }
            });

            if ($('#searchInput').val()) $('#clearSearch').show();
        });

        function toggleStatus(groupId, element) {
            let isChecked = $(element).prop('checked');
            $.ajax({
                url: '{{ url("admin/attribute-groups") }}/' + groupId + '/toggle-status',
                type: 'POST',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({ icon: 'success', title: 'Updated!', text: response.message, timer: 1500, showConfirmButton: false });
                    }
                },
                error: function() {
                    $(element).prop('checked', !isChecked);
                    Swal.fire({ icon: 'error', title: 'Error!', text: 'Failed to update status.', confirmButtonColor: '#d33' });
                }
            });
        }

        function confirmDelete(groupId, groupName) {
            Swal.fire({
                title: 'Delete Group?',
                text: `Are you sure you want to delete "${groupName}"? This will also remove attributes from this group.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    let form = $('#deleteForm');
                    form.attr('action', '{{ url("admin/attribute-groups") }}/' + groupId);
                    $.ajax({
                        url: form.attr('action'),
                        type: 'POST',
                        data: form.serialize(),
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({ icon: 'success', title: 'Deleted!', text: response.message, timer: 1500, showConfirmButton: false }).then(() => location.reload());
                            } else {
                                Swal.fire({ icon: 'error', title: 'Cannot Delete!', text: response.message, confirmButtonColor: '#d33' });
                            }
                        }
                    });
                }
            });
        }

        function bulkAction(action) {
            let selectedGroups = [];
            $('.group-checkbox:checked').each(function() { selectedGroups.push($(this).val()); });
            if (selectedGroups.length === 0) {
                Swal.fire({ icon: 'warning', title: 'No Selection', text: 'Please select at least one group.', confirmButtonColor: '#6c757d' });
                return;
            }
            let actionText = '', confirmColor = '#28a745';
            switch (action) {
                case 'activate': actionText = 'activate'; break;
                case 'deactivate': actionText = 'deactivate'; break;
                case 'approve': actionText = 'approve'; break;
                case 'reject': actionText = 'reject'; confirmColor = '#dc3545'; break;
                case 'delete': actionText = 'delete'; confirmColor = '#d33'; break;
            }
            Swal.fire({
                title: `${actionText.toUpperCase()} Groups?`,
                text: `Are you sure you want to ${actionText} ${selectedGroups.length} selected group(s)?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: confirmColor,
                cancelButtonColor: '#6c757d',
                confirmButtonText: `Yes, ${actionText} them!`
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#bulkAction').val(action);
                    $('#bulkGroupIds').val(JSON.stringify(selectedGroups));
                    $.ajax({
                        url: $('#bulkActionForm').attr('action'),
                        type: 'POST',
                        data: $('#bulkActionForm').serialize(),
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({ icon: 'success', title: 'Success!', text: response.message, timer: 1500, showConfirmButton: false }).then(() => location.reload());
                            }
                        },
                        error: function(xhr) {
                            Swal.fire({ icon: 'error', title: 'Error!', text: xhr.responseJSON?.message || 'Failed to process bulk action.', confirmButtonColor: '#d33' });
                        }
                    });
                }
            });
        }
    </script>
@endpush

@push('styles')
    <style>
        .btn-icon {
            width: 32px;
            height: 32px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50% !important;
        }
    </style>
@endpush