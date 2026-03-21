<?php
// app/Models/Color.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Color extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'hex_code',
        'slug',
        'description',
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

        static::creating(function ($color) {
            $color->slug = Str::slug($color->name);
        });

        static::updating(function ($color) {
            if ($color->isDirty('name')) {
                $color->slug = Str::slug($color->name);
            }
        });
    }

    // Products relationship
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_color');
    }

    // Increment view count
    public function incrementViewCount()
    {
        $this->increment('view_count');
    }

    // Get color preview HTML
    public function getColorPreviewAttribute()
    {
        return '<span class="color-preview" style="background-color: ' . $this->hex_code . '; width: 30px; height: 30px; display: inline-block; border-radius: 50%; border: 1px solid #ddd;"></span>';
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

    // Scope for active colors
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }
}
