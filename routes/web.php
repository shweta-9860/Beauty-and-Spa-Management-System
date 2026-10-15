<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminAppointmentController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminServiceController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TwoFactorController;
use App\Http\Controllers\ServiceVariationController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\StaffScheduleController;
use App\Http\Controllers\StaffAttendanceController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\AdminSalonController;
use App\Http\Controllers\BranchLoginController;
use App\Http\Controllers\ReportController;

use App\Http\Controllers\Admin\BillingController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\CustomerPackageController;
use App\Http\Controllers\Admin\PackageUsageController;
use App\Http\Controllers\Admin\MembershipPlanController;
use App\Http\Controllers\Admin\MembershipBenefitController;
use App\Http\Controllers\Admin\CustomerMembershipController;
use App\Http\Controllers\Admin\NotificationController;

use App\Http\Controllers\Admin\ProductCategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\StockController;
use App\Http\Controllers\Admin\PurchaseController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\WastageController;
use App\Http\Controllers\Admin\DiscountController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\ExpenseController;
use App\Http\Controllers\Admin\ExpenseCategoryController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\BackupController;
use App\Http\Controllers\PrintController;


/*
|--------------------------------------------------------------------------
| Layout Test
|--------------------------------------------------------------------------
*/

Route::get('/admin/layout-test', function () {
    return view('admin.layout');
});


/*
|--------------------------------------------------------------------------
| Home
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});


/*
|--------------------------------------------------------------------------
| Admin Login
|--------------------------------------------------------------------------
*/

Route::get(
    '/admin/login',
    [AdminAuthController::class, 'showLogin']
)->name('admin.login');

Route::post(
    '/admin/login',
    [AdminAuthController::class, 'login']
)->name('admin.login.submit');

Route::post(
    '/admin/logout',
    [AdminAuthController::class, 'logout']
)->name('admin.logout');


