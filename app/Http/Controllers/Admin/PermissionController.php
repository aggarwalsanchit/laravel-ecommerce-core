<?php
// app/Http/Controllers/Admin/PermissionController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\Traits\LogsAdminActivity;

class PermissionController extends Controller implements HasMiddleware
{

    use LogsAdminActivity;

    public static function middleware(): array
    {
        return [
            'auth:admin',
            new Middleware('permission:view_permissions', only: ['index', 'show']),
            new Middleware('permission:create_permissions', only: ['create', 'store']),
            new Middleware('permission:edit_permissions', only: ['edit', 'update']),
            new Middleware('permission:delete_permissions', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of permissions.
     */
    public function index(Request $request)
    {
        $query = Permission::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        // Filter by module
        if ($request->filled('module') && $request->module != '') {
            $query->where('name', 'like', "%{$request->module}");
        }

        $permissions = $query->where('guard_name', 'admin')->paginate(15);

        // Get unique modules for filter
        $allPermissions = Permission::where('guard_name', 'admin')->get();
        $modules = [];
        foreach ($allPermissions as $perm) {
            $parts = explode(' ', $perm->name);
            $module = $parts[1] ?? 'other';
            if (!in_array($module, $modules)) {
                $modules[] = $module;
            }
        }

        // If AJAX request
        if ($request->ajax()) {
            $table = view('admin.pages.permissions.partials.permissions-table', compact('permissions'))->render();
            $pagination = $permissions->appends($request->query())->links('pagination::bootstrap-5')->render();

            return response()->json([
                'table' => $table,
                'pagination' => $pagination
            ]);
        }

        return view('admin.pages.permissions.index', compact('permissions', 'modules'));
    }

    /**
     * Show form for creating new permission.
     */
    public function create()
    {
        return view('admin.pages.permissions.create');
    }

    /**
     * Store newly created permission.
     */
    public function store(Request $request)
    {
        $request->validate([
            'permissions' => 'required|string',
        ]);

        // Get permissions from input, split by comma, trim spaces
        $permissionNames = array_map('trim', explode(',', $request->permissions));

        // Remove empty values
        $permissionNames = array_filter($permissionNames);

        if (empty($permissionNames)) {
            return response()->json([
                'success' => false,
                'message' => 'Please enter valid permission names.',
                'errors' => ['permissions' => ['Please enter valid permission names.']]
            ], 422);
        }

        // Convert spaces to underscores and clean the names
        $permissionNames = array_map(function ($name) {
            // Convert spaces to underscores
            $name = str_replace(' ', '_', $name);
            // Convert multiple underscores to single
            $name = preg_replace('/_+/', '_', $name);
            // Convert to lowercase
            $name = strtolower($name);
            // Remove any special characters except underscore and letters/numbers
            $name = preg_replace('/[^a-z0-9_]/', '', $name);
            // Remove leading/trailing underscores
            $name = trim($name, '_');
            return $name;
        }, $permissionNames);

        // Remove empty after cleaning
        $permissionNames = array_filter($permissionNames);

        // Check for duplicates in the request
        $duplicates = array_diff_assoc($permissionNames, array_unique($permissionNames));
        if (!empty($duplicates)) {
            return response()->json([
                'success' => false,
                'message' => 'Duplicate permissions found in your input.',
                'errors' => ['permissions' => ['Duplicate permissions: ' . implode(', ', array_unique($duplicates))]]
            ], 422);
        }

        // Check which permissions already exist in database
        $existingPermissions = Permission::whereIn('name', $permissionNames)
            ->where('guard_name', 'admin')
            ->pluck('name')
            ->toArray();

        // Filter out existing permissions
        $newPermissions = array_diff($permissionNames, $existingPermissions);

        if (empty($newPermissions)) {
            return response()->json([
                'success' => false,
                'message' => 'All permissions already exist.',
                'errors' => ['permissions' => ['Already exists: ' . implode(', ', $existingPermissions)]]
            ], 422);
        }

        $createdPermissions = [];
        $failedPermissions = [];

        foreach ($newPermissions as $permissionName) {
            try {
                $permission = Permission::create([
                    'name' => $permissionName,
                    'guard_name' => 'admin',
                ]);
                $createdPermissions[] = $permissionName;
            } catch (\Exception $e) {
                $failedPermissions[] = $permissionName;
            }
        }

        // Log activity
        if (!empty($createdPermissions)) {
            $this->logActivity(
                'create',
                'permissions',
                'admin',
                null,
                null,
                null,
                ['permissions' => $createdPermissions],
                'Created permissions: ' . implode(', ', $createdPermissions)
            );
        }

        // Prepare response message
        $message = '';
        if (count($createdPermissions) > 0) {
            $message = count($createdPermissions) . ' permission(s) created successfully: ' . implode(', ', $createdPermissions);
        }
        if (count($failedPermissions) > 0) {
            $message .= ' Failed to create: ' . implode(', ', $failedPermissions);
        }
        if (count($existingPermissions) > 0) {
            $message .= ' Skipped (already exist): ' . implode(', ', $existingPermissions);
        }

        if (count($createdPermissions) > 0) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'created' => $createdPermissions,
                'failed' => $failedPermissions,
                'existing' => $existingPermissions
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to create permissions.',
            'errors' => ['permissions' => ['Failed to create permissions.']]
        ], 500);
    }

    /**
     * Display permission details.
     */
    public function show(Permission $permission)
    {
        $roles = $permission->roles()
            ->where('guard_name', 'admin')
            ->paginate(10);

        return view('admin.pages.permissions.show', compact('permission', 'roles'));
    }

    /**
     * Show form for editing permission.
     */
    public function edit(Permission $permission)
    {
        return view('admin.pages.permissions.edit', compact('permission'));
    }

    /**
     * Update permission.
     */
    public function update(Request $request, Permission $permission)
    {
        // Ensure permission is for admin guard
        if ($permission->guard_name !== 'admin') {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid permission guard.'
                ], 403);
            }
            return redirect()->route('admin.permissions.index')->with('error', 'Invalid permission guard.');
        }

        $request->validate([
            'name' => 'required|string|unique:permissions,name,' . $permission->id . ',id,guard_name,admin',
        ]);

        // Convert spaces to underscores and clean the name
        $newName = str_replace(' ', '_', strtolower($request->name));
        $newName = preg_replace('/[^a-z0-9_]/', '', $newName);
        $newName = trim($newName, '_');

        $oldName = $permission->name;

        $permission->update([
            'name' => $newName,
            'guard_name' => 'admin',
        ]);

        // Log activity
        $this->logActivity(
            'update',
            'permission',
            'admin',
            $permission->id,
            $permission->name,
            ['name' => $oldName],
            ['name' => $newName],
            "Updated permission from '{$oldName}' to '{$newName}'"
        );

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Permission updated successfully!'
            ]);
        }

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permission updated successfully.');
    }

    /**
     * Delete permission.
     */
    public function destroy(Permission $permission)
    {
        // Check if user has permission to delete
        if (!auth('admin')->user()->can('delete_permissions')) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have permission to delete permissions.'
                ], 403);
            }
            return back()->with('error', 'You do not have permission to delete permissions.');
        }

        // Check if permission is assigned to any roles
        $rolesCount = $permission->roles()->count();

        if ($rolesCount > 0) {
            $assignedRoles = $permission->roles()->pluck('name')->toArray();

            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => "Cannot delete permission '{$permission->name}' because it is assigned to " . $rolesCount . " role(s).",
                    'assigned_roles' => $assignedRoles
                ], 422);
            }
            return back()->with('error', "Cannot delete permission '{$permission->name}' because it is assigned to " . $rolesCount . " role(s).");
        }

        $permissionName = $permission->name;
        $permissionId = $permission->id;
        $permissionGuard = $permission->guard_name;

        // Delete the permission
        $permission->delete();

        // Log the activity
        $this->logActivity(
            'delete',           // action
            'permissions',      // module
            'admin',       // entity_type
            $permissionId,      // entity_id
            $permissionName,    // entity_name
            [                   // old_values
                'name' => $permissionName,
                'guard_name' => $permissionGuard,
                'roles_count' => $rolesCount
            ],
            null,               // new_values
            "Deleted permission: {$permissionName} (Guard: {$permissionGuard})"  // description
        );

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Permission '{$permissionName}' deleted successfully."
            ]);
        }

        return redirect()->route('admin.permissions.index')
            ->with('success', "Permission '{$permissionName}' deleted successfully.");
    }

    /**
     * Bulk action on permissions.
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete',
            'permission_ids' => 'required|string',
        ]);

        $action = $request->action;
        $permissionIds = json_decode($request->permission_ids);

        // Check permission
        if (!auth('admin')->user()->can('delete_permissions')) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have permission to delete permissions.'
                ], 403);
            }
            return back()->with('error', 'You do not have permission to delete permissions.');
        }

        $permissions = Permission::whereIn('id', $permissionIds)
            ->where('guard_name', 'admin')
            ->get();

        $deletedCount = 0;
        $failedCount = 0;
        $deletedPermissions = [];
        $failedPermissions = [];

        foreach ($permissions as $permission) {
            $rolesCount = $permission->roles()->count();

            if ($rolesCount == 0) {
                $permissionName = $permission->name;
                $permissionId = $permission->id;

                // Log individual permission deletion
                $this->logActivity(
                    'delete',
                    'permissions',
                    'admin',
                    $permissionId,
                    $permissionName,
                    [
                        'name' => $permissionName,
                        'guard_name' => $permission->guard_name,
                        'deleted_via' => 'bulk_action'
                    ],
                    null,
                    "Deleted permission: {$permissionName} (via bulk action)"
                );

                $permission->delete();
                $deletedCount++;
                $deletedPermissions[] = $permissionName;
            } else {
                $failedCount++;
                $failedPermissions[] = [
                    'name' => $permission->name,
                    'roles_count' => $rolesCount,
                    'roles' => $permission->roles()->pluck('name')->toArray()
                ];

                // Log failed deletion attempt
                $this->logActivity(
                    'delete_failed',
                    'permissions',
                    'admin',
                    $permission->id,
                    $permission->name,
                    [
                        'name' => $permission->name,
                        'guard_name' => $permission->guard_name,
                        'roles_count' => $rolesCount,
                        'assigned_roles' => $permission->roles()->pluck('name')->toArray()
                    ],
                    null,
                    "Failed to delete permission '{$permission->name}' - Assigned to {$rolesCount} role(s)"
                );
            }
        }

        // Log bulk action summary
        if ($deletedCount > 0 || $failedCount > 0) {
            $this->logActivity(
                $deletedCount > 0 ? 'bulk_delete' : 'bulk_delete_attempt',
                'permissions',
                'permissions',
                null,
                null,
                [
                    'requested_count' => count($permissionIds),
                    'deleted_count' => $deletedCount,
                    'failed_count' => $failedCount,
                    'deleted_permissions' => $deletedPermissions,
                    'failed_permissions' => $failedPermissions
                ],
                null,
                "Bulk action completed: {$deletedCount} deleted, {$failedCount} failed"
            );
        }

        $message = "{$deletedCount} permission(s) deleted successfully.";
        if ($failedCount > 0) {
            $message .= " {$failedCount} permission(s) could not be deleted because they are assigned to roles.";
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'deleted_count' => $deletedCount,
                'failed_count' => $failedCount,
                'deleted_permissions' => $deletedPermissions,
                'failed_permissions' => array_column($failedPermissions, 'name')
            ]);
        }

        return redirect()->route('admin.permissions.index')
            ->with('success', $message);
    }
}
