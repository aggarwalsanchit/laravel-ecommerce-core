{{-- resources/views/admin/attributes/groups/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Attribute Groups')

@section('content')
    <div class="page-content">
        <div class="page-container">
            <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                <div class="flex-grow-1">
                    <h4 class="fs-18 text-uppercase fw-bold mb-0">Attribute Groups</h4>
                </div>
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.attributes.index') }}">Attributes</a></li>
                        <li class="breadcrumb-item active">Groups</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <h3 class="card-title mb-0">Attribute Groups</h3>
                            <div class="d-flex gap-2">
                                @can('create attribute groups')
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#createGroupModal">
                                        <i class="ti ti-plus me-1"></i> Add New Group
                                    </button>
                                @endcan
                            </div>
                        </div>
                        <div class="card-body">

                            {{-- Search --}}
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="searchInput"
                                            placeholder="Search groups..." value="{{ request('search') }}">
                                        <button class="btn btn-primary" type="button" id="searchBtn">
                                            <i class="ti ti-search"></i>
                                        </button>
                                        <button class="btn btn-secondary" type="button" id="clearSearch"
                                            style="display: none;">
                                            <i class="ti ti-x"></i> Clear
                                        </button>
                                    </div>
                                </div>
                            </div>

                            {{-- Groups Table Container --}}
                            <div id="groupsTableContainer">
                                @include('admin.pages.attributes.groups.partials.table', ['groups' => $groups])
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

    {{-- Create Group Modal --}}
    <div class="modal fade" id="createGroupModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create Attribute Group</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="createGroupForm">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Group Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" required>
                            <div class="invalid-feedback" id="name-error"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Icon</label>
                            <input type="text" class="form-control" name="icon" placeholder="ti ti-category">
                            <small class="text-muted">Tabler icon name (e.g., ti ti-category)</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Color</label>
                            <input type="color" class="form-control" name="color" style="height: 50px;">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="2"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Display Order</label>
                            <input type="number" class="form-control" name="display_order" value="0">
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input type="checkbox" class="form-check-input" id="is_collapsible" name="is_collapsible"
                                value="1" checked>
                            <label class="form-check-label" for="is_collapsible">Collapsible</label>
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input type="checkbox" class="form-check-input" id="show_in_sidebar" name="show_in_sidebar"
                                value="1" checked>
                            <label class="form-check-label" for="show_in_sidebar">Show in Sidebar</label>
                        </div>
                        <div class="form-check form-switch">
                            <input type="checkbox" class="form-check-input" id="status" name="status"
                                value="1" checked>
                            <label class="form-check-label" for="status">Active</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create Group</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit Group Modal --}}
    <div class="modal fade" id="editGroupModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Attribute Group</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editGroupForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_group_id" name="group_id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Group Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                            <div class="invalid-feedback" id="edit_name-error"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Icon</label>
                            <input type="text" class="form-control" id="edit_icon" name="icon">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Color</label>
                            <input type="color" class="form-control" id="edit_color" name="color"
                                style="height: 50px;">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" id="edit_description" name="description" rows="2"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Display Order</label>
                            <input type="number" class="form-control" id="edit_display_order" name="display_order">
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input type="checkbox" class="form-check-input" id="edit_is_collapsible"
                                name="is_collapsible" value="1">
                            <label class="form-check-label" for="edit_is_collapsible">Collapsible</label>
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input type="checkbox" class="form-check-input" id="edit_show_in_sidebar"
                                name="show_in_sidebar" value="1">
                            <label class="form-check-label" for="edit_show_in_sidebar">Show in Sidebar</label>
                        </div>
                        <div class="form-check form-switch">
                            <input type="checkbox" class="form-check-input" id="edit_status" name="status"
                                value="1">
                            <label class="form-check-label" for="edit_status">Active</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Group</button>
                    </div>
                </form>
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
                page: 1
            };

            // Search
            $('#searchBtn').on('click', function() {
                currentFilters.search = $('#searchInput').val();
                currentFilters.page = 1;
                loadGroups();
                $('#clearSearch').toggle(currentFilters.search !== '');
            });

            $('#searchInput').on('keypress', function(e) {
                if (e.which === 13) {
                    currentFilters.search = $(this).val();
                    currentFilters.page = 1;
                    loadGroups();
                    $('#clearSearch').toggle(currentFilters.search !== '');
                }
            });

            $('#clearSearch').on('click', function() {
                $('#searchInput').val('');
                currentFilters.search = '';
                currentFilters.page = 1;
                loadGroups();
                $(this).hide();
            });

            // Pagination
            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault();
                let page = $(this).attr('href').split('page=')[1];
                if (page) {
                    currentFilters.page = page;
                    loadGroups();
                }
            });

            function loadGroups() {
                $.ajax({
                    url: '{{ route('admin.attribute-groups.index') }}',
                    type: 'GET',
                    data: currentFilters,
                    beforeSend: function() {
                        $('#groupsTableContainer').html(
                            '<div class="text-center py-5"><div class="spinner-border text-primary" role="status"></div></div>'
                            );
                        $('#paginationContainer').html('');
                    },
                    success: function(response) {
                        $('#groupsTableContainer').html(response.table);
                        $('#paginationContainer').html(response.pagination);

                        let url = new URL(window.location);
                        url.searchParams.set('search', currentFilters.search || '');
                        url.searchParams.set('page', currentFilters.page);
                        window.history.pushState({}, '', url);

                        $('[data-bs-toggle="tooltip"]').tooltip();
                    }
                });
            }

            if ($('#searchInput').val()) {
                $('#clearSearch').show();
            }

            // Create Group
            $('#createGroupForm').on('submit', function(e) {
                e.preventDefault();
                let btn = $(this).find('button[type="submit"]');
                let originalText = btn.html();
                btn.html('<span class="spinner-border spinner-border-sm me-1"></span> Creating...').prop(
                    'disabled', true);

                $.ajax({
                    url: '{{ route('admin.attribute-groups.store') }}',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            $('#createGroupModal').modal('hide');
                            Swal.fire({
                                    icon: 'success',
                                    title: 'Success!',
                                    text: response.message,
                                    timer: 1500,
                                    showConfirmButton: false
                                })
                                .then(() => location.reload());
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function(field, messages) {
                                $('#' + field + '-error').text(messages[0]);
                                $('[name="' + field + '"]').addClass('is-invalid');
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Something went wrong.'
                            });
                        }
                        btn.html(originalText).prop('disabled', false);
                    }
                });
            });

            // Edit Group
            window.editGroup = function(id, name, icon, color, description, display_order, is_collapsible,
                show_in_sidebar, status) {
                $('#edit_group_id').val(id);
                $('#edit_name').val(name);
                $('#edit_icon').val(icon);
                $('#edit_color').val(color);
                $('#edit_description').val(description);
                $('#edit_display_order').val(display_order);
                $('#edit_is_collapsible').prop('checked', is_collapsible == 1);
                $('#edit_show_in_sidebar').prop('checked', show_in_sidebar == 1);
                $('#edit_status').prop('checked', status == 1);
                $('#editGroupModal').modal('show');
            };

            $('#editGroupForm').on('submit', function(e) {
                e.preventDefault();
                let id = $('#edit_group_id').val();
                let btn = $(this).find('button[type="submit"]');
                let originalText = btn.html();
                btn.html('<span class="spinner-border spinner-border-sm me-1"></span> Updating...').prop(
                    'disabled', true);

                $.ajax({
                    url: '{{ url('admin/attribute-groups') }}/' + id,
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            $('#editGroupModal').modal('hide');
                            Swal.fire({
                                    icon: 'success',
                                    title: 'Updated!',
                                    text: response.message,
                                    timer: 1500,
                                    showConfirmButton: false
                                })
                                .then(() => location.reload());
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function(field, messages) {
                                $('#edit_' + field + '-error').text(messages[0]);
                                $('#edit_' + field).addClass('is-invalid');
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Something went wrong.'
                            });
                        }
                        btn.html(originalText).prop('disabled', false);
                    }
                });
            });
        });

        // Toggle Status
        function toggleStatus(id) {
            Swal.fire({
                title: 'Toggle Status?',
                text: "Are you sure you want to change the status?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, toggle it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ url('admin/attribute-groups') }}/' + id + '/toggle-status',
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
            });
        }

        // Delete Group
        function deleteGroup(id) {
            Swal.fire({
                title: 'Delete Group?',
                text: "Are you sure you want to delete this group? This action cannot be undone.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ url('admin/attribute-groups') }}/' + id,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
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
    </script>
@endpush
