<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BillItem extends Model
{
    protected $fillable = [
        'bill_id',
        'service_id',
        'service_variation_id',
        'staff_id',

        'item_name',
        'quantity',
        'unit_price',
        'discount',
        'tax_amount',
        'total_amount',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | Bill
    |--------------------------------------------------------------------------
    */

    public function bill(): BelongsTo
    {
        return $this->belongsTo(Bill::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Service
    |--------------------------------------------------------------------------
    */

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Service Variation
    |--------------------------------------------------------------------------
    */

    public function serviceVariation(): BelongsTo
{
    return $this->belongsTo(
        ServiceVariation::class,
        'service_variation_id'
    );
}

    /*
    |--------------------------------------------------------------------------
    | Staff
    |--------------------------------------------------------------------------
    */

    public function staff(): BelongsTo
    {
        return $this->belongsTo(
            Staff::class,
            'staff_id'
        );
    }
}