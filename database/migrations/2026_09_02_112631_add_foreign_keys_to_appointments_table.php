
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {

            // Customer
            $table->foreign('customer_id')
                ->references('id')
                ->on('customers')
                ->cascadeOnDelete();

            // Service
            $table->foreign('service_id')
                ->references('id')
                ->on('services')
                ->cascadeOnDelete();

            // Staff / Beautician
            $table->foreign('staff_id')
                ->references('id')
                ->on('staff')
                ->cascadeOnDelete();

            // Indexes for appointment filtering/calendar
            $table->index('customer_id');
            $table->index('service_id');
            $table->index('staff_id');
            $table->index('branch_id');
            $table->index('appointment_date');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {

            $table->dropForeign([
                'customer_id'
            ]);

            $table->dropForeign([
                'service_id'
            ]);

            $table->dropForeign([
                'staff_id'
            ]);

            $table->dropIndex([
                'appointments_customer_id_index'
            ]);

            $table->dropIndex([
                'appointments_service_id_index'
            ]);

            $table->dropIndex([
                'appointments_staff_id_index'
            ]);

            $table->dropIndex([
                'appointments_branch_id_index'
            ]);

            $table->dropIndex([
                'appointments_appointment_date_index'
            ]);

            $table->dropIndex([
                'appointments_status_index'
            ]);
        });
    }
};

