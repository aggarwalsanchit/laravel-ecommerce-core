<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\State;
use App\Services\ImageCompressionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\Traits\LogsVendorActivity;

class VendorProfileController extends Controller implements HasMiddleware
{
    use LogsVendorActivity;
    protected $imageCompressor;

    public function __construct(ImageCompressionService $imageCompressor)
    {
        $this->imageCompressor = $imageCompressor;
    }

    public static function middleware(): array
    {
        return [
            'auth:vendor',
            new Middleware('permission:view_profile|vendor', only: ['index']),
            new Middleware('permission:update_profile|vendor', only: ['edit', 'update']),
            new Middleware('permission:change_password|vendor', only: ['changePassword', 'updatePassword']),
        ];
    }

    /**
     * Show vendor profile
     */
    public function index()
    {
        $vendor = Auth::guard('vendor')->user();
        $vendor->load(['country', 'state']);

        // Log view activity (optional - can be commented if too many logs)
        $this->logActivity('view', 'profile', $vendor->id, $vendor->name, null, null, 'Viewed own profile');

        return view('marketplace.pages.profile.index', compact('vendor'));
    }

    /**
     * Show edit profile form
     */
    public function edit()
    {
        $vendor = Auth::guard('vendor')->user();
        $vendor->load(['country', 'state']);

        $countries = Country::orderBy('name')->get();

        $states = collect();

        if ($vendor->country_id) {
            $states = State::where('country_id', $vendor->country_id)->orderBy('name')->get();
        }

        // Format birth_date for display
        $formattedBirthDate = $vendor->birth_date ? \Carbon\Carbon::parse($vendor->birth_date)->format('Y-m-d') : '';

        // Log edit access
        $this->logActivity('edit', 'profile', $vendor->id, $vendor->name, null, null, 'Opened profile edit form');

        return view('marketplace.pages.profile.edit', compact('vendor', 'countries', 'states', 'formattedBirthDate'));
    }

    /**
     * Update profile
     */
    public function update(Request $request)
    {
        $vendor = Auth::guard('vendor')->user();

        // Store old values for logging
        $oldValues = $vendor->toArray();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:vendors,email,' . $vendor->id,
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
            'email' => $request->email,
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

            if ($vendor->avatar && Storage::disk('public')->exists($vendor->avatar)) {
                Storage::disk('public')->delete($vendor->avatar);
            }

            // Compress and upload avatar
            $compressed = $this->imageCompressor->compress(
                $request->file('avatar'),
                'vendor/avatars',
                200,  // width
                85    // quality
            );

            if ($compressed['success']) {
                $data['avatar'] = 'vendor/avatars/' . $compressed['filename'];
            } else {
                // Fallback to normal upload if compression fails
                $avatarPath = $request->file('avatar')->store('vendor/avatars', 'public');
                $data['avatar'] = $avatarPath;
            }
        }

        $vendor->update($data);

        // Reload vendor to get new values
        $vendor->refresh();

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

        $this->logActivity(
            'update',
            'profile',
            $vendor->id,
            $vendor->name,
            $oldValues,
            $vendor->toArray(),
            $description
        );

        // Check if request is AJAX
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully!',
                'redirect_url' => route('vendor.profile.index')
            ]);
        }

        // For non-AJAX requests
        return redirect()->route('vendor.profile.index')
            ->with('success', 'Profile updated successfully!');
    }

    /**
     * Show change password form
     */
    public function changePassword()
    {
        $vendor = Auth::guard('vendor')->user();

        // Log password form access
        $this->logActivity('edit', 'password', $vendor->id, $vendor->name, null, null, 'Opened change password form');

        return view('marketplace.pages.profile.change-password');
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $vendor = Auth::guard('vendor')->user();

        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, $vendor->password)) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Current password is incorrect'
                ], 401);
            }
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        $vendor->update([
            'password' => Hash::make($request->password)
        ]);

        // Log activity
        $this->logActivity(
            'change_password',
            'profile',
            $vendor->id,
            $vendor->name,
            null,
            null,
            'Changed account password'
        );

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Password changed successfully!',
                'redirect_url' => route('vendor.profile.index')
            ]);
        }

        return redirect()->route('vendor.profile.index')->with('success', 'Password changed successfully!');
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
