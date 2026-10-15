<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Appointment;
use App\Models\Bill;
use App\Models\Service;
use App\Models\Staff;
use App\Models\Package;
use App\Models\Product;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    /**
     * Reports Dashboard
     */
    public function index()
    {
        return view('admin.reports.index');
    }


    /*
    |--------------------------------------------------------------------------
    | CUSTOMER REPORT
    |--------------------------------------------------------------------------
    */

    public function customers(Request $request)
    {
        $user = auth()->user();

        $query = Customer::with(['branch', 'salon']);

        // Salon isolation
        if (!empty($user->salon_id)) {
            $query->where('salon_id', $user->salon_id);
        }

        // Branch isolation
        if (!empty($user->branch_id)) {
            $query->where('branch_id', $user->branch_id);
        }

        // Search
        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('phone', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        // Gender
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        // Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Date From
        if ($request->filled('date_from')) {
            $query->whereDate(
                'created_at',
                '>=',
                $request->date_from
            );
        }

        // Date To
        if ($request->filled('date_to')) {
            $query->whereDate(
                'created_at',
                '<=',
                $request->date_to
            );
        }

        $customers = $query
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view(
            'admin.reports.customers',
            compact('customers')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CUSTOMER REPORT PDF
    |--------------------------------------------------------------------------
    */

    public function customersPdf(Request $request)
    {
        $user = auth()->user();

        $query = Customer::with([
            'branch',
            'salon',
        ]);

        // Salon isolation
        if (!empty($user->salon_id)) {
            $query->where('salon_id', $user->salon_id);
        }

        // Branch isolation
        if (!empty($user->branch_id)) {
            $query->where('branch_id', $user->branch_id);
        }

        // Search
        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('phone', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        // Gender
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        // Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Date From
        if ($request->filled('date_from')) {
            $query->whereDate(
                'created_at',
                '>=',
                $request->date_from
            );
        }

        // Date To
        if ($request->filled('date_to')) {
            $query->whereDate(
                'created_at',
                '<=',
                $request->date_to
            );
        }

        $customers = $query
            ->latest()
            ->get();

        $pdf = Pdf::loadView(
            'admin.reports.pdf.customers',
            compact('customers')
        );

        $pdf->setPaper('a4', 'landscape');

        return $pdf->download(
            'customer-report-' . now()->format('Y-m-d') . '.pdf'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | APPOINTMENT REPORT
    |--------------------------------------------------------------------------
    */

    public function appointments()
    {
        $user = auth()->user();

        $query = Appointment::with([
            'customer',
            'service',
            'staff',
            'branch',
        ]);

        // Salon isolation
        if (!empty($user->salon_id)) {
            $query->where('salon_id', $user->salon_id);
        }

        // Branch isolation
        if (!empty($user->branch_id)) {
            $query->where('branch_id', $user->branch_id);
        }

        $appointments = $query
            ->orderByDesc('appointment_date')
            ->orderBy('start_time')
            ->get();

        return view(
            'admin.reports.appointments',
            compact('appointments')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | APPOINTMENT REPORT PDF
    |--------------------------------------------------------------------------
    */

    public function appointmentsPdf()
    {
        $user = auth()->user();

        $query = Appointment::with([
            'customer',
            'service',
            'staff',
            'branch',
        ]);

        // Salon isolation
        if (!empty($user->salon_id)) {
            $query->where('salon_id', $user->salon_id);
        }

        // Branch isolation
        if (!empty($user->branch_id)) {
            $query->where('branch_id', $user->branch_id);
        }

        $appointments = $query
            ->orderByDesc('appointment_date')
            ->orderBy('start_time')
            ->get();

        $pdf = Pdf::loadView(
            'admin.reports.pdf.appointments',
            compact('appointments')
        );

        $pdf->setPaper('a4', 'landscape');

        return $pdf->download(
            'appointment-report-' . now()->format('Y-m-d') . '.pdf'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SALES REPORT
    |--------------------------------------------------------------------------
    */

    public function sales(Request $request)
    {
        $user = auth()->user();

        $query = Bill::with([
            'customer',
            'branch',
        ])
        ->where('status', 'completed');

        // Salon isolation
        if (!empty($user->salon_id)) {
            $query->where('salon_id', $user->salon_id);
        }

        // Branch isolation
        if (!empty($user->branch_id)) {
            $query->where('branch_id', $user->branch_id);
        }

        // Search
        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where(
                    'invoice_number',
                    'like',
                    '%' . $search . '%'
                );

                $q->orWhereHas('customer', function ($customerQuery) use ($search) {

                    $customerQuery
                        ->where('name', 'like', '%' . $search . '%')
                        ->orWhere(
                            'phone',
                            'like',
                            '%' . $search . '%'
                        );

                });
            });
        }

        // Payment Method
        if ($request->filled('payment_method')) {
            $query->where(
                'payment_method',
                $request->payment_method
            );
        }

        // Payment Status
        if ($request->filled('payment_status')) {
            $query->where(
                'payment_status',
                $request->payment_status
            );
        }

        // Date From
        if ($request->filled('date_from')) {
            $query->whereDate(
                'bill_date',
                '>=',
                $request->date_from
            );
        }

        // Date To
        if ($request->filled('date_to')) {
            $query->whereDate(
                'bill_date',
                '<=',
                $request->date_to
            );
        }

        $sales = $query
            ->orderByDesc('bill_date')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view(
            'admin.reports.sales',
            compact('sales')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SALES REPORT PDF
    |--------------------------------------------------------------------------
    */

    public function salesPdf(Request $request)
    {
        $user = auth()->user();

        $query = Bill::with([
            'customer',
            'branch',
        ])
        ->where('status', 'completed');

        // Salon isolation
        if (!empty($user->salon_id)) {
            $query->where('salon_id', $user->salon_id);
        }

        // Branch isolation
        if (!empty($user->branch_id)) {
            $query->where('branch_id', $user->branch_id);
        }

        // Search
        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where(
                    'invoice_number',
                    'like',
                    '%' . $search . '%'
                );

                $q->orWhereHas('customer', function ($customerQuery) use ($search) {

                    $customerQuery
                        ->where('name', 'like', '%' . $search . '%')
                        ->orWhere(
                            'phone',
                            'like',
                            '%' . $search . '%'
                        );

                });
            });
        }

        // Payment Method
        if ($request->filled('payment_method')) {
            $query->where(
                'payment_method',
                $request->payment_method
            );
        }

        // Payment Status
        if ($request->filled('payment_status')) {
            $query->where(
                'payment_status',
                $request->payment_status
            );
        }

        // Date From
        if ($request->filled('date_from')) {
            $query->whereDate(
                'bill_date',
                '>=',
                $request->date_from
            );
        }

        // Date To
        if ($request->filled('date_to')) {
            $query->whereDate(
                'bill_date',
                '<=',
                $request->date_to
            );
        }

        $sales = $query
            ->orderByDesc('bill_date')
            ->orderByDesc('id')
            ->get();

        $pdf = Pdf::loadView(
            'admin.reports.pdf.sales',
            compact('sales')
        );

        $pdf->setPaper('a4', 'landscape');

        return $pdf->download(
            'sales-report-' . now()->format('Y-m-d') . '.pdf'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SERVICE REPORT
    |--------------------------------------------------------------------------
    */

    public function services(Request $request)
    {
        $user = auth()->user();

        $query = Service::query();

        // Salon isolation
        if (!empty($user->salon_id)) {
            $query->where(
                'salon_id',
                $user->salon_id
            );
        }

        // Search
        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where(
                    'name',
                    'like',
                    '%' . $search . '%'
                )
                ->orWhere(
                    'description',
                    'like',
                    '%' . $search . '%'
                );

            });
        }

        // Status
        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );

        }

        $services = $query
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view(
            'admin.reports.services',
            compact('services')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SERVICE REPORT PDF
    |--------------------------------------------------------------------------
    */

    public function servicesPdf(Request $request)
    {
        $user = auth()->user();

        $query = Service::query();

        // Salon isolation
        if (!empty($user->salon_id)) {
            $query->where(
                'salon_id',
                $user->salon_id
            );
        }

        // Search
        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where(
                    'name',
                    'like',
                    '%' . $search . '%'
                )
                ->orWhere(
                    'description',
                    'like',
                    '%' . $search . '%'
                );

            });
        }

        // Status
        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );

        }

        $services = $query
            ->orderBy('name')
            ->get();

        $pdf = Pdf::loadView(
            'admin.reports.pdf.services',
            compact('services')
        );

        $pdf->setPaper('a4', 'landscape');

        return $pdf->download(
            'service-report-' . now()->format('Y-m-d') . '.pdf'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | PACKAGE REPORT
    |--------------------------------------------------------------------------
    */

    public function packages(Request $request)
{
    $user = auth()->user();

    $query = Package::query();

    // Salon isolation
    if (!empty($user->salon_id)) {
        $query->where('salon_id', $user->salon_id);
    }

    // Search
    if ($request->filled('search')) {
        $search = trim($request->search);

        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', '%' . $search . '%')
                ->orWhere('code', 'like', '%' . $search . '%')
                ->orWhere('description', 'like', '%' . $search . '%');
        });
    }

    // Status filter
    if ($request->filled('status')) {
        if ($request->status === 'active') {
            $query->where('status', true);
        } elseif ($request->status === 'inactive') {
            $query->where('status', false);
        }
    }

    $packages = $query
        ->orderBy('name')
        ->paginate(15)
        ->withQueryString();

    return view('admin.reports.packages', compact('packages'));
}


