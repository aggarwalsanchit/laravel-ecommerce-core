<?php
// app/Models/Product.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
use App\Services\DiscountService;

class Product extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'sku',
        'short_description',
        'description',
        'category_id',
        'pricing_type',
        'price',
        'sale_price',
        'sale_start_date',
        'sale_end_date',
        'track_stock',
        'stock',
        'low_stock_threshold',
        'stock_status',
        'allow_backorder',
        'weight',
        'length',
        'width',
        'height',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'status',
        'is_featured',
        'is_new',
        'is_bestseller',
        'is_on_sale',
        'view_count',
        'order_count',
        'total_sold',
        'avg_rating',
        'review_count'
    ];

    protected $casts = [
        'sale_start_date' => 'date',
        'sale_end_date' => 'date',
        'status' => 'boolean',
        'is_featured' => 'boolean',
        'is_new' => 'boolean',
        'is_bestseller' => 'boolean',
        'is_on_sale' => 'boolean',
        'allow_backorder' => 'boolean',
        'track_stock' => 'boolean',
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'weight' => 'decimal:2',
        'length' => 'decimal:2',
        'width' => 'decimal:2',
        'height' => 'decimal:2',
        'view_count' => 'integer',
        'order_count' => 'integer',
        'total_sold' => 'decimal:2',
        'avg_rating' => 'decimal:2',
        'review_count' => 'integer',
        'stock' => 'integer',
        'low_stock_threshold' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($product) {
            $product->slug = Str::slug($product->name);
        });
    }

    // ========== RELATIONSHIPS ==========

    // Main Category
    public function mainCategory()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    // Multiple Subcategories (via ProductSubcategory pivot)
    public function subcategories()
    {
        return $this->belongsToMany(Category::class, 'product_subcategories', 'product_id', 'category_id')
            ->withTimestamps();
    }

    // Multiple Colors (via ProductColor pivot)
    public function colors()
    {
        return $this->belongsToMany(Color::class, 'product_colors', 'product_id', 'color_id')
            ->withPivot('color_image')
            ->withTimestamps();
    }

    // Multiple Sizes (via ProductSize pivot)
    public function sizes()
    {
        return $this->belongsToMany(Size::class, 'product_sizes', 'product_id', 'size_id')
            ->withPivot('stock', 'price_adjustment')
            ->withTimestamps();
    }

    // Variants (Color + Size combinations)
    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    // Multiple Images
    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('order');
    }

    // Featured Image (convenience method)
    public function featuredImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_featured', true);
    }

    // Tiered Pricing
    public function tierPrices()
    {
        return $this->hasMany(ProductTierPrice::class);
    }

    // Custom Attributes (Universal Attributes System)
    public function customAttributes()
    {
        return $this->belongsToMany(AttributeValue::class, 'product_custom_attributes', 'product_id', 'attribute_value_id')
            ->withTimestamps();
    }

    // ========== HELPER METHODS ==========

    // Get featured image URL
    public function getFeaturedImageUrlAttribute()
    {
        $featured = $this->images()->where('is_featured', true)->first();
        if ($featured) {
            return Storage::disk('public')->url($featured->image_path);
        }

        $firstImage = $this->images()->first();
        if ($firstImage) {
            return Storage::disk('public')->url($firstImage->image_path);
        }

        return asset('images/placeholder.jpg');
    }

    // Get all image URLs
    public function getAllImageUrlsAttribute()
    {
        return $this->images->map(function ($image) {
            return Storage::disk('public')->url($image->image_path);
        });
    }

    // Get current price (considering sale)
    public function getCurrentPriceAttribute()
    {
        if (
            $this->is_on_sale && $this->sale_price &&
            $this->sale_start_date <= now() && $this->sale_end_date >= now()
        ) {
            return $this->sale_price;
        }
        return $this->price;
    }

    // Get formatted price
    public function getFormattedPriceAttribute()
    {
        return '$' . number_format($this->price, 2);
    }

    // Get formatted sale price
    public function getFormattedSalePriceAttribute()
    {
        return $this->sale_price ? '$' . number_format($this->sale_price, 2) : null;
    }

    // Get discount percentage
    public function getDiscountPercentageAttribute()
    {
        if ($this->sale_price && $this->price > 0) {
            return round((($this->price - $this->sale_price) / $this->price) * 100);
        }
        return 0;
    }

    // Get price for specific quantity (tiered pricing)
    public function getPriceForQuantity($quantity)
    {
        if ($this->pricing_type === 'tiered') {
            $tier = $this->tierPrices()
                ->where('min_quantity', '<=', $quantity)
                ->where(function ($q) use ($quantity) {
                    $q->whereNull('max_quantity')->orWhere('max_quantity', '>=', $quantity);
                })
                ->first();

            if ($tier) {
                return $tier->price;
            }
        }
        return $this->current_price;
    }

    // Check if product is in stock
    public function isInStock()
    {
        if (!$this->track_stock) return true;
        return $this->stock > 0;
    }

    // Get stock status badge
    public function getStockStatusBadgeAttribute()
    {
        if (!$this->track_stock) return ['class' => 'success', 'text' => 'In Stock'];

        return match ($this->stock_status) {
            'in_stock' => ['class' => 'success', 'text' => 'In Stock'],
            'out_of_stock' => ['class' => 'danger', 'text' => 'Out of Stock'],
            'backorder' => ['class' => 'warning', 'text' => 'Backorder'],
            'pre_order' => ['class' => 'info', 'text' => 'Pre-Order'],
            default => ['class' => 'secondary', 'text' => 'Unknown'],
        };
    }

    // Get available colors
    public function getAvailableColorsAttribute()
    {
        return $this->colors()->get();
    }

    // Get available sizes
    public function getAvailableSizesAttribute()
    {
        return $this->sizes()->get();
    }

    // Increment view count
    public function incrementViewCount()
    {
        $this->increment('view_count');
    }

    // ========== SCOPES ==========

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeOnSale($query)
    {
        return $query->where('is_on_sale', true)
            ->where('sale_start_date', '<=', now())
            ->where('sale_end_date', '>=', now());
    }

    public function scopeInStock($query)
    {
        return $query->where(function ($q) {
            $q->where('track_stock', false)
                ->orWhere('stock', '>', 0);
        });
    }

    public function getDiscountInfoAttribute()
    {
        return DiscountService::applyDiscountToProduct($this);
    }

    public function getHasDiscountAttribute()
    {
        return $this->discount_info['has_discount'];
    }

    public function getFinalPriceAttribute()
    {
        return $this->discount_info['final_price'];
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function isOwnStoreProduct()
    {
        return $this->vendor && $this->vendor->vendor_type === 'own_store';
    }

    public function getVendorNameAttribute()
    {
        return $this->vendor ? $this->vendor->shop_name : 'Admin Store';
    }

    // Add global scope for vendors
    protected static function booted()
    {
        static::addGlobalScope('vendor', function (Builder $builder) {
            if (auth()->check() && auth()->user()->hasRole('Vendor')) {
                $vendorId = auth()->user()->vendor->id;
                $builder->where('vendor_id', $vendorId);
            }
        });
    }
}