/*
|--------------------------------------------------------------------------
| Authenticated Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | 2FA Verification
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/admin/2fa/verify',
        [TwoFactorController::class, 'showVerify']
    )->name('admin.2fa.verify');

    Route::post(
        '/admin/2fa/verify',
        [TwoFactorController::class, 'verify']
    )->name('admin.2fa.verify.submit');


    /*
    |--------------------------------------------------------------------------
    | 2FA Settings
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/admin/settings/2fa',
        [TwoFactorController::class, 'index']
    )->name('admin.2fa');

    Route::get(
        '/admin/settings/2fa/setup',
        [TwoFactorController::class, 'generate']
    )->name('admin.2fa.setup');

    Route::post(
        '/admin/settings/2fa/enable',
        [TwoFactorController::class, 'enable']
    )->name('admin.2fa.enable');

    Route::post(
        '/admin/settings/2fa/disable',
        [TwoFactorController::class, 'disable']
    )->name('admin.2fa.disable');


    /*
    |--------------------------------------------------------------------------
    | Salon Profile
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/admin/salon/profile',
        [AdminSalonController::class, 'index']
    )->name('admin.salon.profile');

    Route::get(
        '/admin/salon/profile/create',
        [AdminSalonController::class, 'create']
    )->name('admin.salon.create');

    Route::post(
        '/admin/salon/profile',
        [AdminSalonController::class, 'store']
    )->name('admin.salon.store');


    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/admin/dashboard',
        [AdminController::class, 'dashboard']
    )
        ->middleware('twofactor')
        ->name('admin.dashboard');


    /*
    |--------------------------------------------------------------------------
    | Notification Count
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/admin/notification-count',
        [AdminController::class, 'notificationCount']
    )
        ->middleware('twofactor')
        ->name('admin.notification.count');


    /*
    |--------------------------------------------------------------------------
    | Services
    |--------------------------------------------------------------------------
    */

    Route::resource(
        '/admin/services',
        AdminServiceController::class
    )
        ->names('admin.services')
        ->middleware('twofactor');


    /*
    |--------------------------------------------------------------------------
    | Categories
    |--------------------------------------------------------------------------
    */

    Route::resource(
        '/admin/categories',
        CategoryController::class
    )
        ->names('admin.categories')
        ->middleware('twofactor');


    /*
    |--------------------------------------------------------------------------
    | Category Status Toggle
    |--------------------------------------------------------------------------
    */

    Route::patch(
        '/admin/categories/{category}/toggle-status',
        [CategoryController::class, 'toggleStatus']
    )
        ->middleware('twofactor')
        ->name('admin.categories.toggle-status');


    /*
    |--------------------------------------------------------------------------
    | Service Variations
    |--------------------------------------------------------------------------
    */

    Route::resource(
        '/admin/service-variations',
        ServiceVariationController::class
    )
        ->names('admin.service-variations')
        ->middleware('twofactor');


    /*
    |--------------------------------------------------------------------------
    | Service Variation Status Toggle
    |--------------------------------------------------------------------------
    */

    Route::patch(
        '/admin/service-variations/{serviceVariation}/toggle-status',
        [ServiceVariationController::class, 'toggleStatus']
    )
        ->middleware('twofactor')
        ->name('admin.service-variations.toggle-status');


    /*
    |--------------------------------------------------------------------------
    | Staff Attendance
    |--------------------------------------------------------------------------
    */

    Route::prefix('admin/staff/attendance')
        ->name('admin.staff.attendance.')
        ->middleware('twofactor')
        ->group(function () {

            Route::get(
                '/',
                [StaffAttendanceController::class, 'index']
            )->name('index');

            Route::get(
                '/create',
                [StaffAttendanceController::class, 'create']
            )->name('create');

            Route::post(
                '/',
                [StaffAttendanceController::class, 'store']
            )->name('store');

            Route::post(
                '/{staff}/check-in',
                [StaffAttendanceController::class, 'checkIn']
            )->name('check-in');

            Route::post(
                '/{staff}/check-out',
                [StaffAttendanceController::class, 'checkOut']
            )->name('check-out');

            Route::get(
                '/{attendance}/edit',
                [StaffAttendanceController::class, 'edit']
            )->name('edit');

            Route::put(
                '/{attendance}',
                [StaffAttendanceController::class, 'update']
            )->name('update');

            Route::delete(
                '/{attendance}',
                [StaffAttendanceController::class, 'destroy']
            )->name('destroy');
        });


    /*
    |--------------------------------------------------------------------------
    | Staff Schedule
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/admin/staff/schedule',
        [StaffScheduleController::class, 'list']
    )
        ->middleware('twofactor')
        ->name('admin.staff.schedule.list');

    Route::post(
        '/admin/staff/schedule',
        [StaffScheduleController::class, 'storeAll']
    )
        ->middleware('twofactor')
        ->name('admin.staff.schedule.storeAll');

    Route::get(
        '/admin/staff/{staff}/schedule',
        [StaffScheduleController::class, 'index']
    )
        ->middleware('twofactor')
        ->name('admin.staff.schedule.index');

    Route::post(
        '/admin/staff/{staff}/schedule',
        [StaffScheduleController::class, 'store']
    )
        ->middleware('twofactor')
        ->name('admin.staff.schedule.store');


    /*
    |--------------------------------------------------------------------------
    | Staff
    |--------------------------------------------------------------------------
    */

    Route::resource(
        '/admin/staff',
        StaffController::class
    )
        ->names('admin.staff')
        ->middleware('twofactor');


    /*
    |--------------------------------------------------------------------------
    | Staff Status Toggle
    |--------------------------------------------------------------------------
    */

    Route::patch(
        '/admin/staff/{staff}/toggle-status',
        [StaffController::class, 'toggleStatus']
    )
        ->middleware('twofactor')
        ->name('admin.staff.toggle-status');


    /*
    |--------------------------------------------------------------------------
    | Appointments - Calendar
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/admin/appointments/calendar',
        [AdminAppointmentController::class, 'calendar']
    )
        ->middleware('twofactor')
        ->name('admin.appointments.calendar');

    Route::get(
        '/admin/appointments/calendar-events',
        [AdminAppointmentController::class, 'calendarEvents']
    )
        ->middleware('twofactor')
        ->name('admin.appointments.calendar-events');


    /*
    |--------------------------------------------------------------------------
    | Appointments - Check Staff Leave
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/admin/appointments/check-staff-leave',
        [AdminAppointmentController::class, 'checkStaffLeaveStatus']
    )
        ->middleware('twofactor')
        ->name('admin.appointments.check-staff-leave');


    /*
    |--------------------------------------------------------------------------
    | Appointments - CRUD
    |--------------------------------------------------------------------------
    */

    Route::resource(
        '/admin/appointments',
        AdminAppointmentController::class
    )
        ->except(['show'])
        ->names('admin.appointments')
        ->middleware('twofactor');
});

