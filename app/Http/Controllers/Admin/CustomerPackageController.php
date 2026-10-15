<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerPackage;
use App\Models\Package;
use App\Models\PackageService;
use App\Models\PackageUsage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerPackageController extends Controller
{
    public function purchase(Request $request)
{
    $user = auth()->user();

    if (!$user->salon_id) {
        return redirect()
            ->route('admin.customer-packages.index')
            ->withErrors([
                'salon_id' => 'Your account is not linked to a salon.',
            ]);
    }

    $customers = \App\Models\Customer::where('salon_id', $user->salon_id)
        ->orderBy('name')
        ->get();

    $packages = \App\Models\Package::where('salon_id', $user->salon_id)
        ->where('status', 'active')
        ->orderBy('name')
        ->get();

    return view(
        'admin.customer-packages.purchase',
        compact('customers', 'packages')
    );
}
public function storePurchase(Request $request)
{
    $user = auth()->user();

    if (!$user->salon_id) {
        return back()
            ->withErrors([
                'salon_id' => 'Your account is not linked to a salon.',
            ])
            ->withInput();
    }

    $validated = $request->validate([
        'customer_id' => [
            'required',
            'integer',
            'exists:customers,id',
        ],

        'package_id' => [
            'required',
            'integer',
            'exists:packages,id',
        ],

        'purchase_date' => [
            'required',
            'date',
        ],

        'payment_method' => [
            'required',
            'in:cash,upi,card,bank_transfer,online',
        ],

        'notes' => [
            'nullable',
            'string',
            'max:1000',
        ],
    ]);

    // Check customer belongs to current salon
    $purchaseNumber = 'PKG-' . now()->format('YmdHis') . '-' . random_int(100, 999);
    $customer = \App\Models\Customer::where('id', $validated['customer_id'])
        ->where('salon_id', $user->salon_id)
        ->first();

    if (!$customer) {
        return back()
            ->withErrors([
                'customer_id' => 'Selected customer does not belong to your salon.',
            ])
            ->withInput();
    }

    // Get package
    $package = \App\Models\Package::where('id', $validated['package_id'])
        ->where('salon_id', $user->salon_id)
        ->where('status', 'active')
        ->first();

    if (!$package) {
        return back()
            ->withErrors([
                'package_id' => 'Selected package was not found or is inactive.',
            ])
            ->withInput();
    }

    /*
    |--------------------------------------------------------------------------
    | Get package uses
    |--------------------------------------------------------------------------
    */

    $totalUses = 0;

    if (isset($package->total_uses)) {
        $totalUses = (int) $package->total_uses;
    } elseif (isset($package->uses)) {
        $totalUses = (int) $package->uses;
    } elseif (isset($package->quantity)) {
        $totalUses = (int) $package->quantity;
    }

    /*
    |--------------------------------------------------------------------------
    | Purchase date
    |--------------------------------------------------------------------------
    */

    $purchaseDate = \Carbon\Carbon::parse(
        $validated['purchase_date']
    );

    /*
    |--------------------------------------------------------------------------
    | Expiry date
    |--------------------------------------------------------------------------
    */

    $expiryDate = null;

    if (
        isset($package->validity_days) &&
        $package->validity_days
    ) {
        $expiryDate = $purchaseDate
            ->copy()
            ->addDays((int) $package->validity_days);
    }

    /*
    |--------------------------------------------------------------------------
    | Create Customer Package
    |--------------------------------------------------------------------------
    */

    $customerPackage = \App\Models\CustomerPackage::create([
    'salon_id' => $user->salon_id,

    'customer_id' => $customer->id,

    'package_id' => $package->id,

    'purchase_number' => $purchaseNumber,

    'purchase_date' => $purchaseDate,

    'expiry_date' => $expiryDate,

    'total_uses' => $totalUses,

    'used_uses' => 0,

    'remaining_uses' => $totalUses,

    'status' => 'active',

    'notes' => $validated['notes'] ?? null,
]);

    /*
    |--------------------------------------------------------------------------
    | Success
    |--------------------------------------------------------------------------
    */

    return redirect()
        ->route('admin.customer-packages.index')
        ->with(
            'success',
            'Package purchased successfully for ' .
            $customer->name . '.'
        );
}
    /**
     * Display customer package purchases.
     */
    public function index()
    {
        $user = auth()->user();

        $customerPackages = CustomerPackage::where('salon_id', $user->salon_id)
            ->with([
                'customer',
                'package',
            ])
            ->latest()
            ->get();

        return view(
            'admin.customer-packages.index',
            compact('customerPackages')
        );
    }

    /**
     * Show purchase form.
     */
    public function create()
    {
        $user = auth()->user();

        if (!$user->salon_id) {
            return redirect()
                ->route('admin.customer-packages.index')
                ->withErrors([
                    'salon_id' => 'Your account is not linked to a salon.'
                ]);
        }

        $customers = Customer::where('salon_id', $user->salon_id)
            ->orderBy('name')
            ->get();

        $packages = Package::where('salon_id', $user->salon_id)
            ->where('status', 1)
            ->with('packageServices.service')
            ->orderBy('name')
            ->get();

        return view(
            'admin.customer-packages.create',
            compact('customers', 'packages')
        );
    }

    /**
     * Store customer package purchase.
     */
    public function store(Request $request)
{
    $user = auth()->user();

    if (!$user->salon_id) {
        return back()
            ->withErrors([
                'salon_id' => 'Your account is not linked to a salon.',
            ])
            ->withInput();
    }

    /*
    |--------------------------------------------------------------------------
    | Validate Request
    |--------------------------------------------------------------------------
    */
    $validated = $request->validate([
        'customer_id' => [
            'required',
            'integer',
            'exists:customers,id',
        ],

        'package_id' => [
            'required',
            'integer',
            'exists:packages,id',
        ],

        'purchase_date' => [
            'required',
            'date',
        ],

        'expiry_date' => [
            'nullable',
            'date',
            'after_or_equal:purchase_date',
        ],

        'total_uses' => [
            'required',
            'integer',
            'min:1',
        ],

        'notes' => [
            'nullable',
            'string',
            'max:1000',
        ],
    ]);

    /*
    |--------------------------------------------------------------------------
    | Check Customer Belongs To Current Salon
    |--------------------------------------------------------------------------
    */
    $customer = \App\Models\Customer::where(
        'id',
        $validated['customer_id']
    )
        ->where(
            'salon_id',
            $user->salon_id
        )
        ->first();

    if (!$customer) {
        return back()
            ->withErrors([
                'customer_id' =>
                    'Selected customer does not belong to your salon.',
            ])
            ->withInput();
    }

    /*
    |--------------------------------------------------------------------------
    | Check Package Belongs To Current Salon
    |--------------------------------------------------------------------------
    */
    $package = \App\Models\Package::where(
        'id',
        $validated['package_id']
    )
        ->where(
            'salon_id',
            $user->salon_id
        )
        ->where(
            'status',
            'active'
        )
        ->first();

    if (!$package) {
        return back()
            ->withErrors([
                'package_id' =>
                    'Selected package was not found or is inactive.',
            ])
            ->withInput();
    }

    /*
    |--------------------------------------------------------------------------
    | Generate Purchase Number
    |--------------------------------------------------------------------------
    */
    $purchaseNumber =
        'PKG-' .
        now()->format('YmdHis') .
        '-' .
        random_int(100, 999);

    /*
    |--------------------------------------------------------------------------
    | Create Customer Package
    |--------------------------------------------------------------------------
    */
    $customerPackage = \App\Models\CustomerPackage::create([
        'salon_id' => $user->salon_id,

        'customer_id' =>
            $validated['customer_id'],

        'package_id' =>
            $validated['package_id'],

        'purchase_number' =>
            $purchaseNumber,

        'purchase_date' =>
            $validated['purchase_date'],

        'expiry_date' =>
            $validated['expiry_date'] ?? null,

        'total_uses' =>
            $validated['total_uses'],

        'used_uses' => 0,

        'remaining_uses' =>
            $validated['total_uses'],

        'status' => 'active',

        'notes' =>
            $validated['notes'] ?? null,
    ]);

    /*
    |--------------------------------------------------------------------------
    | Success
    |--------------------------------------------------------------------------
    */
    return redirect()
        ->route('admin.customer-packages.index')
        ->with(
            'success',
            'Package assigned successfully to ' .
            $customer->name .
            '. Purchase No: ' .
            $purchaseNumber
        );
}

    /**
     * Show package service usage form.
     */
    public function usageForm(CustomerPackage $customerPackage)
    {
        $user = auth()->user();

        if ($customerPackage->salon_id != $user->salon_id) {
            abort(403);
        }

        /*
        |--------------------------------------------------------------------------
        | Automatically expire package if expiry date has passed
        |--------------------------------------------------------------------------
        */
        if (
            $customerPackage->status === 'active' &&
            $customerPackage->expiry_date &&
            $customerPackage->expiry_date->isBefore(today())
        ) {
            $customerPackage->update([
                'status' => 'expired',
            ]);
        }

        $customerPackage->load([
            'customer',
            'package.packageServices.service',
            'package.packageServices.serviceVariation',
        ]);

        return view(
            'admin.customer-packages.usage',
            compact('customerPackage')
        );
    }

    /**
     * Deduct package service usage.
     */
    public function useService(Request $request)
    {
        $user = auth()->user();

        if (!$user->salon_id) {
            return back()
                ->withInput()
                ->withErrors([
                    'salon_id' => 'Your account is not linked to a salon.'
                ]);
        }

        $request->validate([
            'customer_package_id' => [
                'required',
                'integer',
                'exists:customer_packages,id',
            ],

            'package_service_id' => [
                'required',
                'integer',
                'exists:package_services,id',
            ],

            'quantity' => [
                'required',
                'integer',
                'min:1',
            ],

            'used_date' => [
                'required',
                'date',
            ],

            'notes' => [
                'nullable',
                'string',
            ],
        ]);

        DB::transaction(function () use ($request, $user) {

            /*
            |--------------------------------------------------------------------------
            | Lock customer package
            |--------------------------------------------------------------------------
            */
            $customerPackage = CustomerPackage::where(
                'id',
                $request->customer_package_id
            )
                ->where('salon_id', $user->salon_id)
                ->lockForUpdate()
                ->firstOrFail();

            /*
            |--------------------------------------------------------------------------
            | Expiry check
            |--------------------------------------------------------------------------
            */
            if (
                $customerPackage->expiry_date &&
                $customerPackage->expiry_date->isBefore(
                    \Carbon\Carbon::parse($request->used_date)
                )
            ) {
                $customerPackage->update([
                    'status' => 'expired',
                ]);

                throw new \Exception(
                    'This customer package has expired.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Status check
            |--------------------------------------------------------------------------
            */
            if ($customerPackage->status === 'cancelled') {
                throw new \Exception(
                    'This customer package has been cancelled.'
                );
            }

            if ($customerPackage->status === 'expired') {
                throw new \Exception(
                    'This customer package has expired.'
                );
            }

            if ($customerPackage->status === 'exhausted') {
                throw new \Exception(
                    'This customer package has no remaining uses.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Remaining balance check
            |--------------------------------------------------------------------------
            */
            $quantity = (int) $request->quantity;

            if ($quantity > $customerPackage->remaining_uses) {
                throw new \Exception(
                    'Requested quantity is greater than remaining package uses.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Validate package service belongs to this package
            |--------------------------------------------------------------------------
            */
            $packageService = PackageService::where(
                'id',
                $request->package_service_id
            )
                ->where(
                    'package_id',
                    $customerPackage->package_id
                )
                ->with([
                    'service',
                    'serviceVariation',
                ])
                ->firstOrFail();

            /*
            |--------------------------------------------------------------------------
            | Create usage history
            |--------------------------------------------------------------------------
            */
            PackageUsage::create([
                'salon_id' => $user->salon_id,

                'customer_package_id' => $customerPackage->id,

                'customer_id' => $customerPackage->customer_id,

                'package_service_id' => $packageService->id,

                'service_id' => $packageService->service_id,

                'service_variation_id' =>
                    $packageService->service_variation_id,

                'appointment_id' => null,

                'bill_id' => null,

                'quantity' => $quantity,

                'used_date' => $request->used_date,

                'notes' => $request->notes,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Update package usage balance
            |--------------------------------------------------------------------------
            */
            $newUsedUses =
                (int) $customerPackage->used_uses + $quantity;

            $newRemainingUses =
                (int) $customerPackage->remaining_uses - $quantity;

            $newStatus =
                $newRemainingUses <= 0
                    ? 'exhausted'
                    : 'active';

            $customerPackage->update([
                'used_uses' => $newUsedUses,

                'remaining_uses' => max(
                    0,
                    $newRemainingUses
                ),

                'status' => $newStatus,
            ]);
        });

        return redirect()
            ->route(
                'admin.customer-packages.index'
            )
            ->with(
                'success',
                'Package service usage recorded successfully.'
            );
    }

    /**
     * Generate unique purchase number.
     */
    private function generatePurchaseNumber(): string
    {
        $date = now()->format('Ymd');

        $lastPurchase = CustomerPackage::where(
                'purchase_number',
                'like',
                "CP-{$date}-%"
            )
            ->latest('id')
            ->first();

        if ($lastPurchase) {
            $lastNumber = (int) substr(
                $lastPurchase->purchase_number,
                -4
            );

            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        return 'CP-' . $date . '-' . str_pad(
            $nextNumber,
            4,
            '0',
            STR_PAD_LEFT
        );
    }
}

