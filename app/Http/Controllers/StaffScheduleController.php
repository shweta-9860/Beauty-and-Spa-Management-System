<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\StaffSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StaffScheduleController extends Controller
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
        return auth()->user()?->branch_id;
    }

    /**
     * Check whether staff belongs to logged-in branch.
     */
    private function checkBranchAccess(Staff $staff): void
    {
        if ($this->isBranchUser()) {
            if (
                (int) $staff->branch_id !==
                (int) $this->currentBranchId()
            ) {
                abort(
                    403,
                    'You do not have access to this staff member.'
                );
            }
        }
    }

    /**
     * Days of week.
     */
    private function days(): array
    {
        return [
            'Monday',
            'Tuesday',
            'Wednesday',
            'Thursday',
            'Friday',
            'Saturday',
            'Sunday',
        ];
    }

    /**
     * Get staff query according to logged-in user.
     *
     * Branch user:
     *     Only staff from current branch.
     *
     * Main salon/admin user:
     *     Only staff from current salon.
     */
    private function accessibleStaffQuery()
    {
        $query = Staff::query();

        if ($this->isBranchUser()) {

            $query->where(
                'branch_id',
                $this->currentBranchId()
            );

        } else {

            $userSalonId = auth()->user()?->salon_id;

            if (!empty($userSalonId)) {
                $query->where(
                    'salon_id',
                    $userSalonId
                );
            }
        }

        return $query;
    }

    /**
     * Display ALL staff schedules.
     *
     * URL:
     * /admin/staff/schedule
     */
    public function list()
    {
        /*
         * Get all accessible staff.
         */
        $staffMembers = $this->accessibleStaffQuery()
            ->with('branch')
            ->orderBy('name')
            ->get();

        /*
         * Get IDs of all visible staff.
         */
        $staffIds = $staffMembers->pluck('id');

        /*
         * Get schedules for all visible staff.
         *
         * Result:
         *
         * staff_id
         *      Monday
         *      Tuesday
         *      Wednesday
         *      ...
         */
        $allSchedules = StaffSchedule::whereIn(
                'staff_id',
                $staffIds
            )
            ->get()
            ->groupBy('staff_id');

        /*
         * Send everything to bulk schedule Blade.
         */
        return view(
            'admin.staff.schedule',
            [
                'staffMembers' => $staffMembers,
                'allSchedules' => $allSchedules,
                'days' => $this->days(),
            ]
        );
    }

    /**
     * Store / update schedules for ALL staff.
     *
     * Expected form structure:
     *
     * schedule[STAFF_ID][Monday][status]
     * schedule[STAFF_ID][Monday][start_time]
     * schedule[STAFF_ID][Monday][end_time]
     * schedule[STAFF_ID][Monday][break_start]
     * schedule[STAFF_ID][Monday][break_end]
     * schedule[STAFF_ID][Monday][leave_reason]
     */
    public function storeAll(Request $request)
    {
        /*
         * Validate main schedule array.
         */
        $validated = $request->validate([

            'schedule' => [
                'required',
                'array',
            ],

            'schedule.*' => [
                'required',
                'array',
            ],

            'schedule.*.*' => [
                'required',
                'array',
            ],

            'schedule.*.*.status' => [
                'required',
                'in:working,leave',
            ],

            'schedule.*.*.start_time' => [
                'nullable',
                'date_format:H:i',
            ],

            'schedule.*.*.end_time' => [
                'nullable',
                'date_format:H:i',
            ],

            'schedule.*.*.break_start' => [
                'nullable',
                'date_format:H:i',
            ],

            'schedule.*.*.break_end' => [
                'nullable',
                'date_format:H:i',
            ],

            'schedule.*.*.leave_reason' => [
                'nullable',
                'string',
                'max:255',
            ],
        ]);

        /*
         * Get IDs of staff accessible to current user.
         */
        $accessibleStaffIds = $this->accessibleStaffQuery()
            ->pluck('id')
            ->map(
                fn ($id) => (string) $id
            )
            ->toArray();

        /*
         * Security check.
         *
         * User cannot submit another
         * branch/salon staff ID.
         */
        foreach (
            array_keys($validated['schedule'])
            as $staffId
        ) {

            if (
                !in_array(
                    (string) $staffId,
                    $accessibleStaffIds,
                    true
                )
            ) {
                abort(
                    403,
                    'You do not have permission to update this staff schedule.'
                );
            }
        }

        /*
         * Save everything inside transaction.
         */
        DB::transaction(function () use ($validated) {

            foreach (
                $validated['schedule']
                as $staffId => $staffSchedule
            ) {

                foreach (
                    $staffSchedule
                    as $day => $data
                ) {

                    /*
                     * Ignore invalid day names.
                     */
                    if (
                        !in_array(
                            $day,
                            $this->days(),
                            true
                        )
                    ) {
                        continue;
                    }

                    $status = $data['status'];

                    /*
                     |--------------------------------------------------------------------------
                     | LEAVE
                     |--------------------------------------------------------------------------
                     */

                    if ($status === 'leave') {

                        $startTime = null;
                        $endTime = null;
                        $breakStart = null;
                        $breakEnd = null;

                        $leaveReason =
                            $data['leave_reason'] ?? null;

                    }

                    /*
                     |--------------------------------------------------------------------------
                     | WORKING
                     |--------------------------------------------------------------------------
                     */

                    else {

                        /*
                         * Start and end time are compulsory
                         * for working day.
                         */
                        if (
                            empty($data['start_time']) ||
                            empty($data['end_time'])
                        ) {

                            throw ValidationException::withMessages([
                                "schedule.$staffId.$day.start_time" =>
                                    "$day requires start time and end time."
                            ]);
                        }

                        $startTime =
                            $data['start_time'];

                        $endTime =
                            $data['end_time'];

                        $breakStart =
                            $data['break_start'] ?? null;

                        $breakEnd =
                            $data['break_end'] ?? null;

                        /*
                         * Leave reason is not applicable
                         * for working days.
                         */
                        $leaveReason = null;
                    }

                    /*
                     |--------------------------------------------------------------------------
                     | INSERT / UPDATE
                     |--------------------------------------------------------------------------
                     */

                    StaffSchedule::updateOrCreate(

                        [
                            'staff_id' => $staffId,
                            'day' => $day,
                        ],

                        [
                            'start_time' => $startTime,
                            'end_time' => $endTime,
                            'break_start' => $breakStart,
                            'break_end' => $breakEnd,
                            'status' => $status,
                            'leave_reason' => $leaveReason,
                        ]
                    );
                }
            }
        });

        /*
         * Return to all staff schedule page.
         */
        return redirect()
            ->route(
                'admin.staff.schedule.list'
            )
            ->with(
                'success',
                'All staff weekly schedules updated successfully.'
            );
    }

    /**
     * Old individual schedule URL.
     *
     * Example:
     * /admin/staff/1/schedule
     *
     * Since schedule is now managed for ALL staff
     * on one page, redirect old URLs to common page.
     */
    public function index(Staff $staff)
    {
        /*
         * Keep branch security check.
         */
        $this->checkBranchAccess($staff);

        /*
         * Redirect to all staff schedule page.
         */
        return redirect()->route(
            'admin.staff.schedule.list'
        );
    }

    /**
     * Store schedule for ONE staff member.
     *
     * Kept for old/legacy forms.
     */
    public function store(
        Request $request,
        Staff $staff
    ) {
        /*
         * Branch access check.
         */
        $this->checkBranchAccess($staff);

        /*
         * Validate individual schedule.
         */
        $validated = $request->validate([

            'schedule' => [
                'required',
                'array',
            ],

            'schedule.*.start_time' => [
                'nullable',
                'date_format:H:i',
            ],

            'schedule.*.end_time' => [
                'nullable',
                'date_format:H:i',
            ],

            'schedule.*.break_start' => [
                'nullable',
                'date_format:H:i',
            ],

            'schedule.*.break_end' => [
                'nullable',
                'date_format:H:i',
            ],

            'schedule.*.status' => [
                'required',
                'in:working,leave',
            ],

            'schedule.*.leave_reason' => [
                'nullable',
                'string',
                'max:255',
            ],
        ]);

        /*
         * Save individual schedule.
         */
        foreach (
            $validated['schedule']
            as $day => $data
        ) {

            /*
             * Ignore invalid day names.
             */
            if (
                !in_array(
                    $day,
                    $this->days(),
                    true
                )
            ) {
                continue;
            }

            $status = $data['status'];

            /*
             |--------------------------------------------------------------------------
             | LEAVE
             |--------------------------------------------------------------------------
             */

            if ($status === 'leave') {

                $startTime = null;
                $endTime = null;
                $breakStart = null;
                $breakEnd = null;

                $leaveReason =
                    $data['leave_reason'] ?? null;

            }

            /*
             |--------------------------------------------------------------------------
             | WORKING
             |--------------------------------------------------------------------------
             */

            else {

                if (
                    empty($data['start_time']) ||
                    empty($data['end_time'])
                ) {

                    return back()
                        ->withInput()
                        ->withErrors([
                            "schedule.$day.start_time" =>
                                "$day requires start time and end time."
                        ]);
                }

                $startTime =
                    $data['start_time'];

                $endTime =
                    $data['end_time'];

                $breakStart =
                    $data['break_start'] ?? null;

                $breakEnd =
                    $data['break_end'] ?? null;

                $leaveReason = null;
            }

            /*
             * Insert or update.
             */
            StaffSchedule::updateOrCreate(

                [
                    'staff_id' => $staff->id,
                    'day' => $day,
                ],

                [
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'break_start' => $breakStart,
                    'break_end' => $breakEnd,
                    'status' => $status,
                    'leave_reason' => $leaveReason,
                ]
            );
        }

        /*
         * Redirect to common schedule page.
         */
        return redirect()
            ->route(
                'admin.staff.schedule.list'
            )
            ->with(
                'success',
                'Staff weekly schedule updated successfully.'
            );
    }
}