/*
|--------------------------------------------------------------------------
| Branch Management
|--------------------------------------------------------------------------
*/

Route::prefix('admin/branches')
    ->name('admin.branches.')
    ->middleware(['auth', 'twofactor'])
    ->group(function () {

        Route::get(
            '/',
            [BranchController::class, 'index']
        )->name('index');

        Route::get(
            '/create',
            [BranchController::class, 'create']
        )->name('create');

        Route::post(
            '/',
            [BranchController::class, 'store']
        )->name('store');

        Route::get(
            '/{branch}/edit',
            [BranchController::class, 'edit']
        )->name('edit');

        Route::put(
            '/{branch}',
            [BranchController::class, 'update']
        )->name('update');

        Route::delete(
            '/{branch}',
            [BranchController::class, 'destroy']
        )->name('destroy');

        Route::get(
            '/{branch}/login',
            [BranchController::class, 'loginForm']
        )->name('login');
    });


/*
|--------------------------------------------------------------------------
| Branch Login Submit
|--------------------------------------------------------------------------
*/

Route::post(
    '/admin/branches/{branch}/login',
    [BranchLoginController::class, 'login']
)->name('admin.branches.login.submit');


/*
|--------------------------------------------------------------------------
| Branch Logout
|--------------------------------------------------------------------------
*/

Route::post(
    '/admin/branch-logout',
    [BranchLoginController::class, 'logout']
)
    ->middleware('auth')
    ->name('admin.branch.logout');


/*
|--------------------------------------------------------------------------
| Customers
|--------------------------------------------------------------------------
*/

Route::resource(
    '/admin/customers',
    CustomerController::class
)
    ->names('admin.customers')
    ->middleware(['auth', 'twofactor']);


/*
|--------------------------------------------------------------------------
| POS Billing
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'twofactor'])
    ->group(function () {

        Route::get(
            '/billing',
            [BillingController::class, 'index']
        )->name('billing.index');

        Route::get(
            '/billing/create',
            [BillingController::class, 'create']
        )->name('billing.create');

        Route::post(
            '/billing/store',
            [BillingController::class, 'store']
        )->name('billing.store');

        Route::post(
            '/billing/hold',
            [BillingController::class, 'hold']
        )->name('billing.hold');

        Route::get(
            '/billing/held',
            [BillingController::class, 'heldBills']
        )->name('billing.held');

        Route::get(
            '/billing/{id}/resume',
            [BillingController::class, 'resume']
        )->name('billing.resume');

        Route::get(
            '/billing/all',
            [BillingController::class, 'allBills']
        )->name('billing.all');


        /*
        |--------------------------------------------------------------------------
        | Remaining Payment
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/billing/{bill}/remaining-payment',
            [BillingController::class, 'remainingPayment']
        )->name('billing.remaining-payment');

        Route::post(
            '/billing/{bill}/remaining-payment',
            [BillingController::class, 'collectRemainingPayment']
        )->name('billing.remaining-payment.collect');


        /*
        |--------------------------------------------------------------------------
        | Invoice
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/billing/{bill}/invoice',
            [BillingController::class, 'invoice']
        )->name('billing.invoice');

        Route::get(
            '/billing/{bill}/invoice/pdf',
            [BillingController::class, 'invoicePdf']
        )->name('billing.invoice.pdf');


        /*
        |--------------------------------------------------------------------------
        | Receipt
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/billing/{bill}/receipt',
            [BillingController::class, 'receipt']
        )->name('billing.receipt');

        Route::get(
            '/billing/{bill}/receipt/pdf',
            [BillingController::class, 'receiptPdf']
        )->name('billing.receipt.pdf');


        /*
        |--------------------------------------------------------------------------
        | Apply Coupon
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/billing/apply-coupon',
            [BillingController::class, 'applyCoupon']
        )->name('billing.apply-coupon');


        /*
        |--------------------------------------------------------------------------
        | Delete Bill
        |--------------------------------------------------------------------------
        */

        Route::delete(
            '/billing/{bill}',
            [BillingController::class, 'destroy']
        )->name('billing.destroy');
    });


