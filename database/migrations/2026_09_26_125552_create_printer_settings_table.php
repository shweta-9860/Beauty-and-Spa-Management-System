<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('printer_settings', function (Blueprint $table) {
            $table->id();

            // Salon-wise printer settings
            $table->foreignId('salon_id')
                ->constrained('salons')
                ->cascadeOnDelete();

            $table->string('printer_name')->nullable();

            // browser / bluetooth / usb
            $table->string('default_print_method')
                ->default('browser');

            // 58mm / 80mm
            $table->string('paper_size')
                ->default('80mm');

            $table->boolean('print_logo')
                ->default(true);

            $table->boolean('print_signature')
                ->default(true);

            $table->boolean('auto_cut')
                ->default(false);

            $table->timestamps();

            $table->unique('salon_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('printer_settings');
    }
};