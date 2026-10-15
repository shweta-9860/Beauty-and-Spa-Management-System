<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();

            // Salon
            $table->foreignId('salon_id')
                ->constrained('salons')
                ->cascadeOnDelete();

            // Optional branch
            $table->foreignId('branch_id')
                ->nullable()
                ->constrained('branches')
                ->nullOnDelete();

            // Coupon
            $table->string('code', 50);
            $table->text('description')->nullable();

            // percentage / fixed
            $table->enum('discount_type', ['percentage', 'fixed']);

            $table->decimal('discount_value', 10, 2);

            // Bill restrictions
            $table->decimal('minimum_bill_amount', 10, 2)
                ->default(0);

            $table->decimal('maximum_discount', 10, 2)
                ->nullable();

            // Validity
            $table->dateTime('valid_from')->nullable();
            $table->dateTime('valid_until')->nullable();

            // Usage
            $table->unsignedInteger('usage_limit')
                ->nullable();

            $table->unsignedInteger('used_count')
                ->default(0);

            $table->unsignedInteger('per_customer_limit')
                ->nullable();

            // Status
            $table->enum('status', ['active', 'inactive'])
                ->default('active');

            $table->timestamps();

            // Unique coupon within salon
            $table->unique(['salon_id', 'code']);

            // Indexes
            $table->index(['salon_id', 'branch_id']);
            $table->index('status');
            $table->index('valid_from');
            $table->index('valid_until');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};