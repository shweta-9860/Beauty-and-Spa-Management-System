<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Bill;
use App\Models\CustomerMembership;
use App\Models\CustomerPackage;
use App\Models\NotificationRead;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class NotificationController extends Controller
{
    /**
     * Get all current notifications.
     */
    public function index(): JsonResponse
    {
        $notifications = $this->getNotifications();

        $html = View::make(
            'admin.notifications.dropdown',
            $notifications
        )->render();

        return response()->json([
            'success' => true,
            'html' => $html,
            'notificationCount' => $notifications['notificationCount'],
            'packageExpiryCount' => $notifications['packageExpiryCount'],
            'membershipExpiryCount' => $notifications['membershipExpiryCount'],
            'pendingPaymentCount' => $notifications['pendingPaymentCount'],
            'appointmentNotificationCount' =>
                $notifications['appointmentNotificationCount'],
        ]);
    }

    /**
     * Return only unread notification count.
     */
    public function count(): JsonResponse
    {
        $notifications = $this->getNotifications();

        return response()->json([
            'success' => true,
            'notificationCount' =>
                $notifications['notificationCount'],

            'packageExpiryCount' =>
                $notifications['packageExpiryCount'],

            'membershipExpiryCount' =>
                $notifications['membershipExpiryCount'],

            'pendingPaymentCount' =>
                $notifications['pendingPaymentCount'],

            'appointmentNotificationCount' =>
                $notifications['appointmentNotificationCount'],
        ]);
    }

    /**
     * Mark one notification as read.
     */
    public function markAsRead(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'notification_type' => [
                'required',
                'string',
                'in:package,membership,payment,appointment',
            ],

            'notification_id' => [
                'required',
                'integer',
                'min:1',
            ],
        ]);

        $user = auth()->user();

        $salonId = $user->salon_id ?? session('salon_id');
        $branchId = $user->branch_id ?? session('branch_id');

        /*
        |--------------------------------------------------------------------------
        | Make sure the notification actually belongs to this salon/branch
        |--------------------------------------------------------------------------
        */

        $exists = $this->notificationExists(
            $validated['notification_type'],
            $validated['notification_id'],
            $salonId,
            $branchId
        );

        if (!$exists) {
            return response()->json([
                'success' => false,
                'message' => 'Notification not found.',
            ], 404);
        }

        /*
        |--------------------------------------------------------------------------
        | Save read status
        |--------------------------------------------------------------------------
        */

        NotificationRead::firstOrCreate(
            [
                'user_id' => $user->id,
                'notification_type' =>
                    $validated['notification_type'],
                'notification_id' =>
                    $validated['notification_id'],
            ],
            [
                'read_at' => now(),
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Return updated unread count
        |--------------------------------------------------------------------------
        */

        $notifications = $this->getNotifications();

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read.',

            'notificationCount' =>
                $notifications['notificationCount'],
        ]);
    }

    /**
     * Build notification data.
     */
    private function getNotifications(): array
    {
        $user = auth()->user();

        $salonId = $user->salon_id ?? session('salon_id');
        $branchId = $user->branch_id ?? session('branch_id');

        $startDate = Carbon::today();
        $endDate = Carbon::today()->addDays(7);

        /*
        |--------------------------------------------------------------------------
        | PACKAGE EXPIRY
        |--------------------------------------------------------------------------
        */

        $packageQuery = CustomerPackage::query()
            ->with([
                'customer',
                'package',
            ])
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

        $packageExpiryNotifications =
            $packageQuery
                ->orderBy('expiry_date')
                ->get();

        /*
        |--------------------------------------------------------------------------
        | MEMBERSHIP EXPIRY
        |--------------------------------------------------------------------------
        */

        $membershipQuery = CustomerMembership::query()
            ->with([
                'customer',
                'membershipPlan',
            ])
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

        $membershipExpiryNotifications =
            $membershipQuery
                ->orderBy('expiry_date')
                ->get();

        /*
        |--------------------------------------------------------------------------
        | PENDING PAYMENTS
        |--------------------------------------------------------------------------
        */

        $paymentQuery = Bill::query()
            ->with([
                'customer',
                'branch',
            ])
            ->where('salon_id', $salonId)
            ->where('payment_status', 'pending')
            ->where('pending_amount', '>', 0);

        if (!empty($branchId)) {
            $paymentQuery->where(
                'branch_id',
                $branchId
            );
        }

        $pendingPaymentNotifications =
            $paymentQuery
                ->latest('bill_date')
                ->get();

        /*
        |--------------------------------------------------------------------------
        | TODAY'S APPOINTMENTS
        |--------------------------------------------------------------------------
        */

        $appointmentQuery = Appointment::query()
            ->with([
                'customer',
                'service',
                'staff',
            ])
            ->where('salon_id', $salonId)
            ->whereDate(
                'appointment_date',
                today()
            )
            ->whereIn('status', [
                'pending',
                'confirmed',
                'arrived',
                'in_service',
            ]);

        if (!empty($branchId)) {
            $appointmentQuery->where(
                'branch_id',
                $branchId
            );
        }

        $appointmentNotifications =
            $appointmentQuery
                ->orderBy('start_time')
                ->get();

        /*
        |--------------------------------------------------------------------------
        | REMOVE ALREADY READ NOTIFICATIONS
        |--------------------------------------------------------------------------
        */

        $userReadNotifications =
            NotificationRead::query()
                ->where('user_id', auth()->id())
                ->get()
                ->groupBy('notification_type');

        /*
        |--------------------------------------------------------------------------
        | Package unread
        |--------------------------------------------------------------------------
        */

        $readPackages = $userReadNotifications
            ->get('package', collect())
            ->pluck('notification_id')
            ->map(fn ($id) => (int) $id)
            ->toArray();

        $packageExpiryNotifications =
            $packageExpiryNotifications
                ->reject(
                    fn ($item) =>
                        in_array(
                            (int) $item->id,
                            $readPackages,
                            true
                        )
                )
                ->values();

        /*
        |--------------------------------------------------------------------------
        | Membership unread
        |--------------------------------------------------------------------------
        */

        $readMemberships = $userReadNotifications
            ->get('membership', collect())
            ->pluck('notification_id')
            ->map(fn ($id) => (int) $id)
            ->toArray();

        $membershipExpiryNotifications =
            $membershipExpiryNotifications
                ->reject(
                    fn ($item) =>
                        in_array(
                            (int) $item->id,
                            $readMemberships,
                            true
                        )
                )
                ->values();

        /*
        |--------------------------------------------------------------------------
        | Payment unread
        |--------------------------------------------------------------------------
        */

        $readPayments = $userReadNotifications
            ->get('payment', collect())
            ->pluck('notification_id')
            ->map(fn ($id) => (int) $id)
            ->toArray();

        $pendingPaymentNotifications =
            $pendingPaymentNotifications
                ->reject(
                    fn ($item) =>
                        in_array(
                            (int) $item->id,
                            $readPayments,
                            true
                        )
                )
                ->values();

        /*
        |--------------------------------------------------------------------------
        | Appointment unread
        |--------------------------------------------------------------------------
        */

        $readAppointments = $userReadNotifications
            ->get('appointment', collect())
            ->pluck('notification_id')
            ->map(fn ($id) => (int) $id)
            ->toArray();

        $appointmentNotifications =
            $appointmentNotifications
                ->reject(
                    fn ($item) =>
                        in_array(
                            (int) $item->id,
                            $readAppointments,
                            true
                        )
                )
                ->values();

        /*
        |--------------------------------------------------------------------------
        | Counts
        |--------------------------------------------------------------------------
        */

        $packageExpiryCount =
            $packageExpiryNotifications->count();

        $membershipExpiryCount =
            $membershipExpiryNotifications->count();

        $pendingPaymentCount =
            $pendingPaymentNotifications->count();

        $appointmentNotificationCount =
            $appointmentNotifications->count();

        $notificationCount =
            $packageExpiryCount
            + $membershipExpiryCount
            + $pendingPaymentCount
            + $appointmentNotificationCount;

        return [
            'packageExpiryNotifications' =>
                $packageExpiryNotifications,

            'packageExpiryCount' =>
                $packageExpiryCount,

            'membershipExpiryNotifications' =>
                $membershipExpiryNotifications,

            'membershipExpiryCount' =>
                $membershipExpiryCount,

            'pendingPaymentNotifications' =>
                $pendingPaymentNotifications,

            'pendingPaymentCount' =>
                $pendingPaymentCount,

            'appointmentNotifications' =>
                $appointmentNotifications,

            'appointmentNotificationCount' =>
                $appointmentNotificationCount,

            'todayAppointmentNotificationCount' =>
                $appointmentNotificationCount,

            'notificationCount' =>
                $notificationCount,
        ];
    }

    /**
     * Verify notification belongs to current salon/branch.
     */
    private function notificationExists(
        string $type,
        int $id,
        $salonId,
        $branchId
    ): bool {
        switch ($type) {

            case 'package':

                $query = CustomerPackage::query()
                    ->where('id', $id)
                    ->where('salon_id', $salonId)
                    ->where('status', 'active');

                if (!empty($branchId)) {
                    $query->whereHas(
                        'customer',
                        function ($q) use ($branchId) {
                            $q->where(
                                'branch_id',
                                $branchId
                            );
                        }
                    );
                }

                return $query->exists();

            case 'membership':

                $query = CustomerMembership::query()
                    ->where('id', $id)
                    ->where('salon_id', $salonId)
                    ->where('status', 'active');

                if (!empty($branchId)) {
                    $query->where(
                        'branch_id',
                        $branchId
                    );
                }

                return $query->exists();

            case 'payment':

                $query = Bill::query()
                    ->where('id', $id)
                    ->where('salon_id', $salonId)
                    ->where('payment_status', 'pending')
                    ->where('pending_amount', '>', 0);

                if (!empty($branchId)) {
                    $query->where(
                        'branch_id',
                        $branchId
                    );
                }

                return $query->exists();

            case 'appointment':

                $query = Appointment::query()
                    ->where('id', $id)
                    ->where('salon_id', $salonId)
                    ->whereDate(
                        'appointment_date',
                        today()
                    )
                    ->whereIn('status', [
                        'pending',
                        'confirmed',
                        'arrived',
                        'in_service',
                    ]);

                if (!empty($branchId)) {
                    $query->where(
                        'branch_id',
                        $branchId
                    );
                }

                return $query->exists();
        }

        return false;
    }
}