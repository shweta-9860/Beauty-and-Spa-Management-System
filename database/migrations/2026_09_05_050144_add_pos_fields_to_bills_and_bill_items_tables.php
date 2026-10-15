<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Bills
        |--------------------------------------------------------------------------
        */

        Schema::table('bills', function (Blueprint $table) {

            // Product amount used in POS grand total
            $table->decimal('product_amount', 10, 2)
                ->default(0)
                ->after('tax_amount');

            // Amount actually received
            $table->decimal('paid_amount', 10, 2)
                ->default(0)
                ->after('total');

            // Amount still pending
            $table->decimal('pending_amount', 10, 2)
                ->default(0)
                ->after('paid_amount');

            // Hold / POS status
            $table->string('bill_status')
                ->default('completed')
                ->after('payment_status');
        });


        /*
        |--------------------------------------------------------------------------
        | Bill Items
        |--------------------------------------------------------------------------
        */

        Schema::table('bill_items', function (Blueprint $table) {

            // Staff / Beautician who provided the service
            $table->foreignId('staff_id')
                ->nullable()
                ->after('service_variation_id')
                ->constrained('staff')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('bill_items', function (Blueprint $table) {

            $table->dropForeign(['staff_id']);
            $table->dropColumn('staff_id');
        });

        Schema::table('bills', function (Blueprint $table) {

            $table->dropColumn([
                'product_amount',
                'paid_amount',
                'pending_amount',
                'bill_status',
            ]);
        });
    }
};