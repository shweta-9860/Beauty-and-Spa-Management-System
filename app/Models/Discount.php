<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Discount extends Model
{
    protected $fillable = [
        'salon_id',
        'branch_id',
        'name',
        'description',
        'discount_type',
        'discount_value',
        'applies_to',
        'start_date',
        'end_date',
        'minimum_bill_amount',
        'maximum_discount',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'discount_value' => 'decimal:2',
        'minimum_bill_amount' => 'decimal:2',
        'maximum_discount' => 'decimal:2',
        'status' => 'string',
    ];


    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    public function salon(): BelongsTo
    {
        return $this->belongsTo(Salon::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }


    /*
    |--------------------------------------------------------------------------
    | EXPIRY CHECK
    |--------------------------------------------------------------------------
    */

    /**
     * Check whether discount has expired.
     *
     * Discount becomes inactive only after end date has passed.
     */
    public function getIsExpiredAttribute(): bool
    {
        if (!$this->end_date) {
            return false;
        }

        return today()->gt($this->end_date);
    }


    /**
     * Check whether discount is currently active.
     *
     * Rules:
     * 1. Database status must be active.
     * 2. End date must not be expired.
     *
     * Future start date does NOT make it inactive.
     */
    public function getIsActiveAttribute(): bool
    {
        if ($this->status !== 'active') {
            return false;
        }

        if ($this->is_expired) {
            return false;
        }

        return true;
    }


    /**
     * Get display status.
     */
    public function getDisplayStatusAttribute(): string
    {
        return $this->is_active
            ? 'Active'
            : 'Inactive';
    }


    /*
    |--------------------------------------------------------------------------
    | VALIDITY
    |--------------------------------------------------------------------------
    */

    /**
     * Check whether discount is currently valid.
     */
    public function isValid(): bool
    {
        return $this->is_active;
    }


    /*
    |--------------------------------------------------------------------------
    | DISCOUNT CALCULATION
    |--------------------------------------------------------------------------
    */

    /**
     * Calculate discount amount.
     */
    public function calculateDiscount(float $amount): float
    {
        /*
        |--------------------------------------------------------------------------
        | Do not apply expired/inactive discount
        |--------------------------------------------------------------------------
        */

        if (!$this->isValid()) {
            return 0;
        }


        /*
        |--------------------------------------------------------------------------
        | Minimum Bill Amount
        |--------------------------------------------------------------------------
        */

        if ($amount < (float) $this->minimum_bill_amount) {
            return 0;
        }


        /*
        |--------------------------------------------------------------------------
        | Percentage / Fixed Discount
        |--------------------------------------------------------------------------
        */

        if ($this->discount_type === 'percentage') {

            $discount = (
                $amount *
                (float) $this->discount_value
            ) / 100;

        } else {

            $discount = (float) $this->discount_value;
        }


        /*
        |--------------------------------------------------------------------------
        | Maximum Discount Limit
        |--------------------------------------------------------------------------
        */

        if ($this->maximum_discount !== null) {

            $discount = min(
                $discount,
                (float) $this->maximum_discount
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Discount Cannot Be Greater Than Bill Amount
        |--------------------------------------------------------------------------
        */

        return min($discount, $amount);
    }
}
