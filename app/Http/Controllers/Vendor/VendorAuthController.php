<?php
// app/Http/Controllers/Vendor/VendorAuthController.php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorTaxInfo;
use App\Models\VendorBankInfo;
use App\Models\VendorDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use App\Traits\LogsVendorActivity;


class VendorAuthController extends Controller
{

    use LogsVendorActivity;
    /**
     * Show vendor registration form
     */
    public function showRegistrationForm()
    {
        // If already logged in as vendor, redirect to dashboard
        if (Auth::guard('vendor')->check()) {
            return redirect()->route('marketplace.dashboard');
        }

        return view('marketplace.auth.register');
    }

    public function showLoginForm()
    {
        if (Auth::guard('vendor')->check()) {
            return redirect()->route('marketplace.dashboard');
        }

        return view('marketplace.auth.login');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:vendors,email',
            'phone' => 'required|string|max:20',
            'password' => 'required|min:8|confirmed',
            'shop_name' => 'required|string|max:255|unique:vendors,shop_name',
            'shop_slug' => 'nullable|string|unique:vendors,shop_slug',
            'shop_description' => 'required|string',
            'shop_email' => 'required|email|unique:vendors,shop_email',
            'shop_phone' => 'required|string|max:20',
            'business_type' => 'required|string',
            'shop_address' => 'required|string',
            'shop_city' => 'required|string',
            'shop_state' => 'required|string',
            'shop_country' => 'required|string',
            'shop_postal_code' => 'required|string',
            'terms' => 'accepted',
        ]);

        DB::beginTransaction();

        // try {
        $vendor = Vendor::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'],
            'shop_name' => $validated['shop_name'],
            'shop_slug' => $validated['shop_slug'] ?? Str::slug($validated['shop_name']),
            'shop_description' => $validated['shop_description'],
            'shop_email' => $validated['shop_email'],
            'shop_phone' => $validated['shop_phone'],
            'business_type' => $validated['business_type'],
            'shop_address' => $validated['shop_address'],
            'shop_city' => $validated['shop_city'],
            'shop_state' => $validated['shop_state'],
            'shop_country' => $validated['shop_country'],
            'shop_postal_code' => $validated['shop_postal_code'],
            'vendor_type' => 'third_party',
            'account_status' => 'pending',
            'verification_status' => 'pending',
            'commission_rate' => 10,
            'last_login_at' => now()
        ]);

        DB::commit();

        // Assign vendor role to the vendor
        $vendor->assignRole('vendor');

        // Auto login after registration
        Auth::guard('vendor')->login($vendor);

        // Redirect to login page with success message
        return redirect()->route('vendor.dashboard')
            ->with('success', 'Registration successful! Please login after admin approval.');
        // } catch (\Exception $e) {
        //     DB::rollBack();
        //     return back()->with('error', 'Registration failed: ' . $e->getMessage())->withInput();
        // }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if (Auth::guard('vendor')->attempt($credentials, $request->remember)) {
            $vendor = Auth::guard('vendor')->user();

            // Log login activity
            // $vendor->logLogin('Logged into vendor dashboard');

            // Check if suspended
            if ($vendor->account_status === 'suspended') {
                Auth::guard('vendor')->logout();
                return back()->withErrors(['email' => 'Your account has been suspended. Contact support.']);
            }

            // Update last login
            $vendor->update(['last_login_at' => now()]);

            // Redirect based on profile completion and verification
            return $this->redirectBasedOnStatus($vendor);
        }

        return back()->withErrors(['email' => 'Invalid credentials.'])->onlyInput('email');
    }

    protected function redirectBasedOnStatus($vendor)
    {
        // Case 1: Profile not completed
        if ($vendor->profile_completed < 100) {
            return redirect()->route('vendor.complete-profile');
        }

        // Case 2: Pending verification
        if ($vendor->verification_status === 'pending') {
            return redirect()->route('vendor.pending');
        }

        // Case 3: Rejected
        if ($vendor->verification_status === 'rejected') {
            Auth::guard('vendor')->logout();
            return back()->withErrors(['email' => 'Your application was rejected: ' . $vendor->verification_notes]);
        }

        // Case 4: Verified and active
        if ($vendor->verification_status === 'verified' && $vendor->account_status === 'active') {
            return redirect()->route('vendor.dashboard');
        }

        return redirect()->route('vendor.pending');
    }

    public function logout(Request $request)
    {
        $vendor = Auth::guard('vendor')->user();

        if ($vendor) {
            $vendor->logLogout('Logged out from vendor panel');
        }

        Auth::guard('vendor')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('vendor.login');
    }
}
