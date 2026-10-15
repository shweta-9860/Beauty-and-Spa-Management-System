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
    Schema::create('salons', function (Blueprint $table) {
        $table->id();

        // Salon Profile
        $table->string('name');
        $table->string('logo')->nullable();

        // Salon Owner
        $table->foreignId('owner_id')
            ->nullable()
            ->constrained('users')
            ->nullOnDelete();

        // Contact
        $table->string('phone')->nullable();
        $table->string('email')->nullable();

        // Address
        $table->text('address')->nullable();
        $table->string('city')->nullable();
        $table->string('state')->nullable();
        $table->string('pincode', 10)->nullable();

        // GST
        $table->string('gst_number')->nullable();

        // Operating Hours
        $table->time('opening_time')->nullable();
        $table->time('closing_time')->nullable();

        // Salon Settings
        $table->string('currency', 10)->default('INR');
        $table->decimal('tax_percentage', 5, 2)->default(0);
        $table->string('invoice_prefix', 20)->default('INV');
        $table->text('receipt_footer')->nullable();
        $table->unsignedInteger('default_appointment_duration')->default(30);

        // Status
        $table->boolean('status')->default(true);

        $table->timestamps();

        $table->index('owner_id');
        $table->index('status');
    });
}



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salons');
    }
};
