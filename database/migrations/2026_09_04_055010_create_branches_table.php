<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

public function up(): void
{
    Schema::create('branches', function (Blueprint $table) {
        $table->id();

        // Salon
        $table->foreignId('salon_id')
            ->constrained('salons')
            ->cascadeOnDelete();

        // Branch Details
        $table->string('name');
        $table->string('code')->unique();

        // Contact
        $table->string('phone')->nullable();
        $table->string('email')->nullable();

        // Address
        $table->text('address')->nullable();
        $table->string('city')->nullable();
        $table->string('state')->nullable();
        $table->string('pincode', 10)->nullable();

        // Branch Manager
        $table->foreignId('manager_id')
            ->nullable()
            ->constrained('users')
            ->nullOnDelete();

        // Operating Hours
        $table->time('opening_time')->nullable();
        $table->time('closing_time')->nullable();

        // Status
        $table->boolean('status')->default(true);

        $table->timestamps();

        $table->index('salon_id');
        $table->index('manager_id');
        $table->index('status');
    });
}



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};
