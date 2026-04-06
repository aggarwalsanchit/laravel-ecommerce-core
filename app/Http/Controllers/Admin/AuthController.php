<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\LogsAdminActivity;

class AuthController extends Controller
{
    use LogsAdminActivity;
    /**
     * Show the login form
     */
    public function showLogin()
    {
        return view('admin.auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        // Validate the request
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Get credentials and remember me value
        $credentials = $request->only('email', 'password');
        $remember = $request->filled('remember');

        // Attempt login
        if (Auth::guard('admin')->attempt($credentials, $remember)) {

            $admin = Auth::guard('admin')->user();
            
            // Log login activity
            $admin->logLogin('Admin logged into dashboard');

            // Regenerate session to prevent session fixation
            $request->session()->regenerate();
            
            return redirect()->intended(route('admin.dashboard'));
        }

        // Login failed
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin) {
            $admin->logLogout('Admin logged out');
        }
        
        Auth::guard('admin')->logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('admin.login');
    }
}