<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('discounts', function (Blueprint $table) {
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

            // Discount details
            $table->string('name');
            $table->text('description')->nullable();

            // percentage / fixed
            $table->enum('discount_type', ['percentage', 'fixed']);

            $table->decimal('discount_value', 10, 2);

            // service / package / membership / all
            $table->enum('applies_to', [
                'service',
                'package',
                'membership',
                'all'
            ])->default('all');

            // Validity
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            // Bill restrictions
            $table->decimal('minimum_bill_amount', 10, 2)
                ->default(0);

            $table->decimal('maximum_discount', 10, 2)
                ->nullable();

            // active / inactive
            $table->enum('status', ['active', 'inactive'])
                ->default('active');

            $table->timestamps();

            // Indexes
            $table->index(['salon_id', 'branch_id']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};