
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_variations', function (Blueprint $table) {
            $table->id();

            // Parent service
            $table->foreignId('service_id')
                ->constrained('services')
                ->cascadeOnDelete();

            // Variation details
            $table->string('name');

            $table->decimal('price', 10, 2);

            $table->string('duration')->nullable();

            $table->text('description')->nullable();

            $table->boolean('status')->default(true);

            $table->integer('sort_order')->default(0);

            $table->timestamps();

            // Prevent duplicate variation names for same service
            $table->unique(['service_id', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_variations');
    }
};

