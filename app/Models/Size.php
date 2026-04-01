<?php
// app/Models/Size.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Size extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'slug',
        'description',
        'image',
        'order',
        'status',
        'product_count',
        'view_count',
        'order_count',
        'total_revenue',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'status' => 'boolean',
        'product_count' => 'integer',
        'view_count' => 'integer',
        'order_count' => 'integer',
        'total_revenue' => 'decimal:2',
    ];

    // Boot method to auto-generate slug
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($size) {
            $size->slug = Str::slug($size->name);
        });

        static::updating(function ($size) {
            if ($size->isDirty('name')) {
                $size->slug = Str::slug($size->name);
            }
        });
    }

    // Products relationship
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_size')->withPivot('stock', 'price_adjustment')
                    ->withTimestamps();
    }

    // Variants using this size
    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    // Increment view count
    public function incrementViewCount()
    {
        $this->increment('view_count');
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

    // Scope for active sizes
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }
}
