<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'deficiency_id',
        'comment_by',
        'comment',
    ];
}
