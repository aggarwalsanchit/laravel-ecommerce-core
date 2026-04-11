<?php
// app/Models/CategoryAnalytic.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CategoryAnalytic extends Model
{
    protected $table = 'category_analytics';

    protected $fillable = [
        'category_id',
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
     * Get the category for this analytic
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
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
     * Get total revenue for a category within date range
     */
    public static function getTotalRevenue($categoryId, $startDate = null, $endDate = null)
    {
        $query = self::where('category_id', $categoryId);

        if ($startDate && $endDate) {
            $query->whereBetween('date', [$startDate, $endDate]);
        }

        return $query->sum('total_revenue');
    }

    /**
     * Get average price for a category within date range
     */
    public static function getAveragePrice($categoryId, $startDate = null, $endDate = null)
    {
        $query = self::where('category_id', $categoryId);

        if ($startDate && $endDate) {
            $query->whereBetween('date', [$startDate, $endDate]);
        }

        return $query->avg('avg_price') ?? 0;
    }

    /**
     * Get top performing categories
     */
    public static function getTopPerforming($limit = 10, $startDate = null, $endDate = null)
    {
        $query = self::select('category_id')
            ->selectRaw('SUM(total_revenue) as total_revenue')
            ->selectRaw('SUM(order_count) as total_orders')
            ->selectRaw('SUM(view_count) as total_views')
            ->groupBy('category_id');

        if ($startDate && $endDate) {
            $query->whereBetween('date', [$startDate, $endDate]);
        }

        return $query->orderBy('total_revenue', 'desc')
            ->limit($limit)
            ->with('category')
            ->get();
    }

    /**
     * Increment or update analytics
     */
    public static function incrementMetrics($categoryId, array $metrics)
    {
        $analytic = self::firstOrCreate([
            'category_id' => $categoryId,
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

        // Also update main category table
        $category = Category::find($categoryId);
        if ($category) {
            if (isset($metrics['view_count'])) {
                $category->increment('view_count', $metrics['view_count']);
            }
            if (isset($metrics['product_count'])) {
                $category->update(['product_count' => $category->products()->count()]);
            }
        }

        return $analytic;
    }
}
