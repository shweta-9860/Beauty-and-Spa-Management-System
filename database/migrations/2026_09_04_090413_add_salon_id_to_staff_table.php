<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('staff', function (Blueprint $table) {

            $table->foreignId('salon_id')
                ->nullable()
                ->after('id')
                ->constrained('salons')
                ->cascadeOnDelete();

            $table->index('salon_id');
        });
    }

    public function down(): void
    {
        Schema::table('staff', function (Blueprint $table) {

            $table->dropForeign(['salon_id']);
            $table->dropIndex(['staff_salon_id_index']);
            $table->dropColumn('salon_id');
        });
    }
};