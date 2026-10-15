<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Models\Salon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupplierController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $salon = Auth::user()->salon;

        abort_if(!$salon, 403, 'Salon not found.');

        $suppliers = Supplier::where('salon_id', $salon->id)
            ->latest()
            ->paginate(15);

        return view(
            'admin.inventory.suppliers.index',
            compact('suppliers')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */
public function create()
{
    $salon = Auth::user()->salon;

    abort_if(!$salon, 403, 'Salon not found.');

    $salons = Salon::where('id', $salon->id)
        ->orderBy('name')
        ->get();

    return view(
        'admin.inventory.suppliers.create',
        compact('salons')
    );
}


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $salon = Auth::user()->salon;

        abort_if(!$salon, 403, 'Salon not found.');

        $validated = $request->validate(
            [
                'name' => [
                    'required',
                    'string',
                    'max:255',
                ],

                'company_name' => [
                    'nullable',
                    'string',
                    'max:255',
                ],

                'phone' => [
                    'nullable',
                    'regex:/^[6-9][0-9]{9}$/',
                ],

                'email' => [
                    'nullable',
                    'email:rfc',
                    'max:255',
                ],

                'address' => [
                    'nullable',
                    'string',
                ],

                'city' => [
                    'nullable',
                    'string',
                    'max:100',
                ],

                'state' => [
                    'nullable',
                    'string',
                    'max:100',
                ],

                'pincode' => [
                    'nullable',
                    'string',
                    'max:20',
                ],

                'gst_number' => [
                    'nullable',
                    'string',
                    'max:50',
                ],

                'status' => [
                    'required',
                    'in:active,inactive',
                ],

                'notes' => [
                    'nullable',
                    'string',
                ],
            ],
            [
                'phone.regex' =>
                    'Please enter a valid 10-digit Indian mobile number starting with 6, 7, 8 or 9.',

                'email.email' =>
                    'Please enter a valid email address.',

                'email.max' =>
                    'Email address cannot exceed 255 characters.',
            ]
        );

        Supplier::create([
            'salon_id' => $salon->id,
            'name' => $validated['name'],
            'company_name' => $validated['company_name'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'email' => $validated['email'] ?? null,
            'address' => $validated['address'] ?? null,
            'city' => $validated['city'] ?? null,
            'state' => $validated['state'] ?? null,
            'pincode' => $validated['pincode'] ?? null,
            'gst_number' => $validated['gst_number'] ?? null,
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()
            ->route('admin.inventory.suppliers.index')
            ->with(
                'success',
                'Supplier created successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(Supplier $supplier)
    {
        $salon = Auth::user()->salon;

        abort_if(!$salon, 403, 'Salon not found.');

        abort_unless(
            $supplier->salon_id == $salon->id,
            404
        );

        return view(
            'admin.inventory.suppliers.show',
            compact('supplier')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(Supplier $supplier)
    {
        $salon = Auth::user()->salon;

        abort_if(!$salon, 403, 'Salon not found.');

        abort_unless(
            $supplier->salon_id == $salon->id,
            404
        );

        /*
        |--------------------------------------------------------------------------
        | Load salons for edit form
        |--------------------------------------------------------------------------
        */

        $salons = Salon::orderBy('name')->get();

        return view(
            'admin.inventory.suppliers.edit',
            compact(
                'supplier',
                'salons'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        Supplier $supplier
    ) {
        $salon = Auth::user()->salon;

        abort_if(!$salon, 403, 'Salon not found.');

        abort_unless(
            $supplier->salon_id == $salon->id,
            404
        );

        $validated = $request->validate(
            [
                'name' => [
                    'required',
                    'string',
                    'max:255',
                ],

                'company_name' => [
                    'nullable',
                    'string',
                    'max:255',
                ],

                'phone' => [
                    'nullable',
                    'regex:/^[6-9][0-9]{9}$/',
                ],

                'email' => [
                    'nullable',
                    'email:rfc',
                    'max:255',
                ],

                'address' => [
                    'nullable',
                    'string',
                ],

                'city' => [
                    'nullable',
                    'string',
                    'max:100',
                ],

                'state' => [
                    'nullable',
                    'string',
                    'max:100',
                ],

                'pincode' => [
                    'nullable',
                    'string',
                    'max:20',
                ],

                'gst_number' => [
                    'nullable',
                    'string',
                    'max:50',
                ],

                'status' => [
                    'required',
                    'in:active,inactive',
                ],

                'notes' => [
                    'nullable',
                    'string',
                ],
            ],
            [
                'phone.regex' =>
                    'Please enter a valid 10-digit Indian mobile number starting with 6, 7, 8 or 9.',

                'email.email' =>
                    'Please enter a valid email address.',

                'email.max' =>
                    'Email address cannot exceed 255 characters.',
            ]
        );

        $supplier->update([
            'name' => $validated['name'],
            'company_name' => $validated['company_name'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'email' => $validated['email'] ?? null,
            'address' => $validated['address'] ?? null,
            'city' => $validated['city'] ?? null,
            'state' => $validated['state'] ?? null,
            'pincode' => $validated['pincode'] ?? null,
            'gst_number' => $validated['gst_number'] ?? null,
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()
            ->route('admin.inventory.suppliers.index')
            ->with(
                'success',
                'Supplier updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy(Supplier $supplier)
    {
        $salon = Auth::user()->salon;

        abort_if(!$salon, 403, 'Salon not found.');

        abort_unless(
            $supplier->salon_id == $salon->id,
            404
        );

        $supplier->delete();

        return redirect()
            ->route('admin.inventory.suppliers.index')
            ->with(
                'success',
                'Supplier deleted successfully.'
            );
    }
}