<?php
// app/Http/Controllers/Admin/ProfileController.php

namespace App\Http\ControllersAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = auth()->user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
        ]);

        $user->update($request->only('name', 'email', 'phone'));

        return back()->with('success', 'Profile updated successfully.');
    }

    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = auth()->user();

        // Delete old avatar
        if ($user->avatar) {
            Storage::disk('public')->delete('avatars/' . $user->avatar);
        }

        // Upload new avatar
        $avatarPath = $request->file('avatar')->store('avatars', 'public');
        $user->update(['avatar' => basename($avatarPath)]);

        return back()->with('success', 'Avatar uploaded successfully.');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'new_password' => 'required|min:8|confirmed',
        ]);

        auth()->user()->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with('success', 'Password changed successfully.');
    }
}
