<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Customer;
use App\Models\Staff;
use App\Models\Expense;
use App\Models\CustomerPackage;
use App\Models\CustomerMembership;
use App\Models\Bill;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class AdminController extends Controller
{
    /**
     * Dashboard
     */
    public function dashboard()
    {
        $user = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | Salon / Branch
        |--------------------------------------------------------------------------
        */

        $salonId = $user->salon_id ?? session('salon_id');
        $branchId = $user->branch_id ?? session('branch_id');

        /*
        |--------------------------------------------------------------------------
        | Appointment Query
        |--------------------------------------------------------------------------
        */

        $appointmentQuery = Appointment::query()
            ->where('salon_id', $salonId);

        if (!empty($branchId)) {
            $appointmentQuery->where('branch_id', $branchId);
        }

        /*
        |--------------------------------------------------------------------------
        | Today's Appointments
        |--------------------------------------------------------------------------
        */

        $todayAppointments = (clone $appointmentQuery)
            ->whereDate('appointment_date', today())
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Today's Revenue
        |--------------------------------------------------------------------------
        */

        $todayRevenue = (clone $appointmentQuery)
            ->with('service')
            ->whereDate('appointment_date', today())
            ->where('status', 'completed')
            ->get()
            ->sum(function ($appointment) {
                return $appointment->service->price ?? 0;
            });

        /*
        |--------------------------------------------------------------------------
        | Customers
        |--------------------------------------------------------------------------
        */

        $customerQuery = Customer::query()
            ->where('salon_id', $salonId);

        if (!empty($branchId)) {
            $customerQuery->where('branch_id', $branchId);
        }

        $totalCustomers = $customerQuery->count();

        /*
        |--------------------------------------------------------------------------
        | Available Staff
        |--------------------------------------------------------------------------
        */

        $staffQuery = Staff::query()
            ->where('salon_id', $salonId)
            ->where('status', true);

        if (!empty($branchId)) {
            $staffQuery->where('branch_id', $branchId);
        }

        $availableStaff = $staffQuery->count();

        /*
        |--------------------------------------------------------------------------
        | Today's Appointment List
        |--------------------------------------------------------------------------
        */

        $todayAppointmentList = (clone $appointmentQuery)
            ->with([
                'customer',
                'service',
                'staff'
            ])
            ->whereDate('appointment_date', today())
            ->orderBy('start_time')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Recent Appointments
        |--------------------------------------------------------------------------
        */

        $recentAppointments = (clone $appointmentQuery)
            ->with([
                'customer',
                'service',
                'staff'
            ])
            ->latest('appointment_date')
            ->latest('start_time')
            ->take(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Revenue Chart - Last 7 Days
        |--------------------------------------------------------------------------
        */

        $revenueLabels = [];
        $revenueData = [];

        for ($i = 6; $i >= 0; $i--) {

            $date = Carbon::today()->subDays($i);

            $revenue = (clone $appointmentQuery)
                ->with('service')
                ->whereDate('appointment_date', $date)
                ->where('status', 'completed')
                ->get()
                ->sum(function ($appointment) {
                    return $appointment->service->price ?? 0;
                });

            $revenueLabels[] = $date->format('D');
            $revenueData[] = (float) $revenue;
        }

        /*
        |--------------------------------------------------------------------------
        | Monthly Revenue
        |--------------------------------------------------------------------------
        */

        $monthStart = Carbon::now()->startOfMonth();
        $monthEnd = Carbon::now()->endOfMonth();

        $monthlyRevenue = (clone $appointmentQuery)
            ->with('service')
            ->whereBetween('appointment_date', [
                $monthStart->toDateString(),
                $monthEnd->toDateString()
            ])
            ->where('status', 'completed')
            ->get()
            ->sum(function ($appointment) {
                return $appointment->service->price ?? 0;
            });

        /*
        |--------------------------------------------------------------------------
        | Monthly Expenses
        |--------------------------------------------------------------------------
        */

        $expenseQuery = Expense::query()
            ->where('salon_id', $salonId);

        if (!empty($branchId)) {
            $expenseQuery->where('branch_id', $branchId);
        }

        $monthlyExpenses = (clone $expenseQuery)
            ->whereBetween('expense_date', [
                $monthStart->toDateString(),
                $monthEnd->toDateString()
            ])
            ->sum('amount');

        /*
        |--------------------------------------------------------------------------
        | Monthly Net Profit
        |--------------------------------------------------------------------------
        */

        $monthlyNetProfit = $monthlyRevenue - $monthlyExpenses;

        /*
        |--------------------------------------------------------------------------
        | DASHBOARD NOTIFICATIONS
        |--------------------------------------------------------------------------
        |
        | Package / Membership expiry:
        | Today -> Next 7 Days
        |
        */

        $notificationStartDate = Carbon::today();
        $notificationEndDate = Carbon::today()->addDays(7);

        /*
        |--------------------------------------------------------------------------
        | 1. PACKAGE EXPIRY NOTIFICATIONS
        |--------------------------------------------------------------------------
        */

        $packageExpiryQuery = CustomerPackage::query()
            ->with([
                'customer',
                'package'
            ])
            ->where('salon_id', $salonId)
            ->where('status', 'active')
            ->whereDate(
                'expiry_date',
                '>=',
                $notificationStartDate->toDateString()
            )
            ->whereDate(
                'expiry_date',
                '<=',
                $notificationEndDate->toDateString()
            );

        /*
        | CustomerPackage does not have branch_id.
        | Therefore branch is filtered through customer.
        */

        if (!empty($branchId)) {

            $packageExpiryQuery->whereHas(
                'customer',
                function ($query) use ($branchId) {

                    $query->where(
                        'branch_id',
                        $branchId
                    );
                }
            );
        }

        $packageExpiryNotifications = $packageExpiryQuery
            ->orderBy('expiry_date')
            ->get();

        $packageExpiryCount =
            $packageExpiryNotifications->count();

        /*
        |--------------------------------------------------------------------------
        | 2. MEMBERSHIP EXPIRY NOTIFICATIONS
        |--------------------------------------------------------------------------
        */

        $membershipExpiryQuery = CustomerMembership::query()
            ->with([
                'customer',
                'membershipPlan'
            ])
            ->where('salon_id', $salonId)
            ->where('status', 'active')
            ->whereDate(
                'expiry_date',
                '>=',
                $notificationStartDate->toDateString()
            )
            ->whereDate(
                'expiry_date',
                '<=',
                $notificationEndDate->toDateString()
            );

        if (!empty($branchId)) {

            $membershipExpiryQuery->where(
                'branch_id',
                $branchId
            );
        }

        $membershipExpiryNotifications = $membershipExpiryQuery
            ->orderBy('expiry_date')
            ->get();

        $membershipExpiryCount =
            $membershipExpiryNotifications->count();

        /*
        |--------------------------------------------------------------------------
        | 3. PENDING PAYMENT NOTIFICATIONS
        |--------------------------------------------------------------------------
        */

        $pendingPaymentQuery = Bill::query()
            ->with([
                'customer',
                'branch'
            ])
            ->where('salon_id', $salonId)
            ->where('payment_status', 'pending')
            ->where('pending_amount', '>', 0);

        if (!empty($branchId)) {

            $pendingPaymentQuery->where(
                'branch_id',
                $branchId
            );
        }

        $pendingPaymentNotifications = $pendingPaymentQuery
            ->latest('bill_date')
            ->get();

        $pendingPaymentCount =
            $pendingPaymentNotifications->count();

        /*
        |--------------------------------------------------------------------------
        | 4. TODAY'S APPOINTMENT NOTIFICATIONS
        |--------------------------------------------------------------------------
        |
        | Today's:
        | pending
        | confirmed
        | arrived
        | in_service
        |
        */

        $appointmentNotificationQuery = (clone $appointmentQuery)
            ->with([
                'customer',
                'service',
                'staff'
            ])
            ->whereDate(
                'appointment_date',
                today()
            )
            ->whereIn('status', [
                'pending',
                'confirmed',
                'arrived',
                'in_service'
            ]);

        $appointmentNotifications = $appointmentNotificationQuery
            ->orderBy('start_time')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Appointment Notification Counts
        |--------------------------------------------------------------------------
        |
        | Both variable names are kept because dashboard.blade.php
        | uses $todayAppointmentNotificationCount while the
        | total notification calculation uses
        | $appointmentNotificationCount.
        |
        */

        $todayAppointmentNotificationCount =
            $appointmentNotifications->count();

        $appointmentNotificationCount =
            $todayAppointmentNotificationCount;

        /*
        |--------------------------------------------------------------------------
        | TOTAL NOTIFICATION COUNT
        |--------------------------------------------------------------------------
        */

        $notificationCount =
            $packageExpiryCount
            + $membershipExpiryCount
            + $pendingPaymentCount
            + $appointmentNotificationCount;

        /*
        |--------------------------------------------------------------------------
        | Return Dashboard
        |--------------------------------------------------------------------------
        */

        return view(
            'admin.dashboard',
            compact(

                // Dashboard Cards
                'todayAppointments',
                'todayRevenue',
                'totalCustomers',
                'availableStaff',

                // Appointment Data
                'todayAppointmentList',
                'recentAppointments',

                // Revenue Chart
                'revenueLabels',
                'revenueData',

                // Monthly Summary
                'monthlyRevenue',
                'monthlyExpenses',
                'monthlyNetProfit',

                // Package Notifications
                'packageExpiryNotifications',
                'packageExpiryCount',

                // Membership Notifications
                'membershipExpiryNotifications',
                'membershipExpiryCount',

                // Payment Notifications
                'pendingPaymentNotifications',
                'pendingPaymentCount',

                // Appointment Notifications
                'appointmentNotifications',
                'appointmentNotificationCount',
                'todayAppointmentNotificationCount',

                // Total
                'notificationCount'
            )
        );
    }


    /**
     * AJAX Notification Count
     *
     * Dashboard JavaScript every 10 seconds
     * this method call करेल.
     */
    public function notificationCount(): JsonResponse
    {
        $user = auth()->user();

        $salonId = $user->salon_id ?? session('salon_id');
        $branchId = $user->branch_id ?? session('branch_id');

        /*
        |--------------------------------------------------------------------------
        | Date Range
        |--------------------------------------------------------------------------
        */

        $startDate = Carbon::today();
        $endDate = Carbon::today()->addDays(7);

        /*
        |--------------------------------------------------------------------------
        | Package Expiry
        |--------------------------------------------------------------------------
        */

        $packageQuery = CustomerPackage::query()
            ->where('salon_id', $salonId)
            ->where('status', 'active')
            ->whereDate(
                'expiry_date',
                '>=',
                $startDate->toDateString()
            )
            ->whereDate(
                'expiry_date',
                '<=',
                $endDate->toDateString()
            );

        if (!empty($branchId)) {

            $packageQuery->whereHas(
                'customer',
                function ($query) use ($branchId) {

                    $query->where(
                        'branch_id',
                        $branchId
                    );
                }
            );
        }

        $packageExpiryCount =
            $packageQuery->count();

        /*
        |--------------------------------------------------------------------------
        | Membership Expiry
        |--------------------------------------------------------------------------
        */

        $membershipQuery = CustomerMembership::query()
            ->where('salon_id', $salonId)
            ->where('status', 'active')
            ->whereDate(
                'expiry_date',
                '>=',
                $startDate->toDateString()
            )
            ->whereDate(
                'expiry_date',
                '<=',
                $endDate->toDateString()
            );

        if (!empty($branchId)) {

            $membershipQuery->where(
                'branch_id',
                $branchId
            );
        }

        $membershipExpiryCount =
            $membershipQuery->count();

        /*
        |--------------------------------------------------------------------------
        | Pending Payments
        |--------------------------------------------------------------------------
        */

        $paymentQuery = Bill::query()
            ->where('salon_id', $salonId)
            ->where('payment_status', 'pending')
            ->where('pending_amount', '>', 0);

        if (!empty($branchId)) {

            $paymentQuery->where(
                'branch_id',
                $branchId
            );
        }

        $pendingPaymentCount =
            $paymentQuery->count();

        /*
        |--------------------------------------------------------------------------
        | Today's Appointments
        |--------------------------------------------------------------------------
        */

        $appointmentQuery = Appointment::query()
            ->where('salon_id', $salonId)
            ->whereDate(
                'appointment_date',
                today()
            )
            ->whereIn('status', [
                'pending',
                'confirmed',
                'arrived',
                'in_service'
            ]);

        if (!empty($branchId)) {

            $appointmentQuery->where(
                'branch_id',
                $branchId
            );
        }

        $appointmentNotificationCount =
            $appointmentQuery->count();

        /*
        |--------------------------------------------------------------------------
        | Today's Appointment Notification Count
        |--------------------------------------------------------------------------
        */

        $todayAppointmentNotificationCount =
            $appointmentNotificationCount;

        /*
        |--------------------------------------------------------------------------
        | Total
        |--------------------------------------------------------------------------
        */

        $notificationCount =
            $packageExpiryCount
            + $membershipExpiryCount
            + $pendingPaymentCount
            + $appointmentNotificationCount;

        /*
        |--------------------------------------------------------------------------
        | JSON Response
        |--------------------------------------------------------------------------
        */

        return response()->json([
            'success' => true,

            'notificationCount' =>
                $notificationCount,

            'packageExpiryCount' =>
                $packageExpiryCount,

            'membershipExpiryCount' =>
                $membershipExpiryCount,

            'pendingPaymentCount' =>
                $pendingPaymentCount,

            'appointmentNotificationCount' =>
                $appointmentNotificationCount,

            'todayAppointmentNotificationCount' =>
                $todayAppointmentNotificationCount,
        ]);
    }
    
}