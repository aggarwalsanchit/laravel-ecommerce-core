<?php
// app/Models/Product.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Product extends Model
{
    use SoftDeletes;

    protected $table = 'products';

    protected $fillable = [
        'name',
        'slug',
        'short_description',
        'description',
        'highlights',
        'brand_id',
        'vendor_id',
        'sku',
        'barcode',
        'price',
        'compare_price',
        'cost',
        'wholesale_price',
        'is_wholesale',
        'min_price',
        'max_price',
        'is_range',
        'stock_quantity',
        'low_stock_threshold',
        'track_stock',
        'allow_backorder',
        'stock_status',
        'weight',
        'length',
        'width',
        'height',
        'free_shipping',
        'status',
        'is_featured',
        'is_bestseller',
        'is_new',
        'sort_order',
        'published_at',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'focus_keyword',
        'canonical_url',
        'og_title',
        'og_description',
        'og_image',
        'primary_category_id',
        'approval_status',
        'rejection_reason',
        'approved_by',
        'approved_at'
    ];

    protected $casts = [
        'highlights' => 'array',
        'is_wholesale' => 'boolean',
        'is_range' => 'boolean',
        'track_stock' => 'boolean',
        'allow_backorder' => 'boolean',
        'free_shipping' => 'boolean',
        'status' => 'boolean',
        'is_featured' => 'boolean',
        'is_bestseller' => 'boolean',
        'is_new' => 'boolean',
        'sale_start_at' => 'datetime',
        'sale_end_at' => 'datetime',
        'published_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    // ==================== RELATIONSHIPS ====================

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'approved_by');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function mainImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_main', true);
    }

    public function videos(): HasMany
    {
        return $this->hasMany(ProductVideo::class)->orderBy('sort_order');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'product_categories');
    }

    public function primaryCategory()
    {
        return $this->belongsTo(Category::class, 'primary_category_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'product_tag');
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class)->orderBy('sort_order');
    }

    public function attributeValues(): HasMany
    {
        return $this->hasMany(ProductAttributeValue::class);
    }

    public function tierPrices(): HasMany
    {
        return $this->hasMany(ProductTierPrice::class);
    }

    public function discounts()
    {
        return $this->belongsToMany(Discount::class, 'discount_product');
    }

    public function relatedProducts()
    {
        return $this->hasMany(ProductRelation::class, 'product_id');
    }

    public function questions(): HasMany
    {
        return $this->hasMany(ProductQuestion::class);
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(ProductRating::class);
    }

    public function dailyAnalytics(): HasMany
    {
        return $this->hasMany(ProductDailyAnalytic::class);
    }

    // ==================== SCOPES ====================

    public function scopeActive($query)
    {
        return $query->where('status', true)->where('approval_status', 'approved');
    }

    public function scopeInStock($query)
    {
        return $query->where('stock_status', 'instock');
    }

    public function scopeOnSale($query)
    {
        return $query->whereNotNull('sale_start_at')
            ->where('sale_start_at', '<=', now())
            ->where(function ($q) {
                $q->whereNull('sale_end_at')->orWhere('sale_end_at', '>=', now());
            });
    }

    // ==================== ACCESSORS ====================

    public function getDiscountedPriceAttribute(): ?float
    {
        if ($this->sale_start_at && $this->sale_end_at && now()->between($this->sale_start_at, $this->sale_end_at)) {
            return $this->compare_price ?? $this->price;
        }
        return null;
    }

    public function getIsOnSaleAttribute(): bool
    {
        return $this->discounted_price !== null && $this->discounted_price < $this->price;
    }

    // ==================== BOOT ====================

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }
}
