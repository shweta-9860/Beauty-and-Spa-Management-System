<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void
{
    Schema::create('expenses', function (Blueprint $table) {
        $table->id();

        $table->foreignId('salon_id')
            ->constrained('salons')
            ->cascadeOnDelete();

        $table->foreignId('branch_id')
            ->nullable()
            ->constrained('branches')
            ->nullOnDelete();

        $table->foreignId('expense_category_id')
            ->constrained('expense_categories')
            ->cascadeOnDelete();

        $table->date('expense_date');

        $table->string('title');

        $table->decimal('amount', 12, 2);

        $table->string('payment_method');

        $table->string('paid_to')->nullable();

        $table->string('reference_no')->nullable();

        $table->text('description')->nullable();

        $table->string('attachment')->nullable();

        $table->boolean('status')->default(true);

        $table->foreignId('created_by')
            ->nullable()
            ->constrained('users')
            ->nullOnDelete();

        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
