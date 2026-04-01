<?php
// app/Models/ProductAttributeValue.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductAttributeValue extends Model
{
    protected $table = 'product_attribute_values';

    protected $fillable = [
        'product_id',
        'attribute_value_id',
        'attribute_id',
        'additional_price',
        'additional_weight',
        'additional_stock',
        'custom_sku',
        'custom_barcode',
        'has_discount',
        'discount_percentage',
        'discounted_price',
        'view_count',
        'click_count',
        'order_count',
        'revenue_generated'
    ];

    protected $casts = [
        'has_discount' => 'boolean',
        'additional_price' => 'decimal:2',
        'additional_weight' => 'decimal:2',
        'additional_stock' => 'integer',
        'discount_percentage' => 'decimal:2',
        'discounted_price' => 'decimal:2',
        'revenue_generated' => 'decimal:2',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function attributeValue()
    {
        return $this->belongsTo(AttributeValue::class);
    }

    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }
}
