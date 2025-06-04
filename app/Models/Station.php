<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Station extends Model
{
    protected $fillable = [
        "full_name",
        "short_name",
        "district",
        "state",
        "division_id",
    ];


    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }
}
