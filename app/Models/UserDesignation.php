<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserDesignation extends Model
{
    protected $fillable = [
        'user_id',
        'division_departments_id',
        'station_id',
        'designation_id',
        'address_asc',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];


    public function designation(): BelongsTo
    {
        return $this->belongsTo(Designation::class);
    }
}