/*
|--------------------------------------------------------------------------
| Packages
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'twofactor'])
    ->group(function () {

        Route::resource(
            'packages',
            PackageController::class
        );
    });


/*
|--------------------------------------------------------------------------
| Customer Packages
|--------------------------------------------------------------------------
*/

Route::prefix('admin/customer-packages')
    ->name('admin.customer-packages.')
    ->middleware(['auth', 'twofactor'])
    ->group(function () {

        Route::get(
            '/',
            [CustomerPackageController::class, 'index']
        )->name('index');

        Route::get(
            '/create',
            [CustomerPackageController::class, 'create']
        )->name('create');

        Route::post(
            '/',
            [CustomerPackageController::class, 'store']
        )->name('store');

        Route::get(
            '/{customerPackage}/use',
            [CustomerPackageController::class, 'usageForm']
        )->name('usage.form');

        Route::post(
            '/use',
            [CustomerPackageController::class, 'useService']
        )->name('usage.store');
    });


/*
|--------------------------------------------------------------------------
| Customer Package Usage History
|--------------------------------------------------------------------------
*/

Route::prefix('admin/customer-package-usage')
    ->name('admin.customer-package-usage.')
    ->middleware(['auth', 'twofactor'])
    ->group(function () {

        Route::get(
            '/',
            [PackageUsageController::class, 'index']
        )->name('index');
    });


/*
|--------------------------------------------------------------------------
| Membership
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'twofactor'])
    ->group(function () {

        Route::resource(
            'membership/plans',
            MembershipPlanController::class
        )
            ->parameters([
                'plans' => 'membershipPlan'
            ])
            ->names('membership.plans');


        Route::resource(
            'membership/plans/{membershipPlan}/benefits',
            MembershipBenefitController::class
        )
            ->names('membership.benefits');


        Route::resource(
            'membership/customers',
            CustomerMembershipController::class
        )
            ->parameters([
                'customers' => 'customerMembership'
            ])
            ->names('membership.customers');


        Route::get(
            'membership/assigned-members',
            [CustomerMembershipController::class, 'assignedMembers']
        )->name('membership.assigned-members');
    });


/*
|--------------------------------------------------------------------------
| Inventory
|--------------------------------------------------------------------------
*/

Route::prefix('admin/inventory')
    ->name('admin.inventory.')
    ->middleware(['auth', 'twofactor'])
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Product Categories
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'categories',
            ProductCategoryController::class
        );


        /*
        |--------------------------------------------------------------------------
        | Suppliers
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'suppliers',
            SupplierController::class
        );


        /*
        |--------------------------------------------------------------------------
        | Products
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'products',
            ProductController::class
        );


        /*
        |--------------------------------------------------------------------------
        | Stock
        |--------------------------------------------------------------------------
        */

        Route::get(
            'stock',
            [StockController::class, 'index']
        )->name('stock.index');


        /*
        |--------------------------------------------------------------------------
        | Purchases
        |--------------------------------------------------------------------------
        */

        Route::get(
            'purchases',
            [PurchaseController::class, 'index']
        )->name('purchases.index');

        Route::get(
            'purchases/create',
            [PurchaseController::class, 'create']
        )->name('purchases.create');

        Route::post(
            'purchases',
            [PurchaseController::class, 'store']
        )->name('purchases.store');

        Route::post(
            'purchases/create',
            [PurchaseController::class, 'store']
        )->name('purchases.create.store');

        Route::get(
            'purchases/{purchase}',
            [PurchaseController::class, 'show']
        )->name('purchases.show');


        /*
        |--------------------------------------------------------------------------
        | Wastage
        |--------------------------------------------------------------------------
        */

        Route::get(
            'wastage',
            [WastageController::class, 'index']
        )->name('wastage.index');

        Route::get(
            'wastage/create',
            [WastageController::class, 'create']
        )->name('wastage.create');

        Route::post(
            'wastage',
            [WastageController::class, 'store']
        )->name('wastage.store');

        Route::get(
            'wastage/{wastage}',
            [WastageController::class, 'show']
        )->name('wastage.show');

        Route::post(
            'wastage/{wastage}/approve',
            [WastageController::class, 'approve']
        )->name('wastage.approve');

        Route::post(
            'wastage/{wastage}/reject',
            [WastageController::class, 'reject']
        )->name('wastage.reject');
    });


