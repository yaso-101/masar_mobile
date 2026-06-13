<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany; // Added for the relationship
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Role;
use App\Models\Ride; // Added to reference the Ride model

#[Fillable(['name', 'email', 'password', 'role_id'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get the rides associated with this user (if they are a student).
     */
    public function ridesAsStudent(): HasMany
    {
        return $this->hasMany(Ride::class, 'student_id');
    }

    /**
     * Check if the user is a student.
     */
    public function isStudent(): bool
    {
        return $this->role_id === 1;
    }
}
