<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Coupon extends Model
{
    protected $fillable = [
        'salon_id',
        'branch_id',
        'code',
        'description',
        'discount_type',
        'discount_value',
        'minimum_bill_amount',
        'maximum_discount',
        'valid_from',
        'valid_until',
        'usage_limit',
        'used_count',
        'per_customer_limit',
        'status',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'minimum_bill_amount' => 'decimal:2',
        'maximum_discount' => 'decimal:2',
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
    ];

    public function salon(): BelongsTo
    {
        return $this->belongsTo(Salon::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Check whether coupon is currently valid.
     */
    public function isValid(): bool
    {
        if ($this->status !== 'active') {
            return false;
        }

        $now = now();

        if ($this->valid_from && $now->lt($this->valid_from)) {
            return false;
        }

        if ($this->valid_until && $now->gt($this->valid_until)) {
            return false;
        }

        if (
            $this->usage_limit !== null &&
            $this->used_count >= $this->usage_limit
        ) {
            return false;
        }

        return true;
    }

    /**
     * Calculate coupon discount.
     */
    public function calculateDiscount(float $amount): float
    {
        if (!$this->isValid()) {
            return 0;
        }

        if ($amount < (float) $this->minimum_bill_amount) {
            return 0;
        }

        if ($this->discount_type === 'percentage') {
            $discount = ($amount * (float) $this->discount_value) / 100;
        } else {
            $discount = (float) $this->discount_value;
        }

        if ($this->maximum_discount !== null) {
            $discount = min(
                $discount,
                (float) $this->maximum_discount
            );
        }

        return min($discount, $amount);
 
        }
        public function usages()
{
    return $this->hasMany(CouponUsage::class);
}
}