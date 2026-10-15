<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MembershipPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MembershipPlanController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $user = Auth::user();

        $salon = $user->salon;

        if (!$salon) {
            abort(403, 'Your account is not linked to a salon.');
        }

        $plans = MembershipPlan::where('salon_id', $salon->id)
            ->latest()
            ->get();

        /*
        |--------------------------------------------------------------------------
        | AUTOMATICALLY DEACTIVATE EXPIRED PLANS
        |--------------------------------------------------------------------------
        */

        foreach ($plans as $plan) {

            if ($plan->is_expired && $plan->status) {

                $plan->update([
                    'status' => false,
                ]);
            }
        }

        return view(
            'admin.membership.plans.index',
            compact('plans')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        return view('admin.membership.plans.create');
    }


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $user = Auth::user();

        $salon = $user->salon;

        if (!$salon) {
            abort(403, 'Your account is not linked to a salon.');
        }

        $validated = $request->validate([

            'name' => 'required|string|max:255',

            'code' => 'nullable|string|max:100',

            'description' => 'nullable|string',

            'billing_cycle' =>
                'required|in:monthly,quarterly,yearly',

            'price' =>
                'required|numeric|min:0',

            'validity_days' =>
                'required|integer|min:1',

            'discount_type' =>
                'required|in:percentage,fixed,none',

            'discount_value' => [
                'required',
                'numeric',
                'min:0',
            ],

            'status' =>
                'nullable|boolean',
        ]);


        /*
        |--------------------------------------------------------------------------
        | VALIDATE PERCENTAGE DISCOUNT
        |--------------------------------------------------------------------------
        */

        if (
            $validated['discount_type'] === 'percentage'
            &&
            $validated['discount_value'] > 100
        ) {

            return back()
                ->withInput()
                ->withErrors([
                    'discount_value' =>
                        'Percentage discount cannot exceed 100%.'
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDATE FIXED DISCOUNT
        |--------------------------------------------------------------------------
        */

        if (
            $validated['discount_type'] === 'fixed'
            &&
            $validated['discount_value'] > $validated['price']
        ) {

            return back()
                ->withInput()
                ->withErrors([
                    'discount_value' =>
                        'Fixed discount cannot be greater than the plan price.'
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | CREATE PLAN
        |--------------------------------------------------------------------------
        */

        MembershipPlan::create([

            'salon_id' =>
                $salon->id,

            'name' =>
                $validated['name'],

            'code' =>
                $validated['code'] ?? null,

            'description' =>
                $validated['description'] ?? null,

            'billing_cycle' =>
                $validated['billing_cycle'],

            'price' =>
                $validated['price'],

            'validity_days' =>
                $validated['validity_days'],

            'discount_type' =>
                $validated['discount_type'],

            'discount_value' =>
                $validated['discount_value'],

            'status' =>
                $request->boolean('status'),
        ]);


        return redirect()
            ->route('admin.membership.plans.index')
            ->with(
                'success',
                'Membership plan created successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(MembershipPlan $membershipPlan)
    {
        $user = Auth::user();

        $salon = $user->salon;

        if (
            !$salon ||
            $membershipPlan->salon_id !== $salon->id
        ) {
            abort(403);
        }

        return view(
            'admin.membership.plans.edit',
            compact('membershipPlan')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        MembershipPlan $membershipPlan
    ) {

        $user = Auth::user();

        $salon = $user->salon;

        if (
            !$salon ||
            $membershipPlan->salon_id !== $salon->id
        ) {
            abort(403);
        }


        $validated = $request->validate([

            'name' =>
                'required|string|max:255',

            'code' =>
                'nullable|string|max:100',

            'description' =>
                'nullable|string',

            'billing_cycle' =>
                'required|in:monthly,quarterly,yearly',

            'price' =>
                'required|numeric|min:0',

            'validity_days' =>
                'required|integer|min:1',

            'discount_type' =>
                'required|in:percentage,fixed,none',

            'discount_value' =>
                'required|numeric|min:0',
        ]);


        /*
        |--------------------------------------------------------------------------
        | VALIDATE PERCENTAGE DISCOUNT
        |--------------------------------------------------------------------------
        */

        if (
            $validated['discount_type'] === 'percentage'
            &&
            $validated['discount_value'] > 100
        ) {

            return back()
                ->withInput()
                ->withErrors([
                    'discount_value' =>
                        'Percentage discount cannot exceed 100%.'
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDATE FIXED DISCOUNT
        |--------------------------------------------------------------------------
        */

        if (
            $validated['discount_type'] === 'fixed'
            &&
            $validated['discount_value'] > $validated['price']
        ) {

            return back()
                ->withInput()
                ->withErrors([
                    'discount_value' =>
                        'Fixed discount cannot be greater than the plan price.'
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | UPDATE PLAN
        |--------------------------------------------------------------------------
        */

        $membershipPlan->update([

            'name' =>
                $validated['name'],

            'code' =>
                $validated['code'] ?? null,

            'description' =>
                $validated['description'] ?? null,

            'billing_cycle' =>
                $validated['billing_cycle'],

            'price' =>
                $validated['price'],

            'validity_days' =>
                $validated['validity_days'],

            'discount_type' =>
                $validated['discount_type'],

            'discount_value' =>
                $validated['discount_value'],

            'status' =>
                $request->boolean('status'),
        ]);


        return redirect()
            ->route('admin.membership.plans.index')
            ->with(
                'success',
                'Membership plan updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy(MembershipPlan $membershipPlan)
    {
        $user = Auth::user();

        $salon = $user->salon;

        if (
            !$salon ||
            $membershipPlan->salon_id !== $salon->id
        ) {
            abort(403);
        }

        $membershipPlan->delete();

        return redirect()
            ->route('admin.membership.plans.index')
            ->with(
                'success',
                'Membership plan deleted successfully.'
            );
    }
}

