<?php
// app/Models/Attribute.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Attribute extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'code',
        'description',
        'type',
        'unit',
        'display_order',
        'is_required',
        'is_filterable',
        'is_visible_on_product_page',
        'is_visible_on_shop_page',
        'show_in_search',
        'is_variant',
        'affects_price',
        'affects_stock',
        'affects_weight',
        'has_image',
        'has_thumbnail',
        'placeholder_image',
        'icon',
        'discount_applicable',
        'can_be_used_in_bogo',
        'track_analytics',
        'track_views',
        'track_clicks',
        'track_conversion',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'attribute_group_id',
        'product_category_id',
        'status',
        'is_featured',
        'is_popular',
        'source',
        'source_table',
        'source_model',
        'total_products',
        'total_views',
        'total_clicks',
        'total_revenue'
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'is_filterable' => 'boolean',
        'is_visible_on_product_page' => 'boolean',
        'is_visible_on_shop_page' => 'boolean',
        'show_in_search' => 'boolean',
        'is_variant' => 'boolean',
        'affects_price' => 'boolean',
        'affects_stock' => 'boolean',
        'affects_weight' => 'boolean',
        'has_image' => 'boolean',
        'has_thumbnail' => 'boolean',
        'discount_applicable' => 'boolean',
        'can_be_used_in_bogo' => 'boolean',
        'track_analytics' => 'boolean',
        'track_views' => 'boolean',
        'track_clicks' => 'boolean',
        'track_conversion' => 'boolean',
        'status' => 'boolean',
        'is_featured' => 'boolean',
        'is_popular' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($attribute) {
            $attribute->slug = Str::slug($attribute->name);
        });
    }

    public function group()
    {
        return $this->belongsTo(AttributeGroup::class, 'attribute_group_id');
    }

    public function category()
    {
        return $this->belongsTo(AttributeCategory::class, 'product_category_id');
    }

    public function values()
    {
        return $this->hasMany(AttributeValue::class)->orderBy('display_order');
    }

    public function productValues()
    {
        return $this->hasManyThrough(ProductAttributeValue::class, AttributeValue::class);
    }

    public function discountRules()
    {
        return $this->hasMany(AttributeDiscountRule::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeFilterable($query)
    {
        return $query->where('is_filterable', true)->where('status', true);
    }

    public function scopeVariant($query)
    {
        return $query->where('is_variant', true);
    }
}
