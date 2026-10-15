<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\StaffAttendance;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StaffAttendanceController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Current User Helpers
    |--------------------------------------------------------------------------
    */

    private function isBranchUser(): bool
    {
        return auth()->check() && !empty(auth()->user()->branch_id);
    }

    private function currentBranchId(): ?int
    {
        return auth()->user()?->branch_id;
    }

    private function currentSalonId(): ?int
    {
        return auth()->user()?->salon_id;
    }

    /*
    |--------------------------------------------------------------------------
    | Staff Access
    |--------------------------------------------------------------------------
    */

    private function checkStaffAccess(Staff $staff): void
    {
        $salonId = $this->currentSalonId();

        if ($salonId && (int) $staff->salon_id !== (int) $salonId) {
            abort(403, 'You do not have access to this staff member.');
        }

        if ($this->isBranchUser()) {
            if ((int) $staff->branch_id !== (int) $this->currentBranchId()) {
                abort(403, 'You do not have access to this staff member.');
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Attendance Access
    |--------------------------------------------------------------------------
    */

    private function checkAttendanceAccess(StaffAttendance $attendance): void
    {
        $salonId = $this->currentSalonId();

        if ($salonId && (int) $attendance->salon_id !== (int) $salonId) {
            abort(403, 'You do not have access to this attendance record.');
        }

        if ($this->isBranchUser()) {
            if ((int) $attendance->branch_id !== (int) $this->currentBranchId()) {
                abort(403, 'You do not have access to this attendance record.');
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Calculate Working Hours
    |--------------------------------------------------------------------------
    |
    | Example:
    |
    | 09:00 -> 21:00 = 12:00
    | 09:00 -> 20:00 = 11:00
    | 09:00 -> 20:59 = 11:59
    |
    */

    private function calculateWorkingHours(
        ?string $checkIn,
        ?string $checkOut
    ): ?string {
        if (!$checkIn || !$checkOut) {
            return null;
        }

        try {
            /*
             * Convert check-in time to minutes.
             */
            [$inHour, $inMinute] = array_map(
                'intval',
                explode(':', substr($checkIn, 0, 5))
            );

            /*
             * Convert check-out time to minutes.
             */
            [$outHour, $outMinute] = array_map(
                'intval',
                explode(':', substr($checkOut, 0, 5))
            );

            $startMinutes =
                ($inHour * 60) + $inMinute;

            $endMinutes =
                ($outHour * 60) + $outMinute;

            /*
             * If checkout is after midnight.
             */
            if ($endMinutes < $startMinutes) {
                $endMinutes += 24 * 60;
            }

            $workingMinutes =
                $endMinutes - $startMinutes;

            /*
             * Convert minutes to HH:MM.
             */
            $hours =
                intdiv($workingMinutes, 60);

            $minutes =
                $workingMinutes % 60;

            return sprintf(
                '%02d:%02d',
                $hours,
                $minutes
            );

        } catch (\Throwable $e) {
            return null;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Calculate Attendance Status
    |--------------------------------------------------------------------------
    |
    | 12 hours or more = Full Day
    | Less than 12 hours = Half Day
    |
    | Database:
    | present  = Full Day
    | half_day = Half Day
    |
    */

    private function calculateAttendanceStatus(
        ?string $checkIn,
        ?string $checkOut,
        string $currentStatus = 'present'
    ): string {

        /*
         * Absent and Leave should remain unchanged.
         */
        if (in_array(
            $currentStatus,
            ['absent', 'leave']
        )) {
            return $currentStatus;
        }

        /*
         * Check-in without checkout means
         * attendance is still in progress.
         */
        if (!$checkIn || !$checkOut) {
            return 'present';
        }

        try {

            /*
             * Convert check-in to minutes.
             */
            [$inHour, $inMinute] = array_map(
                'intval',
                explode(':', substr($checkIn, 0, 5))
            );

            /*
             * Convert check-out to minutes.
             */
            [$outHour, $outMinute] = array_map(
                'intval',
                explode(':', substr($checkOut, 0, 5))
            );

            $startMinutes =
                ($inHour * 60) + $inMinute;

            $endMinutes =
                ($outHour * 60) + $outMinute;

            /*
             * If checkout is after midnight.
             */
            if ($endMinutes < $startMinutes) {
                $endMinutes += 24 * 60;
            }

            $workingMinutes =
                $endMinutes - $startMinutes;

            /*
             * 12 hours = 720 minutes.
             *
             * 720 or more = Full Day
             * Less than 720 = Half Day
             */
            if ($workingMinutes >= 720) {
                return 'present';
            }

            return 'half_day';

        } catch (\Throwable $e) {

            /*
             * Safe fallback.
             */
            return 'half_day';
        }
    }

    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */
public function index(Request $request)
{
    /*
     * =========================================================
     * Historical Attendance
     * =========================================================
     */

    $query = StaffAttendance::with([
        'staff',
        'branch',
        'salon'
    ]);

    /*
     * Salon isolation
     */
    if ($this->currentSalonId()) {
        $query->where(
            'salon_id',
            $this->currentSalonId()
        );
    }

    /*
     * Branch isolation
     */
    if ($this->isBranchUser()) {
        $query->where(
            'branch_id',
            $this->currentBranchId()
        );
    } elseif ($request->filled('branch_id')) {
        $query->where(
            'branch_id',
            $request->branch_id
        );
    }

    /*
     * Date filter
     */
    if ($request->filled('attendance_date')) {
        $query->whereDate(
            'attendance_date',
            $request->attendance_date
        );
    }

    /*
     * Staff filter
     */
    if ($request->filled('staff_id')) {
        $query->where(
            'staff_id',
            $request->staff_id
        );
    }

    /*
     * Status filter
     */
    if ($request->filled('status')) {
        $query->where(
            'status',
            $request->status
        );
    }

    $attendances = $query
        ->orderByDesc('attendance_date')
        ->orderBy('staff_id')
        ->paginate(15)
        ->withQueryString();


    /*
     * =========================================================
     * Active Staff
     * =========================================================
     */

    $staffQuery = Staff::where(
        'status',
        true
    )->orderBy('name');

    if ($this->currentSalonId()) {
        $staffQuery->where(
            'salon_id',
            $this->currentSalonId()
        );
    }

    if ($this->isBranchUser()) {
        $staffQuery->where(
            'branch_id',
            $this->currentBranchId()
        );
    }

    $staff = $staffQuery->get();


    /*
     * =========================================================
     * Today's Attendance
     * =========================================================
     */

    $today = now()->toDateString();

    $todayQuery = StaffAttendance::with('staff')
        ->whereDate(
            'attendance_date',
            $today
        );

    if ($this->currentSalonId()) {
        $todayQuery->where(
            'salon_id',
            $this->currentSalonId()
        );
    }

    if ($this->isBranchUser()) {
        $todayQuery->where(
            'branch_id',
            $this->currentBranchId()
        );
    }

    $todayAttendances = $todayQuery
        ->get()
        ->keyBy('staff_id');


    /*
     * =========================================================
     * Today's Staff Attendance Summary
     *
     * This includes every active staff member.
     * If no attendance record exists for today,
     * staff will be shown as Absent.
     * =========================================================
     */

    $todayStaffAttendance = $staff->map(function ($staffMember) use ($todayAttendances) {

        $todayAttendance = $todayAttendances->get(
            $staffMember->id
        );


        /*
         * No attendance record
         * => Absent
         */
        if (!$todayAttendance) {

            return [
                'staff' => $staffMember,
                'attendance' => null,
                'status' => 'absent',
            ];
        }


        /*
         * Leave
         */
        if ($todayAttendance->status === 'leave') {

            return [
                'staff' => $staffMember,
                'attendance' => $todayAttendance,
                'status' => 'leave',
            ];
        }


        /*
         * Checked in but not checked out
         * => Working
         */
        if (
            $todayAttendance->check_in &&
            !$todayAttendance->check_out
        ) {

            return [
                'staff' => $staffMember,
                'attendance' => $todayAttendance,
                'status' => 'working',
            ];
        }


        /*
         * Checked in + checked out
         *
         * Calculate:
         * Full Day / Half Day
         */
        if (
            $todayAttendance->check_in &&
            $todayAttendance->check_out
        ) {

            $status = $this->calculateAttendanceStatus(
                $todayAttendance->check_in,
                $todayAttendance->check_out,
                $todayAttendance->status
            );

            return [
                'staff' => $staffMember,
                'attendance' => $todayAttendance,
                'status' => $status,
            ];
        }


        /*
         * Explicit Absent
         */
        if ($todayAttendance->status === 'absent') {

            return [
                'staff' => $staffMember,
                'attendance' => $todayAttendance,
                'status' => 'absent',
            ];
        }


        /*
         * Explicit Half Day
         */
        if ($todayAttendance->status === 'half_day') {

            return [
                'staff' => $staffMember,
                'attendance' => $todayAttendance,
                'status' => 'half_day',
            ];
        }


        /*
         * Default
         */
        return [
            'staff' => $staffMember,
            'attendance' => $todayAttendance,
            'status' => $todayAttendance->status ?? 'present',
        ];
    });


    /*
     * =========================================================
     * Return View
     * =========================================================
     */

    return view(
        'admin.staff.attendance.index',
        compact(
            'attendances',
            'staff',
            'todayAttendances',
            'todayStaffAttendance'
        )
    );
}
    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */
public function create()
{
    /*
    |--------------------------------------------------------------------------
    | ACTIVE STAFF
    |--------------------------------------------------------------------------
    */

    $staffQuery = Staff::where(
        'status',
        true
    )->orderBy('name');

    // Salon filter
    if ($this->currentSalonId()) {
        $staffQuery->where(
            'salon_id',
            $this->currentSalonId()
        );
    }

    // Branch filter
    if ($this->isBranchUser()) {
        $staffQuery->where(
            'branch_id',
            $this->currentBranchId()
        );
    }

    $staff = $staffQuery->get();


    /*
    |--------------------------------------------------------------------------
    | TODAY'S ATTENDANCE
    |--------------------------------------------------------------------------
    */

    $todayAttendanceQuery = StaffAttendance::whereDate(
        'attendance_date',
        today()
    );

    // Salon filter
    if ($this->currentSalonId()) {
        $todayAttendanceQuery->where(
            'salon_id',
            $this->currentSalonId()
        );
    }

    // Branch filter
    if ($this->isBranchUser()) {
        $todayAttendanceQuery->where(
            'branch_id',
            $this->currentBranchId()
        );
    }

    /*
    |--------------------------------------------------------------------------
    | TODAY'S ATTENDANCE COLLECTION
    |
    | keyBy(staff_id) is important because Blade uses:
    | $todayAttendances->get($staffMember->id)
    |--------------------------------------------------------------------------
    */

    $todayAttendances = $todayAttendanceQuery
        ->orderByDesc('id')
        ->get()
        ->keyBy('staff_id');


    /*
    |--------------------------------------------------------------------------
    | ATTENDANCE HISTORY
    |--------------------------------------------------------------------------
    |
    | Blade uses:
    | - firstItem()
    | - hasPages()
    | - links()
    |
    | Therefore paginate() is required here.
    |
    */

    $attendanceQuery = StaffAttendance::query();

    // Salon filter
    if ($this->currentSalonId()) {
        $attendanceQuery->where(
            'salon_id',
            $this->currentSalonId()
        );
    }

    // Branch filter
    if ($this->isBranchUser()) {
        $attendanceQuery->where(
            'branch_id',
            $this->currentBranchId()
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DATE FILTER
    |--------------------------------------------------------------------------
    */

    if (request()->filled('attendance_date')) {
        $attendanceQuery->whereDate(
            'attendance_date',
            request('attendance_date')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STAFF FILTER
    |--------------------------------------------------------------------------
    */

    if (request()->filled('staff_id')) {
        $attendanceQuery->where(
            'staff_id',
            request('staff_id')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STATUS FILTER
    |--------------------------------------------------------------------------
    */

    if (request()->filled('status')) {
        $attendanceQuery->where(
            'status',
            request('status')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | PAGINATED ATTENDANCE HISTORY
    |--------------------------------------------------------------------------
    */

    $attendances = $attendanceQuery
        ->with([
            'staff',
            'branch'
        ])
        ->orderByDesc('attendance_date')
        ->orderByDesc('id')
        ->paginate(10)
        ->withQueryString();


    /*
    |--------------------------------------------------------------------------
    | RETURN VIEW
    |--------------------------------------------------------------------------
    */

    return view(
        'admin.staff.attendance.create',
        compact(
            'staff',
            'todayAttendances',
            'attendances'
        )
    );
}

    /*
    |--------------------------------------------------------------------------
    | MANUAL STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $request->validate([
            'staff_id' => [
                'required',
                'exists:staff,id'
            ],

            'attendance_date' => [
                'required',
                'date'
            ],

            'status' => [
                'required',
                'in:present,absent,half_day,leave'
            ],

            'check_in' => [
                'nullable',
                'date_format:H:i'
            ],

            'check_out' => [
                'nullable',
                'date_format:H:i'
            ],

            'notes' => [
                'nullable',
                'string',
                'max:1000'
            ],
        ]);

        $staff = Staff::findOrFail(
            $validated['staff_id']
        );

        $this->checkStaffAccess($staff);

        /*
         * Check duplicate attendance.
         */

        $exists = StaffAttendance::where(
                'salon_id',
                $staff->salon_id
            )
            ->where(
                'branch_id',
                $staff->branch_id
            )
            ->where(
                'staff_id',
                $staff->id
            )
            ->whereDate(
                'attendance_date',
                $validated['attendance_date']
            )
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->withErrors([
                    'attendance_date' =>
                        'Attendance already exists for this staff member on this date.'
                ]);
        }

        /*
         * Absent / Leave
         */

        if (in_array(
            $validated['status'],
            ['absent', 'leave']
        )) {
            $validated['check_in'] = null;
            $validated['check_out'] = null;
        }

        /*
         * Calculate Working Hours.
         */

        $workingHours =
            $this->calculateWorkingHours(
                $validated['check_in'] ?? null,
                $validated['check_out'] ?? null
            );

        /*
         * Automatically calculate status.
         */

        $attendanceStatus =
            $this->calculateAttendanceStatus(
                $validated['check_in'] ?? null,
                $validated['check_out'] ?? null,
                $validated['status']
            );

        StaffAttendance::create([
            'salon_id' =>
                $staff->salon_id,

            'branch_id' =>
                $staff->branch_id,

            'staff_id' =>
                $staff->id,

            'attendance_date' =>
                $validated['attendance_date'],

            'status' =>
                $attendanceStatus,

            'check_in' =>
                $validated['check_in'] ?? null,

            'check_out' =>
                $validated['check_out'] ?? null,

            'working_hours' =>
                $workingHours,

            'notes' =>
                $validated['notes'] ?? null,
        ]);

        return redirect()
            ->route(
                'admin.staff.attendance.index'
            )
            ->with(
                'success',
                'Staff attendance recorded successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | CHECK IN
    |--------------------------------------------------------------------------
    */

    public function checkIn(Staff $staff)
    {
        $this->checkStaffAccess($staff);

        $today = now()->toDateString();

        /*
         * Find today's attendance.
         */

        $attendance = StaffAttendance::where(
                'salon_id',
                $staff->salon_id
            )
            ->where(
                'branch_id',
                $staff->branch_id
            )
            ->where(
                'staff_id',
                $staff->id
            )
            ->whereDate(
                'attendance_date',
                $today
            )
            ->first();

        /*
         * Already checked in.
         */

        if ($attendance && $attendance->check_in) {
            return back()->withErrors([
                'attendance' =>
                    $staff->name .
                    ' has already checked in today.'
            ]);
        }

        /*
         * Absent / Leave cannot check in.
         */

        if (
            $attendance &&
            in_array(
                $attendance->status,
                ['absent', 'leave']
            )
        ) {
            return back()->withErrors([
                'attendance' =>
                    'Staff is marked as ' .
                    ucfirst($attendance->status) .
                    ' today.'
            ]);
        }

        $checkInTime =
            now()->format('H:i');

        /*
         * Create attendance.
         */

        if (!$attendance) {

            StaffAttendance::create([
                'salon_id' =>
                    $staff->salon_id,

                'branch_id' =>
                    $staff->branch_id,

                'staff_id' =>
                    $staff->id,

                'attendance_date' =>
                    $today,

                'status' =>
                    'present',

                'check_in' =>
                    $checkInTime,

                'check_out' =>
                    null,

                'working_hours' =>
                    null,

                'notes' =>
                    null,
            ]);

        } else {

            /*
             * Existing attendance.
             */

            $attendance->update([
                'status' =>
                    'present',

                'check_in' =>
                    $checkInTime,

                'check_out' =>
                    null,

                'working_hours' =>
                    null,
            ]);
        }

        return back()->with(
            'success',
            $staff->name .
            ' checked in successfully at ' .
            now()->format('h:i A') .
            '.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CHECK OUT
    |--------------------------------------------------------------------------
    */

    public function checkOut(Staff $staff)
    {
        $this->checkStaffAccess($staff);

        $today = now()->toDateString();

        /*
         * Find today's attendance.
         */

        $attendance = StaffAttendance::where(
                'salon_id',
                $staff->salon_id
            )
            ->where(
                'branch_id',
                $staff->branch_id
            )
            ->where(
                'staff_id',
                $staff->id
            )
            ->whereDate(
                'attendance_date',
                $today
            )
            ->first();

        /*
         * No attendance.
         */

        if (!$attendance) {
            return back()->withErrors([
                'attendance' =>
                    $staff->name .
                    ' has not checked in today.'
            ]);
        }

        /*
         * No check-in.
         */

        if (!$attendance->check_in) {
            return back()->withErrors([
                'attendance' =>
                    $staff->name .
                    ' has not checked in today.'
            ]);
        }

        /*
         * Already checked out.
         */

        if ($attendance->check_out) {
            return back()->withErrors([
                'attendance' =>
                    $staff->name .
                    ' has already checked out today.'
            ]);
        }

        $checkOutTime =
            now()->format('H:i');

        /*
         * Calculate working hours.
         */

        $workingHours =
            $this->calculateWorkingHours(
                $attendance->check_in,
                $checkOutTime
            );

        /*
         * Calculate Full Day / Half Day.
         */

        $attendanceStatus =
            $this->calculateAttendanceStatus(
                $attendance->check_in,
                $checkOutTime,
                $attendance->status
            );

        /*
         * Update attendance.
         */

        $attendance->update([
            'check_out' =>
                $checkOutTime,

            'status' =>
                $attendanceStatus,

            'working_hours' =>
                $workingHours,
        ]);

        $statusText =
            $attendanceStatus === 'present'
                ? 'Full Day'
                : 'Half Day';

        return back()->with(
            'success',
            $staff->name .
            ' checked out successfully at ' .
            now()->format('h:i A') .
            '. Working hours: ' .
            ($workingHours ?? '00:00') .
            ' (' .
            $statusText .
            ')'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(
        StaffAttendance $attendance
    ) {
        $this->checkAttendanceAccess(
            $attendance
        );

        $staffQuery = Staff::where(
            'status',
            true
        )->orderBy('name');

        if ($this->currentSalonId()) {
            $staffQuery->where(
                'salon_id',
                $this->currentSalonId()
            );
        }

        if ($this->isBranchUser()) {
            $staffQuery->where(
                'branch_id',
                $this->currentBranchId()
            );
        }

        $staff = $staffQuery->get();

        return view(
            'admin.staff.attendance.edit',
            compact(
                'attendance',
                'staff'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        StaffAttendance $attendance
    ) {
        $this->checkAttendanceAccess(
            $attendance
        );

        $validated = $request->validate([
            'staff_id' => [
                'required',
                'exists:staff,id'
            ],

            'attendance_date' => [
                'required',
                'date'
            ],

            'status' => [
                'required',
                'in:present,absent,half_day,leave'
            ],

            'check_in' => [
                'nullable',
                'date_format:H:i'
            ],

            'check_out' => [
                'nullable',
                'date_format:H:i'
            ],

            'notes' => [
                'nullable',
                'string',
                'max:1000'
            ],
        ]);

        $staff = Staff::findOrFail(
            $validated['staff_id']
        );

        $this->checkStaffAccess($staff);

        /*
         * Duplicate attendance.
         */

        $exists = StaffAttendance::where(
                'salon_id',
                $staff->salon_id
            )
            ->where(
                'branch_id',
                $staff->branch_id
            )
            ->where(
                'staff_id',
                $staff->id
            )
            ->whereDate(
                'attendance_date',
                $validated['attendance_date']
            )
            ->where(
                'id',
                '!=',
                $attendance->id
            )
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->withErrors([
                    'attendance_date' =>
                        'Attendance already exists for this staff member on this date.'
                ]);
        }

        /*
         * Absent / Leave.
         */

        if (
            in_array(
                $validated['status'],
                ['absent', 'leave']
            )
        ) {
            $validated['check_in'] = null;
            $validated['check_out'] = null;
        }

        /*
         * Calculate Working Hours.
         */

        $workingHours =
            $this->calculateWorkingHours(
                $validated['check_in'] ?? null,
                $validated['check_out'] ?? null
            );

        /*
         * Automatically calculate status.
         */

        $attendanceStatus =
            $this->calculateAttendanceStatus(
                $validated['check_in'] ?? null,
                $validated['check_out'] ?? null,
                $validated['status']
            );

        /*
         * Update attendance.
         */

        $attendance->update([
            'salon_id' =>
                $staff->salon_id,

            'branch_id' =>
                $staff->branch_id,

            'staff_id' =>
                $staff->id,

            'attendance_date' =>
                $validated['attendance_date'],

            'status' =>
                $attendanceStatus,

            'check_in' =>
                $validated['check_in'] ?? null,

            'check_out' =>
                $validated['check_out'] ?? null,

            'working_hours' =>
                $workingHours,

            'notes' =>
                $validated['notes'] ?? null,
        ]);

        return redirect()
            ->route(
                'admin.staff.attendance.index'
            )
            ->with(
                'success',
                'Staff attendance updated successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy(
        StaffAttendance $attendance
    ) {
        $this->checkAttendanceAccess(
            $attendance
        );

        $attendance->delete();

        return redirect()
            ->route(
                'admin.staff.attendance.index'
            )
            ->with(
                'success',
                'Staff attendance deleted successfully.'
            );
    }
}