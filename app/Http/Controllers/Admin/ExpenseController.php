<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ExpenseController extends Controller
{
    /**
     * Display expenses.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Expense::with([
            'category',
            'branch',
            'creator',
        ])
        ->where('salon_id', $user->salon_id);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('paid_to', 'like', "%{$search}%")
                    ->orWhere('reference_no', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($request->filled('category_id')) {
            $query->where(
                'expense_category_id',
                $request->category_id
            );
        }

        // Branch filter
        if ($request->filled('branch_id')) {
            $query->where(
                'branch_id',
                $request->branch_id
            );
        }

        // Payment method filter
        if ($request->filled('payment_method')) {
            $query->where(
                'payment_method',
                $request->payment_method
            );
        }

        // Date From
        if ($request->filled('date_from')) {
            $query->whereDate(
                'expense_date',
                '>=',
                $request->date_from
            );
        }

        // Date To
        if ($request->filled('date_to')) {
            $query->whereDate(
                'expense_date',
                '<=',
                $request->date_to
            );
        }

        $expenses = $query
            ->latest('expense_date')
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        $categories = ExpenseCategory::where(
            'salon_id',
            $user->salon_id
        )
        ->where('status', true)
        ->orderBy('name')
        ->get();

        $branches = Branch::where(
            'salon_id',
            $user->salon_id
        )
        ->where('status', true)
        ->orderBy('name')
        ->get();

        $totalExpenses = (clone $query)->sum('amount');

        return view(
            'admin.expenses.index',
            compact(
                'expenses',
                'categories',
                'branches',
                'totalExpenses'
            )
        );
    }

    /**
     * Show create form.
     */
    public function create()
    {
        $user = Auth::user();

        $categories = ExpenseCategory::where(
            'salon_id',
            $user->salon_id
        )
        ->where('status', true)
        ->orderBy('name')
        ->get();

        $branches = Branch::where(
            'salon_id',
            $user->salon_id
        )
        ->where('status', true)
        ->orderBy('name')
        ->get();

        return view(
            'admin.expenses.create',
            compact(
                'categories',
                'branches'
            )
        );
    }

    /**
     * Store expense.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'branch_id' => [
                'nullable',
                'exists:branches,id',
            ],

            'expense_category_id' => [
                'required',
                'exists:expense_categories,id',
            ],

            'expense_date' => [
                'required',
                'date',
            ],

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'amount' => [
                'required',
                'numeric',
                'min:0',
            ],

            'payment_method' => [
                'required',
                'string',
                'max:50',
            ],

            'paid_to' => [
                'nullable',
                'string',
                'max:255',
            ],

            'reference_no' => [
                'nullable',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'attachment' => [
                'nullable',
                'file',
                'max:5120',
                'mimes:jpg,jpeg,png,pdf,webp',
            ],

            'status' => [
                'nullable',
                'boolean',
            ],
        ]);

        // Verify category belongs to current salon
        $category = ExpenseCategory::where('id', $validated['expense_category_id'])
            ->where('salon_id', $user->salon_id)
            ->firstOrFail();

        // Verify branch belongs to current salon
        if (!empty($validated['branch_id'])) {
            Branch::where('id', $validated['branch_id'])
                ->where('salon_id', $user->salon_id)
                ->firstOrFail();
        }

        $attachment = null;

        if ($request->hasFile('attachment')) {
            $attachment = $request
                ->file('attachment')
                ->store('expenses', 'public');
        }

        Expense::create([
            'salon_id' => $user->salon_id,
            'branch_id' => $validated['branch_id'] ?? null,
            'expense_category_id' => $validated['expense_category_id'],
            'expense_date' => $validated['expense_date'],
            'title' => $validated['title'],
            'amount' => $validated['amount'],
            'payment_method' => $validated['payment_method'],
            'paid_to' => $validated['paid_to'] ?? null,
            'reference_no' => $validated['reference_no'] ?? null,
            'description' => $validated['description'] ?? null,
            'attachment' => $attachment,
            'status' => $request->boolean('status', true),
            'created_by' => $user->id,
        ]);

        return redirect()
            ->route('admin.expenses.index')
            ->with(
                'success',
                'Expense created successfully.'
            );
    }

    /**
     * Show expense.
     */
    public function show(Expense $expense)
    {
        $user = Auth::user();

        abort_unless(
            $expense->salon_id === $user->salon_id,
            403
        );

        $expense->load([
            'category',
            'branch',
            'creator',
        ]);

        return view(
            'admin.expenses.show',
            compact('expense')
        );
    }

    /**
     * Show edit form.
     */
    public function edit(Expense $expense)
    {
        $user = Auth::user();

        abort_unless(
            $expense->salon_id === $user->salon_id,
            403
        );

        $categories = ExpenseCategory::where(
            'salon_id',
            $user->salon_id
        )
        ->where('status', true)
        ->orderBy('name')
        ->get();

        $branches = Branch::where(
            'salon_id',
            $user->salon_id
        )
        ->where('status', true)
        ->orderBy('name')
        ->get();

        return view(
            'admin.expenses.edit',
            compact(
                'expense',
                'categories',
                'branches'
            )
        );
    }

    /**
     * Update expense.
     */
    public function update(
        Request $request,
        Expense $expense
    ) {
        $user = Auth::user();

        abort_unless(
            $expense->salon_id === $user->salon_id,
            403
        );

        $validated = $request->validate([
            'branch_id' => [
                'nullable',
                'exists:branches,id',
            ],

            'expense_category_id' => [
                'required',
                'exists:expense_categories,id',
            ],

            'expense_date' => [
                'required',
                'date',
            ],

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'amount' => [
                'required',
                'numeric',
                'min:0',
            ],

            'payment_method' => [
                'required',
                'string',
                'max:50',
            ],

            'paid_to' => [
                'nullable',
                'string',
                'max:255',
            ],

            'reference_no' => [
                'nullable',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'attachment' => [
                'nullable',
                'file',
                'max:5120',
                'mimes:jpg,jpeg,png,pdf,webp',
            ],

            'status' => [
                'nullable',
                'boolean',
            ],
        ]);

        // Verify category
        ExpenseCategory::where('id', $validated['expense_category_id'])
            ->where('salon_id', $user->salon_id)
            ->firstOrFail();

        // Verify branch
        if (!empty($validated['branch_id'])) {
            Branch::where('id', $validated['branch_id'])
                ->where('salon_id', $user->salon_id)
                ->firstOrFail();
        }

        $data = [
            'branch_id' => $validated['branch_id'] ?? null,
            'expense_category_id' => $validated['expense_category_id'],
            'expense_date' => $validated['expense_date'],
            'title' => $validated['title'],
            'amount' => $validated['amount'],
            'payment_method' => $validated['payment_method'],
            'paid_to' => $validated['paid_to'] ?? null,
            'reference_no' => $validated['reference_no'] ?? null,
            'description' => $validated['description'] ?? null,
            'status' => $request->boolean('status', true),
        ];

        if ($request->hasFile('attachment')) {

            if (
                $expense->attachment &&
                Storage::disk('public')->exists(
                    $expense->attachment
                )
            ) {
                Storage::disk('public')->delete(
                    $expense->attachment
                );
            }

            $data['attachment'] = $request
                ->file('attachment')
                ->store('expenses', 'public');
        }

        $expense->update($data);

        return redirect()
            ->route('admin.expenses.index')
            ->with(
                'success',
                'Expense updated successfully.'
            );
    }

    /**
     * Delete expense.
     */
    public function destroy(Expense $expense)
    {
        $user = Auth::user();

        abort_unless(
            $expense->salon_id === $user->salon_id,
            403
        );

        if (
            $expense->attachment &&
            Storage::disk('public')->exists(
                $expense->attachment
            )
        ) {
            Storage::disk('public')->delete(
                $expense->attachment
            );
        }

        $expense->delete();

        return redirect()
            ->route('admin.expenses.index')
            ->with(
                'success',
                'Expense deleted successfully.'
            );
    }
}