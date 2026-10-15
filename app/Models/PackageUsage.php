<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PackageUsage extends Model
{
    protected $fillable = [
        'salon_id',
        'customer_package_id',
        'customer_id',
        'package_service_id',
        'service_id',
        'service_variation_id',
        'appointment_id',
        'bill_id',
        'quantity',
        'used_date',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'used_date' => 'date',
    ];

    /**
     * Salon
     */
    public function salon(): BelongsTo
    {
        return $this->belongsTo(Salon::class);
    }

    /**
     * Customer Package
     */
    public function customerPackage(): BelongsTo
    {
        return $this->belongsTo(
            CustomerPackage::class,
            'customer_package_id'
        );
    }

    /**
     * Customer
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Package Service
     */
    public function packageService(): BelongsTo
    {
        return $this->belongsTo(
            PackageService::class,
            'package_service_id'
        );
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
     * Appointment
     */
    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    /**
     * Bill
     */
    public function bill(): BelongsTo
    {
        return $this->belongsTo(Bill::class);
    }
}