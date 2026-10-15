{{-- resources/views/admin/notifications/dropdown.blade.php --}}

<div class="notification-dropdown-content">

    {{-- Header --}}
    <div class="dropdown-header d-flex align-items-center justify-content-between px-3 py-3">
        <div>
            <div class="fw-bold fs-6">
                Notifications
            </div>

            <small class="text-muted">
                Your latest updates
            </small>
        </div>

        <span
            class="badge rounded-pill text-bg-danger"
            id="notificationHeaderCount"
        >
            {{ $notificationCount }}
        </span>
    </div>

    <div class="dropdown-divider m-0"></div>


    {{-- No Notifications --}}
    @if($notificationCount <= 0)

        <div class="text-center py-5 px-3 notification-empty-state">

            <div class="mb-3">
                <span
                    class="d-inline-flex align-items-center justify-content-center rounded-circle bg-light"
                    style="width:64px;height:64px;"
                >
                    <i class="bi bi-bell-slash fs-3 text-muted"></i>
                </span>
            </div>

            <h6 class="mb-1 fw-semibold">
                No New Notifications
            </h6>

            <p class="text-muted small mb-0">
                You're all caught up.
            </p>

        </div>

    @else

        <div
            class="notification-list"
            style="max-height: 430px; overflow-y: auto;"
        >

            {{-- ========================================================= --}}
            {{-- PACKAGE EXPIRY --}}
            {{-- ========================================================= --}}

            @if($packageExpiryCount > 0)

                <div class="notification-section">

                    <div class="px-3 py-2 bg-light border-bottom">
                        <div class="d-flex align-items-center justify-content-between">

                            <span class="small fw-semibold text-dark">
                                <i class="bi bi-box-seam me-1 text-warning"></i>
                                Package Expiry
                            </span>

                            <span class="badge rounded-pill text-bg-warning">
                                {{ $packageExpiryCount }}
                            </span>

                        </div>
                    </div>


                    @foreach($packageExpiryNotifications as $notification)

                        @php
                            $customerName =
                                $notification->customer->name
                                ?? 'Customer';

                            $packageName =
                                $notification->package->name
                                ?? 'Package';

                            $expiryDate =
                                $notification->expiry_date
                                    ? \Carbon\Carbon::parse(
                                        $notification->expiry_date
                                    )->format('d M Y')
                                    : '-';
                        @endphp

                        <a
                            href="{{ route('admin.customer-packages.index') }}"
                            class="dropdown-item notification-link py-3 px-3"
                            data-notification-type="package"
                            data-notification-id="{{ $notification->id }}"
                        >

                            <div class="d-flex align-items-start">

                                <div
                                    class="notification-icon bg-warning-subtle text-warning-emphasis rounded-circle me-3"
                                    style="width:40px;height:40px;min-width:40px;"
                                >
                                    <i class="bi bi-box-seam"></i>
                                </div>

                                <div class="flex-grow-1">

                                    <div class="fw-semibold text-dark">
                                        {{ $customerName }}
                                    </div>

                                    <div class="small text-muted">
                                        {{ $packageName }}
                                    </div>

                                    <div class="small mt-1">
                                        Expires on
                                        <strong>
                                            {{ $expiryDate }}
                                        </strong>
                                    </div>

                                </div>

                            </div>

                        </a>

                    @endforeach

                </div>

            @endif


            {{-- ========================================================= --}}
            {{-- MEMBERSHIP EXPIRY --}}
            {{-- ========================================================= --}}

            @if($membershipExpiryCount > 0)

                <div class="notification-section">

                    <div class="px-3 py-2 bg-light border-bottom">

                        <div class="d-flex align-items-center justify-content-between">

                            <span class="small fw-semibold text-dark">
                                <i class="bi bi-person-badge me-1 text-primary"></i>
                                Membership Expiry
                            </span>

                            <span class="badge rounded-pill text-bg-primary">
                                {{ $membershipExpiryCount }}
                            </span>

                        </div>

                    </div>


                    @foreach($membershipExpiryNotifications as $notification)

                        @php
                            $customerName =
                                $notification->customer->name
                                ?? 'Customer';

                            $membershipName =
                                $notification->membershipPlan->name
                                ?? 'Membership';

                            $expiryDate =
                                $notification->expiry_date
                                    ? \Carbon\Carbon::parse(
                                        $notification->expiry_date
                                    )->format('d M Y')
                                    : '-';
                        @endphp

                        <a
                            href="{{ route('admin.membership.customers.index') }}"
                            class="dropdown-item notification-link py-3 px-3"
                            data-notification-type="membership"
                            data-notification-id="{{ $notification->id }}"
                        >

                            <div class="d-flex align-items-start">

                                <div
                                    class="notification-icon bg-primary-subtle text-primary rounded-circle me-3"
                                    style="width:40px;height:40px;min-width:40px;"
                                >
                                    <i class="bi bi-person-badge"></i>
                                </div>

                                <div class="flex-grow-1">

                                    <div class="fw-semibold text-dark">
                                        {{ $customerName }}
                                    </div>

                                    <div class="small text-muted">
                                        {{ $membershipName }}
                                    </div>

                                    <div class="small mt-1">
                                        Expires on
                                        <strong>
                                            {{ $expiryDate }}
                                        </strong>
                                    </div>

                                </div>

                            </div>

                        </a>

                    @endforeach

                </div>

            @endif


            {{-- ========================================================= --}}
            {{-- PENDING PAYMENTS --}}
            {{-- ========================================================= --}}

            @if($pendingPaymentCount > 0)

                <div class="notification-section">

                    <div class="px-3 py-2 bg-light border-bottom">

                        <div class="d-flex align-items-center justify-content-between">

                            <span class="small fw-semibold text-dark">
                                <i class="bi bi-credit-card me-1 text-danger"></i>
                                Pending Payments
                            </span>

                            <span class="badge rounded-pill text-bg-danger">
                                {{ $pendingPaymentCount }}
                            </span>

                        </div>

                    </div>


                    @foreach($pendingPaymentNotifications as $notification)

                        @php
                            $customerName =
                                $notification->customer->name
                                ?? 'Customer';

                            $pendingAmount =
                                (float) (
                                    $notification->pending_amount
                                    ?? 0
                                );

                            $invoiceNumber =
                                $notification->invoice_number
                                ?? ('Bill #' . $notification->id);
                        @endphp

                        <a
                            href="{{ route('admin.billing.all') }}"
                            class="dropdown-item notification-link py-3 px-3"
                            data-notification-type="payment"
                            data-notification-id="{{ $notification->id }}"
                        >

                            <div class="d-flex align-items-start">

                                <div
                                    class="notification-icon bg-danger-subtle text-danger rounded-circle me-3"
                                    style="width:40px;height:40px;min-width:40px;"
                                >
                                    <i class="bi bi-credit-card"></i>
                                </div>

                                <div class="flex-grow-1">

                                    <div class="fw-semibold text-dark">
                                        {{ $customerName }}
                                    </div>

                                    <div class="small text-muted">
                                        {{ $invoiceNumber }}
                                    </div>

                                    <div class="small mt-1">
                                        Pending:
                                        <strong class="text-danger">
                                            ₹{{ number_format($pendingAmount, 2) }}
                                        </strong>
                                    </div>

                                </div>

                            </div>

                        </a>

                    @endforeach

                </div>

            @endif


            {{-- ========================================================= --}}
            {{-- APPOINTMENTS --}}
            {{-- ========================================================= --}}

            @if($appointmentNotificationCount > 0)

                <div class="notification-section">

                    <div class="px-3 py-2 bg-light border-bottom">

                        <div class="d-flex align-items-center justify-content-between">

                            <span class="small fw-semibold text-dark">
                                <i class="bi bi-calendar-check me-1 text-success"></i>
                                Today's Appointments
                            </span>

                            <span class="badge rounded-pill text-bg-success">
                                {{ $appointmentNotificationCount }}
                            </span>

                        </div>

                    </div>


                    @foreach($appointmentNotifications as $notification)

                        @php
                            $customerName =
                                $notification->customer->name
                                ?? 'Customer';

                            $serviceName =
                                $notification->service->name
                                ?? 'Appointment';

                            $staffName =
                                $notification->staff->name
                                ?? 'Staff';

                            $appointmentTime =
                                $notification->start_time
                                    ? \Carbon\Carbon::parse(
                                        $notification->start_time
                                    )->format('h:i A')
                                    : '-';

                            $status =
                                ucfirst(
                                    str_replace(
                                        '_',
                                        ' ',
                                        $notification->status
                                    )
                                );
                        @endphp

                        <a
                            href="{{ route('admin.appointments.index', [
                                'appointment_date' => now()->format('Y-m-d')
                            ]) }}"
                            class="dropdown-item notification-link py-3 px-3"
                            data-notification-type="appointment"
                            data-notification-id="{{ $notification->id }}"
                        >

                            <div class="d-flex align-items-start">

                                <div
                                    class="notification-icon bg-success-subtle text-success rounded-circle me-3"
                                    style="width:40px;height:40px;min-width:40px;"
                                >
                                    <i class="bi bi-calendar-check"></i>
                                </div>

                                <div class="flex-grow-1">

                                    <div class="fw-semibold text-dark">
                                        {{ $customerName }}
                                    </div>

                                    <div class="small text-muted">
                                        {{ $serviceName }}
                                    </div>

                                    <div class="small mt-1">
                                        {{ $appointmentTime }}
                                        ·
                                        {{ $staffName }}
                                    </div>

                                    <span class="badge bg-light text-dark border mt-1">
                                        {{ $status }}
                                    </span>

                                </div>

                            </div>

                        </a>

                    @endforeach

                </div>

            @endif

        </div>

    @endif

