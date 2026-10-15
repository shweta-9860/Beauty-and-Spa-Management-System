<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\Salon;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StaffController extends Controller
{
    /**
     * Check whether logged-in user is a branch user.
     */
    private function isBranchUser(): bool
    {
        return auth()->check() && !empty(auth()->user()->branch_id);
    }


    /**
     * Current logged-in branch ID.
     */
    private function currentBranchId(): ?int
    {
        return $this->isBranchUser()
            ? (int) auth()->user()->branch_id
            : null;
    }


    /**
     * Current logged-in salon ID.
     */
    private function currentSalonId(): ?int
    {
        return auth()->check()
            ? (int) auth()->user()->salon_id
            : null;
    }


    /**
     * Check staff branch access.
     */
    private function checkBranchAccess(Staff $staff): void
    {
        if (
            $this->isBranchUser() &&
            (
                (int) $staff->branch_id !== $this->currentBranchId() ||
                (int) $staff->salon_id !== $this->currentSalonId()
            )
        ) {
            abort(
                403,
                'You do not have access to this staff member.'
            );
        }
    }


    /**
     * Display all staff.
     */
    public function index(Request $request)
    {
        $query = Staff::with([
            'salon',
            'branch',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Branch User Isolation
        |--------------------------------------------------------------------------
        */

        if ($this->isBranchUser()) {

            $query->where(
                'salon_id',
                $this->currentSalonId()
            );

            $query->where(
                'branch_id',
                $this->currentBranchId()
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where(
                    'name',
                    'like',
                    '%' . $search . '%'
                )
                ->orWhere(
                    'email',
                    'like',
                    '%' . $search . '%'
                )
                ->orWhere(
                    'phone',
                    'like',
                    '%' . $search . '%'
                )
                ->orWhere(
                    'role',
                    'like',
                    '%' . $search . '%'
                )
                ->orWhere(
                    'specialization',
                    'like',
                    '%' . $search . '%'
                );

            });
        }


        /*
        |--------------------------------------------------------------------------
        | Role Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('role')) {

            $query->where(
                'role',
                $request->role
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Branch Filter
        |--------------------------------------------------------------------------
        */

        if (
            !$this->isBranchUser() &&
            $request->filled('branch_id')
        ) {

            $query->where(
                'branch_id',
                $request->branch_id
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Salon Filter
        |--------------------------------------------------------------------------
        */

        if (
            !$this->isBranchUser() &&
            $request->filled('salon_id')
        ) {

            $query->where(
                'salon_id',
                $request->salon_id
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Staff List
        |--------------------------------------------------------------------------
        */

        $staff = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | Filter Roles
        |--------------------------------------------------------------------------
        */

        $roles = Staff::query()
            ->whereNotNull('role')
            ->where('role', '!=', '')
            ->when(
                $this->isBranchUser(),
                function ($q) {

                    $q->where(
                        'salon_id',
                        $this->currentSalonId()
                    );

                    $q->where(
                        'branch_id',
                        $this->currentBranchId()
                    );

                }
            )
            ->distinct()
            ->orderBy('role')
            ->pluck('role');


        /*
        |--------------------------------------------------------------------------
        | Salons
        |--------------------------------------------------------------------------
        */

        $salons = Salon::where(
            'status',
            true
        )
            ->orderBy('name')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Branches
        |--------------------------------------------------------------------------
        */

        $branchesQuery = Branch::where(
            'status',
            true
        )
            ->orderBy('name');


        if ($this->isBranchUser()) {

            $branchesQuery->where(
                'id',
                $this->currentBranchId()
            );
        }


        $branches = $branchesQuery->get();


        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */

        return view(
            'admin.staff.index',
            compact(
                'staff',
                'roles',
                'salons',
                'branches'
            )
        );
    }


    /**
     * Show create staff form.
     */
    public function create()
    {
        if ($this->isBranchUser()) {

            $salons = Salon::where(
                    'id',
                    $this->currentSalonId()
                )
                ->where(
                    'status',
                    true
                )
                ->get();

            $branches = Branch::where(
                    'id',
                    $this->currentBranchId()
                )
                ->where(
                    'salon_id',
                    $this->currentSalonId()
                )
                ->where(
                    'status',
                    true
                )
                ->get();

        } else {

            $salons = Salon::where(
                    'status',
                    true
                )
                ->orderBy('name')
                ->get();

            $branches = Branch::where(
                    'status',
                    true
                )
                ->orderBy('name')
                ->get();
        }

        return view(
            'admin.staff.create',
            compact(
                'salons',
                'branches'
            )
        );
    }


    /**
     * Store new staff.
     */
    public function store(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Force Current Branch
        |--------------------------------------------------------------------------
        */

        if ($this->isBranchUser()) {

            $request->merge([
                'salon_id' => $this->currentSalonId(),
                'branch_id' => $this->currentBranchId(),
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Validate Staff
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
                        )
                        ->where(
                            'status',
                            true
                        );

                    }),
            ],

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            /*
            |--------------------------------------------------------------------------
            | EMAIL VALIDATION
            |--------------------------------------------------------------------------
            */

            'email' => [
                'nullable',
                'email:rfc',
                'max:255',
            ],

            /*
            |--------------------------------------------------------------------------
            | PHONE VALIDATION
            |--------------------------------------------------------------------------
            */

            'phone' => [
                'nullable',
                'regex:/^[6-9][0-9]{9}$/',
            ],

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

            'role' => [
                'required',
                'string',
                'max:255',
            ],

            'specialization' => [
                'nullable',
                'string',
                'max:255',
            ],

            'salary' => [
                'required',
                'numeric',
                'min:0',
            ],

            'commission_type' => [
                'required',
                'in:percentage,fixed',
            ],

            'commission_value' => [
                'required',
                'numeric',
                'min:0',
            ],

            'joining_date' => [
                'nullable',
                'date',
            ],

            'status' => [
                'required',
                'boolean',
            ],

            'profile_image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

        ], [

            /*
            |--------------------------------------------------------------------------
            | CUSTOM EMAIL MESSAGES
            |--------------------------------------------------------------------------
            */

            'email.email' =>
                'Please enter a valid email address.',

            'email.max' =>
                'Email address cannot exceed 255 characters.',


            /*
            |--------------------------------------------------------------------------
            | CUSTOM PHONE MESSAGE
            |--------------------------------------------------------------------------
            */

            'phone.regex' =>
                'Please enter a valid 10-digit Indian mobile number starting with 6, 7, 8 or 9.',

        ]);


        /*
        |--------------------------------------------------------------------------
        | Security Check
        |--------------------------------------------------------------------------
        */

        if (
            $this->isBranchUser() &&
            (
                (int) $validated['salon_id'] !== $this->currentSalonId() ||
                (int) $validated['branch_id'] !== $this->currentBranchId()
            )
        ) {
            abort(
                403,
                'You cannot create staff for another branch.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Profile Image
        |--------------------------------------------------------------------------
        */

        unset($validated['profile_image']);


        /*
        |--------------------------------------------------------------------------
        | Create Staff
        |--------------------------------------------------------------------------
        */

        Staff::create($validated);


        return redirect()
            ->route('admin.staff.index')
            ->with(
                'success',
                'Staff member added successfully.'
            );
    }


    /**
     * Show edit staff form.
     */
    public function edit(Staff $staff)
    {
        $this->checkBranchAccess($staff);

        if ($this->isBranchUser()) {

            $salons = Salon::where(
                    'id',
                    $this->currentSalonId()
                )
                ->where(
                    'status',
                    true
                )
                ->get();

            $branches = Branch::where(
                    'id',
                    $this->currentBranchId()
                )
                ->where(
                    'salon_id',
                    $this->currentSalonId()
                )
                ->where(
                    'status',
                    true
                )
                ->get();

        } else {

            $salons = Salon::where(
                    'status',
                    true
                )
                ->orderBy('name')
                ->get();

            $branches = Branch::where(
                    'status',
                    true
                )
                ->orderBy('name')
                ->get();
        }

        return view(
            'admin.staff.edit',
            compact(
                'staff',
                'salons',
                'branches'
            )
        );
    }


    /**
     * Update staff.
     */
    public function update(
        Request $request,
        Staff $staff
    ) {
        $this->checkBranchAccess($staff);


        /*
        |--------------------------------------------------------------------------
        | Force Current Branch
        |--------------------------------------------------------------------------
        */

        if ($this->isBranchUser()) {

            $request->merge([
                'salon_id' => $this->currentSalonId(),
                'branch_id' => $this->currentBranchId(),
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Validate Staff
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
                        )
                        ->where(
                            'status',
                            true
                        );

                    }),
            ],

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            /*
            |--------------------------------------------------------------------------
            | EMAIL VALIDATION
            |--------------------------------------------------------------------------
            */

            'email' => [
                'nullable',
                'email:rfc',
                'max:255',
            ],

            /*
            |--------------------------------------------------------------------------
            | PHONE VALIDATION
            |--------------------------------------------------------------------------
            */

            'phone' => [
                'nullable',
                'regex:/^[6-9][0-9]{9}$/',
            ],

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

            'role' => [
                'required',
                'string',
                'max:255',
            ],

            'specialization' => [
                'nullable',
                'string',
                'max:255',
            ],

            'salary' => [
                'required',
                'numeric',
                'min:0',
            ],

            'commission_type' => [
                'required',
                'in:percentage,fixed',
            ],

            'commission_value' => [
                'required',
                'numeric',
                'min:0',
            ],

            'joining_date' => [
                'nullable',
                'date',
            ],

            'status' => [
                'required',
                'boolean',
            ],

            'profile_image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

        ], [

            /*
            |--------------------------------------------------------------------------
            | CUSTOM EMAIL MESSAGES
            |--------------------------------------------------------------------------
            */

            'email.email' =>
                'Please enter a valid email address.',

            'email.max' =>
                'Email address cannot exceed 255 characters.',


            /*
            |--------------------------------------------------------------------------
            | CUSTOM PHONE MESSAGE
            |--------------------------------------------------------------------------
            */

            'phone.regex' =>
                'Please enter a valid 10-digit Indian mobile number starting with 6, 7, 8 or 9.',

        ]);


        /*
        |--------------------------------------------------------------------------
        | Security Check
        |--------------------------------------------------------------------------
        */

        if (
            $this->isBranchUser() &&
            (
                (int) $validated['salon_id'] !== $this->currentSalonId() ||
                (int) $validated['branch_id'] !== $this->currentBranchId()
            )
        ) {
            abort(
                403,
                'You cannot move staff to another branch.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Profile Image
        |--------------------------------------------------------------------------
        */

        unset($validated['profile_image']);


        /*
        |--------------------------------------------------------------------------
        | Update Staff
        |--------------------------------------------------------------------------
        */

        $staff->update($validated);


        return redirect()
            ->route('admin.staff.index')
            ->with(
                'success',
                'Staff member updated successfully.'
            );
    }


    /**
     * Delete staff.
     */
    public function destroy(Staff $staff)
    {
        $this->checkBranchAccess($staff);

        $staff->delete();

        return redirect()
            ->route('admin.staff.index')
            ->with(
                'success',
                'Staff member deleted successfully.'
            );
    }


    /**
     * Toggle staff status.
     */
    public function toggleStatus(Staff $staff)
    {
        $this->checkBranchAccess($staff);

        $staff->update([
            'status' => !$staff->status,
        ]);

        return redirect()
            ->route('admin.staff.index')
            ->with(
                'success',
                'Staff status updated successfully.'
            );
    }
}