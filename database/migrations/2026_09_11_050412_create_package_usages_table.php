<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('package_usages', function (Blueprint $table) {

            $table->id();

            // Salon
            $table->foreignId('salon_id')
                ->constrained('salons')
                ->cascadeOnDelete();

            // Customer package
            $table->foreignId('customer_package_id')
                ->constrained('customer_packages')
                ->cascadeOnDelete();

            // Customer
            $table->foreignId('customer_id')
                ->constrained('customers')
                ->cascadeOnDelete();

            // Package service
            $table->foreignId('package_service_id')
                ->constrained('package_services')
                ->cascadeOnDelete();

            // Actual service used
            $table->foreignId('service_id')
                ->constrained('services')
                ->cascadeOnDelete();

            // Optional variation
            $table->foreignId('service_variation_id')
                ->nullable()
                ->constrained('service_variations')
                ->nullOnDelete();

            // Appointment / Bill reference
            $table->foreignId('appointment_id')
                ->nullable()
                ->constrained('appointments')
                ->nullOnDelete();

            $table->foreignId('bill_id')
                ->nullable()
                ->constrained('bills')
                ->nullOnDelete();

            // Number of services used
            $table->unsignedInteger('quantity')
                ->default(1);

            // Date
            $table->date('used_date');

            // Optional notes
            $table->text('notes')
                ->nullable();

            $table->timestamps();

            $table->index([
                'customer_id',
                'customer_package_id',
                'used_date'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('package_usages');
    }
};