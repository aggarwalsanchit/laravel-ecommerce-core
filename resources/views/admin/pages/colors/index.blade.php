{{-- resources/views/admin/colors/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Colors')

@section('content')
    <div class="page-content">
        <div class="page-container">
            <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                <div class="flex-grow-1">
                    <h4 class="fs-18 text-uppercase fw-bold mb-0">Color Management</h4>
                </div>
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Colors</li>
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
                                    <h6 class="mb-0">Total Colors</h6>
                                    <h2 class="mb-0">{{ $statistics['total'] ?? 0 }}</h2>
                                </div>
                                <i class="ti ti-palette" style="font-size: 40px; opacity: 0.5;"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">Active Colors</h6>
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
                            <h3 class="card-title mb-0">Color Management</h3>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.colors.analytics') }}" class="btn btn-info">
                                    <i class="ti ti-chart-bar me-1"></i> Analytics
                                </a>
                                @can('create colors')
                                    <a href="{{ route('admin.colors.create') }}" class="btn btn-primary">
                                        <i class="ti ti-plus me-1"></i> Add New Color
                                    </a>
                                @endcan
                            </div>
                        </div>
                        <div class="card-body">

                            {{-- Search and Filter --}}
                            <div class="row mb-3">
                                <div class="col-md-5">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="searchInput"
                                            placeholder="Search by name or code..." value="{{ request('search') }}">
                                        <button class="btn btn-primary" type="button" id="searchBtn">
                                            <i class="ti ti-search"></i>
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
                                                <li><a class="dropdown-item" href="#" data-sort="hex_code">Hex
                                                        Code</a></li>
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
                            @canany(['edit colors', 'delete colors'])
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <div class="btn-group flex-wrap gap-2">
                                            @can('edit colors')
                                                <button type="button" class="btn btn-outline-success btn-sm"
                                                    onclick="bulkAction('activate')">
                                                    <i class="ti ti-check"></i> Activate Selected
                                                </button>
                                                <button type="button" class="btn btn-outline-warning btn-sm"
                                                    onclick="bulkAction('deactivate')">
                                                    <i class="ti ti-x"></i> Deactivate Selected
                                                </button>
                                            @endcan
                                            @can('delete colors')
                                                <button type="button" class="btn btn-outline-danger btn-sm"
                                                    onclick="bulkAction('delete')">
                                                    <i class="ti ti-trash"></i> Delete Selected
                                                </button>
                                            @endcan
                                        </div>
                                    </div>
                                </div>
                            @endcanany

                            {{-- Colors Table Container --}}
                            <div id="colorsTableContainer">
                                @include('admin.pages.colors.partials.colors-table', ['colors' => $colors])
                            </div>

                            {{-- Pagination Container --}}
                            <div id="paginationContainer" class="mt-3">
                                {{ $colors->appends(request()->query())->links('pagination::bootstrap-5') }}
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
    <form id="bulkActionForm" method="POST" action="{{ route('admin.colors.bulk-action') }}" style="display: none;">
        @csrf
        <input type="hidden" name="action" id="bulkAction">
        <input type="hidden" name="color_ids" id="bulkColorIds">
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
                loadColors();
                $('#clearSearch').toggle(currentFilters.search !== '');
            });

            $('#searchInput').on('keypress', function(e) {
                if (e.which === 13) {
                    currentFilters.search = $(this).val();
                    currentFilters.page = 1;
                    loadColors();
                    $('#clearSearch').toggle(currentFilters.search !== '');
                }
            });

            $('#clearSearch').on('click', function() {
                $('#searchInput').val('');
                currentFilters.search = '';
                currentFilters.page = 1;
                loadColors();
                $(this).hide();
            });

            $('#statusFilter .dropdown-item').on('click', function(e) {
                e.preventDefault();
                let status = $(this).data('status');

                $('#statusFilter .dropdown-item').removeClass('active');
                $(this).addClass('active');

                currentFilters.status = status;
                currentFilters.page = 1;
                loadColors();
            });

            $('#sortFilter .dropdown-item').on('click', function(e) {
                e.preventDefault();
                let sortBy = $(this).data('sort');

                $('#sortFilter .dropdown-item').removeClass('active');
                $(this).addClass('active');

                currentFilters.sort_by = sortBy;
                currentFilters.page = 1;
                loadColors();
            });

            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault();
                let page = $(this).attr('href').split('page=')[1];
                if (page) {
                    currentFilters.page = page;
                    loadColors();
                }
            });

            function loadColors() {
                $.ajax({
                    url: '{{ route('admin.colors.index') }}',
                    type: 'GET',
                    data: currentFilters,
                    beforeSend: function() {
                        $('#colorsTableContainer').html(
                            '<div class="text-center py-5"><div class="spinner-border text-primary" role="status"></div></div>'
                        );
                        $('#paginationContainer').html('');
                    },
                    success: function(response) {
                        $('#colorsTableContainer').html(response.table);
                        $('#paginationContainer').html(response.pagination);

                        if (response.statistics) {
                            updateStatistics(response.statistics);
                        }

                        let url = new URL(window.location);
                        url.searchParams.set('search', currentFilters.search || '');
                        url.searchParams.set('status', currentFilters.status || '');
                        url.searchParams.set('sort_by', currentFilters.sort_by || 'order');
                        url.searchParams.set('page', currentFilters.page);
                        window.history.pushState({}, '', url);

                        $('[data-bs-toggle="tooltip"]').tooltip();

                        $('#selectAll').off('change').on('change', function() {
                            $('.color-checkbox').prop('checked', $(this).prop('checked'));
                        });

                        $('.color-checkbox').off('change').on('change', function() {
                            let allChecked = $('.color-checkbox:checked').length === $(
                                '.color-checkbox').length;
                            $('#selectAll').prop('checked', allChecked);
                        });

                        $('.toggle-status').off('change').on('change', function() {
                            let colorId = $(this).data('id');
                            toggleStatus(colorId, this);
                        });
                    },
                    error: function() {
                        $('#colorsTableContainer').html(
                            '<div class="alert alert-danger">Error loading colors</div>');
                    }
                });
            }

            function updateStatistics(statistics) {
                $('.bg-primary .h2').text(statistics.total || 0);
                $('.bg-success .h2').text(statistics.active || 0);
                $('.bg-info .h2').text(statistics.total_products || 0);
                $('.bg-warning .h2').text('$' + (statistics.total_revenue || 0).toLocaleString());
            }

            if ($('#searchInput').val()) {
                $('#clearSearch').show();
            }
        });

        function toggleStatus(colorId, element) {
            let isChecked = $(element).prop('checked');

            $.ajax({
                url: '{{ url('admin/colors') }}/' + colorId + '/toggle-status',
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
                        text: 'Failed to update status.'
                    });
                }
            });
        }

        function confirmDelete(colorId) {
            Swal.fire({
                title: 'Delete Color?',
                text: "Are you sure you want to delete this color?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    let form = $('#deleteForm');
                    form.attr('action', '{{ url('admin/colors') }}/' + colorId);

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
                                    })
                                    .then(() => location.reload());
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Cannot Delete!',
                                    text: response.message
                                });
                            }
                        }
                    });
                }
            });
        }

        function bulkAction(action) {
            let selectedColors = [];
            $('.color-checkbox:checked').each(function() {
                selectedColors.push($(this).val());
            });

            if (selectedColors.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'No Selection',
                    text: 'Please select at least one color.'
                });
                return;
            }

            let actionText = action === 'activate' ? 'activate' : (action === 'deactivate' ? 'deactivate' : 'delete');
            let confirmColor = action === 'delete' ? '#d33' : '#28a745';

            Swal.fire({
                title: `${actionText.toUpperCase()} Colors?`,
                text: `Are you sure you want to ${actionText} ${selectedColors.length} selected color(s)?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: confirmColor,
                cancelButtonColor: '#6c757d',
                confirmButtonText: `Yes, ${actionText} them!`
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#bulkAction').val(action);
                    $('#bulkColorIds').val(JSON.stringify(selectedColors));

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
                                    })
                                    .then(() => location.reload());
                            }
                        }
                    });
                }
            });
        }
    </script>
@endpush
