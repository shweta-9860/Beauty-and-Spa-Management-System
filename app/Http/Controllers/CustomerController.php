<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Salon;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    /**
     * Check whether logged-in user is a branch user.
     */
    private function isBranchUser(): bool
    {
        return auth()->check() && !empty(auth()->user()->branch_id);
    }

    /**
     * Get logged-in user's branch ID.
     */
    private function currentBranchId(): ?int
    {
        return $this->isBranchUser()
            ? (int) auth()->user()->branch_id
            : null;
    }


    /**
     * Customer List
     */
    public function index(Request $request)
    {
        $query = Customer::with([
            'salon',
            'branch',
        ]);

        /*
        |--------------------------------------------------------------------------
        | BRANCH DATA ISOLATION
        |--------------------------------------------------------------------------
        */
        if ($this->isBranchUser()) {

    $query->where(
        'salon_id',
        auth()->user()->salon_id
    );

    $query->where(
        'branch_id',
        $this->currentBranchId()
    );

} else {

       

            // Super Admin / Salon Owner can filter manually

            if ($request->filled('salon_id')) {
                $query->where(
                    'salon_id',
                    $request->salon_id
                );
            }

            if ($request->filled('branch_id')) {
                $query->where(
                    'branch_id',
                    $request->branch_id
                );
            }
        }


        // Search
        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where(
                    'name',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'phone',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'email',
                    'like',
                    "%{$search}%"
                );

            });
        }


        // Status filter
        if ($request->filled('status')) {

            if ($request->status === 'active') {

                $query->where('status', true);

            } elseif ($request->status === 'inactive') {

                $query->where('status', false);

            }
        }


        // Customers
        $customers = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | DROPDOWNS
        |--------------------------------------------------------------------------
        */

        if ($this->isBranchUser()) {

            $branchId = $this->currentBranchId();

            $branches = Branch::where('id', $branchId)
                ->where('status', true)
                ->get();

            $salons = Salon::where(
                    'id',
                    auth()->user()->salon_id
                )
                ->where('status', true)
                ->orderBy('name')
                ->get();

        } else {

            $salons = Salon::where('status', true)
                ->orderBy('name')
                ->get();

            $branches = Branch::where('status', true)
                ->when(
                    $request->filled('salon_id'),
                    function ($query) use ($request) {

                        $query->where(
                            'salon_id',
                            $request->salon_id
                        );

                    }
                )
                ->orderBy('name')
                ->get();
        }


        return view(
            'admin.customers.index',
            compact(
                'customers',
                'salons',
                'branches'
            )
        );
    }


    /**
     * Create Customer Form
     */
    public function create()
    {
        /*
        |--------------------------------------------------------------------------
        | BRANCH USER
        |--------------------------------------------------------------------------
        */

        if ($this->isBranchUser()) {

            $salons = Salon::where(
                    'id',
                    auth()->user()->salon_id
                )
                ->where('status', true)
                ->orderBy('name')
                ->get();

            $branches = Branch::where(
                    'id',
                    $this->currentBranchId()
                )
                ->where('status', true)
                ->orderBy('name')
                ->get();

        } else {

            $salons = Salon::where('status', true)
                ->orderBy('name')
                ->get();

            $branches = Branch::where('status', true)
                ->orderBy('name')
                ->get();
        }


        return view(
            'admin.customers.create',
            compact(
                'salons',
                'branches'
            )
        );
    }


/**
 * Store Customer
 */
