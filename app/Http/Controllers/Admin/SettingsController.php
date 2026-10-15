<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PrinterSetting;
use App\Models\Salon;
use App\Models\TwoFactorAuthentication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class SettingsController extends Controller
{
    /**
     * Display settings page.
     */
    public function index()
    {
        $user = auth()->user();

        $salon = $user->salon;

        if (!$salon) {
            return redirect()
                ->back()
                ->with('error', 'Your account is not linked to a salon.');
        }

        return view('admin.settings.index', compact(
            'user',
            'salon'
        ));
    }


    /**
     * Update profile and salon settings.
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $salon = $user->salon;

        if (!$salon) {
            return back()->with(
                'error',
                'Your account is not linked to a salon.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            // User details
            'name' => [
                'required',
                'string',
                'max:255'
            ],

            'email' => [
                'required',
                'string',
                'email:rfc,dns',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id)
            ],

            'phone' => [
                'required',
                'regex:/^[6-9][0-9]{9}$/'
            ],

            // Salon details
            'salon_name' => [
                'required',
                'string',
                'max:255'
            ],

            'salon_email' => [
                'nullable',
                'string',
                'email:rfc,dns',
                'max:255'
            ],

            'address' => [
                'nullable',
                'string'
            ],

            'city' => [
                'nullable',
                'string',
                'max:100'
            ],

            'state' => [
                'nullable',
                'string',
                'max:100'
            ],

            'pincode' => [
                'nullable',
                'string',
                'regex:/^[0-9]{6}$/'
            ],

            'gst_number' => [
                'nullable',
                'string',
                'max:50'
            ],

            // Business settings
            'opening_time' => [
                'nullable',
                'date_format:H:i'
            ],

            'closing_time' => [
                'nullable',
                'date_format:H:i'
            ],

            'currency' => [
                'required',
                'string',
                'max:10'
            ],

            'tax_percentage' => [
                'required',
                'numeric',
                'min:0',
                'max:100'
            ],

            'invoice_prefix' => [
                'required',
                'string',
                'max:20'
            ],

            'receipt_footer' => [
                'nullable',
                'string'
            ],

            'default_appointment_duration' => [
                'required',
                'integer',
                'min:5',
                'max:1440'
            ],

            'status' => [
                'required',
                'boolean'
            ],

            /*
            |--------------------------------------------------------------------------
            | Logo
            |--------------------------------------------------------------------------
            */

            'logo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048'
            ],

            /*
            |--------------------------------------------------------------------------
            | Authorized Signature
            |--------------------------------------------------------------------------
            */

            'authorized_signature' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048'
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Update User
        |--------------------------------------------------------------------------
        */

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Update Salon Basic Information
        |--------------------------------------------------------------------------
        */

        $salon->name = $validated['salon_name'];

        $salon->phone = $validated['phone'];

        $salon->email = $validated['salon_email'] ?? null;

        $salon->address = $validated['address'] ?? null;

        $salon->city = $validated['city'] ?? null;

        $salon->state = $validated['state'] ?? null;

        $salon->pincode = $validated['pincode'] ?? null;

        $salon->gst_number = $validated['gst_number'] ?? null;


        /*
        |--------------------------------------------------------------------------
        | Opening / Closing Time
        |--------------------------------------------------------------------------
        */

        $salon->opening_time = $validated['opening_time'] ?? null;

        $salon->closing_time = $validated['closing_time'] ?? null;


        /*
        |--------------------------------------------------------------------------
        | Business Settings
        |--------------------------------------------------------------------------
        */

        $salon->currency = $validated['currency'];

        $salon->tax_percentage = $validated['tax_percentage'];

        $salon->invoice_prefix = $validated['invoice_prefix'];

        $salon->receipt_footer = $validated['receipt_footer'] ?? null;

        $salon->default_appointment_duration =
            $validated['default_appointment_duration'];

        $salon->status = $request->boolean('status');


        /*
        |--------------------------------------------------------------------------
        | Upload Logo
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('logo')) {

            /*
            | Delete old logo
            */

            if (
                !empty($salon->logo) &&
                Storage::disk('public')->exists($salon->logo)
            ) {
                Storage::disk('public')->delete($salon->logo);
            }

            /*
            | Store new logo
            */

            $logoPath = $request
                ->file('logo')
                ->store('salons', 'public');

            $salon->logo = $logoPath;
        }


        /*
        |--------------------------------------------------------------------------
        | Upload Authorized Signature
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('authorized_signature')) {

            /*
            | Delete old authorized signature
            */

            if (
                !empty($salon->authorized_signature) &&
                Storage::disk('public')->exists(
                    $salon->authorized_signature
                )
            ) {
                Storage::disk('public')->delete(
                    $salon->authorized_signature
                );
            }

            /*
            | Store new authorized signature
            */

            $signaturePath = $request
                ->file('authorized_signature')
                ->store('signatures', 'public');

            $salon->authorized_signature = $signaturePath;
        }


        /*
        |--------------------------------------------------------------------------
        | Save Salon
        |--------------------------------------------------------------------------
        */

        $salon->save();


        /*
        |--------------------------------------------------------------------------
        | Success
        |--------------------------------------------------------------------------
        */

        return back()->with(
            'success',
            'Profile settings updated successfully.'
        );
    }


    /**
     * Update password.
     */
    public function updatePassword(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'current_password' => [
                'required',
                'current_password'
            ],

            'password' => [
                'required',
                'confirmed',
                Password::min(8)
            ],
        ]);


        $user->update([
            'password' => Hash::make(
                $validated['password']
            ),
        ]);


        return back()->with(
            'success',
            'Password updated successfully.'
        );
    }


    /**
     * Update security / 2FA.
     */
    public function updateSecurity(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'two_factor_enabled' => [
                'nullable',
                'boolean'
            ],
        ]);

        $enabled = $request->boolean(
            'two_factor_enabled'
        );


        /*
        |--------------------------------------------------------------------------
        | If project uses TwoFactorAuthentication table
        |--------------------------------------------------------------------------
        */

        $twoFactor = TwoFactorAuthentication::firstOrNew([
            'user_id' => $user->id,
        ]);

        $twoFactor->enabled = $enabled;

        $twoFactor->save();


        return back()->with(
            'success',
            $enabled
                ? 'Two-factor authentication enabled successfully.'
                : 'Two-factor authentication disabled successfully.'
        );
    }


    /**
     * Printer settings page.
     */
    public function printer()
    {
        $user = auth()->user();

        $salon = $user->salon;

        if (!$salon) {
            return back()->with(
                'error',
                'Your account is not linked to a salon.'
            );
        }

        $printer = PrinterSetting::firstOrCreate(
            [
                'salon_id' => $salon->id
            ],
            [
                'printer_name' => null,
                'connection_type' => 'browser',
                'printer_ip' => null,
                'printer_port' => null,
                'paper_width' => '80mm',
                'is_active' => true,
            ]
        );

        return view(
            'admin.settings.printer',
            compact(
                'printer',
                'salon'
            )
        );
    }


    /**
     * Update printer settings.
     */
    public function updatePrinter(Request $request)
    {
        $user = auth()->user();

        $salon = $user->salon;

        if (!$salon) {
            return back()->with(
                'error',
                'Your account is not linked to a salon.'
            );
        }


        $validated = $request->validate([
            'printer_name' => [
                'nullable',
                'string',
                'max:255'
            ],

            'connection_type' => [
                'required',
                'string',
                'max:50'
            ],

            'printer_ip' => [
                'nullable',
                'ip'
            ],

            'printer_port' => [
                'nullable',
                'integer',
                'min:1',
                'max:65535'
            ],

            'paper_width' => [
                'required',
                'in:58mm,80mm'
            ],

            'is_active' => [
                'nullable',
                'boolean'
            ],
        ]);


        $printer = PrinterSetting::firstOrNew([
            'salon_id' => $salon->id
        ]);


        $printer->printer_name =
            $validated['printer_name'] ?? null;

        $printer->connection_type =
            $validated['connection_type'];

        $printer->printer_ip =
            $validated['printer_ip'] ?? null;

        $printer->printer_port =
            $validated['printer_port'] ?? null;

        $printer->paper_width =
            $validated['paper_width'];

        $printer->is_active =
            $request->boolean('is_active');


        $printer->save();


        return back()->with(
            'success',
            'Printer settings updated successfully.'
        );
    }
}