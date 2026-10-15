<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_memberships', function (Blueprint $table) {

            $table->id();

            $table->foreignId('salon_id')
                ->constrained('salons')
                ->cascadeOnDelete();

            $table->foreignId('customer_id')
                ->constrained('customers')
                ->cascadeOnDelete();

            $table->foreignId('membership_plan_id')
                ->constrained('membership_plans')
                ->cascadeOnDelete();

            $table->date('start_date');

            $table->date('expiry_date');

            $table->decimal('price', 10, 2)
                ->default(0);

            $table->decimal('discount', 10, 2)
                ->default(0);

            $table->decimal('paid_amount', 10, 2)
                ->default(0);

            $table->enum('status', [
                'pending',
                'active',
                'expired',
                'cancelled'
            ])->default('pending');

            $table->date('renewal_date')
                ->nullable();

            $table->text('notes')
                ->nullable();

            $table->timestamps();

            $table->index([
                'salon_id',
                'customer_id',
                'status'
            ]);

            $table->index([
                'membership_plan_id',
                'status'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_memberships');
    }
};

