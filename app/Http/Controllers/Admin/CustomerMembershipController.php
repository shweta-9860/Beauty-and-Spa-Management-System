<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerMembership;
use App\Models\MembershipPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CustomerMembershipController extends Controller
{
    /**
     * Display customer memberships.
     */
    public function index()
    {
        $user = auth()->user();

        $salon = $user->salon;

        if (!$salon) {
            abort(403, 'Salon not found.');
        }

        /*
        |--------------------------------------------------------------------------
        | Mark expired memberships
        |--------------------------------------------------------------------------
        */

        CustomerMembership::where('salon_id', $salon->id)
            ->where('status', 'active')
            ->whereDate('expiry_date', '<', today())
            ->update([
                'status' => 'expired',
            ]);


        /*
        |--------------------------------------------------------------------------
        | Customer Memberships
        |--------------------------------------------------------------------------
        | Memberships are branch-wise.
        |
        | Membership Plans remain salon-wide.
        |--------------------------------------------------------------------------
        */

        $membershipsQuery = CustomerMembership::with([
            'customer',
            'membershipPlan',
            'branch',
        ])
        ->where('salon_id', $salon->id);

        /*
        |--------------------------------------------------------------------------
        | Branch-wise Membership Filter
        |--------------------------------------------------------------------------
        */

        if (!empty($user->branch_id)) {
            $membershipsQuery->where(
                'branch_id',
                $user->branch_id
            );
        }

        $memberships = $membershipsQuery
            ->latest()
            ->paginate(10);


        /*
        |--------------------------------------------------------------------------
        | Customers
        |--------------------------------------------------------------------------
        | Customers are branch-wise.
        |--------------------------------------------------------------------------
        */

        $customersQuery = Customer::where(
            'salon_id',
            $salon->id
        );

        if (!empty($user->branch_id)) {
            $customersQuery->where(
                'branch_id',
                $user->branch_id
            );
        }

        $customers = $customersQuery
            ->orderBy('name')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Membership Plans
        |--------------------------------------------------------------------------
        | IMPORTANT:
        | No branch_id filter here.
        | Plans are available across all branches.
        |--------------------------------------------------------------------------
        */

        $membershipPlans = MembershipPlan::where(
            'salon_id',
            $salon->id
        )
        ->orderBy('name')
        ->get();


        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */

        return view(
            'admin.membership.customers.index',
            compact(
                'memberships',
                'customers',
                'membershipPlans'
            )
        );
    }


    /**
     * Show create form.
     */
    public function create()
    {
        $user = auth()->user();

        $salon = $user->salon;

        if (!$salon) {
            abort(403, 'Salon not found.');
        }


        /*
        |--------------------------------------------------------------------------
        | Customers - Branch Wise
        |--------------------------------------------------------------------------
        */

        $customersQuery = Customer::where(
            'salon_id',
            $salon->id
        );

        if (!empty($user->branch_id)) {
            $customersQuery->where(
                'branch_id',
                $user->branch_id
            );
        }

        $customers = $customersQuery
            ->orderBy('name')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Membership Plans
        |--------------------------------------------------------------------------
        | Plans are salon-wide.
        |--------------------------------------------------------------------------
        */

        $membershipPlans = MembershipPlan::where(
            'salon_id',
            $salon->id
        )
        ->where('status', 'active')
        ->orderBy('name')
        ->get();


        return view(
            'admin.membership.customers.create',
            compact(
                'customers',
                'membershipPlans'
            )
        );
    }


    /**
     * Store customer membership.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $salon = $user->salon;

        if (!$salon) {
            abort(403, 'Your account is not linked to a salon.');
        }


        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'customer_id' => [
                'required',
                'integer',
                'exists:customers,id'
            ],

            'membership_plan_id' => [
                'required',
                'integer',
                'exists:membership_plans,id'
            ],

            'start_date' => [
                'required',
                'date'
            ],

            'paid_amount' => [
                'required',
                'numeric',
                'min:0'
            ],

            'discount' => [
                'nullable',
                'numeric',
                'min:0'
            ],

            'status' => [
                'required',
                'in:pending,active,expired,cancelled'
            ],

            'notes' => [
                'nullable',
                'string'
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Verify Customer belongs to current salon + branch
        |--------------------------------------------------------------------------
        */

        $customerQuery = Customer::where(
            'id',
            $validated['customer_id']
        )
        ->where(
            'salon_id',
            $salon->id
        );

        if (!empty($user->branch_id)) {
            $customerQuery->where(
                'branch_id',
                $user->branch_id
            );
        }

        $customer = $customerQuery->first();


        if (!$customer) {
            return back()
                ->withInput()
                ->withErrors([
                    'customer_id' =>
                        'Selected customer does not belong to this salon or branch.'
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Verify Membership Plan belongs to current salon
        |--------------------------------------------------------------------------
        |
        | NO branch filter.
        |
        */

        $plan = MembershipPlan::where(
            'id',
            $validated['membership_plan_id']
        )
        ->where(
            'salon_id',
            $salon->id
        )
        ->where(
            'status',
            'active'
        )
        ->first();


        if (!$plan) {
            return back()
                ->withInput()
                ->withErrors([
                    'membership_plan_id' =>
                        'Selected membership plan is invalid.'
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Calculate dates
        |--------------------------------------------------------------------------
        */

        $startDate = Carbon::parse(
            $validated['start_date']
        );

        $validityDays = max(
            1,
            (int) $plan->validity_days
        );

        $expiryDate = $startDate
            ->copy()
            ->addDays($validityDays - 1);


        /*
        |--------------------------------------------------------------------------
        | Calculate price
        |--------------------------------------------------------------------------
        */

        $price = (float) $plan->price;

        $discount = (float) (
            $validated['discount'] ?? 0
        );


        if ($discount > $price) {
            return back()
                ->withInput()
                ->withErrors([
                    'discount' =>
                        'Discount cannot be greater than membership amount.'
                ]);
        }


        $finalAmount = max(
            0,
            $price - $discount
        );


        /*
        |--------------------------------------------------------------------------
        | Paid Amount Validation
        |--------------------------------------------------------------------------
        */

        if ((float) $validated['paid_amount'] > $finalAmount) {
            return back()
                ->withInput()
                ->withErrors([
                    'paid_amount' =>
                        'Paid amount cannot be greater than membership amount.'
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Determine Branch
        |--------------------------------------------------------------------------
        |
        | For branch user:
        |     use logged-in user's branch.
        |
        | For salon-level user:
        |     use selected customer's branch.
        |--------------------------------------------------------------------------
        */

        $branchId = $customer->branch_id;

        if (!empty($user->branch_id)) {
            $branchId = $user->branch_id;
        }


        /*
        |--------------------------------------------------------------------------
        | Create Membership
        |--------------------------------------------------------------------------
        */

        CustomerMembership::create([
            'salon_id' => $salon->id,

            'branch_id' => $branchId,

            'customer_id' =>
                $validated['customer_id'],

            'membership_plan_id' =>
                $validated['membership_plan_id'],

            'start_date' =>
                $startDate->toDateString(),

            'expiry_date' =>
                $expiryDate->toDateString(),

            'price' =>
                $price,

            'discount' =>
                $discount,

            'paid_amount' =>
                $validated['paid_amount'],

            'status' =>
                $validated['status'],

            'renewal_date' =>
                null,

            'notes' =>
                $validated['notes'] ?? null,
        ]);


        return redirect()
            ->route('admin.membership.customers.index')
            ->with(
                'success',
                'Customer membership added successfully.'
            );
    }


    /**
     * Show edit form.
     */
    public function edit(CustomerMembership $customerMembership)
    {
        $user = Auth::user();

        $salonId = $user->salon_id;


        /*
        |--------------------------------------------------------------------------
        | Verify Membership belongs to current salon
        |--------------------------------------------------------------------------
        */

        if (
            $customerMembership->salon_id != $salonId
        ) {
            abort(403);
        }


        /*
        |--------------------------------------------------------------------------
        | Verify Membership belongs to current branch
        |--------------------------------------------------------------------------
        */

        if (
            !empty($user->branch_id) &&
            $customerMembership->branch_id != $user->branch_id
        ) {
            abort(
                403,
                'You cannot edit membership from another branch.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Customers - Branch Wise
        |--------------------------------------------------------------------------
        */

        $customersQuery = Customer::where(
            'salon_id',
            $salonId
        );

        if (!empty($user->branch_id)) {
            $customersQuery->where(
                'branch_id',
                $user->branch_id
            );
        }

        $customers = $customersQuery
            ->orderBy('name')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Membership Plans
        |--------------------------------------------------------------------------
        | Plans are salon-wide.
        |--------------------------------------------------------------------------
        */

        $membershipPlans = MembershipPlan::where(
            'salon_id',
            $salonId
        )
        ->where('status', 'active')
        ->orderBy('name')
        ->get();


        /*
        |--------------------------------------------------------------------------
        | Edit View
        |--------------------------------------------------------------------------
        */

        return view(
            'admin.membership.customers.edit',
            compact(
                'customerMembership',
                'customers',
                'membershipPlans'
            )
        );
    }


    /**
     * Update customer membership.
     */
    public function update(
        Request $request,
        CustomerMembership $customerMembership
    ) {
        $user = Auth::user();

        $salon = $user->salon;


        /*
        |--------------------------------------------------------------------------
        | Salon Check
        |--------------------------------------------------------------------------
        */

        if (
            !$salon ||
            $customerMembership->salon_id !== $salon->id
        ) {
            abort(403);
        }


        /*
        |--------------------------------------------------------------------------
        | Branch Check
        |--------------------------------------------------------------------------
        */

        if (
            !empty($user->branch_id) &&
            $customerMembership->branch_id != $user->branch_id
        ) {
            abort(
                403,
                'You cannot update membership from another branch.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'customer_id' => [
                'required',
                'integer',
                'exists:customers,id'
            ],

            'membership_plan_id' => [
                'required',
                'integer',
                'exists:membership_plans,id'
            ],

            'start_date' => [
                'required',
                'date'
            ],

            'paid_amount' => [
                'required',
                'numeric',
                'min:0'
            ],

            'discount' => [
                'nullable',
                'numeric',
                'min:0'
            ],

            'status' => [
                'required',
                'in:pending,active,expired,cancelled'
            ],

            'notes' => [
                'nullable',
                'string'
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Verify Customer - Salon + Branch
        |--------------------------------------------------------------------------
        */

        $customerQuery = Customer::where(
            'id',
            $validated['customer_id']
        )
        ->where(
            'salon_id',
            $salon->id
        );

        if (!empty($user->branch_id)) {
            $customerQuery->where(
                'branch_id',
                $user->branch_id
            );
        }

        $customer = $customerQuery->first();


        if (!$customer) {
            return back()
                ->withInput()
                ->withErrors([
                    'customer_id' =>
                        'Selected customer does not belong to this salon or branch.'
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Verify Membership Plan
        |--------------------------------------------------------------------------
        |
        | NO branch filter.
        |--------------------------------------------------------------------------
        */

        $plan = MembershipPlan::where(
            'id',
            $validated['membership_plan_id']
        )
        ->where(
            'salon_id',
            $salon->id
        )
        ->where(
            'status',
            'active'
        )
        ->first();


        if (!$plan) {
            return back()
                ->withInput()
                ->withErrors([
                    'membership_plan_id' =>
                        'Selected membership plan is invalid.'
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Calculate Dates
        |--------------------------------------------------------------------------
        */

        $startDate = Carbon::parse(
            $validated['start_date']
        );

        $validityDays = max(
            1,
            (int) $plan->validity_days
        );

        $expiryDate = $startDate
            ->copy()
            ->addDays($validityDays - 1);


        /*
        |--------------------------------------------------------------------------
        | Calculate Amounts
        |--------------------------------------------------------------------------
        */

        /*
        | IMPORTANT:
        | Use selected membership plan price.
        */

        $price = round(
            (float) $plan->price,
            2
        );

        $discount = round(
            (float) ($validated['discount'] ?? 0),
            2
        );


        /*
        |--------------------------------------------------------------------------
        | Discount Validation
        |--------------------------------------------------------------------------
        */

        if ($discount > $price) {
            return back()
                ->withInput()
                ->withErrors([
                    'discount' =>
                        'Discount cannot be greater than membership amount.'
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Final Membership Amount
        |--------------------------------------------------------------------------
        */

        $finalAmount = round(
            max(
                0,
                $price - $discount
            ),
            2
        );


        /*
        |--------------------------------------------------------------------------
        | Paid Amount
        |--------------------------------------------------------------------------
        */

        $paidAmount = round(
            (float) $validated['paid_amount'],
            2
        );


        /*
        |--------------------------------------------------------------------------
        | Paid Amount Validation
        |--------------------------------------------------------------------------
        */

        if (
            $paidAmount >
            ($finalAmount + 0.01)
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'paid_amount' =>
                        'Paid amount cannot be greater than membership amount.'
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Branch ID
        |--------------------------------------------------------------------------
        */

        $branchId = $customer->branch_id;

        if (!empty($user->branch_id)) {
            $branchId = $user->branch_id;
        }


        /*
        |--------------------------------------------------------------------------
        | Update Membership
        |--------------------------------------------------------------------------
        */

        $customerMembership->update([

            'branch_id' =>
                $branchId,

            'customer_id' =>
                $validated['customer_id'],

            'membership_plan_id' =>
                $validated['membership_plan_id'],

            'start_date' =>
                $startDate->toDateString(),

            'expiry_date' =>
                $expiryDate->toDateString(),

            'price' =>
                $price,

            'discount' =>
                $discount,

            'paid_amount' =>
                $paidAmount,

            'status' =>
                $validated['status'],

            'notes' =>
                $validated['notes'] ?? null,
        ]);


        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('admin.membership.customers.index')
            ->with(
                'success',
                'Customer membership updated successfully.'
            );
    }


    /**
     * Delete membership.
     */
    public function destroy(
        CustomerMembership $customerMembership
    ) {
        $user = Auth::user();

        $salon = $user->salon;


        /*
        |--------------------------------------------------------------------------
        | Salon Check
        |--------------------------------------------------------------------------
        */

        if (
            !$salon ||
            $customerMembership->salon_id !== $salon->id
        ) {
            abort(403);
        }


        /*
        |--------------------------------------------------------------------------
        | Branch Check
        |--------------------------------------------------------------------------
        */

        if (
            !empty($user->branch_id) &&
            $customerMembership->branch_id != $user->branch_id
        ) {
            abort(
                403,
                'You cannot delete membership from another branch.'
            );
        }


        $customerMembership->delete();


        return redirect()
            ->route('admin.membership.customers.index')
            ->with(
                'success',
                'Customer membership deleted successfully.'
            );
    }


    /**
     * Display assigned members branch-wise.
     */
    public function assignedMembers(Request $request)
    {
        $user = auth()->user();

        $salon = $user->salon;

        if (!$salon) {
            abort(403, 'Salon not found.');
        }


        /*
        |--------------------------------------------------------------------------
        | Assigned Customer Memberships
        |--------------------------------------------------------------------------
        */

        $query = CustomerMembership::with([
            'customer',
            'membershipPlan',
            'branch',
        ])
        ->where(
            'salon_id',
            $salon->id
        );


        /*
        |--------------------------------------------------------------------------
        | Branch-wise Filter
        |--------------------------------------------------------------------------
        |
        | This is the main fix.
        |
        | Branch A will see only Branch A memberships.
        | Branch B will see only Branch B memberships.
        |--------------------------------------------------------------------------
        */

        if (!empty($user->branch_id)) {
            $query->where(
                'branch_id',
                $user->branch_id
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Customer Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = $request->search;

            $query->whereHas(
                'customer',
                function ($q) use ($search) {

                    $q->where(
                        'name',
                        'like',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'phone',
                        'like',
                        '%' . $search . '%'
                    );
                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Membership Plan Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('membership_plan_id')) {

            $query->where(
                'membership_plan_id',
                $request->membership_plan_id
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
        | Pagination
        |--------------------------------------------------------------------------
        */

        $assignedMembers = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | Membership Plans
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        | No branch_id filter.
        | All plans of this salon will be available.
        |--------------------------------------------------------------------------
        */

        $membershipPlans = MembershipPlan::where(
            'salon_id',
            $salon->id
        )
        ->orderBy('name')
        ->get();


        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */

        return view(
            'admin.membership.assigned-members',
            compact(
                'assignedMembers',
                'membershipPlans'
            )
        );
    }
}