<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class College extends Model
{
    protected $fillable = [
        'name',
        'latitude',
        'longitude',
    ];
    protected $table = 'colleges';
}
