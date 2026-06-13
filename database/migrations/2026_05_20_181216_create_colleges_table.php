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
        Schema::create('colleges', function (Blueprint $table) {
            $table->id();

            // The name of the college (e.g., "State University")
            $table->string('name');

            // The precise location for the map
            // decimal(10, 8) is standard for latitude
            $table->decimal('latitude', 10, 8);

            // The precise location for the map
            // decimal(11, 8) is standard for longitude
            $table->decimal('longitude', 11, 8);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('colleges');
    }
};
