<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bill_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('bill_id')
                ->constrained('bills')
                ->cascadeOnDelete();

            $table->foreignId('service_id')
                ->constrained('services')
                ->cascadeOnDelete();

            $table->foreignId('service_variation_id')
                ->nullable()
                ->constrained('service_variations')
                ->nullOnDelete();

            $table->foreignId('staff_id')
                ->nullable()
                ->constrained('staff')
                ->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Item Information
            |--------------------------------------------------------------------------
            */

            $table->string('item_name');

            $table->decimal('quantity', 10, 2)->default(1);

            $table->decimal('unit_price', 12, 2)->default(0);

            $table->decimal('discount', 12, 2)->default(0);

            $table->decimal('tax_amount', 12, 2)->default(0);

            $table->decimal('total_amount', 12, 2)->default(0);

            $table->timestamps();

            $table->index('bill_id');
            $table->index('service_id');
            $table->index('staff_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bill_items');
    }
};