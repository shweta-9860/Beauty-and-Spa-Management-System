<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MembershipBenefit;
use App\Models\MembershipPlan;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MembershipBenefitController extends Controller
{
    public function index(MembershipPlan $membershipPlan)
    {
        $user = Auth::user();

        if (
            !$user->salon ||
            $membershipPlan->salon_id !== $user->salon->id
        ) {
            abort(403);
        }

        $benefits = $membershipPlan->benefits()
            ->with('service')
            ->latest()
            ->get();

        return view(
            'admin.membership.benefits.index',
            compact('membershipPlan', 'benefits')
        );
    }


    public function create(MembershipPlan $membershipPlan)
    {
        $user = Auth::user();

        if (
            !$user->salon ||
            $membershipPlan->salon_id !== $user->salon->id
        ) {
            abort(403);
        }

        $services = Service::where('salon_id', $user->salon->id)
            ->where('status', true)
            ->orderBy('name')
            ->get();

        return view(
            'admin.membership.benefits.create',
            compact('membershipPlan', 'services')
        );
    }


    public function store(
        Request $request,
        MembershipPlan $membershipPlan
    ) {
        $user = Auth::user();

        if (
            !$user->salon ||
            $membershipPlan->salon_id !== $user->salon->id
        ) {
            abort(403);
        }

        $validated = $request->validate([
            'benefit_type' => [
                'required',
                'in:discount,free_service,priority_booking,custom'
            ],

            'service_id' => [
                'nullable',
                'integer',
                'exists:services,id'
            ],

            'discount_type' => [
                'required',
                'in:percentage,fixed,none'
            ],

            'discount_value' => [
                'required',
                'numeric',
                'min:0'
            ],

            'free_uses' => [
                'nullable',
                'integer',
                'min:0'
            ],

            'title' => [
                'nullable',
                'string',
                'max:255'
            ],

            'description' => [
                'nullable',
                'string'
            ],
        ]);

        if (
            $validated['discount_type'] === 'percentage' &&
            $validated['discount_value'] > 100
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'discount_value' =>
                        'Percentage discount cannot exceed 100%.'
                ]);
        }

        if (
            !empty($validated['service_id']) &&
            !Service::where('id', $validated['service_id'])
                ->where('salon_id', $user->salon->id)
                ->exists()
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'service_id' =>
                        'Selected service does not belong to this salon.'
                ]);
        }

        MembershipBenefit::create([
            'membership_plan_id' => $membershipPlan->id,
            'benefit_type' => $validated['benefit_type'],
            'service_id' => $validated['service_id'] ?? null,
            'discount_type' => $validated['discount_type'],
            'discount_value' => $validated['discount_value'],
            'free_uses' => $validated['free_uses'] ?? 0,
            'title' => $validated['title'] ?? null,
            'description' => $validated['description'] ?? null,
            'status' => $request->boolean('status'),
        ]);

        return redirect()
            ->route(
                'admin.membership.benefits.index',
                $membershipPlan
            )
            ->with(
                'success',
                'Membership benefit created successfully.'
            );
    }


    public function edit(MembershipBenefit $membershipBenefit)
    {
        $user = Auth::user();

        $membershipPlan = $membershipBenefit->membershipPlan;

        if (
            !$user->salon ||
            !$membershipPlan ||
            $membershipPlan->salon_id !== $user->salon->id
        ) {
            abort(403);
        }

        $services = Service::where('salon_id', $user->salon->id)
            ->where('status', true)
            ->orderBy('name')
            ->get();

        return view(
            'admin.membership.benefits.edit',
            compact(
                'membershipBenefit',
                'membershipPlan',
                'services'
            )
        );
    }


    public function update(
        Request $request,
        MembershipBenefit $membershipBenefit
    ) {
        $user = Auth::user();

        $membershipPlan = $membershipBenefit->membershipPlan;

        if (
            !$user->salon ||
            !$membershipPlan ||
            $membershipPlan->salon_id !== $user->salon->id
        ) {
            abort(403);
        }

        $validated = $request->validate([
            'benefit_type' => [
                'required',
                'in:discount,free_service,priority_booking,custom'
            ],

            'service_id' => [
                'nullable',
                'integer',
                'exists:services,id'
            ],

            'discount_type' => [
                'required',
                'in:percentage,fixed,none'
            ],

            'discount_value' => [
                'required',
                'numeric',
                'min:0'
            ],

            'free_uses' => [
                'nullable',
                'integer',
                'min:0'
            ],

            'title' => [
                'nullable',
                'string',
                'max:255'
            ],

            'description' => [
                'nullable',
                'string'
            ],
        ]);

        if (
            $validated['discount_type'] === 'percentage' &&
            $validated['discount_value'] > 100
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'discount_value' =>
                        'Percentage discount cannot exceed 100%.'
                ]);
        }

        if (
            !empty($validated['service_id']) &&
            !Service::where('id', $validated['service_id'])
                ->where('salon_id', $user->salon->id)
                ->exists()
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'service_id' =>
                        'Selected service does not belong to this salon.'
                ]);
        }

        $membershipBenefit->update([
            'benefit_type' => $validated['benefit_type'],
            'service_id' => $validated['service_id'] ?? null,
            'discount_type' => $validated['discount_type'],
            'discount_value' => $validated['discount_value'],
            'free_uses' => $validated['free_uses'] ?? 0,
            'title' => $validated['title'] ?? null,
            'description' => $validated['description'] ?? null,
            'status' => $request->boolean('status'),
        ]);

        return redirect()
            ->route(
                'admin.membership.benefits.index',
                $membershipPlan
            )
            ->with(
                'success',
                'Membership benefit updated successfully.'
            );
    }


    public function destroy(
        MembershipBenefit $membershipBenefit
    ) {
        $user = Auth::user();

        $membershipPlan = $membershipBenefit->membershipPlan;

        if (
            !$user->salon ||
            !$membershipPlan ||
            $membershipPlan->salon_id !== $user->salon->id
        ) {
            abort(403);
        }

        $membershipBenefit->delete();

        return redirect()
            ->route(
                'admin.membership.benefits.index',
                $membershipPlan
            )
            ->with(
                'success',
                'Membership benefit deleted successfully.'
            );
    }
}

