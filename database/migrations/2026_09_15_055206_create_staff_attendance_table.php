<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('staff_attendance', function (Blueprint $table) {

            $table->id();

            // Salon
            $table->foreignId('salon_id')
                ->constrained('salons')
                ->cascadeOnDelete();

            // Branch
            $table->foreignId('branch_id')
                ->constrained('branches')
                ->cascadeOnDelete();

            // Staff
            $table->foreignId('staff_id')
                ->constrained('staff')
                ->cascadeOnDelete();

            // Attendance date
            $table->date('attendance_date');

            // Attendance status
            $table->enum('status', [
                'present',
                'absent',
                'half_day',
                'leave',
            ])->default('present');

            // Time
            $table->time('check_in')->nullable();
            $table->time('check_out')->nullable();

            // Optional notes
            $table->text('notes')->nullable();

            $table->timestamps();

            // One attendance record per staff per date
            $table->unique(
                ['staff_id', 'attendance_date'],
                'staff_attendance_staff_date_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff_attendance');
    }
};