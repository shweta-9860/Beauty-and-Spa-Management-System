<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $fillable = [
        'salon_id',
        'branch_id',
        'name',
        'email',
        'phone',
        'gender',
        'date_of_birth',
        'address',
        'notes',
        'status',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'status' => 'boolean',
    ];

    /**
     * Customer belongs to Salon
     */
    public function salon(): BelongsTo
    {
        return $this->belongsTo(Salon::class);
    }

    /**
     * Customer belongs to Branch
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Customer appointments
     */
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Customer bills
     */
    public function bills(): HasMany
    {
        return $this->hasMany(Bill::class);
    }
    /**
 * Customer purchased packages
 */
public function customerPackages()
{
    return $this->hasMany(
        CustomerPackage::class,
        'customer_id'
    );
}

/**
 * Package usage history
 */
public function packageUsages()
{
    return $this->hasMany(
        PackageUsage::class,
        'customer_id'
    );
}
public function memberships(): HasMany
{
    return $this->hasMany(
        CustomerMembership::class,
        'customer_id'
    );
}
}