<?php
// app/Http/Controllers/Admin/ProfileController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ProfileController extends Controller implements HasMiddleware
{
    /**
     * Define middleware for this controller.
     */
    public static function middleware(): array
    {
        return [
            'auth:admin',

            // Profile permissions
            new Middleware('permission:edit own profile', only: ['edit', 'update']),
            new Middleware('permission:change own password', only: ['changePassword']),
            new Middleware('permission:upload avatar', only: ['uploadAvatar']),
        ];
    }

    /**
     * Show profile edit form.
     */
    public function edit()
    {
        $user = auth('admin')->user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Update profile information.
     */
    public function update(Request $request)
    {
        $user = auth('admin')->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
        ]);

        $user->update($request->only('name', 'email', 'phone'));

        return back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Upload avatar image.
     */
    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = auth('admin')->user();

        // Delete old avatar if exists
        if ($user->avatar) {
            Storage::disk('public')->delete('avatars/' . $user->avatar);
        }

        // Upload new avatar
        $avatarPath = $request->file('avatar')->store('avatars', 'public');
        $user->update(['avatar' => basename($avatarPath)]);

        return back()->with('success', 'Avatar uploaded successfully.');
    }

    /**
     * Change password.
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password:admin',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = auth('admin')->user();
        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with('success', 'Password changed successfully.');
    }

    /**
     * Show activity log (optional - requires permission)
     */
    public function activityLog()
    {
        if (!auth('admin')->user()->can('view activity log')) {
            abort(403);
        }

        $user = auth('admin')->user();
        $activities = $user->activities()->paginate(20); // If you have activity log

        return view('profile.activity', compact('activities'));
    }

    /**
     * Delete avatar.
     */
    public function deleteAvatar()
    {
        $user = auth('admin')->user();

        if ($user->avatar) {
            Storage::disk('public')->delete('avatars/' . $user->avatar);
            $user->update(['avatar' => null]);
        }

        return back()->with('success', 'Avatar removed successfully.');
    }

    /**
     * Two-factor authentication setup (optional)
     */
    public function twoFactor()
    {
        if (!auth('admin')->user()->can('manage 2fa')) {
            abort(403);
        }

        return view('profile.two-factor');
    }

    /**
     * Enable two-factor authentication.
     */
    public function enableTwoFactor(Request $request)
    {
        // Implement 2FA logic here
        return back()->with('success', 'Two-factor authentication enabled.');
    }

    /**
     * Disable two-factor authentication.
     */
    public function disableTwoFactor(Request $request)
    {
        // Implement 2FA disable logic here
        return back()->with('success', 'Two-factor authentication disabled.');
    }
}
