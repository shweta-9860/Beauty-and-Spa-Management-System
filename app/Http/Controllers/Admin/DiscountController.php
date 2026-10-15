<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Discount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DiscountController extends Controller
{
    /**
     * Display all discounts.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Discount::with('branch')
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
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $discounts = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view(
            'admin.offers.discounts.index',
            compact('discounts')
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
            'admin.offers.discounts.create',
            compact('branches')
        );
    }

    /**
     * Store discount.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',

            'description' => 'nullable|string',

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

            'applies_to' => [
                'required',
                'in:service,package,membership,all',
            ],

            'start_date' => 'nullable|date',

            'end_date' => [
                'nullable',
                'date',
                'after_or_equal:start_date',
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

        // Branch validation
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

        Discount::create([
            'salon_id' => $user->salon_id,
            'branch_id' => $validated['branch_id'] ?? null,
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'discount_type' => $validated['discount_type'],
            'discount_value' => $validated['discount_value'],
            'applies_to' => $validated['applies_to'],
            'start_date' => $validated['start_date'] ?? null,
            'end_date' => $validated['end_date'] ?? null,
            'minimum_bill_amount' =>
                $validated['minimum_bill_amount'] ?? 0,
            'maximum_discount' =>
                $validated['maximum_discount'] ?? null,
            'status' => $validated['status'],
        ]);

        return redirect()
            ->route('admin.offers.discounts.index')
            ->with(
                'success',
                'Discount created successfully.'
            );
    }

    /**
     * Show edit form.
     */
    public function edit(Discount $discount)
    {
        $user = Auth::user();

        $this->authorizeDiscount($discount);

        $branches = Branch::where('salon_id', $user->salon_id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        return view(
            'admin.offers.discounts.edit',
            compact('discount', 'branches')
        );
    }

    /**
     * Update discount.
     */
    public function update(
        Request $request,
        Discount $discount
    ) {
        $user = Auth::user();

        $this->authorizeDiscount($discount);

        $validated = $request->validate([
            'name' => 'required|string|max:255',

            'description' => 'nullable|string',

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

            'applies_to' => [
                'required',
                'in:service,package,membership,all',
            ],

            'start_date' => 'nullable|date',

            'end_date' => [
                'nullable',
                'date',
                'after_or_equal:start_date',
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

        // Branch validation
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

        $discount->update([
            'branch_id' => $validated['branch_id'] ?? null,
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'discount_type' => $validated['discount_type'],
            'discount_value' => $validated['discount_value'],
            'applies_to' => $validated['applies_to'],
            'start_date' => $validated['start_date'] ?? null,
            'end_date' => $validated['end_date'] ?? null,
            'minimum_bill_amount' =>
                $validated['minimum_bill_amount'] ?? 0,
            'maximum_discount' =>
                $validated['maximum_discount'] ?? null,
            'status' => $validated['status'],
        ]);

        return redirect()
            ->route('admin.offers.discounts.index')
            ->with(
                'success',
                'Discount updated successfully.'
            );
    }

    /**
     * Delete discount.
     */
    public function destroy(Discount $discount)
    {
        $this->authorizeDiscount($discount);

        $discount->delete();

        return redirect()
            ->route('admin.offers.discounts.index')
            ->with(
                'success',
                'Discount deleted successfully.'
            );
    }

    /**
     * Toggle active/inactive.
     */
    public function toggleStatus(Discount $discount)
    {
        $this->authorizeDiscount($discount);

        $discount->update([
            'status' => $discount->status === 'active'
                ? 'inactive'
                : 'active',
        ]);

        return back()->with(
            'success',
            'Discount status updated successfully.'
        );
    }

    /**
     * Check salon and branch access.
     */
    private function authorizeDiscount(Discount $discount): void
    {
        $user = Auth::user();

        // Salon isolation
        if ($discount->salon_id != $user->salon_id) {
            abort(403);
        }

        // Branch isolation
        if (
            session()->has('branch_id') &&
            $discount->branch_id != session('branch_id')
        ) {
            abort(403);
        }
    }
}