<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Wastage extends Model
{
    protected $fillable = [
        'salon_id',
        'branch_id',
        'product_id',
        'staff_id',
        'wastage_number',
        'wastage_date',
        'quantity',
        'unit_cost',
        'total_cost',
        'reason',
        'notes',
        'status',
    ];

    protected $casts = [
        'wastage_date' => 'date',
        'quantity' => 'decimal:2',
        'unit_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
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

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }
}