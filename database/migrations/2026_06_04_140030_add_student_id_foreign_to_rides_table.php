<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rides', function (Blueprint $table) {
            // If student_id doesn't exist yet, uncomment the next line:
            // $table->unsignedBigInteger('student_id')->after('id');

            $table->foreign('student_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade'); // or 'set null' depending on your business logic
        });
    }

    public function down(): void
    {
        Schema::table('rides', function (Blueprint $table) {
            $table->dropForeign(['student_id']);
        });
    }
};
