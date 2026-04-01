<?php
// app/Models/ProductTierPrice.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductTierPrice extends Model
{
    protected $fillable = [
        'product_id', 'min_quantity', 'max_quantity', 'price'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'min_quantity' => 'integer',
        'max_quantity' => 'integer',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getFormattedPriceAttribute()
    {
        return '$' . number_format($this->price, 2);
    }

    public function getQuantityRangeAttribute()
    {
        $range = $this->min_quantity;
        if ($this->max_quantity) {
            $range .= ' - ' . $this->max_quantity;
        } else {
            $range .= '+';
        }
        return $range;
    }
}