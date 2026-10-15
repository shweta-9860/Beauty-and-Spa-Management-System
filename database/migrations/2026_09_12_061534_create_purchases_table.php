<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchases', function (Blueprint $table) {

            $table->id();

            // Salon
            $table->foreignId('salon_id')
                ->constrained()
                ->cascadeOnDelete();

            // Branch
            $table->foreignId('branch_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            // Supplier
            $table->foreignId('supplier_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            // Purchase information
            $table->string('purchase_number')->unique();
            $table->date('purchase_date');

            // Amounts
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('tax', 12, 2)->default(0);
            $table->decimal('grand_total', 12, 2)->default(0);

            // Payment
            $table->enum('payment_status', [
                'pending',
                'partial',
                'paid'
            ])->default('pending');

            // Purchase status
            $table->enum('status', [
                'draft',
                'received',
                'cancelled'
            ])->default('received');

            $table->text('notes')->nullable();

            $table->timestamps();

            // Useful indexes
            $table->index(['salon_id', 'purchase_date']);
            $table->index('supplier_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};