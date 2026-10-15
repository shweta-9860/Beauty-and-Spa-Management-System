<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'salon_id',
        'category_id',
        'supplier_id',
        'name',
        'sku',
        'brand',
        'purchase_price',
        'selling_price',
        'stock',
        'minimum_stock',
        'unit',
        'expiry_date',
        'status',
    ];

    protected $casts = [
        'purchase_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'stock' => 'decimal:2',
        'minimum_stock' => 'decimal:2',
        'expiry_date' => 'date',
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

    public function category(): BelongsTo
    {
        return $this->belongsTo(
            ProductCategory::class,
            'category_id'
        );
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function stockHistories(): HasMany
    {
        return $this->hasMany(StockHistory::class);
    }

    /*
    |--------------------------------------------------------------------------
    | STOCK STATUS
    |--------------------------------------------------------------------------
    | Stock 0 or below = Inactive
    | Stock greater than 0 = Active
    |--------------------------------------------------------------------------
    */

    public function getIsActiveAttribute(): bool
    {
        return (float) $this->stock > 0;
    }

    /*
    |--------------------------------------------------------------------------
    | STOCK STATUS TEXT
    |--------------------------------------------------------------------------
    */

    public function getStockStatusAttribute(): string
    {
        return $this->is_active
            ? 'Active'
            : 'Inactive';
    }
}

