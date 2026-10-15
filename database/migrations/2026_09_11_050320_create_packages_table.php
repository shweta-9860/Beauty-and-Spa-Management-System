<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('packages', function (Blueprint $table) {

            $table->id();

            // Salon
            $table->foreignId('salon_id')
                ->constrained('salons')
                ->cascadeOnDelete();

            // Package details
            $table->string('name');

            $table->string('code')
                ->nullable();

            $table->text('description')
                ->nullable();

            // Package price
            $table->decimal('price', 10, 2)
                ->default(0);

            // Validity in days
            $table->unsignedInteger('validity_days')
                ->default(30);

            // Total number of service uses
            $table->unsignedInteger('total_uses')
                ->default(1);

            // Active / Inactive
            $table->boolean('status')
                ->default(true);

            $table->timestamps();

            $table->index([
                'salon_id',
                'status'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};