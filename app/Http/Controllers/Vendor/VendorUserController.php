<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\VendorStaff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Middleware\PermissionMiddleware;

class VendorUserController extends Controller
{
    public static function middleware(): array
    {
        return [
            'auth:vendor',
            // new PermissionMiddleware('manage_staff', only: ['index', 'create', 'store', 'edit', 'update', 'destroy', 'updateStatus']),
            // new PermissionMiddleware('view_staff', only: ['index', 'show']),
        ];
    }

    /**
     * Display list of staff users
     */
    public function index(Request $request)
    {
        $vendor = Auth::guard('vendor')->user();

        $query = VendorStaff::where('vendor_id', $vendor->id);

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $staffs = $query->latest()->paginate(20);

        // Get statistics
        $stats = [
            'total' => VendorStaff::where('vendor_id', $vendor->id)->count(),
            'active' => VendorStaff::where('vendor_id', $vendor->id)->where('is_active', true)->count(),
            'inactive' => VendorStaff::where('vendor_id', $vendor->id)->where('is_active', false)->count(),
            'by_role' => VendorStaff::where('vendor_id', $vendor->id)
                ->select('role', DB::raw('count(*) as total'))
                ->groupBy('role')
                ->pluck('total', 'role'),
        ];

        $roles = [
            'admin' => 'Administrator',
            'manager' => 'Manager',
            'inventory' => 'Inventory Manager',
            'fulfillment' => 'Fulfillment Executive',
            'support' => 'Support Staff',
        ];

        return view('marketplace.pages.staff.index', compact('staffs', 'stats', 'roles'));
    }

    /**
     * Show create staff form
     */
    public function create()
    {
        $roles = [
            'admin' => 'Administrator',
            'manager' => 'Store Manager',
            'inventory' => 'Inventory Manager',
            'fulfillment' => 'Fulfillment Executive',
            'support' => 'Customer Support',
        ];

        // Get all vendor permissions for custom permissions
        $allPermissions = Permission::where('guard_name', 'vendor')->get();

        // Group permissions by category
        $permissionGroups = [
            'Dashboard & Profile' => ['view_dashboard', 'view_profile', 'update_profile', 'change_password', 'upload_avatar', 'complete_profile'],
            'Products' => ['view_products', 'create_products', 'edit_products', 'delete_products'],
            'Orders' => ['view_orders', 'update_order_status', 'cancel_orders'],
            'Reports' => ['view_reports', 'view_analytics'],
            'Staff Management' => ['view_staff', 'create_staff', 'edit_staff', 'delete_staff', 'manage_staff'],
            'Store Settings' => ['manage_store_settings', 'manage_payment_settings', 'manage_shipping_settings'],
        ];

        return view('marketplace.pages.staff.create', compact('roles', 'permissionGroups', 'allPermissions'));
    }

