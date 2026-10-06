<?php

use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Artisan;
use App\Models\Ride;
use App\Models\Subscription;

// This tells Laravel to run this specific function every single night at 2:00 AM
Schedule::call(function () {

    // Find all rides that were finished yesterday, and reset them for today
    Ride::where('status', 'completed')
        ->update(['status' => 'pending']);

})->dailyAt('02:00');


// For whoever checks the FIB payments (instead of editing the database by hand):
//   php artisan subscription:paid 07701234567            -> marks that user as paid
//   php artisan subscription:paid 07701234567 --unpaid   -> takes it back
// An email works too (accounts made before sign-up asked for a phone number have none)
Artisan::command('subscription:paid {who : Phone number or email} {--unpaid : Mark as NOT paid instead}', function (string $who) {
    $subscription = Subscription::where('phone_number', Subscription::normalizePhone($who))
        ->orWhereHas('user', fn ($query) => $query->where('email', $who))
        ->first();

    if (! $subscription) {
        $this->error("No account found for {$who}.");

        return 1;
    }

    $subscription->update(['is_paid' => ! $this->option('unpaid')]);

    $this->info(sprintf(
        '%s (%s) is now %s.',
        $subscription->user->name,
        $subscription->phone_number ?? $subscription->user->email,
        $subscription->is_paid ? 'PAID' : 'NOT paid'
    ));
})->purpose("Mark a user's subscription as paid (or unpaid with --unpaid)");
