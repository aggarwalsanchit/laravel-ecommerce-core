<?php
// app/Models/Discount.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Discount extends Model
{
    protected $fillable = [
        'name',
        'code',
        'description',
        'discount_type',
        'discount_value',
        'buy_quantity',
        'get_quantity',
        'free_shipping_only',
        'target_type',
        'target_ids',
        'min_purchase_amount',
        'max_usage_per_user',
        'total_usage_limit',
        'used_count',
        'start_date',
        'end_date',
        'status',
        'is_featured',
        'stackable',
        'user_groups',
    ];

    protected $casts = [
        'target_ids' => 'array',
        'user_groups' => 'array',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'status' => 'boolean',
        'is_featured' => 'boolean',
        'stackable' => 'boolean',
        'discount_value' => 'decimal:2',
        'min_purchase_amount' => 'decimal:2',
    ];

    // Check if discount is active
    public function isActive()
    {
        if (!$this->status) return false;

        $now = Carbon::now();

        if ($this->start_date && $now < $this->start_date) return false;
        if ($this->end_date && $now > $this->end_date) return false;

        if ($this->total_usage_limit && $this->used_count >= $this->total_usage_limit) return false;

        return true;
    }

    // Get formatted discount value
    public function getFormattedDiscountAttribute()
    {
        if ($this->discount_type === 'percentage') {
            return $this->discount_value . '% OFF';
        } elseif ($this->discount_type === 'fixed_amount') {
            return '$' . number_format($this->discount_value, 2) . ' OFF';
        } elseif ($this->discount_type === 'buy_x_get_y') {
            return "Buy {$this->buy_quantity} Get {$this->get_quantity} Free";
        } elseif ($this->discount_type === 'free_shipping') {
            return "Free Shipping";
        }
        return '';
    }

    // Get target display
    public function getTargetDisplayAttribute()
    {
        $targets = [
            'all_products' => 'All Products',
            'categories' => 'Categories',
            'subcategories' => 'Subcategories',
            'products' => 'Specific Products',
            'colors' => 'Colors',
            'sizes' => 'Sizes',
            'fabrics' => 'Fabrics',
            'occasions' => 'Occasions',
            'collections' => 'Collections',
            'brands' => 'Brands',
            'seasons' => 'Seasons',
            'user_groups' => 'User Groups',
            'min_purchase' => 'Minimum Purchase',
            'first_purchase' => 'First Purchase',
            'holiday_special' => 'Holiday Special',
            'clearance' => 'Clearance'
        ];

        return $targets[$this->target_type] ?? ucfirst($this->target_type);
    }

    // Scope for active discounts
    public function scopeActive($query)
    {
        $now = Carbon::now();

        return $query->where('status', true)
            ->where(function ($q) use ($now) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', $now);
            })
            ->where(function ($q) use ($now) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', $now);
            })
            ->where(function ($q) {
                $q->whereNull('total_usage_limit')->orWhereRaw('used_count < total_usage_limit');
            });
    }

    // Scope for featured discounts
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}
