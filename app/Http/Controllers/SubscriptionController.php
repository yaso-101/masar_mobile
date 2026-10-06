<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    /**
     * The "pay with FIB" page. Until their payment is confirmed, this is the only page a user sees.
     */
    public function show()
    {
        $user = Auth::user();

        // Already paid? Nothing to do here, go use the app
        if ($user->hasPaidSubscription()) {
            return redirect('/');
        }

        return view('components.pages.subscription', [
            'payToNumber' => config('subscription.pay_to_number'),
            'phoneNumber' => $user->subscription?->phone_number,
        ]);
    }
}
