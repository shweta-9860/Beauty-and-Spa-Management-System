<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    /**
     * Show Admin Login Page
     */
    public function showLogin()
    {
        return view('admin.login');
    }

    /**
     * Admin Login
     */

public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (!Auth::attempt(
        $credentials,
        $request->boolean('remember')
    )) {

        return back()
            ->withInput($request->only('email'))
            ->with(
                'error',
                'Invalid email or password.'
            );
    }

    $request->session()->regenerate();

    /*
    |--------------------------------------------------------------------------
    | Reset 2FA verification on every login
    |--------------------------------------------------------------------------
    */

    session([
        'two_factor_verified' => false,
    ]);

    
$user = Auth::user();

session([
    'salon_id' => $user->salon_id,
    'branch_id' => $user->branch_id,
    'two_factor_verified' => false,
]);
    /*
    |--------------------------------------------------------------------------
    | If 2FA is not setup
    |--------------------------------------------------------------------------
    */

    if (!$user->google2fa_secret) {

        return redirect()
            ->route('admin.2fa.setup');
    }

    /*
    |--------------------------------------------------------------------------
    | 2FA already setup
    |--------------------------------------------------------------------------
    */

    return redirect()
        ->route('admin.2fa.verify');
}



    /**
     * Admin Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()
            ->route('admin.login')
            ->with('success', 'You have been logged out successfully.');
    }
    
}

