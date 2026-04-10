<?php
// app/Http/Controllers/Vendor/VendorUserController.php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\Country;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\Services\ImageCompressionService;
use App\Traits\LogsVendorActivity;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class VendorUserController extends Controller implements HasMiddleware
{
    use LogsVendorActivity;

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
            'auth:vendor',
            new Middleware('permission:view_staff', only: ['index', 'show']),
            new Middleware('permission:create_staff', only: ['create', 'store']),
            new Middleware('permission:edit_staff', only: ['edit', 'update']),
            new Middleware('permission:delete_staff', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of staff members.
     */
    public function index(Request $request)
    {
        $vendor = Auth::guard('vendor')->user();

        $query = Vendor::where('shop_id', $vendor->shop_id);

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
            $query->where('role', $request->role);
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $staffs = $query->paginate(10);
        $roles = Role::where('guard_name', 'vendor')->get();

        // Statistics
        $stats = [
            'total' => Vendor::where('shop_id', $vendor->shop_id)->count(),
            'active' => Vendor::where('shop_id', $vendor->shop_id)->where('is_active', true)->count(),
            'inactive' => Vendor::where('shop_id', $vendor->shop_id)->where('is_active', false)->count(),
            'roles' => Role::where('guard_name', 'vendor')->count(),
        ];

        // Filtered stats
        $filteredStats = [
            'filtered_total' => $query->count(),
            'filtered_active' => (clone $query)->where('is_active', true)->count(),
            'filtered_inactive' => (clone $query)->where('is_active', false)->count(),
        ];

        // Log activity
        $this->logActivity('view', 'staff', null, null, null, null, null, 'Viewed staff list');

        if ($request->ajax()) {
            $table = view('marketplace.pages.staff.partials.staff-table', compact('staffs'))->render();
            $pagination = $staffs->links('pagination::bootstrap-5')->render();

            return response()->json([
                'table' => $table,
                'pagination' => $pagination,
                'stats' => $stats,
                'filteredStats' => $filteredStats
            ]);
        }

        return view('marketplace.pages.staff.index', compact('staffs', 'stats', 'roles', 'filteredStats'));
    }

    /**
     * Show form for creating new staff.
     */
    public function create()
    {
        $roles = Role::where('guard_name', 'vendor')->get();
        $permissions = Permission::where('guard_name', 'vendor')->get();
        $countries = Country::all();

        return view('marketplace.pages.staff.create', compact('roles', 'permissions', 'countries'));
    }

    /**
     * Store newly created staff.
     */
    public function store(Request $request)
    {
        $vendor = Auth::guard('vendor')->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:vendors,email',
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
            'role' => 'required',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->except(['password', 'role', 'avatar']);
        $data['password'] = Hash::make($request->password);
        $data['is_active'] = $request->is_active ?? true;
        $data['shop_id'] = $vendor->shop_id;
        $data['is_owner'] = false;
        $data['vendor_role'] =  $request->role;

        // Handle avatar upload with compression
        if ($request->hasFile('avatar')) {
            $compressed = $this->imageCompressor->compress(
                $request->file('avatar'),
                'vendor/avatars',
                200,
                85
            );

            if ($compressed['success']) {
                $data['avatar'] = 'vendor/avatars/' . $compressed['filename'];
            } else {
                $avatarPath = $request->file('avatar')->store('vendor/avatars', 'public');
                $data['avatar'] = $avatarPath;
            }
        }

        $staff = Vendor::create($data);
        $staff->assignRole($request->role);

        // Log activity
        $this->logActivity(
            'create',
            'staff',
            $staff->id,
            $staff->name,
            null,
            $staff->toArray(),
            "Created new staff member: {$staff->name} with role: {$request->role}"
        );

        return response()->json([
            'success' => true,
            'message' => 'Staff member created successfully!'
        ]);
    }

    /**
     * Display staff details.
     */
    public function show($id)
    {
        $vendor = Auth::guard('vendor')->user();

        $staff = Vendor::where('shop_id', $vendor->shop_id)
            ->with(['roles', 'permissions', 'shop', 'state', 'country'])
            ->firstOrFail();

        // Log activity
        $this->logActivity(
            'view',
            'staff',
            $staff->id,
            $staff->name,
            null,
            null,
            "Viewed staff details: {$staff->name}"
        );

        return view('marketplace.pages.staff.show', compact('staff'));
    }

    /**
     * Show form for editing staff.
     */
    public function edit($id)
    {
        $vendor = Auth::guard('vendor')->user();

        $staff = Vendor::where('shop_id', $vendor->shop_id)
            ->where('id', $id)
            ->firstOrFail();

        $countries = Country::all();
        $states = State::where('country_id', $staff->country_id)->get();
        $roles = Role::where('guard_name', 'vendor')->get();
        $staffRole = $staff->roles->first()->name ?? '';

        // Log activity
        $this->logActivity(
            'edit',
            'staff',
            $staff->id,
            $staff->name,
            null,
            null,
            "Opened edit form for staff: {$staff->name}"
        );

        return view('marketplace.pages.staff.edit', compact('staff', 'roles', 'staffRole', 'countries', 'states'));
    }

    /**
     * Update staff.
     */
    public function update(Request $request, $id)
    {
        $vendor = Auth::guard('vendor')->user();

        $staff = Vendor::where('shop_id', $vendor->shop_id)
            ->where('id', $id)
            ->firstOrFail();

        // Store old values for logging
        $oldValues = $staff->toArray();

        $request->validate([
            'name' => 'required|string|max:255',
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
            'role' => 'required',
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
            if ($staff->avatar && Storage::disk('public')->exists($staff->avatar)) {
                Storage::disk('public')->delete($staff->avatar);
                $data['avatar'] = null;
            }
        }

        // Handle avatar upload with compression
        if ($request->hasFile('avatar')) {
            // Delete old avatar
            if ($staff->avatar && Storage::disk('public')->exists($staff->avatar)) {
                Storage::disk('public')->delete($staff->avatar);
            }

            // Compress and upload avatar
            $compressed = $this->imageCompressor->compress(
                $request->file('avatar'),
                'vendor/avatars',
                200,
                85
            );

            if ($compressed['success']) {
                $data['avatar'] = 'vendor/avatars/' . $compressed['filename'];
            } else {
                $avatarPath = $request->file('avatar')->store('vendor/avatars', 'public');
                $data['avatar'] = $avatarPath;
            }
        }

        $staff->update($data);

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

        $description = "Updated staff member: {$staff->name}";
        if (!empty($changes)) {
            $fields = array_keys($changes);
            $description .= " - Changed: " . implode(', ', $fields);
        }

        $this->logActivity(
            'update',
            'staff',
            $staff->id,
            $staff->name,
            $oldValues,
            $staff->toArray(),
            $description
        );

        return response()->json([
            'success' => true,
            'message' => 'Staff member updated successfully!'
        ]);
    }

    /**
     * Activate staff.
     */
    public function activate($id)
    {
        $vendor = Auth::guard('vendor')->user();

        $staff = Vendor::where('shop_id', $vendor->shop_id)
            ->where('id', $id)
            ->firstOrFail();

        $oldStatus = $staff->is_active;
        $staff->update(['is_active' => true]);

        $this->logActivity(
            'activate',
            'staff',
            $staff->id,
            $staff->name,
            ['is_active' => $oldStatus],
            ['is_active' => true],
            "Activated staff member: {$staff->name}"
        );

        return response()->json([
            'success' => true,
            'message' => 'Staff member activated successfully!'
        ]);
    }

    /**
     * Deactivate staff.
     */
    public function deactivate($id)
    {
        $vendor = Auth::guard('vendor')->user();

        $staff = Vendor::where('shop_id', $vendor->shop_id)
            ->where('id', $id)
            ->firstOrFail();

        $oldStatus = $staff->is_active;
        $staff->update(['is_active' => false]);

        $this->logActivity(
            'deactivate',
            'staff',
            $staff->id,
            $staff->name,
            ['is_active' => $oldStatus],
            ['is_active' => false],
            "Deactivated staff member: {$staff->name}"
        );

        return response()->json([
            'success' => true,
            'message' => 'Staff member deactivated successfully!'
        ]);
    }

    /**
     * Delete staff.
     */
    public function destroy($id)
    {
        $vendor = Auth::guard('vendor')->user();

        $staff = Vendor::where('shop_id', $vendor->shop_id)
            ->where('id', $id)
            ->firstOrFail();

        $staffName = $staff->name;
        $staffId = $staff->id;

        // Delete avatar if exists
        if ($staff->avatar && Storage::disk('public')->exists($staff->avatar)) {
            Storage::disk('public')->delete($staff->avatar);
        }

        $staff->delete();

        // Log activity
        $this->logActivity(
            'delete',
            'staff',
            $staffId,
            $staffName,
            null,
            null,
            "Deleted staff member: {$staffName}"
        );

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Staff member deleted successfully!'
            ]);
        }

        return redirect()->route('vendor.staff.index')
            ->with('success', 'Staff member deleted successfully.');
    }

    /**
     * Bulk action on staff.
     */
    public function bulkAction(Request $request)
    {
        $vendor = Auth::guard('vendor')->user();

        $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'staff_ids' => 'required|string',
        ]);

        $action = $request->action;
        $staffIds = json_decode($request->staff_ids);

        // Check permission based on action
        if ($action === 'activate' && !$vendor->can('edit_staff')) {
            return response()->json(['success' => false, 'message' => 'You do not have permission to activate staff.'], 403);
        }

        if ($action === 'deactivate' && !$vendor->can('edit_staff')) {
            return response()->json(['success' => false, 'message' => 'You do not have permission to deactivate staff.'], 403);
        }

        if ($action === 'delete' && !$vendor->can('delete_staff')) {
            return response()->json(['success' => false, 'message' => 'You do not have permission to delete staff.'], 403);
        }

        // Get staff members
        $staffs = Vendor::where('shop_id', $vendor->shop_id)
            ->whereIn('id', $staffIds)
            ->get();

        $count = 0;
        $affectedStaff = [];

        foreach ($staffs as $staff) {
            $affectedStaff[] = $staff->name;

            if ($action === 'activate') {
                $staff->update(['is_active' => true]);
                $count++;
            } elseif ($action === 'deactivate') {
                $staff->update(['is_active' => false]);
                $count++;
            } elseif ($action === 'delete') {
                if ($staff->avatar && Storage::disk('public')->exists($staff->avatar)) {
                    Storage::disk('public')->delete($staff->avatar);
                }
                $staff->delete();
                $count++;
            }
        }

        // Log bulk action
        $this->logActivity(
            $action,
            'staff_bulk',
            null,
            null,
            null,
            null,
            "Bulk {$action} on staff: " . implode(', ', $affectedStaff)
        );

        return response()->json([
            'success' => true,
            'message' => "{$count} staff member(s) {$action}d successfully."
        ]);
    }
}
