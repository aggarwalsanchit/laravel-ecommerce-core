<?php
// app/Models/ProductAnalyticsLog.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductAnalyticsLog extends Model
{
    protected $table = 'product_analytics_logs';
    
    protected $fillable = [
        'product_id', 'session_id', 'ip_address', 'event_type', 
        'quantity', 'price_at_time'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price_at_time' => 'decimal:2',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}