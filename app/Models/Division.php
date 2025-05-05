<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    protected $fillable = [
        "full_name",
        "short_name",
        "zone_id",
    ];
}
