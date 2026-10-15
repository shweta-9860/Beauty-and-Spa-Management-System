<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wastages', function (Blueprint $table) {

            $table->id();

            $table->foreignId('salon_id')
                ->constrained('salons')
                ->cascadeOnDelete();

            $table->foreignId('branch_id')
                ->constrained('branches')
                ->cascadeOnDelete();

            $table->foreignId('product_id')
                ->constrained('products')
                ->cascadeOnDelete();

            $table->foreignId('staff_id')
                ->nullable()
                ->constrained('staff')
                ->nullOnDelete();

            $table->string('wastage_number')->unique();

            $table->date('wastage_date');

            $table->decimal('quantity', 10, 2);

            $table->decimal('unit_cost', 12, 2)->default(0);

            $table->decimal('total_cost', 12, 2)->default(0);

            $table->enum('reason', [
                'damaged',
                'expired',
                'spillage',
                'leakage',
                'broken',
                'contaminated',
                'lost',
                'other',
            ]);

            $table->text('notes')->nullable();

            $table->enum('status', [
                'pending',
                'approved',
                'rejected',
            ])->default('pending');

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wastages');
    }
};