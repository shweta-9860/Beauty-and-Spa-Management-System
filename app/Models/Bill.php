<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bill extends Model
{
    protected $fillable = [
        'salon_id',
        'branch_id',
        'customer_id',
        'appointment_id',

        'invoice_number',
        'bill_date',

        'service_total',
'discount',

'coupon_id',
'coupon_code',
'coupon_discount',

'tax_percentage',
        'tax_amount',
        'product_amount',
        'grand_total',

        'payment_method',
        'payment_status',

        'paid_amount',
        'pending_amount',

        'transaction_id',

        'status',
        'notes',
    ];

    protected $casts = [
        'bill_date' => 'date',

        'service_total' => 'decimal:2',
        'discount' => 'decimal:2',
        'coupon_discount' => 'decimal:2',
        'tax_percentage' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'product_amount' => 'decimal:2',
        'grand_total' => 'decimal:2',

        'paid_amount' => 'decimal:2',
        'pending_amount' => 'decimal:2',
        
    ];

    /*
    |--------------------------------------------------------------------------
    | Salon
    |--------------------------------------------------------------------------
    */

    public function salon(): BelongsTo
    {
        return $this->belongsTo(Salon::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Branch
    |--------------------------------------------------------------------------
    */

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Customer
    |--------------------------------------------------------------------------
    */

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Appointment
    |--------------------------------------------------------------------------
    */

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Bill Items
    |--------------------------------------------------------------------------
    */

    public function items(): HasMany
    {
        return $this->hasMany(BillItem::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Payments
    |--------------------------------------------------------------------------
    */

    public function payments(): HasMany
    {
        return $this->hasMany(BillPayment::class);
    }
}
