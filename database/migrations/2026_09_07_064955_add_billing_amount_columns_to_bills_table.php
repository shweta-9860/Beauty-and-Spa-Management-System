<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bills', function (Blueprint $table) {

            if (!Schema::hasColumn('bills', 'service_total')) {
                $table->decimal('service_total', 12, 2)->default(0)->after('bill_date');
            }

            if (!Schema::hasColumn('bills', 'discount')) {
                $table->decimal('discount', 12, 2)->default(0)->after('service_total');
            }

            if (!Schema::hasColumn('bills', 'tax_percentage')) {
                $table->decimal('tax_percentage', 5, 2)->default(0)->after('discount');
            }

            if (!Schema::hasColumn('bills', 'tax_amount')) {
                $table->decimal('tax_amount', 12, 2)->default(0)->after('tax_percentage');
            }

            if (!Schema::hasColumn('bills', 'product_amount')) {
                $table->decimal('product_amount', 12, 2)->default(0)->after('tax_amount');
            }

            if (!Schema::hasColumn('bills', 'grand_total')) {
                $table->decimal('grand_total', 12, 2)->default(0)->after('product_amount');
            }
        });
    }

    public function down(): void
    {
        Schema::table('bills', function (Blueprint $table) {

            $columns = [
                'service_total',
                'discount',
                'tax_percentage',
                'tax_amount',
                'product_amount',
                'grand_total',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('bills', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};