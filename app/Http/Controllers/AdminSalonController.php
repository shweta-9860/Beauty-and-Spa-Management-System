<?php

namespace App\Http\Controllers;

use App\Models\Salon;
use Illuminate\Http\Request;

class AdminSalonController extends Controller
{
    /**
     * Show all salon records.
     */
    public function index()
    {
        $salons = Salon::with('owner')
            ->latest()
            ->get();

        return view('admin.salon.profile', compact('salons'));
    }

    /**
     * Show Add Salon form.
     */
    public function create()
    {
        return view('admin.salon.create');
    }

    /**
     * Store new salon.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',

            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'phone' => 'nullable|string|max:20',

            'email' => 'nullable|email|max:255',

            'address' => 'nullable|string',

            'city' => 'nullable|string|max:100',

            'state' => 'nullable|string|max:100',

            'pincode' => 'nullable|string|max:10',

            'gst_number' => 'nullable|string|max:50',

            'opening_time' => 'nullable',

            'closing_time' => 'nullable',

            'currency' => 'required|string|max:10',

            'tax_percentage' => 'required|numeric|min:0|max:100',

            'invoice_prefix' => 'required|string|max:20',

            'receipt_footer' => 'nullable|string',

            'default_appointment_duration' =>
                'required|integer|min:1',

            'status' => 'nullable|boolean',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Owner
        |--------------------------------------------------------------------------
        */

        $validated['owner_id'] = auth()->id();

        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */

        $validated['status'] = $request->boolean('status');

        /*
        |--------------------------------------------------------------------------
        | Logo Upload
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request
                ->file('logo')
                ->store('salons', 'public');
        }

        /*
        |--------------------------------------------------------------------------
        | Create Salon
        |--------------------------------------------------------------------------
        */

        Salon::create($validated);

        return redirect()
            ->route('admin.salon.profile')
            ->with('success', 'Salon added successfully.');
    }
}