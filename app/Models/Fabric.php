<?php
// app/Models/Fabric.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Fabric extends Model
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

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($fabric) {
            $fabric->slug = Str::slug($fabric->name);
        });

        static::updating(function ($fabric) {
            if ($fabric->isDirty('name')) {
                $fabric->slug = Str::slug($fabric->name);
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

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }
}
