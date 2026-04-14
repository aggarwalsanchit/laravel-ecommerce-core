<?php
// app/Models/Attribute.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Attribute extends Model
{
    protected $table = 'attributes';

    protected $fillable = [
        'name', 'slug', 'description', 'type', 'unit', 'order',
        'is_required', 'is_unique', 'is_filterable', 'is_searchable', 'is_comparable',
        'show_on_product_page', 'show_on_product_list',
        'min_value', 'max_value', 'max_length', 'regex_pattern',
        'default_value', 'placeholder', 'help_text',
        'icon', 'input_class', 'wrapper_class',
        'status', 'is_featured',
        'approval_status', 'requested_by', 'request_notes',
        'rejection_reason', 'approved_by', 'approved_at', 'requested_at',
        'meta_title', 'meta_description', 'group_id'
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'is_unique' => 'boolean',
        'is_filterable' => 'boolean',
        'is_searchable' => 'boolean',
        'is_comparable' => 'boolean',
        'show_on_product_page' => 'boolean',
        'show_on_product_list' => 'boolean',
        'status' => 'boolean',
        'is_featured' => 'boolean',
        'approved_at' => 'datetime',
        'requested_at' => 'datetime',
    ];

    protected $appends = ['status_badge', 'type_label'];

    // ==================== RELATIONSHIPS ====================

    /**
     * Get the group this attribute belongs to
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(AttributeGroup::class, 'group_id');
    }

    /**
     * Get the vendor who requested this attribute
     */
    public function requestedBy(): BelongsTo
    {
        return $this->belongsTo(Vendor::class, 'requested_by');
    }

    /**
     * Get the admin who approved this attribute
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'approved_by');
    }

    /**
     * Get the predefined values for this attribute (for select/multiselect)
     */
    public function values(): HasMany
    {
        return $this->hasMany(AttributeValue::class)->orderBy('order');
    }

    /**
     * Get the categories this attribute belongs to
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'attribute_category')
            ->withPivot('group_id', 'order', 'is_visible')
            ->withTimestamps();
    }

    /**
     * Get product attribute values
     */
    public function productValues(): HasMany
    {
        return $this->hasMany(ProductAttributeValue::class);
    }

    /**
     * Get analytics for this attribute
     */
    public function analytics(): HasMany
    {
        return $this->hasMany(AttributeAnalytic::class);
    }

    // ==================== SCOPES ====================

    public function scopeActive($query)
    {
        return $query->where('status', true)->where('approval_status', 'approved');
    }

    public function scopeFilterable($query)
    {
        return $query->active()->where('is_filterable', true);
    }

    public function scopeForCategory($query, $categoryId)
    {
        return $query->whereHas('categories', function ($q) use ($categoryId) {
            $q->where('category_id', $categoryId);
        });
    }

    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    // ==================== ACCESSORS ====================

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

    public function getTypeLabelAttribute(): string
    {
        $types = [
            'text' => 'Text Field',
            'textarea' => 'Text Area',
            'number' => 'Number Field',
            'decimal' => 'Decimal Field',
            'select' => 'Select Dropdown',
            'multiselect' => 'Multi-Select',
            'checkbox' => 'Checkbox',
            'radio' => 'Radio Button',
            'date' => 'Date Picker',
            'datetime' => 'Date & Time',
            'color' => 'Color Picker',
            'image' => 'Image Upload',
            'file' => 'File Upload',
            'url' => 'URL Field',
            'email' => 'Email Field',
            'phone' => 'Phone Field',
        ];
        return $types[$this->type] ?? ucfirst($this->type);
    }

    public function getTypeIconAttribute(): string
    {
        $icons = [
            'text' => 'ti ti-text-size',
            'textarea' => 'ti ti-article',
            'number' => 'ti ti-numbers',
            'select' => 'ti ti-list',
            'multiselect' => 'ti ti-list-check',
            'checkbox' => 'ti ti-checkbox',
            'radio' => 'ti ti-circle',
            'date' => 'ti ti-calendar',
            'datetime' => 'ti ti-calendar-time',
            'color' => 'ti ti-color-swatch',
            'image' => 'ti ti-photo',
            'file' => 'ti ti-file',
            'url' => 'ti ti-link',
            'email' => 'ti ti-mail',
            'phone' => 'ti ti-phone',
        ];
        return $icons[$this->type] ?? 'ti ti-input';
    }

    // ==================== HELPER METHODS ====================

    /**
     * Check if attribute has predefined values
     */
    public function hasPredefinedValues(): bool
    {
        return in_array($this->type, ['select', 'multiselect', 'radio', 'checkbox']);
    }

    /**
     * Get validation rules array
     */
    public function getValidationRules(): array
    {
        $rules = [];
        
        if ($this->is_required) {
            $rules[] = 'required';
        }
        
        if ($this->type === 'number' || $this->type === 'decimal') {
            if ($this->min_value) $rules[] = "min:{$this->min_value}";
            if ($this->max_value) $rules[] = "max:{$this->max_value}";
        }
        
        if ($this->type === 'text' && $this->max_length) {
            $rules[] = "max:{$this->max_length}";
        }
        
        if ($this->regex_pattern) {
            $rules[] = "regex:{$this->regex_pattern}";
        }
        
        if ($this->type === 'email') {
            $rules[] = 'email';
        }
        
        if ($this->type === 'url') {
            $rules[] = 'url';
        }
        
        if ($this->type === 'phone') {
            $rules[] = 'regex:/^([0-9\s\-\+\(\)]*)$/';
        }
        
        return $rules;
    }

    // ==================== BOOT ====================

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($attribute) {
            if (empty($attribute->slug)) {
                $attribute->slug = Str::slug($attribute->name);
            }
        });
    }
}