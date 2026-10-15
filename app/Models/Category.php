<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
        'description',
        'image',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    /**
     * Category has many services.
     *
     * Current services table uses category name
     * instead of category_id.
     */
    public function services()
    {
        return $this->hasMany(Service::class, 'category', 'name');
    }
}

