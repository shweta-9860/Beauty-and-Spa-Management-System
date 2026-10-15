<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class BranchLoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Branch Login
    |--------------------------------------------------------------------------
    */

    public function login(
        Request $request,
        Branch $branch
    ) {
        /*
        |--------------------------------------------------------------------------
        | Branch must be active
        |--------------------------------------------------------------------------
        */

        if (!$branch->status) {

            return back()
                ->withInput($request->only('email'))
                ->with(
                    'error',
                    'This branch is currently inactive.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Validate
        |--------------------------------------------------------------------------
        */

        $credentials = $request->validate([
            'email' => [
                'required',
                'email',
            ],

            'password' => [
                'required',
                'string',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Find Branch User
        |--------------------------------------------------------------------------
        */

        $user = User::where(
                'email',
                $credentials['email']
            )
            ->where(
                'branch_id',
                $branch->id
            )
            ->where(
                'salon_id',
                $branch->salon_id
            )
            ->first();

        /*
        |--------------------------------------------------------------------------
        | User Not Found
        |--------------------------------------------------------------------------
        */

        if (!$user) {

            return back()
                ->withInput(
                    $request->only('email')
                )
                ->with(
                    'error',
                    'No user found for this branch with the provided email.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Password Check
        |--------------------------------------------------------------------------
        */

        if (!Hash::check(
            $credentials['password'],
            $user->password
        )) {

            return back()
                ->withInput(
                    $request->only('email')
                )
                ->with(
                    'error',
                    'Invalid branch email or password.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Login
        |--------------------------------------------------------------------------
        */

        Auth::login(
            $user,
            $request->boolean('remember')
        );

        /*
        |--------------------------------------------------------------------------
        | Regenerate Session
        |--------------------------------------------------------------------------
        */

        $request->session()->regenerate();

        /*
        |--------------------------------------------------------------------------
        | Branch Session
        |--------------------------------------------------------------------------
        */

        session([
            'salon_id' => $user->salon_id,

            'branch_id' => $user->branch_id,

            'branch_login' => true,

            'two_factor_verified' => false,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('admin.dashboard')
            ->with(
                'success',
                'Welcome to ' . $branch->name . '.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Branch Logout
    |--------------------------------------------------------------------------
    */

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()
            ->route('admin.login')
            ->with(
                'success',
                'Branch logged out successfully.'
            );
    }
}

