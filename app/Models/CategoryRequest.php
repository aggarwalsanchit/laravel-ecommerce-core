<?php
// app/Models/CategoryRequest.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class CategoryRequest extends Model
{
    protected $table = 'category_requests';

    protected $fillable = [
        'vendor_id',
        'requested_name',
        'requested_slug',
        'requested_parent_id',
        'description',
        'reason',
        'status',
        'admin_notes',
        'rejection_reason',
        'approved_by',
        'created_category_id',
        'approved_at'
    ];

    protected $casts = [
        'status' => 'string',
        'approved_at' => 'datetime',
    ];
    
    // ==================== RELATIONSHIPS ====================

    /**
     * Get the vendor who made the request
     */
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    /**
     * Get the requested parent category
     */
    public function requestedParent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'requested_parent_id');
    }

    /**
     * Get the admin who approved/rejected
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'approved_by');
    }

    /**
     * Get the created category (after approval)
     */
    public function createdCategory(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'created_category_id');
    }
    
    // ==================== SCOPES ====================

    /**
     * Scope for pending requests
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for approved requests
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope for rejected requests
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Scope for a specific vendor
     */
    public function scopeForVendor($query, $vendorId)
    {
        return $query->where('vendor_id', $vendorId);
    }
    
    // ==================== ACCESSORS ====================

    /**
     * Get status badge HTML
     */
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

    /**
     * Get status color
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'warning',
            'approved' => 'success',
            'rejected' => 'danger',
            default => 'secondary'
        };
    }
    
    // ==================== HELPER METHODS ====================

    /**
     * Approve the request and create category
     */
    public function approve($adminId, $categoryData = []): ?Category
    {
        // Create the category
        $category = Category::create([
            'name' => $categoryData['name'] ?? $this->requested_name,
            'slug' => $categoryData['slug'] ?? Str::slug($this->requested_name),
            'description' => $categoryData['description'] ?? $this->description,
            'parent_id' => $categoryData['parent_id'] ?? $this->requested_parent_id,
            'requested_by' => $this->vendor_id,
            'approved_by' => $adminId,
            'approved_at' => now(),
            'approval_status' => 'approved',
            'status' => true,
        ]);

        // Update the request
        $this->update([
            'status' => 'approved',
            'approved_by' => $adminId,
            'approved_at' => now(),
            'created_category_id' => $category->id,
            'admin_notes' => $categoryData['admin_notes'] ?? null
        ]);

        return $category;
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

    /**
     * Check if request is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if request is approved
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if request is rejected
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }
}
