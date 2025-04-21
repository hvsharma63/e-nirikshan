<?php

namespace App\Models;

use App\Enums\DesignationLevelEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Designation extends Model
{
    protected $fillable = [
        "full_name",
        "short_name",
        "level"
    ];

    protected $casts = [
        'level' => DesignationLevelEnum::class,
    ];

    public function scopeBranchOfficers(Builder $query): Builder
    {
        return $query->where('level', DesignationLevelEnum::BRANCH_OFFICER);
    }
}
