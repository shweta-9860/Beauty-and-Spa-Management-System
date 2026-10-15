<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomerPackage;
use App\Models\PackageUsage;
use Illuminate\Http\Request;

class PackageUsageController extends Controller
{
    /**
     * Display package usage history.
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | Salon Check
        |--------------------------------------------------------------------------
        */
        if (!$user->salon_id) {

            return redirect()
                ->route('admin.customer-packages.index')
                ->withErrors([
                    'salon_id' =>
                        'Your account is not linked to a salon.'
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Customer Packages
        |--------------------------------------------------------------------------
        |
        | Get all customer packages.
        | Even if the customer has NOT used any service,
        | the customer/package will still appear.
        |
        |--------------------------------------------------------------------------
        */
        $customerPackagesQuery = CustomerPackage::where(
                'salon_id',
                $user->salon_id
            )
            ->with([
                'customer',
                'package',
            ])
            ->latest('id');


        /*
        |--------------------------------------------------------------------------
        | Customer Filter
        |--------------------------------------------------------------------------
        */
        if ($request->filled('customer_id')) {

            $customerPackagesQuery->where(
                'customer_id',
                $request->customer_id
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Customer Packages
        |--------------------------------------------------------------------------
        */
        $customerPackages = $customerPackagesQuery->get();


        /*
        |--------------------------------------------------------------------------
        | Get Usage Records
        |--------------------------------------------------------------------------
        */
        $usageQuery = PackageUsage::where(
                'salon_id',
                $user->salon_id
            )
            ->with([
                'customer',
                'customerPackage.package',
                'packageService.service',
                'serviceVariation',
            ])
            ->latest('used_date')
            ->latest('id');


        /*
        |--------------------------------------------------------------------------
        | Customer Filter
        |--------------------------------------------------------------------------
        */
        if ($request->filled('customer_id')) {

            $usageQuery->where(
                'customer_id',
                $request->customer_id
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Date Filter
        |--------------------------------------------------------------------------
        */
        if ($request->filled('from_date')) {

            $usageQuery->whereDate(
                'used_date',
                '>=',
                $request->from_date
            );
        }


        if ($request->filled('to_date')) {

            $usageQuery->whereDate(
                'used_date',
                '<=',
                $request->to_date
            );
        }


        $usages = $usageQuery->get();


        /*
        |--------------------------------------------------------------------------
        | Group Usage By Customer Package
        |--------------------------------------------------------------------------
        */
        $usagesByPackage = $usages->groupBy(
            'customer_package_id'
        );


        /*
        |--------------------------------------------------------------------------
        | Attach Usage To Customer Packages
        |--------------------------------------------------------------------------
        */
        $customerPackages->each(
            function ($customerPackage) use ($usagesByPackage) {

                $packageUsages =
                    $usagesByPackage->get(
                        $customerPackage->id,
                        collect()
                    );

                $customerPackage->setRelation(
                    'usages',
                    $packageUsages
                );
            }
        );


        /*
        |--------------------------------------------------------------------------
        | Summary
        |--------------------------------------------------------------------------
        */
        $totalUsageRecords =
            $usages->count();

        $totalServicesUsed =
            $usages->sum('quantity');

        $totalCustomers =
            $customerPackages
                ->pluck('customer_id')
                ->unique()
                ->count();


        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */
        return view(
            'admin.customer-packages.usage-history',
            compact(
                'customerPackages',
                'usages',
                'totalUsageRecords',
                'totalServicesUsed',
                'totalCustomers'
            )
        );
        $customerPackages = CustomerPackage::with([
    'customer',
    'package',
    'usages.service',
    'usages.serviceVariation',
])->get();

    }
}