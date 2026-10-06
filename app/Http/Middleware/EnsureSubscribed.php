<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSubscribed
{
    /**
     * Users whose FIB payment hasn't been confirmed only get to see the subscription page.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && ! $user->hasPaidSubscription()) {
            // Background fetch() calls (ride status polling, driver GPS) get a plain JSON answer
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Subscription required.'], 402);
            }

            return redirect('/subscription');
        }

        return $next($request);
    }
}
