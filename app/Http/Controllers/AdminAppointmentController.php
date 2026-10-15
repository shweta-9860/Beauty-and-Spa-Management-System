<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Customer;
use App\Models\Service;
use App\Models\Staff;
use App\Models\Branch;
use App\Models\StaffAttendance;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AdminAppointmentController extends Controller
{
    /**
     * Check whether logged-in user is a branch user.
     */
    private function isBranchUser(): bool
    {
        return auth()->check() && !empty(auth()->user()->branch_id);
    }

    /**
     * Get logged-in user's branch ID.
     */
    private function currentBranchId(): ?int
    {
        return $this->isBranchUser()
            ? (int) auth()->user()->branch_id
            : null;
    }

    /**
     * Get logged-in user's salon ID.
     */
    private function currentSalonId(): ?int
    {
        return auth()->check()
            ? (int) auth()->user()->salon_id
            : null;
    }

    /**
     * Check appointment branch access.
     */
    private function checkBranchAccess(Appointment $appointment): void
    {
        if (
            $this->isBranchUser() &&
            (int) $appointment->branch_id !== $this->currentBranchId()
        ) {
            abort(
                403,
                'You do not have access to this appointment.'
            );
        }
    }

    /**
     * Check whether staff is on leave for selected date.
     *
     * Leave is considered full-day leave.
     */
    private function checkStaffLeave(
        int $staffId,
        string $appointmentDate,
        int $branchId,
        ?int $salonId = null
    ): bool {

        $query = StaffAttendance::query()
            ->where('staff_id', $staffId)
            ->where('branch_id', $branchId)
            ->whereDate('attendance_date', $appointmentDate)

            /*
             * Handles:
             * leave
             * Leave
             * LEAVE
             *  leave
             */
            ->whereRaw(
                'LOWER(TRIM(status)) = ?',
                ['leave']
            );

        if ($salonId !== null) {
            $query->where('salon_id', $salonId);
        }

        return $query->exists();
    }

    /**
     * Display all appointments.
     */
    public function index(Request $request)
    {
        $query = Appointment::with([
            'salon',
            'customer',
            'service',
            'staff',
            'branch',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Branch Isolation
        |--------------------------------------------------------------------------
        */

        if ($this->isBranchUser()) {

            $query->where(
                'salon_id',
                $this->currentSalonId()
            )
            ->where(
                'branch_id',
                $this->currentBranchId()
            );

        } else {

            if ($request->filled('branch_id')) {
                $query->where(
                    'branch_id',
                    $request->branch_id
                );
            }

            if ($request->filled('salon_id')) {
                $query->where(
                    'salon_id',
                    $request->salon_id
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Search Customer
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = $request->search;

            $query->whereHas(
                'customer',
                function ($q) use ($search) {

                    $q->where(
                        'name',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhere(
                        'phone',
                        'like',
                        "%{$search}%"
                    );
                }
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
        | Appointment Date Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('appointment_date')) {

            $query->where(
                'appointment_date',
                $request->appointment_date
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Appointments
        |--------------------------------------------------------------------------
        */

        $appointments = $query
            ->orderBy(
                'appointment_date',
                'desc'
            )
            ->orderBy(
                'start_time',
                'asc'
            )
            ->paginate(10)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Branch Dropdown
        |--------------------------------------------------------------------------
        */

        if ($this->isBranchUser()) {

            $branches = Branch::where(
                    'id',
                    $this->currentBranchId()
                )
                ->where(
                    'salon_id',
                    $this->currentSalonId()
                )
                ->where(
                    'status',
                    1
                )
                ->get();

        } else {

            $branches = Branch::where(
                    'status',
                    1
                )
                ->when(
                    $request->filled('salon_id'),
                    function ($query) use ($request) {

                        $query->where(
                            'salon_id',
                            $request->salon_id
                        );
                    }
                )
                ->orderBy('name')
                ->get();
        }

        return view(
            'admin.appointments.index',
            compact(
                'appointments',
                'branches'
            )
        );
    }

    /**
     * Show create appointment form.
     */
    public function create()
    {
        /*
        |--------------------------------------------------------------------------
        | Branch User
        |--------------------------------------------------------------------------
        */

        if ($this->isBranchUser()) {

            $branchId = $this->currentBranchId();
            $salonId = $this->currentSalonId();

            /*
            | Customers - Current Branch Only
            */

            $customers = Customer::where(
                    'salon_id',
                    $salonId
                )
                ->where(
                    'branch_id',
                    $branchId
                )
                ->orderBy('name')
                ->get();

            /*
            | Services - Salon Level
            */

            $services = Service::where(
                    'salon_id',
                    $salonId
                )
                ->where(
                    'status',
                    1
                )
                ->orderBy('name')
                ->get();

            /*
            | Staff - Current Branch Only
            */

            $staff = Staff::where(
                    'salon_id',
                    $salonId
                )
                ->where(
                    'branch_id',
                    $branchId
                )
                ->where(
                    'status',
                    1
                )
                ->orderBy('name')
                ->get();

            /*
            | Current Branch Only
            */

            $branches = Branch::where(
                    'id',
                    $branchId
                )
                ->where(
                    'salon_id',
                    $salonId
                )
                ->where(
                    'status',
                    1
                )
                ->get();

        } else {

            $customers = Customer::orderBy('name')
                ->get();

            $services = Service::where(
                    'status',
                    1
                )
                ->orderBy('name')
                ->get();

            $staff = Staff::where(
                    'status',
                    1
                )
                ->orderBy('name')
                ->get();

            $branches = Branch::where(
                    'status',
                    1
                )
                ->orderBy('name')
                ->get();
        }

        return view(
            'admin.appointments.create',
            compact(
                'customers',
                'services',
                'staff',
                'branches'
            )
        );
    }

    /**
     * Store appointment.
     */
    public function store(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Branch User - Force Current Branch
        |--------------------------------------------------------------------------
        */

        if ($this->isBranchUser()) {

            $request->merge([
                'salon_id' => $this->currentSalonId(),
                'branch_id' => $this->currentBranchId(),
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'customer_id' =>
                'required|exists:customers,id',

            'service_id' =>
                'required|exists:services,id',

            'staff_id' =>
                'required|exists:staff,id',

            'branch_id' =>
                'required|exists:branches,id',

            'appointment_date' =>
                'required|date',

            'start_time' =>
                'required|date_format:H:i',

            'end_time' =>
                'required|date_format:H:i|after:start_time',

            'notes' =>
                'nullable|string|max:1000',

            'status' =>
                'required|in:pending,confirmed,arrived,in_service,completed,cancelled,no_show',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Branch User Security Check
        |--------------------------------------------------------------------------
        */

        if (
            $this->isBranchUser() &&
            (int) $validated['branch_id'] !== $this->currentBranchId()
        ) {
            abort(
                403,
                'You cannot create an appointment for another branch.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Get Selected Branch
        |--------------------------------------------------------------------------
        */

        $branch = Branch::findOrFail(
            $validated['branch_id']
        );

        /*
        |--------------------------------------------------------------------------
        | Branch Must Belong To User Salon
        |--------------------------------------------------------------------------
        */

        if (
            $this->isBranchUser() &&
            (int) $branch->salon_id !== $this->currentSalonId()
        ) {
            abort(
                403,
                'You cannot access this branch.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Related Records
        |--------------------------------------------------------------------------
        */

        $customer = Customer::findOrFail(
            $validated['customer_id']
        );

        $service = Service::findOrFail(
            $validated['service_id']
        );

        $staff = Staff::findOrFail(
            $validated['staff_id']
        );

        /*
        |--------------------------------------------------------------------------
        | Salon Consistency
        |--------------------------------------------------------------------------
        */

        if (
            $customer->salon_id != $branch->salon_id ||
            $service->salon_id != $branch->salon_id ||
            $staff->salon_id != $branch->salon_id
        ) {
            throw ValidationException::withMessages([
                'branch_id' =>
                    'Selected customer, service, staff and branch must belong to the same salon.'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Branch Consistency
        |--------------------------------------------------------------------------
        */

        if (
            $customer->branch_id != $branch->id ||
            $staff->branch_id != $branch->id
        ) {
            throw ValidationException::withMessages([
                'branch_id' =>
                    'Selected customer and staff must belong to the selected branch.'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Salon ID
        |--------------------------------------------------------------------------
        */

        $validated['salon_id'] = $branch->salon_id;

        /*
        |--------------------------------------------------------------------------
        | STAFF LEAVE CHECK
        |--------------------------------------------------------------------------
        */

        if (
            $this->checkStaffLeave(
                (int) $validated['staff_id'],
                $validated['appointment_date'],
                (int) $validated['branch_id'],
                (int) $validated['salon_id']
            )
        ) {
            throw ValidationException::withMessages([
                'staff_id' =>
                    'The selected staff member is on leave on the selected date. Please select another staff member.'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Double Booking Check
        |--------------------------------------------------------------------------
        */

        $overlap = Appointment::where(
                'staff_id',
                $validated['staff_id']
            )
            ->where(
                'branch_id',
                $validated['branch_id']
            )
            ->where(
                'appointment_date',
                $validated['appointment_date']
            )
            ->whereNotIn(
                'status',
                [
                    'cancelled',
                    'no_show',
                ]
            )
            ->where(function ($query) use ($validated) {

                $query->where(
                    'start_time',
                    '<',
                    $validated['end_time']
                )
                ->where(
                    'end_time',
                    '>',
                    $validated['start_time']
                );
            })
            ->exists();

        if ($overlap) {

            return back()
                ->withInput()
                ->withErrors([
                    'start_time' =>
                        'This staff member is already booked for the selected time slot.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Create Appointment
        |--------------------------------------------------------------------------
        */

        Appointment::create($validated);

        return redirect()
            ->route('admin.appointments.index')
            ->with(
                'success',
                'Appointment booked successfully.'
            );
    }

    /**
     * Show edit appointment form.
     */
    public function edit(Appointment $appointment)
    {
        $this->checkBranchAccess($appointment);

        if ($this->isBranchUser()) {

            $branchId = $this->currentBranchId();
            $salonId = $this->currentSalonId();

            $customers = Customer::where(
                    'salon_id',
                    $salonId
                )
                ->where(
                    'branch_id',
                    $branchId
                )
                ->orderBy('name')
                ->get();

            $services = Service::where(
                    'salon_id',
                    $salonId
                )
                ->where(
                    'status',
                    1
                )
                ->orderBy('name')
                ->get();

            $staff = Staff::where(
                    'salon_id',
                    $salonId
                )
                ->where(
                    'branch_id',
                    $branchId
                )
                ->where(
                    'status',
                    1
                )
                ->orderBy('name')
                ->get();

            $branches = Branch::where(
                    'id',
                    $branchId
                )
                ->where(
                    'salon_id',
                    $salonId
                )
                ->where(
                    'status',
                    1
                )
                ->get();

        } else {

            $customers = Customer::orderBy('name')
                ->get();

            $services = Service::where(
                    'status',
                    1
                )
                ->orderBy('name')
                ->get();

            $staff = Staff::where(
                    'status',
                    1
                )
                ->orderBy('name')
                ->get();

            $branches = Branch::where(
                    'status',
                    1
                )
                ->orderBy('name')
                ->get();
        }

        return view(
            'admin.appointments.edit',
            compact(
                'appointment',
                'customers',
                'services',
                'staff',
                'branches'
            )
        );
    }

    /**
     * Update appointment.
     */
    public function update(
        Request $request,
        Appointment $appointment
    ) {
        $this->checkBranchAccess($appointment);

        /*
        |--------------------------------------------------------------------------
        | Branch User - Force Current Branch
        |--------------------------------------------------------------------------
        */

        if ($this->isBranchUser()) {

            $request->merge([
                'salon_id' => $this->currentSalonId(),
                'branch_id' => $this->currentBranchId(),
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'customer_id' =>
                'required|exists:customers,id',

            'service_id' =>
                'required|exists:services,id',

            'staff_id' =>
                'required|exists:staff,id',

            'branch_id' =>
                'required|exists:branches,id',

            'appointment_date' =>
                'required|date',

            'start_time' =>
                'required|date_format:H:i',

            'end_time' =>
                'required|date_format:H:i|after:start_time',

            'notes' =>
                'nullable|string|max:1000',

            'status' =>
                'required|in:pending,confirmed,arrived,in_service,completed,cancelled,no_show',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Branch User Security
        |--------------------------------------------------------------------------
        */

        if (
            $this->isBranchUser() &&
            (int) $validated['branch_id'] !== $this->currentBranchId()
        ) {
            abort(
                403,
                'You cannot move this appointment to another branch.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Get Branch
        |--------------------------------------------------------------------------
        */

        $branch = Branch::findOrFail(
            $validated['branch_id']
        );

        /*
        |--------------------------------------------------------------------------
        | Branch Must Belong To User Salon
        |--------------------------------------------------------------------------
        */

        if (
            $this->isBranchUser() &&
            (int) $branch->salon_id !== $this->currentSalonId()
        ) {
            abort(
                403,
                'You cannot access this branch.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Related Records
        |--------------------------------------------------------------------------
        */

        $customer = Customer::findOrFail(
            $validated['customer_id']
        );

        $service = Service::findOrFail(
            $validated['service_id']
        );

        $staff = Staff::findOrFail(
            $validated['staff_id']
        );

        /*
        |--------------------------------------------------------------------------
        | Salon Consistency
        |--------------------------------------------------------------------------
        */

        if (
            $customer->salon_id != $branch->salon_id ||
            $service->salon_id != $branch->salon_id ||
            $staff->salon_id != $branch->salon_id
        ) {
            throw ValidationException::withMessages([
                'branch_id' =>
                    'Selected customer, service, staff and branch must belong to the same salon.'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Branch Consistency
        |--------------------------------------------------------------------------
        */

        if (
            $customer->branch_id != $branch->id ||
            $staff->branch_id != $branch->id
        ) {
            throw ValidationException::withMessages([
                'branch_id' =>
                    'Selected customer and staff must belong to the selected branch.'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Salon ID
        |--------------------------------------------------------------------------
        */

        $validated['salon_id'] = $branch->salon_id;

        /*
        |--------------------------------------------------------------------------
        | STAFF LEAVE CHECK
        |--------------------------------------------------------------------------
        */

        if (
            $this->checkStaffLeave(
                (int) $validated['staff_id'],
                $validated['appointment_date'],
                (int) $validated['branch_id'],
                (int) $validated['salon_id']
            )
        ) {
            throw ValidationException::withMessages([
                'staff_id' =>
                    'The selected staff member is on leave on the selected date. Please select another staff member.'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Double Booking Check
        |--------------------------------------------------------------------------
        */

        $overlap = Appointment::where(
                'staff_id',
                $validated['staff_id']
            )
            ->where(
                'branch_id',
                $validated['branch_id']
            )
            ->where(
                'appointment_date',
                $validated['appointment_date']
            )
            ->where(
                'id',
                '!=',
                $appointment->id
            )
            ->whereNotIn(
                'status',
                [
                    'cancelled',
                    'no_show',
                ]
            )
            ->where(function ($query) use ($validated) {

                $query->where(
                    'start_time',
                    '<',
                    $validated['end_time']
                )
                ->where(
                    'end_time',
                    '>',
                    $validated['start_time']
                );
            })
            ->exists();

        if ($overlap) {

            return back()
                ->withInput()
                ->withErrors([
                    'start_time' =>
                        'This staff member is already booked for the selected time slot.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Update Appointment
        |--------------------------------------------------------------------------
        */

        $appointment->update($validated);

        return redirect()
            ->route('admin.appointments.index')
            ->with(
                'success',
                'Appointment updated successfully.'
            );
    }

    /**
     * Delete appointment.
     */
    public function destroy(Appointment $appointment)
    {
        $this->checkBranchAccess($appointment);

        $appointment->delete();

        return redirect()
            ->route('admin.appointments.index')
            ->with(
                'success',
                'Appointment deleted successfully.'
            );
    }

    /**
     * Appointment Calendar.
     */
    public function calendar()
    {
        return view(
            'admin.appointments.calendar'
        );
    }

    /**
     * Calendar Events.
     */
    public function calendarEvents(Request $request)
    {
        $query = Appointment::with([
            'salon',
            'customer',
            'service',
            'staff',
            'branch',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Branch Isolation
        |--------------------------------------------------------------------------
        */

        if ($this->isBranchUser()) {

            $query->where(
                'salon_id',
                $this->currentSalonId()
            )
            ->where(
                'branch_id',
                $this->currentBranchId()
            );

        } else {

            if ($request->filled('branch_id')) {
                $query->where(
                    'branch_id',
                    $request->branch_id
                );
            }

            if ($request->filled('salon_id')) {
                $query->where(
                    'salon_id',
                    $request->salon_id
                );
            }
        }

        $appointments = $query->get();

        $events = $appointments->map(
            function ($appointment) {

                $statusColors = [

                    'pending' =>
                        '#ffc107',

                    'confirmed' =>
                        '#0d6efd',

                    'arrived' =>
                        '#0dcaf0',

                    'in_service' =>
                        '#6c757d',

                    'completed' =>
                        '#198754',

                    'cancelled' =>
                        '#dc3545',

                    'no_show' =>
                        '#212529',
                ];

                $color =
                    $statusColors[
                        $appointment->status
                    ] ?? '#6c757d';

                return [

                    'id' =>
                        $appointment->id,

                    'title' =>
                        ($appointment->customer->name ?? 'Customer')
                        . ' - '
                        . ($appointment->service->name ?? 'Service'),

                    'start' =>
                        $appointment
                            ->appointment_date
                            ->format('Y-m-d')
                        . 'T'
                        . $appointment->start_time,

                    'end' =>
                        $appointment
                            ->appointment_date
                            ->format('Y-m-d')
                        . 'T'
                        . $appointment->end_time,

                    'url' =>
                        route(
                            'admin.appointments.edit',
                            $appointment->id
                        ),

                    'backgroundColor' =>
                        $color,

                    'borderColor' =>
                        $color,

                    'extendedProps' => [

                        'salon' =>
                            $appointment->salon->name
                            ?? 'N/A',

                        'customer' =>
                            $appointment->customer->name
                            ?? 'N/A',

                        'service' =>
                            $appointment->service->name
                            ?? 'N/A',

                        'staff' =>
                            $appointment->staff->name
                            ?? 'N/A',

                        'branch' =>
                            $appointment->branch->name
                            ?? 'N/A',

                        'status' =>
                            $appointment->status,
                    ],
                ];
            }
        );

        return response()->json(
            $events
        );
    }

    /**
     * Check staff leave status using AJAX.
     *
     * Used by appointment create/edit form.
     */
    public function checkStaffLeaveStatus(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Validate Request
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'staff_id' => [
                'required',
                'integer',
                'exists:staff,id',
            ],

            'branch_id' => [
                'required',
                'integer',
                'exists:branches,id',
            ],

            'appointment_date' => [
                'required',
                'date',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Get Branch
        |--------------------------------------------------------------------------
        */

        $branch = Branch::findOrFail(
            $validated['branch_id']
        );

        /*
        |--------------------------------------------------------------------------
        | Branch User Security
        |--------------------------------------------------------------------------
        */

        if (
            $this->isBranchUser() &&
            (
                (int) $branch->id !== $this->currentBranchId() ||
                (int) $branch->salon_id !== $this->currentSalonId()
            )
        ) {
            abort(
                403,
                'You cannot check staff availability for another branch.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Get Staff
        |--------------------------------------------------------------------------
        */

        $staff = Staff::findOrFail(
            $validated['staff_id']
        );

        /*
        |--------------------------------------------------------------------------
        | Staff Must Belong To Selected Branch
        |--------------------------------------------------------------------------
        */

        if (
            (int) $staff->branch_id !==
            (int) $branch->id
        ) {
            return response()->json([
                'on_leave' => false,
                'message' =>
                    'Selected staff does not belong to the selected branch.',
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | Staff Must Belong To Same Salon
        |--------------------------------------------------------------------------
        */

        if (
            (int) $staff->salon_id !==
            (int) $branch->salon_id
        ) {
            return response()->json([
                'on_leave' => false,
                'message' =>
                    'Selected staff does not belong to the selected salon.',
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | Check Staff Leave
        |--------------------------------------------------------------------------
        */

        $onLeave = $this->checkStaffLeave(
            (int) $validated['staff_id'],
            $validated['appointment_date'],
            (int) $validated['branch_id'],
            (int) $branch->salon_id
        );

        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

        return response()->json([

            'on_leave' =>
                $onLeave,

            'message' =>
                $onLeave
                    ? 'This staff member is on leave on the selected date. Please select another staff member.'
                    : 'Staff is available on the selected date.',

            /*
             * Temporary debugging information.
             * यामुळे AJAX कोणती date check करत आहे ते दिसेल.
             */
            'checked_date' =>
                $validated['appointment_date'],

            'staff_id' =>
                (int) $validated['staff_id'],

            'branch_id' =>
                (int) $validated['branch_id'],

        ]);
    }
}