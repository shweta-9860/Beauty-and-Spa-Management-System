<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('package_services', function (Blueprint $table) {

            $table->id();

            // Package
            $table->foreignId('package_id')
                ->constrained('packages')
                ->cascadeOnDelete();

            // Service
            $table->foreignId('service_id')
                ->constrained('services')
                ->cascadeOnDelete();

            // Optional variation
            $table->foreignId('service_variation_id')
                ->nullable()
                ->constrained('service_variations')
                ->nullOnDelete();

            // How many times customer can use this service
            $table->unsignedInteger('quantity')
                ->default(1);

            $table->timestamps();

            $table->index([
                'package_id',
                'service_id'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('package_services');
    }
};