<?php

// app/Http/Controllers/Vendor/ResetPasswordController.php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Validator;

class VendorResetPasswordController extends Controller
{
    // Show reset password form
    public function showResetForm(Request $request, $token = null)
    {
        return view('marketplace.auth.reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    // Reset password
    public function reset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required|email|exists:vendors,email',
            'password' => 'required|min:8|confirmed',
        ], [
            'email.exists' => 'We could not find a vendor account with that email address.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $response = Password::broker('vendors')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($vendor, $password) {
                $vendor->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $vendor->save();

                event(new PasswordReset($vendor));
            }
        );

        if ($response == Password::PASSWORD_RESET) {
            return redirect()->route('vendor.login')->with('status', 'Your password has been reset! You can now login.');
        }

        return back()->withErrors(['email' => trans($response)]);
    }
}
