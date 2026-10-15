<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('membership_benefits', function (Blueprint $table) {

            $table->id();

            $table->foreignId('membership_plan_id')
                ->constrained('membership_plans')
                ->cascadeOnDelete();

            $table->enum('benefit_type', [
                'discount',
                'free_service',
                'priority_booking',
                'custom',
            ])->default('discount');

            $table->foreignId('service_id')
                ->nullable()
                ->constrained('services')
                ->nullOnDelete();

            $table->enum('discount_type', [
                'percentage',
                'fixed',
                'none',
            ])->default('none');

            $table->decimal('discount_value', 10, 2)
                ->default(0);

            $table->unsignedInteger('free_uses')
                ->default(0);

            $table->string('title')
                ->nullable();

            $table->text('description')
                ->nullable();

            $table->boolean('status')
                ->default(true);

            $table->timestamps();

            $table->index([
                'membership_plan_id',
                'benefit_type',
                'status'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('membership_benefits');
    }
};

