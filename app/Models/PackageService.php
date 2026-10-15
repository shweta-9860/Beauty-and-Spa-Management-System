<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PackageService extends Model
{
    protected $fillable = [
        'package_id',
        'service_id',
        'service_variation_id',
        'quantity',
    ];

    protected $casts = [
        'quantity' => 'integer',
    ];

    /**
     * Package
     */
    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    /**
     * Service
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Service Variation
     */
    public function serviceVariation(): BelongsTo
    {
        return $this->belongsTo(
            ServiceVariation::class,
            'service_variation_id'
        );
    }

    /**
     * Usage records
     */
    public function usages()
    {
        return $this->hasMany(
            PackageUsage::class,
            'package_service_id'
        );
    }
}