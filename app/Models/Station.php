<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Station extends Model
{
    protected $fillable = [
        "full_name",
        "short_name",
        "district",
        "state",
        "division_id",
    ];
}
