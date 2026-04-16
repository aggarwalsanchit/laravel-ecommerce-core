<?php
// app/Models/BrandRequest.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BrandRequest extends Model
{
    protected $table = 'brand_requests';

    protected $fillable = [
        'vendor_id',
        'requested_name',
        'requested_slug',
        'requested_code',
        'description',
        'reason',
        'requested_category_ids',
        'logo',
        'status',
        'rejection_reason',
        'approved_by',
        'approved_at',
        'admin_notes',
        'created_brand_id'
    ];

    protected $casts = [
        'requested_category_ids' => 'array',
        'approved_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the vendor who made the request
     */
    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    /**
     * Get the admin who approved/rejected the request
     */
    public function approvedBy()
    {
        return $this->belongsTo(Admin::class, 'approved_by');
    }

    /**
     * Get the created brand from this request
     */
    public function createdBrand()
    {
        return $this->belongsTo(Brand::class, 'created_brand_id');
    }

    /**
     * Get requested categories
     */
    public function getRequestedCategoriesAttribute()
    {
        if (empty($this->requested_category_ids)) {
            return collect([]);
        }

        return Category::whereIn('id', $this->requested_category_ids)->get();
    }

    /**
     * Get status badge HTML
     */
    public function getStatusBadgeAttribute()
    {
        switch ($this->status) {
            case 'pending':
                return '<span class="badge bg-warning"><i class="ti ti-clock"></i> Pending</span>';
            case 'approved':
                return '<span class="badge bg-success"><i class="ti ti-check"></i> Approved</span>';
            case 'rejected':
                return '<span class="badge bg-danger"><i class="ti ti-x"></i> Rejected</span>';
            default:
                return '<span class="badge bg-secondary">Unknown</span>';
        }
    }
}
