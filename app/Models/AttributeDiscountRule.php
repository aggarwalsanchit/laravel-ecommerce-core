<?php
// app/Models/AttributeDiscountRule.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttributeDiscountRule extends Model
{
    protected $fillable = [
        'attribute_id',
        'attribute_value_id',
        'discount_id',
        'rule_type',
        'discount_value',
        'buy_quantity',
        'get_quantity',
        'requires_min_quantity',
        'min_quantity',
        'requires_min_purchase',
        'min_purchase_amount',
        'stackable',
        'exclusive',
        'start_date',
        'end_date',
        'usage_limit',
        'used_count',
        'status'
    ];

    protected $casts = [
        'requires_min_quantity' => 'boolean',
        'requires_min_purchase' => 'boolean',
        'stackable' => 'boolean',
        'exclusive' => 'boolean',
        'status' => 'boolean',
        'discount_value' => 'decimal:2',
        'min_purchase_amount' => 'decimal:2',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }

    public function attributeValue()
    {
        return $this->belongsTo(AttributeValue::class);
    }

    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }

    public function isActive()
    {
        if (!$this->status) return false;

        $now = now();
        if ($this->start_date && $now < $this->start_date) return false;
        if ($this->end_date && $now > $this->end_date) return false;
        if ($this->usage_limit && $this->used_count >= $this->usage_limit) return false;

        return true;
    }
}
