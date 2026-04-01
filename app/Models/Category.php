<?php
// app/Models/Category.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'short_description',
        'parent_id',
        'image',
        'thumbnail_image',
        'banner_image',
        'icon',
        'order',
        'status',
        'show_in_menu',
        'is_featured',
        'is_popular',
        'is_trending',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_image',
        'canonical_url',
        'focus_keyword',
        'schema_markup',
        'view_count',
        'product_count',
        'order_count',
        'total_revenue',
        'avg_price',
        'last_viewed_at',
        'last_updated_at'
    ];

    protected $casts = [
        'status' => 'boolean',
        'show_in_menu' => 'boolean',
        'is_featured' => 'boolean',
        'is_popular' => 'boolean',
        'is_trending' => 'boolean',
        'view_count' => 'integer',
        'product_count' => 'integer',
        'order_count' => 'integer',
        'total_revenue' => 'decimal:2',
        'avg_price' => 'decimal:2',
        'last_viewed_at' => 'datetime',
        'last_updated_at' => 'datetime',
    ];

    // Parent category relationship
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // Child categories relationship
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('order');
    }

    // Recursive children (all descendants)
    public function allChildren()
    {
        return $this->children()->with('allChildren');
    }

    // Products relationship
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    // Orders through products
    public function orders()
    {
        return $this->hasManyThrough(Order::class, Product::class);
    }

    // Get full path (breadcrumb)
    public function getPathAttribute()
    {
        $path = [];
        $category = $this;

        while ($category) {
            array_unshift($path, $category->name);
            $category = $category->parent;
        }

        return implode(' > ', $path);
    }

    // Get depth level
    public function getDepthAttribute()
    {
        $depth = 0;
        $category = $this;

        while ($category->parent) {
            $depth++;
            $category = $category->parent;
        }

        return $depth;
    }

    // Get all ancestors
    public function ancestors()
    {
        $ancestors = [];
        $category = $this;

        while ($category->parent) {
            $ancestors[] = $category->parent;
            $category = $category->parent;
        }

        return collect($ancestors);
    }

    // Increment view count
    public function incrementViewCount()
    {
        $this->increment('view_count');
        $this->update(['last_viewed_at' => now()]);
    }

    // Update product statistics
    public function updateProductStats()
    {
        $productCount = $this->products()->count();
        $totalRevenue = $this->products()->sum('total_sold_value');
        $avgPrice = $this->products()->avg('price') ?? 0;
        $orderCount = $this->products()->sum('order_count');

        $this->update([
            'product_count' => $productCount,
            'total_revenue' => $totalRevenue,
            'avg_price' => $avgPrice,
            'order_count' => $orderCount,
            'last_updated_at' => now()
        ]);
    }

    // Check if category has children
    public function hasChildren()
    {
        return $this->children()->count() > 0;
    }

    // Get formatted view count
    public function getFormattedViewCountAttribute()
    {
        if ($this->view_count >= 1000000) {
            return round($this->view_count / 1000000, 1) . 'M';
        }
        if ($this->view_count >= 1000) {
            return round($this->view_count / 1000, 1) . 'K';
        }
        return $this->view_count;
    }

    // Get formatted revenue
    public function getFormattedRevenueAttribute()
    {
        return '$' . number_format($this->total_revenue, 2);
    }

    // Get popularity score (0-100)
    public function getPopularityScoreAttribute()
    {
        $maxViews = Category::max('view_count') ?: 1;
        $maxOrders = Category::max('order_count') ?: 1;

        $viewScore = ($this->view_count / $maxViews) * 50;
        $orderScore = ($this->order_count / $maxOrders) * 50;

        return round($viewScore + $orderScore, 2);
    }

    // Get SEO score (0-100)
    public function getSeoScoreAttribute()
    {
        $score = 0;

        if ($this->meta_title) $score += 15;
        if ($this->meta_description) $score += 15;
        if ($this->meta_keywords) $score += 10;
        if ($this->focus_keyword) $score += 15;
        if ($this->canonical_url) $score += 10;
        if ($this->og_image) $score += 10;
        if (strlen($this->description) > 100) $score += 15;
        if ($this->slug && !preg_match('/[^a-z0-9-]/', $this->slug)) $score += 10;

        return $score;
    }

    // Get SEO status
    public function getSeoStatusAttribute()
    {
        $score = $this->seo_score;

        if ($score >= 80) return ['badge' => 'success', 'text' => 'Excellent'];
        if ($score >= 60) return ['badge' => 'primary', 'text' => 'Good'];
        if ($score >= 40) return ['badge' => 'warning', 'text' => 'Needs Improvement'];
        return ['badge' => 'danger', 'text' => 'Poor'];
    }

    // Boot method
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            $category->slug = Str::slug($category->name);
        });

        static::updating(function ($category) {
            if ($category->isDirty('name')) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeInMenu($query)
    {
        return $query->where('show_in_menu', true)->where('status', true);
    }

    public function scopeParents($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopePopular($query)
    {
        return $query->where('is_popular', true);
    }

    public function scopeTrending($query)
    {
        return $query->where('is_trending', true);
    }
}
