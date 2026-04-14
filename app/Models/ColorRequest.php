<?php
// app/Models/ColorRequest.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ColorRequest extends Model
{
    protected $table = 'color_requests';

    protected $fillable = [
        'vendor_id',
        'requested_name',
        'requested_slug',
        'requested_code',
        'requested_rgb',
        'requested_hsl',
        'description',
        'reason',
        'image',
        'status',
        'admin_notes',
        'rejection_reason',
        'approved_by',
        'created_color_id',
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
     * Get the admin who approved/rejected
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'approved_by');
    }

    /**
     * Get the created color (after approval)
     */
    public function createdColor(): BelongsTo
    {
        return $this->belongsTo(Color::class, 'created_color_id');
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

    /**
     * Get color preview for requested color
     */
    public function getColorPreviewAttribute(): string
    {
        return "<div style='background-color: {$this->requested_code}; width: 30px; height: 30px; border-radius: 50%; display: inline-block; border: 1px solid #ddd;'></div>";
    }

    // ==================== HELPER METHODS ====================

    /**
     * Approve the request and create color
     */
    public function approve($adminId, $colorData = []): ?Color
    {
        // Create the color
        $color = Color::create([
            'name' => $colorData['name'] ?? $this->requested_name,
            'slug' => $colorData['slug'] ?? Str::slug($this->requested_name),
            'code' => $colorData['code'] ?? $this->requested_code,
            'rgb' => $colorData['rgb'] ?? $this->requested_rgb,
            'hsl' => $colorData['hsl'] ?? $this->requested_hsl,
            'description' => $colorData['description'] ?? $this->description,
            'image' => $colorData['image'] ?? $this->image,
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
            'created_color_id' => $color->id,
            'admin_notes' => $colorData['admin_notes'] ?? null
        ]);

        return $color;
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
