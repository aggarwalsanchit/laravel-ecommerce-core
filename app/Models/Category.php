<?php
// app/Models/Category.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $table = 'categories';

    protected $fillable = [
        // Basic
        'name',
        'slug',
        'description',
        'short_description',
        'parent_id',
        'level',
        'path',
        'order',

        // Images
        'image',
        'image_alt',
        'banner_image',
        'banner_alt',
        'thumbnail_image',
        'thumbnail_alt',
        'icon',

        // Status
        'status',
        'show_in_menu',
        'is_featured',
        'is_popular',
        'is_trending',

        // Approval
        'approval_status',
        'requested_by',
        'request_notes',
        'rejection_reason',
        'approved_by',
        'approved_at',
        'requested_at',

        // SEO
        'meta_title',
        'meta_description',
        'meta_keywords',
        'focus_keyword',
        'canonical_url',

        // Social
        'og_title',
        'og_description',
        'og_image',

        // Schema
        'schema_markup',

        // Tracking
        'last_viewed_at',
        'last_updated_at',
    ];

    protected $casts = [
        'schema_markup' => 'array',
        'status' => 'boolean',
        'show_in_menu' => 'boolean',
        'is_featured' => 'boolean',
        'is_popular' => 'boolean',
        'is_trending' => 'boolean',
        'approved_at' => 'datetime',
        'requested_at' => 'datetime',
        'last_viewed_at' => 'datetime',
        'last_updated_at' => 'datetime',
    ];

    protected $appends = [
        'full_path',
        'breadcrumb',
        'has_children',
        'is_root'
    ];
    
    // ==================== RELATIONSHIPS ====================

    /**
     * Get the parent category
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Get the child categories
     */
    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('order');
    }

    /**
     * Get all descendants (recursive)
     */
    public function descendants()
    {
        return $this->children()->with('descendants');
    }

    /**
     * Get products in this category
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get vendor who requested this category
     */
    public function requestedBy(): BelongsTo
    {
        return $this->belongsTo(Vendor::class, 'requested_by');
    }

    /**
     * Get admin who approved this category
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'approved_by');
    }

    /**
     * Get category analytics
     */
    public function analytics(): HasMany
    {
        return $this->hasMany(CategoryAnalytic::class);
    }

    /**
     * Get today's analytics
     */
    public function todayAnalytics()
    {
        return $this->hasOne(CategoryAnalytic::class)
            ->where('date', today()->toDateString());
    }

    /**
     * Get category requests
     */
    public function requests(): HasMany
    {
        return $this->hasMany(CategoryRequest::class, 'created_category_id');
    }
    
    // ==================== SCOPES ====================

    /**
     * Scope for active categories
     */
    public function scopeActive($query)
    {
        return $query->where('status', true)
            ->where('approval_status', 'approved');
    }

    /**
     * Scope for pending approval categories
     */
    public function scopePending($query)
    {
        return $query->where('approval_status', 'pending');
    }

    /**
     * Scope for approved categories
     */
    public function scopeApproved($query)
    {
        return $query->where('approval_status', 'approved');
    }

    /**
     * Scope for rejected categories
     */
    public function scopeRejected($query)
    {
        return $query->where('approval_status', 'rejected');
    }

    /**
     * Scope for categories in menu
     */
    public function scopeInMenu($query)
    {
        return $query->active()->where('show_in_menu', true);
    }

    /**
     * Scope for parent categories (level 0)
     */
    public function scopeParents($query)
    {
        return $query->whereNull('parent_id')->orWhere('level', 0);
    }

    /**
     * Scope for featured categories
     */
    public function scopeFeatured($query)
    {
        return $query->active()->where('is_featured', true);
    }

    /**
     * Scope for popular categories
     */
    public function scopePopular($query)
    {
        return $query->active()->where('is_popular', true);
    }

    /**
     * Scope for trending categories
     */
    public function scopeTrending($query)
    {
        return $query->active()->where('is_trending', true);
    }

    /**
     * Scope for root level categories
     */
    public function scopeRootLevel($query)
    {
        return $query->whereNull('parent_id')->where('level', 0);
    }
    
    // ==================== ACCESSORS & MUTATORS ====================

    /**
     * Get full path as breadcrumb
     */
    public function getBreadcrumbAttribute(): string
    {
        if (!$this->path) {
            return $this->name;
        }

        $ids = explode('/', $this->path);
        $categories = self::whereIn('id', $ids)
            ->orderBy('level')
            ->get();

        return $categories->pluck('name')->implode(' > ');
    }

    /**
     * Get all parent categories
     */
    public function getParentsAttribute()
    {
        if (!$this->path) {
            return collect();
        }

        $ids = explode('/', $this->path);
        return self::whereIn('id', $ids)
            ->orderBy('level')
            ->get();
    }

    /**
     * Get full path for URL
     */
    public function getFullPathAttribute(): string
    {
        if (empty($this->path)) {
            return $this->slug ?? '';
        }

        $ids = explode('/', $this->path);
        $categories = self::whereIn('id', $ids)
            ->orderBy('level')
            ->get();

        if ($categories->isEmpty()) {
            return $this->slug ?? '';
        }

        $path = $categories->pluck('slug')->implode('/');
        return $path . '/' . $this->slug;
    }

    /**
     * Check if category has children
     */
    public function getHasChildrenAttribute(): bool
    {
        return $this->children()->exists();
    }

    /**
     * Check if category is root level
     */
    public function getIsRootAttribute(): bool
    {
        return is_null($this->parent_id) || $this->level === 0;
    }

    /**
     * Get total products count including subcategories
     */
    public function getTotalProductsCountAttribute(): int
    {
        $count = $this->products()->count();

        foreach ($this->children as $child) {
            $count += $child->total_products_count;
        }

        return $count;
    }

    /**
     * Get URL for category
     */
    public function getUrlAttribute(): string
    {
        return route('categories.show', $this->slug);
    }

    /**
     * Get admin URL for category
     */
    public function getAdminUrlAttribute(): string
    {
        return route('admin.categories.edit', $this->id);
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
    
    // ==================== HELPER METHODS ====================

    /**
     * Increment view count
     */
    public function incrementViewCount(): void
    {
        $this->increment('view_count');
        $this->update(['last_viewed_at' => now()]);

        // Also update analytics table
        CategoryAnalytic::updateOrCreate(
            [
                'category_id' => $this->id,
                'date' => today()->toDateString()
            ],
            [
                'view_count' => \DB::raw('view_count + 1')
            ]
        );
    }

    /**
     * Update product count
     */
    public function updateProductCount(): void
    {
        $count = $this->products()->count();
        $this->update(['product_count' => $count]);
    }

    /**
     * Get all category IDs including descendants
     */
    public function getDescendantIds(): array
    {
        $ids = [$this->id];

        foreach ($this->children as $child) {
            $ids = array_merge($ids, $child->getDescendantIds());
        }

        return $ids;
    }

    /**
     * Check if category can be deleted
     */
    public function canBeDeleted(): bool
    {
        return !$this->has_children && $this->products()->count() === 0;
    }

    /**
     * Get category tree for select dropdown
     */
    public static function getSelectTree($excludeId = null, $prefix = ''): array
    {
        $categories = self::active()->parents()->orderBy('order')->get();
        $options = [];

        foreach ($categories as $category) {
            if ($excludeId && $category->id == $excludeId) {
                continue;
            }

            $options[$category->id] = $prefix . $category->name;
            $options = array_merge($options, $category->getChildrenForSelect($excludeId, $prefix . '-- '));
        }

        return $options;
    }

    /**
     * Get children for select dropdown
     */
    protected function getChildrenForSelect($excludeId = null, $prefix = ''): array
    {
        $options = [];

        foreach ($this->children as $child) {
            if ($excludeId && $child->id == $excludeId) {
                continue;
            }

            $options[$child->id] = $prefix . $child->name;
            $options = array_merge($options, $child->getChildrenForSelect($excludeId, $prefix . '-- '));
        }

        return $options;
    }

    /**
     * Get category hierarchy as array
     */
    public function toHierarchyArray(): array
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'children' => []
        ];

        foreach ($this->children as $child) {
            $data['children'][] = $child->toHierarchyArray();
        }

        return $data;
    }

    public function brands()
    {
        return $this->belongsToMany(Brand::class, 'brand_category', 'category_id', 'brand_id')
            ->withTimestamps();
    }

    // ==================== BOOT METHODS ====================

    protected static function boot()
    {
        parent::boot();

        // Auto-generate slug and path before creating
        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }

            if (empty($category->level) && $category->parent_id) {
                $parent = self::find($category->parent_id);
                $category->level = $parent->level + 1;
                $category->path = $parent->path ? $parent->path . '/' . $parent->id : (string) $parent->id;
            } elseif (empty($category->level)) {
                $category->level = 0;
            }
        });

        // Update children paths when parent changes
        static::updating(function ($category) {
            if ($category->isDirty('parent_id')) {
                $oldPath = $category->getOriginal('path');
                $oldLevel = $category->getOriginal('level');

                if ($category->parent_id) {
                    $parent = self::find($category->parent_id);
                    $category->level = $parent->level + 1;
                    $category->path = $parent->path ? $parent->path . '/' . $parent->id : (string) $parent->id;
                } else {
                    $category->level = 0;
                    $category->path = null;
                }

                // Update all descendants
                if ($oldPath) {
                    self::where('path', 'LIKE', $oldPath . '/%')
                        ->orWhere('path', $oldPath)
                        ->update([
                            'level' => \DB::raw('level - ' . ($oldLevel - $category->level)),
                            'path' => \DB::raw("REPLACE(path, '{$oldPath}', '{$category->path}')")
                        ]);
                }
            }
        });

        // Delete analytics when category is deleted
        static::deleting(function ($category) {
            $category->analytics()->delete();
        });
    }
}