public function packagesPdf(Request $request)
{
    $user = auth()->user();

    $query = Package::query();

    // Salon isolation
    if (!empty($user->salon_id)) {
        $query->where('salon_id', $user->salon_id);
    }

    // Search
    if ($request->filled('search')) {
        $search = trim($request->search);

        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', '%' . $search . '%')
                ->orWhere('code', 'like', '%' . $search . '%')
                ->orWhere('description', 'like', '%' . $search . '%');
        });
    }

    // Status filter
    if ($request->filled('status')) {
        if ($request->status === 'active') {
            $query->where('status', true);
        } elseif ($request->status === 'inactive') {
            $query->where('status', false);
        }
    }

    $packages = $query
        ->orderBy('name')
        ->get();

    $pdf = Pdf::loadView(
        'admin.reports.pdf.packages',
        compact('packages')
    );

    $pdf->setPaper('a4', 'landscape');

    return $pdf->download(
        'package-report-' . now()->format('Y-m-d') . '.pdf'
    );
}
/*
|--------------------------------------------------------------------------
| INVENTORY REPORT
|--------------------------------------------------------------------------
*/

public function inventory(Request $request)
{
    $user = auth()->user();

    $query = Product::with([
        'category',
        'supplier',
    ]);

    /*
    |--------------------------------------------------------------------------
    | Salon Isolation
    |--------------------------------------------------------------------------
    */

    if (!empty($user->salon_id)) {
        $query->where(
            'salon_id',
            $user->salon_id
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Search
    |--------------------------------------------------------------------------
    */

    if ($request->filled('search')) {

        $search = trim($request->search);

        $query->where(function ($q) use ($search) {

            $q->where(
                'name',
                'like',
                '%' . $search . '%'
            )
            ->orWhere(
                'sku',
                'like',
                '%' . $search . '%'
            )
            ->orWhere(
                'brand',
                'like',
                '%' . $search . '%'
            );

        });
    }

    /*
    |--------------------------------------------------------------------------
    | Status / Stock Filter
    |--------------------------------------------------------------------------
    */

    if ($request->filled('status')) {

        if ($request->status === 'active') {

            $query->where(
                'status',
                true
            );

        } elseif ($request->status === 'inactive') {

            $query->where(
                'status',
                false
            );

        } elseif ($request->status === 'low_stock') {

            $query->whereColumn(
                'stock',
                '<=',
                'minimum_stock'
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Inventory List
    |--------------------------------------------------------------------------
    */

    $products = $query
        ->orderBy('name')
        ->paginate(15)
        ->withQueryString();

    return view(
        'admin.reports.inventory',
        compact('products')
    );
}


/*
|--------------------------------------------------------------------------
| INVENTORY REPORT PDF
|--------------------------------------------------------------------------
*/

public function inventoryPdf(Request $request)
{
    $user = auth()->user();

    $query = Product::with([
        'category',
        'supplier',
    ]);

    /*
    |--------------------------------------------------------------------------
    | Salon Isolation
    |--------------------------------------------------------------------------
    */

    if (!empty($user->salon_id)) {

        $query->where(
            'salon_id',
            $user->salon_id
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Search
    |--------------------------------------------------------------------------
    */

    if ($request->filled('search')) {

        $search = trim($request->search);

        $query->where(function ($q) use ($search) {

            $q->where(
                'name',
                'like',
                '%' . $search . '%'
            )
            ->orWhere(
                'sku',
                'like',
                '%' . $search . '%'
            )
            ->orWhere(
                'brand',
                'like',
                '%' . $search . '%'
            );

        });
    }

    /*
    |--------------------------------------------------------------------------
    | Status / Stock Filter
    |--------------------------------------------------------------------------
    */

    if ($request->filled('status')) {

        if ($request->status === 'active') {

            $query->where(
                'status',
                true
            );

        } elseif ($request->status === 'inactive') {

            $query->where(
                'status',
                false
            );

        } elseif ($request->status === 'low_stock') {

            $query->whereColumn(
                'stock',
                '<=',
                'minimum_stock'
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Get All Filtered Products
    |--------------------------------------------------------------------------
    */

    $products = $query
        ->orderBy('name')
        ->get();

    /*
    |--------------------------------------------------------------------------
    | Generate PDF
    |--------------------------------------------------------------------------
    */

    $pdf = Pdf::loadView(
        'admin.reports.pdf.inventory',
        compact('products')
    );

    $pdf->setPaper(
        'a4',
        'landscape'
    );

    return $pdf->download(
        'inventory-report-' .
        now()->format('Y-m-d') .
        '.pdf'
    );
}


    /*
|--------------------------------------------------------------------------
| EXPENSE REPORT
|--------------------------------------------------------------------------
*/

public function expenses(Request $request)
{
    $user = auth()->user();

    $query = Expense::with([
        'category',
        'branch',
        'creator',
    ]);

    /*
    |--------------------------------------------------------------------------
    | Salon Isolation
    |--------------------------------------------------------------------------
    */

    if (!empty($user->salon_id)) {
        $query->where(
            'salon_id',
            $user->salon_id
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Branch Isolation
    |--------------------------------------------------------------------------
    */

    if (!empty($user->branch_id)) {
        $query->where(
            'branch_id',
            $user->branch_id
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Search
    |--------------------------------------------------------------------------
    */

    if ($request->filled('search')) {

        $search = trim($request->search);

        $query->where(function ($q) use ($search) {

            $q->where(
                'title',
                'like',
                '%' . $search . '%'
            )
            ->orWhere(
                'paid_to',
                'like',
                '%' . $search . '%'
            )
            ->orWhere(
                'reference_no',
                'like',
                '%' . $search . '%'
            );

        });
    }

    /*
    |--------------------------------------------------------------------------
    | Category Filter
    |--------------------------------------------------------------------------
    */

    if ($request->filled('category_id')) {

        $query->where(
            'expense_category_id',
            $request->category_id
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Payment Method Filter
    |--------------------------------------------------------------------------
    */

    if ($request->filled('payment_method')) {

        $query->where(
            'payment_method',
            $request->payment_method
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Date From
    |--------------------------------------------------------------------------
    */

    if ($request->filled('date_from')) {

        $query->whereDate(
            'expense_date',
            '>=',
            $request->date_from
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Date To
    |--------------------------------------------------------------------------
    */

    if ($request->filled('date_to')) {

        $query->whereDate(
            'expense_date',
            '<=',
            $request->date_to
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Expense List
    |--------------------------------------------------------------------------
    */

    $expenses = $query
        ->orderByDesc('expense_date')
        ->orderByDesc('id')
        ->paginate(15)
        ->withQueryString();

    /*
    |--------------------------------------------------------------------------
    | Categories
    |--------------------------------------------------------------------------
    */

    $categoriesQuery = ExpenseCategory::query();

    if (!empty($user->salon_id)) {

        $categoriesQuery->where(
            'salon_id',
            $user->salon_id
        );
    }

    $categories = $categoriesQuery
        ->where('status', true)
        ->orderBy('name')
        ->get();

    /*
    |--------------------------------------------------------------------------
    | Total Expense
    |--------------------------------------------------------------------------
    */

    $totalExpenses = (clone $query)->sum('amount');

    /*
    |--------------------------------------------------------------------------
    | Category Wise Summary
    |--------------------------------------------------------------------------
    */

    $categorySummary = Expense::query()
        ->selectRaw(
            'expense_category_id, SUM(amount) as total'
        )
        ->with('category')
        ->when(
            !empty($user->salon_id),
            function ($q) use ($user) {
                $q->where(
                    'salon_id',
                    $user->salon_id
                );
            }
        )
        ->when(
            !empty($user->branch_id),
            function ($q) use ($user) {
                $q->where(
                    'branch_id',
                    $user->branch_id
                );
            }
        )
        ->when(
            $request->filled('category_id'),
            function ($q) use ($request) {
                $q->where(
                    'expense_category_id',
                    $request->category_id
                );
            }
        )
        ->when(
            $request->filled('payment_method'),
            function ($q) use ($request) {
                $q->where(
                    'payment_method',
                    $request->payment_method
                );
            }
        )
        ->when(
            $request->filled('date_from'),
            function ($q) use ($request) {
                $q->whereDate(
                    'expense_date',
                    '>=',
                    $request->date_from
                );
            }
        )
        ->when(
            $request->filled('date_to'),
            function ($q) use ($request) {
                $q->whereDate(
                    'expense_date',
                    '<=',
                    $request->date_to
                );
            }
        )
        ->groupBy('expense_category_id')
        ->orderByDesc('total')
        ->get();

    return view(
        'admin.reports.expenses',
        compact(
            'expenses',
            'categories',
            'totalExpenses',
            'categorySummary'
        )
    );
}


/*
|--------------------------------------------------------------------------
| EXPENSE REPORT PDF
|--------------------------------------------------------------------------
*/

public function expensesPdf(Request $request)
{
    $user = auth()->user();

    $query = Expense::with([
        'category',
        'branch',
        'creator',
    ]);

    /*
    |--------------------------------------------------------------------------
    | Salon Isolation
    |--------------------------------------------------------------------------
    */

    if (!empty($user->salon_id)) {

        $query->where(
            'salon_id',
            $user->salon_id
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Branch Isolation
    |--------------------------------------------------------------------------
    */

    if (!empty($user->branch_id)) {

        $query->where(
            'branch_id',
            $user->branch_id
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Search
    |--------------------------------------------------------------------------
    */

    if ($request->filled('search')) {

        $search = trim($request->search);

        $query->where(function ($q) use ($search) {

            $q->where(
                'title',
                'like',
                '%' . $search . '%'
            )
            ->orWhere(
                'paid_to',
                'like',
                '%' . $search . '%'
            )
            ->orWhere(
                'reference_no',
                'like',
                '%' . $search . '%'
            );

        });
    }

    /*
    |--------------------------------------------------------------------------
    | Category
    |--------------------------------------------------------------------------
    */

    if ($request->filled('category_id')) {

        $query->where(
            'expense_category_id',
            $request->category_id
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Payment Method
    |--------------------------------------------------------------------------
    */

    if ($request->filled('payment_method')) {

        $query->where(
            'payment_method',
            $request->payment_method
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Date From
    |--------------------------------------------------------------------------
    */

    if ($request->filled('date_from')) {

        $query->whereDate(
            'expense_date',
            '>=',
            $request->date_from
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Date To
    |--------------------------------------------------------------------------
    */

    if ($request->filled('date_to')) {

        $query->whereDate(
            'expense_date',
            '<=',
            $request->date_to
        );
    }

    $expenses = $query
        ->orderByDesc('expense_date')
        ->orderByDesc('id')
        ->get();

    $totalExpenses = $expenses->sum('amount');

    $pdf = Pdf::loadView(
        'admin.reports.pdf.expenses',
        compact(
            'expenses',
            'totalExpenses'
        )
    );

    $pdf->setPaper(
        'a4',
        'landscape'
    );

    return $pdf->download(
        'expense-report-' .
        now()->format('Y-m-d') .
        '.pdf'
    );
}
    /*
|--------------------------------------------------------------------------
| STAFF REPORT
|--------------------------------------------------------------------------
*/

public function staff(Request $request)
{
    $user = auth()->user();

    $query = Staff::with([
        'branch',
    ]);

    /*
    |--------------------------------------------------------------------------
    | Salon Isolation
    |--------------------------------------------------------------------------
    */

    if (!empty($user->salon_id)) {
        $query->where(
            'salon_id',
            $user->salon_id
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Branch Isolation
    |--------------------------------------------------------------------------
    */

    if (!empty($user->branch_id)) {
        $query->where(
            'branch_id',
            $user->branch_id
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Search
    |--------------------------------------------------------------------------
    */

    if ($request->filled('search')) {

        $search = trim($request->search);

        $query->where(function ($q) use ($search) {

            $q->where(
                'name',
                'like',
                '%' . $search . '%'
            )
            ->orWhere(
                'phone',
                'like',
                '%' . $search . '%'
            )
            ->orWhere(
                'email',
                'like',
                '%' . $search . '%'
            )
            ->orWhere(
                'specialization',
                'like',
                '%' . $search . '%'
            );

        });
    }

    /*
    |--------------------------------------------------------------------------
    | Role Filter
    |--------------------------------------------------------------------------
    */

    if ($request->filled('role')) {

        $query->where(
            'role',
            $request->role
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Status Filter
    |--------------------------------------------------------------------------
    */

    if ($request->filled('status')) {

        $query->where(
            'status',
            $request->status
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Staff List
    |--------------------------------------------------------------------------
    */

    $staff = $query
        ->orderBy('name')
        ->paginate(15)
        ->withQueryString();

    /*
    |--------------------------------------------------------------------------
    | Roles For Filter
    |--------------------------------------------------------------------------
    */

    $rolesQuery = Staff::query();

    if (!empty($user->salon_id)) {
        $rolesQuery->where(
            'salon_id',
            $user->salon_id
        );
    }

    if (!empty($user->branch_id)) {
        $rolesQuery->where(
            'branch_id',
            $user->branch_id
        );
    }

    $roles = $rolesQuery
        ->whereNotNull('role')
        ->where('role', '!=', '')
        ->distinct()
        ->orderBy('role')
        ->pluck('role');

    return view(
        'admin.reports.staff',
        compact(
            'staff',
            'roles'
        )
    );
}


/*
|--------------------------------------------------------------------------
| STAFF REPORT PDF
|--------------------------------------------------------------------------
*/

public function staffPdf(Request $request)
{
    $user = auth()->user();

    $query = Staff::with([
        'branch',
    ]);

    /*
    |--------------------------------------------------------------------------
    | Salon Isolation
    |--------------------------------------------------------------------------
    */

    if (!empty($user->salon_id)) {
        $query->where(
            'salon_id',
            $user->salon_id
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Branch Isolation
    |--------------------------------------------------------------------------
    */

    if (!empty($user->branch_id)) {
        $query->where(
            'branch_id',
            $user->branch_id
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Search
    |--------------------------------------------------------------------------
    */

    if ($request->filled('search')) {

        $search = trim($request->search);

        $query->where(function ($q) use ($search) {

            $q->where(
                'name',
                'like',
                '%' . $search . '%'
            )
            ->orWhere(
                'phone',
                'like',
                '%' . $search . '%'
            )
            ->orWhere(
                'email',
                'like',
                '%' . $search . '%'
            )
            ->orWhere(
                'specialization',
                'like',
                '%' . $search . '%'
            );

        });
    }

    /*
    |--------------------------------------------------------------------------
    | Role Filter
    |--------------------------------------------------------------------------
    */

    if ($request->filled('role')) {

        $query->where(
            'role',
            $request->role
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Status Filter
    |--------------------------------------------------------------------------
    */

    if ($request->filled('status')) {

        $query->where(
            'status',
            $request->status
        );
    }

    $staff = $query
        ->orderBy('name')
        ->get();

    $pdf = Pdf::loadView(
        'admin.reports.pdf.staff',
        compact('staff')
    );

    $pdf->setPaper(
        'a4',
        'landscape'
    );

    return $pdf->download(
        'staff-report-' . now()->format('Y-m-d') . '.pdf'
    );
}
}