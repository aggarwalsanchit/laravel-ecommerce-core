<?php
// app/Http/Controllers/Admin/UserController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Country;
use App\Models\State;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\Services\ImageCompressionService;
use App\Traits\LogsAdminActivity;

class UserController extends Controller implements HasMiddleware
{
    use LogsAdminActivity;

    protected $imageCompressor;

    public function __construct(ImageCompressionService $imageCompressor)
    {
        $this->imageCompressor = $imageCompressor;
    }

    /**
     * Define middleware for this controller.
     */
    public static function middleware(): array
    {
        return [
            'auth:admin',
            new Middleware('permission:view_users', only: ['index', 'show']),
            new Middleware('permission:create_users', only: ['create', 'store']),
            new Middleware('permission:edit_users', only: ['edit', 'update']),
            new Middleware('permission:delete_users', only: ['destroy']),
            new Middleware('permission:activate_users', only: ['activate']),
            new Middleware('permission:deactivate_users', only: ['deactivate']),
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
        $roles = Role::where('guard_name', 'admin')->get();

        // Statistics
        $stats = [
            'total' => Admin::count(),
            'active' => Admin::where('is_active', true)->count(),
            'inactive' => Admin::where('is_active', false)->count(),
            'roles' => Role::where('guard_name', 'admin')->count(),
        ];

        // Filtered stats
        $filteredStats = [
            'filtered_total' => $query->count(),
            'filtered_active' => (clone $query)->where('is_active', true)->count(),
            'filtered_inactive' => (clone $query)->where('is_active', false)->count(),
        ];

        // Log activity
        $this->logActivity('view', 'users', 'admin', null, null, null, null, 'Viewed users list');

        if ($request->ajax()) {
            $table = view('admin.pages.users.partials.users-table', compact('users'))->render();
            $pagination = $users->links('pagination::bootstrap-5')->render();

            return response()->json([
                'table' => $table,
                'pagination' => $pagination,
                'stats' => $stats,
                'filteredStats' => $filteredStats
            ]);
        }

        return view('admin.pages.users.index', compact('users', 'roles', 'stats', 'filteredStats'));
    }

    /**
     * Show form for creating new user.
     */
    public function create()
    {
        $roles = Role::where('guard_name', 'admin')->get();
        $permissions = Permission::where('guard_name', 'admin')->get();
        $countries = Country::all();

        return view('admin.pages.users.create', compact('roles', 'permissions', 'countries'));
    }

    /**
     * Store newly created user.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|min:8|confirmed',
            'phone_code' => 'nullable|string|max:10',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'country_id' => 'nullable|exists:countries,id',
            'state_id' => 'nullable|exists:states,id',
            'postal_code' => 'nullable|string|max:20',
            'birth_date' => 'nullable|date|before:today',
            'is_active' => 'boolean',
            'role' => 'required|exists:roles,name',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->except(['password', 'role', 'avatar']);
        $data['password'] = Hash::make($request->password);
        $data['is_active'] = $request->is_active ?? true;

        // Handle avatar upload with compression
        if ($request->hasFile('avatar')) {
            $compressed = $this->imageCompressor->compress(
                $request->file('avatar'),
                'avatars',
                200,
                85
            );

            if ($compressed['success']) {
                $data['avatar'] = $compressed['filename'];
            } else {
                $avatarPath = $request->file('avatar')->store('avatars', 'public');
                $data['avatar'] = basename($avatarPath);
            }
        }

        $user = Admin::create($data);
        $user->assignRole($request->role);

        // Log activity
        $this->logActivity(
            'create',
            'user',
            'admin',
            $user->id,
            $user->name,
            null,
            $user->toArray(),
            "Created new user with role: {$request->role}"
        );

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
        $user->load('roles', 'permissions');

        // Log activity
        $this->logActivity(
            'view',
            'user',
            'admin',
            $user->id,
            $user->name,
            null,
            null,
            "Viewed user details: {$user->name}"
        );

        return view('admin.pages.users.show', compact('user'));
    }

    /**
     * Show form for editing user.
     */
    public function edit($id)
    {
        $user = Admin::findOrFail($id);
        $roles = Role::where('guard_name', 'admin')->get();
        $userRole = $user->roles->first()->name ?? '';
        $countries = Country::all();
        $states = State::where('country_id', $user->country_id)->get();

        // Log activity
        $this->logActivity(
            'edit',
            'user',
            'admin',
            $user->id,
            $user->name,
            null,
            null,
            "Opened edit form for user: {$user->name}"
        );

        return view('admin.pages.users.edit', compact('user', 'roles', 'userRole', 'countries', 'states'));
    }

    /**
     * Update user.
     */
    public function update(Request $request, Admin $user)
    {
        // Store old values for logging
        $oldValues = $user->toArray();
        $oldRole = $user->roles->first()->name ?? 'none';

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email,' . $user->id,
            'password' => 'nullable|min:8|confirmed',
            'phone_code' => 'nullable|string|max:10',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'country_id' => 'nullable|exists:countries,id',
            'state_id' => 'nullable|exists:states,id',
            'postal_code' => 'nullable|string|max:20',
            'birth_date' => 'nullable|date|before:today',
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

        // Handle avatar removal
        if ($request->has('remove_avatar') && $request->remove_avatar) {
            if ($user->avatar && Storage::disk('public')->exists('avatars/' . $user->avatar)) {
                Storage::disk('public')->delete('avatars/' . $user->avatar);
                $data['avatar'] = null;
            }
        }

        // Handle avatar upload with compression
        if ($request->hasFile('avatar')) {
            // Delete old avatar
            if ($user->avatar && Storage::disk('public')->exists('avatars/' . $user->avatar)) {
                Storage::disk('public')->delete('avatars/' . $user->avatar);
            }

            // Compress and upload avatar
            $compressed = $this->imageCompressor->compress(
                $request->file('avatar'),
                'avatars',
                200,
                85
            );

            if ($compressed['success']) {
                $data['avatar'] = $compressed['filename'];
            } else {
                $avatarPath = $request->file('avatar')->store('avatars', 'public');
                $data['avatar'] = basename($avatarPath);
            }
        }

        $user->update($data);

        // Sync role (remove old roles and assign new one)
        $newRole = $request->role;
        $user->syncRoles([$newRole]);

        // Log activity with changes
        $changes = [];
        foreach ($oldValues as $key => $value) {
            if (isset($data[$key]) && $oldValues[$key] != $data[$key]) {
                $changes[$key] = [
                    'old' => $oldValues[$key],
                    'new' => $data[$key]
                ];
            }
        }

        if ($oldRole !== $newRole) {
            $changes['role'] = [
                'old' => $oldRole,
                'new' => $newRole
            ];
        }

        $description = "Updated user: {$user->name}";
        if (!empty($changes)) {
            $fields = array_keys($changes);
            $description .= " - Changed: " . implode(', ', $fields);
        }

        $this->logActivity(
            'update',
            'user',
            'admin',
            $user->id,
            $user->name,
            $oldValues,
            $user->toArray(),
            $description
        );

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully with ' . $newRole . ' role.'
        ]);
    }

    /**
     * Delete user.
     */
    public function destroy(Admin $user)
    {
        // Check permission
        if (!auth('admin')->user()->can('delete_users')) {
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'You do not have permission to delete users.'], 403);
            }
            return back()->with('error', 'You do not have permission to delete users.');
        }

        // Prevent deleting yourself
        if ($user->id === auth('admin')->id()) {
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'You cannot delete your own account.'], 403);
            }
            return back()->with('error', 'You cannot delete your own account.');
        }

        // Prevent deleting Super Admin users
        if ($user->hasRole('Super Admin')) {
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'Super Admin users cannot be deleted.'], 403);
            }
            return back()->with('error', 'Super Admin users cannot be deleted.');
        }

        $userName = $user->name;
        $userId = $user->id;

        // Delete avatar if exists
        if ($user->avatar && Storage::disk('public')->exists('avatars/' . $user->avatar)) {
            Storage::disk('public')->delete('avatars/' . $user->avatar);
        }

        // Delete user
        $user->delete();

        // Log activity
        $this->logActivity(
            'delete',
            'user',
            'admin',
            $userId,
            $userName,
            null,
            null,
            "Deleted user: {$userName}"
        );

        // Return response based on request type
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully.'
            ]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    /**
     * Activate user.
     */
    public function activate(Admin $user)
    {
        // Check permission
        if (!auth('admin')->user()->can('activate_users')) {
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'You do not have permission to activate users.'], 403);
            }
            return back()->with('error', 'You do not have permission to activate users.');
        }

        // Store old values for logging
        $oldStatus = $user->is_active;

        // Activate user
        $user->update(['is_active' => true]);

        // Log activity
        $this->logActivity(
            'activate',
            'user',
            'admin',
            $user->id,
            $user->name,
            ['is_active' => $oldStatus],
            ['is_active' => true],
            "Activated user: {$user->name}"
        );

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
        if (!auth('admin')->user()->can('deactivate_users')) {
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'You do not have permission to deactivate users.'], 403);
            }
            return back()->with('error', 'You do not have permission to deactivate users.');
        }

        // Prevent deactivating yourself
        if ($user->id === auth('admin')->id()) {
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

        // Store old values for logging
        $oldStatus = $user->is_active;

        // Deactivate user
        $user->update(['is_active' => false]);

        // Log activity
        $this->logActivity(
            'deactivate',
            'user',
            'admin',
            $user->id,
            $user->name,
            ['is_active' => $oldStatus],
            ['is_active' => false],
            "Deactivated user: {$user->name}"
        );

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
            'user_ids' => 'required|string',
        ]);

        $action = $request->action;
        $userIds = json_decode($request->user_ids);

        // Check permission based on action
        if ($action === 'activate' && !auth('admin')->user()->can('activate_users')) {
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'You do not have permission to activate users.'], 403);
            }
            return back()->with('error', 'You do not have permission to activate users.');
        }

        if ($action === 'deactivate' && !auth('admin')->user()->can('deactivate_users')) {
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'You do not have permission to deactivate users.'], 403);
            }
            return back()->with('error', 'You do not have permission to deactivate users.');
        }

        if ($action === 'delete' && !auth('admin')->user()->can('delete_users')) {
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'You do not have permission to delete users.'], 403);
            }
            return back()->with('error', 'You do not have permission to delete users.');
        }

        // Get users, excluding current admin and Super Admin
        $users = Admin::whereIn('id', $userIds)
            ->where('id', '!=', auth('admin')->id())
            ->get();

        $count = 0;
        $affectedUsers = [];

        foreach ($users as $user) {
            // Skip Super Admin users for deactivate and delete actions
            if ($user->hasRole('Super Admin') && ($action === 'deactivate' || $action === 'delete')) {
                continue;
            }

            $affectedUsers[] = $user->name;

            if ($action === 'activate') {
                $user->update(['is_active' => true]);
                $count++;
            } elseif ($action === 'deactivate') {
                $user->update(['is_active' => false]);
                $count++;
            } elseif ($action === 'delete') {
                if ($user->avatar && Storage::disk('public')->exists('avatars/' . $user->avatar)) {
                    Storage::disk('public')->delete('avatars/' . $user->avatar);
                }
                $user->delete();
                $count++;
            }
        }

        // Log bulk action
        $this->logActivity(
            $action,
            'users_bulk',
            'admin',
            null,
            null,
            null,
            null,
            "Bulk {$action} on users: " . implode(', ', $affectedUsers)
        );

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
