<?php

namespace App\Models;

use App\Enums\DeficiencyStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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

    public function inspection(): BelongsTo {
        return $this->belongsTo(Inspection::class);
    }

    public function pertainsTo(): BelongsTo {
        return $this->belongsTo(User::class, 'pertains_to');
    }
}