</div>


{{-- ============================================================= --}}
{{-- NOTIFICATION JAVASCRIPT --}}
{{-- ============================================================= --}}

<script>
(function () {

    /*
    |--------------------------------------------------------------------------
    | Mark notification as read
    |--------------------------------------------------------------------------
    */

    document.addEventListener('click', function (event) {

        const notificationLink =
            event.target.closest('.notification-link');

        if (!notificationLink) {
            return;
        }

        event.preventDefault();

        const notificationType =
            notificationLink.dataset.notificationType;

        const notificationId =
            notificationLink.dataset.notificationId;

        const targetUrl =
            notificationLink.href;

        if (!notificationType || !notificationId) {
            window.location.href = targetUrl;
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Prevent double click
        |--------------------------------------------------------------------------
        */

        notificationLink.style.pointerEvents = 'none';

        /*
        |--------------------------------------------------------------------------
        | Mark as read
        |--------------------------------------------------------------------------
        */

        fetch("{{ route('admin.notifications.read') }}", {
            method: 'POST',

            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',

                'X-CSRF-TOKEN':
                    "{{ csrf_token() }}"
            },

            body: JSON.stringify({
                notification_type: notificationType,
                notification_id: notificationId
            })
        })

        .then(function (response) {

            if (!response.ok) {
                throw new Error(
                    'Unable to mark notification as read.'
                );
            }

            return response.json();
        })

        .then(function (data) {

            /*
            |--------------------------------------------------------------------------
            | Update badge immediately
            |--------------------------------------------------------------------------
            */

            if (
                typeof window.updateNotificationBadge ===
                'function'
            ) {
                window.updateNotificationBadge(
                    Number(data.notificationCount || 0)
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Remove clicked notification immediately
            |--------------------------------------------------------------------------
            */

            notificationLink.remove();

            /*
            |--------------------------------------------------------------------------
            | Navigate after read
            |--------------------------------------------------------------------------
            */

            window.location.href = targetUrl;
        })

        .catch(function (error) {

            console.error(
                'Notification read error:',
                error
            );

            /*
            |--------------------------------------------------------------------------
            | Even if AJAX fails, allow user to open target page
            |--------------------------------------------------------------------------
            */

            window.location.href = targetUrl;
        });

    });

})();
</script>