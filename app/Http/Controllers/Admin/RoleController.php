<?php
// app/Http/Controllers/Admin/RoleController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('permission:view roles')->only(['index', 'show']);
    //     $this->middleware('permission:create roles')->only(['create', 'store']);
    //     $this->middleware('permission:edit roles')->only(['edit', 'update']);
    //     $this->middleware('permission:delete roles')->only(['destroy']);
    // }

    /**
     * Display a listing of roles.
     */
    public function index(Request $request)
    {
        $query = Role::with('permissions');

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $roles = $query->paginate(10);

        return view('roles.index', compact('roles'));
    }

    /**
     * Show form for creating new role.
     */
    public function create()
    {
        $permissions = Permission::all()->groupBy(function ($permission) {
            return explode(' ', $permission->name)[1] ?? 'other';
        });

        return view('roles.create', compact('permissions'));
    }

    /**
     * Store newly created role.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'array',
        ]);

        $role = Role::create(['name' => $request->name]);

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return redirect()->route('roles.index')
            ->with('success', 'Role created successfully.');
    }

    /**
     * Display role details.
     */
    public function show(Role $role)
    {
        $role->load('permissions');
        $users = $role->users()->paginate(10);

        return view('roles.show', compact('role', 'users'));
    }

    /**
     * Show form for editing role.
     */
    public function edit(Role $role)
    {
        if ($role->name === 'Super Admin') {
            return back()->with('error', 'Super Admin role cannot be edited.');
        }

        $permissions = Permission::all()->groupBy(function ($permission) {
            return explode(' ', $permission->name)[1] ?? 'other';
        });
        $rolePermissions = $role->permissions->pluck('name')->toArray();

        return view('roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * Update role.
     */
    public function update(Request $request, Role $role)
    {
        if ($role->name === 'Super Admin') {
            return back()->with('error', 'Super Admin role cannot be edited.');
        }

        $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id,
            'permissions' => 'array',
        ]);

        $role->update(['name' => $request->name]);

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return redirect()->route('roles.index')
            ->with('success', 'Role updated successfully.');
    }

    /**
     * Delete role.
     */
    public function destroy(Role $role)
    {
        if ($role->name === 'Super Admin') {
            return back()->with('error', 'Super Admin role cannot be deleted.');
        }

        // Check if role has users
        if ($role->users()->count() > 0) {
            return back()->with('error', 'Cannot delete role because it has assigned users.');
        }

        $role->delete();

        return redirect()->route('roles.index')
            ->with('success', 'Role deleted successfully.');
    }
}
