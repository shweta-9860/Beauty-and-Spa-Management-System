<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Package extends Model
{
    protected $fillable = [
        'salon_id',
        'name',
        'code',
        'description',
        'price',
        'validity_days',
        'total_uses',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'status' => 'boolean',
        'validity_days' => 'integer',
        'total_uses' => 'integer',
    ];

    /**
     * Package belongs to Salon
     */
    public function salon(): BelongsTo
    {
        return $this->belongsTo(Salon::class);
    }

    /**
     * Package services
     */
    public function packageServices(): HasMany
    {
        return $this->hasMany(PackageService::class);
    }

    /**
     * Customers who purchased this package
     */
    public function customerPackages(): HasMany
    {
        return $this->hasMany(CustomerPackage::class);
    }

    /**
     * Calculate package expiry date
     */
    public function getExpiryDateAttribute()
    {
        if (!$this->created_at || !$this->validity_days) {
            return null;
        }

        return $this->created_at
            ->copy()
            ->startOfDay()
            ->addDays((int) $this->validity_days);
    }
}

