{{-- resources/views/admin/attributes/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Attributes')

@section('content')
    <div class="page-content">
        <div class="page-container">
            <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                <div class="flex-grow-1">
                    <h4 class="fs-18 text-uppercase fw-bold mb-0">Custom Attributes</h4>
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
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6>Total Attributes</h6>
                                    <h2 class="mb-0">{{ $statistics['total'] ?? 0 }}</h2>
                                </div>
                                <i class="ti ti-list fs-1 opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6>Active Attributes</h6>
                                    <h2 class="mb-0">{{ $statistics['active'] ?? 0 }}</h2>
                                </div>
                                <i class="ti ti-circle-check fs-1 opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6>Total Values</h6>
                                    <h2 class="mb-0">{{ number_format($statistics['total_values'] ?? 0) }}</h2>
                                </div>
                                <i class="ti ti-list-check fs-1 opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-dark">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6>Total Products</h6>
                                    <h2 class="mb-0">{{ number_format($statistics['total_products'] ?? 0) }}</h2>
                                </div>
                                <i class="ti ti-package fs-1 opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <h3 class="card-title mb-0">Custom Attributes</h3>
                            <div class="d-flex gap-2">
                                <a href="" class="btn btn-info">
                                    <i class="ti ti-chart-bar me-1"></i> Analytics
                                </a>
                                <a href="{{ route('admin.attribute-groups.index') }}" class="btn btn-secondary">
                                    <i class="ti ti-category me-1"></i> Groups
                                </a>
                                @can('create attributes')
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
                                <div class="col-md-8">
                                    <div class="d-flex gap-2 justify-content-end flex-wrap">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-outline-secondary dropdown-toggle"
                                                data-bs-toggle="dropdown">
                                                <i class="ti ti-filter me-1"></i> Filter by Group
                                            </button>
                                            <ul class="dropdown-menu" id="groupFilter">
                                                <li><a class="dropdown-item" href="#" data-group="">All Groups</a>
                                                </li>
                                                @foreach ($groups as $group)
                                                    <li><a class="dropdown-item" href="#"
                                                            data-group="{{ $group->id }}">{{ $group->name }}</a></li>
                                                @endforeach
                                            </ul>
                                        </div>

                                        <div class="btn-group">
                                            <button type="button" class="btn btn-outline-secondary dropdown-toggle"
                                                data-bs-toggle="dropdown">
                                                <i class="ti ti-layers me-1"></i> Filter by Type
                                            </button>
                                            <ul class="dropdown-menu" id="typeFilter">
                                                <li><a class="dropdown-item" href="#" data-type="">All Types</a>
                                                </li>
                                                <li><a class="dropdown-item" href="#" data-type="text">Text</a></li>
                                                <li><a class="dropdown-item" href="#" data-type="select">Select</a>
                                                </li>
                                                <li><a class="dropdown-item" href="#" data-type="color">Color</a>
                                                </li>
                                                <li><a class="dropdown-item" href="#" data-type="size">Size</a>
                                                </li>
                                                <li><a class="dropdown-item" href="#" data-type="number">Number</a>
                                                </li>
                                            </ul>
                                        </div>

                                        <div class="btn-group">
                                            <button type="button" class="btn btn-outline-secondary dropdown-toggle"
                                                data-bs-toggle="dropdown">
                                                <i class="ti ti-filter me-1"></i> Filter by Status
                                            </button>
                                            <ul class="dropdown-menu" id="statusFilter">
                                                <li><a class="dropdown-item" href="#" data-status="">All</a></li>
                                                <li><a class="dropdown-item" href="#"
                                                        data-status="active">Active</a></li>
                                                <li><a class="dropdown-item" href="#"
                                                        data-status="inactive">Inactive</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Bulk Actions --}}
                            @canany(['edit attributes', 'delete attributes'])
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <div class="btn-group gap-2">
                                            @can('edit attributes')
                                                <button type="button" class="btn btn-outline-success btn-sm"
                                                    onclick="bulkAction('activate')">
                                                    <i class="ti ti-check"></i> Activate Selected
                                                </button>
                                                <button type="button" class="btn btn-outline-warning btn-sm"
                                                    onclick="bulkAction('deactivate')">
                                                    <i class="ti ti-x"></i> Deactivate Selected
                                                </button>
                                            @endcan
                                            @can('delete attributes')
                                                <button type="button" class="btn btn-outline-danger btn-sm"
                                                    onclick="bulkAction('delete')">
                                                    <i class="ti ti-trash"></i> Delete Selected
                                                </button>
                                            @endcan
                                        </div>
                                    </div>
                                </div>
                            @endcanany

                            {{-- Attributes Table Container --}}
                            <div id="attributesTableContainer">
                                @include('admin.pages.attributes.partials.table', ['attributes' => $attributes])
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
    <form id="bulkActionForm" method="POST" action=""
        style="display: none;">
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
                group_id: '{{ request('group_id') }}',
                type: '{{ request('type') }}',
                status: '{{ request('status') }}',
                page: 1
            };

            // Search
            $('#searchBtn').on('click', function() {
                currentFilters.search = $('#searchInput').val();
                currentFilters.page = 1;
                loadAttributes();
                $('#clearSearch').toggle(currentFilters.search !== '');
            });

            $('#searchInput').on('keypress', function(e) {
                if (e.which === 13) {
                    currentFilters.search = $(this).val();
                    currentFilters.page = 1;
                    loadAttributes();
                    $('#clearSearch').toggle(currentFilters.search !== '');
                }
            });

            $('#clearSearch').on('click', function() {
                $('#searchInput').val('');
                currentFilters.search = '';
                currentFilters.page = 1;
                loadAttributes();
                $(this).hide();
            });

            // Filters
            $('#groupFilter .dropdown-item').on('click', function(e) {
                e.preventDefault();
                let groupId = $(this).data('group');
                currentFilters.group_id = groupId;
                currentFilters.page = 1;
                loadAttributes();
            });

            $('#typeFilter .dropdown-item').on('click', function(e) {
                e.preventDefault();
                let type = $(this).data('type');
                currentFilters.type = type;
                currentFilters.page = 1;
                loadAttributes();
            });

            $('#statusFilter .dropdown-item').on('click', function(e) {
                e.preventDefault();
                let status = $(this).data('status');
                currentFilters.status = status;
                currentFilters.page = 1;
                loadAttributes();
            });

            // Pagination
            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault();
                let page = $(this).attr('href').split('page=')[1];
                if (page) {
                    currentFilters.page = page;
                    loadAttributes();
                }
            });

            function loadAttributes() {
                $.ajax({
                    url: '{{ route('admin.attributes.index') }}',
                    type: 'GET',
                    data: currentFilters,
                    beforeSend: function() {
                        $('#attributesTableContainer').html(
                            '<div class="text-center py-5"><div class="spinner-border text-primary" role="status"></div></div>'
                            );
                        $('#paginationContainer').html('');
                    },
                    success: function(response) {
                        $('#attributesTableContainer').html(response.table);
                        $('#paginationContainer').html(response.pagination);

                        let url = new URL(window.location);
                        url.searchParams.set('search', currentFilters.search || '');
                        url.searchParams.set('group_id', currentFilters.group_id || '');
                        url.searchParams.set('type', currentFilters.type || '');
                        url.searchParams.set('status', currentFilters.status || '');
                        url.searchParams.set('page', currentFilters.page);
                        window.history.pushState({}, '', url);

                        $('[data-bs-toggle="tooltip"]').tooltip();

                        $('#selectAll').off('change').on('change', function() {
                            $('.attribute-checkbox').prop('checked', $(this).prop('checked'));
                        });

                        $('.attribute-checkbox').off('change').on('change', function() {
                            let allChecked = $('.attribute-checkbox:checked').length === $(
                                '.attribute-checkbox').length;
                            $('#selectAll').prop('checked', allChecked);
                        });
                    }
                });
            }

            if ($('#searchInput').val()) {
                $('#clearSearch').show();
            }
        });

        function toggleStatus(id) {
            $.ajax({
                url: '{{ url('admin/attributes') }}/' + id + '/toggle-status',
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
                            })
                            .then(() => location.reload());
                    }
                }
            });
        }

        function confirmDelete(id) {
            Swal.fire({
                title: 'Delete Attribute?',
                text: "Are you sure? This will also delete all values!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    let form = $('#deleteForm');
                    form.attr('action', '{{ url('admin/attributes') }}/' + id);
                    form.submit();
                }
            });
        }

        function bulkAction(action) {
            let selected = [];
            $('.attribute-checkbox:checked').each(function() {
                selected.push($(this).val());
            });

            if (selected.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'No Selection',
                    text: 'Please select at least one attribute.'
                });
                return;
            }

            Swal.fire({
                title: `${action.toUpperCase()} Attributes?`,
                text: `Are you sure you want to ${action} ${selected.length} selected attribute(s)?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: action === 'delete' ? '#d33' : '#28a745',
                confirmButtonText: `Yes, ${action} them!`
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#bulkAction').val(action);
                    $('#bulkAttributeIds').val(JSON.stringify(selected));
                    $('#bulkActionForm').submit();
                }
            });
        }
    </script>
@endpush
