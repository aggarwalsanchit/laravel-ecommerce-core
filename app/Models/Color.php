<?php
// app/Models/Color.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Color extends Model
{
    protected $table = 'colors';

    protected $fillable = [
        // Basic Information
        'name',
        'slug',
        'code',
        'rgb',
        'hsl',

        // Color Details
        'description',
        'image',
        'image_alt',

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
        'approved_at' => 'datetime',
        'requested_at' => 'datetime',
        'last_used_at' => 'datetime',
    ];

    protected $appends = [
        'color_preview_style',
        'formatted_code',
        'is_light_color'
    ];

    // ==================== RELATIONSHIPS ====================

    /**
     * Get the vendor who requested this color
     */
    public function requestedBy(): BelongsTo
    {
        return $this->belongsTo(Vendor::class, 'requested_by');
    }

    /**
     * Get the admin who approved this color
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'approved_by');
    }

    /**
     * Get the products using this color
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'color_product')
            ->withPivot('vendor_id', 'color_image', 'stock_quantity', 'price_adjustment')
            ->withTimestamps();
    }

    /**
     * Get color analytics
     */
    public function analytics(): HasMany
    {
        return $this->hasMany(ColorAnalytic::class);
    }

    /**
     * Get today's analytics
     */
    public function todayAnalytics()
    {
        return $this->hasOne(ColorAnalytic::class)
            ->where('date', today()->toDateString());
    }

    /**
     * Get color requests
     */
    public function requests(): HasMany
    {
        return $this->hasMany(ColorRequest::class, 'created_color_id');
    }

    // ==================== SCOPES ====================

    /**
     * Scope for active colors
     */
    public function scopeActive($query)
    {
        return $query->where('status', true)
            ->where('approval_status', 'approved');
    }

    /**
     * Scope for pending approval colors
     */
    public function scopePending($query)
    {
        return $query->where('approval_status', 'pending');
    }

    /**
     * Scope for approved colors
     */
    public function scopeApproved($query)
    {
        return $query->where('approval_status', 'approved');
    }

    /**
     * Scope for rejected colors
     */
    public function scopeRejected($query)
    {
        return $query->where('approval_status', 'rejected');
    }

    /**
     * Scope for featured colors
     */
    public function scopeFeatured($query)
    {
        return $query->active()->where('is_featured', true);
    }

    /**
     * Scope for popular colors
     */
    public function scopePopular($query)
    {
        return $query->active()->where('is_popular', true);
    }

    /**
     * Scope for trending colors
     */
    public function scopeTrending($query)
    {
        return $query->active()->where('is_trending', true);
    }

    /**
     * Scope for most used colors
     */
    public function scopeMostUsed($query, $limit = 10)
    {
        return $query->active()->orderBy('usage_count', 'desc')->limit($limit);
    }

    /**
     * Scope by color code
     */
    public function scopeByCode($query, $code)
    {
        return $query->where('code', $code);
    }

    // ==================== ACCESSORS & MUTATORS ====================

    /**
     * Get color preview style attribute
     */
    public function getColorPreviewStyleAttribute(): string
    {
        return "background-color: {$this->code}; width: 30px; height: 30px; border-radius: 50%; display: inline-block; border: 1px solid #ddd;";
    }

    /**
     * Get formatted hex code attribute
     */
    public function getFormattedCodeAttribute(): string
    {
        return strtoupper($this->code);
    }

    /**
     * Check if color is light (for text contrast)
     */
    public function getIsLightColorAttribute(): bool
    {
        // Convert hex to RGB
        $hex = ltrim($this->code, '#');
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        // Calculate luminance
        $luminance = (0.299 * $r + 0.587 * $g + 0.114 * $b);

        return $luminance > 186;
    }

    /**
     * Get text color based on background (white or black)
     */
    public function getTextColorAttribute(): string
    {
        return $this->is_light_color ? '#000000' : '#FFFFFF';
    }

    /**
     * Get contrast color for text
     */
    public function getContrastColorAttribute(): string
    {
        return $this->is_light_color ? 'dark' : 'light';
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
     * Get URL for color page
     */
    public function getUrlAttribute(): string
    {
        return route('colors.show', $this->slug);
    }

    /**
     * Get admin URL for edit
     */
    public function getAdminUrlAttribute(): string
    {
        return route('admin.colors.edit', $this->id);
    }

    /**
     * Get RGB values as array
     */
    public function getRgbArrayAttribute(): array
    {
        $hex = ltrim($this->code, '#');
        return [
            'r' => hexdec(substr($hex, 0, 2)),
            'g' => hexdec(substr($hex, 2, 2)),
            'b' => hexdec(substr($hex, 4, 2)),
        ];
    }

    /**
     * Set slug automatically when creating
     */
    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = $value ?: Str::slug($this->name);
    }

    /**
     * Ensure code is always uppercase with # prefix
     */
    public function setCodeAttribute($value)
    {
        $value = strtoupper($value);
        if (!str_starts_with($value, '#')) {
            $value = '#' . $value;
        }
        $this->attributes['code'] = $value;
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
     * Get similar colors (by RGB proximity)
     */
    public function getSimilarColors($limit = 5)
    {
        $currentRgb = $this->rgb_array;

        return self::active()
            ->where('id', '!=', $this->id)
            ->get()
            ->map(function ($color) use ($currentRgb) {
                $colorRgb = $color->rgb_array;
                $distance = sqrt(
                    pow($currentRgb['r'] - $colorRgb['r'], 2) +
                        pow($currentRgb['g'] - $colorRgb['g'], 2) +
                        pow($currentRgb['b'] - $colorRgb['b'], 2)
                );
                $color->similarity = round((1 - $distance / 441) * 100);
                return $color;
            })
            ->sortByDesc('similarity')
            ->take($limit);
    }

    /**
     * Check if color can be deleted
     */
    public function canBeDeleted(): bool
    {
        return $this->products()->count() === 0;
    }

    /**
     * Get all colors for select dropdown
     */
    public static function getSelectOptions()
    {
        return self::active()
            ->orderBy('order')
            ->get()
            ->map(function ($color) {
                return [
                    'id' => $color->id,
                    'name' => $color->name,
                    'code' => $color->code,
                    'preview' => $color->color_preview_style
                ];
            });
    }

    /**
     * Sync product usage count
     */
    public function syncProductCount(): void
    {
        $count = $this->products()->count();
        $this->update(['usage_count' => $count]);
    }

    // ==================== BOOT METHODS ====================

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($color) {
            if (empty($color->slug)) {
                $color->slug = Str::slug($color->name);
            }
            if (empty($color->order)) {
                $color->order = self::max('order') + 1;
            }
        });

        static::updating(function ($color) {
            if ($color->isDirty('name')) {
                $color->slug = Str::slug($color->name);
            }
        });

        static::deleting(function ($color) {
            // Delete analytics when color is deleted
            $color->analytics()->delete();

            // Detach from products
            $color->products()->detach();
        });
    }

    public function getSimilarColorsAttribute()
{
    $currentRgb = $this->rgb_array;
    
    return self::active()
        ->where('id', '!=', $this->id)
        ->get()
        ->map(function ($color) use ($currentRgb) {
            $colorRgb = $color->rgb_array;
            $distance = sqrt(
                pow($currentRgb['r'] - $colorRgb['r'], 2) +
                pow($currentRgb['g'] - $colorRgb['g'], 2) +
                pow($currentRgb['b'] - $colorRgb['b'], 2)
            );
            $color->similarity = round((1 - $distance / 441) * 100);
            return $color;
        })
        ->filter(function ($color) {
            return $color->similarity > 70;
        })
        ->sortByDesc('similarity')
        ->take(6);
}

}
