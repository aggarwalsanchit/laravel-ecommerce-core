<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Throwable;

class Handler extends ExceptionHandler
{
    // ... other code ...

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        // Check if it's an admin guard attempt
        $guard = $exception->guards()[0] ?? null;
        
        if ($guard === 'admin') {
            return $request->expectsJson()
                ? response()->json(['message' => 'Unauthenticated.'], 401)
                : redirect()->guest(route('admin.login'));
        }

        // Default redirect for web guard
        return $request->expectsJson()
            ? response()->json(['message' => 'Unauthenticated.'], 401)
            : redirect()->guest(route('login'));
    }
}