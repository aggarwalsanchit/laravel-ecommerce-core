<?php
// app/Http/Controllers/Vendor/VendorUserController.php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class VendorUserController extends Controller implements HasMiddleware
{
    /**
     * Define middleware for this controller.
     */
    public static function middleware(): array
    {
        return [
            'auth:vendor',

            new Middleware('permission:view users', only: ['index', 'show']),
            new Middleware('permission:create users', only: ['create', 'store']),
            new Middleware('permission:edit users', only: ['edit', 'update']),
            new Middleware('permission:delete users', only: ['destroy']),
            new Middleware('permission:activate users', only: ['activate']),
            new Middleware('permission:deactivate users', only: ['deactivate']),
        ];
    }

    /**
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        $query = Admin::with('roles', 'permissions');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->role($request->role);
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $users = $query->paginate(10);
        $roles = Role::all();

        // If AJAX request, return JSON with table and pagination
        if ($request->ajax()) {
            $table = view('marketplace.pages.users.partials.users-table', compact('users'))->render();

            // Use Laravel's default pagination view
            $pagination = $users->links('pagination::bootstrap-5')->render();

            return response()->json([
                'table' => $table,
                'pagination' => $pagination
            ]);
        }

        return view('marketplace.pages.users.index', compact('users', 'roles'));
    }

    /**
     * Show form for creating new user.
     */
    public function create()
    {
        $roles = Role::all();
        $permissions = Permission::all();

        return view('marketplace.pages.users.create', compact('roles', 'permissions'));
    }

    /**
     * Store newly created user.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'birth_date' => 'nullable|date',
            'is_active' => 'boolean',
            'role' => 'required|exists:roles,name',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->except(['password', 'role', 'avatar']);
        $data['password'] = Hash::make($request->password);
        $data['is_active'] = $request->is_active ?? true;

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = basename($avatarPath);
        }

        $user = Admin::create($data);

        // Assign the selected role
        $user->assignRole($request->role);

        return response()->json([
            'success' => true,
            'message' => 'User created successfully with ' . $request->role . ' role.',
            'user' => $user
        ]);
    }

    /**
     * Display user details.
     */
    public function show(Admin $user)
    {
        // Load relationships
        $user->load('roles', 'permissions');

        return view('marketplace.pages.users.show', compact('user'));
    }

    /**
     * Show form for editing user.
     */
    public function edit(Admin $user)
    {
        $roles = Role::all();
        $userRole = $user->roles->first() ? $user->roles->first()->name : '';

        return view('marketplace.pages.users.edit', compact('user', 'roles', 'userRole'));
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
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'birth_date' => 'nullable|date',
            'is_active' => 'boolean',
            'role' => 'required|exists:roles,name',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'remove_avatar' => 'nullable|boolean',
        ]);

        $data = $request->except(['password', 'role', 'avatar', 'remove_avatar']);

        // Update password only if provided
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $data['is_active'] = $request->is_active ?? true;

        // Handle avatar
        if ($request->has('remove_avatar') && $request->remove_avatar) {
            if ($user->avatar) {
                Storage::disk('public')->delete('avatars/' . $user->avatar);
                $data['avatar'] = null;
            }
        }

        if ($request->hasFile('avatar')) {
            // Delete old avatar
            if ($user->avatar) {
                Storage::disk('public')->delete('avatars/' . $user->avatar);
            }

            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = basename($avatarPath);
        }

        $user->update($data);

        // Sync role (remove old roles and assign new one)
        $user->syncRoles([$request->role]);

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully with ' . $request->role . ' role.'
        ]);
    }

    /**
     * Delete user.
     */
    public function destroy(Admin $user)
    {
        // Check permission
        if (!auth()->user()->can('delete users')) {
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'You do not have permission to delete users.'], 403);
            }
            return back()->with('error', 'You do not have permission to delete users.');
        }

        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'You cannot delete your own account.'], 403);
            }
            return back()->with('error', 'You cannot delete your own account.');
        }

        // Optional: Prevent deleting Super Admin users
        if ($user->hasRole('Super Admin')) {
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'Super Admin users cannot be deleted.'], 403);
            }
            return back()->with('error', 'Super Admin users cannot be deleted.');
        }

        // Delete avatar if exists
        if ($user->avatar) {
            Storage::disk('public')->delete('avatars/' . $user->avatar);
        }

        // Delete user
        $user->delete();

        // Return response based on request type
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully.'
            ]);
        }

        return redirect()->route('vendor.users.index')
            ->with('success', 'User deleted successfully.');
    }

    /**
     * Activate user.
     */
    public function activate(Admin $user)
    {
        // Check permission
        if (!auth('vendor')->user()->can('activate users')) {
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'You do not have permission to activate users.'], 403);
            }
            return back()->with('error', 'You do not have permission to activate users.');
        }

        // Activate user
        $user->update(['is_active' => true]);

        // Return response based on request type
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'User activated successfully.'
            ]);
        }

        return back()->with('success', 'User activated successfully.');
    }

    /**
     * Deactivate user.
     */
    public function deactivate(Admin $user)
    {
        // Check permission
        if (!auth('vendor')->user()->can('deactivate users')) {
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'You do not have permission to deactivate users.'], 403);
            }
            return back()->with('error', 'You do not have permission to deactivate users.');
        }

        // Prevent deactivating yourself
        if ($user->id === auth('vendor')->id()) {
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'You cannot deactivate your own account.'], 403);
            }
            return back()->with('error', 'You cannot deactivate your own account.');
        }

        // Prevent deactivating Super Admin
        if ($user->hasRole('Super Admin')) {
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'Super Admin users cannot be deactivated.'], 403);
            }
            return back()->with('error', 'Super Admin users cannot be deactivated.');
        }

        // Deactivate user
        $user->update(['is_active' => false]);

        // Return response based on request type
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'User deactivated successfully.'
            ]);
        }

        return back()->with('success', 'User deactivated successfully.');
    }

    /**
     * Bulk action on users.
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'user_ids' => 'required|string', // Comes as JSON string
        ]);

        $action = $request->action;
        $userIds = json_decode($request->user_ids); // Decode JSON string to array

        // Check permission based on action
        if ($action === 'activate' && !auth('vendor')->user()->can('activate users')) {
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'You do not have permission to activate users.'], 403);
            }
            return back()->with('error', 'You do not have permission to activate users.');
        }

        if ($action === 'deactivate' && !auth('vendor')->user()->can('deactivate users')) {
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'You do not have permission to deactivate users.'], 403);
            }
            return back()->with('error', 'You do not have permission to deactivate users.');
        }

        if ($action === 'delete' && !auth('vendor')->user()->can('delete users')) {
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'You do not have permission to delete users.'], 403);
            }
            return back()->with('error', 'You do not have permission to delete users.');
        }

        // Get users, excluding current vendor and Super Admin
        $users = Admin::whereIn('id', $userIds)
            ->where('id', '!=', auth('vendor')->id()) // Exclude current user
            ->get();

        $count = 0;
        foreach ($users as $user) {
            // Skip Super Admin users for deactivate and delete actions
            if ($user->hasRole('Super Admin') && ($action === 'deactivate' || $action === 'delete')) {
                continue; // Skip Super Admin
            }

            if ($action === 'activate') {
                $user->update(['is_active' => true]);
                $count++;
            } elseif ($action === 'deactivate') {
                $user->update(['is_active' => false]);
                $count++;
            } elseif ($action === 'delete') {
                // Delete avatar if exists
                if ($user->avatar) {
                    Storage::disk('public')->delete('avatars/' . $user->avatar);
                }
                $user->delete();
                $count++;
            }
        }

        // Return response based on request type
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "{$count} users {$action}d successfully."
            ]);
        }

        return back()->with('success', "{$count} users {$action}d successfully.");
    }
}
