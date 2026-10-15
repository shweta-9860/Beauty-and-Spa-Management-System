<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerMembership extends Model
{
    protected $fillable = [
        'salon_id',
         'branch_id',
        'customer_id',
        'membership_plan_id',
        'start_date',
        'expiry_date',
        'price',
        'discount',
        'paid_amount',
        'status',
        'renewal_date',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'expiry_date' => 'date',
        'renewal_date' => 'date',
        'price' => 'decimal:2',
        'discount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
    ];

    public function salon(): BelongsTo
    {
        return $this->belongsTo(Salon::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function membershipPlan(): BelongsTo
    {
        return $this->belongsTo(
            MembershipPlan::class,
            'membership_plan_id'
        );
    }
    public function branch()
{
    return $this->belongsTo(\App\Models\Branch::class);
}
}

