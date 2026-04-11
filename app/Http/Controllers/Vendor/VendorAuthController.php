<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\Vendor\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Traits\LogsVendorActivity;

class VendorAuthController extends Controller
{
    use LogsVendorActivity;

    public function showLoginForm()
    {
        return view('marketplace.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('vendor')->attempt($credentials, $request->remember)) {
            $vendor = Auth::guard('vendor')->user();

            // Log login activity
            $this->logLogin('Vendor logged into dashboard');

            if ($vendor->account_status === 'suspended' || $vendor->account_status === 'banned') {
                Auth::guard('vendor')->logout();
                return back()->withErrors(['email' => 'Your account has been ' . $vendor->account_status . '. Please contact support.']);
            }

            if ($vendor->account_status === 'pending') {
                Auth::guard('vendor')->logout();
                return back()->withErrors(['email' => 'Your account is pending approval. Please wait for admin approval.']);
            }

            return redirect()->intended(route('vendor.dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showRegisterForm()
    {
        return view('marketplace.auth.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // Personal Information
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:vendors,email',
            'password' => 'required|min:8|confirmed',

            // Shop Information
            'shop_name' => 'required|string|max:255',
            'shop_slug' => 'nullable|string|unique:shops,shop_slug',

            'terms' => 'required|accepted',
        ], [
            'name.required' => 'Please enter your full name',
            'email.required' => 'Please enter your email address',
            'email.unique' => 'This email is already registered',
            'password.required' => 'Please create a password',
            'password.min' => 'Password must be at least 8 characters',
            'password.confirmed' => 'Password confirmation does not match',
            'shop_name.required' => 'Please enter your shop name',
            'terms.required' => 'You must agree to the terms and conditions',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        DB::beginTransaction();

        try {


            // Generate unique slug
            $slug = $validated['shop_slug'] ?? Str::slug($validated['shop_name']);
            $originalSlug = $slug;
            $count = 1;
            while (Shop::where('shop_slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $count++;
            }

            // Create Shop
            $shop = Shop::create([
                'shop_name' => $validated['shop_name'],
                'shop_slug' => $slug,
                'account_status' => 'pending',
                'verification_status' => 'pending',
                'vendor_type' => 'third_party',
                'profile_completed' => '10',
            ]);

            // Create Vendor
            $vendor = Vendor::create([
                'shop_id' => $shop->id,
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'account_status' => 'pending',
                'verification_status' => 'pending',
                'role' => 'vendor',
                'is_owner' => 1
            ]);

            $vendor->assignRole('vendor');

            DB::commit();

            // Login the vendor
            Auth::guard('vendor')->login($vendor);

            return redirect()->route('vendor.dashboard')->with('success', 'Registration successful! Welcome to ' . $shop->shop_name);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Vendor Registration Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Something went wrong! Please try again.')->withInput();
        }
    }

    public function logout()
    {
        Auth::guard('vendor')->logout();
        $this->logLogout('Vendor logged out');
        return redirect()->route('vendor.login');
    }
}
