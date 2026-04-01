<?php
// app/Models/AttributeValue.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class AttributeValue extends Model
{
    protected $fillable = [
        'attribute_id',
        'value',
        'slug',
        'description',
        'short_description',
        'color_code',
        'color_name',
        'size_value',
        'size_unit',
        'image',
        'thumbnail',
        'icon',
        'price_adjustment',
        'weight_adjustment',
        'stock',
        'sku',
        'barcode',
        'min_value',
        'max_value',
        'display_order',
        'is_default',
        'is_visible',
        'discount_applicable',
        'max_discount_percentage',
        'view_count',
        'click_count',
        'order_count',
        'total_revenue',
        'avg_rating',
        'review_count',
        'usage_count',
        'meta_title',
        'meta_description',
        'meta_keywords'
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'is_visible' => 'boolean',
        'discount_applicable' => 'boolean',
        'price_adjustment' => 'decimal:2',
        'weight_adjustment' => 'decimal:2',
        'total_revenue' => 'decimal:2',
        'avg_rating' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($value) {
            $value->slug = Str::slug($value->value);
        });
    }

    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_attribute_values', 'attribute_value_id', 'product_id')
            ->withPivot('additional_price', 'additional_stock', 'custom_sku', 'has_discount')
            ->withTimestamps();
    }

    public function productValues()
    {
        return $this->hasMany(ProductAttributeValue::class);
    }

    public function discountRules()
    {
        return $this->hasMany(AttributeDiscountRule::class);
    }

    public function analyticsLogs()
    {
        return $this->hasMany(AttributeAnalyticsLog::class);
    }

    public function scopeActive($query)
    {
        return $query->whereHas('attribute', function ($q) {
            $q->where('status', true);
        })->where('is_visible', true);
    }

    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }
}
