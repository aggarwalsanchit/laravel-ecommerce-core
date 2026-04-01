<?php
// app/Models/ProductVariant.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id', 'color_id', 'size_id', 'sku', 'price', 'sale_price', 
        'stock', 'image', 'custom_attributes', 'status'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'stock' => 'integer',
        'custom_attributes' => 'array',
        'status' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function color()
    {
        return $this->belongsTo(Color::class);
    }

    public function size()
    {
        return $this->belongsTo(Size::class);
    }

    public function getCurrentPriceAttribute()
    {
        return $this->sale_price ?: $this->price;
    }

    public function getImageUrlAttribute()
    {
        return $this->image ? Storage::disk('public')->url('products/variants/' . $this->image) : null;
    }

    public function isInStock()
    {
        return $this->stock > 0;
    }
}