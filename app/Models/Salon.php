<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Salon extends Model
{
    protected $fillable = [
        'name',
        'logo',
          'signature',
        'owner_id',

        'phone',
        'email',

        'address',
        'city',
        'state',
        'pincode',

        'gst_number',

        'opening_time',
        'closing_time',

        'currency',
        'tax_percentage',
        'invoice_prefix',
        'receipt_footer',
        'default_appointment_duration',

        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
        'tax_percentage' => 'decimal:2',
        'default_appointment_duration' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | Owner
    |--------------------------------------------------------------------------
    */

    public function owner(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'owner_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Bills
    |--------------------------------------------------------------------------
    */

    public function bills(): HasMany
    {
        return $this->hasMany(Bill::class);
    }

    /*
|--------------------------------------------------------------------------
| Branches
|--------------------------------------------------------------------------
*/

public function branches(): HasMany
{
    return $this->hasMany(Branch::class);
}
public function printerSetting()
{
    return $this->hasOne(\App\Models\PrinterSetting::class);
}
}