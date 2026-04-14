<?php
// app/Models/AttributeRequest.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class AttributeRequest extends Model
{
    protected $table = 'attribute_requests';

    protected $fillable = [
        'vendor_id', 'requested_name', 'requested_slug', 'requested_type',
        'description', 'reason', 'requested_values', 'requested_category_ids',
        'requested_group_id', 'is_required', 'is_filterable',
        'status', 'admin_notes', 'rejection_reason',
        'approved_by', 'created_attribute_id', 'approved_at'
    ];

    protected $casts = [
        'requested_values' => 'array',
        'requested_category_ids' => 'array',
        'is_required' => 'boolean',
        'is_filterable' => 'boolean',
        'approved_at' => 'datetime',
    ];

    protected $appends = ['status_badge'];

    // ==================== RELATIONSHIPS ====================

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'approved_by');
    }

    public function createdAttribute(): BelongsTo
    {
        return $this->belongsTo(Attribute::class, 'created_attribute_id');
    }

    public function requestedGroup(): BelongsTo
    {
        return $this->belongsTo(AttributeGroup::class, 'requested_group_id');
    }

    // ==================== SCOPES ====================

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    // ==================== ACCESSORS ====================

    public function getStatusBadgeAttribute(): string
    {
        switch ($this->status) {
            case 'pending':
                return '<span class="badge bg-warning">Pending</span>';
            case 'approved':
                return '<span class="badge bg-success">Approved</span>';
            case 'rejected':
                return '<span class="badge bg-danger">Rejected</span>';
            default:
                return '<span class="badge bg-secondary">Unknown</span>';
        }
    }

    // ==================== HELPER METHODS ====================

    /**
     * Approve the request and create attribute
     */
    public function approve($adminId, $attributeData = []): ?Attribute
    {
        $attribute = Attribute::create([
            'name' => $attributeData['name'] ?? $this->requested_name,
            'slug' => $attributeData['slug'] ?? Str::slug($this->requested_name),
            'type' => $attributeData['type'] ?? $this->requested_type,
            'description' => $attributeData['description'] ?? $this->description,
            'is_required' => $attributeData['is_required'] ?? $this->is_required,
            'is_filterable' => $attributeData['is_filterable'] ?? $this->is_filterable,
            'group_id' => $attributeData['group_id'] ?? $this->requested_group_id,
            'requested_by' => $this->vendor_id,
            'approved_by' => $adminId,
            'approved_at' => now(),
            'approval_status' => 'approved',
            'status' => true,
        ]);
        
        // Attach to categories
        $categoryIds = $attributeData['category_ids'] ?? $this->requested_category_ids;
        if ($categoryIds) {
            $attribute->categories()->attach($categoryIds);
        }
        
        // Create values if provided
        if ($this->requested_values && in_array($this->requested_type, ['select', 'multiselect'])) {
            foreach ($this->requested_values as $value) {
                AttributeValue::create([
                    'attribute_id' => $attribute->id,
                    'value' => $value,
                    'label' => $value,
                ]);
            }
        }
        
        $this->update([
            'status' => 'approved',
            'approved_by' => $adminId,
            'approved_at' => now(),
            'created_attribute_id' => $attribute->id,
        ]);
        
        return $attribute;
    }

    /**
     * Reject the request
     */
    public function reject($adminId, $reason): void
    {
        $this->update([
            'status' => 'rejected',
            'approved_by' => $adminId,
            'approved_at' => now(),
            'rejection_reason' => $reason
        ]);
    }
}