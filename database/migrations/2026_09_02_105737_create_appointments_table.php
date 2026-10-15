
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
   

{
    // Appointments table was already created manually
    // during the previous migration attempt.
}

public function down(): void
{
    Schema::dropIfExists('appointments');
}
};

