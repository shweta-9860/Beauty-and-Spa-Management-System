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
    Schema::table('customers', function (Blueprint $table) {
        $table->foreignId('salon_id')
            ->nullable()
            ->after('id')
            ->constrained('salons')
            ->nullOnDelete();

        $table->foreignId('branch_id')
            ->nullable()
            ->after('salon_id')
            ->constrained('branches')
            ->nullOnDelete();

        $table->index(['salon_id', 'branch_id']);
    });
}

public function down(): void
{
    Schema::table('customers', function (Blueprint $table) {
        $table->dropForeign(['branch_id']);
        $table->dropForeign(['salon_id']);
        $table->dropIndex(['salon_id', 'branch_id']);
        $table->dropColumn(['salon_id', 'branch_id']);
    });
}
};