public function store(Request $request)
{
    /*
    |--------------------------------------------------------------------------
    | BRANCH USER
    |--------------------------------------------------------------------------
    |
    | Branch user कडून आलेला branch_id ignore करून
    | actual logged-in branch वापरला जाईल.
    |
    */

    if ($this->isBranchUser()) {

        $request->merge([
            'salon_id'  => auth()->user()->salon_id,
            'branch_id' => auth()->user()->branch_id,
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | VALIDATION
    |--------------------------------------------------------------------------
    */

    $validated = $request->validate([

        'salon_id' => [
            'required',
            'exists:salons,id',
        ],

        'branch_id' => [
            'required',
            'exists:branches,id',

            Rule::exists('branches', 'id')
                ->where(function ($query) use ($request) {

                    $query->where(
                        'salon_id',
                        $request->salon_id
                    );

                    $query->where(
                        'status',
                        true
                    );

                }),
        ],

        /*
        |--------------------------------------------------------------------------
        | CUSTOMER NAME
        |--------------------------------------------------------------------------
        */

        'name' => [
            'required',
            'string',
            'max:255',
        ],

        /*
        |--------------------------------------------------------------------------
        | EMAIL
        |--------------------------------------------------------------------------
        |
        | Valid email format only.
        |
        */

        'email' => [
            'nullable',
            'email:rfc',
            'max:255',
        ],

        /*
        |--------------------------------------------------------------------------
        | PHONE / CONTACT NUMBER
        |--------------------------------------------------------------------------
        |
        | Indian 10 digit mobile number.
        | First digit must be 6, 7, 8 or 9.
        |
        */

        'phone' => [
            'nullable',
            'regex:/^[6-9][0-9]{9}$/',
        ],

        /*
        |--------------------------------------------------------------------------
        | OTHER FIELDS
        |--------------------------------------------------------------------------
        */

        'gender' => [
            'nullable',
            'in:male,female,other',
        ],

        'date_of_birth' => [
            'nullable',
            'date',
        ],

        'address' => [
            'nullable',
            'string',
        ],

        'notes' => [
            'nullable',
            'string',
        ],

        'status' => [
            'required',
            'boolean',
        ],

    ], [

        /*
        |--------------------------------------------------------------------------
        | CUSTOM VALIDATION MESSAGES
        |--------------------------------------------------------------------------
        */

        'email.email' =>
            'Please enter a valid email address.',

        'email.max' =>
            'Email address cannot exceed 255 characters.',

        'phone.regex' =>
            'Please enter a valid 10-digit Indian mobile number starting with 6, 7, 8 or 9.',

    ]);


    /*
    |--------------------------------------------------------------------------
    | CREATE CUSTOMER
    |--------------------------------------------------------------------------
    */

    Customer::create($validated);


    /*
    |--------------------------------------------------------------------------
    | REDIRECT
    |--------------------------------------------------------------------------
    */

    return redirect()
        ->route('admin.customers.index')
        ->with(
            'success',
            'Customer added successfully.'
        );
}

    /**
     * Show Customer
     */
    public function show(Customer $customer)
    {
        /*
        |--------------------------------------------------------------------------
        | BLOCK OTHER BRANCH
        |--------------------------------------------------------------------------
        */

        $this->checkBranchAccess($customer);


        return redirect()->route(
            'admin.customers.edit',
            $customer->id
        );
    }


    /**
     * Edit Customer Form
     */
    public function edit(Customer $customer)
    {
        /*
        |--------------------------------------------------------------------------
        | BLOCK OTHER BRANCH
        |--------------------------------------------------------------------------
        */

        $this->checkBranchAccess($customer);


        if ($this->isBranchUser()) {

            $salons = Salon::where(
                    'id',
                    auth()->user()->salon_id
                )
                ->where('status', true)
                ->orderBy('name')
                ->get();

            $branches = Branch::where(
                    'id',
                    $this->currentBranchId()
                )
                ->where('status', true)
                ->orderBy('name')
                ->get();

        } else {

            $salons = Salon::where('status', true)
                ->orderBy('name')
                ->get();

            $branches = Branch::where('status', true)
                ->orderBy('name')
                ->get();
        }


        return view(
            'admin.customers.edit',
            compact(
                'customer',
                'salons',
                'branches'
            )
        );
    }


/**
 * Update Customer
 */
public function update(
    Request $request,
    Customer $customer
) {

    /*
    |--------------------------------------------------------------------------
    | BLOCK OTHER BRANCH
    |--------------------------------------------------------------------------
    */

    $this->checkBranchAccess($customer);


    /*
    |--------------------------------------------------------------------------
    | FORCE CURRENT BRANCH
    |--------------------------------------------------------------------------
    */

    if ($this->isBranchUser()) {

        $request->merge([
            'salon_id'  => auth()->user()->salon_id,
            'branch_id' => auth()->user()->branch_id,
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | VALIDATION
    |--------------------------------------------------------------------------
    */

    $validated = $request->validate([

        'salon_id' => [
            'required',
            'exists:salons,id',
        ],

        'branch_id' => [
            'required',
            'exists:branches,id',

            Rule::exists('branches', 'id')
                ->where(function ($query) use ($request) {

                    $query->where(
                        'salon_id',
                        $request->salon_id
                    );

                    $query->where(
                        'status',
                        true
                    );

                }),
        ],

        /*
        |--------------------------------------------------------------------------
        | CUSTOMER NAME
        |--------------------------------------------------------------------------
        */

        'name' => [
            'required',
            'string',
            'max:255',
        ],

        /*
        |--------------------------------------------------------------------------
        | EMAIL
        |--------------------------------------------------------------------------
        */

        'email' => [
            'nullable',
            'email:rfc',
            'max:255',
        ],

        /*
        |--------------------------------------------------------------------------
        | PHONE / CONTACT NUMBER
        |--------------------------------------------------------------------------
        |
        | Indian 10 digit mobile number.
        | First digit must be 6, 7, 8 or 9.
        |
        */

        'phone' => [
            'nullable',
            'regex:/^[6-9][0-9]{9}$/',
        ],

        /*
        |--------------------------------------------------------------------------
        | OTHER FIELDS
        |--------------------------------------------------------------------------
        */

        'gender' => [
            'nullable',
            'in:male,female,other',
        ],

        'date_of_birth' => [
            'nullable',
            'date',
        ],

        'address' => [
            'nullable',
            'string',
        ],

        'notes' => [
            'nullable',
            'string',
        ],

        'status' => [
            'required',
            'boolean',
        ],

    ], [

        /*
        |--------------------------------------------------------------------------
        | CUSTOM VALIDATION MESSAGES
        |--------------------------------------------------------------------------
        */

        'email.email' =>
            'Please enter a valid email address.',

        'email.max' =>
            'Email address cannot exceed 255 characters.',

        'phone.regex' =>
            'Please enter a valid 10-digit Indian mobile number starting with 6, 7, 8 or 9.',

    ]);


    /*
    |--------------------------------------------------------------------------
    | UPDATE CUSTOMER
    |--------------------------------------------------------------------------
    */

    $customer->update($validated);


    /*
    |--------------------------------------------------------------------------
    | REDIRECT
    |--------------------------------------------------------------------------
    */

    return redirect()
        ->route('admin.customers.index')
        ->with(
            'success',
            'Customer updated successfully.'
        );
}

    /**
     * Delete Customer
     */
    public function destroy(Customer $customer)
    {
        /*
        |--------------------------------------------------------------------------
        | BLOCK OTHER BRANCH
        |--------------------------------------------------------------------------
        */

        $this->checkBranchAccess($customer);


        $customer->delete();


        return redirect()
            ->route('admin.customers.index')
            ->with(
                'success',
                'Customer deleted successfully.'
            );
    }


    /**
     * Check Branch Access
     */
    private function checkBranchAccess(Customer $customer): void
    {
        if (
            $this->isBranchUser() &&
            (int) $customer->branch_id !== $this->currentBranchId()
        ) {
            abort(
                403,
                'You do not have access to this customer.'
            );
        }
    }
}