<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StaffAttendance extends Model
{
    protected $table = 'staff_attendance';

    protected $fillable = [
        'salon_id',
        'branch_id',
        'staff_id',
        'attendance_date',
        'status',
        'check_in',
        'check_out',
        'working_hours',
        'notes',
    ];

    protected $casts = [
        'attendance_date' => 'date',
    ];

    /**
     * Salon
     */
    public function salon(): BelongsTo
    {
        return $this->belongsTo(Salon::class);
    }

    /**
     * Branch
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Staff
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }
}
