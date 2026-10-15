<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bill_payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('bill_id')
                ->constrained('bills')
                ->cascadeOnDelete();

            $table->enum('payment_method', [
                'cash',
                'upi',
                'card',
                'bank_transfer',
                'online',
            ]);

            $table->decimal('amount', 12, 2);

            $table->string('transaction_id')->nullable();

            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index('bill_id');
            $table->index('payment_method');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bill_payments');
    }
};