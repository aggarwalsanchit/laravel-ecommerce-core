<?php
// app/Models/CommissionLog.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommissionLog extends Model
{
    protected $fillable = [
        'order_id',
        'vendor_id',
        'order_amount',
        'commission_percentage',
        'commission_amount',
        'vendor_earning',
        'vendor_type',
        'is_paid',
        'paid_at'
    ];

    protected $casts = [
        'order_amount' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'vendor_earning' => 'decimal:2',
        'is_paid' => 'boolean',
        'paid_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}
