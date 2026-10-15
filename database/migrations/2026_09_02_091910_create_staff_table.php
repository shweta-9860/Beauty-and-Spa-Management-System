
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('staff', function (Blueprint $table) {

            $table->id();

            // Personal Details
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();

            $table->enum('gender', [
                'male',
                'female',
                'other'
            ])->nullable();

            $table->date('date_of_birth')->nullable();
            $table->text('address')->nullable();

            // Professional Details
            $table->string('role');
            $table->string('specialization')->nullable();

            // Salary & Commission
            $table->decimal('salary', 12, 2)->default(0);

            $table->enum('commission_type', [
                'percentage',
                'fixed'
            ])->default('percentage');

            $table->decimal('commission_value', 10, 2)
                ->default(0);

            // Branch
            // Branch module will be created later,
            // therefore keeping this nullable for now.
            $table->unsignedBigInteger('branch_id')->nullable();

            // Joining & Status
            $table->date('joining_date')->nullable();

            $table->boolean('status')
                ->default(true);

            // Profile Image
            $table->string('profile_image')->nullable();

            $table->timestamps();

            // Indexes
            $table->index('role');
            $table->index('branch_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};

