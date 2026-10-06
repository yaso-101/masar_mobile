<?php

use App\Models\Ride;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Live driver location for a ride: only that ride's student and driver may listen
Broadcast::channel('ride-tracking.{ride}', function (User $user, Ride $ride) {
    return in_array($user->id, [$ride->student_id, $ride->driver_id]);
});
