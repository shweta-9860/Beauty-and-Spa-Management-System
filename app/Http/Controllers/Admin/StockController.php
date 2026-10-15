<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $salon = Auth::user()->salon;

        abort_if(!$salon, 403, 'Salon not found.');

        /*
        |--------------------------------------------------------------------------
        | Products Query
        |--------------------------------------------------------------------------
        */

        $query = Product::with('category')
            ->where('salon_id', $salon->id)
            ->where('status', true);

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%");

            });
        }

        /*
        |--------------------------------------------------------------------------
        | Stock Status Filter
        |--------------------------------------------------------------------------
        */

        if ($request->stock_status === 'low') {

            $query->whereColumn('stock', '<=', 'minimum_stock')
                ->where('stock', '>', 0);

        } elseif ($request->stock_status === 'out') {

            $query->where('stock', '<=', 0);

        } elseif ($request->stock_status === 'available') {

            $query->where('stock', '>', 0)
                ->whereColumn('stock', '>', 'minimum_stock');

        }

        /*
        |--------------------------------------------------------------------------
        | Paginated Products
        |--------------------------------------------------------------------------
        */

        $products = $query
            ->latest()
            ->paginate(15)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Dashboard Counts
        |--------------------------------------------------------------------------
        */

        $allProducts = Product::where('salon_id', $salon->id)
            ->where('status', true);

        // Total number of products
        $totalProducts = (clone $allProducts)->count();

        // Low stock products
        $lowStock = (clone $allProducts)
            ->whereColumn('stock', '<=', 'minimum_stock')
            ->where('stock', '>', 0)
            ->count();

        // Out of stock products
        $outOfStock = (clone $allProducts)
            ->where('stock', '<=', 0)
            ->count();

        // Total available stock quantity
        $availableStock = (clone $allProducts)
            ->where('stock', '>', 0)
            ->sum('stock');

        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */

        return view(
            'admin.inventory.stock.index',
            compact(
                'products',
                'totalProducts',
                'lowStock',
                'outOfStock',
                'availableStock'
            )
        );
    }
}