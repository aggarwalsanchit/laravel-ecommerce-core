{{-- resources/views/admin/seasons/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Seasons')

@section('content')
    <div class="page-content">
        <div class="page-container">
            <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                <div class="flex-grow-1">
                    <h4 class="fs-18 text-uppercase fw-bold mb-0">Season Management</h4>
                </div>
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Seasons</li>
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
                                    <h6 class="mb-0">Total Seasons</h6>
                                    <h2 class="mb-0">{{ $statistics['total'] ?? 0 }}</h2>
                                </div>
                                <i class="ti ti-calendar" style="font-size: 40px; opacity: 0.5;"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">Active Seasons</h6>
                                    <h2 class="mb-0">{{ $statistics['active'] ?? 0 }}</h2>
                                </div>
                                <i class="ti ti-circle-check" style="font-size: 40px; opacity: 0.5;"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-dark">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">Current Season</h6>
                                    <h2 class="mb-0">{{ $statistics['current'] ?? 0 }}</h2>
                                </div>
                                <i class="ti ti-star" style="font-size: 40px; opacity: 0.5;"></i>
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
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <h3 class="card-title mb-0">Season Management</h3>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.seasons.analytics') }}" class="btn btn-info">
                                    <i class="ti ti-chart-bar me-1"></i> Analytics
                                </a>
                                @can('create seasons')
                                    <a href="{{ route('admin.seasons.create') }}" class="btn btn-primary">
                                        <i class="ti ti-plus me-1"></i> Add New Season
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
                                                <i class="ti ti-star me-1"></i> Filter by Current
                                            </button>
                                            <ul class="dropdown-menu" id="currentFilter">
                                                <li><a class="dropdown-item" href="#" data-current="">All</a></li>
                                                <li><a class="dropdown-item" href="#" data-current="yes">Current
                                                        Season</a></li>
                                                <li><a class="dropdown-item" href="#" data-current="no">Not
                                                        Current</a></li>
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
                                                <li><a class="dropdown-item" href="#"
                                                        data-sort="avg_rating">Highest Rated</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Bulk Actions --}}
                            @canany(['edit seasons', 'delete seasons'])
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <div class="btn-group flex-wrap gap-2">
                                            @can('edit seasons')
                                                <button type="button" class="btn btn-outline-success btn-sm"
                                                    onclick="bulkAction('activate')">
                                                    <i class="ti ti-check"></i> Activate Selected
                                                </button>
                                                <button type="button" class="btn btn-outline-warning btn-sm"
                                                    onclick="bulkAction('deactivate')">
                                                    <i class="ti ti-x"></i> Deactivate Selected
                                                </button>
                                                <button type="button" class="btn btn-outline-primary btn-sm"
                                                    onclick="bulkAction('set_current')">
                                                    <i class="ti ti-star"></i> Set as Current Season
                                                </button>
                                            @endcan
                                            @can('delete seasons')
                                                <button type="button" class="btn btn-outline-danger btn-sm"
                                                    onclick="bulkAction('delete')">
                                                    <i class="ti ti-trash"></i> Delete Selected
                                                </button>
                                            @endcan
                                        </div>
                                    </div>
                                </div>
                            @endcanany

                            {{-- Seasons Table Container --}}
                            <div id="seasonsTableContainer">
                                @include('admin.pages.seasons.partials.seasons-table', [
                                    'seasons' => $seasons,
                                ])
                            </div>

                            {{-- Pagination Container --}}
                            <div id="paginationContainer" class="mt-3">
                                {{ $seasons->appends(request()->query())->links('pagination::bootstrap-5') }}
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
    <form id="bulkActionForm" method="POST" action="{{ route('admin.seasons.bulk-action') }}" style="display: none;">
        @csrf
        <input type="hidden" name="action" id="bulkAction">
        <input type="hidden" name="season_ids" id="bulkSeasonIds">
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
                current: '{{ request('current') }}',
                sort_by: '{{ request('sort_by', 'order') }}',
                page: 1
            };

            // Update filter labels
            function updateFilterLabels() {
                if (currentFilters.status === 'active') {
                    $('#statusFilter').closest('.btn-group').find('.dropdown-toggle').html(
                        '<i class="ti ti-filter me-1"></i> Status: Active <i class="ti ti-chevron-down"></i>');
                } else if (currentFilters.status === 'inactive') {
                    $('#statusFilter').closest('.btn-group').find('.dropdown-toggle').html(
                        '<i class="ti ti-filter me-1"></i> Status: Inactive <i class="ti ti-chevron-down"></i>');
                } else {
                    $('#statusFilter').closest('.btn-group').find('.dropdown-toggle').html(
                        '<i class="ti ti-filter me-1"></i> Filter by Status <i class="ti ti-chevron-down"></i>');
                }

                if (currentFilters.current === 'yes') {
                    $('#currentFilter').closest('.btn-group').find('.dropdown-toggle').html(
                        '<i class="ti ti-star me-1"></i> Current: Yes <i class="ti ti-chevron-down"></i>');
                } else if (currentFilters.current === 'no') {
                    $('#currentFilter').closest('.btn-group').find('.dropdown-toggle').html(
                        '<i class="ti ti-star me-1"></i> Current: No <i class="ti ti-chevron-down"></i>');
                } else {
                    $('#currentFilter').closest('.btn-group').find('.dropdown-toggle').html(
                        '<i class="ti ti-star me-1"></i> Filter by Current <i class="ti ti-chevron-down"></i>');
                }
            }

            updateFilterLabels();

            // Search button click
            $('#searchBtn').on('click', function() {
                currentFilters.search = $('#searchInput').val();
                currentFilters.page = 1;
                loadSeasons();
                $('#clearSearch').toggle(currentFilters.search !== '');
            });

            // Search on enter key
            $('#searchInput').on('keypress', function(e) {
                if (e.which === 13) {
                    currentFilters.search = $(this).val();
                    currentFilters.page = 1;
                    loadSeasons();
                    $('#clearSearch').toggle(currentFilters.search !== '');
                }
            });

            // Clear search
            $('#clearSearch').on('click', function() {
                $('#searchInput').val('');
                currentFilters.search = '';
                currentFilters.page = 1;
                loadSeasons();
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
                loadSeasons();
                updateFilterLabels();
            });

            // Current filter
            $('#currentFilter .dropdown-item').on('click', function(e) {
                e.preventDefault();
                let current = $(this).data('current');

                $('#currentFilter .dropdown-item').removeClass('active');
                $(this).addClass('active');

                currentFilters.current = current;
                currentFilters.page = 1;
                loadSeasons();
                updateFilterLabels();
            });

            // Sort filter
            $('#sortFilter .dropdown-item').on('click', function(e) {
                e.preventDefault();
                let sortBy = $(this).data('sort');

                $('#sortFilter .dropdown-item').removeClass('active');
                $(this).addClass('active');

                let buttonText = $(this).text();
                $('#sortFilter').closest('.btn-group').find('.dropdown-toggle').html(
                    '<i class="ti ti-arrows-sort me-1"></i> Sort: ' + buttonText +
                    ' <i class="ti ti-chevron-down"></i>');

                currentFilters.sort_by = sortBy;
                currentFilters.page = 1;
                loadSeasons();
            });

            // Pagination click handler
            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault();
                let page = $(this).attr('href').split('page=')[1];
                if (page) {
                    currentFilters.page = page;
                    loadSeasons();
                }
            });

            // Load seasons via AJAX
            function loadSeasons() {
                $.ajax({
                    url: '{{ route('admin.seasons.index') }}',
                    type: 'GET',
                    data: currentFilters,
                    beforeSend: function() {
                        $('#seasonsTableContainer').html(
                            '<div class="text-center py-5"><div class="spinner-border text-primary" role="status"></div></div>'
                        );
                        $('#paginationContainer').html('');
                    },
                    success: function(response) {
                        $('#seasonsTableContainer').html(response.table);
                        $('#paginationContainer').html(response.pagination);

                        if (response.statistics) {
                            updateStatistics(response.statistics);
                        }

                        // Update URL without reload
                        let url = new URL(window.location);
                        url.searchParams.set('search', currentFilters.search || '');
                        url.searchParams.set('status', currentFilters.status || '');
                        url.searchParams.set('current', currentFilters.current || '');
                        url.searchParams.set('sort_by', currentFilters.sort_by || 'order');
                        url.searchParams.set('page', currentFilters.page);
                        window.history.pushState({}, '', url);

                        // Reinitialize tooltips
                        $('[data-bs-toggle="tooltip"]').tooltip();

                        // Reinitialize select all
                        $('#selectAll').off('change').on('change', function() {
                            $('.season-checkbox').prop('checked', $(this).prop('checked'));
                        });

                        $('.season-checkbox').off('change').on('change', function() {
                            let allChecked = $('.season-checkbox:checked').length === $(
                                '.season-checkbox').length;
                            $('#selectAll').prop('checked', allChecked);
                        });

                        // Reinitialize status toggle switches
                        $('.toggle-status').off('change').on('change', function() {
                            let seasonId = $(this).data('id');
                            toggleStatus(seasonId, this);
                        });

                        // Reinitialize set current buttons
                        $('.set-current').off('click').on('click', function() {
                            let seasonId = $(this).data('id');
                            setCurrent(seasonId);
                        });
                    },
                    error: function() {
                        $('#seasonsTableContainer').html(
                            '<div class="alert alert-danger">Error loading seasons</div>');
                    }
                });
            }

            // Update statistics cards
            function updateStatistics(statistics) {
                $('.bg-primary .h2').text(statistics.total || 0);
                $('.bg-success .h2').text(statistics.active || 0);
                $('.bg-warning .h2').text(statistics.current || 0);
                $('.bg-info .h2').text(statistics.total_products || 0);
            }

            // Show clear button if search exists
            if ($('#searchInput').val()) {
                $('#clearSearch').show();
            }
        });

        // Toggle Status
        function toggleStatus(seasonId, element) {
            let isChecked = $(element).prop('checked');

            $.ajax({
                url: '{{ url('admin/seasons') }}/' + seasonId + '/toggle-status',
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

        // Set as Current Season
        function setCurrent(seasonId) {
            Swal.fire({
                title: 'Set as Current Season?',
                text: "This will set this season as the current active season.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, set as current!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ url('admin/seasons') }}/' + seasonId + '/set-current',
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
                                }).then(() => {
                                    location.reload();
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Failed to set current season.',
                                confirmButtonColor: '#d33'
                            });
                        }
                    });
                }
            });
        }

        // Confirm Delete
        function confirmDelete(seasonId) {
            Swal.fire({
                title: 'Delete Season?',
                text: "Are you sure you want to delete this season? This action cannot be undone.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    let form = $('#deleteForm');
                    form.attr('action', '{{ url('admin/seasons') }}/' + seasonId);

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
            let selectedSeasons = [];
            $('.season-checkbox:checked').each(function() {
                selectedSeasons.push($(this).val());
            });

            if (selectedSeasons.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'No Selection',
                    text: 'Please select at least one season.',
                    confirmButtonColor: '#6c757d'
                });
                return;
            }

            let actionText = '';
            let confirmColor = '#28a745';

            switch (action) {
                case 'activate':
                    actionText = 'activate';
                    break;
                case 'deactivate':
                    actionText = 'deactivate';
                    break;
                case 'set_current':
                    actionText = 'set as current season';
                    break;
                case 'delete':
                    actionText = 'delete';
                    confirmColor = '#d33';
                    break;
            }

            Swal.fire({
                title: `${actionText.toUpperCase()} Seasons?`,
                text: `Are you sure you want to ${actionText} ${selectedSeasons.length} selected season(s)?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: confirmColor,
                cancelButtonColor: '#6c757d',
                confirmButtonText: `Yes, ${actionText} them!`
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#bulkAction').val(action);
                    $('#bulkSeasonIds').val(JSON.stringify(selectedSeasons));

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

        .current-badge {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                opacity: 1;
            }

            50% {
                opacity: 0.7;
            }

            100% {
                opacity: 1;
            }
        }
    </style>
@endpush
