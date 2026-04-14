<?php
// app/Models/SizeAnalytic.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SizeAnalytic extends Model
{
    protected $table = 'size_analytics';

    protected $fillable = [
        'size_id',
        'view_count',
        'product_count',
        'order_count',
        'total_revenue',
        'avg_price',
        'date'
    ];

    protected $casts = [
        'view_count' => 'integer',
        'product_count' => 'integer',
        'order_count' => 'integer',
        'total_revenue' => 'decimal:2',
        'avg_price' => 'decimal:2',
        'date' => 'date'
    ];

    // ==================== RELATIONSHIPS ====================

    /**
     * Get the size for this analytic
     */
    public function size(): BelongsTo
    {
        return $this->belongsTo(Size::class);
    }

    // ==================== SCOPES ====================

    /**
     * Scope for date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    /**
     * Scope for today
     */
    public function scopeToday($query)
    {
        return $query->where('date', today()->toDateString());
    }

    /**
     * Scope for this week
     */
    public function scopeThisWeek($query)
    {
        return $query->whereBetween('date', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    /**
     * Scope for this month
     */
    public function scopeThisMonth($query)
    {
        return $query->whereMonth('date', now()->month)
            ->whereYear('date', now()->year);
    }

    /**
     * Scope for last N days
     */
    public function scopeLastDays($query, $days)
    {
        return $query->where('date', '>=', now()->subDays($days));
    }

    // ==================== HELPER METHODS ====================

    /**
     * Get total revenue for a size within date range
     */
    public static function getTotalRevenue($sizeId, $startDate = null, $endDate = null)
    {
        $query = self::where('size_id', $sizeId);

        if ($startDate && $endDate) {
            $query->whereBetween('date', [$startDate, $endDate]);
        }

        return $query->sum('total_revenue');
    }

    /**
     * Get average price for a size within date range
     */
    public static function getAveragePrice($sizeId, $startDate = null, $endDate = null)
    {
        $query = self::where('size_id', $sizeId);

        if ($startDate && $endDate) {
            $query->whereBetween('date', [$startDate, $endDate]);
        }

        return $query->avg('avg_price') ?? 0;
    }

    /**
     * Get top performing sizes
     */
    public static function getTopPerforming($limit = 10, $startDate = null, $endDate = null)
    {
        $query = self::select('size_id')
            ->selectRaw('SUM(total_revenue) as total_revenue')
            ->selectRaw('SUM(order_count) as total_orders')
            ->selectRaw('SUM(view_count) as total_views')
            ->groupBy('size_id');

        if ($startDate && $endDate) {
            $query->whereBetween('date', [$startDate, $endDate]);
        }

        return $query->orderBy('total_revenue', 'desc')
            ->limit($limit)
            ->with('size')
            ->get();
    }

    /**
     * Increment or update analytics
     */
    public static function incrementMetrics($sizeId, array $metrics)
    {
        $analytic = self::firstOrCreate([
            'size_id' => $sizeId,
            'date' => today()->toDateString()
        ]);

        foreach ($metrics as $metric => $value) {
            if (in_array($metric, ['view_count', 'product_count', 'order_count'])) {
                $analytic->increment($metric, $value);
            } elseif (in_array($metric, ['total_revenue', 'avg_price'])) {
                $analytic->{$metric} += $value;
            }
        }

        // Recalculate average price if needed
        if (isset($metrics['total_revenue']) && isset($metrics['order_count'])) {
            $analytic->avg_price = $analytic->total_revenue / max($analytic->order_count, 1);
        }

        $analytic->save();

        return $analytic;
    }
}
