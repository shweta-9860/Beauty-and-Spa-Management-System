<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CustomerPackage extends Model
{
    protected $fillable = [
        'salon_id',
        'customer_id',
        'package_id',
        'purchase_number',
        'price',
        'discount',
        'paid_amount',
        'total_uses',
        'used_uses',
        'remaining_uses',
        'purchase_date',
        'expiry_date',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discount' => 'decimal:2',
        'paid_amount' => 'decimal:2',

        'total_uses' => 'integer',
        'used_uses' => 'integer',
        'remaining_uses' => 'integer',

        'purchase_date' => 'date',
        'expiry_date' => 'date',
    ];

    /**
     * Salon
     */
    public function salon(): BelongsTo
    {
        return $this->belongsTo(Salon::class);
    }

    /**
     * Customer
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Package
     */
    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    /**
     * Usage history
     */
    public function usages(): HasMany
    {
        return $this->hasMany(
            PackageUsage::class,
            'customer_package_id'
        );
    }
    

}