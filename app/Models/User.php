<?php

namespace App\Models;

use App\Models\Salon;
use App\Models\Branch;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'google2fa_secret',
        'two_factor_enabled',
        'salon_id',
        'branch_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'google2fa_secret',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_enabled' => 'boolean',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Salon
    |--------------------------------------------------------------------------
    */

    public function salon(): BelongsTo
    {
        return $this->belongsTo(Salon::class, 'salon_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Branch
    |--------------------------------------------------------------------------
    */

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Managed Branches
    |--------------------------------------------------------------------------
    */

    public function managedBranches(): HasMany
    {
        return $this->hasMany(
            Branch::class,
            'manager_id'
        );
    }
}