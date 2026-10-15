<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseCategoryController extends Controller
{
    /**
     * Display all expense categories.
     */
    public function index()
    {
        $user = Auth::user();

        $categories = ExpenseCategory::where(
                'salon_id',
                $user->salon_id
            )
            ->latest()
            ->paginate(10);

        return view(
            'admin.expenses.categories.index',
            compact('categories')
        );
    }

    /**
     * Show create form.
     */
    public function create()
    {
        return view(
            'admin.expenses.categories.create'
        );
    }

    /**
     * Store category.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'status' => [
                'nullable',
                'boolean',
            ],
        ]);

        ExpenseCategory::create([
            'salon_id' => $user->salon_id,
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'status' => $request->boolean('status'),
        ]);

        return redirect()
            ->route('admin.expenses.categories.index')
            ->with(
                'success',
                'Expense category created successfully.'
            );
    }

    /**
     * Show edit form.
     */
    public function edit(ExpenseCategory $category)
    {
        $user = Auth::user();

        abort_unless(
            $category->salon_id === $user->salon_id,
            403
        );

        return view(
            'admin.expenses.categories.edit',
            compact('category')
        );
    }

    /**
     * Update category.
     */
    public function update(
        Request $request,
        ExpenseCategory $category
    ) {
        $user = Auth::user();

        abort_unless(
            $category->salon_id === $user->salon_id,
            403
        );

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'status' => [
                'nullable',
                'boolean',
            ],
        ]);

        $category->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'status' => $request->boolean('status'),
        ]);

        return redirect()
            ->route('admin.expenses.categories.index')
            ->with(
                'success',
                'Expense category updated successfully.'
            );
    }

    /**
     * Delete category.
     */
    public function destroy(ExpenseCategory $category)
    {
        $user = Auth::user();

        abort_unless(
            $category->salon_id === $user->salon_id,
            403
        );

        if ($category->expenses()->exists()) {
            return redirect()
                ->back()
                ->with(
                    'error',
                    'This category cannot be deleted because expenses are already linked to it.'
                );
        }

        $category->delete();

        return redirect()
            ->route('admin.expenses.categories.index')
            ->with(
                'success',
                'Expense category deleted successfully.'
            );
    }
}