<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bills', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | Coupon
            |--------------------------------------------------------------------------
            */

            $table->foreignId('coupon_id')
                ->nullable()
                ->after('discount')
                ->constrained('coupons')
                ->nullOnDelete();

            $table->string('coupon_code', 50)
                ->nullable()
                ->after('coupon_id');

            $table->decimal('coupon_discount', 12, 2)
                ->default(0)
                ->after('coupon_code');

            /*
            |--------------------------------------------------------------------------
            | Index
            |--------------------------------------------------------------------------
            */

            $table->index('coupon_id');
        });
    }

    public function down(): void
    {
        Schema::table('bills', function (Blueprint $table) {

            $table->dropForeign(['coupon_id']);

            $table->dropIndex(['coupon_id']);

            $table->dropColumn([
                'coupon_id',
                'coupon_code',
                'coupon_discount',
            ]);
        });
    }
};