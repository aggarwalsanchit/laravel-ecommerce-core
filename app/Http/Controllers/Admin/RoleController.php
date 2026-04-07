<?php
// app/Http/Controllers/Admin/RoleController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class RoleController extends Controller implements HasMiddleware
{
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
        $query = Role::with('permissions');

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
        $permissions = Permission::where('guard_name', 'admin')
            ->get()
            ->groupBy(function ($permission) {
                $parts = explode(' ', $permission->name);
                return $parts[1] ?? 'general';
            });

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
        if ($request->has('permissions')) {
            $permissionNames = Permission::whereIn('id', $request->permissions)
                ->pluck('name')
                ->toArray();

            $role->syncPermissions($permissionNames);
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Role created successfully with ' . count($request->permissions ?? []) . ' permissions.',
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
        $permissions = Permission::where('guard_name', 'admin')
            ->get()
            ->groupBy(function ($permission) {
                $parts = explode(' ', $permission->name);
                return $parts[1] ?? 'general';
            });

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
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Super Admin role cannot be edited.'
                ], 403);
            }
            return redirect()->route('admin.roles.index')
                ->with('error', 'Super Admin role cannot be edited.');
        }

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

        // Sync permissions - IMPORTANT: Convert IDs to names
        if ($request->has('permissions')) {
            // Get permission names from IDs
            $permissionNames = Permission::whereIn('id', $request->permissions)
                ->pluck('name')
                ->toArray();

            $role->syncPermissions($permissionNames);
        } else {
            $role->syncPermissions([]);
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Role updated successfully with ' . count($request->permissions ?? []) . ' permissions.'
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
        if ($role->name === 'Super Admin') {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Super Admin role cannot be deleted.'
                ], 403);
            }
            return back()->with('error', 'Super Admin role cannot be deleted.');
        }

        if ($role->users()->count() > 0) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete role because it has ' . $role->users()->count() . ' assigned users.'
                ], 422);
            }
            return back()->with('error', 'Cannot delete role because it has assigned users.');
        }

        $role->delete();

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
        $permissions = Permission::where('guard_name', 'admin')
            ->get()
            ->groupBy(function ($permission) {
                $parts = explode(' ', $permission->name);
                return $parts[1] ?? 'general';
            });

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

        // Convert permission IDs to names
        if ($request->has('permissions')) {
            $permissionNames = Permission::whereIn('id', $request->permissions)
                ->pluck('name')
                ->toArray();

            $role->syncPermissions($permissionNames);
        } else {
            $role->syncPermissions([]);
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Permissions synced successfully.'
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

        if (!auth('admin')->user()->can('delete roles')) {
            return response()->json(['success' => false, 'message' => 'Permission denied.'], 403);
        }

        $roles = Role::whereIn('id', $roleIds)
            ->where('name', '!=', 'Super Admin')
            ->get();

        $count = 0;

        foreach ($roles as $role) {
            if ($role->users()->count() == 0) {
                $role->delete();
                $count++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => "{$count} roles deleted successfully."
        ]);
    }
}
