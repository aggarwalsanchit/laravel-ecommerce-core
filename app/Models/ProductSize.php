<?php
// app/Models/ProductSize.php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductSize extends Pivot
{
    protected $table = 'product_size';

    protected $fillable = [
        'size_id',
        'product_id',
        'vendor_id',
        'stock_quantity',
        'price_adjustment'
    ];

    protected $casts = [
        'stock_quantity' => 'integer',
        'price_adjustment' => 'decimal:2'
    ];

    /**
     * Get the size
     */
    public function size()
    {
        return $this->belongsTo(Size::class);
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

    /**
     * Get stock status badge
     */
    public function getStockStatusAttribute(): string
    {
        if ($this->stock_quantity <= 0) {
            return '<span class="badge bg-danger">Out of Stock</span>';
        } elseif ($this->stock_quantity < 10) {
            return '<span class="badge bg-warning">Low Stock (' . $this->stock_quantity . ')</span>';
        }
        return '<span class="badge bg-success">In Stock</span>';
    }
}
