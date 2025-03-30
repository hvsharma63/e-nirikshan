<?php

namespace App\Models;

use App\Enums\DeficiencyStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;

class Deficiency extends Model
{
    use Notifiable;
    
    protected $fillable = [
        'inspection_id' ,
        'pertains_to',
        'note',
        'status',
        'action_date',
    ];

    protected $casts = [
        'status' => DeficiencyStatusEnum::class,
    ];

    public function getIsPendingAttribute(): bool
    {
        return $this->status->value === DeficiencyStatusEnum::PENDING;
    }

    public function getIsSeenAttribute(): bool
    {
        return $this->status->value >= DeficiencyStatusEnum::SEEN;
    }

    public function getIsAttendedAttribute(): bool
    {
        return $this->status->value === DeficiencyStatusEnum::ATTENEDED;
    }

    public function inspection(): BelongsTo {
        return $this->belongsTo(Inspection::class);
    }

    public function pertainsTo(): BelongsTo {
        return $this->belongsTo(User::class, 'pertains_to');
    }

    public function comment(): HasOne {
        return $this->hasOne(Comment::class);
    }
}
