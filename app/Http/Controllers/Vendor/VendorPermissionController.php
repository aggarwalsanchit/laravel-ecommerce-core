<?php
// app/Http/Controllers/Vendor/VendorPermissionController.php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class VendorPermissionController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth:vendor',
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

        $permissions = $query->paginate(15);

        // Get unique modules for filter
        $allPermissions = Permission::all();
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
            $table = view('marketplace.pages.permissions.partials.permissions-table', compact('permissions'))->render();
            $pagination = $permissions->appends($request->query())->links('pagination::bootstrap-5')->render();

            return response()->json([
                'table' => $table,
                'pagination' => $pagination
            ]);
        }

        return view('marketplace.pages.permissions.index', compact('permissions', 'modules'));
    }

    /**
     * Show form for creating new permission.
     */
    public function create()
    {
        return view('marketplace.pages.permissions.create');
    }

    /**
     * Store newly created permission.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name|regex:/^[a-z]+ [a-z]+$/',
        ], [
            'name.regex' => 'Permission name must be in format: "action module" (e.g., "view users", "create posts")'
        ]);

        // Create permission with vendor guard
        $permission = Permission::create([
            'name' => $request->name,
            'guard_name' => 'vendor' // Important: Set guard_name for vendor permissions
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Permission created successfully.',
                'permission' => $permission
            ]);
        }

        return redirect()->route('vendor.permissions.index')
            ->with('success', 'Permission created successfully.');
    }

    /**
     * Display permission details.
     */
    public function show(Permission $permission)
    {
        $roles = $permission->roles()->paginate(10);

        return view('marketplace.pages.permissions.show', compact('permission', 'roles'));
    }

    /**
     * Show form for editing permission.
     */
    public function edit(Permission $permission)
    {
        return view('marketplace.pages.permissions.edit', compact('permission'));
    }

    /**
     * Update permission.
     */
    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name,' . $permission->id . '|regex:/^[a-z]+ [a-z]+$/',
        ]);

        $permission->update(['name' => $request->name]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Permission updated successfully.'
            ]);
        }

        return redirect()->route('vendor.permissions.index')
            ->with('success', 'Permission updated successfully.');
    }

    /**
     * Delete permission.
     */
    public function destroy(Permission $permission)
    {
        // Check if permission is assigned to any roles
        if ($permission->roles()->count() > 0) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete permission because it is assigned to roles.'
                ], 422);
            }
            return back()->with('error', 'Cannot delete permission because it is assigned to roles.');
        }

        $permission->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Permission deleted successfully.'
            ]);
        }

        return redirect()->route('vendor.permissions.index')
            ->with('success', 'Permission deleted successfully.');
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

        if (!auth('vendor')->user()->can('delete permissions')) {
            return response()->json(['success' => false, 'message' => 'Permission denied.'], 403);
        }

        $permissions = Permission::whereIn('id', $permissionIds)->get();
        $count = 0;

        foreach ($permissions as $permission) {
            // Check if permission is assigned to any roles
            if ($permission->roles()->count() == 0) {
                $permission->delete();
                $count++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => "{$count} permissions deleted successfully."
        ]);
    }
}
