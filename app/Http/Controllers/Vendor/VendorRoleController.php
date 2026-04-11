<?php
// app/Http/Controllers/Vendor/VendorRoleController.php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\Traits\LogsVendorActivity;

class VendorRoleController extends Controller implements HasMiddleware
{
    use LogsVendorActivity;

    public static function middleware(): array
    {
        return [
            'auth:vendor',
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
        $query = Role::where('guard_name', 'vendor')->with('permissions');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        if ($request->filled('guard') && $request->guard != '') {
            $query->where('guard_name', $request->guard);
        }

        $roles = $query->paginate(10);

        if ($request->ajax()) {
            $table = view('marketplace.pages.roles.partials.roles-table', compact('roles'))->render();
            $pagination = $roles->appends($request->query())->links('pagination::bootstrap-5')->render();

            return response()->json([
                'table' => $table,
                'pagination' => $pagination
            ]);
        }

        return view('marketplace.pages.roles.index', compact('roles'));
    }

    /**
     * Show form for creating new role.
     */
    public function create()
    {
        // Get permissions as a collection of models
        $permissions = Permission::where('guard_name', 'vendor')->get();

        return view('marketplace.pages.roles.create', compact('permissions'));
    }

    /**
     * Store newly created role.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name|string|max:255',
            'permissions' => 'array',
            'guard_name' => 'nullable|string|in:web,vendor'
        ]);

        $vendor = auth('vendor')->user();

        $data = [
            'name' => $request->name,
            'guard_name' => 'vendor'
        ];

        $role = Role::create($data);

        // Sync permissions - Convert IDs to names
        $permissionNames = [];
        $permissionDetails = [];

        if ($request->has('permissions')) {
            $permissions = Permission::whereIn('id', $request->permissions)
                ->where('guard_name', 'vendor')
                ->get();

            foreach ($permissions as $permission) {
                $permissionNames[] = $permission->name;
                $permissionDetails[] = [
                    'id' => $permission->id,
                    'name' => $permission->name,
                    'guard_name' => $permission->guard_name
                ];
            }

            $role->syncPermissions($permissionNames);
        }

        // Prepare new values for logging
        $newValues = [
            'name' => $role->name,
            'guard_name' => $role->guard_name,
            'permissions_count' => count($permissionNames),
            'permissions' => $permissionDetails,
            'created_by_vendor_id' => $vendor->id,
            'created_by_vendor_name' => $vendor->name,
            'shop_id' => $vendor->shop_id
        ];

        // Log activity
        $this->logActivity(
            'create',                    // action
            'role',                      // entity_type
            $role->id,                   // entity_id
            $role->name,                 // entity_name
            null,                        // old_values
            $newValues,                  // new_values
            "Created new role: {$role->name} (Guard: {$role->guard_name}) with " . count($permissionNames) . " permissions: " . implode(', ', $permissionNames)
        );

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Role created successfully with ' . count($permissionNames) . ' permissions.',
                'role' => $role,
                'permissions' => $permissionNames
            ]);
        }

        return redirect()->route('marketplace.roles.index')
            ->with('success', 'Role created successfully.');
    }

    /**
     * Display role details.
     */
    public function show(Role $role)
    {
        // Ensure role is for vendor guard
        if ($role->guard_name !== 'vendor') {
            abort(404, 'Role not found');
        }

        $role->load('permissions');
        $users = $role->users()->paginate(10);

        return view('marketplace.pages.roles.show', compact('role', 'users'));
    }

    /**
     * Show form for editing role.
     */
    public function edit(Role $role)
    {
        // Prevent editing Super Admin role
        if ($role->name === 'Super Admin') {
            return redirect()->route('vendor.roles.index')
                ->with('error', 'Super Admin role cannot be edited.');
        }

        // Get all permissions for vendor guard
        $permissions = Permission::where('guard_name', 'vendor')->get();

        // Get current role permissions as IDs
        $rolePermissions = $role->permissions->pluck('id')->toArray();

        return view('marketplace.pages.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * Update role.
     */
    public function update(Request $request, Role $role)
    {
        // Prevent updating Store Owner role
        if ($role->name === 'Store Owner') {
            // Log the blocked attempt
            $this->logActivity(
                'update_blocked',            // action
                'role',                      // entity_type
                $role->id,                   // entity_id
                $role->name,                 // entity_name
                null,                        // old_values
                [
                    'role_id' => $role->id,
                    'role_name' => $role->name,
                    'attempted_data' => $request->only(['name', 'guard_name'])
                ],                           // new_values
                "Attempted to edit Store Owner role: {$role->name}"
            );

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Store Owner role cannot be edited.'
                ], 403);
            }
            return redirect()->route('vendor.roles.index')
                ->with('error', 'Store Owner role cannot be edited.');
        }

        // Store old values for logging
        $oldName = $role->name;
        $oldGuardName = $role->guard_name;
        $oldPermissions = $role->permissions->pluck('id')->toArray();
        $oldPermissionNames = $role->permissions->pluck('name')->toArray();

        $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id . '|string|max:255',
            'permissions' => 'array',
            'guard_name' => 'nullable|string|in:web,vendor'
        ]);

        // Update role
        $role->update([
            'name' => $request->name,
            'guard_name' => 'vendor'
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
        $oldValues = [
            'name' => $oldName,
            'guard_name' => $oldGuardName,
            'permissions_count' => count($oldPermissions),
            'permissions' => $oldPermissionNames
        ];

        // Prepare new data for logging
        $newValues = [
            'name' => $role->name,
            'guard_name' => $role->guard_name,
            'permissions_count' => count($newPermissionIds),
            'permissions' => $permissionNames
        ];

        // Build description message
        $changes = [];
        if ($oldName !== $role->name) {
            $changes[] = "name changed from '{$oldName}' to '{$role->name}'";
        }
        if ($oldGuardName !== $role->guard_name) {
            $changes[] = "guard changed from '{$oldGuardName}' to '{$role->guard_name}'";
        }

        $permissionChanges = [];
        if (!empty($addedPermissions)) {
            $addedNames = Permission::whereIn('id', $addedPermissions)->pluck('name')->toArray();
            $permissionChanges[] = "added " . count($addedPermissions) . " permissions: " . implode(', ', $addedNames);
        }
        if (!empty($removedPermissions)) {
            $removedNames = Permission::whereIn('id', $removedPermissions)->pluck('name')->toArray();
            $permissionChanges[] = "removed " . count($removedPermissions) . " permissions: " . implode(', ', $removedNames);
        }

        $allChanges = array_merge($changes, $permissionChanges);
        $changeText = empty($allChanges) ? "no changes made" : implode('; ', $allChanges);

        // Log activity
        $this->logActivity(
            'update',                        // action
            'role',                          // entity_type
            $role->id,                       // entity_id
            $role->name,                     // entity_name
            $oldValues,                      // old_values
            $newValues,                      // new_values
            "Updated role: {$role->name} (Guard: {$role->guard_name}) - {$changeText}"
        );

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Role updated successfully with ' . count($request->permissions ?? []) . ' permissions.'
            ]);
        }

        return redirect()->route('vendor.roles.index')
            ->with('success', 'Role updated successfully.');
    }

    /**
     * Delete role.
     */
    public function destroy(Role $role)
    {
        // Check if trying to delete Store Owner role
        if ($role->name === 'Store Owner') {
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
                    'reason' => 'Store Owner role cannot be deleted'
                ],                           // new_values
                "Attempted to delete Store Owner role: {$role->name}"
            );

            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Store Owner role cannot be deleted.'
                ], 403);
            }
            return back()->with('error', 'Store Owner role cannot be deleted.');
        }

        // Check if role has assigned users
        $usersCount = $role->users()->count();
        if ($usersCount > 0) {
            // Get assigned user details for better logging
            $assignedUsers = $role->users()->take(5)->get(['id', 'name', 'email'])->toArray();

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
                    'assigned_users' => $assignedUsers,
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
            'created_at' => $role->created_at ? $role->created_at->toDateTimeString() : null,
            'updated_at' => $role->updated_at ? $role->updated_at->toDateTimeString() : null
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
            'role',                          // entity_type
            $roleId,                         // entity_id
            $roleName,                       // entity_name
            $oldValues,                      // old_values
            null,                            // new_values
            "Deleted role: {$roleName} (Guard: {$roleGuard}) with " . $oldValues['permissions_count'] . " permissions: [{$permissionsList}]"
        );

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Role deleted successfully.',
                'deleted_role' => [
                    'id' => $roleId,
                    'name' => $roleName,
                    'guard' => $roleGuard
                ]
            ]);
        }

        return redirect()->route('vendor.roles.index')
            ->with('success', 'Role deleted successfully.');
    }

    /**
     * Show assign permissions form.
     */
    public function assignPermissions(Role $role)
    {
        $permissions = Permission::where('guard_name', 'vendor')->get();

        $rolePermissions = $role->permissions->pluck('id')->toArray();

        return view('marketplace.pages.roles.assign-permissions', compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * Sync permissions for role.
     */
    public function syncPermissions(Request $request, Role $role)
    {
        try {
            // Check if role exists
            if (!$role || !$role->exists) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Role not found.'
                    ], 404);
                }
                return redirect()->route('vendor.roles.index')
                    ->with('error', 'Role not found.');
            }

            // Prevent syncing permissions for Store Owner role
            if ($role->name === 'Store Owner') {
                // Log the blocked attempt
                $this->logActivity(
                    'sync_permissions_blocked',  // action
                    'role',                       // entity_type
                    $role->id,                    // entity_id
                    $role->name,                  // entity_name
                    null,                         // old_values
                    [
                        'role_id' => $role->id,
                        'role_name' => $role->name,
                        'reason' => 'Store Owner role permissions cannot be modified'
                    ],                            // new_values
                    "Attempted to sync permissions for Store Owner role: {$role->name}"
                );

                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Store Owner role permissions cannot be modified.'
                    ], 403);
                }
                return redirect()->route('vendor.roles.show', $role->id)
                    ->with('error', 'Store Owner role permissions cannot be modified.');
            }

            // Validate request
            $request->validate([
                'permissions' => 'array'
            ]);

            // Store old permissions for logging
            $oldPermissions = $role->permissions->pluck('id')->toArray();
            $oldPermissionNames = $role->permissions->pluck('name')->toArray();
            $oldPermissionsCount = count($oldPermissions);

            // Convert permission IDs to names
            $permissionNames = [];
            $newPermissionIds = $request->permissions ?? [];

            if ($request->has('permissions') && !empty($request->permissions)) {
                $permissionNames = Permission::whereIn('id', $request->permissions)
                    ->pluck('name')
                    ->toArray();

                // Calculate added and removed permissions
                $addedPermissions = array_diff($request->permissions, $oldPermissions);
                $removedPermissions = array_diff($oldPermissions, $request->permissions);

                // Get names of added/removed permissions
                $addedPermissionNames = [];
                $removedPermissionNames = [];

                if (!empty($addedPermissions)) {
                    $addedPermissionNames = Permission::whereIn('id', $addedPermissions)->pluck('name')->toArray();
                }
                if (!empty($removedPermissions)) {
                    $removedPermissionNames = Permission::whereIn('id', $removedPermissions)->pluck('name')->toArray();
                }

                $role->syncPermissions($permissionNames);

                // Prepare change details for description
                $changeDetails = [];
                if (!empty($addedPermissionNames)) {
                    $changeDetails[] = "added " . count($addedPermissionNames) . " permissions: " . implode(', ', $addedPermissionNames);
                }
                if (!empty($removedPermissionNames)) {
                    $changeDetails[] = "removed " . count($removedPermissionNames) . " permissions: " . implode(', ', $removedPermissionNames);
                }
                $changeText = implode('; ', $changeDetails);

                // Log the permission sync with details
                $this->logActivity(
                    'sync_permissions',          // action
                    'role',                       // entity_type
                    $role->id,                    // entity_id
                    $role->name,                  // entity_name
                    [
                        'permissions_count' => $oldPermissionsCount,
                        'permissions' => $oldPermissionNames
                    ],                            // old_values
                    [
                        'permissions_count' => count($permissionNames),
                        'permissions' => $permissionNames,
                        'added_permissions_count' => count($addedPermissions),
                        'added_permissions' => $addedPermissionNames,
                        'removed_permissions_count' => count($removedPermissions),
                        'removed_permissions' => $removedPermissionNames
                    ],                            // new_values
                    "Synced permissions for role: {$role->name} - {$changeText}"
                );
            } else {
                // Remove all permissions
                $removedPermissions = $oldPermissions;
                $removedPermissionNames = $oldPermissionNames;

                $role->syncPermissions([]);

                // Log the removal of all permissions
                $this->logActivity(
                    'sync_permissions',          // action
                    'role',                       // entity_type
                    $role->id,                    // entity_id
                    $role->name,                  // entity_name
                    [
                        'permissions_count' => $oldPermissionsCount,
                        'permissions' => $oldPermissionNames
                    ],                            // old_values
                    [
                        'permissions_count' => 0,
                        'permissions' => [],
                        'removed_permissions_count' => count($removedPermissions),
                        'removed_permissions' => $removedPermissionNames
                    ],                            // new_values
                    "Removed all permissions from role: {$role->name} (Removed " . count($removedPermissions) . " permissions)"
                );
            }

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Permissions synced successfully.',
                    'data' => [
                        'role_id' => $role->id,
                        'role_name' => $role->name,
                        'permissions_count' => count($permissionNames),
                        'permissions' => $permissionNames
                    ]
                ]);
            }

            return redirect()->route('vendor.roles.show', $role->id)
                ->with('success', 'Permissions updated successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Log validation error
            \Log::channel('vendor')->error('Permission sync validation error', [
                'vendor_id' => auth()->id(),
                'vendor_email' => auth()->user()->email ?? 'unknown',
                'role_id' => $role->id ?? null,
                'role_name' => $role->name ?? null,
                'errors' => $e->errors(),
                'ip' => request()->ip()
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed.',
                    'errors' => $e->errors()
                ], 422);
            }

            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            // Log unexpected error
            \Log::channel('vendor')->error('Permission sync failed', [
                'vendor_id' => auth()->id(),
                'vendor_email' => auth()->user()->email ?? 'unknown',
                'role_id' => $role->id ?? null,
                'role_name' => $role->name ?? null,
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString(),
                'ip' => request()->ip()
            ]);

            $this->logActivity(
                'sync_permissions_failed',      // action
                'role',                          // entity_type
                $role->id ?? null,               // entity_id
                $role->name ?? 'Unknown',        // entity_name
                null,                            // old_values
                [
                    'error' => $e->getMessage(),
                    'request_data' => $request->all()
                ],                               // new_values
                "Failed to sync permissions for role: {$role->name} - Error: " . $e->getMessage()
            );

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to sync permissions: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->route('vendor.roles.show', $role->id)
                ->with('error', 'Failed to sync permissions: ' . $e->getMessage());
        }
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

        if (!auth('vendor')->user()->can('delete_roles')) {
            // Log permission denied
            $this->logActivity(
                'bulk_action_denied',           // action
                'role',                          // entity_type
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
                'role',                          // entity_type
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
