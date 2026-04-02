<?php
// app/Models/ProductCustomAttribute.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCustomAttribute extends Model
{
    protected $table = 'product_custom_attributes';
    
    protected $fillable = [
        'product_id', 'attribute_value_id'
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
        return $this->hasOneThrough(Attribute::class, AttributeValue::class, 'id', 'id', 'attribute_value_id', 'attribute_id');
    }
}