/*
|--------------------------------------------------------------------------
| Reports
|--------------------------------------------------------------------------
*/

Route::prefix('admin/reports')
    ->name('admin.reports.')
    ->middleware(['auth', 'twofactor'])
    ->group(function () {

        Route::get(
            '/',
            [ReportController::class, 'index']
        )->name('index');


        Route::get(
            '/customers/pdf',
            [ReportController::class, 'customersPdf']
        )->name('customers.pdf');

        Route::get(
            '/customers',
            [ReportController::class, 'customers']
        )->name('customers');


        Route::get(
            '/appointments/pdf',
            [ReportController::class, 'appointmentsPdf']
        )->name('appointments.pdf');

        Route::get(
            '/appointments',
            [ReportController::class, 'appointments']
        )->name('appointments');


        Route::get(
            '/sales/pdf',
            [ReportController::class, 'salesPdf']
        )->name('sales.pdf');

        Route::get(
            '/sales',
            [ReportController::class, 'sales']
        )->name('sales');


        Route::get(
            '/services/pdf',
            [ReportController::class, 'servicesPdf']
        )->name('services.pdf');

        Route::get(
            '/services',
            [ReportController::class, 'services']
        )->name('services');


        Route::get(
            '/staff/pdf',
            [ReportController::class, 'staffPdf']
        )->name('staff.pdf');

        Route::get(
            '/staff',
            [ReportController::class, 'staff']
        )->name('staff');


        Route::get(
            '/packages/pdf',
            [ReportController::class, 'packagesPdf']
        )->name('packages.pdf');

        Route::get(
            '/packages',
            [ReportController::class, 'packages']
        )->name('packages');


        Route::get(
            '/inventory/pdf',
            [ReportController::class, 'inventoryPdf']
        )->name('inventory.pdf');

        Route::get(
            '/inventory',
            [ReportController::class, 'inventory']
        )->name('inventory');


        Route::get(
            '/expenses/pdf',
            [ReportController::class, 'expensesPdf']
        )->name('expenses.pdf');

        Route::get(
            '/expenses',
            [ReportController::class, 'expenses']
        )->name('expenses');
    });


/*
|--------------------------------------------------------------------------
| Offers
|--------------------------------------------------------------------------
*/

Route::prefix('admin/offers')
    ->name('admin.offers.')
    ->middleware(['auth'])
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Discounts
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/discounts',
            [DiscountController::class, 'index']
        )->name('discounts.index');

        Route::get(
            '/discounts/create',
            [DiscountController::class, 'create']
        )->name('discounts.create');

        Route::post(
            '/discounts',
            [DiscountController::class, 'store']
        )->name('discounts.store');

        Route::get(
            '/discounts/{discount}/edit',
            [DiscountController::class, 'edit']
        )->name('discounts.edit');

        Route::put(
            '/discounts/{discount}',
            [DiscountController::class, 'update']
        )->name('discounts.update');

        Route::delete(
            '/discounts/{discount}',
            [DiscountController::class, 'destroy']
        )->name('discounts.destroy');

        Route::patch(
            '/discounts/{discount}/toggle-status',
            [DiscountController::class, 'toggleStatus']
        )->name('discounts.toggle-status');


        /*
        |--------------------------------------------------------------------------
        | Coupons
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/coupons',
            [CouponController::class, 'index']
        )->name('coupons.index');

        Route::get(
            '/coupons/create',
            [CouponController::class, 'create']
        )->name('coupons.create');

        Route::post(
            '/coupons',
            [CouponController::class, 'store']
        )->name('coupons.store');

        Route::get(
            '/coupons/{coupon}/edit',
            [CouponController::class, 'edit']
        )->name('coupons.edit');

        Route::put(
            '/coupons/{coupon}',
            [CouponController::class, 'update']
        )->name('coupons.update');

        Route::delete(
            '/coupons/{coupon}',
            [CouponController::class, 'destroy']
        )->name('coupons.destroy');


        /*
        |--------------------------------------------------------------------------
        | Coupon Status Toggle
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/coupons/{coupon}/toggle-status',
            [CouponController::class, 'toggleStatus']
        )->name('coupons.toggle-status');
    });


/*
|--------------------------------------------------------------------------
| Expenses
|--------------------------------------------------------------------------
*/

