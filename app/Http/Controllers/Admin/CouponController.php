<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CouponController extends Controller
{
    
    /**
     * Display coupons.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Coupon::with('branch')
            ->where('salon_id', $user->salon_id);

        // Logged-in branch isolation
        if (session()->has('branch_id')) {

    $branchId = session('branch_id');

    $query->where(function ($q) use ($branchId) {

        $q->where('branch_id', $branchId)
          ->orWhereNull('branch_id');

    });
}
        // Search
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $coupons = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view(
            'admin.offers.coupons.index',
            compact('coupons')
        );
    }


    /**
     * Show create form.
     */
    public function create()
    {
        $user = Auth::user();

        $branches = Branch::where('salon_id', $user->salon_id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        return view(
            'admin.offers.coupons.create',
            compact('branches')
        );
    }


    /**
     * Store coupon.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([

            'code' => [
                'required',
                'string',
                'max:50',
                'regex:/^[A-Za-z0-9_-]+$/',
                Rule::unique('coupons', 'code')
                    ->where(function ($query) use ($user) {
                        return $query->where(
                            'salon_id',
                            $user->salon_id
                        );
                    }),
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'branch_id' => [
                'nullable',
                'integer',
                'exists:branches,id',
            ],

            'discount_type' => [
                'required',
                'in:percentage,fixed',
            ],

            'discount_value' => [
                'required',
                'numeric',
                'min:0.01',
            ],

            'minimum_bill_amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'maximum_discount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            // DATE ONLY
            'valid_from' => [
                'nullable',
                'date',
            ],

            'valid_until' => [
                'nullable',
                'date',
                'after_or_equal:valid_from',
            ],

            'usage_limit' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'per_customer_limit' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'status' => [
                'required',
                'in:active,inactive',
            ],
        ]);


        // Percentage validation
        if (
            $validated['discount_type'] === 'percentage' &&
            $validated['discount_value'] > 100
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'discount_value' =>
                        'Percentage discount cannot exceed 100%.',
                ]);
        }


        // Validate branch belongs to current salon
        if (!empty($validated['branch_id'])) {

            $branchExists = Branch::where('id', $validated['branch_id'])
                ->where('salon_id', $user->salon_id)
                ->exists();

            if (!$branchExists) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'branch_id' =>
                            'Invalid branch selected.',
                    ]);
            }
        }


        Coupon::create([

            'salon_id' => $user->salon_id,

            'branch_id' =>
                $validated['branch_id'] ?? null,

            'code' =>
                strtoupper($validated['code']),

            'description' =>
                $validated['description'] ?? null,

            'discount_type' =>
                $validated['discount_type'],

            'discount_value' =>
                $validated['discount_value'],

            'minimum_bill_amount' =>
                $validated['minimum_bill_amount'] ?? 0,

            'maximum_discount' =>
                $validated['maximum_discount'] ?? null,

            // DATE ONLY
            'valid_from' =>
                !empty($validated['valid_from'])
                    ? date('Y-m-d', strtotime($validated['valid_from']))
                    : null,

            'valid_until' =>
                !empty($validated['valid_until'])
                    ? date('Y-m-d', strtotime($validated['valid_until']))
                    : null,

            'usage_limit' =>
                $validated['usage_limit'] ?? null,

            'used_count' =>
                0,

            'per_customer_limit' =>
                $validated['per_customer_limit'] ?? null,

            'status' =>
                $validated['status'],
        ]);


        return redirect()
            ->route('admin.offers.coupons.index')
            ->with(
                'success',
                'Coupon created successfully.'
            );
    }


    /**
     * Show edit form.
     */
    public function edit(Coupon $coupon)
    {
        $this->authorizeCoupon($coupon);

        $user = Auth::user();

        $branches = Branch::where('salon_id', $user->salon_id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        return view(
            'admin.offers.coupons.edit',
            compact('coupon', 'branches')
        );
    }


    /**
     * Update coupon.
     */
    public function update(Request $request, Coupon $coupon)
    {
        $this->authorizeCoupon($coupon);

        $user = Auth::user();

        $validated = $request->validate([

            'code' => [
                'required',
                'string',
                'max:50',
                'regex:/^[A-Za-z0-9_-]+$/',

                Rule::unique('coupons', 'code')
                    ->where(function ($query) use ($user) {
                        return $query->where(
                            'salon_id',
                            $user->salon_id
                        );
                    })
                    ->ignore($coupon->id),
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'branch_id' => [
                'nullable',
                'integer',
                'exists:branches,id',
            ],

            'discount_type' => [
                'required',
                'in:percentage,fixed',
            ],

            'discount_value' => [
                'required',
                'numeric',
                'min:0.01',
            ],

            'minimum_bill_amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'maximum_discount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            // DATE ONLY
            'valid_from' => [
                'nullable',
                'date',
            ],

            'valid_until' => [
                'nullable',
                'date',
                'after_or_equal:valid_from',
            ],

            'usage_limit' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'per_customer_limit' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'status' => [
                'required',
                'in:active,inactive',
            ],
        ]);


        // Percentage validation
        if (
            $validated['discount_type'] === 'percentage' &&
            $validated['discount_value'] > 100
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'discount_value' =>
                        'Percentage discount cannot exceed 100%.',
                ]);
        }


        // Validate branch
        if (!empty($validated['branch_id'])) {

            $branchExists = Branch::where('id', $validated['branch_id'])
                ->where('salon_id', $user->salon_id)
                ->exists();

            if (!$branchExists) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'branch_id' =>
                            'Invalid branch selected.',
                    ]);
            }
        }


        $coupon->update([

            'branch_id' =>
                $validated['branch_id'] ?? null,

            'code' =>
                strtoupper($validated['code']),

            'description' =>
                $validated['description'] ?? null,

            'discount_type' =>
                $validated['discount_type'],

            'discount_value' =>
                $validated['discount_value'],

            'minimum_bill_amount' =>
                $validated['minimum_bill_amount'] ?? 0,

            'maximum_discount' =>
                $validated['maximum_discount'] ?? null,

            // DATE ONLY
            'valid_from' =>
                !empty($validated['valid_from'])
                    ? date('Y-m-d', strtotime($validated['valid_from']))
                    : null,

            'valid_until' =>
                !empty($validated['valid_until'])
                    ? date('Y-m-d', strtotime($validated['valid_until']))
                    : null,

            'usage_limit' =>
                $validated['usage_limit'] ?? null,

            'per_customer_limit' =>
                $validated['per_customer_limit'] ?? null,

            'status' =>
                $validated['status'],
        ]);


        return redirect()
            ->route('admin.offers.coupons.index')
            ->with(
                'success',
                'Coupon updated successfully.'
            );
    }


    /**
     * Delete coupon.
     */
    public function destroy(Coupon $coupon)
    {
        $this->authorizeCoupon($coupon);

        $coupon->delete();

        return redirect()
            ->route('admin.offers.coupons.index')
            ->with(
                'success',
                'Coupon deleted successfully.'
            );
    }


    /**
     * Toggle coupon status.
     */
    public function toggleStatus(Coupon $coupon)
    {
        $this->authorizeCoupon($coupon);

        $coupon->update([
            'status' =>
                $coupon->status === 'active'
                    ? 'inactive'
                    : 'active',
        ]);

        return back()->with(
            'success',
            'Coupon status updated successfully.'
        );
    }


    /**
     * Authorize coupon for current salon/branch.
     */
    private function authorizeCoupon(Coupon $coupon): void
    {
        $user = Auth::user();

        if ($coupon->salon_id != $user->salon_id) {
            abort(403);
        }

        if (
            session()->has('branch_id') &&
            $coupon->branch_id != session('branch_id')
        ) {
            abort(403);
        }
    }
}