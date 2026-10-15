<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    protected $fillable = [
        'salon_id',
        'name',
        'category',
        'price',
        'duration',
        'description',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'status' => 'boolean',
    ];

    public function salon(): BelongsTo
    {
        return $this->belongsTo(Salon::class);
    }

    public function variations(): HasMany
    {
        return $this->hasMany(ServiceVariation::class)
            ->orderBy('sort_order')
            ->orderBy('name');
    }

    public function billItems(): HasMany
    {
        return $this->hasMany(BillItem::class);
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }
    /**
 * Package services
 */
public function packageServices()
{
    return $this->hasMany(
        PackageService::class,
        'service_id'
    );
}

/**
 * Package usage history
 */
public function packageUsages()
{
    return $this->hasMany(
        PackageUsage::class,
        'service_id'
    );
}
}