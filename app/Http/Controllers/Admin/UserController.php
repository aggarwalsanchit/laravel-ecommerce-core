<?php
// app/Http/Controllers/UserController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('permission:view users')->only(['index', 'show']);
    //     $this->middleware('permission:create users')->only(['create', 'store']);
    //     $this->middleware('permission:edit users')->only(['edit', 'update']);
    //     $this->middleware('permission:delete users')->only(['destroy']);
    //     $this->middleware('permission:activate users')->only(['activate', 'deactivate']);
    // }

    /**
     * Display a listing of users.
     */
    public function showUsers(Request $request)
    {
        $query = Admin::query();

        // Search functionality
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%');
        }

        // Filter by status
        if ($request->has('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Filter by role
        if ($request->has('role') && $request->role != '') {
            $query->role($request->role);
        }

        $users = $query->with('roles')->paginate(10);
        $roles = Role::all();

        return view('users.index', compact('users', 'roles'));
    }

    /**
     * Show form for creating new user.
     */
    public function create()
    {
        $roles = Role::all();
        $permissions = Permission::all();

        return view('users.create', compact('roles', 'permissions'));
    }

    /**
     * Store newly created user.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'roles' => 'array',
            'permissions' => 'array',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'is_active' => $request->has('is_active'),
        ];

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = basename($avatarPath);
        }

        $user = Admin::create($data);

        // Assign roles
        if ($request->has('roles')) {
            $user->syncRoles($request->roles);
        }

        // Assign direct permissions
        if ($request->has('permissions')) {
            $user->syncPermissions($request->permissions);
        }

        return redirect()->route('users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display user details.
     */
    public function show(Admin $user)
    {
        $user->load('roles', 'permissions');
        return view('users.show', compact('user'));
    }

    /**
     * Show form for editing user.
     */
    public function edit(Admin $user)
    {
        $roles = Role::all();
        $permissions = Permission::all();
        $userRoles = $user->roles->pluck('name')->toArray();
        $userPermissions = $user->permissions->pluck('name')->toArray();

        return view('users.edit', compact('user', 'roles', 'permissions', 'userRoles', 'userPermissions'));
    }

    /**
     * Update user.
     */
    public function update(Request $request, Admin $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'roles' => 'array',
            'permissions' => 'array',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'is_active' => $request->has('is_active'),
        ];

        // Update password if provided
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar
            if ($user->avatar) {
                Storage::disk('public')->delete('avatars/' . $user->avatar);
            }

            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = basename($avatarPath);
        }

        $user->update($data);

        // Sync roles
        if ($request->has('roles')) {
            $user->syncRoles($request->roles);
        } else {
            $user->syncRoles([]);
        }

        // Sync permissions
        if ($request->has('permissions')) {
            $user->syncPermissions($request->permissions);
        } else {
            $user->syncPermissions([]);
        }

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Delete user.
     */
    public function destroy(Admin $user)
    {
        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        // Delete avatar
        if ($user->avatar) {
            Storage::disk('public')->delete('avatars/' . $user->avatar);
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully.');
    }

    /**
     * Activate user.
     */
    public function activate(Admin $user)
    {
        $user->update(['is_active' => true]);

        return back()->with('success', 'User activated successfully.');
    }

    /**
     * Deactivate user.
     */
    public function deactivate(Admin $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot deactivate your own account.');
        }

        $user->update(['is_active' => false]);

        return back()->with('success', 'User deactivated successfully.');
    }

    /**
     * Bulk action on users.
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $users = Admin::whereIn('id', $request->user_ids)
            ->where('id', '!=', auth()->id()) // Exclude current user
            ->get();

        foreach ($users as $user) {
            if ($request->action === 'activate') {
                $user->update(['is_active' => true]);
            } elseif ($request->action === 'deactivate') {
                $user->update(['is_active' => false]);
            } elseif ($request->action === 'delete') {
                if ($user->avatar) {
                    Storage::disk('public')->delete('avatars/' . $user->avatar);
                }
                $user->delete();
            }
        }

        $count = $users->count();
        return back()->with('success', "{$count} users {$request->action}d successfully.");
    }
}
