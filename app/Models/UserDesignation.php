<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDesignation extends Model
{
    protected $fillable = [
        'user_id',
        'division_departments_id',
        'station_id',
        'designation_id',
        'address_asc',
        'address_desc',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
