<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        // salon_id and branch_id already exist in customers table.
    }

    public function down(): void
    {
        // Nothing to rollback.
    }
};