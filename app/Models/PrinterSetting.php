<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrinterSetting extends Model
{
    protected $fillable = [
        'salon_id',
        'printer_name',
        'default_print_method',
        'paper_size',
        'print_logo',
        'print_signature',
        'auto_cut',
    ];

    protected $casts = [
        'print_logo' => 'boolean',
        'print_signature' => 'boolean',
        'auto_cut' => 'boolean',
    ];

    public function salon(): BelongsTo
    {
        return $this->belongsTo(Salon::class);
    }
}