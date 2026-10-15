<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Basic Relations
            |--------------------------------------------------------------------------
            */

            $table->foreignId('salon_id')
                ->constrained('salons')
                ->cascadeOnDelete();

            $table->foreignId('branch_id')
                ->constrained('branches')
                ->cascadeOnDelete();

            $table->foreignId('customer_id')
                ->constrained('customers')
                ->cascadeOnDelete();

            $table->foreignId('appointment_id')
                ->nullable()
                ->constrained('appointments')
                ->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Invoice
            |--------------------------------------------------------------------------
            */

            $table->string('invoice_number')->unique();

            $table->date('bill_date');

            /*
            |--------------------------------------------------------------------------
            | Amounts
            |--------------------------------------------------------------------------
            */

            $table->decimal('service_total', 12, 2)->default(0);

            $table->decimal('discount', 12, 2)->default(0);

            $table->decimal('tax_percentage', 5, 2)->default(0);

            $table->decimal('tax_amount', 12, 2)->default(0);

            $table->decimal('product_amount', 12, 2)->default(0);

            $table->decimal('grand_total', 12, 2)->default(0);

            /*
            |--------------------------------------------------------------------------
            | Payment
            |--------------------------------------------------------------------------
            */

            $table->enum('payment_method', [
                'cash',
                'upi',
                'card',
                'bank_transfer',
                'online',
            ])->nullable();

            $table->enum('payment_status', [
                'pending',
                'partial',
                'paid',
                'refunded',
            ])->default('pending');

            $table->decimal('paid_amount', 12, 2)->default(0);

            $table->decimal('pending_amount', 12, 2)->default(0);

            $table->string('transaction_id')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Bill Status
            |--------------------------------------------------------------------------
            */

            $table->enum('status', [
                'completed',
                'held',
                'cancelled',
                'refunded',
            ])->default('completed');

            /*
            |--------------------------------------------------------------------------
            | Notes
            |--------------------------------------------------------------------------
            */

            $table->text('notes')->nullable();

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->index('salon_id');
            $table->index('branch_id');
            $table->index('customer_id');
            $table->index('appointment_id');
            $table->index('payment_status');
            $table->index('bill_date');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bills');
    }
};