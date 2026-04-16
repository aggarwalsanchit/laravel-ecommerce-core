<?php
// app/Models/ProductVariant.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id', 'color_id', 'size_id', 'sku', 'price', 'compare_price', 'wholesale_price',
        'stock_quantity', 'stock_status', 'image', 'image_alt', 'sort_order'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'compare_price' => 'decimal:2',
        'wholesale_price' => 'decimal:2',
        'stock_quantity' => 'integer',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class);
    }

    public function size(): BelongsTo
    {
        return $this->belongsTo(Size::class);
    }

    public function tierPrices()
    {
        return $this->hasMany(ProductTierPrice::class, 'variant_id');
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? asset('storage/variants/' . $this->image) : null;
    }
}