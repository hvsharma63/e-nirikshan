<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    protected $fillable = [
        'deficiency_id',
        'comment_by',
        'comment',
    ];


    public function deficiency(): BelongsTo
    {
        return $this->belongsTo(Deficiency::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'comment_by');
    }
}
