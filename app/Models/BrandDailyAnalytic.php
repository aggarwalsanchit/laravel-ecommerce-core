<?php
// app/Models/BrandDailyAnalytic.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BrandDailyAnalytic extends Model
{
    protected $table = 'brand_daily_analytics';

    protected $fillable = [
        'brand_id', 'date',
        'product_count', 'view_count', 'order_count', 'total_revenue',
        'avg_rating', 'review_count'
    ];

    protected $casts = [
        'date' => 'date',
        'product_count' => 'integer',
        'view_count' => 'integer',
        'order_count' => 'integer',
        'total_revenue' => 'decimal:2',
        'avg_rating' => 'decimal:2',
        'review_count' => 'integer',
    ];

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }
}