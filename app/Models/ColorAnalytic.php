<?php
// app/Models/ColorAnalytic.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ColorAnalytic extends Model
{
    protected $table = 'color_analytics';

    protected $fillable = [
        'color_id',
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
     * Get the color for this analytic
     */
    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class);
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
     * Get total revenue for a color within date range
     */
    public static function getTotalRevenue($colorId, $startDate = null, $endDate = null)
    {
        $query = self::where('color_id', $colorId);

        if ($startDate && $endDate) {
            $query->whereBetween('date', [$startDate, $endDate]);
        }

        return $query->sum('total_revenue');
    }

    /**
     * Get average price for a color within date range
     */
    public static function getAveragePrice($colorId, $startDate = null, $endDate = null)
    {
        $query = self::where('color_id', $colorId);

        if ($startDate && $endDate) {
            $query->whereBetween('date', [$startDate, $endDate]);
        }

        return $query->avg('avg_price') ?? 0;
    }

    /**
     * Get top performing colors
     */
    public static function getTopPerforming($limit = 10, $startDate = null, $endDate = null)
    {
        $query = self::select('color_id')
            ->selectRaw('SUM(total_revenue) as total_revenue')
            ->selectRaw('SUM(order_count) as total_orders')
            ->selectRaw('SUM(view_count) as total_views')
            ->groupBy('color_id');

        if ($startDate && $endDate) {
            $query->whereBetween('date', [$startDate, $endDate]);
        }

        return $query->orderBy('total_revenue', 'desc')
            ->limit($limit)
            ->with('color')
            ->get();
    }

    /**
     * Increment or update analytics
     */
    public static function incrementMetrics($colorId, array $metrics)
    {
        $analytic = self::firstOrCreate([
            'color_id' => $colorId,
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
