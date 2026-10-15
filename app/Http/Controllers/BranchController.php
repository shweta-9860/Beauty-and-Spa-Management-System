<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Salon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BranchController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | All Branches
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $query = Branch::with(['salon', 'manager']);

        // Search
        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%");

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

        $branches = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view(
            'admin.branches.index',
            compact('branches')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Create Branch
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        /*
        |--------------------------------------------------------------------------
        | Only Active Salons
        |--------------------------------------------------------------------------
        */

        $salons = Salon::where('status', true)
            ->orderBy('name')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | No Manager Dropdown
        |--------------------------------------------------------------------------
        |
        | Manager will be created directly from the Branch Create form.
        |
        */

        return view(
            'admin.branches.create',
            compact('salons')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Store Branch + Create New Branch Manager
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $request->validate(

            [

                /*
                |--------------------------------------------------------------------------
                | Salon
                |--------------------------------------------------------------------------
                */

                'salon_id' => [
                    'required',
                    'exists:salons,id'
                ],


                /*
                |--------------------------------------------------------------------------
                | Branch Information
                |--------------------------------------------------------------------------
                */

                'name' => [
                    'required',
                    'string',
                    'max:255'
                ],

                'code' => [
                    'required',
                    'string',
                    'max:50',
                    'unique:branches,code'
                ],


                /*
                |--------------------------------------------------------------------------
                | Branch Manager Name
                |--------------------------------------------------------------------------
                */

                'manager_name' => [
                    'required',
                    'string',
                    'max:255'
                ],


                /*
                |--------------------------------------------------------------------------
                | Branch Phone
                |--------------------------------------------------------------------------
                */

                'phone' => [
                    'nullable',
                    'regex:/^[6-9][0-9]{9}$/',
                ],


                /*
                |--------------------------------------------------------------------------
                | Branch Email
                |--------------------------------------------------------------------------
                */

                'email' => [
                    'nullable',
                    'email:rfc',
                    'max:255'
                ],


                /*
                |--------------------------------------------------------------------------
                | Address
                |--------------------------------------------------------------------------
                */

                'address' => [
                    'nullable',
                    'string'
                ],


                /*
                |--------------------------------------------------------------------------
                | City
                |--------------------------------------------------------------------------
                */

                'city' => [
                    'nullable',
                    'string',
                    'max:100'
                ],


                /*
                |--------------------------------------------------------------------------
                | State
                |--------------------------------------------------------------------------
                */

                'state' => [
                    'nullable',
                    'string',
                    'max:100'
                ],


                /*
                |--------------------------------------------------------------------------
                | Pincode
                |--------------------------------------------------------------------------
                */

                'pincode' => [
                    'nullable',
                    'string',
                    'max:10'
                ],


                /*
                |--------------------------------------------------------------------------
                | Opening Time
                |--------------------------------------------------------------------------
                */

                'opening_time' => [
                    'nullable',
                    'date_format:H:i'
                ],


                /*
                |--------------------------------------------------------------------------
                | Closing Time
                |--------------------------------------------------------------------------
                */

                'closing_time' => [
                    'nullable',
                    'date_format:H:i'
                ],


                /*
                |--------------------------------------------------------------------------
                | Status
                |--------------------------------------------------------------------------
                */

                'status' => [
                    'required',
                    'boolean'
                ],


                /*
                |--------------------------------------------------------------------------
                | Branch Manager Login Email
                |--------------------------------------------------------------------------
                */

                'login_email' => [
                    'required',
                    'email:rfc',
                    'max:255',
                    'unique:users,email'
                ],


                /*
                |--------------------------------------------------------------------------
                | Branch Manager Password
                |--------------------------------------------------------------------------
                */

                'login_password' => [
                    'required',
                    'string',
                    'min:8',
                    'confirmed'
                ],

            ],


            /*
            |--------------------------------------------------------------------------
            | Custom Validation Messages
            |--------------------------------------------------------------------------
            */

            [

                'manager_name.required' =>
                    'Please enter the branch manager name.',

                'phone.regex' =>
                    'Please enter a valid 10-digit Indian mobile number starting with 6, 7, 8 or 9.',

                'email.email' =>
                    'Please enter a valid branch email address.',

                'email.max' =>
                    'Branch email address cannot exceed 255 characters.',

                'login_email.required' =>
                    'Please enter the manager login email.',

                'login_email.email' =>
                    'Please enter a valid manager login email address.',

                'login_email.max' =>
                    'Manager login email cannot exceed 255 characters.',

                'login_email.unique' =>
                    'This login email is already registered.',

                'login_password.min' =>
                    'Manager password must be at least 8 characters.',

                'login_password.confirmed' =>
                    'Password and confirm password must match.',

            ]
        );


        /*
        |--------------------------------------------------------------------------
        | Check Opening / Closing Time
        |--------------------------------------------------------------------------
        */

        if (
            !empty($validated['opening_time']) &&
            !empty($validated['closing_time']) &&
            $validated['closing_time'] <= $validated['opening_time']
        ) {

            return back()
                ->withInput()
                ->withErrors([
                    'closing_time' =>
                        'Closing time must be after opening time.'
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Database Transaction
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use ($validated) {

            /*
            |--------------------------------------------------------------------------
            | Create Branch
            |--------------------------------------------------------------------------
            */

            $branch = Branch::create([

                'salon_id' =>
                    $validated['salon_id'],

                'name' =>
                    $validated['name'],

                'code' =>
                    strtoupper($validated['code']),

                'phone' =>
                    $validated['phone'] ?? null,

                'email' =>
                    $validated['email'] ?? null,

                'address' =>
                    $validated['address'] ?? null,

                'city' =>
                    $validated['city'] ?? null,

                'state' =>
                    $validated['state'] ?? null,

                'pincode' =>
                    $validated['pincode'] ?? null,

                /*
                |--------------------------------------------------------------------------
                | Manager ID will be updated after manager creation
                |--------------------------------------------------------------------------
                */

                'manager_id' =>
                    null,

                'opening_time' =>
                    $validated['opening_time'] ?? null,

                'closing_time' =>
                    $validated['closing_time'] ?? null,

                'status' =>
                    $validated['status'],

            ]);


            /*
            |--------------------------------------------------------------------------
            | Create NEW Branch Manager User
            |--------------------------------------------------------------------------
            */

            $branchManager = User::create([

                'name' =>
                    $validated['manager_name'],

                'email' =>
                    $validated['login_email'],

                'password' =>
                    $validated['login_password'],

                'role' =>
                    'manager',

                'salon_id' =>
                    $branch->salon_id,

                'branch_id' =>
                    $branch->id,

            ]);


            /*
            |--------------------------------------------------------------------------
            | Assign Newly Created Manager to Branch
            |--------------------------------------------------------------------------
            */

            $branch->update([

                'manager_id' =>
                    $branchManager->id,

            ]);

        });


        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('admin.branches.index')
            ->with(
                'success',
                'Branch and new branch manager account created successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Show Branch
    |--------------------------------------------------------------------------
    */

    public function show(Branch $branch)
    {
        return redirect()->route(
            'admin.branches.edit',
            $branch->id
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Edit Branch
    |--------------------------------------------------------------------------
    */

    public function edit(Branch $branch)
    {
        $salons = Salon::where('status', true)
            ->orderBy('name')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Managers for Edit Page
        |--------------------------------------------------------------------------
        |
        | Existing manager can still be changed from Edit page.
        |
        */

        $managers = User::whereIn('role', [
                'manager',
                'salon_owner',
                'owner'
            ])
            ->orderBy('name')
            ->get();

        return view(
            'admin.branches.edit',
            compact(
                'branch',
                'salons',
                'managers'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Update Branch
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        Branch $branch
    ) {

        $validated = $request->validate(

            [

                'salon_id' => [
                    'required',
                    'exists:salons,id'
                ],

                'name' => [
                    'required',
                    'string',
                    'max:255'
                ],

                'code' => [
                    'required',
                    'string',
                    'max:50',
                    'unique:branches,code,' . $branch->id
                ],

                'phone' => [
                    'nullable',
                    'regex:/^[6-9][0-9]{9}$/',
                ],

                'email' => [
                    'nullable',
                    'email:rfc',
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
                    'max:10'
                ],

                'manager_id' => [
                    'nullable',
                    'exists:users,id'
                ],

                'opening_time' => [
                    'nullable',
                    'date_format:H:i'
                ],

                'closing_time' => [
                    'nullable',
                    'date_format:H:i'
                ],

                'status' => [
                    'required',
                    'boolean'
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


        /*
        |--------------------------------------------------------------------------
        | Check Opening / Closing Time
        |--------------------------------------------------------------------------
        */

        if (
            !empty($validated['opening_time']) &&
            !empty($validated['closing_time']) &&
            $validated['closing_time'] <= $validated['opening_time']
        ) {

            return back()
                ->withInput()
                ->withErrors([
                    'closing_time' =>
                        'Closing time must be after opening time.'
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Update Branch
        |--------------------------------------------------------------------------
        */

        $branch->update([

            'salon_id' =>
                $validated['salon_id'],

            'name' =>
                $validated['name'],

            'code' =>
                strtoupper($validated['code']),

            'phone' =>
                $validated['phone'] ?? null,

            'email' =>
                $validated['email'] ?? null,

            'address' =>
                $validated['address'] ?? null,

            'city' =>
                $validated['city'] ?? null,

            'state' =>
                $validated['state'] ?? null,

            'pincode' =>
                $validated['pincode'] ?? null,

            'manager_id' =>
                $validated['manager_id'] ?? null,

            'opening_time' =>
                $validated['opening_time'] ?? null,

            'closing_time' =>
                $validated['closing_time'] ?? null,

            'status' =>
                $validated['status'],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('admin.branches.index')
            ->with(
                'success',
                'Branch updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Delete Branch
    |--------------------------------------------------------------------------
    */

    public function destroy(Branch $branch)
    {
        $branch->delete();

        return redirect()
            ->route('admin.branches.index')
            ->with(
                'success',
                'Branch deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Toggle Status
    |--------------------------------------------------------------------------
    */

    public function toggleStatus(Branch $branch)
    {
        $branch->update([
            'status' => !$branch->status,
        ]);

        return redirect()
            ->route('admin.branches.index')
            ->with(
                'success',
                'Branch status updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Branch Login Page
    |--------------------------------------------------------------------------
    */

    public function loginForm(Branch $branch)
    {
        return view(
            'admin.branches.login',
            compact('branch')
        );
    }
}