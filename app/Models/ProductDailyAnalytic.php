<?php
// app/Models/ProductDailyAnalytic.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductDailyAnalytic extends Model
{
    protected $table = 'product_daily_analytics';

    protected $fillable = [
        'product_id', 'date',
        'views', 'cart_adds', 'cart_removes', 'orders', 'quantity_sold',
        'revenue', 'avg_price_sold',
        'wishlist_adds', 'wishlist_removes', 'share_count', 'click_count',
        'new_ratings', 'avg_rating_daily'
    ];

    protected $casts = [
        'date' => 'date',
        'views' => 'integer',
        'cart_adds' => 'integer',
        'cart_removes' => 'integer',
        'orders' => 'integer',
        'quantity_sold' => 'integer',
        'revenue' => 'decimal:2',
        'avg_price_sold' => 'decimal:2',
        'wishlist_adds' => 'integer',
        'wishlist_removes' => 'integer',
        'share_count' => 'integer',
        'click_count' => 'integer',
        'new_ratings' => 'integer',
        'avg_rating_daily' => 'decimal:2',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}