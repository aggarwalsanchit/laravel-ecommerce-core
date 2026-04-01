<?php
// app/Models/ProductSize.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSize extends Model
{
    protected $table = 'product_sizes';
    
    protected $fillable = [
        'product_id', 'size_id', 'stock', 'price_adjustment'
    ];

    protected $casts = [
        'stock' => 'integer',
        'price_adjustment' => 'decimal:2',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function size()
    {
        return $this->belongsTo(Size::class);
    }

    public function getAdjustedPriceAttribute($basePrice)
    {
        return $basePrice + $this->price_adjustment;
    }
}