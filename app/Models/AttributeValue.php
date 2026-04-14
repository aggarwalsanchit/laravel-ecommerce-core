<?php
// app/Models/AttributeValue.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AttributeValue extends Model
{
    protected $table = 'attribute_values';

    protected $fillable = [
        'attribute_id', 'value', 'label', 'color_code',
        'image', 'image_alt', 'order', 'description',
        'price_adjustment', 'weight_adjustment', 'status', 'is_default'
    ];

    protected $casts = [
        'price_adjustment' => 'decimal:2',
        'weight_adjustment' => 'decimal:2',
        'status' => 'boolean',
        'is_default' => 'boolean',
    ];

    protected $appends = ['display_name', 'status_badge'];

    // ==================== RELATIONSHIPS ====================

    /**
     * Get the attribute this value belongs to
     */
    public function attribute(): BelongsTo
    {
        return $this->belongsTo(Attribute::class);
    }

    /**
     * Get product attribute values using this value
     */
    public function productValues(): HasMany
    {
        return $this->hasMany(ProductAttributeValue::class, 'attribute_value_id');
    }

    /**
     * Get analytics for this attribute value
     */
    public function analytics(): HasMany
    {
        return $this->hasMany(AttributeAnalytic::class, 'attribute_value_id');
    }

    // ==================== ACCESSORS ====================

    public function getDisplayNameAttribute(): string
    {
        return $this->label ?? $this->value;
    }

    public function getStatusBadgeAttribute(): string
    {
        return $this->status 
            ? '<span class="badge bg-success">Active</span>'
            : '<span class="badge bg-danger">Inactive</span>';
    }

    public function getColorPreviewAttribute(): string
    {
        if ($this->color_code) {
            return "<div style='width: 30px; height: 30px; background-color: {$this->color_code}; border-radius: 50%; border: 1px solid #ddd;'></div>";
        }
        return '';
    }

    // ==================== SCOPES ====================

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}