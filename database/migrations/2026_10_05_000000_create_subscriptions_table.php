<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();

            // The number the user pays from; whoever checks FIB payments matches against it.
            // Nullable only for accounts created before sign-up asked for a phone number.
            $table->string('phone_number', 20)->nullable()->unique();

            // 'yes' or 'no' (easy to read and change by hand); set to 'yes' once the payment is confirmed.
            // The database only accepts these two values.
            $table->enum('is_paid', ['no', 'yes'])->default('no');

            $table->timestamps();
        });

        // Existing accounts get an (unpaid) row too, so they show up in the table
        $now = now();
        DB::table('users')->pluck('id')->each(fn ($userId) => DB::table('subscriptions')->insert([
            'user_id' => $userId,
            'is_paid' => 'no',
            'created_at' => $now,
            'updated_at' => $now,
        ]));
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
