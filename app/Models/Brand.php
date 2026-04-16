<?php
// app/Models/Brand.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Str;

class Brand extends Model
{
    protected $table = 'brands';

    protected $fillable = [
        'name',
        'slug',
        'code',
        'description',
        'logo',
        'logo_alt',
        'banner',
        'banner_alt',
        'order',
        'status',
        'is_featured',
        'meta_title',
        'meta_description',
        'meta_keywords'
    ];

    protected $casts = [
        'status' => 'boolean',
        'is_featured' => 'boolean',
        'order' => 'integer'
    ];

    /**
     * Boot method to auto-generate slug
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($brand) {
            if (empty($brand->slug)) {
                $brand->slug = Str::slug($brand->name);
            }
            if (empty($brand->code)) {
                $brand->code = strtoupper(Str::slug($brand->name, ''));
            }
        });

        static::updating(function ($brand) {
            if ($brand->isDirty('name') && empty($brand->slug)) {
                $brand->slug = Str::slug($brand->name);
            }
        });
    }

    /**
     * Get the categories for this brand (many-to-many)
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'brand_category', 'brand_id', 'category_id')
            ->withTimestamps();
    }

    /**
     * Get the products for this brand
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get daily analytics for this brand
     */
    public function dailyAnalytics(): HasMany
    {
        return $this->hasMany(BrandDailyAnalytic::class);
    }

    /**
     * Get today's analytics
     */
    public function todayAnalytics()
    {
        return $this->dailyAnalytics()->where('date', today())->first();
    }

    /**
     * Get logo URL
     */
    public function getLogoUrlAttribute()
    {
        if ($this->logo && file_exists(storage_path('app/public/brands/' . $this->logo))) {
            return asset('storage/brands/' . $this->logo);
        }
        return null;
    }

    /**
     * Get banner URL
     */
    public function getBannerUrlAttribute()
    {
        if ($this->banner && file_exists(storage_path('app/public/brands/banners/' . $this->banner))) {
            return asset('storage/brands/banners/' . $this->banner);
        }
        return null;
    }

    /**
     * Get total views from analytics
     */
    public function getTotalViewsAttribute()
    {
        return $this->dailyAnalytics->sum('view_count');
    }

    /**
     * Get total orders from analytics
     */
    public function getTotalOrdersAttribute()
    {
        return $this->dailyAnalytics->sum('order_count');
    }

    /**
     * Get total revenue from analytics
     */
    public function getTotalRevenueAttribute()
    {
        return $this->dailyAnalytics->sum('total_revenue');
    }

    /**
     * Scope for active colors
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }
}
