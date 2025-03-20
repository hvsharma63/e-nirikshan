<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deficiency extends Model
{
    protected $fillable = [
        'inspection_id',
        'pertains_to',
        'is_viewed',
        'is_attended',
        'action_date',
    ];
}
