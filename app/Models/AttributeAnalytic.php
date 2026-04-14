<?php
// app/Models/AttributeAnalytic.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttributeAnalytic extends Model
{
    protected $table = 'attribute_analytics';

    protected $fillable = [
        'attribute_id', 'attribute_value_id', 'usage_count', 'view_count',
        'product_count', 'order_count', 'total_revenue', 'avg_price', 'date'
    ];

    protected $casts = [
        'usage_count' => 'integer',
        'view_count' => 'integer',
        'product_count' => 'integer',
        'order_count' => 'integer',
        'total_revenue' => 'decimal:2',
        'avg_price' => 'decimal:2',
        'date' => 'date'
    ];

    // ==================== RELATIONSHIPS ====================

    /**
     * Get the attribute
     */
    public function attribute(): BelongsTo
    {
        return $this->belongsTo(Attribute::class);
    }

    /**
     * Get the attribute value
     */
    public function attributeValue(): BelongsTo
    {
        return $this->belongsTo(AttributeValue::class, 'attribute_value_id');
    }

    // ==================== SCOPES ====================

    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    public function scopeToday($query)
    {
        return $query->where('date', today()->toDateString());
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('date', now()->month)
                     ->whereYear('date', now()->year);
    }

    // ==================== HELPER METHODS ====================

    /**
     * Increment analytics metrics
     */
    public static function incrementMetrics($attributeId, array $metrics, $attributeValueId = null)
    {
        $analytic = self::firstOrCreate([
            'attribute_id' => $attributeId,
            'attribute_value_id' => $attributeValueId,
            'date' => today()->toDateString()
        ]);
        
        foreach ($metrics as $metric => $value) {
            if (in_array($metric, ['usage_count', 'view_count', 'product_count', 'order_count'])) {
                $analytic->increment($metric, $value);
            } elseif (in_array($metric, ['total_revenue', 'avg_price'])) {
                $analytic->{$metric} += $value;
            }
        }
        
        $analytic->save();
        return $analytic;
    }
}