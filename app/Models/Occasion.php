<?php
// app/Models/Occasion.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Occasion extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'code',
        'description',
        'image',
        'icon',
        'order',
        'status',
        'is_featured',
        'product_count',
        'view_count',
        'order_count',
        'total_revenue',
        'avg_rating',
        'review_count',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    protected $casts = [
        'status' => 'boolean',
        'is_featured' => 'boolean',
        'product_count' => 'integer',
        'view_count' => 'integer',
        'order_count' => 'integer',
        'total_revenue' => 'decimal:2',
        'avg_rating' => 'decimal:2',
        'review_count' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($occasion) {
            $occasion->slug = Str::slug($occasion->name);
        });

        static::updating(function ($occasion) {
            if ($occasion->isDirty('name')) {
                $occasion->slug = Str::slug($occasion->name);
            }
        });
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function incrementViewCount()
    {
        $this->increment('view_count');
    }

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

    public function getFormattedRevenueAttribute()
    {
        return '$' . number_format($this->total_revenue, 2);
    }

    public function getFormattedRatingAttribute()
    {
        if ($this->avg_rating > 0) {
            return number_format($this->avg_rating, 1) . ' ★';
        }
        return 'No ratings';
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}
