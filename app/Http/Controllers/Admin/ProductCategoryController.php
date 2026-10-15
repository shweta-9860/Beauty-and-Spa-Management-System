<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProductCategoryController extends Controller
{
    /**
     * Display all product categories.
     */
    public function index()
    {
        $salon = Auth::user()->salon;

        abort_if(!$salon, 403, 'Salon not found.');

        $categories = ProductCategory::where('salon_id', $salon->id)
            ->latest()
            ->paginate(15);

        return view('admin.inventory.categories.index', compact(
            'categories'
        ));
    }

    /**
     * Show create form.
     */
    public function create()
    {
        $salon = Auth::user()->salon;

        abort_if(!$salon, 403, 'Salon not found.');

        return view('admin.inventory.categories.create');
    }

    /**
     * Store new category.
     */
    public function store(Request $request)
    {
        $salon = Auth::user()->salon;

        abort_if(!$salon, 403, 'Salon not found.');

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('product_categories', 'name')
                    ->where(fn ($query) => $query->where('salon_id', $salon->id)),
            ],
            'description' => [
                'nullable',
                'string',
                'max:1000',
            ],
            'status' => [
                'nullable',
                'boolean',
            ],
        ]);

        ProductCategory::create([
            'salon_id' => $salon->id,
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'status' => $request->boolean('status', true),
        ]);

        return redirect()
            ->route('admin.inventory.categories.index')
            ->with('success', 'Product category created successfully.');
    }

    /**
     * Show category.
     */
    public function show(ProductCategory $productCategory)
    {
        $salon = Auth::user()->salon;

        abort_if(!$salon, 403, 'Salon not found.');

        abort_unless(
            $productCategory->salon_id == $salon->id,
            404
        );

        return view(
            'admin.inventory.categories.show',
            compact('productCategory')
        );
    }

    /**
     * Show edit form.
     */
    public function edit(ProductCategory $productCategory)
    {
        $salon = Auth::user()->salon;

        abort_if(!$salon, 403, 'Salon not found.');

        abort_unless(
            $productCategory->salon_id == $salon->id,
            404
        );

        return view(
            'admin.inventory.categories.edit',
            compact('productCategory')
        );
    }

    /**
     * Update category.
     */
    public function update(
        Request $request,
        ProductCategory $productCategory
    ) {
        $salon = Auth::user()->salon;

        abort_if(!$salon, 403, 'Salon not found.');

        abort_unless(
            $productCategory->salon_id == $salon->id,
            404
        );

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('product_categories', 'name')
                    ->ignore($productCategory->id)
                    ->where(fn ($query) => $query->where('salon_id', $salon->id)),
            ],
            'description' => [
                'nullable',
                'string',
                'max:1000',
            ],
            'status' => [
                'nullable',
                'boolean',
            ],
        ]);

        $productCategory->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'status' => $request->boolean('status'),
        ]);

        return redirect()
            ->route('admin.inventory.categories.index')
            ->with('success', 'Product category updated successfully.');
    }

    /**
     * Delete category.
     */
    public function destroy(ProductCategory $productCategory)
    {
        $salon = Auth::user()->salon;

        abort_if(!$salon, 403, 'Salon not found.');

        abort_unless(
            $productCategory->salon_id == $salon->id,
            404
        );

        if ($productCategory->products()->exists()) {
            return back()->with(
                'error',
                'This category cannot be deleted because products are assigned to it.'
            );
        }

        $productCategory->delete();

        return redirect()
            ->route('admin.inventory.categories.index')
            ->with('success', 'Product category deleted successfully.');
    }
}