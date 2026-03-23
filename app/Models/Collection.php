<?php
// app/Models/Collection.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Collection extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'code',
        'description',
        'image',
        'banner',
        'icon',
        'start_date',
        'end_date',
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
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($collection) {
            $collection->slug = Str::slug($collection->name);
        });

        static::updating(function ($collection) {
            if ($collection->isDirty('name')) {
                $collection->slug = Str::slug($collection->name);
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

    public function getDateRangeAttribute()
    {
        $start = $this->start_date ? $this->start_date->format('M d, Y') : 'No start date';
        $end = $this->end_date ? $this->end_date->format('M d, Y') : 'No end date';
        return $start . ' - ' . $end;
    }

    public function isActive()
    {
        if (!$this->status) return false;

        $now = now();

        if ($this->start_date && $now < $this->start_date) return false;
        if ($this->end_date && $now > $this->end_date) return false;

        return true;
    }

    public function scopeActive($query)
    {
        return $query->where('status', true)
            ->where(function ($q) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', now());
            });
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}
