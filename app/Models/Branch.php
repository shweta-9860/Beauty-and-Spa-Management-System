<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Branch extends Model
{
    protected $fillable = [
        'salon_id',
        'name',
        'code',
        'phone',
        'email',
        'address',
        'city',
        'state',
        'pincode',
        'manager_id',
        'opening_time',
        'closing_time',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function salon(): BelongsTo
    {
        return $this->belongsTo(Salon::class);
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function staff(): HasMany
    {
        return $this->hasMany(Staff::class);
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function bills(): HasMany
    {
        return $this->hasMany(Bill::class);
    }
}