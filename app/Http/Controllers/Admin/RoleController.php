<?php
// app/Http/Controllers/Admin/RoleController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\Traits\LogsAdminActivity;

class RoleController extends Controller implements HasMiddleware
{
    use LogsAdminActivity;

    public static function middleware(): array
    {
        return [
            'auth:admin',
            new Middleware('permission:view_roles', only: ['index', 'show']),
            new Middleware('permission:create_roles', only: ['create', 'store']),
            new Middleware('permission:edit_roles', only: ['edit', 'update']),
            new Middleware('permission:delete_roles', only: ['destroy']),
            new Middleware('permission:assign_permissions', only: ['assignPermissions', 'syncPermissions']),
        ];
    }

    /**
     * Display a listing of roles.
     */
    public function index(Request $request)
    {
        $query = Role::where('guard_name', 'admin')->with('permissions');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        if ($request->filled('guard') && $request->guard != '') {
            $query->where('guard_name', $request->guard);
        }

        $roles = $query->paginate(10);

        if ($request->ajax()) {
            $table = view('admin.pages.roles.partials.roles-table', compact('roles'))->render();
            $pagination = $roles->appends($request->query())->links('pagination::bootstrap-5')->render();

            return response()->json([
                'table' => $table,
                'pagination' => $pagination
            ]);
        }

        return view('admin.pages.roles.index', compact('roles'));
    }

    /**
     * Show form for creating new role.
     */
    public function create()
    {
        // Get permissions as a collection of models
        $permissions = Permission::where('guard_name', 'admin')->get();

        return view('admin.pages.roles.create', compact('permissions'));
    }

    /**
     * Store newly created role.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name|string|max:255',
            'permissions' => 'array',
            'guard_name' => 'nullable|string|in:web,admin'
        ]);

        $data = [
            'name' => $request->name,
            'guard_name' => $request->guard_name ?? 'admin'
        ];

        $role = Role::create($data);

        // Sync permissions - Convert IDs to names
        $permissionNames = [];
        if ($request->has('permissions')) {
            $permissionNames = Permission::whereIn('id', $request->permissions)
                ->pluck('name')
                ->toArray();

            $role->syncPermissions($permissionNames);

            // Log each permission assignment separately
            foreach ($permissionNames as $permissionName) {
                $this->logActivity(
                    'assign_permission',
                    'roles',
                    'role',
                    $role->id,
                    $role->name,
                    null,
                    ['permission' => $permissionName],
                    "Assigned permission '{$permissionName}' to role '{$role->name}'"
                );
            }
        }

        // Log main role creation
        $this->logActivity(
            'create',
            'roles',
            'admin',
            $role->id,
            $role->name,
            null,
            [
                'name' => $role->name,
                'guard_name' => $role->guard_name,
                'permissions_count' => count($permissionNames),
                'permissions' => $permissionNames
            ],
            "Created new role: {$role->name} with " . count($permissionNames) . " permissions"
        );

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Role created successfully with ' . count($permissionNames) . ' permissions.',
                'role' => $role
            ]);
        }

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role created successfully.');
    }

    /**
     * Display role details.
     */
    public function show(Role $role)
    {
        // Ensure role is for admin guard
        if ($role->guard_name !== 'admin') {
            abort(404, 'Role not found');
        }

        $role->load('permissions');
        $users = $role->users()->paginate(10);

        return view('admin.pages.roles.show', compact('role', 'users'));
    }

    /**
     * Show form for editing role.
     */
    public function edit(Role $role)
    {
        // Prevent editing Super Admin role
        if ($role->name === 'Super Admin') {
            return redirect()->route('admin.roles.index')
                ->with('error', 'Super Admin role cannot be edited.');
        }

        // Get all permissions for admin guard
        $permissions = Permission::where('guard_name', 'admin')->get();

        // Get current role permissions as IDs
        $rolePermissions = $role->permissions->pluck('id')->toArray();

        return view('admin.pages.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * Update role.
     */
    public function update(Request $request, Role $role)
    {
        // Prevent updating Super Admin role
        if ($role->name === 'Super Admin') {
            // Log the blocked attempt
            $this->logActivity(
                'update_blocked',
                'roles',
                'admin',
                $role->id,
                $role->name,
                null,
                [
                    'role_id' => $role->id,
                    'role_name' => $role->name,
                    'attempted_data' => $request->only(['name', 'guard_name'])
                ],
                "Attempted to edit Super Admin role: {$role->name}"
            );

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Super Admin role cannot be edited.'
                ], 403);
            }
            return redirect()->route('admin.roles.index')
                ->with('error', 'Super Admin role cannot be edited.');
        }

