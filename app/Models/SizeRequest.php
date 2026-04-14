<?php
// app/Models/SizeRequest.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class SizeRequest extends Model
{
    protected $table = 'size_requests';

    protected $fillable = [
        'vendor_id',
        'requested_name',
        'requested_slug',
        'requested_code',
        'requested_gender',
        'requested_category_ids',

        // Measurements
        'requested_chest',
        'requested_waist',
        'requested_hip',
        'requested_inseam',
        'requested_shoulder',
        'requested_sleeve',
        'requested_neck',
        'requested_height',
        'requested_weight',

        // International conversions
        'requested_us_size',
        'requested_uk_size',
        'requested_eu_size',
        'requested_au_size',
        'requested_jp_size',
        'requested_cn_size',
        'requested_int_size',

        'description',
        'reason',
        'image',
        'status',
        'admin_notes',
        'rejection_reason',
        'approved_by',
        'created_size_id',
        'approved_at'
    ];

    protected $casts = [
        'status' => 'string',
        'requested_category_ids' => 'array',
        'requested_chest' => 'decimal:2',
        'requested_waist' => 'decimal:2',
        'requested_hip' => 'decimal:2',
        'requested_inseam' => 'decimal:2',
        'requested_shoulder' => 'decimal:2',
        'requested_sleeve' => 'decimal:2',
        'requested_neck' => 'decimal:2',
        'requested_height' => 'decimal:2',
        'requested_weight' => 'decimal:2',
        'approved_at' => 'datetime',
    ];

    protected $appends = [
        'status_badge',
        'measurement_summary'
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
     * Get the created size (after approval)
     */
    public function createdSize(): BelongsTo
    {
        return $this->belongsTo(Size::class, 'created_size_id');
    }

    /**
     * Get the requested categories
     */
    public function requestedCategories()
    {
        if ($this->requested_category_ids) {
            return Category::whereIn('id', $this->requested_category_ids)->get();
        }
        return collect();
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
     * Get measurement summary
     */
    public function getMeasurementSummaryAttribute(): string
    {
        $measurements = [];
        if ($this->requested_chest) $measurements[] = "Chest: {$this->requested_chest}\"";
        if ($this->requested_waist) $measurements[] = "Waist: {$this->requested_waist}\"";
        if ($this->requested_hip) $measurements[] = "Hip: {$this->requested_hip}\"";
        if ($this->requested_inseam) $measurements[] = "Inseam: {$this->requested_inseam}\"";

        return !empty($measurements) ? implode(' | ', $measurements) : 'No measurements provided';
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

        $color = $colors[$this->requested_gender] ?? 'secondary';
        return "<span class='badge bg-{$color}'>{$this->requested_gender}</span>";
    }

    // ==================== HELPER METHODS ====================

    /**
     * Approve the request and create size
     */
    public function approve($adminId, $sizeData = []): ?Size
    {
        // Create the size
        $size = Size::create([
            'name' => $sizeData['name'] ?? $this->requested_name,
            'slug' => $sizeData['slug'] ?? Str::slug($this->requested_name),
            'code' => $sizeData['code'] ?? $this->requested_code,
            'gender' => $sizeData['gender'] ?? $this->requested_gender,
            'chest' => $sizeData['chest'] ?? $this->requested_chest,
            'waist' => $sizeData['waist'] ?? $this->requested_waist,
            'hip' => $sizeData['hip'] ?? $this->requested_hip,
            'inseam' => $sizeData['inseam'] ?? $this->requested_inseam,
            'shoulder' => $sizeData['shoulder'] ?? $this->requested_shoulder,
            'sleeve' => $sizeData['sleeve'] ?? $this->requested_sleeve,
            'neck' => $sizeData['neck'] ?? $this->requested_neck,
            'height' => $sizeData['height'] ?? $this->requested_height,
            'weight' => $sizeData['weight'] ?? $this->requested_weight,
            'us_size' => $sizeData['us_size'] ?? $this->requested_us_size,
            'uk_size' => $sizeData['uk_size'] ?? $this->requested_uk_size,
            'eu_size' => $sizeData['eu_size'] ?? $this->requested_eu_size,
            'au_size' => $sizeData['au_size'] ?? $this->requested_au_size,
            'jp_size' => $sizeData['jp_size'] ?? $this->requested_jp_size,
            'cn_size' => $sizeData['cn_size'] ?? $this->requested_cn_size,
            'int_size' => $sizeData['int_size'] ?? $this->requested_int_size,
            'description' => $sizeData['description'] ?? $this->description,
            'image' => $sizeData['image'] ?? $this->image,
            'requested_by' => $this->vendor_id,
            'approved_by' => $adminId,
            'approved_at' => now(),
            'approval_status' => 'approved',
            'status' => true,
            'order' => Size::max('order') + 1,
        ]);

        // Attach to categories
        if ($this->requested_category_ids && !empty($sizeData['category_ids'] ?? $this->requested_category_ids)) {
            $categoryIds = $sizeData['category_ids'] ?? $this->requested_category_ids;
            $size->categories()->attach($categoryIds);
        }

        // Update the request
        $this->update([
            'status' => 'approved',
            'approved_by' => $adminId,
            'approved_at' => now(),
            'created_size_id' => $size->id,
            'admin_notes' => $sizeData['admin_notes'] ?? null
        ]);

        return $size;
    }

    /**
     * Reject the request
     */
    public function reject($adminId, $reason, $adminNotes = null): void
    {
        $this->update([
            'status' => 'rejected',
            'approved_by' => $adminId,
            'approved_at' => now(),
            'rejection_reason' => $reason,
            'admin_notes' => $adminNotes
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
