<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('membership_plans', function (Blueprint $table) {
            $table->id();

            $table->foreignId('salon_id')
                ->constrained('salons')
                ->cascadeOnDelete();

            $table->string('name');
            $table->string('code')->nullable();

            $table->text('description')->nullable();

            $table->enum('billing_cycle', [
                'monthly',
                'quarterly',
                'yearly'
            ])->default('monthly');

            $table->decimal('price', 10, 2)->default(0);

            $table->unsignedInteger('validity_days')->default(30);

            $table->enum('discount_type', [
                'percentage',
                'fixed',
                'none'
            ])->default('percentage');

            $table->decimal('discount_value', 10, 2)->default(0);

            $table->boolean('status')->default(true);

            $table->timestamps();

            $table->index(['salon_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('membership_plans');
    }
};

