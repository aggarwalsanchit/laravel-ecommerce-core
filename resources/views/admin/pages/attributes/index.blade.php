{{-- resources/views/admin/pages/attributes/index.blade.php --}}
@extends('management.layouts.app')

@section('title', 'Attributes')

@section('content')
    <div class="page-content">
        <div class="page-container">
            <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                <div class="flex-grow-1">
                    <h4 class="fs-18 text-uppercase fw-bold mb-0">Custom Attributes Management</h4>
                </div>
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Attributes</li>
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
                                    <h6 class="mb-0">Total Attributes</h6>
                                    <h2 class="mb-0">{{ $statistics['total'] ?? 0 }}</h2>
                                </div>
                                <i class="ti ti-list" style="font-size: 40px; opacity: 0.5;"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">Active Attributes</h6>
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
                                    <h6 class="mb-0">Filterable</h6>
                                    <h2 class="mb-0">{{ $statistics['filterable'] ?? 0 }}</h2>
                                </div>
                                <i class="ti ti-filter" style="font-size: 40px; opacity: 0.5;"></i>
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

            {{-- Second Row - Additional Stats --}}
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card bg-danger text-white">
                        <div class="card-body">
                            <div class="text-center">
                                <h6 class="mb-0">Required Attributes</h6>
                                <h2 class="mb-0">{{ $statistics['required'] ?? 0 }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-secondary text-white">
                        <div class="card-body">
                            <div class="text-center">
                                <h6 class="mb-0">Rejected</h6>
                                <h2 class="mb-0">{{ $statistics['rejected'] ?? 0 }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-dark text-white">
                        <div class="card-body">
                            <div class="text-center">
                                <h6 class="mb-0">Attribute Groups</h6>
                                <h2 class="mb-0">{{ $groups->count() ?? 0 }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <h3 class="card-title mb-0">Attributes</h3>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.attributes.analytics') }}" class="btn btn-info">
                                    <i class="ti ti-chart-bar me-1"></i> Analytics
                                </a>
                                <a href="{{ route('admin.attributes.requests') }}" class="btn btn-success">
                                    <i class="ti ti-clipboard-list me-1"></i> Pending Requests
                                    @if(($statistics['pending'] ?? 0) > 0)
                                        <span class="badge bg-light text-dark ms-1">{{ $statistics['pending'] }}</span>
                                    @endif
                                </a>
                                <a href="{{ route('admin.attribute-groups.index') }}" class="btn btn-secondary">
                                    <i class="ti ti-layout-sidebar me-1"></i> Attribute Groups
                                </a>
                                @can('create_attributes')
                                    <a href="{{ route('admin.attributes.create') }}" class="btn btn-primary">
                                        <i class="ti ti-plus me-1"></i> Add New Attribute
                                    </a>
                                @endcan
                            </div>
                        </div>
                        <div class="card-body">

                            {{-- Search and Filter --}}
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="searchInput"
                                            placeholder="Search by name or slug..." value="{{ request('search') }}">
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
                                                <i class="ti ti-type"></i> Type
                                            </button>
                                            <ul class="dropdown-menu" id="typeFilter">
                                                <li><a class="dropdown-item" href="#" data-type="">All Types</a></li>
                                                <li><a class="dropdown-item" href="#" data-type="text">Text</a></li>
                                                <li><a class="dropdown-item" href="#" data-type="textarea">Textarea</a></li>
                                                <li><a class="dropdown-item" href="#" data-type="number">Number</a></li>
                                                <li><a class="dropdown-item" href="#" data-type="select">Select</a></li>
                                                <li><a class="dropdown-item" href="#" data-type="multiselect">Multi-Select</a></li>
                                                <li><a class="dropdown-item" href="#" data-type="checkbox">Checkbox</a></li>
                                                <li><a class="dropdown-item" href="#" data-type="radio">Radio</a></li>
                                                <li><a class="dropdown-item" href="#" data-type="date">Date</a></li>
                                                <li><a class="dropdown-item" href="#" data-type="color">Color</a></li>
                                            </ul>
                                        </div>

                                        <div class="btn-group">
                                            <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                                <i class="ti ti-filter me-1"></i> Filterable
                                            </button>
                                            <ul class="dropdown-menu" id="filterableFilter">
                                                <li><a class="dropdown-item" href="#" data-filterable="">All</a></li>
                                                <li><a class="dropdown-item" href="#" data-filterable="true">Filterable</a></li>
                                                <li><a class="dropdown-item" href="#" data-filterable="false">Not Filterable</a></li>
                                            </ul>
                                        </div>

                                        <div class="btn-group">
                                            <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                                <i class="ti ti-star me-1"></i> Required
                                            </button>
                                            <ul class="dropdown-menu" id="requiredFilter">
                                                <li><a class="dropdown-item" href="#" data-required="">All</a></li>
                                                <li><a class="dropdown-item" href="#" data-required="true">Required</a></li>
                                                <li><a class="dropdown-item" href="#" data-required="false">Optional</a></li>
                                            </ul>
                                        </div>

                                        <div class="btn-group">
                                            <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                                <i class="ti ti-folder me-1"></i> Group
                                            </button>
                                            <ul class="dropdown-menu" id="groupFilter">
                                                <li><a class="dropdown-item" href="#" data-group="">All Groups</a></li>
                                                @foreach($groups as $group)
                                                    <li><a class="dropdown-item" href="#" data-group="{{ $group->id }}">{{ $group->name }}</a></li>
                                                @endforeach
                                            </ul>
                                        </div>

                                        <div class="btn-group">
                                            <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                                <i class="ti ti-arrows-sort me-1"></i> Sort By
                                            </button>
                                            <ul class="dropdown-menu" id="sortFilter">
                                                <li><a class="dropdown-item" href="#" data-sort="order">Default Order</a></li>
                                                <li><a class="dropdown-item" href="#" data-sort="name">Name (A-Z)</a></li>
                                                <li><a class="dropdown-item" href="#" data-sort="type">Type</a></li>
                                                <li><a class="dropdown-item" href="#" data-sort="usage_count">Most Used</a></li>
                                                <li><a class="dropdown-item" href="#" data-sort="created_at">Newest First</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Bulk Actions --}}
                            @canany(['edit_attributes', 'delete_attributes'])
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <div class="btn-group flex-wrap gap-2">
                                            @can('edit_attributes')
                                                <button type="button" class="btn btn-outline-success btn-sm" onclick="bulkAction('activate')">
                                                    <i class="ti ti-check"></i> Activate Selected
                                                </button>
                                                <button type="button" class="btn btn-outline-warning btn-sm" onclick="bulkAction('deactivate')">
                                                    <i class="ti ti-x"></i> Deactivate Selected
                                                </button>
                                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="bulkAction('feature')">
                                                    <i class="ti ti-star"></i> Mark as Featured
                                                </button>
                                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="bulkAction('unfeature')">
                                                    <i class="ti ti-star-off"></i> Remove Featured
                                                </button>
                                                <button type="button" class="btn btn-outline-info btn-sm" onclick="bulkAction('filterable')">
                                                    <i class="ti ti-filter"></i> Make Filterable
                                                </button>
                                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="bulkAction('unfilterable')">
                                                    <i class="ti ti-filter-off"></i> Make Unfilterable
                                                </button>
                                                <button type="button" class="btn btn-outline-success btn-sm" onclick="bulkAction('approve')">
                                                    <i class="ti ti-check-circle"></i> Approve Selected
                                                </button>
                                                <button type="button" class="btn btn-outline-danger btn-sm" onclick="bulkAction('reject')">
                                                    <i class="ti ti-x-circle"></i> Reject Selected
                                                </button>
                                            @endcan
                                            @can('delete_attributes')
                                                <button type="button" class="btn btn-outline-danger btn-sm" onclick="bulkAction('delete')">
                                                    <i class="ti ti-trash"></i> Delete Selected
                                                </button>
                                            @endcan
                                        </div>
                                    </div>
                                </div>
                            @endcanany

                            {{-- Attributes Table Container --}}
                            <div id="attributesTableContainer">
                                @include('admin.pages.attributes.partials.attributes-table', compact('attributes'))
                            </div>

                            {{-- Pagination Container --}}
                            <div id="paginationContainer" class="mt-3">
                                {{ $attributes->appends(request()->query())->links('pagination::bootstrap-5') }}
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
    <form id="bulkActionForm" method="POST" action="{{ route('admin.attributes.bulk-action') }}" style="display: none;">
        @csrf
        <input type="hidden" name="action" id="bulkAction">
        <input type="hidden" name="attribute_ids" id="bulkAttributeIds">
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
                type: '{{ request('type') }}',
                group_id: '{{ request('group_id') }}',
                is_filterable: '{{ request('is_filterable') }}',
                is_required: '{{ request('is_required') }}',
                sort_by: '{{ request('sort_by', 'order') }}',
                page: 1
            };

            function loadAttributes() {
                $.ajax({
                    url: '{{ route("admin.attributes.index") }}',
                    type: 'GET',
                    data: currentFilters,
                    beforeSend: function() {
                        $('#attributesTableContainer').html('<div class="text-center py-5"><div class="spinner-border text-primary" role="status"></div></div>');
                        $('#paginationContainer').html('');
                    },
                    success: function(response) {
                        $('#attributesTableContainer').html(response.table);
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
                            $('.attribute-checkbox').prop('checked', $(this).prop('checked'));
                        });
                        $('.attribute-checkbox').off('change').on('change', function() {
                            let allChecked = $('.attribute-checkbox:checked').length === $('.attribute-checkbox').length;
                            $('#selectAll').prop('checked', allChecked);
                        });
                        $('.toggle-status').off('change').on('change', function() {
                            toggleStatus($(this).data('id'), this);
                        });
                    },
                    error: function() {
                        $('#attributesTableContainer').html('<div class="alert alert-danger">Error loading attributes</div>');
                    }
                });
            }

            function updateStatistics(statistics) {
                $('.bg-primary .h2').text(statistics.total || 0);
                $('.bg-success .h2').text(statistics.active || 0);
                $('.bg-info .h2').text(statistics.filterable || 0);
                $('.bg-warning .h2').text(statistics.pending || 0);
                $('.bg-danger .h2').text(statistics.required || 0);
                $('.bg-secondary .h2').text(statistics.rejected || 0);
            }

            $('#searchBtn').on('click', function() {
                currentFilters.search = $('#searchInput').val();
                currentFilters.page = 1;
                loadAttributes();
                $('#clearSearch').toggle(currentFilters.search !== '');
            });

            $('#searchInput').on('keypress', function(e) {
                if (e.which === 13) $('#searchBtn').click();
            });

            $('#clearSearch').on('click', function() {
                $('#searchInput').val('');
                currentFilters.search = '';
                currentFilters.page = 1;
                loadAttributes();
                $(this).hide();
            });

            $('#statusFilter .dropdown-item').on('click', function(e) {
                e.preventDefault();
                currentFilters.status = $(this).data('status');
                currentFilters.page = 1;
                loadAttributes();
            });

            $('#approvalFilter .dropdown-item').on('click', function(e) {
                e.preventDefault();
                currentFilters.approval_status = $(this).data('approval');
                currentFilters.page = 1;
                loadAttributes();
            });

            $('#typeFilter .dropdown-item').on('click', function(e) {
                e.preventDefault();
                currentFilters.type = $(this).data('type');
                currentFilters.page = 1;
                loadAttributes();
            });

            $('#groupFilter .dropdown-item').on('click', function(e) {
                e.preventDefault();
                currentFilters.group_id = $(this).data('group');
                currentFilters.page = 1;
                loadAttributes();
            });

            $('#filterableFilter .dropdown-item').on('click', function(e) {
                e.preventDefault();
                currentFilters.is_filterable = $(this).data('filterable');
                currentFilters.page = 1;
                loadAttributes();
            });

            $('#requiredFilter .dropdown-item').on('click', function(e) {
                e.preventDefault();
                currentFilters.is_required = $(this).data('required');
                currentFilters.page = 1;
                loadAttributes();
            });

            $('#sortFilter .dropdown-item').on('click', function(e) {
                e.preventDefault();
                currentFilters.sort_by = $(this).data('sort');
                currentFilters.page = 1;
                loadAttributes();
            });

            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault();
                let page = $(this).attr('href').split('page=')[1];
                if (page) {
                    currentFilters.page = page;
                    loadAttributes();
                }
            });

            if ($('#searchInput').val()) $('#clearSearch').show();
        });

        function toggleStatus(attributeId, element) {
            let isChecked = $(element).prop('checked');
            $.ajax({
                url: '{{ url("admin/attributes") }}/' + attributeId + '/toggle-status',
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

        function confirmDelete(attributeId, attributeName) {
            Swal.fire({
                title: 'Delete Attribute?',
                text: `Are you sure you want to delete "${attributeName}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    let form = $('#deleteForm');
                    form.attr('action', '{{ url("admin/attributes") }}/' + attributeId);
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
            let selectedAttributes = [];
            $('.attribute-checkbox:checked').each(function() { selectedAttributes.push($(this).val()); });
            if (selectedAttributes.length === 0) {
                Swal.fire({ icon: 'warning', title: 'No Selection', text: 'Please select at least one attribute.', confirmButtonColor: '#6c757d' });
                return;
            }
            let actionText = '', confirmColor = '#28a745';
            switch (action) {
                case 'activate': actionText = 'activate'; break;
                case 'deactivate': actionText = 'deactivate'; break;
                case 'feature': actionText = 'mark as featured'; break;
                case 'unfeature': actionText = 'remove featured'; break;
                case 'filterable': actionText = 'make filterable'; break;
                case 'unfilterable': actionText = 'make unfilterable'; break;
                case 'approve': actionText = 'approve'; break;
                case 'reject': actionText = 'reject'; confirmColor = '#dc3545'; break;
                case 'delete': actionText = 'delete'; confirmColor = '#d33'; break;
            }
            Swal.fire({
                title: `${actionText.toUpperCase()} Attributes?`,
                text: `Are you sure you want to ${actionText} ${selectedAttributes.length} selected attribute(s)?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: confirmColor,
                cancelButtonColor: '#6c757d',
                confirmButtonText: `Yes, ${actionText} them!`
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#bulkAction').val(action);
                    $('#bulkAttributeIds').val(JSON.stringify(selectedAttributes));
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
        .type-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 500;
        }
        .type-badge.text { background-color: #e3f2fd; color: #1976d2; }
        .type-badge.number { background-color: #e8f5e9; color: #388e3c; }
        .type-badge.select { background-color: #fff3e0; color: #f57c00; }
        .type-badge.checkbox { background-color: #fce4ec; color: #c2185b; }
        .type-badge.date { background-color: #e0f7fa; color: #0097a7; }
        .type-badge.color { background-color: #f3e5f5; color: #7b1fa2; }
    </style>
@endpush