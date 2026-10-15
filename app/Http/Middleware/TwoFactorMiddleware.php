<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TwoFactorMiddleware
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {

        /*
        |--------------------------------------------------------------------------
        | User must be logged in
        |--------------------------------------------------------------------------
        */

        if (!auth()->check()) {

            return redirect()
                ->route('admin.login');
        }

        $user = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | 2FA is disabled
        |--------------------------------------------------------------------------
        | If 2FA is not enabled, allow access normally.
        |--------------------------------------------------------------------------
        */

        if (!$user->two_factor_enabled) {

            return $next($request);
        }

        /*
        |--------------------------------------------------------------------------
        | Allow 2FA verification page
        |--------------------------------------------------------------------------
        | User must be able to open the OTP page even when
        | two_factor_verified is false.
        |--------------------------------------------------------------------------
        */

        if ($request->routeIs('admin.2fa.verify')) {

            return $next($request);
        }

        /*
        |--------------------------------------------------------------------------
        | 2FA is enabled but NOT verified
        |--------------------------------------------------------------------------
        */

        if (!session('two_factor_verified', false)) {

            return redirect()
                ->route('admin.2fa.verify');
        }

        /*
        |--------------------------------------------------------------------------
        | 2FA verified
        |--------------------------------------------------------------------------
        */

        return $next($request);
    }
}