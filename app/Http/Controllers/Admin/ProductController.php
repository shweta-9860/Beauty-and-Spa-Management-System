<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\Supplier;
class ProductController extends Controller
{
    public function index()
    {
        $salon = Auth::user()->salon;

        abort_if(!$salon, 403, 'Salon not found.');

        $products = Product::with('category')
            ->where('salon_id', $salon->id)
            ->latest()
            ->paginate(15);

        return view('admin.inventory.products.index', compact('products'));
    }

    public function create()
{
    $salon = Auth::user()->salon;

    abort_if(!$salon, 403, 'Salon not found.');

    $categories = ProductCategory::where('salon_id', $salon->id)
        ->where('status', true)
        ->orderBy('name')
        ->get();

    $suppliers = Supplier::where('salon_id', $salon->id)
        ->where('status', 'active')
        ->orderBy('name')
        ->get();

    return view(
        'admin.inventory.products.create',
        compact('categories', 'suppliers')
    );
}
    public function store(Request $request)
    {
        $salon = Auth::user()->salon;

        abort_if(!$salon, 403, 'Salon not found.');

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'sku' => [
                'required',
                'string',
                'max:100',
                Rule::unique('products', 'sku')
                    ->where(fn ($query) =>
                        $query->where('salon_id', $salon->id)
                    ),
            ],

            'category_id' => [
                'nullable',
                'integer',
                'exists:product_categories,id',
            ],

            'brand' => [
                'nullable',
                'string',
                'max:255',
            ],

            'purchase_price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'selling_price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'stock' => [
                'required',
                'numeric',
                'min:0',
            ],

            'minimum_stock' => [
                'required',
                'numeric',
                'min:0',
            ],

            'unit' => [
                'required',
                'string',
                'max:50',
            ],

            'supplier_id' => [
                'nullable',
                'integer',
            ],

            'expiry_date' => [
                'nullable',
                'date',
            ],

            'status' => [
                'nullable',
                'boolean',
            ],
        ]);

        // Category must belong to current salon
        if (!empty($validated['category_id'])) {
            $categoryExists = ProductCategory::where('id', $validated['category_id'])
                ->where('salon_id', $salon->id)
                ->exists();

            if (!$categoryExists) {
                return back()
                    ->withErrors([
                        'category_id' => 'Invalid product category.'
                    ])
                    ->withInput();
            }
        }

        Product::create([
            'salon_id' => $salon->id,
            'category_id' => $validated['category_id'] ?? null,
            'supplier_id' => $validated['supplier_id'] ?? null,
            'name' => $validated['name'],
            'sku' => $validated['sku'],
            'brand' => $validated['brand'] ?? null,
            'purchase_price' => $validated['purchase_price'],
            'selling_price' => $validated['selling_price'],
            'stock' => $validated['stock'],
            'minimum_stock' => $validated['minimum_stock'],
            'unit' => $validated['unit'],
            'expiry_date' => $validated['expiry_date'] ?? null,
            'status' => $request->boolean('status', true),
        ]);

        return redirect()
            ->route('admin.inventory.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        $salon = Auth::user()->salon;

        abort_if(!$salon, 403, 'Salon not found.');

        abort_unless(
            $product->salon_id == $salon->id,
            404
        );

        return view(
            'admin.inventory.products.show',
            compact('product')
        );
    }

    public function edit(Product $product)
{
    $salon = Auth::user()->salon;

    abort_if(!$salon, 403, 'Salon not found.');

    abort_unless(
        $product->salon_id == $salon->id,
        404
    );

    $categories = ProductCategory::where('salon_id', $salon->id)
        ->where('status', true)
        ->orderBy('name')
        ->get();

    $suppliers = Supplier::where('salon_id', $salon->id)
        ->where('status', 'active')
        ->orderBy('name')
        ->get();

    return view(
        'admin.inventory.products.edit',
        compact('product', 'categories', 'suppliers')
    );
}
    public function update(Request $request, Product $product)
    {
        $salon = Auth::user()->salon;

        abort_if(!$salon, 403, 'Salon not found.');

        abort_unless(
            $product->salon_id == $salon->id,
            404
        );

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'sku' => [
                'required',
                'string',
                'max:100',
                Rule::unique('products', 'sku')
                    ->ignore($product->id)
                    ->where(fn ($query) =>
                        $query->where('salon_id', $salon->id)
                    ),
            ],

            'category_id' => [
                'nullable',
                'integer',
                'exists:product_categories,id',
            ],

            'brand' => [
                'nullable',
                'string',
                'max:255',
            ],

            'purchase_price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'selling_price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'stock' => [
                'required',
                'numeric',
                'min:0',
            ],

            'minimum_stock' => [
                'required',
                'numeric',
                'min:0',
            ],

            'unit' => [
                'required',
                'string',
                'max:50',
            ],

            'supplier_id' => [
                'nullable',
                'integer',
            ],

            'expiry_date' => [
                'nullable',
                'date',
            ],

            'status' => [
                'nullable',
                'boolean',
            ],
        ]);

        if (!empty($validated['category_id'])) {
            $categoryExists = ProductCategory::where('id', $validated['category_id'])
                ->where('salon_id', $salon->id)
                ->exists();

            if (!$categoryExists) {
                return back()
                    ->withErrors([
                        'category_id' => 'Invalid product category.'
                    ])
                    ->withInput();
            }
        }

        $product->update([
            'category_id' => $validated['category_id'] ?? null,
            'supplier_id' => $validated['supplier_id'] ?? null,
            'name' => $validated['name'],
            'sku' => $validated['sku'],
            'brand' => $validated['brand'] ?? null,
            'purchase_price' => $validated['purchase_price'],
            'selling_price' => $validated['selling_price'],
            'stock' => $validated['stock'],
            'minimum_stock' => $validated['minimum_stock'],
            'unit' => $validated['unit'],
            'expiry_date' => $validated['expiry_date'] ?? null,
            'status' => $request->boolean('status'),
        ]);

        return redirect()
            ->route('admin.inventory.products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $salon = Auth::user()->salon;

        abort_if(!$salon, 403, 'Salon not found.');

        abort_unless(
            $product->salon_id == $salon->id,
            404
        );

        $product->delete();

        return redirect()
            ->route('admin.inventory.products.index')
            ->with('success', 'Product deleted successfully.');
    }
}