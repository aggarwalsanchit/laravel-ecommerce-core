<?php
// app/Http/Controllers/Admin/PermissionController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('permission:view permissions')->only(['index', 'show']);
    //     $this->middleware('permission:create permissions')->only(['create', 'store']);
    //     $this->middleware('permission:edit permissions')->only(['edit', 'update']);
    //     $this->middleware('permission:delete permissions')->only(['destroy']);
    // }

    /**
     * Display a listing of permissions.
     */
    public function index(Request $request)
    {
        $query = Permission::query();

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $permissions = $query->paginate(20);

        return view('permissions.index', compact('permissions'));
    }

    /**
     * Show form for creating new permission.
     */
    public function create()
    {
        return view('permissions.create');
    }

    /**
     * Store newly created permission.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name',
        ]);

        Permission::create(['name' => $request->name]);

        return redirect()->route('permissions.index')
            ->with('success', 'Permission created successfully.');
    }

    /**
     * Display permission details.
     */
    public function show(Permission $permission)
    {
        $roles = $permission->roles()->paginate(10);

        return view('permissions.show', compact('permission', 'roles'));
    }

    /**
     * Show form for editing permission.
     */
    public function edit(Permission $permission)
    {
        return view('permissions.edit', compact('permission'));
    }

    /**
     * Update permission.
     */
    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name,' . $permission->id,
        ]);

        $permission->update(['name' => $request->name]);

        return redirect()->route('permissions.index')
            ->with('success', 'Permission updated successfully.');
    }

    /**
     * Delete permission.
     */
    public function destroy(Permission $permission)
    {
        // Check if permission is assigned to any roles
        if ($permission->roles()->count() > 0) {
            return back()->with('error', 'Cannot delete permission because it is assigned to roles.');
        }

        $permission->delete();

        return redirect()->route('permissions.index')
            ->with('success', 'Permission deleted successfully.');
    }
}
