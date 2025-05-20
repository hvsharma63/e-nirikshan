<?php

declare(strict_types=1);

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\DeficiencyStatusEnum;
use App\Enums\InspectionStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'dob',
        'pf_no',
        'mobile_no'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function userDesignations(): HasMany
    {
        return $this->hasMany(UserDesignation::class);
    }

    public function activeDesignation(): HasOne
    {
        return $this->hasOne(UserDesignation::class)
            ->where('is_active', true);
    }

    public function inspectionsAttended(): HasMany
    {
        return $this->hasMany(Inspection::class, 'attended_by');
    }

    public function inspectionsInProgress(): HasMany
    {
        return $this->hasMany(Inspection::class, 'attended_by')
            ->where('status', InspectionStatusEnum::PROGRESS);
    }

    public function inspectionsCompleted(): HasMany
    {
        return $this->hasMany(Inspection::class, 'attended_by')
            ->where('status', InspectionStatusEnum::COMPLETED);
    }

    public function deficienciesAttended(): HasMany
    {
        return $this->hasMany(Deficiency::class, 'pertains_to')
            ->where('status', DeficiencyStatusEnum::ATTENDED);
    }

    public function deficienciesPending(): HasMany
    {
        return $this->hasMany(Deficiency::class, 'pertains_to')
            ->where('status', DeficiencyStatusEnum::PENDING);
    }

    public function deficienciesSeen(): HasMany
    {
        return $this->hasMany(Deficiency::class, 'pertains_to')
            ->where('status', DeficiencyStatusEnum::SEEN);
    }

    public function deficienciesTotal(): HasMany
    {
        return $this->hasMany(Deficiency::class, 'pertains_to');
    }

    public function recentInspections(): HasMany
    {
        return $this->hasMany(Inspection::class, 'attended_by')
            ->latest()
            ->take(5);
    }

    public function recentDeficiencies(): HasMany
    {
        return $this->hasMany(Deficiency::class, 'pertains_to')
            ->latest()
            ->take(5);
    }
}
