<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    /**
     * Check current user branch access.
     */
    private function currentBranchId()
    {
        return Auth::user()->branch_id;
    }

    /**
     * Purchase List
     */
    public function index()
    {
        $user = Auth::user();

        abort_if(!$user->salon, 403, 'Salon not found.');

        $query = Purchase::with([
            'supplier',
            'branch',
        ])
        ->where('salon_id', $user->salon_id);

        /*
         * Branch user:
         * Show ONLY logged-in branch purchases.
         */
        if (!empty($user->branch_id)) {
            $query->where('branch_id', $user->branch_id);
        }

        $purchases = $query
            ->latest()
            ->paginate(15);

        return view(
            'admin.inventory.purchases.index',
            compact('purchases')
        );
    }


    /**
     * Create Purchase
     */
    public function create()
    {
        $user = Auth::user();

        abort_if(!$user->salon, 403, 'Salon not found.');

        /*
         * Suppliers are salon-level.
         */
        $suppliers = Supplier::where('salon_id', $user->salon_id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        /*
         * Products are salon-level.
         */
        $products = Product::where('salon_id', $user->salon_id)
            ->where('status', true)
            ->orderBy('name')
            ->get();

        /*
         * Branch user:
         * Only show their own branch.
         */
        if (!empty($user->branch_id)) {

            $branches = $user->branch()
                ->where('status', true)
                ->get();

        } else {

            /*
             * Super Admin / Salon Admin:
             * Can see all branches.
             */
            $branches = $user->salon
                ->branches()
                ->where('status', true)
                ->orderBy('name')
                ->get();
        }

        return view(
            'admin.inventory.purchases.create',
            compact(
                'suppliers',
                'products',
                'branches'
            )
        );
    }


    /**
     * Store Purchase
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        abort_if(!$user->salon, 403, 'Salon not found.');

        $validated = $request->validate([

            'supplier_id' => [
                'nullable',
                'exists:suppliers,id',
            ],

            /*
             * Branch is still validated,
             * but branch user will NOT be allowed
             * to submit another branch.
             */
            'branch_id' => [
                'required',
                'exists:branches,id',
            ],

            'purchase_date' => [
                'required',
                'date',
            ],

            'discount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'payment_status' => [
                'required',
                'in:pending,partial,paid',
            ],

            'status' => [
                'required',
                'in:draft,received,cancelled',
            ],

            'notes' => [
                'nullable',
                'string',
            ],

            'items' => [
                'required',
                'array',
                'min:1',
            ],

            'items.*.product_id' => [
                'required',
                'exists:products,id',
            ],

            'items.*.quantity' => [
                'required',
                'numeric',
                'min:0.01',
            ],

            'items.*.unit_price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'items.*.discount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'items.*.tax' => [
                'nullable',
                'numeric',
                'min:0',
            ],
        ]);


        /*
         * IMPORTANT:
         * Branch user can NEVER create purchase
         * for another branch.
         */
        if (!empty($user->branch_id)) {

            if ((int) $validated['branch_id'] !== (int) $user->branch_id) {

                abort(
                    403,
                    'You can only create purchases for your assigned branch.'
                );
            }

            /*
             * Force current branch.
             */
            $validated['branch_id'] = $user->branch_id;
        }


        /*
         * Check supplier belongs to current salon.
         */
        if (!empty($validated['supplier_id'])) {

            $supplierExists = Supplier::where('id', $validated['supplier_id'])
                ->where('salon_id', $user->salon_id)
                ->exists();

            abort_if(
                !$supplierExists,
                403,
                'Invalid supplier access.'
            );
        }


        DB::transaction(function () use (
            $validated,
            $user
        ) {

            /*
             * Generate Purchase Number
             */
            $purchaseNumber =
                'PUR-' .
                date('Ymd') .
                '-' .
                str_pad(
                    (
                        Purchase::where(
                            'salon_id',
                            $user->salon_id
                        )->count() + 1
                    ),
                    4,
                    '0',
                    STR_PAD_LEFT
                );


            $subtotal = 0;
            $totalTax = 0;


            /*
             * Create Purchase
             */
            $purchase = Purchase::create([

                'salon_id' => $user->salon_id,

                /*
                 * Always use validated branch.
                 */
                'branch_id' => $validated['branch_id'],

                'supplier_id' =>
                    $validated['supplier_id'] ?? null,

                'purchase_number' =>
                    $purchaseNumber,

                'purchase_date' =>
                    $validated['purchase_date'],

                'subtotal' => 0,

                'discount' =>
                    $validated['discount'] ?? 0,

                'tax' => 0,

                'grand_total' => 0,

                'payment_status' =>
                    $validated['payment_status'],

                'status' =>
                    $validated['status'],

                'notes' =>
                    $validated['notes'] ?? null,
            ]);


            /*
             * Purchase Items
             */
            foreach ($validated['items'] as $item) {

                $quantity =
                    (float) $item['quantity'];

                $unitPrice =
                    (float) $item['unit_price'];

                $itemDiscount =
                    (float) ($item['discount'] ?? 0);

                $taxPercentage =
                    (float) ($item['tax'] ?? 0);


                /*
                 * Verify product belongs to current salon.
                 */
                $product = Product::where(
                        'id',
                        $item['product_id']
                    )
                    ->where(
                        'salon_id',
                        $user->salon_id
                    )
                    ->lockForUpdate()
                    ->first();

                abort_if(
                    !$product,
                    403,
                    'Invalid product access.'
                );


                /*
                 * Base amount
                 */
                $baseAmount =
                    $quantity * $unitPrice;


                /*
                 * After item discount
                 */
                $afterDiscount =
                    max(
                        $baseAmount - $itemDiscount,
                        0
                    );


                /*
                 * Tax amount
                 */
                $taxAmount =
                    $afterDiscount *
                    $taxPercentage /
                    100;


                /*
                 * Item total
                 */
                $itemTotal =
                    $afterDiscount +
                    $taxAmount;


                $subtotal +=
                    $afterDiscount;

                $totalTax +=
                    $taxAmount;


                /*
                 * Create Purchase Item
                 */
                PurchaseItem::create([

                    'purchase_id' =>
                        $purchase->id,

                    'product_id' =>
                        $product->id,

                    'quantity' =>
                        $quantity,

                    'unit_price' =>
                        $unitPrice,

                    'discount' =>
                        $itemDiscount,

                    'tax' =>
                        $taxAmount,

                    'total' =>
                        $itemTotal,
                ]);


                /*
                 * Increase Stock
                 *
                 * Only RECEIVED purchase
                 * increases stock.
                 */
                if (
                    $validated['status'] ===
                    'received'
                ) {

                    $product->increment(
                        'stock',
                        $quantity
                    );
                }
            }


            /*
             * Overall Discount
             */
            $overallDiscount =
                (float) (
                    $validated['discount'] ?? 0
                );


            /*
             * Grand Total
             */
            $grandTotal =
                max(
                    $subtotal -
                    $overallDiscount +
                    $totalTax,
                    0
                );


            /*
             * Update Purchase Totals
             */
            $purchase->update([

                'subtotal' =>
                    $subtotal,

                'tax' =>
                    $totalTax,

                'grand_total' =>
                    $grandTotal,
            ]);
        });


        return redirect()
            ->route(
                'admin.inventory.purchases.index'
            )
            ->with(
                'success',
                'Purchase created successfully and stock updated.'
            );
    }


    /**
     * Show Purchase Details
     */
    public function show($id)
    {
        $user = Auth::user();

        $query = Purchase::with([
            'supplier',
            'branch',
            'items.product',
        ])
        ->where('id', $id)
        ->where('salon_id', $user->salon_id);

        /*
         * Branch user:
         * Only own branch purchase.
         */
        if (!empty($user->branch_id)) {
            $query->where(
                'branch_id',
                $user->branch_id
            );
        }

        $purchase = $query->firstOrFail();

        return view(
            'admin.inventory.purchases.show',
            compact('purchase')
        );
    }
}