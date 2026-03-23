{{-- resources/views/admin/fabrics/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Fabrics')

@section('content')
    <div class="page-content">
        <div class="page-container">
            <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                <div class="flex-grow-1">
                    <h4 class="fs-18 text-uppercase fw-bold mb-0">Fabric Management</h4>
                </div>
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Fabrics</li>
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
                                    <h6 class="mb-0">Total Fabrics</h6>
                                    <h2 class="mb-0">{{ $statistics['total'] ?? 0 }}</h2>
                                </div>
                                <i class="ti ti-fabric" style="font-size: 40px; opacity: 0.5;"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">Active Fabrics</h6>
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
                                    <h6 class="mb-0">Total Products</h6>
                                    <h2 class="mb-0">{{ number_format($statistics['total_products'] ?? 0) }}</h2>
                                </div>
                                <i class="ti ti-package" style="font-size: 40px; opacity: 0.5;"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-dark">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">Total Revenue</h6>
                                    <h2 class="mb-0">${{ number_format($statistics['total_revenue'] ?? 0, 2) }}</h2>
                                </div>
                                <i class="ti ti-chart-line" style="font-size: 40px; opacity: 0.5;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <h3 class="card-title mb-0">Fabric Management</h3>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.fabrics.analytics') }}" class="btn btn-info">
                                    <i class="ti ti-chart-bar me-1"></i> Analytics
                                </a>
                                @can('create fabrics')
                                    <a href="{{ route('admin.fabrics.create') }}" class="btn btn-primary">
                                        <i class="ti ti-plus me-1"></i> Add New Fabric
                                    </a>
                                @endcan
                            </div>
                        </div>
                        <div class="card-body">

                            {{-- Search and Filter --}}
                            <div class="row mb-3">
                                <div class="col-md-5">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="ti ti-search"></i></span>
                                        <input type="text" class="form-control" id="searchInput"
                                            placeholder="Search by name or code..." value="{{ request('search') }}">
                                        <button class="btn btn-primary" type="button" id="searchBtn">
                                            Search
                                        </button>
                                        <button class="btn btn-secondary" type="button" id="clearSearch"
                                            style="display: none;">
                                            <i class="ti ti-x"></i> Clear
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <div class="d-flex gap-2 justify-content-end flex-wrap">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-outline-secondary dropdown-toggle"
                                                data-bs-toggle="dropdown">
                                                <i class="ti ti-filter me-1"></i> Filter by Status
                                            </button>
                                            <ul class="dropdown-menu" id="statusFilter">
                                                <li><a class="dropdown-item" href="#" data-status="">All</a></li>
                                                <li><a class="dropdown-item" href="#" data-status="active">Active</a>
                                                </li>
                                                <li><a class="dropdown-item" href="#"
                                                        data-status="inactive">Inactive</a></li>
                                            </ul>
                                        </div>

                                        <div class="btn-group">
                                            <button type="button" class="btn btn-outline-secondary dropdown-toggle"
                                                data-bs-toggle="dropdown">
                                                <i class="ti ti-arrows-sort me-1"></i> Sort By
                                            </button>
                                            <ul class="dropdown-menu" id="sortFilter">
                                                <li><a class="dropdown-item" href="#" data-sort="order">Default
                                                        Order</a></li>
                                                <li><a class="dropdown-item" href="#" data-sort="name">Name
                                                        (A-Z)</a></li>
                                                <li><a class="dropdown-item" href="#" data-sort="code">Code
                                                        (A-Z)</a></li>
                                                <li><a class="dropdown-item" href="#" data-sort="view_count">Most
                                                        Viewed</a></li>
                                                <li><a class="dropdown-item" href="#"
                                                        data-sort="product_count">Most Products</a></li>
                                                <li><a class="dropdown-item" href="#"
                                                        data-sort="total_revenue">Highest Revenue</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Bulk Actions --}}
                            @canany(['edit fabrics', 'delete fabrics'])
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <div class="btn-group flex-wrap gap-2">
                                            @can('edit fabrics')
                                                <button type="button" class="btn btn-outline-success btn-sm"
                                                    onclick="bulkAction('activate')">
                                                    <i class="ti ti-check"></i> Activate Selected
                                                </button>
                                                <button type="button" class="btn btn-outline-warning btn-sm"
                                                    onclick="bulkAction('deactivate')">
                                                    <i class="ti ti-x"></i> Deactivate Selected
                                                </button>
                                            @endcan
                                            @can('delete fabrics')
                                                <button type="button" class="btn btn-outline-danger btn-sm"
                                                    onclick="bulkAction('delete')">
                                                    <i class="ti ti-trash"></i> Delete Selected
                                                </button>
                                            @endcan
                                        </div>
                                    </div>
                                </div>
                            @endcanany

                            {{-- Fabrics Table Container --}}
                            <div id="fabricsTableContainer">
                                @include('admin.pages.fabrics.partials.fabrics-table', [
                                    'fabrics' => $fabrics,
                                ])
                            </div>

                            {{-- Pagination Container --}}
                            <div id="paginationContainer" class="mt-3">
                                {{ $fabrics->appends(request()->query())->links('pagination::bootstrap-5') }}
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
    <form id="bulkActionForm" method="POST" action="{{ route('admin.fabrics.bulk-action') }}" style="display: none;">
        @csrf
        <input type="hidden" name="action" id="bulkAction">
        <input type="hidden" name="fabric_ids" id="bulkFabricIds">
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
                sort_by: '{{ request('sort_by', 'order') }}',
                page: 1
            };

            // Search button click
            $('#searchBtn').on('click', function() {
                currentFilters.search = $('#searchInput').val();
                currentFilters.page = 1;
                loadFabrics();
                $('#clearSearch').toggle(currentFilters.search !== '');
            });

            // Search on enter key
            $('#searchInput').on('keypress', function(e) {
                if (e.which === 13) {
                    currentFilters.search = $(this).val();
                    currentFilters.page = 1;
                    loadFabrics();
                    $('#clearSearch').toggle(currentFilters.search !== '');
                }
            });

            // Clear search
            $('#clearSearch').on('click', function() {
                $('#searchInput').val('');
                currentFilters.search = '';
                currentFilters.page = 1;
                loadFabrics();
                $(this).hide();
            });

            // Status filter
            $('#statusFilter .dropdown-item').on('click', function(e) {
                e.preventDefault();
                let status = $(this).data('status');

                $('#statusFilter .dropdown-item').removeClass('active');
                $(this).addClass('active');

                currentFilters.status = status;
                currentFilters.page = 1;
                loadFabrics();
            });

            // Sort filter
            $('#sortFilter .dropdown-item').on('click', function(e) {
                e.preventDefault();
                let sortBy = $(this).data('sort');

                $('#sortFilter .dropdown-item').removeClass('active');
                $(this).addClass('active');

                currentFilters.sort_by = sortBy;
                currentFilters.page = 1;
                loadFabrics();
            });

            // Pagination click handler
            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault();
                let page = $(this).attr('href').split('page=')[1];
                if (page) {
                    currentFilters.page = page;
                    loadFabrics();
                }
            });

            // Load fabrics via AJAX
            function loadFabrics() {
                $.ajax({
                    url: '{{ route('admin.fabrics.index') }}',
                    type: 'GET',
                    data: currentFilters,
                    beforeSend: function() {
                        $('#fabricsTableContainer').html(
                            '<div class="text-center py-5"><div class="spinner-border text-primary" role="status"></div></div>'
                        );
                        $('#paginationContainer').html('');
                    },
                    success: function(response) {
                        $('#fabricsTableContainer').html(response.table);
                        $('#paginationContainer').html(response.pagination);

                        if (response.statistics) {
                            updateStatistics(response.statistics);
                        }

                        // Update URL without reload
                        let url = new URL(window.location);
                        url.searchParams.set('search', currentFilters.search || '');
                        url.searchParams.set('status', currentFilters.status || '');
                        url.searchParams.set('sort_by', currentFilters.sort_by || 'order');
                        url.searchParams.set('page', currentFilters.page);
                        window.history.pushState({}, '', url);

                        // Reinitialize tooltips
                        $('[data-bs-toggle="tooltip"]').tooltip();

                        // Reinitialize select all
                        $('#selectAll').off('change').on('change', function() {
                            $('.fabric-checkbox').prop('checked', $(this).prop('checked'));
                        });

                        $('.fabric-checkbox').off('change').on('change', function() {
                            let allChecked = $('.fabric-checkbox:checked').length === $(
                                '.fabric-checkbox').length;
                            $('#selectAll').prop('checked', allChecked);
                        });

                        // Reinitialize status toggle switches
                        $('.toggle-status').off('change').on('change', function() {
                            let fabricId = $(this).data('id');
                            toggleStatus(fabricId, this);
                        });
                    },
                    error: function() {
                        $('#fabricsTableContainer').html(
                            '<div class="alert alert-danger">Error loading fabrics</div>');
                    }
                });
            }

            // Update statistics cards
            function updateStatistics(statistics) {
                $('.bg-primary .h2').text(statistics.total || 0);
                $('.bg-success .h2').text(statistics.active || 0);
                $('.bg-info .h2').text(statistics.total_products || 0);
                $('.bg-warning .h2').text('$' + (statistics.total_revenue || 0).toLocaleString());
            }

            // Show clear button if search exists
            if ($('#searchInput').val()) {
                $('#clearSearch').show();
            }
        });

        // Toggle Status
        function toggleStatus(fabricId, element) {
            let isChecked = $(element).prop('checked');

            $.ajax({
                url: '{{ url('admin/fabrics') }}/' + fabricId + '/toggle-status',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Updated!',
                            text: response.message,
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                },
                error: function() {
                    $(element).prop('checked', !isChecked);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Failed to update status.',
                        confirmButtonColor: '#d33'
                    });
                }
            });
        }

        // Confirm Delete
        function confirmDelete(fabricId) {
            Swal.fire({
                title: 'Delete Fabric?',
                text: "Are you sure you want to delete this fabric? This action cannot be undone.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    let form = $('#deleteForm');
                    form.attr('action', '{{ url('admin/fabrics') }}/' + fabricId);

                    $.ajax({
                        url: form.attr('action'),
                        type: 'POST',
                        data: form.serialize(),
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: response.message,
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Cannot Delete!',
                                    text: response.message,
                                    confirmButtonColor: '#d33'
                                });
                            }
                        }
                    });
                }
            });
        }

        // Bulk Action
        function bulkAction(action) {
            let selectedFabrics = [];
            $('.fabric-checkbox:checked').each(function() {
                selectedFabrics.push($(this).val());
            });

            if (selectedFabrics.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'No Selection',
                    text: 'Please select at least one fabric.',
                    confirmButtonColor: '#6c757d'
                });
                return;
            }

            let actionText = action === 'activate' ? 'activate' : (action === 'deactivate' ? 'deactivate' : 'delete');
            let confirmColor = action === 'delete' ? '#d33' : '#28a745';

            Swal.fire({
                title: `${actionText.toUpperCase()} Fabrics?`,
                text: `Are you sure you want to ${actionText} ${selectedFabrics.length} selected fabric(s)?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: confirmColor,
                cancelButtonColor: '#6c757d',
                confirmButtonText: `Yes, ${actionText} them!`
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#bulkAction').val(action);
                    $('#bulkFabricIds').val(JSON.stringify(selectedFabrics));

                    $.ajax({
                        url: $('#bulkActionForm').attr('action'),
                        type: 'POST',
                        data: $('#bulkActionForm').serialize(),
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success!',
                                    text: response.message,
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => {
                                    location.reload();
                                });
                            }
                        }
                    });
                }
            });
        }
    </script>
@endpush

@push('styles')
    <style>
        .empty-state {
            text-align: center;
            padding: 40px 20px;
        }

        .btn-icon {
            width: 32px;
            height: 32px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50% !important;
        }

        .btn-sm.rounded-circle {
            border-radius: 50% !important;
            width: 32px;
            height: 32px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .hstack {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .hstack .btn {
            margin: 0;
            flex-shrink: 0;
        }

        .badge {
            font-weight: 500;
        }

        .table> :not(caption)>*>* {
            vertical-align: middle;
            padding: 0.75rem;
        }

        .form-switch .form-check-input {
            width: 2.5em;
            height: 1.25em;
            cursor: pointer;
        }

        .card {
            transition: box-shadow 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
    </style>
@endpush
