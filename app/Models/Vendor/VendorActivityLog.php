<?php

namespace App\Models\Vendor;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VendorActivityLog extends Model
{
    protected $table = 'vendor_activity_logs';

    protected $fillable = [
        'shop_id',
        'vendor_id',
        'action',
        'entity_type',
        'entity_id',
        'entity_name',
        'old_values',
        'new_values',
        'description',
        'ip_address',
        'user_agent',
        'device',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
    ];

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    // Scope for filtering
    public function scopeForVendor($query, $vendorId)
    {
        return $query->where('vendor_id', $vendorId);
    }

    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    public function scopeByEntity($query, $entityType)
    {
        return $query->where('entity_type', $entityType);
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    // Get action badge color
    public function getActionBadgeClassAttribute()
    {
        return match ($this->action) {
            'create' => 'success',
            'update' => 'info',
            'delete' => 'danger',
            'login' => 'primary',
            'logout' => 'secondary',
            'upload' => 'warning',
            'download' => 'dark',
            'export' => 'success',
            default => 'secondary',
        };
    }

    // Get human readable action
    public function getHumanActionAttribute()
    {
        $actions = [
            'create' => 'Created',
            'update' => 'Updated',
            'delete' => 'Deleted',
            'login' => 'Logged In',
            'logout' => 'Logged Out',
            'upload' => 'Uploaded',
            'download' => 'Downloaded',
            'export' => 'Exported',
            'change_password' => 'Changed Password',
            'update_profile' => 'Updated Profile',
            'upload_avatar' => 'Uploaded Avatar',
            'complete_profile' => 'Completed Profile',
        ];

        return $actions[$this->action] ?? ucfirst($this->action);
    }
}
