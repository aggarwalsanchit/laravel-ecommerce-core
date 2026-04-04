<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorPermission
{
    public function handle(Request $request, Closure $next, $permission)
    {
        $vendor = Auth::guard('vendor')->user();

        if (!$vendor) {
            return redirect()->route('vendor.login');
        }

        if (!$vendor->hasPermissionTo($permission, 'vendor')) {
            abort(403, 'You do not have permission to access this page.');
        }

        return $next($request);
    }
}
