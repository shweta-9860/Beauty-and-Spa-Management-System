<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Bill;
use App\Models\BillItem;
use App\Models\BillPayment;
use App\Models\Branch;
use App\Models\Customer;
use App\Models\CustomerPackage;
use App\Models\PackageService;
use App\Models\PackageUsage;
use App\Models\Service;
use App\Models\ServiceVariation;
use App\Models\Staff;
use App\Models\Coupon;
use App\Models\CouponUsage;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;


class BillingController extends Controller
{
    /**
     * Billing Main Page
     */
    public function index(Request $request)
    {
        return $this->create($request);
    }


    /**
     * POS Billing Screen
     */
    public function create(Request $request)
    {
        $user = Auth::user();

        $salon = $user->salon;

        if (!$salon) {
            abort(
                403,
                'Your account is not linked to a salon.'
            );
        }

        $salonId = $salon->id;


        /*
        |--------------------------------------------------------------------------
        | Branches
        |--------------------------------------------------------------------------
        */

        $branches = Branch::where(
            'salon_id',
            $salonId
        )
            ->where(
                'status',
                true
            )
            ->orderBy('name')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Selected Branch
        |--------------------------------------------------------------------------
        */

        $selectedBranchId =
            $request->integer(
                'branch_id'
            );

        if (
            !$selectedBranchId &&
            $branches->isNotEmpty()
        ) {
            $selectedBranchId =
                $branches->first()->id;
        }

        $selectedBranch = null;

        if ($selectedBranchId) {

            $selectedBranch =
                $branches->firstWhere(
                    'id',
                    $selectedBranchId
                );

            if (!$selectedBranch) {
                abort(
                    404,
                    'Selected branch not found.'
                );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Customers
        |--------------------------------------------------------------------------
        */

        $customers = Customer::where(
            'salon_id',
            $salonId
        )
            ->where(
                'status',
                true
            )
            ->when(
                $selectedBranchId,
                function ($query)
                use ($selectedBranchId) {

                    $query->where(
                        'branch_id',
                        $selectedBranchId
                    );
                }
            )
            ->orderBy('name')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Customer Data For JavaScript Search
        |--------------------------------------------------------------------------
        */

        $customerData = $customers
            ->map(function ($customer) {

                return [

                    'id' =>
                        $customer->id,

                    'name' =>
                        $customer->name,

                    'phone' =>
                        $customer->phone,

                    'email' =>
                        $customer->email,

                ];
            })
            ->values()
            ->toArray();


        /*
        |--------------------------------------------------------------------------
        | Staff
        |--------------------------------------------------------------------------
        */

        $staff = Staff::where(
            'salon_id',
            $salonId
        )
            ->where(
                'status',
                true
            )
            ->when(
                $selectedBranchId,
                function ($query)
                use ($selectedBranchId) {

                    $query->where(
                        'branch_id',
                        $selectedBranchId
                    );
                }
            )
            ->orderBy('name')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Services
        |--------------------------------------------------------------------------
        */

        $services = Service::with([

            'variations' => function ($query) {

                $query
                    ->where(
                        'status',
                        true
                    )
                    ->orderBy(
                        'sort_order'
                    )
                    ->orderBy(
                        'name'
                    );
            }

        ])
            ->where(
                'salon_id',
                $salonId
            )
            ->where(
                'status',
                true
            )
            ->orderBy(
                'category'
            )
            ->orderBy(
                'name'
            )
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Service Data For JavaScript
        |--------------------------------------------------------------------------
        */

        $serviceData = $services
            ->mapWithKeys(function ($service) {

                return [

                    $service->id => [

                        'id' =>
                            $service->id,

                        'name' =>
                            $service->name,

                        'category' =>
                            $service->category,

                        'price' =>
                            (float)
                            $service->price,

                        'duration' =>
                            $service->duration,

                        'description' =>
                            $service->description,

                        'variations' =>
                            $service->variations

                                ->map(
                                    function (
                                        $variation
                                    ) {

                                        return [

                                            'id' =>
                                                $variation->id,

                                            'name' =>
                                                $variation->name,

                                            'price' =>
                                                (float)
                                                $variation->price,

                                            'duration' =>
                                                $variation->duration,

                                            'description' =>
                                                $variation->description,

                                        ];
                                    }
                                )

                                ->values()

                                ->toArray(),

                    ],

                ];
            })
            ->toArray();


        /*
        |--------------------------------------------------------------------------
        | Staff Data For JavaScript
        |--------------------------------------------------------------------------
        */

        $staffData = $staff
            ->map(function ($member) {

                return [

                    'id' =>
                        $member->id,

                    'name' =>
                        $member->name,

                    'role' =>
                        $member->role,

                ];
            })
            ->values()
            ->toArray();


        /*
        |--------------------------------------------------------------------------
        | Appointments
        |--------------------------------------------------------------------------
        */

        $appointments = Appointment::with([

            'customer',

            'service',

            'staff',

            'branch'

        ])
            ->where(
                'salon_id',
                $salonId
            )
            ->when(
                $selectedBranchId,
                function ($query)
                use ($selectedBranchId) {

                    $query->where(
                        'branch_id',
                        $selectedBranchId
                    );
                }
            )
            ->whereIn(
                'status',
                [

                    'pending',

                    'confirmed',

                    'arrived',

                    'in_service',

                ]
            )
            ->orderBy(
                'appointment_date'
            )
            ->orderBy(
                'start_time'
            )
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Appointment Data For JavaScript Search
        |--------------------------------------------------------------------------
        */

        $appointmentData = $appointments
            ->map(function ($appointment) {

                return [

                    /*
                    | Appointment
                    */
                    'id' =>
                        $appointment->id,


                    /*
                    | Customer
                    */
                    'customer_id' =>
                        $appointment->customer_id,

                    'customer_name' =>
                        optional(
                            $appointment->customer
                        )->name,

                    'phone' =>
                        optional(
                            $appointment->customer
                        )->phone,


                    /*
                    | Service
                    */
                    'service_id' =>
                        $appointment->service_id,

                    'service_name' =>
                        optional(
                            $appointment->service
                        )->name,

                    'service_price' =>
                        optional(
                            $appointment->service
                        )->price !== null

                            ? (float)
                                $appointment
                                    ->service
                                    ->price

                            : 0,

                    'service_duration' =>
                        optional(
                            $appointment->service
                        )->duration,


                    /*
                    | Staff
                    */
                    'staff_id' =>
                        $appointment->staff_id,

                    'staff_name' =>
                        optional(
                            $appointment->staff
                        )->name,


                    /*
                    | Branch
                    */
                    'branch_id' =>
                        $appointment->branch_id,


                    /*
                    | Appointment Date
                    */
                    'appointment_date' =>
                        $appointment->appointment_date,


                    /*
                    | Appointment Time
                    */
                    'start_time' =>
                        $appointment->start_time,


                    /*
                    | Status
                    */
                    'status' =>
                        $appointment->status,

                ];
            })
            ->values()
            ->toArray();
/*
|--------------------------------------------------------------------------
| Coupons
|--------------------------------------------------------------------------
*/

$coupons = Coupon::where(
    'salon_id',
    $salonId
)
    ->orderBy('code')
    ->get();

        /*
        |--------------------------------------------------------------------------
        | Resume Held Bill
        |--------------------------------------------------------------------------
        */

        $resumeBill = null;

        if (
            $request->filled(
                'resume_bill_id'
            )
        ) {

            $resumeBill =
                Bill::with([

                    'items.service',

                    'items.serviceVariation',

                    'items.staff',

                    'payments',

                ])
                    ->where(
                        'salon_id',
                        $salonId
                    )
                    ->where(
                        'id',
                        $request->integer(
                            'resume_bill_id'
                        )
                    )
                    ->where(
                        'status',
                        'held'
                    )
                    ->first();
        }


        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */

        return view(
            'admin.billing.create',
            compact(

                'salon',

                'branches',

                'selectedBranchId',

                'selectedBranch',

                'customers',

                /*
                | Customer Search
                */
                'customerData',

                'staff',

                'services',

                'appointments',

                'serviceData',

                'staffData',

                'appointmentData',

                'coupons',

                'resumeBill'

            )
        );
    }


    /**
     * Get customers of selected branch.
     */
    public function branchCustomers(
        Request $request
    )
    {
        $user = Auth::user();

        $salon = $user->salon;

        if (!$salon) {

            return response()->json([
                'success' => false,
                'message' => 'Salon not found.'
            ], 403);
        }

        $request->validate([

            'branch_id' => [
                'required',
                'integer'
            ]

        ]);

        $branch = Branch::where(
            'id',
            $request->branch_id
        )
            ->where(
                'salon_id',
                $salon->id
            )
            ->where(
                'status',
                true
            )
            ->first();

        if (!$branch) {

            return response()->json([
                'success' => false,
                'message' => 'Invalid branch.'
            ], 422);
        }

        $customers = Customer::where(
            'salon_id',
            $salon->id
        )
            ->where(
                'branch_id',
                $branch->id
            )
            ->where(
                'status',
                true
            )
            ->orderBy('name')
            ->get([

                'id',

                'name',

                'phone',

                'email'

            ]);

        return response()->json([

            'success' => true,

            'customers' =>
                $customers

        ]);
    }


    /**
     * Get staff of selected branch.
     */
    public function branchStaff(
        Request $request
    )
    {
        $user = Auth::user();

        $salon = $user->salon;

        if (!$salon) {

            return response()->json([
                'success' => false,
                'message' => 'Salon not found.'
            ], 403);
        }

        $request->validate([

            'branch_id' => [
                'required',
                'integer'
            ]

        ]);

        $branch = Branch::where(
            'id',
            $request->branch_id
        )
            ->where(
                'salon_id',
                $salon->id
            )
            ->where(
                'status',
                true
            )
            ->first();

        if (!$branch) {

            return response()->json([
                'success' => false,
                'message' => 'Invalid branch.'
            ], 422);
        }

        $staff = Staff::where(
            'salon_id',
            $salon->id
        )
            ->where(
                'branch_id',
                $branch->id
            )
            ->where(
                'status',
                true
            )
            ->orderBy('name')
            ->get([

                'id',

                'name',

                'role',

                'specialization'

            ]);

        return response()->json([

            'success' => true,

            'staff' =>
                $staff

        ]);
    }


    /**
     * Get services.
     */
    public function services()
    {
        $user = Auth::user();

        $salon = $user->salon;

        if (!$salon) {

            return response()->json([
                'success' => false,
                'message' => 'Salon not found.'
            ], 403);
        }

        $services = Service::with([

            'variations' => function ($query) {

                $query
                    ->where(
                        'status',
                        true
                    )
                    ->orderBy(
                        'sort_order'
                    )
                    ->orderBy(
                        'name'
                    );
            }

        ])
            ->where(
                'salon_id',
                $salon->id
            )
            ->where(
                'status',
                true
            )
            ->orderBy('name')
            ->get();

        return response()->json([

            'success' => true,

            'services' =>
                $services

        ]);
    }


    /**
     * Get appointment details.
     */
    public function appointment(
        Request $request,
        $id
    )
    {
        $user = Auth::user();

        $salon = $user->salon;

        if (!$salon) {

            return response()->json([
                'success' => false,
                'message' => 'Salon not found.'
            ], 403);
        }

        $appointment =
            Appointment::with([

                'customer',

                'service',

                'staff',

                'branch'

            ])
                ->where(
                    'id',
                    $id
                )
                ->where(
                    'salon_id',
                    $salon->id
                )
                ->first();

        if (!$appointment) {

            return response()->json([
                'success' => false,
                'message' =>
                    'Appointment not found.'
            ], 404);
        }

        return response()->json([

            'success' => true,

            'appointment' =>
                $appointment

        ]);
    }


/**
 * Store completed bill.
 */
public function store(Request $request)
{
    $user = Auth::user();

    $salon = $user->salon;

    if (!$salon) {
        throw ValidationException::withMessages([
            'salon' => 'Your account is not linked to a salon.'
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Convert JSON fields
    |--------------------------------------------------------------------------
    */

    foreach (['items', 'payments'] as $field) {

        if (
            $request->has($field) &&
            is_string($request->input($field))
        ) {
            $decoded = json_decode(
                $request->input($field),
                true
            );

            if (json_last_error() === JSON_ERROR_NONE) {
                $request->merge([
                    $field => $decoded
                ]);
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Normalize Frontend Item Fields
    |--------------------------------------------------------------------------
    |
    | Frontend may send:
    | variation_id / price
    |
    | Backend internally uses:
    | service_variation_id / unit_price
    |
    */

    if (is_array($request->input('items'))) {

        $items = $request->input('items');

        foreach ($items as $index => $item) {

            if (
                !isset($item['service_variation_id']) &&
                isset($item['variation_id'])
            ) {
                $items[$index]['service_variation_id'] =
                    $item['variation_id'];
            }

            if (
                !isset($item['unit_price']) &&
                isset($item['price'])
            ) {
                $items[$index]['unit_price'] =
                    $item['price'];
            }
        }

        $request->merge([
            'items' => $items
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Validation
    |--------------------------------------------------------------------------
    */

    $validated = $request->validate([

        'branch_id' => [
            'required',
            'integer'
        ],

        'customer_id' => [
            'nullable',
            'integer'
        ],

        'appointment_id' => [
            'nullable',
            'integer'
        ],

        'resume_bill_id' => [
            'nullable',
            'integer'
        ],

        /*
        | Manual overall discount only
        */
        'discount' => [
            'nullable',
            'numeric',
            'min:0'
        ],

        'coupon_code' => [
            'nullable',
            'string',
            'max:50'
        ],

        'tax_mode' => [
            'required',
            'in:with_tax,without_tax'
        ],

        'tax_percentage' => [
            'nullable',
            'numeric',
            'min:0',
            'max:100'
        ],

        'notes' => [
            'nullable',
            'string'
        ],

        'items' => [
            'required',
            'array',
            'min:1'
        ],

        'items.*.service_id' => [
            'required',
            'integer'
        ],

        'items.*.service_variation_id' => [
            'nullable',
            'integer'
        ],

        'items.*.staff_id' => [
            'required',
            'integer'
        ],

        'items.*.quantity' => [
            'required',
            'numeric',
            'min:1'
        ],

        /*
        | Frontend price is accepted,
        | but backend will NOT trust it for final billing.
        */
        'items.*.unit_price' => [
            'required',
            'numeric',
            'min:0'
        ],

        'items.*.discount' => [
            'nullable',
            'numeric',
            'min:0'
        ],

        'payments' => [
            'nullable',
            'array'
        ],

        'payments.*.payment_method' => [
            'required',
            'in:cash,upi,card,bank_transfer,online'
        ],

        'payments.*.amount' => [
            'required',
            'numeric',
            'min:0'
        ],

        'payments.*.transaction_id' => [
            'nullable',
            'string',
            'max:255'
        ],

        'payments.*.notes' => [
            'nullable',
            'string'
        ],
    ]);

    /*
    |--------------------------------------------------------------------------
    | Validate Branch
    |--------------------------------------------------------------------------
    */

    $branch = Branch::where(
        'id',
        $validated['branch_id']
    )
        ->where(
            'salon_id',
            $salon->id
        )
        ->first();

    if (!$branch) {
        throw ValidationException::withMessages([
            'branch_id' =>
                'Selected branch does not belong to this salon.'
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Branch Status
    |--------------------------------------------------------------------------
    |
    | Supports:
    | active
    | 1
    | true
    |
    */

    $branchStatus = strtolower(
        trim((string) $branch->status)
    );

    if (
        !in_array(
            $branchStatus,
            ['active', '1', 'true'],
            true
        )
    ) {
        throw ValidationException::withMessages([
            'branch_id' =>
                'Selected branch is currently inactive.'
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Validate Customer
    |--------------------------------------------------------------------------
    */

    $customer = null;

    if (!empty($validated['customer_id'])) {

        $customer = Customer::where(
            'id',
            $validated['customer_id']
        )
            ->where(
                'salon_id',
                $salon->id
            )
            ->where(
                'branch_id',
                $branch->id
            )
            ->first();

        if (!$customer) {
            throw ValidationException::withMessages([
                'customer_id' =>
                    'Selected customer must belong to selected branch.'
            ]);
        }

        /*
        | Customer status
        */

        $customerStatus = strtolower(
            trim((string) $customer->status)
        );

        if (
            !in_array(
                $customerStatus,
                ['active', '1', 'true'],
                true
            )
        ) {
            throw ValidationException::withMessages([
                'customer_id' =>
                    'Selected customer is inactive.'
            ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Validate Appointment
    |--------------------------------------------------------------------------
    */

    $appointment = null;

    if (!empty($validated['appointment_id'])) {

        if (!$customer) {
            throw ValidationException::withMessages([
                'customer_id' =>
                    'A customer must be selected when using an appointment.'
            ]);
        }

        $appointment = Appointment::where(
            'id',
            $validated['appointment_id']
        )
            ->where(
                'salon_id',
                $salon->id
            )
            ->where(
                'branch_id',
                $branch->id
            )
            ->where(
                'customer_id',
                $customer->id
            )
            ->first();

        if (!$appointment) {
            throw ValidationException::withMessages([
                'appointment_id' =>
                    'Selected appointment is invalid.'
            ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Resume Existing Held Bill
    |--------------------------------------------------------------------------
    */

    $resumeBill = null;

    if (!empty($validated['resume_bill_id'])) {

        $resumeBill = Bill::where(
            'id',
            $validated['resume_bill_id']
        )
            ->where(
                'salon_id',
                $salon->id
            )
            ->where(
                'branch_id',
                $branch->id
            )
            ->where(
                'status',
                'held'
            )
            ->first();

        if (!$resumeBill) {
            throw ValidationException::withMessages([
                'resume_bill_id' =>
                    'Held bill not found or already completed.'
            ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Tax Mode
    |--------------------------------------------------------------------------
    */

    $taxMode = $validated['tax_mode'];

    if ($taxMode === 'without_tax') {

        $taxPercentage = 0;

    } else {

        $taxPercentage = isset(
            $validated['tax_percentage']
        )
            ? (float) $validated['tax_percentage']
            : (float) ($salon->tax_percentage ?? 0);
    }

    $taxPercentage = round(
        max(0, $taxPercentage),
        2
    );

    /*
    |--------------------------------------------------------------------------
    | Manual Overall Discount
    |--------------------------------------------------------------------------
    |
    | IMPORTANT:
    | Blank discount = 0
    | Coupon is NOT included here.
    |
    */

    $manualDiscount = isset(
        $validated['discount']
    )
        ? (float) $validated['discount']
        : 0;

    $manualDiscount = round(
        max(0, $manualDiscount),
        2
    );

    /*
    |--------------------------------------------------------------------------
    | Active Customer Packages
    |--------------------------------------------------------------------------
    */

    $activeCustomerPackages = collect();

    if ($customer) {

        $activeCustomerPackages =
            CustomerPackage::where(
                'salon_id',
                $salon->id
            )
                ->where(
                    'customer_id',
                    $customer->id
                )
                ->where(
                    'status',
                    'active'
                )
                ->where(
                    'remaining_uses',
                    '>',
                    0
                )
                ->where(function ($query) {

                    $query
                        ->whereNull('expiry_date')
                        ->orWhereDate(
                            'expiry_date',
                            '>=',
                            today()
                        );
                })
                ->orderByRaw(
                    'CASE WHEN expiry_date IS NULL THEN 1 ELSE 0 END'
                )
                ->orderBy(
                    'expiry_date'
                )
                ->orderBy(
                    'id'
                )
                ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Reserved Package Uses
    |--------------------------------------------------------------------------
    */

    $reservedPackageUses = [];

    /*
    |--------------------------------------------------------------------------
    | Calculate Services
    |--------------------------------------------------------------------------
    */

    $serviceTotal = 0;

    $preparedItems = [];

    foreach (
        $validated['items']
        as $index => $item
    ) {

        /*
        |--------------------------------------------------------------------------
        | Service
        |--------------------------------------------------------------------------
        */

        $service = Service::where(
            'id',
            $item['service_id']
        )
            ->where(
                'salon_id',
                $salon->id
            )
            ->first();

        if (!$service) {
            throw ValidationException::withMessages([
                "items.$index.service_id" =>
                    'Selected service is invalid.'
            ]);
        }

        $serviceStatus = strtolower(
            trim((string) $service->status)
        );

        if (
            !in_array(
                $serviceStatus,
                ['active', '1', 'true'],
                true
            )
        ) {
            throw ValidationException::withMessages([
                "items.$index.service_id" =>
                    'Selected service is inactive.'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Staff
        |--------------------------------------------------------------------------
        */

        $staff = Staff::where(
            'id',
            $item['staff_id']
        )
            ->where(
                'salon_id',
                $salon->id
            )
            ->where(
                'branch_id',
                $branch->id
            )
            ->first();

        if (!$staff) {
            throw ValidationException::withMessages([
                "items.$index.staff_id" =>
                    'Selected staff is invalid for this branch.'
            ]);
        }

        $staffStatus = strtolower(
            trim((string) $staff->status)
        );

        if (
            !in_array(
                $staffStatus,
                ['active', '1', 'true'],
                true
            )
        ) {
            throw ValidationException::withMessages([
                "items.$index.staff_id" =>
                    'Selected staff is inactive.'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Variation
        |--------------------------------------------------------------------------
        */

        $variation = null;

        if (!empty($item['service_variation_id'])) {

            $variation = ServiceVariation::where(
                'id',
                $item['service_variation_id']
            )
                ->where(
                    'service_id',
                    $service->id
                )
                ->first();

            if (!$variation) {
                throw ValidationException::withMessages([
                    "items.$index.service_variation_id" =>
                        'Selected service variation is invalid.'
                ]);
            }

            $variationStatus = strtolower(
                trim((string) $variation->status)
            );

            if (
                !in_array(
                    $variationStatus,
                    ['active', '1', 'true'],
                    true
                )
            ) {
                throw ValidationException::withMessages([
                    "items.$index.service_variation_id" =>
                        'Selected service variation is inactive.'
                ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Quantity
        |--------------------------------------------------------------------------
        */

        $quantity = (float) $item['quantity'];

        if ($quantity <= 0) {
            throw ValidationException::withMessages([
                "items.$index.quantity" =>
                    'Quantity must be greater than zero.'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Backend Price
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        | Never trust frontend price for final billing.
        |
        */

        $unitPrice = $variation
            ? (float) $variation->price
            : (float) $service->price;

        $unitPrice = round(
            max(0, $unitPrice),
            2
        );

        $grossAmount = round(
            $unitPrice * $quantity,
            2
        );

        /*
        |--------------------------------------------------------------------------
        | Item Discount
        |--------------------------------------------------------------------------
        */

        $itemDiscount = isset(
            $item['discount']
        )
            ? (float) $item['discount']
            : 0;

        $itemDiscount = min(
            max(0, $itemDiscount),
            $grossAmount
        );

        $itemDiscount = round(
            $itemDiscount,
            2
        );

        $netAmount = max(
            0,
            round(
                $grossAmount - $itemDiscount,
                2
            )
        );

        /*
        |--------------------------------------------------------------------------
        | Item Name
        |--------------------------------------------------------------------------
        */

        $itemName = $service->name;

        if ($variation) {
            $itemName .=
                ' - ' . $variation->name;
        }

        /*
        |--------------------------------------------------------------------------
        | Package Matching
        |--------------------------------------------------------------------------
        */

        $packageCovered = false;

        $customerPackageId = null;

        $packageServiceId = null;

        if (
            $customer &&
            $activeCustomerPackages->isNotEmpty()
        ) {

            if (
                $quantity != floor($quantity)
            ) {
                throw ValidationException::withMessages([
                    "items.$index.quantity" =>
                        'Package service quantity must be a whole number.'
                ]);
            }

            $packageQuantity = (int) $quantity;

            foreach (
                $activeCustomerPackages
                as $customerPackage
            ) {

                $alreadyReserved =
                    $reservedPackageUses[
                        $customerPackage->id
                    ] ?? 0;

                $availablePackageUses =
                    (int) $customerPackage->remaining_uses
                    -
                    (int) $alreadyReserved;

                if (
                    $availablePackageUses
                    <
                    $packageQuantity
                ) {
                    continue;
                }

                $packageServices =
                    PackageService::where(
                        'package_id',
                        $customerPackage->package_id
                    )
                        ->where(
                            'service_id',
                            $service->id
                        )
                        ->get();

                $matchedPackageService = null;

                if ($variation) {

                    $matchedPackageService =
                        $packageServices->first(
                            function ($packageService) use ($variation) {

                                return
                                    !is_null(
                                        $packageService
                                            ->service_variation_id
                                    )
                                    &&
                                    (int)
                                        $packageService
                                            ->service_variation_id
                                    ===
                                    (int)
                                        $variation->id;
                            }
                        );
                }

                if (!$matchedPackageService) {

                    $matchedPackageService =
                        $packageServices->first(
                            function ($packageService) {

                                return is_null(
                                    $packageService
                                        ->service_variation_id
                                );
                            }
                        );
                }

                if ($matchedPackageService) {

                    $packageCovered = true;

                    $customerPackageId =
                        $customerPackage->id;

                    $packageServiceId =
                        $matchedPackageService->id;

                    $reservedPackageUses[
                        $customerPackage->id
                    ] =
                        (
                            $reservedPackageUses[
                                $customerPackage->id
                            ] ?? 0
                        )
                        +
                        $packageQuantity;

                    $unitPrice = 0;

                    $itemDiscount = 0;

                    $netAmount = 0;

                    $itemName .= ' (Package)';

                    break;
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Service Total
        |--------------------------------------------------------------------------
        |
        | Item discount is already included here.
        |
        */

        $serviceTotal += $netAmount;

        $serviceTotal = round(
            $serviceTotal,
            2
        );

        /*
        |--------------------------------------------------------------------------
        | Prepared Item
        |--------------------------------------------------------------------------
        */

        $preparedItems[] = [

            'service_id' =>
                $service->id,

            'service_variation_id' =>
                $variation?->id,

            'staff_id' =>
                $staff->id,

            'item_name' =>
                $itemName,

            'quantity' =>
                $quantity,

            'unit_price' =>
                $unitPrice,

            'discount' =>
                $itemDiscount,

            'net_amount' =>
                $netAmount,

            'package_covered' =>
                $packageCovered,

            'customer_package_id' =>
                $customerPackageId,

            'package_service_id' =>
                $packageServiceId,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Overall Manual Discount
    |--------------------------------------------------------------------------
    */

    $manualDiscount = min(
        max(0, $manualDiscount),
        $serviceTotal
    );

    $manualDiscount = round(
        $manualDiscount,
        2
    );

    /*
    |--------------------------------------------------------------------------
    | Coupon
    |--------------------------------------------------------------------------
    */

    $coupon = null;

    $couponDiscount = 0;

    $couponCode = !empty(
        $validated['coupon_code']
    )
        ? strtoupper(
            trim(
                $validated['coupon_code']
            )
        )
        : null;

    if ($couponCode) {

        $coupon = Coupon::where(
            'salon_id',
            $salon->id
        )
            ->whereRaw(
                'UPPER(code) = ?',
                [$couponCode]
            )
            ->where(
                'status',
                'active'
            )
            ->where(function ($query) use ($branch) {

                $query
                    ->whereNull('branch_id')
                    ->orWhere(
                        'branch_id',
                        $branch->id
                    );
            })
            ->first();

        if (!$coupon) {
            throw ValidationException::withMessages([
                'coupon_code' =>
                    'Invalid or inactive coupon code.'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Date Only Validation
        |--------------------------------------------------------------------------
        */

        $today = now()->startOfDay();

        if ($coupon->valid_from) {

            $validFrom = Carbon::parse(
                $coupon->valid_from
            )->startOfDay();

            if ($today->lt($validFrom)) {
                throw ValidationException::withMessages([
                    'coupon_code' =>
                        'This coupon is not active yet.'
                ]);
            }
        }

        if ($coupon->valid_until) {

            $validUntil = Carbon::parse(
                $coupon->valid_until
            )->endOfDay();

            if ($today->gt($validUntil)) {
                throw ValidationException::withMessages([
                    'coupon_code' =>
                        'This coupon has expired.'
                ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Usage Limit
        |--------------------------------------------------------------------------
        */

        if (
            !is_null($coupon->usage_limit) &&
            (int) $coupon->used_count >=
            (int) $coupon->usage_limit
        ) {
            throw ValidationException::withMessages([
                'coupon_code' =>
                    'This coupon usage limit has been reached.'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Coupon Eligible Amount
        |--------------------------------------------------------------------------
        |
        | serviceTotal already contains item-level discount.
        | Therefore item discount must NOT be subtracted again.
        |
        */

        $couponEligibleAmount = max(
            0,
            round(
                $serviceTotal -
                $manualDiscount,
                2
            )
        );

        /*
        |--------------------------------------------------------------------------
        | Minimum Bill
        |--------------------------------------------------------------------------
        */

        if (
            $couponEligibleAmount <
            (float) $coupon->minimum_bill_amount
        ) {
            throw ValidationException::withMessages([
                'coupon_code' =>
                    'Minimum bill amount for this coupon is ₹' .
                    number_format(
                        $coupon->minimum_bill_amount,
                        2
                    )
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Per Customer Limit
        |--------------------------------------------------------------------------
        */

        if (
            $customer &&
            !is_null(
                $coupon->per_customer_limit
            )
        ) {

            $customerCouponUses =
                CouponUsage::where(
                    'coupon_id',
                    $coupon->id
                )
                    ->where(
                        'customer_id',
                        $customer->id
                    )
                    ->count();

            if (
                $customerCouponUses >=
                (int) $coupon->per_customer_limit
            ) {
                throw ValidationException::withMessages([
                    'coupon_code' =>
                        'You have already reached the usage limit for this coupon.'
                ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Calculate Coupon Discount
        |--------------------------------------------------------------------------
        */

        if (
            $coupon->discount_type ===
            'percentage'
        ) {

            $couponDiscount = round(
                (
                    $couponEligibleAmount *
                    (float) $coupon->discount_value
                ) / 100,
                2
            );

        } else {

            /*
            | Fixed coupon.
            | Example: ₹10 coupon = exactly ₹10 discount.
            */

            $couponDiscount = (float)
                $coupon->discount_value;
        }

        /*
        |--------------------------------------------------------------------------
        | Maximum Discount
        |--------------------------------------------------------------------------
        */

        if (
            !is_null(
                $coupon->maximum_discount
            )
        ) {

            $couponDiscount = min(
                $couponDiscount,
                (float)
                    $coupon->maximum_discount
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Coupon Cannot Exceed Eligible Amount
        |--------------------------------------------------------------------------
        */

        $couponDiscount = min(
            max(0, $couponDiscount),
            $couponEligibleAmount
        );

        $couponDiscount = round(
            $couponDiscount,
            2
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Total Discount
    |--------------------------------------------------------------------------
    |
    | IMPORTANT:
    |
    | serviceTotal already has item-level discount.
    |
    | So here only:
    | Manual Discount + Coupon Discount
    |
    */

    $totalDiscount = round(
        $manualDiscount +
        $couponDiscount,
        2
    );

    /*
    |--------------------------------------------------------------------------
    | Taxable Amount
    |--------------------------------------------------------------------------
    */

    $taxableAmount = max(
        0,
        round(
            $serviceTotal -
            $totalDiscount,
            2
        )
    );

    /*
    |--------------------------------------------------------------------------
    | Tax
    |--------------------------------------------------------------------------
    */

    if ($taxMode === 'without_tax') {

        $taxAmount = 0;

    } else {

        $taxAmount = round(
            (
                $taxableAmount *
                $taxPercentage
            ) / 100,
            2
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Products
    |--------------------------------------------------------------------------
    */

    $productAmount = 0;

    /*
    |--------------------------------------------------------------------------
    | Grand Total
    |--------------------------------------------------------------------------
    */

    $grandTotal = round(
        $taxableAmount +
        $taxAmount +
        $productAmount,
        2
    );

    /*
    |--------------------------------------------------------------------------
    | Payments
    |--------------------------------------------------------------------------
    */

    $payments =
        $validated['payments'] ?? [];

    if (!is_array($payments)) {
        $payments = [];
    }

    $normalizedPayments = [];

    $remainingPayment = round(
        $grandTotal,
        2
    );

    foreach ($payments as $payment) {

        if ($remainingPayment <= 0) {
            break;
        }

        $amount = round(
            (float) (
                $payment['amount'] ?? 0
            ),
            2
        );

        if ($amount <= 0) {
            continue;
        }

        $amount = min(
            $amount,
            $remainingPayment
        );

        $amount = round(
            $amount,
            2
        );

        if ($amount <= 0) {
            continue;
        }

        $normalizedPayments[] = [

            'payment_method' =>
                $payment['payment_method'],

            'amount' =>
                $amount,

            'transaction_id' =>
                $payment['transaction_id']
                ?? null,

            'notes' =>
                $payment['notes']
                ?? null,
        ];

        $remainingPayment = round(
            $remainingPayment -
            $amount,
            2
        );
    }

    $payments = $normalizedPayments;

    /*
    |--------------------------------------------------------------------------
    | Paid Amount
    |--------------------------------------------------------------------------
    */

    $paidAmount = 0;

    foreach ($payments as $payment) {

        $amount = round(
            (float) (
                $payment['amount'] ?? 0
            ),
            2
        );

        if ($amount > 0) {
            $paidAmount += $amount;
        }
    }

    $paidAmount = round(
        $paidAmount,
        2
    );

    $grandTotal = round(
        $grandTotal,
        2
    );

    /*
    |--------------------------------------------------------------------------
    | Payment Difference
    |--------------------------------------------------------------------------
    */

    $paymentDifference = round(
        $paidAmount -
        $grandTotal,
        2
    );

    if ($paymentDifference > 0.01) {

        throw ValidationException::withMessages([
            'payments' =>
                'Payment amount cannot be greater than grand total.'
        ]);
    }

    if (
        $paidAmount > $grandTotal &&
        $paymentDifference <= 0.01
    ) {
        $paidAmount = $grandTotal;
    }

    /*
    |--------------------------------------------------------------------------
    | Payment Status
    |--------------------------------------------------------------------------
    */

    if ($grandTotal <= 0) {

        $paidAmount = 0;

        $pendingAmount = 0;

        $paymentStatus = 'paid';

    } else {

        $pendingAmount = round(
            max(
                0,
                $grandTotal -
                $paidAmount
            ),
            2
        );

        if ($paidAmount <= 0) {

            $paymentStatus = 'pending';

        } elseif ($paidAmount < $grandTotal) {

            $paymentStatus = 'partial';

        } else {

            $paidAmount = $grandTotal;

            $pendingAmount = 0;

            $paymentStatus = 'paid';
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Payment Method
    |--------------------------------------------------------------------------
    */

    $paymentMethod = null;

    $validPayments = array_values(
        array_filter(
            $payments,
            function ($payment) {

                return
                    (float) (
                        $payment['amount'] ?? 0
                    ) > 0;
            }
        )
    );

    if (count($validPayments) === 1) {

        $paymentMethod =
            $validPayments[0]['payment_method'];

    } elseif (count($validPayments) > 1) {

        $paymentMethod = 'online';
    }

    /*
    |--------------------------------------------------------------------------
    | Save Bill
    |--------------------------------------------------------------------------
    */

    $bill = DB::transaction(
        function () use (

            $salon,
            $branch,
            $customer,
            $appointment,
            $validated,
            $preparedItems,
            $serviceTotal,
            $totalDiscount,
            $coupon,
            $couponCode,
            $couponDiscount,
            $taxPercentage,
            $taxAmount,
            $productAmount,
            $grandTotal,
            $paidAmount,
            $pendingAmount,
            $paymentMethod,
            $paymentStatus,
            $payments,
            $resumeBill

        ) {

            /*
            |--------------------------------------------------------------------------
            | Create / Update Bill
            |--------------------------------------------------------------------------
            */

            if ($resumeBill) {

                $bill = $resumeBill;

                $bill->update([

                    'branch_id' =>
                        $branch->id,

                    'customer_id' =>
                        $customer?->id,

                    'appointment_id' =>
                        $appointment?->id,

                    'bill_date' =>
                        now()->toDateString(),

                    'service_total' =>
                        $serviceTotal,

                    'subtotal' =>
                        $serviceTotal,

                    'discount' =>
                        $totalDiscount,

                    'coupon_id' =>
                        $coupon?->id,

                    'coupon_code' =>
                        $couponCode,

                    'coupon_discount' =>
                        $couponDiscount,

                    'tax_percentage' =>
                        $taxPercentage,

                    'tax_amount' =>
                        $taxAmount,

                    'product_amount' =>
                        $productAmount,

                    'grand_total' =>
                        $grandTotal,

                    'total' =>
                        $grandTotal,

                    'paid_amount' =>
                        $paidAmount,

                    'pending_amount' =>
                        $pendingAmount,

                    'payment_method' =>
                        $paymentMethod,

                    'payment_status' =>
                        $paymentStatus,

                    'transaction_id' =>
                        null,

                    'status' =>
                        'completed',

                    'bill_status' =>
                        'completed',

                    'notes' =>
                        $validated['notes']
                        ?? null,
                ]);

                /*
                | Delete old items
                */

                BillItem::where(
                    'bill_id',
                    $bill->id
                )->delete();

                /*
                | Delete old payments
                */

                BillPayment::where(
                    'bill_id',
                    $bill->id
                )->delete();

                /*
                | Delete old coupon usage
                */

                CouponUsage::where(
                    'bill_id',
                    $bill->id
                )->delete();

            } else {

                /*
                |--------------------------------------------------------------------------
                | Generate Invoice Number
                |--------------------------------------------------------------------------
                */

                $invoiceNumber =
                    $this->generateInvoiceNumber(
                        $salon
                    );

                /*
                |--------------------------------------------------------------------------
                | Create Bill
                |--------------------------------------------------------------------------
                */

                $bill = Bill::create([

                    'salon_id' =>
                        $salon->id,

                    'branch_id' =>
                        $branch->id,

                    'customer_id' =>
                        $customer?->id,

                    'appointment_id' =>
                        $appointment?->id,

                    'invoice_number' =>
                        $invoiceNumber,

                    'bill_date' =>
                        now()->toDateString(),

                    'service_total' =>
                        $serviceTotal,

                    'subtotal' =>
                        $serviceTotal,

                    'discount' =>
                        $totalDiscount,

                    'coupon_id' =>
                        $coupon?->id,

                    'coupon_code' =>
                        $couponCode,

                    'coupon_discount' =>
                        $couponDiscount,

                    'tax_percentage' =>
                        $taxPercentage,

                    'tax_amount' =>
                        $taxAmount,

                    'product_amount' =>
                        $productAmount,

                    'grand_total' =>
                        $grandTotal,

                    'total' =>
                        $grandTotal,

                    'paid_amount' =>
                        $paidAmount,

                    'pending_amount' =>
                        $pendingAmount,

                    'payment_method' =>
                        $paymentMethod,

                    'payment_status' =>
                        $paymentStatus,

                    'transaction_id' =>
                        null,

                    'status' =>
                        'completed',

                    'bill_status' =>
                        'completed',

                    'notes' =>
                        $validated['notes']
                        ?? null,
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Bill Items
            |--------------------------------------------------------------------------
            */

            foreach ($preparedItems as $item) {

                $itemTax = 0;

                if (
                    $taxPercentage > 0 &&
                    $serviceTotal > 0 &&
                    !$item['package_covered']
                ) {

                    $itemTax = round(
                        (
                            $item['net_amount'] /
                            $serviceTotal
                        ) *
                        $taxAmount,
                        2
                    );
                }

                if (
                    $item['package_covered'] ||
                    $taxPercentage <= 0
                ) {
                    $itemTax = 0;
                }

                $itemTotal = round(
                    $item['net_amount'] +
                    $itemTax,
                    2
                );

                BillItem::create([

                    'bill_id' =>
                        $bill->id,

                    'service_id' =>
                        $item['service_id'],

                    'service_variation_id' =>
                        $item['service_variation_id'],

                    'staff_id' =>
                        $item['staff_id'],

                    'item_name' =>
                        $item['item_name'],

                    'quantity' =>
                        $item['quantity'],

                    'unit_price' =>
                        $item['unit_price'],

                    'discount' =>
                        $item['discount'],

                    'tax_percentage' =>
                        $item['package_covered']
                            ? 0
                            : $taxPercentage,

                    'tax_amount' =>
                        $itemTax,

                    'total' =>
                        $itemTotal,
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Package Usage
            |--------------------------------------------------------------------------
            */

            foreach ($preparedItems as $item) {

                if (
                    empty(
                        $item['package_covered']
                    )
                ) {
                    continue;
                }

                $customerPackage =
                    CustomerPackage::where(
                        'id',
                        $item['customer_package_id']
                    )
                        ->where(
                            'salon_id',
                            $salon->id
                        )
                        ->where(
                            'customer_id',
                            $customer->id
                        )
                        ->lockForUpdate()
                        ->first();

                if (!$customerPackage) {
                    throw ValidationException::withMessages([
                        'items' =>
                            'Customer package could not be found.'
                    ]);
                }

                if (
                    $customerPackage->status !==
                    'active'
                ) {
                    throw ValidationException::withMessages([
                        'items' =>
                            'Selected customer package is no longer active.'
                    ]);
                }

                if (
                    $customerPackage->expiry_date &&
                    $customerPackage->expiry_date->lt(today())
                ) {

                    $customerPackage->status =
                        'expired';

                    $customerPackage->save();

                    throw ValidationException::withMessages([
                        'items' =>
                            'Selected customer package has expired.'
                    ]);
                }

                $quantity = (int)
                    $item['quantity'];

                if ($quantity <= 0) {
                    throw ValidationException::withMessages([
                        'items' =>
                            'Package quantity must be greater than zero.'
                    ]);
                }

                if (
                    (int)
                        $customerPackage->remaining_uses
                    <
                    $quantity
                ) {
                    throw ValidationException::withMessages([
                        'items' =>
                            'Not enough package service balance remaining.'
                    ]);
                }

                $packageService =
                    PackageService::where(
                        'id',
                        $item['package_service_id']
                    )
                        ->where(
                            'package_id',
                            $customerPackage->package_id
                        )
                        ->where(
                            'service_id',
                            $item['service_id']
                        )
                        ->first();

                if (!$packageService) {
                    throw ValidationException::withMessages([
                        'items' =>
                            'Package service configuration is invalid.'
                    ]);
                }

                if (
                    !is_null(
                        $packageService
                            ->service_variation_id
                    )
                ) {

                    if (
                        empty(
                            $item[
                                'service_variation_id'
                            ]
                        )
                        ||
                        (int)
                            $packageService
                                ->service_variation_id
                        !==
                        (int)
                            $item[
                                'service_variation_id'
                            ]
                    ) {
                        throw ValidationException::withMessages([
                            'items' =>
                                'Selected service variation is not allowed in this package.'
                        ]);
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | Create Package Usage
                |--------------------------------------------------------------------------
                */

                $packageUsage =
                    PackageUsage::create([

                        'salon_id' =>
                            $salon->id,

                        'customer_package_id' =>
                            $customerPackage->id,

                        'customer_id' =>
                            $customer->id,

                        'package_service_id' =>
                            $packageService->id,

                        'service_id' =>
                            $item['service_id'],

                        'service_variation_id' =>
                            $item[
                                'service_variation_id'
                            ],

                        'appointment_id' =>
                            $appointment?->id,

                        'bill_id' =>
                            $bill->id,

                        'quantity' =>
                            $quantity,

                        'used_date' =>
                            today(),

                        'notes' =>
                            'Service redeemed through POS billing.',
                    ]);

                if (!$packageUsage->exists) {
                    throw ValidationException::withMessages([
                        'items' =>
                            'Package usage record could not be created.'
                    ]);
                }

                /*
                |--------------------------------------------------------------------------
                | Update Package Balance
                |--------------------------------------------------------------------------
                */

                $newUsedUses =
                    (int)
                        $customerPackage->used_uses
                    +
                    $quantity;

                $newRemainingUses =
                    max(
                        0,
                        (int)
                            $customerPackage
                                ->remaining_uses
                        -
                        $quantity
                    );

                $customerPackage->used_uses =
                    $newUsedUses;

                $customerPackage->remaining_uses =
                    $newRemainingUses;

                $customerPackage->status =
                    $newRemainingUses <= 0
                        ? 'exhausted'
                        : 'active';

                $customerPackage->save();
            }

            /*
            |--------------------------------------------------------------------------
            | Coupon Usage
            |--------------------------------------------------------------------------
            */

            if (
                $coupon &&
                $couponDiscount > 0
            ) {

                $lockedCoupon =
                    Coupon::where(
                        'id',
                        $coupon->id
                    )
                        ->where(
                            'salon_id',
                            $salon->id
                        )
                        ->lockForUpdate()
                        ->first();

                if (!$lockedCoupon) {
                    throw ValidationException::withMessages([
                        'coupon_code' =>
                            'Coupon could not be found.'
                    ]);
                }

                if (
                    $lockedCoupon->status !==
                    'active'
                ) {
                    throw ValidationException::withMessages([
                        'coupon_code' =>
                            'This coupon is no longer active.'
                    ]);
                }

                if (
                    !is_null(
                        $lockedCoupon->usage_limit
                    )
                    &&
                    (int)
                        $lockedCoupon->used_count
                    >=
                    (int)
                        $lockedCoupon->usage_limit
                ) {
                    throw ValidationException::withMessages([
                        'coupon_code' =>
                            'This coupon usage limit has been reached.'
                    ]);
                }

                if (
                    $customer &&
                    !is_null(
                        $lockedCoupon
                            ->per_customer_limit
                    )
                ) {

                    $customerCouponUses =
                        CouponUsage::where(
                            'coupon_id',
                            $lockedCoupon->id
                        )
                            ->where(
                                'customer_id',
                                $customer->id
                            )
                            ->count();

                    if (
                        $customerCouponUses >=
                        (int)
                            $lockedCoupon
                                ->per_customer_limit
                    ) {
                        throw ValidationException::withMessages([
                            'coupon_code' =>
                                'You have already reached the usage limit for this coupon.'
                        ]);
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | Create Coupon Usage
                |--------------------------------------------------------------------------
                */

                CouponUsage::create([

                    'salon_id' =>
                        $salon->id,

                    'branch_id' =>
                        $branch->id,

                    'coupon_id' =>
                        $lockedCoupon->id,

                    'customer_id' =>
                        $customer?->id,

                    'bill_id' =>
                        $bill->id,

                    'discount_amount' =>
                        $couponDiscount,

                    'used_at' =>
                        now(),
                ]);

                /*
                |--------------------------------------------------------------------------
                | Increment Usage
                |--------------------------------------------------------------------------
                */

                $lockedCoupon->increment(
                    'used_count'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Bill Payments
            |--------------------------------------------------------------------------
            */

            foreach ($payments as $payment) {

                $amount = round(
                    (float) (
                        $payment['amount'] ?? 0
                    ),
                    2
                );

                if ($amount <= 0) {
                    continue;
                }

                BillPayment::create([

                    'bill_id' =>
                        $bill->id,

                    'payment_method' =>
                        $payment[
                            'payment_method'
                        ],

                    'amount' =>
                        $amount,

                    'transaction_id' =>
                        $payment[
                            'transaction_id'
                        ] ?? null,

                    'notes' =>
                        $payment['notes']
                        ?? null,
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Complete Appointment
            |--------------------------------------------------------------------------
            */

            if ($appointment) {

                $appointment->update([
                    'status' => 'completed'
                ]);
            }

            return $bill;
        }
    );

    /*
    |--------------------------------------------------------------------------
    | Redirect Invoice
    |--------------------------------------------------------------------------
    */

    return redirect()
        ->route(
            'admin.billing.invoice',
            $bill->id
        )
        ->with(
            'success',
            'Bill completed successfully.'
        );
}


/**
 * All Completed Bills.
 */
public function allBills(Request $request)
{
    $user = Auth::user();

    $salon = $user->salon;

    if (!$salon) {
        abort(
            403,
            'Your account is not linked to a salon.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | User Branch
    |--------------------------------------------------------------------------
    |
    | branch_id = null => Main Admin / Salon Admin
    | branch_id != null => Branch User
    |
    */

    $userBranchId = $user->branch_id;

    /*
    |--------------------------------------------------------------------------
    | Base Query
    |--------------------------------------------------------------------------
    */

    $baseQuery = Bill::query()
        ->where('salon_id', $salon->id)
        ->where('status', 'completed');

    /*
    |--------------------------------------------------------------------------
    | Branch Access Restriction
    |--------------------------------------------------------------------------
    |
    | Branch user:
    | Only own branch bills.
    |
    | Main admin:
    | Can view all branches and use branch filter.
    |
    */

    if (!empty($userBranchId)) {

        $baseQuery->where(
            'branch_id',
            $userBranchId
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Search Filter
    |--------------------------------------------------------------------------
    */

    if ($request->filled('search')) {

        $search = trim($request->search);

        $baseQuery->where(function ($q) use ($search) {

            $q->where(
                'invoice_number',
                'like',
                "%{$search}%"
            )

            ->orWhereHas(
                'customer',
                function ($customerQuery) use ($search) {

                    $customerQuery
                        ->where(
                            'name',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'phone',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'email',
                            'like',
                            "%{$search}%"
                        );
                }
            );
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Branch Filter
    |--------------------------------------------------------------------------
    |
    | Main admin:
    | Selected branch filter works.
    |
    | Branch user:
    | Request branch_id is ignored because own branch restriction
    | is already applied above.
    |
    */

    if (
        empty($userBranchId) &&
        $request->filled('branch_id')
    ) {

        $baseQuery->where(
            'branch_id',
            $request->branch_id
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Payment Status Filter
    |--------------------------------------------------------------------------
    */

    if ($request->filled('payment_status')) {

        $paymentStatus = $request->payment_status;

        if (
            in_array(
                $paymentStatus,
                ['paid', 'partial', 'pending'],
                true
            )
        ) {

            $baseQuery->where(
                'payment_status',
                $paymentStatus
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | From Date Filter
    |--------------------------------------------------------------------------
    */

    if ($request->filled('from_date')) {

        $baseQuery->whereDate(
            'bill_date',
            '>=',
            $request->from_date
        );
    }

    /*
    |--------------------------------------------------------------------------
    | To Date Filter
    |--------------------------------------------------------------------------
    */

    if ($request->filled('to_date')) {

        $baseQuery->whereDate(
            'bill_date',
            '<=',
            $request->to_date
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Bills List
    |--------------------------------------------------------------------------
    */

    $bills = (clone $baseQuery)
        ->with([
            'branch',
            'customer',
            'items.service',
            'items.staff',
            'payments',
        ])
        ->latest('id')
        ->paginate(15)
        ->withQueryString();

    /*
    |--------------------------------------------------------------------------
    | Summary
    |--------------------------------------------------------------------------
    |
    | IMPORTANT:
    | Summary uses exactly the same filters as the bill list.
    |
    */

    $summaryQuery = clone $baseQuery;

    $summary = [

        'total_bills' => (clone $summaryQuery)
            ->count(),

        'total_sales' => (clone $summaryQuery)
            ->sum('grand_total'),

        'paid_amount' => (clone $summaryQuery)
            ->sum('paid_amount'),

        'pending_amount' => (clone $summaryQuery)
            ->sum('pending_amount'),

    ];

    /*
    |--------------------------------------------------------------------------
    | Branches
    |--------------------------------------------------------------------------
    |
    | Main admin gets all active branches.
    | Branch user gets only own branch.
    |
    */

    $branchesQuery = Branch::where(
        'salon_id',
        $salon->id
    )
        ->where(
            'status',
            true
        )
        ->orderBy('name');

    if (!empty($userBranchId)) {

        $branchesQuery->where(
            'id',
            $userBranchId
        );
    }

    $branches = $branchesQuery->get();

    /*
    |--------------------------------------------------------------------------
    | Return View
    |--------------------------------------------------------------------------
    */

    return view(
        'admin.billing.all',
        compact(
            'bills',
            'branches',
            'summary'
        )
    );
}
public function remainingPayment($id)
{
    $user = Auth::user();
    $salon = $user->salon;

    if (!$salon) {
        abort(403, 'Salon not found.');
    }

    $bill = Bill::with(['customer', 'branch', 'payments'])
        ->where('id', $id)
        ->where('salon_id', $salon->id)
        ->where('status', 'completed')
        ->firstOrFail();

    if (
        !empty($user->branch_id) &&
        (int) $bill->branch_id !== (int) $user->branch_id
    ) {
        abort(403, 'You are not authorized to access this bill.');
    }

    $paidAmount = round(
        (float) $bill->payments->sum('amount'),
        2
    );

    $grandTotal = round(
        (float) $bill->grand_total,
        2
    );

    $pendingAmount = round(
        max(0, $grandTotal - $paidAmount),
        2
    );

    return view(
        'admin.billing.remaining-payment',
        compact(
            'bill',
            'paidAmount',
            'pendingAmount'
        )
    );
}


public function collectRemainingPayment(Request $request, $id)
{
    $user = Auth::user();
    $salon = $user->salon;

    if (!$salon) {
        abort(403, 'Salon not found.');
    }

    $validated = $request->validate([
        'payment_method' => [
            'required',
            'in:cash,upi,card,bank_transfer,online'
        ],

        'amount' => [
            'required',
            'numeric',
            'min:0.01'
        ],

        'transaction_id' => [
            'nullable',
            'string',
            'max:255'
        ],

        'notes' => [
            'nullable',
            'string',
            'max:1000'
        ],
    ]);

    DB::beginTransaction();

    try {

        $bill = Bill::where('id', $id)
            ->where('salon_id', $salon->id)
            ->where('status', 'completed')
            ->lockForUpdate()
            ->firstOrFail();

        if (
            !empty($user->branch_id) &&
            (int) $bill->branch_id !== (int) $user->branch_id
        ) {
            DB::rollBack();

            abort(403, 'You are not authorized to access this bill.');
        }

        $paidAmount = round(
            (float) BillPayment::where('bill_id', $bill->id)->sum('amount'),
            2
        );

        $grandTotal = round(
            (float) $bill->grand_total,
            2
        );

        $pendingAmount = round(
            max(0, $grandTotal - $paidAmount),
            2
        );

        if ($pendingAmount <= 0) {

            DB::rollBack();

            return redirect()
                ->route('admin.billing.all')
                ->with('error', 'This bill is already fully paid.');
        }

        $paymentAmount = round(
            (float) $validated['amount'],
            2
        );

        if ($paymentAmount > $pendingAmount) {

            DB::rollBack();

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Payment amount cannot be greater than the remaining amount of ₹' .
                    number_format($pendingAmount, 2)
                );
        }

        BillPayment::create([
            'bill_id' => $bill->id,
            'payment_method' => $validated['payment_method'],
            'amount' => $paymentAmount,
            'transaction_id' => $validated['transaction_id'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ]);

        $newPaidAmount = round(
            $paidAmount + $paymentAmount,
            2
        );

        $newPendingAmount = round(
            max(0, $grandTotal - $newPaidAmount),
            2
        );

        if ($newPendingAmount <= 0) {

            $newPaidAmount = $grandTotal;
            $newPendingAmount = 0;
            $paymentStatus = 'paid';

        } else {

            $paymentStatus = 'partial';
        }

        $bill->update([
            'paid_amount' => $newPaidAmount,
            'pending_amount' => $newPendingAmount,
            'payment_method' => $validated['payment_method'],
            'payment_status' => $paymentStatus,
            'transaction_id' =>
                $validated['transaction_id']
                ?? $bill->transaction_id,
        ]);

        DB::commit();

        if ($paymentStatus === 'paid') {

            return redirect()
                ->route('admin.billing.invoice', $bill->id)
                ->with(
                    'success',
                    'Remaining payment collected successfully. Bill is now fully PAID.'
                );
        }

        return redirect()
            ->route('admin.billing.all')
            ->with(
                'success',
                'Payment collected successfully. Remaining amount is ₹' .
                number_format($newPendingAmount, 2)
            );

    } catch (\Throwable $e) {

        DB::rollBack();

        report($e);

        return back()
            ->withInput()
            ->with(
                'error',
                'Payment could not be processed. Please try again.'
            );
    }
}
/**
 * Show all held bills.
 */
public function heldBills()
{
    $user = Auth::user();

    $salon = $user->salon;

    if (!$salon) {
        abort(
            403,
            'Your account is not linked to a salon.'
        );
    }

    $query = Bill::with([
        'customer',
        'branch',
        'items.service',
        'items.serviceVariation',
        'items.staff',
    ])
        ->where(
            'salon_id',
            $salon->id
        )
        ->where(
            'status',
            'held'
        );

    /*
    |--------------------------------------------------------------------------
    | Branch User
    |--------------------------------------------------------------------------
    | Main Branch = all
    | Other Branch = own branch only
    |--------------------------------------------------------------------------
    */

    if (
        !empty($user->branch_id) &&
        (int) $user->branch_id !== 1
    ) {

        $query->where(
            'branch_id',
            $user->branch_id
        );
    }

    $heldBills = $query
        ->orderByDesc('id')
        ->get();

    return view(
        'admin.billing.held',
        compact(
            'heldBills'
        )
    );
}


    /**
     * Invoice.
     */
    public function invoice($id)
    {
        $user = Auth::user();

        $salon = $user->salon;

        if (!$salon) {

            abort(
                403,
                'Your account is not linked to a salon.'
            );
        }


        $bill =
            Bill::with([

                'salon',

                'branch',

                'customer',

                'appointment',

                'items.service',

                'items.serviceVariation',

                'items.staff',

                'payments'

            ])
                ->where(
                    'id',
                    $id
                )
                ->where(
                    'salon_id',
                    $salon->id
                )
                ->firstOrFail();


        return view(
            'admin.billing.invoice',
            compact(
                'bill'
            )
        );
    }


    /**
     * Download Invoice PDF
     */
    public function invoicePdf(
        Bill $bill
    )
    {
        $salon =
            Auth::user()->salon;

        if (!$salon) {

            abort(
                403,
                'Your account is not linked to a salon.'
            );
        }


        $bill =
            Bill::with([

                'salon',

                'branch',

                'customer',

                'appointment',

                'items.service',

                'items.serviceVariation',

                'items.staff',

                'payments',

            ])
                ->where(
                    'salon_id',
                    $salon->id
                )
                ->findOrFail(
                    $bill->id
                );


     
$pdf = Pdf::loadView(
    'admin.billing.invoice-pdf',
    [
        'bill'  => $bill,
        'salon' => $salon,
    ]
);

$pdf->setPaper(
    'A4',
    'portrait'
);

return $pdf->download(
    'Invoice-' .
    $bill->invoice_number .
    '.pdf'
);




        $pdf->setPaper(
            'A4',
            'portrait'
        );


        return $pdf->download(
            'Invoice-' .
            $bill->invoice_number .
            '.pdf'
        );
    }


    /**
     * Print Receipt.
     */
    public function receipt($id)
    {
        $user = Auth::user();

        $salon = $user->salon;

        if (!$salon) {

            abort(
                403,
                'Your account is not linked to a salon.'
            );
        }


        $bill =
            Bill::with([

                'salon',

                'branch',

                'customer',

                'items.service',

                'items.serviceVariation',

                'items.staff',

                'payments'

            ])
                ->where(
                    'id',
                    $id
                )
                ->where(
                    'salon_id',
                    $salon->id
                )
                ->firstOrFail();


        return view(
            'admin.billing.receipt',
            compact(
                'bill'
            )
        );
    }


    /**
     * Download Receipt PDF
     */
    public function receiptPdf(
        Bill $bill
    )
    {
        $salon =
            Auth::user()->salon;

        if (!$salon) {

            abort(
                403,
                'Your account is not linked to a salon.'
            );
        }


        $bill =
            Bill::with([

                'salon',

                'branch',

                'customer',

                'appointment',

                'items.service',

                'items.serviceVariation',

                'items.staff',

                'payments',

            ])
                ->where(
                    'salon_id',
                    $salon->id
                )
                ->findOrFail(
                    $bill->id
                );


        $pdf =
            Pdf::loadView(
                'admin.billing.receipt-pdf',
                [
                    'bill' =>
                        $bill,
                ]
            );


        /*
        |--------------------------------------------------------------------------
        | 80mm Thermal Receipt
        |--------------------------------------------------------------------------
        */

        $pdf->setPaper(
            [
                0,
                0,
                226.77,
                1000
            ],
            'portrait'
        );


        return $pdf->download(
            'Receipt-' .
            $bill->invoice_number .
            '.pdf'
        );
    }


    /**
     * Generate Invoice Number.
     */
    private function generateInvoiceNumber(
        $salon
    ): string
    {
        $prefix =
            $salon->invoice_prefix
            ?: 'INV';


        $date =
            now()->format(
                'Ymd'
            );


        $lastBill =
            Bill::where(
                'salon_id',
                $salon->id
            )
                ->latest('id')
                ->first();


        $number =
            $lastBill
                ? $lastBill->id + 1
                : 1;


        return
            $prefix .
            '-' .
            $date .
            '-' .
            str_pad(
                $number,
                5,
                '0',
                STR_PAD_LEFT
            );
    }


    /**
     * Apply Coupon
     */
    public function applyCoupon(
        Request $request
    ): JsonResponse
    {
        $user = Auth::user();


        $request->validate([

            'coupon_code' => [
                'required',
                'string',
                'max:50'
            ],

            'branch_id' => [
                'required',
                'integer'
            ],

            'customer_id' => [
                'nullable',
                'integer'
            ],

            'bill_amount' => [
                'required',
                'numeric',
                'min:0'
            ],

        ]);


        $couponCode =
            strtoupper(
                trim(
                    $request->coupon_code
                )
            );


        /*
        |--------------------------------------------------------------------------
        | Find Coupon
        |--------------------------------------------------------------------------
        */

        $coupon =
            Coupon::where(
                'salon_id',
                $user->salon_id
            )
                ->whereRaw(
                    'UPPER(code) = ?',
                    [$couponCode]
                )
                ->first();


        if (!$coupon) {

            return response()->json([

                'success' =>
                    false,

                'message' =>
                    'Invalid coupon code.',

            ], 422);
        }


        /*
        |--------------------------------------------------------------------------
        | Branch Validation
        |--------------------------------------------------------------------------
        */

        $branchId =
            (int)
                $request->branch_id;


        if (
            $coupon->branch_id !== null &&
            (int)
                $coupon->branch_id
            !==
            $branchId
        ) {

            return response()->json([

                'success' =>
                    false,

                'message' =>
                    'This coupon is not valid for the selected branch.',

            ], 422);
        }


        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */

        if (
            $coupon->status !==
            'active'
        ) {

            return response()->json([

                'success' =>
                    false,

                'message' =>
                    'This coupon is currently inactive.',

            ], 422);
        }


        /*
        |--------------------------------------------------------------------------
        | Date Only Validity
        |--------------------------------------------------------------------------
        */

        $today =
            now()->startOfDay();


        if (
            $coupon->valid_from !== null
        ) {

            $validFrom =
                \Carbon\Carbon::parse(
                    $coupon->valid_from
                )->startOfDay();


            if (
                $today->lt(
                    $validFrom
                )
            ) {

                return response()->json([

                    'success' =>
                        false,

                    'message' =>
                        'This coupon is not active yet.',

                ], 422);
            }
        }


        if (
            $coupon->valid_until !== null
        ) {

            $validUntil =
                \Carbon\Carbon::parse(
                    $coupon->valid_until
                )->endOfDay();


            if (
                $today->gt(
                    $validUntil
                )
            ) {

                return response()->json([

                    'success' =>
                        false,

                    'message' =>
                        'This coupon has expired.',

                ], 422);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Overall Usage Limit
        |--------------------------------------------------------------------------
        */

        if (
            $coupon->usage_limit !== null &&
            $coupon->used_count >=
            $coupon->usage_limit
        ) {

            return response()->json([

                'success' =>
                    false,

                'message' =>
                    'This coupon usage limit has been reached.',

            ], 422);
        }


        /*
        |--------------------------------------------------------------------------
        | Bill Amount
        |--------------------------------------------------------------------------
        */

        $billAmount =
            round(
                (float)
                    $request->bill_amount,
                2
            );


        if (
            $billAmount <= 0
        ) {

            return response()->json([

                'success' =>
                    false,

                'message' =>
                    'Please add services before applying a coupon.',

            ], 422);
        }


        /*
        |--------------------------------------------------------------------------
        | Minimum Bill
        |--------------------------------------------------------------------------
        */

        $minimumBill =
            (float) (
                $coupon
                    ->minimum_bill_amount
                ?? 0
            );


        if (
            $billAmount <
            $minimumBill
        ) {

            return response()->json([

                'success' =>
                    false,

                'message' =>
                    'Minimum bill amount of ₹' .
                    number_format(
                        $minimumBill,
                        2
                    ) .
                    ' is required for this coupon.',

            ], 422);
        }


        /*
        |--------------------------------------------------------------------------
        | Per Customer Limit
        |--------------------------------------------------------------------------
        */

        if (
            $coupon->per_customer_limit !==
            null &&
            $request->customer_id
        ) {

            $customerUsageCount =
                CouponUsage::where(
                    'coupon_id',
                    $coupon->id
                )
                    ->where(
                        'customer_id',
                        $request->customer_id
                    )
                    ->count();


            if (
                $customerUsageCount >=
                $coupon->per_customer_limit
            ) {

                return response()->json([

                    'success' =>
                        false,

                    'message' =>
                        'This customer has already used this coupon the maximum allowed number of times.',

                ], 422);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Calculate Discount
        |--------------------------------------------------------------------------
        */

        $discount = 0;


        if (
            $coupon->discount_type ===
            'percentage'
        ) {

            $discount =
                (
                    $billAmount *
                    (float)
                        $coupon
                            ->discount_value
                ) / 100;

        } else {

            $discount =
                (float)
                    $coupon
                        ->discount_value;
        }


        /*
        |--------------------------------------------------------------------------
        | Maximum Discount
        |--------------------------------------------------------------------------
        */

        if (
            $coupon->maximum_discount !==
            null
        ) {

            $discount =
                min(
                    $discount,
                    (float)
                        $coupon
                            ->maximum_discount
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Discount Cannot Exceed Bill
        |--------------------------------------------------------------------------
        */

        $discount =
            min(
                $discount,
                $billAmount
            );


        $discount =
            round(
                $discount,
                2
            );


        /*
        |--------------------------------------------------------------------------
        | Final Amount
        |--------------------------------------------------------------------------
        */

        $finalAmount =
            round(
                $billAmount -
                $discount,
                2
            );


        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

        return response()->json([

            'success' =>
                true,

            'message' =>
                'Coupon applied successfully.',

            'coupon' => [

                'id' =>
                    $coupon->id,

                'code' =>
                    $coupon->code,

                'discount_type' =>
                    $coupon->discount_type,

                'discount_value' =>
                    (float)
                        $coupon
                            ->discount_value,

                'maximum_discount' =>
                    $coupon->maximum_discount !== null

                        ? (float)
                            $coupon
                                ->maximum_discount

                        : null,

            ],

            'discount' =>
                $discount,

            'bill_amount' =>
                $billAmount,

            'final_amount' =>
                $finalAmount,

        ]);
    }
  
/**
 * Delete a billing record.
 */
public function destroy($id)
{
    $user = Auth::user();

    $bill = Bill::where('id', $id)
        ->where('salon_id', $user->salon_id)
        ->firstOrFail();

    $bill->delete();

    return redirect()
        ->route('admin.billing.index')
        ->with('success', 'Bill deleted successfully.');
}


}

