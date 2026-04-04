{{-- resources/views/admin/attributes/groups/partials/table.blade.php --}}
<div class="table-responsive">
    <table class="table table-hover text-nowrap mb-0">
        <thead class="bg-dark-subtle">
            32
            <th>ID</th>
            <th>Group Name</th>
            <th>Icon</th>
            <th>Color</th>
            <th>Attributes</th>
            <th>Display Order</th>
            <th>Sidebar</th>
            <th>Status</th>
            <th class="text-center">Actions</th>
        </thead>
        <tbody>
            @forelse($groups as $group)
                <tr>
                    <td>#{{ $group->id }}</td>
                    <td>
                        <strong>{{ $group->name }}</strong>
                        <div class="small text-muted">{{ $group->description ?: 'No description' }}</div>
                    </td>
                    <td>
                        @if ($group->icon)
                            <i class="{{ $group->icon }} fs-5"></i>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>
                        @if ($group->color)
                            <div
                                style="width: 30px; height: 30px; background: {{ $group->color }}; border-radius: 6px; border: 1px solid #dee2e6;">
                            </div>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge bg-primary">{{ $group->attributes->count() }}</span>
                    </td>
                    <td>{{ $group->display_order }}</td>
                    <td>
                        @if ($group->show_in_sidebar)
                            <span class="badge bg-success">Visible</span>
                        @else
                            <span class="badge bg-secondary">Hidden</span>
                        @endif
                    </td>
                    <td>
                        <div class="form-check form-switch">
                            <input type="checkbox" class="form-check-input toggle-status" data-id="{{ $group->id }}"
                                {{ $group->status ? 'checked' : '' }} onclick="toggleStatus({{ $group->id }})">
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="btn-group">
                            <button type="button" class="btn btn-soft-primary btn-sm"
                                onclick="editGroup({{ $group->id }}, '{{ $group->name }}', '{{ $group->icon }}', '{{ $group->color }}', '{{ addslashes($group->description) }}', {{ $group->display_order }}, {{ $group->is_collapsible ? 1 : 0 }}, {{ $group->show_in_sidebar ? 1 : 0 }}, {{ $group->status ? 1 : 0 }})">
                                <i class="ti ti-edit"></i>
                            </button>
                            <button type="button" class="btn btn-soft-danger btn-sm"
                                onclick="deleteGroup({{ $group->id }})">
                                <i class="ti ti-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center py-5">
                        <i class="ti ti-category-off fs-1 text-muted"></i>
                        <h5 class="mt-3">No Attribute Groups Found</h5>
                        <button class="btn btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#createGroupModal">
                            <i class="ti ti-plus"></i> Create First Group
                        </button>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
