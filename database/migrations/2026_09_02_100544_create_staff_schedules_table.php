<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('staff_schedules', function (Blueprint $table) {

            $table->id();

            $table->foreignId('staff_id')
                ->constrained('staff')
                ->cascadeOnDelete();

            $table->enum('day', [
                'Monday',
                'Tuesday',
                'Wednesday',
                'Thursday',
                'Friday',
                'Saturday',
                'Sunday'
            ]);

            $table->time('start_time')->nullable();

            $table->time('end_time')->nullable();

            $table->time('break_start')->nullable();

            $table->time('break_end')->nullable();

            $table->enum('status', [
                'working',
                'leave'
            ])->default('working');

            $table->string('leave_reason')->nullable();

            $table->timestamps();

            $table->unique(['staff_id', 'day']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff_schedules');
    }
};