<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_packages', function (Blueprint $table) {

            $table->id();

            // Salon
            $table->foreignId('salon_id')
                ->constrained('salons')
                ->cascadeOnDelete();

            // Customer
            $table->foreignId('customer_id')
                ->constrained('customers')
                ->cascadeOnDelete();

            // Package
            $table->foreignId('package_id')
                ->constrained('packages')
                ->cascadeOnDelete();

            // Purchase reference
            $table->string('purchase_number')
                ->unique();

            // Financial details
            $table->decimal('price', 10, 2)
                ->default(0);

            $table->decimal('discount', 10, 2)
                ->default(0);

            $table->decimal('paid_amount', 10, 2)
                ->default(0);

            // Usage
            $table->unsignedInteger('total_uses')
                ->default(0);

            $table->unsignedInteger('used_uses')
                ->default(0);

            $table->unsignedInteger('remaining_uses')
                ->default(0);

            // Dates
            $table->date('purchase_date');

            $table->date('expiry_date');

            // active / expired / exhausted / cancelled
            $table->enum('status', [
                'active',
                'expired',
                'exhausted',
                'cancelled'
            ])->default('active');

            $table->timestamps();

            $table->index([
                'salon_id',
                'customer_id',
                'status'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_packages');
    }
};