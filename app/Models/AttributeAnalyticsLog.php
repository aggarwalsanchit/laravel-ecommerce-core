<?php
// app/Models/AttributeAnalyticsLog.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttributeAnalyticsLog extends Model
{
    protected $fillable = [
        'attribute_id',
        'attribute_value_id',
        'product_id',
        'order_id',
        'user_id',
        'session_id',
        'event_type',
        'value',
        'quantity',
        'price_at_time',
        'discount_amount',
        'revenue',
        'page_url',
        'referrer',
        'ip_address',
        'user_agent',
        'metadata'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price_at_time' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'revenue' => 'decimal:2',
        'metadata' => 'array',
    ];

    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }

    public function attributeValue()
    {
        return $this->belongsTo(AttributeValue::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
