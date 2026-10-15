<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MembershipPlan extends Model
{
    protected $fillable = [
        'salon_id',
        'name',
        'code',
        'description',
        'billing_cycle',
        'price',
        'validity_days',
        'discount_type',
        'discount_value',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discount_value' => 'decimal:2',
        'validity_days' => 'integer',
        'status' => 'boolean',
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

    public function benefits(): HasMany
    {
        return $this->hasMany(
            MembershipBenefit::class,
            'membership_plan_id'
        );
    }

    public function customerMemberships(): HasMany
    {
        return $this->hasMany(
            CustomerMembership::class,
            'membership_plan_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | DISCOUNT AMOUNT
    |--------------------------------------------------------------------------
    */

    public function getDiscountAmountAttribute(): float
    {
        $price = (float) $this->price;
        $discountValue = (float) $this->discount_value;

        // Percentage discount
        if ($this->discount_type === 'percentage') {

            return round(
                ($price * $discountValue) / 100,
                2
            );
        }

        // Fixed discount
        if ($this->discount_type === 'fixed') {

            return min(
                $discountValue,
                $price
            );
        }

        // No discount
        return 0;
    }

    /*
    |--------------------------------------------------------------------------
    | FINAL AMOUNT
    |--------------------------------------------------------------------------
    */

    public function getFinalAmountAttribute(): float
    {
        return max(
            0,
            round(
                (float) $this->price - $this->discount_amount,
                2
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | EXPIRY DATE
    |--------------------------------------------------------------------------
    | Expiry = Created Date + Validity Days
    |--------------------------------------------------------------------------
    */

    public function getExpiryDateAttribute(): Carbon
    {
        if (!$this->created_at) {
            return now()->addDays(
                (int) $this->validity_days
            );
        }

        return $this->created_at
            ->copy()
            ->addDays((int) $this->validity_days);
    }

    /*
    |--------------------------------------------------------------------------
    | CHECK EXPIRED
    |--------------------------------------------------------------------------
    | Expiry date itself is considered ACTIVE.
    |
    | Example:
    | Today     = 24 Sep
    | Expiry    = 24 Sep → Active
    |
    | Today     = 25 Sep
    | Expiry    = 24 Sep → Inactive
    |--------------------------------------------------------------------------
    */

   public function getIsExpiredAttribute(): bool
{
    return today()->greaterThan(
        $this->expiry_date->toDateString()
    );
}

public function getIsActiveAttribute(): bool
{
    return !$this->is_expired;
}
}

