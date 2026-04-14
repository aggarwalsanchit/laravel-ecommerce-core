<?php
// app/Models/Size.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Size extends Model
{
    protected $table = 'sizes';

    protected $fillable = [
        // Basic Information
        'name',
        'slug',
        'code',
        'gender',

        // Size Measurements
        'chest',
        'waist',
        'hip',
        'inseam',
        'shoulder',
        'sleeve',
        'neck',
        'height',
        'weight',

        // International Conversions
        'us_size',
        'uk_size',
        'eu_size',
        'au_size',
        'jp_size',
        'cn_size',
        'int_size',

        // Additional Info
        'description',
        'image',
        'image_alt',
        'icon',

        // Status & Visibility
        'status',
        'is_featured',
        'is_popular',
        'is_trending',
        'order',

        // Vendor Request & Approval System
        'approval_status',
        'requested_by',
        'request_notes',
        'rejection_reason',
        'approved_by',
        'approved_at',
        'requested_at',

        // SEO Fields
        'meta_title',
        'meta_description',
        'meta_keywords',
        'focus_keyword',
        'canonical_url',

        // Social Media / Open Graph
        'og_title',
        'og_description',
        'og_image',

        // Usage Tracking
        'usage_count',
        'last_used_at',
    ];

    protected $casts = [
        'status' => 'boolean',
        'is_featured' => 'boolean',
        'is_popular' => 'boolean',
        'is_trending' => 'boolean',
        'chest' => 'decimal:2',
        'waist' => 'decimal:2',
        'hip' => 'decimal:2',
        'inseam' => 'decimal:2',
        'shoulder' => 'decimal:2',
        'sleeve' => 'decimal:2',
        'neck' => 'decimal:2',
        'height' => 'decimal:2',
        'weight' => 'decimal:2',
        'approved_at' => 'datetime',
        'requested_at' => 'datetime',
        'last_used_at' => 'datetime',
    ];

    protected $appends = [
        'full_name',
        'measurement_summary'
    ];

    // ==================== RELATIONSHIPS ====================

    /**
     * Get the categories that this size belongs to
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_size')
            ->withPivot('order')
            ->withTimestamps();
    }

    /**
     * Get the vendor who requested this size
     */
    public function requestedBy(): BelongsTo
    {
        return $this->belongsTo(Vendor::class, 'requested_by');
    }

    /**
     * Get the admin who approved this size
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'approved_by');
    }

    /**
     * Get the products using this size
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_size')
            ->withPivot('vendor_id', 'stock_quantity', 'price_adjustment')
            ->withTimestamps();
    }

    /**
     * Get size analytics
     */
    public function analytics(): HasMany
    {
        return $this->hasMany(SizeAnalytic::class);
    }

    /**
     * Get today's analytics
     */
    public function todayAnalytics()
    {
        return $this->hasOne(SizeAnalytic::class)
            ->where('date', today()->toDateString());
    }

    /**
     * Get size requests
     */
    public function requests(): HasMany
    {
        return $this->hasMany(SizeRequest::class, 'created_size_id');
    }

    // ==================== SCOPES ====================

    /**
     * Scope for active sizes
     */
    public function scopeActive($query)
    {
        return $query->where('status', true)
            ->where('approval_status', 'approved');
    }

    /**
     * Scope for pending approval sizes
     */
    public function scopePending($query)
    {
        return $query->where('approval_status', 'pending');
    }

    /**
     * Scope for approved sizes
     */
    public function scopeApproved($query)
    {
        return $query->where('approval_status', 'approved');
    }

    /**
     * Scope for rejected sizes
     */
    public function scopeRejected($query)
    {
        return $query->where('approval_status', 'rejected');
    }

    /**
     * Scope for featured sizes
     */
    public function scopeFeatured($query)
    {
        return $query->active()->where('is_featured', true);
    }

    /**
     * Scope for popular sizes
     */
    public function scopePopular($query)
    {
        return $query->active()->where('is_popular', true);
    }

    /**
     * Scope for a specific gender
     */
    public function scopeForGender($query, $gender)
    {
        return $query->where('gender', $gender);
    }

    /**
     * Scope for a specific category
     */
    public function scopeForCategory($query, $categoryId)
    {
        return $query->whereHas('categories', function ($q) use ($categoryId) {
            $q->where('category_id', $categoryId);
        });
    }

    /**
     * Scope for a specific product type
     */
    public function scopeForProductType($query, $productType)
    {
        return $query->whereHas('categories', function ($q) use ($productType) {
            $q->where('name', 'like', "%{$productType}%");
        });
    }

    /**
     * Scope for clothing sizes
     */
    public function scopeClothing($query)
    {
        return $query->whereIn('gender', ['Men', 'Women', 'Unisex', 'Kids']);
    }

    /**
     * Scope for shoe sizes
     */
    public function scopeShoes($query)
    {
        return $query->where('code', 'like', 'SHOE-%');
    }

    /**
     * Scope for most used sizes
     */
    public function scopeMostUsed($query, $limit = 10)
    {
        return $query->active()->orderBy('usage_count', 'desc')->limit($limit);
    }

    // ==================== ACCESSORS & MUTATORS ====================

    /**
     * Get full name with gender prefix
     */
    public function getFullNameAttribute(): string
    {
        if ($this->gender && $this->gender !== 'Unisex') {
            return "{$this->gender} {$this->name}";
        }
        return $this->name;
    }

    /**
     * Get measurement summary
     */
    public function getMeasurementSummaryAttribute(): string
    {
        $measurements = [];
        if ($this->chest) $measurements[] = "Chest: {$this->chest}\"";
        if ($this->waist) $measurements[] = "Waist: {$this->waist}\"";
        if ($this->hip) $measurements[] = "Hip: {$this->hip}\"";
        if ($this->inseam) $measurements[] = "Inseam: {$this->inseam}\"";

        return !empty($measurements) ? implode(' | ', $measurements) : 'No measurements available';
    }

    /**
     * Get status badge HTML
     */
    public function getStatusBadgeAttribute(): string
    {
        if (!$this->status) {
            return '<span class="badge bg-danger">Inactive</span>';
        }

        if ($this->approval_status === 'pending') {
            return '<span class="badge bg-warning">Pending</span>';
        }

        if ($this->approval_status === 'rejected') {
            return '<span class="badge bg-danger">Rejected</span>';
        }

        return '<span class="badge bg-success">Active</span>';
    }

    /**
     * Get gender badge
     */
    public function getGenderBadgeAttribute(): string
    {
        $colors = [
            'Men' => 'primary',
            'Women' => 'danger',
            'Unisex' => 'info',
            'Kids' => 'success',
        ];

        $color = $colors[$this->gender] ?? 'secondary';
        return "<span class='badge bg-{$color}'>{$this->gender}</span>";
    }

    /**
     * Get URL for size page
     */
    public function getUrlAttribute(): string
    {
        return route('sizes.show', $this->slug);
    }

    /**
     * Get admin URL for edit
     */
    public function getAdminUrlAttribute(): string
    {
        return route('admin.sizes.edit', $this->id);
    }

    /**
     * Set slug automatically when creating
     */
    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = $value ?: Str::slug($this->name);
    }

    /**
     * Ensure code is uppercase
     */
    public function setCodeAttribute($value)
    {
        $this->attributes['code'] = strtoupper($value);
    }

    // ==================== HELPER METHODS ====================

    /**
     * Increment usage count
     */
    public function incrementUsageCount(): void
    {
        $this->increment('usage_count');
        $this->update(['last_used_at' => now()]);
    }

    /**
     * Decrement usage count
     */
    public function decrementUsageCount(): void
    {
        $this->decrement('usage_count');
    }

    /**
     * Check if size can be deleted
     */
    public function canBeDeleted(): bool
    {
        return $this->products()->count() === 0;
    }

    /**
     * Get conversion chart for this size
     */
    public function getConversionChart(): array
    {
        $chart = [];
        if ($this->us_size) $chart['US'] = $this->us_size;
        if ($this->uk_size) $chart['UK'] = $this->uk_size;
        if ($this->eu_size) $chart['EU'] = $this->eu_size;
        if ($this->au_size) $chart['AU'] = $this->au_size;
        if ($this->jp_size) $chart['JP'] = $this->jp_size;
        if ($this->cn_size) $chart['CN'] = $this->cn_size;
        if ($this->int_size) $chart['International'] = $this->int_size;

        return $chart;
    }

    /**
     * Sync product usage count
     */
    public function syncProductCount(): void
    {
        $count = $this->products()->count();
        $this->update(['usage_count' => $count]);
    }

    /**
     * Get all sizes for select dropdown
     */
    public static function getSelectOptions($categoryId = null)
    {
        $query = self::active()->orderBy('order');

        if ($categoryId) {
            $query->forCategory($categoryId);
        }

        return $query->get()->map(function ($size) {
            return [
                'id' => $size->id,
                'name' => $size->full_name,
                'code' => $size->code,
                'gender' => $size->gender,
            ];
        });
    }

    // ==================== BOOT METHODS ====================

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($size) {
            if (empty($size->slug)) {
                $size->slug = Str::slug($size->name);
            }
            if (empty($size->order)) {
                $size->order = self::max('order') + 1;
            }
        });

        static::updating(function ($size) {
            if ($size->isDirty('name')) {
                $size->slug = Str::slug($size->name);
            }
        });

        static::deleting(function ($size) {
            // Delete analytics when size is deleted
            $size->analytics()->delete();

            // Detach from categories
            $size->categories()->detach();

            // Detach from products
            $size->products()->detach();
        });
    }
}
