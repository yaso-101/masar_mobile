<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\Fillable;

// These exact names must match your database screenshot!
#[Fillable([
    'student_id',
    'driver_id',
    'pickup_lat',
    'pickup_long',
    'ending_point_college_id',
    'status'
])]
class Ride extends Model
{
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
