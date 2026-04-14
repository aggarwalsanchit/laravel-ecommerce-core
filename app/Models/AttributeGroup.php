<?php
// app/Models/AttributeGroup.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class AttributeGroup extends Model
{
    protected $table = 'attribute_groups';

    protected $fillable = [
        'name', 'slug', 'description', 'order',
        'is_collapsible', 'is_open_by_default', 'icon', 'position',
        'status', 'approval_status', 'requested_by', 'request_notes',
        'rejection_reason', 'approved_by', 'approved_at', 'requested_at'
    ];

    protected $casts = [
        'is_collapsible' => 'boolean',
        'is_open_by_default' => 'boolean',
        'status' => 'boolean',
        'approved_at' => 'datetime',
        'requested_at' => 'datetime',
    ];

    protected $appends = ['status_badge'];

    // ==================== RELATIONSHIPS ====================

    /**
     * Get the vendor who requested this group
     */
    public function requestedBy(): BelongsTo
    {
        return $this->belongsTo(Vendor::class, 'requested_by');
    }

    /**
     * Get the admin who approved this group
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'approved_by');
    }

    /**
     * Get the attributes in this group
     */
    public function attributes(): HasMany
    {
        return $this->hasMany(Attribute::class, 'group_id');
    }

    /**
     * Get the categories this group belongs to
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'attribute_group_category')
            ->withPivot('order')
            ->withTimestamps();
    }

    // ==================== SCOPES ====================

    public function scopeActive($query)
    {
        return $query->where('status', true)->where('approval_status', 'approved');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
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

    // ==================== BOOT ====================

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($group) {
            if (empty($group->slug)) {
                $group->slug = Str::slug($group->name);
            }
        });
    }
}