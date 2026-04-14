<?php
// app/Models/AttributeValueRequest.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttributeValueRequest extends Model
{
    protected $table = 'attribute_value_requests';

    protected $fillable = [
        'vendor_id', 'attribute_id', 'requested_value', 'requested_label',
        'requested_color_code', 'requested_image', 'reason',
        'status', 'admin_notes', 'rejection_reason',
        'approved_by', 'created_value_id', 'approved_at'
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    protected $appends = ['status_badge'];

    // ==================== RELATIONSHIPS ====================

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function attribute(): BelongsTo
    {
        return $this->belongsTo(Attribute::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'approved_by');
    }

    public function createdValue(): BelongsTo
    {
        return $this->belongsTo(AttributeValue::class, 'created_value_id');
    }

    // ==================== SCOPES ====================

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
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
     * Approve the request and create attribute value
     */
    public function approve($adminId, $valueData = []): ?AttributeValue
    {
        $attributeValue = AttributeValue::create([
            'attribute_id' => $this->attribute_id,
            'value' => $valueData['value'] ?? $this->requested_value,
            'label' => $valueData['label'] ?? $this->requested_label,
            'color_code' => $valueData['color_code'] ?? $this->requested_color_code,
            'image' => $valueData['image'] ?? $this->requested_image,
            'status' => true,
        ]);
        
        $this->update([
            'status' => 'approved',
            'approved_by' => $adminId,
            'approved_at' => now(),
            'created_value_id' => $attributeValue->id,
        ]);
        
        return $attributeValue;
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