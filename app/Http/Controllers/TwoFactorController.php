<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TwoFactorController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | 2FA Settings Page
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $user = Auth::user();

        return view(
            'admin.settings.two-factor',
            compact('user')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Generate / Setup 2FA QR Code
    |--------------------------------------------------------------------------
    */

    public function generate()
    {
        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | If already enabled, don't generate another QR
        |--------------------------------------------------------------------------
        */

        if ($user->two_factor_enabled) {

            return redirect()
                ->route('admin.2fa')
                ->with(
                    'error',
                    '2FA is already enabled. Disable 2FA first.'
                );
        }

        $google2fa = app('pragmarx.google2fa');

        /*
        |--------------------------------------------------------------------------
        | Generate a fresh secret
        |--------------------------------------------------------------------------
        |
        | Disable 2FA
        |      ↓
        | Secret becomes NULL
        |      ↓
        | Enable again
        |      ↓
        | NEW secret
        |      ↓
        | NEW QR Code
        |
        |--------------------------------------------------------------------------
        */

        $secret = $google2fa->generateSecretKey();

        /*
        |--------------------------------------------------------------------------
        | Save secret but keep 2FA disabled
        |--------------------------------------------------------------------------
        */

        $user->google2fa_secret = $secret;
        $user->two_factor_enabled = false;
        $user->save();

        /*
        |--------------------------------------------------------------------------
        | Generate QR Code URL
        |--------------------------------------------------------------------------
        */

        $qrCodeUrl = $google2fa->getQRCodeUrl(
            config('app.name', 'Salon Management'),
            $user->email,
            $secret
        );

        return view(
            'admin.settings.two-factor-setup',
            compact(
                'user',
                'secret',
                'qrCodeUrl'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Enable 2FA
    |--------------------------------------------------------------------------
    */

    public function enable(Request $request)
    {
        $request->validate([
            'otp' => [
                'required',
                'digits:6',
            ],
        ]);

        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | User check
        |--------------------------------------------------------------------------
        */

        if (!$user) {

            return redirect()
                ->route('admin.login')
                ->with(
                    'error',
                    'Please login first.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Secret check
        |--------------------------------------------------------------------------
        */

        if (!$user->google2fa_secret) {

            return redirect()
                ->route('admin.2fa')
                ->with(
                    'error',
                    'Please generate the QR code first.'
                );
        }

        $google2fa = app('pragmarx.google2fa');

        /*
        |--------------------------------------------------------------------------
        | Verify OTP
        |--------------------------------------------------------------------------
        */

        $valid = $google2fa->verifyKey(
            $user->google2fa_secret,
            $request->otp
        );

        /*
        |--------------------------------------------------------------------------
        | Invalid OTP
        |--------------------------------------------------------------------------
        */

        if (!$valid) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Invalid OTP. Please enter the current Google Authenticator code.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Enable 2FA only after successful OTP verification
        |--------------------------------------------------------------------------
        */

        $user->two_factor_enabled = true;
        $user->save();

        /*
        |--------------------------------------------------------------------------
        | Current session is already verified
        |--------------------------------------------------------------------------
        */

        session([
            'two_factor_verified' => true,
        ]);

        return redirect()
            ->route('admin.2fa')
            ->with(
                'success',
                'Two-Factor Authentication enabled successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Disable 2FA
    |--------------------------------------------------------------------------
    */

    public function disable()
    {
        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | Disable 2FA
        |--------------------------------------------------------------------------
        */

        $user->two_factor_enabled = false;

        /*
        |--------------------------------------------------------------------------
        | Delete old Google Authenticator secret
        |--------------------------------------------------------------------------
        |
        | This allows a completely fresh QR setup next time.
        |
        |--------------------------------------------------------------------------
        */

        $user->google2fa_secret = null;

        $user->save();

        /*
        |--------------------------------------------------------------------------
        | Reset current verification session
        |--------------------------------------------------------------------------
        */

        session()->forget('two_factor_verified');

        return redirect()
            ->route('admin.2fa')
            ->with(
                'success',
                'Two-Factor Authentication disabled successfully. You can setup 2FA again using a new QR code.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | OTP Verification Page During Login
    |--------------------------------------------------------------------------
    */

    public function showVerify()
    {
        /*
        |--------------------------------------------------------------------------
        | User must be logged in
        |--------------------------------------------------------------------------
        */

        if (!Auth::check()) {

            return redirect()
                ->route('admin.login');
        }

        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | If 2FA is disabled
        |--------------------------------------------------------------------------
        |
        | No need to show OTP verification page.
        |
        |--------------------------------------------------------------------------
        */

        if (!$user->two_factor_enabled) {

            return redirect()
                ->route('admin.dashboard');
        }

        /*
        |--------------------------------------------------------------------------
        | If already verified
        |--------------------------------------------------------------------------
        |
        | Don't show OTP page again.
        |
        |--------------------------------------------------------------------------
        */

        if (session('two_factor_verified', false)) {

            return redirect()
                ->route('admin.dashboard');
        }

        /*
        |--------------------------------------------------------------------------
        | Show OTP verification page
        |--------------------------------------------------------------------------
        */

        return view(
            'admin.two-factor-verify'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Verify OTP During Login
    |--------------------------------------------------------------------------
    */

    public function verify(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Validate OTP
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'otp' => [
                'required',
                'digits:6',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Get authenticated user
        |--------------------------------------------------------------------------
        */

        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | User check
        |--------------------------------------------------------------------------
        */

        if (!$user) {

            return redirect()
                ->route('admin.login')
                ->with(
                    'error',
                    'Please login first.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Make sure 2FA is actually enabled
        |--------------------------------------------------------------------------
        */

        if (!$user->two_factor_enabled) {

            return redirect()
                ->route('admin.dashboard');
        }

        /*
        |--------------------------------------------------------------------------
        | Secret missing
        |--------------------------------------------------------------------------
        */

        if (!$user->google2fa_secret) {

            Auth::logout();

            session()->forget('two_factor_verified');

            return redirect()
                ->route('admin.login')
                ->with(
                    'error',
                    'Please setup Two-Factor Authentication first.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Google 2FA instance
        |--------------------------------------------------------------------------
        */

        $google2fa = app('pragmarx.google2fa');

        /*
        |--------------------------------------------------------------------------
        | Verify OTP
        |--------------------------------------------------------------------------
        */

        $valid = $google2fa->verifyKey(
            $user->google2fa_secret,
            $request->otp
        );

        /*
        |--------------------------------------------------------------------------
        | Invalid OTP
        |--------------------------------------------------------------------------
        */

        if (!$valid) {

            /*
            |--------------------------------------------------------------------------
            | Important:
            | Keep two_factor_verified false
            |--------------------------------------------------------------------------
            */

            session([
                'two_factor_verified' => false,
            ]);

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Invalid OTP. Please try again.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | OTP VERIFIED SUCCESSFULLY
        |--------------------------------------------------------------------------
        */

        session([
            'two_factor_verified' => true,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Regenerate session ID for security
        |--------------------------------------------------------------------------
        */

        $request->session()->regenerate();

        /*
        |--------------------------------------------------------------------------
        | IMPORTANT:
        | OTP verification is successful.
        | Automatically open Dashboard.
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('admin.dashboard');
    }
}