Route::prefix('admin/expenses')
    ->name('admin.expenses.')
    ->middleware(['auth', 'twofactor'])
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Expense Categories
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'categories',
            ExpenseCategoryController::class
        );


        /*
        |--------------------------------------------------------------------------
        | Expenses
        |--------------------------------------------------------------------------
        */

        Route::resource(
            '/',
            ExpenseController::class
        )->parameters([
            '' => 'expense'
        ]);
    });


/*
|--------------------------------------------------------------------------
| Settings
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'twofactor'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | General Settings
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/settings',
            [SettingsController::class, 'index']
        )->name('settings.index');


        /*
        |--------------------------------------------------------------------------
        | Update Salon Profile
        |--------------------------------------------------------------------------
        */

        Route::put(
            '/settings/profile',
            [SettingsController::class, 'updateProfile']
        )->name('settings.profile.update');


        /*
        |--------------------------------------------------------------------------
        | Update Password
        |--------------------------------------------------------------------------
        */

        Route::put(
            '/settings/password',
            [SettingsController::class, 'updatePassword']
        )->name('settings.password.update');


        /*
        |--------------------------------------------------------------------------
        | Update Security
        |--------------------------------------------------------------------------
        */

        Route::put(
            '/settings/security',
            [SettingsController::class, 'updateSecurity']
        )->name('settings.security.update');


        /*
        |--------------------------------------------------------------------------
        | Printer Settings
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/settings/printer',
            [SettingsController::class, 'printer']
        )->name('settings.printer');


        /*
        |--------------------------------------------------------------------------
        | Update Printer Settings
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/settings/printer',
            [SettingsController::class, 'updatePrinter']
        )->name('settings.printer.update');
    });


/*
|--------------------------------------------------------------------------
| Backup & Restore
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'twofactor'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Backup & Restore Page
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/settings/backup',
            [BackupController::class, 'index']
        )->name('settings.backup');


        /*
        |--------------------------------------------------------------------------
        | Create Backup
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/settings/backup/create',
            [BackupController::class, 'create']
        )->name('settings.backup.create');


        /*
        |--------------------------------------------------------------------------
        | Download Backup
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/settings/backup/download/{file}',
            [BackupController::class, 'download']
        )->name('settings.backup.download');


        /*
        |--------------------------------------------------------------------------
        | Restore Backup
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/settings/backup/restore',
            [BackupController::class, 'restore']
        )->name('settings.backup.restore');


        /*
        |--------------------------------------------------------------------------
        | Delete Backup
        |--------------------------------------------------------------------------
        */

        Route::delete(
            '/settings/backup/{file}',
            [BackupController::class, 'destroy']
        )->name('settings.backup.destroy');
    });


/*
|--------------------------------------------------------------------------
| Print
|--------------------------------------------------------------------------
*/

Route::get(
    '/print/{id}',
    [PrintController::class, 'index']
)->name('print.index');


/*
|--------------------------------------------------------------------------
| Customer Package Purchase
|--------------------------------------------------------------------------
*/

Route::get(
    'customer-packages/purchase',
    [CustomerPackageController::class, 'purchase']
)->name('admin.customer-packages.purchase');

Route::post(
    'customer-packages/purchase',
    [CustomerPackageController::class, 'storePurchase']
)->name('admin.customer-packages.store-purchase');

Route::get('/notifications', [NotificationController::class, 'index'])
    ->name('admin.notifications.index');

Route::get('/notification/count', [NotificationController::class, 'count'])
    ->name('admin.notifications.count');

Route::post('/notification/read', [NotificationController::class, 'markAsRead'])
    ->name('admin.notifications.read');