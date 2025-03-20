<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inspection extends Model
{
    protected $fillable = [
        'location',
        'date',
        'inspection_type_id',
        'address',
        'attended_by',
        'daytime'
    ];
}
