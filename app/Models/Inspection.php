<?php

namespace App\Models;

use App\Enums\InspectionDayPeriodEnum;
use App\Enums\InspectionStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Inspection extends Model
{
    protected $fillable = [
        'location',
        'address',
        'attended_by',
        'datetime',
        'day_period',
        'no_deficiencies_found',
        'status',
    ];

    protected $casts = [
        'no_deficiencies_found' => 'boolean',
        'datetime' => 'datetime',
        'day_period' => InspectionDayPeriodEnum::class,
        'status' => InspectionStatusEnum::class,
    ];

    public function deficiencies(): HasMany
    {
        return $this->hasMany(Deficiency::class);
    }

    public function attendedBy(): BelongsTo
    {
        return $this->belongsTo(User::class,'attended_by');
    }
}