    /**
     * Store new staff user
     */
    public function store(Request $request)
    {
        $vendor = Auth::guard('vendor')->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:vendor_staff,email',
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:admin,manager,inventory,fulfillment,support',
            'password' => 'required|min:8|confirmed',
            'custom_permissions' => 'nullable|array',
            'is_active' => 'boolean',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'country' => 'nullable|string',
            'postal_code' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            // Handle avatar upload
            $avatarPath = null;
            if ($request->hasFile('avatar')) {
                $avatarPath = $request->file('avatar')->store('vendor/staff/avatars', 'public');
            }

            // Create staff user
            $staff = VendorStaff::create([
                'vendor_id' => $vendor->id,
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'custom_permissions' => $request->role === 'admin' ? null : ($request->custom_permissions ?? []),
                'is_active' => $request->is_active ?? true,
                'avatar' => $avatarPath,
                'address' => $request->address,
                'city' => $request->city,
                'state' => $request->state,
                'country' => $request->country,
                'postal_code' => $request->postal_code,
            ]);

            // Sync permissions based on role
            $staff->syncPermissionsByRole();

            // Send welcome email
            // Mail::to($staff->email)->send(new StaffWelcomeMail($staff, $request->password));

            DB::commit();

            return redirect()->route('vendor.staff.index')
                ->with('success', 'Staff user created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    /**
     * Show staff user details
     */
    public function show($id)
    {
        $vendor = Auth::guard('vendor')->user();

        $staff = VendorStaff::where('vendor_id', $vendor->id)
            ->with(['vendor'])
            ->findOrFail($id);

        // Get staff activity logs
        $activities = $staff->activities()->latest()->limit(20)->get();

        return view('vendor.staff.show', compact('staff', 'activities'));
    }

    /**
     * Show edit staff form
     */
    public function edit($id)
    {
        $vendor = Auth::guard('vendor')->user();

        $staff = VendorStaff::where('vendor_id', $vendor->id)->findOrFail($id);

        $roles = [
            'admin' => 'Administrator',
            'manager' => 'Store Manager',
            'inventory' => 'Inventory Manager',
            'fulfillment' => 'Fulfillment Executive',
            'support' => 'Customer Support',
        ];

        // Get all vendor permissions
        $allPermissions = Permission::where('guard_name', 'vendor')->get();

        // Group permissions by category
        $permissionGroups = [
            'Dashboard & Profile' => ['view_dashboard', 'view_profile', 'update_profile', 'change_password', 'upload_avatar', 'complete_profile'],
            'Products' => ['view_products', 'create_products', 'edit_products', 'delete_products'],
            'Orders' => ['view_orders', 'update_order_status', 'cancel_orders'],
            'Reports' => ['view_reports', 'view_analytics'],
            'Staff Management' => ['view_staff', 'create_staff', 'edit_staff', 'delete_staff', 'manage_staff'],
            'Store Settings' => ['manage_store_settings', 'manage_payment_settings', 'manage_shipping_settings'],
        ];

        return view('vendor.staff.edit', compact('staff', 'roles', 'permissionGroups', 'allPermissions'));
    }

    /**
     * Update staff user
     */
    public function update(Request $request, $id)
    {
        $vendor = Auth::guard('vendor')->user();

        $staff = VendorStaff::where('vendor_id', $vendor->id)->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:vendor_staff,email,' . $staff->id,
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:admin,manager,inventory,fulfillment,support',
            'password' => 'nullable|min:8|confirmed',
            'custom_permissions' => 'nullable|array',
            'is_active' => 'boolean',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'country' => 'nullable|string',
            'postal_code' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            // Handle avatar upload
            if ($request->hasFile('avatar')) {
                if ($staff->avatar && Storage::disk('public')->exists($staff->avatar)) {
                    Storage::disk('public')->delete($staff->avatar);
                }
                $avatarPath = $request->file('avatar')->store('vendor/staff/avatars', 'public');
                $staff->avatar = $avatarPath;
            }

            // Update staff data
            $staff->name = $request->name;
            $staff->email = $request->email;
            $staff->phone = $request->phone;
            $staff->role = $request->role;
            $staff->custom_permissions = $request->role === 'admin' ? null : ($request->custom_permissions ?? []);
            $staff->is_active = $request->is_active ?? true;
            $staff->address = $request->address;
            $staff->city = $request->city;
            $staff->state = $request->state;
            $staff->country = $request->country;
            $staff->postal_code = $request->postal_code;

            if ($request->filled('password')) {
                $staff->password = Hash::make($request->password);
            }

            $staff->save();

            // Sync permissions based on role
            $staff->syncPermissionsByRole();

            DB::commit();

            return redirect()->route('vendor.staff.index')
                ->with('success', 'Staff user updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    /**
     * Delete staff user
     */
    public function destroy($id)
    {
        $vendor = Auth::guard('vendor')->user();

        $staff = VendorStaff::where('vendor_id', $vendor->id)->findOrFail($id);

        // Don't allow deleting the last admin
        if ($staff->role === 'admin') {
            $adminCount = VendorStaff::where('vendor_id', $vendor->id)
                ->where('role', 'admin')
                ->where('id', '!=', $staff->id)
                ->count();

            if ($adminCount === 0) {
                return back()->with('error', 'Cannot delete the last administrator!');
            }
        }

        DB::beginTransaction();

        try {
            // Delete avatar
            if ($staff->avatar && Storage::disk('public')->exists($staff->avatar)) {
                Storage::disk('public')->delete($staff->avatar);
            }

            $staff->delete();

            DB::commit();

            return redirect()->route('vendor.staff.index')
                ->with('success', 'Staff user deleted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong!');
        }
    }

    /**
     * Update staff status (activate/deactivate)
     */
    public function updateStatus(Request $request, $id)
    {
        $vendor = Auth::guard('vendor')->user();

        $staff = VendorStaff::where('vendor_id', $vendor->id)->findOrFail($id);

        // Don't allow deactivating the last admin
        if ($staff->role === 'admin' && $request->is_active == false) {
            $adminCount = VendorStaff::where('vendor_id', $vendor->id)
                ->where('role', 'admin')
                ->where('is_active', true)
                ->where('id', '!=', $staff->id)
                ->count();

            if ($adminCount === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot deactivate the last administrator!'
                ], 400);
            }
        }

        $staff->is_active = $request->is_active;
        $staff->save();

        return response()->json([
            'success' => true,
            'message' => 'Staff status updated successfully!'
        ]);
    }

    /**
     * Resend invitation email
     */
    public function resendInvitation($id)
    {
        $vendor = Auth::guard('vendor')->user();

        $staff = VendorStaff::where('vendor_id', $vendor->id)->findOrFail($id);

        // Generate reset token
        $token = \Illuminate\Support\Str::random(60);

        // Store token in password_resets table
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $staff->email],
            ['token' => Hash::make($token), 'created_at' => now()]
        );

        // Send invitation email
        // Mail::to($staff->email)->send(new StaffInvitationMail($staff, $token));

        return back()->with('success', 'Invitation sent successfully!');
    }
}
