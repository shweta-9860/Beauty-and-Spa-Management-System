<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('bills', 'status')) {
            Schema::table('bills', function (Blueprint $table) {
                $table->enum('status', [
                    'completed',
                    'held',
                    'cancelled',
                    'refunded',
                ])->default('completed')->after('transaction_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('bills', 'status')) {
            Schema::table('bills', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }
};