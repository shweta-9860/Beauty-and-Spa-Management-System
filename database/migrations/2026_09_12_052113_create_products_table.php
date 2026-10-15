<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->foreignId('salon_id')
                ->constrained('salons')
                ->cascadeOnDelete();

            $table->foreignId('category_id')
                ->nullable()
                ->constrained('product_categories')
                ->nullOnDelete();

            // Supplier table आपण पुढच्या step मध्ये तयार करू.
            $table->foreignId('supplier_id')
                ->nullable();

            $table->string('name');
            $table->string('sku');
            $table->string('brand')->nullable();

            $table->decimal('purchase_price', 12, 2)->default(0);
            $table->decimal('selling_price', 12, 2)->default(0);

            $table->decimal('stock', 12, 2)->default(0);
            $table->decimal('minimum_stock', 12, 2)->default(0);

            $table->string('unit')->default('piece');

            $table->date('expiry_date')->nullable();

            $table->boolean('status')->default(true);

            $table->timestamps();

            $table->unique(['salon_id', 'sku']);
            $table->index(['salon_id', 'stock']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};