        // Store old values for logging
        $oldName = $role->name;
        $oldGuardName = $role->guard_name;
        $oldPermissions = $role->permissions->pluck('id')->toArray();
        $oldPermissionNames = $role->permissions->pluck('name')->toArray();

        $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id . '|string|max:255',
            'permissions' => 'array',
            'guard_name' => 'nullable|string|in:web,admin'
        ]);

        // Update role
        $role->update([
            'name' => $request->name,
            'guard_name' => $request->guard_name ?? 'admin'
        ]);

        // Track permission changes
        $newPermissionIds = $request->permissions ?? [];
        $addedPermissions = [];
        $removedPermissions = [];
        $permissionNames = [];

        // Sync permissions - IMPORTANT: Convert IDs to names
        if ($request->has('permissions')) {
            // Get permission names from IDs
            $permissionNames = Permission::whereIn('id', $request->permissions)
                ->pluck('name')
                ->toArray();

            // Calculate added and removed permissions
            $addedPermissions = array_diff($request->permissions, $oldPermissions);
            $removedPermissions = array_diff($oldPermissions, $request->permissions);

            $role->syncPermissions($permissionNames);
        } else {
            $removedPermissions = $oldPermissions;
            $role->syncPermissions([]);
        }

        // Prepare old data for logging
        $oldData = [
            'name' => $oldName,
            'guard_name' => $oldGuardName,
            'permissions_count' => count($oldPermissions),
            'permissions' => $oldPermissionNames
        ];

        // Prepare new data for logging
        $newData = [
            'name' => $role->name,
            'guard_name' => $role->guard_name,
            'permissions_count' => count($newPermissionIds),
            'permissions' => $permissionNames,
            'added_permissions' => $addedPermissions,
            'removed_permissions' => $removedPermissions
        ];

        // Prepare changes description
        $changes = [];
        if ($oldName !== $role->name) {
            $changes[] = "name changed from '{$oldName}' to '{$role->name}'";
        }
        if ($oldGuardName !== $role->guard_name) {
            $changes[] = "guard changed from '{$oldGuardName}' to '{$role->guard_name}'";
        }
        if (!empty($addedPermissions)) {
            $addedNames = Permission::whereIn('id', $addedPermissions)->pluck('name')->toArray();
            $changes[] = "added " . count($addedPermissions) . " permissions: " . implode(', ', $addedNames);
        }
        if (!empty($removedPermissions)) {
            $removedNames = Permission::whereIn('id', $removedPermissions)->pluck('name')->toArray();
            $changes[] = "removed " . count($removedPermissions) . " permissions: " . implode(', ', $removedNames);
        }

        $changeText = empty($changes) ? "no changes made" : implode('; ', $changes);

        // Log main role update
        $this->logActivity(
            'update',
            'roles',
            'admin',
            $role->id,
            $role->name,
            $oldData,
            $newData,
            "Updated role: {$role->name} - {$changeText}"
        );

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Role updated successfully with ' . count($request->permissions ?? []) . ' permissions.',
                'changes' => $changes
            ]);
        }

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role updated successfully.');
    }

    /**
     * Delete role.
     */
    public function destroy(Role $role)
    {
        // Check if trying to delete Super Admin role
        if ($role->name === 'Super Admin') {
            // Log the blocked attempt
            $this->logActivity(
                'delete_blocked',            // action
                'role',                      // entity_type
                $role->id,                   // entity_id
                $role->name,                 // entity_name
                null,                        // old_values
                [
                    'role_id' => $role->id,
                    'role_name' => $role->name,
                    'reason' => 'Super Admin role cannot be deleted'
                ],                           // new_values
                "Attempted to delete Super Admin role: {$role->name}"
            );

            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Super Admin role cannot be deleted.'
                ], 403);
            }
            return back()->with('error', 'Super Admin role cannot be deleted.');
        }

        // Check if role has assigned users
        $usersCount = $role->users()->count();
        if ($usersCount > 0) {
            // Log the blocked attempt due to assigned users
            $this->logActivity(
                'delete_blocked',            // action
                'role',                      // entity_type
                $role->id,                   // entity_id
                $role->name,                 // entity_name
                null,                        // old_values
                [
                    'role_id' => $role->id,
                    'role_name' => $role->name,
                    'assigned_users_count' => $usersCount,
                    'reason' => 'Role has assigned users'
                ],                           // new_values
                "Attempted to delete role '{$role->name}' which has {$usersCount} assigned users"
            );

            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete role because it has ' . $usersCount . ' assigned users.'
                ], 422);
            }
            return back()->with('error', 'Cannot delete role because it has assigned users.');
        }

        // Store old values for logging before deletion
        $oldValues = [
            'name' => $role->name,
            'guard_name' => $role->guard_name,
            'permissions_count' => $role->permissions->count(),
            'permissions' => $role->permissions->pluck('name')->toArray(),
            'users_count' => $usersCount,
            'created_at' => $role->created_at,
            'updated_at' => $role->updated_at
        ];

        $roleId = $role->id;
        $roleName = $role->name;
        $roleGuard = $role->guard_name;
        $permissionsList = implode(', ', $oldValues['permissions']);

        // Delete the role
        $role->delete();

        // Log the successful deletion
        $this->logActivity(
            'delete',                        // action
            'role',
            'admin',                          // entity_type
            $roleId,                         // entity_id
            $roleName,                       // entity_name
            $oldValues,                      // old_values
            null,                            // new_values
            "Deleted role: {$roleName} (Guard: {$roleGuard}) with " . $oldValues['permissions_count'] . " permissions: [{$permissionsList}]"
        );

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Role deleted successfully.'
            ]);
        }

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role deleted successfully.');
    }

    /**
     * Show assign permissions form.
     */
    public function assignPermissions(Role $role)
    {
        $permissions = Permission::where('guard_name', 'admin')->get();

        $rolePermissions = $role->permissions->pluck('id')->toArray();

        return view('admin.pages.roles.assign-permissions', compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * Sync permissions for role.
     */
    public function syncPermissions(Request $request, Role $role)
    {
        $request->validate([
            'permissions' => 'array'
        ]);

        // Prevent syncing permissions for Super Admin role
        if ($role->name === 'Super Admin') {
            $this->logActivity(
                'sync_permissions_blocked',
                'role',
                'admin',
                $role->id,
                $role->name,
                null,
                [
                    'role_id' => $role->id,
                    'role_name' => $role->name,
                    'reason' => 'Super Admin role permissions cannot be modified'
                ],
                "Attempted to sync permissions for Super Admin role: {$role->name}"
            );

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Super Admin role permissions cannot be modified.'
                ], 403);
            }
            return redirect()->route('admin.roles.show', $role->id)
                ->with('error', 'Super Admin role permissions cannot be modified.');
        }

        // Get old permissions
        $oldPermissions = $role->permissions;
        $oldPermissionIds = $oldPermissions->pluck('id')->toArray();
        $oldPermissionNames = $oldPermissions->pluck('name')->toArray();

        // Get new permissions
        $newPermissionIds = $request->permissions ?? [];
        $newPermissionNames = [];

        if (!empty($newPermissionIds)) {
            $newPermissionNames = Permission::whereIn('id', $newPermissionIds)
                ->pluck('name')
                ->toArray();
        }

        // Calculate changes
        $addedIds = array_diff($newPermissionIds, $oldPermissionIds);
        $removedIds = array_diff($oldPermissionIds, $newPermissionIds);

        $addedNames = Permission::whereIn('id', $addedIds)->pluck('name')->toArray();
        $removedNames = Permission::whereIn('id', $removedIds)->pluck('name')->toArray();

        // Sync permissions
        if ($request->has('permissions')) {
            $role->syncPermissions($newPermissionNames);
        } else {
            $role->syncPermissions([]);
        }

        // Prepare change description
        $changeDetails = [];
        if (!empty($addedNames)) {
            $changeDetails[] = "Added " . count($addedNames) . " permissions: " . implode(', ', $addedNames);
        }
        if (!empty($removedNames)) {
            $changeDetails[] = "Removed " . count($removedNames) . " permissions: " . implode(', ', $removedNames);
        }

        $changeText = empty($changeDetails) ? "No changes made" : implode('; ', $changeDetails);

        // Log activity
        $this->logActivity(
            'sync_permissions',
            'role',
            'admin',
            $role->id,
            $role->name,
            [
                'permissions_count' => count($oldPermissionIds),
                'permissions' => $oldPermissionNames
            ],
            [
                'permissions_count' => count($newPermissionIds),
                'permissions' => $newPermissionNames,
                'added_permissions' => $addedNames,
                'removed_permissions' => $removedNames
            ],
            "Synced permissions for role: {$role->name} - {$changeText}"
        );

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Permissions synced successfully.',
                'changes' => [
                    'added' => $addedNames,
                    'removed' => $removedNames,
                    'added_count' => count($addedNames),
                    'removed_count' => count($removedNames)
                ]
            ]);
        }

        return redirect()->route('admin.roles.show', $role->id)
            ->with('success', 'Permissions updated successfully.');
    }

    /**
     * Bulk action on roles.
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete',
            'role_ids' => 'required|string',
        ]);

        $action = $request->action;
        $roleIds = json_decode($request->role_ids);

        if (!auth('admin')->user()->can('delete_roles')) {
            // Log permission denied
            $this->logActivity(
                'bulk_action_denied',           // action
                'role',                          // entity_type
                'admin',
                null,                            // entity_id
                'Bulk Action',                   // entity_name
                null,                            // old_values
                [
                    'action' => $action,
                    'role_ids' => $roleIds,
                    'reason' => 'Permission denied'
                ],                               // new_values
                "Attempted bulk {$action} on roles but permission denied"
            );

            return response()->json(['success' => false, 'message' => 'Permission denied.'], 403);
        }

        $roles = Role::whereIn('id', $roleIds)
            ->where('name', '!=', 'Store Owner')
            ->get();

        $deletedRoles = [];
        $blockedRoles = [];
        $skippedRoles = [];

        foreach ($roles as $role) {
            if ($role->users()->count() == 0) {
                // Store role data before deletion
                $roleData = [
                    'id' => $role->id,
                    'name' => $role->name,
                    'guard_name' => $role->guard_name,
                    'permissions_count' => $role->permissions->count(),
                    'permissions' => $role->permissions->pluck('name')->toArray()
                ];

                $deletedRoles[] = $roleData;
                $role->delete();
            } else {
                // Role has assigned users
                $skippedRoles[] = [
                    'id' => $role->id,
                    'name' => $role->name,
                    'users_count' => $role->users()->count()
                ];
            }
        }

        $count = count($deletedRoles);

        // Log bulk deletion if any roles were deleted
        if ($count > 0) {
            $deletedNames = implode(', ', array_column($deletedRoles, 'name'));

            $this->logActivity(
                'bulk_delete',                   // action
                'role',                          // entity_type
                'admin',
                null,                            // entity_id
                'Bulk Action',                   // entity_name
                null,                            // old_values
                [
                    'action' => $action,
                    'total_requested' => count($roleIds),
                    'deleted_count' => $count,
                    'deleted_roles' => $deletedRoles,
                    'skipped_roles' => $skippedRoles,
                    'skipped_count' => count($skippedRoles)
                ],                               // new_values
                "Bulk deleted {$count} roles: {$deletedNames}" . (count($skippedRoles) > 0 ? ". Skipped " . count($skippedRoles) . " roles because they have assigned users" : "")
            );
        }

        // Log if some roles were skipped
        if (count($skippedRoles) > 0) {
            $skippedNames = implode(', ', array_column($skippedRoles, 'name'));

            $this->logActivity(
                'bulk_delete_skipped',           // action
                'role',
                'admin',                         // entity_type
                null,                            // entity_id
                'Bulk Action',                   // entity_name
                null,                            // old_values
                [
                    'skipped_roles' => $skippedRoles,
                    'reason' => 'Roles have assigned users'
                ],                               // new_values
                "Skipped bulk deletion for " . count($skippedRoles) . " roles: {$skippedNames} - They have assigned users"
            );
        }

        return response()->json([
            'success' => true,
            'message' => "{$count} roles deleted successfully.",
            'skipped' => count($skippedRoles),
            'skipped_roles' => $skippedRoles
        ]);
    }
}
