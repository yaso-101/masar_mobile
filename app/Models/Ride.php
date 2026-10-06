<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
    use HasFactory;

    /**
     * Get the student who requested this ride.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * 🚀 ADDED: Get the driver assigned to this ride.
     */
    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    /**
     * Get the college for this ride.
     */
    public function college(): BelongsTo
    {
        // The second parameter tells Laravel exactly which column to look at!
        return $this->belongsTo(College::class, 'ending_point_college_id');
    }
}
