<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\State;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\Services\ImageCompressionService;
use App\Traits\LogsAdminActivity;

class ProfileController extends Controller implements HasMiddleware
{
    use LogsAdminActivity;

    protected $imageCompressor;

    public function __construct(ImageCompressionService $imageCompressor)
    {
        $this->imageCompressor = $imageCompressor;
    }

    public static function middleware(): array
    {
        return [
            'auth:admin',
            new Middleware('permission:view_profile|admin', only: ['index']),
            new Middleware('permission:edit_profile|admin', only: ['edit', 'update']),
            new Middleware('permission:change_password|admin', only: ['changePassword', 'updatePassword']),
        ];
    }

    /**
     * Show admin profile
     */
    public function index()
    {
        $admin = Auth::guard('admin')->user();
        $admin->load(['country', 'state']);

        // Log view activity (optional - can be commented if too many logs)
        // $this->logActivity('view', 'profile', 'admin', $admin->id, $admin->name, null, null, 'Viewed own profile');

        return view('admin.pages.profile.index', compact('admin'));
    }

    /**
     * Show edit profile form
     */
    public function edit()
    {
        $admin = Auth::guard('admin')->user();
        $admin->load(['country', 'state']);

        $countries = Country::orderBy('name')->get();

        $states = collect();
        $cities = collect();

        if ($admin->country_id) {
            $states = State::where('country_id', $admin->country_id)->orderBy('name')->get();
        }

        // Format birth_date for display
        $formattedBirthDate = $admin->birth_date ? \Carbon\Carbon::parse($admin->birth_date)->format('Y-m-d') : '';

        // Log edit access
        $this->logActivity(
            'edit',           // action
            'profile',        // module
            'admin',          // entityType (string)
            $admin->id,       // entityId (integer)
            $admin->name,     // entityName (string)
            null,             // oldValues
            null,             // newValues
            'Opened profile edit form'  // description
        );

        return view('admin.pages.profile.edit', compact('admin', 'countries', 'states', 'formattedBirthDate'));
    }

    /**
     * Update profile
     */
    public function update(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        // Store old values for logging
        $oldValues = $admin->toArray();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone_code' => 'nullable|string|max:10',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'country_id' => 'nullable|exists:countries,id',
            'state_id' => 'nullable|exists:states,id',
            'city' => 'nullable',
            'postal_code' => 'nullable|string|max:20',
            'birth_date' => 'nullable|date|before:today',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = [
            'name' => $request->name,
            'phone_code' => $request->phone_code,
            'phone' => $request->phone,
            'address' => $request->address,
            'country_id' => $request->country_id,
            'state_id' => $request->state_id,
            'city' => $request->city,
            'postal_code' => $request->postal_code,
            'birth_date' => $request->birth_date,
        ];

        $avatarChanged = false;

        // Upload avatar
        if ($request->hasFile('avatar')) {
            $avatarChanged = true;

            if ($admin->avatar && Storage::disk('public')->exists($admin->avatar)) {
                Storage::disk('public')->delete($admin->avatar);
            }

            // Compress and upload avatar
            $compressed = $this->imageCompressor->compress(
                $request->file('avatar'),
                'admin/avatars',
                200,  // width
                85    // quality
            );

            if ($compressed['success']) {
                $data['avatar'] = 'admin/avatars/' . $compressed['filename'];
            } else {
                // Fallback to normal upload if compression fails
                $avatarPath = $request->file('avatar')->store('admin/avatars', 'public');
                $data['avatar'] = $avatarPath;
            }
        }

        $admin->update($data);

        // Reload admin to get new values
        $admin->refresh();

        // Log profile update
        $changes = [];
        foreach ($oldValues as $key => $value) {
            if (isset($data[$key]) && $oldValues[$key] != $data[$key]) {
                $changes[$key] = [
                    'old' => $oldValues[$key],
                    'new' => $data[$key]
                ];
            }
        }

        if ($avatarChanged) {
            $changes['avatar'] = ['old' => $oldValues['avatar'] ?? 'none', 'new' => $data['avatar'] ?? 'none'];
        }

        $description = 'Updated profile information';
        if (!empty($changes)) {
            $fields = array_keys($changes);
            $description .= ' - Changed: ' . implode(', ', $fields);
        }

        $this->logUpdate('profile', $admin, $oldValues, $description);

        // Check if request is AJAX
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully!',
                'redirect_url' => route('admin.profile.index')
            ]);
        }

        // For non-AJAX requests
        return redirect()->route('admin.profile.index')
            ->with('success', 'Profile updated successfully!');
    }

    /**
     * Show change password form
     */
    public function changePassword()
    {
        $admin = Auth::guard('admin')->user();

        // Log password form access
        $this->logActivity('edit', 'password', 'admin', $admin->id, $admin->name, null, null, 'Opened change password form');

        return view('admin.pages.profile.change-password');
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, $admin->password)) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Current password is incorrect'
                ], 401);
            }
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        $admin->update([
            'password' => Hash::make($request->password)
        ]);

        // Log activity
        $this->logActivity(
            'change_password',
            'profile',
            'admin',
            $admin->id,
            $admin->name,
            null,
            null,
            'Changed account password'
        );

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Password changed successfully!',
                'redirect_url' => route('admin.profile.index')
            ]);
        }

        return redirect()->route('admin.profile.index')->with('success', 'Password changed successfully!');
    }

    /**
     * Get states by country (AJAX)
     */
    public function getStates($countryId)
    {
        $states = State::where('country_id', $countryId)
            ->orderBy('name')
            ->get(['id', 'name']);

        // Optional: Log AJAX requests (can be commented to reduce log noise)
        // $this->logActivity('ajax', 'location', 'state', null, null, null, null, "Fetched states for country ID: {$countryId}");

        return response()->json($states);
    }

    /**
     * Get country phone code (AJAX)
     */
    public function getPhoneCode($countryId)
    {
        $country = Country::find($countryId);

        return response()->json([
            'phone_code' => $country ? $country->phonecode : null
        ]);
    }
}
