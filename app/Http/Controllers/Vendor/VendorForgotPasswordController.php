<?php

// app/Http/Controllers/Vendor/ForgotPasswordController.php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class VendorForgotPasswordController extends Controller
{
    // Show forgot password form
    public function showForgotForm()
    {
        return view('marketplace.auth.forgot-password');
    }

    // Send reset link email
    public function sendResetLink(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:vendors,email',
        ], [
            'email.exists' => 'We could not find a vendor account with that email address.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Send reset link using vendor broker
        $response = Password::broker('vendors')->sendResetLink(
            $request->only('email')
        );

        if ($response == Password::RESET_LINK_SENT) {
            return back()->with('status', trans($response));
        }

        return back()->withErrors(['email' => trans($response)]);
    }
}
