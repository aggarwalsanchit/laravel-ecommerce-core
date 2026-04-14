<?php
// app/Models/ColorProduct.php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ColorProduct extends Pivot
{
    protected $table = 'color_product';

    protected $fillable = [
        'color_id',
        'product_id',
        'vendor_id',
        'color_image',
        'stock_quantity',
        'price_adjustment'
    ];

    protected $casts = [
        'stock_quantity' => 'integer',
        'price_adjustment' => 'decimal:2'
    ];

    /**
     * Get the color
     */
    public function color()
    {
        return $this->belongsTo(Color::class);
    }

    /**
     * Get the product
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the vendor
     */
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    /**
     * Get final price with adjustment
     */
    public function getFinalPriceAttribute($basePrice)
    {
        return $basePrice + $this->price_adjustment;
    }

    /**
     * Check if in stock
     */
    public function getInStockAttribute(): bool
    {
        return $this->stock_quantity > 0;
    }
}
