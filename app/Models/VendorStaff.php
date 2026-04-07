<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class VendorStaff extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $table = 'vendor_staff';

    protected $guard_name = 'vendor';

    protected $fillable = [
        'vendor_id',
        'user_id',
        'name',
        'email',
        'password',
        'phone',
        'avatar',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'role',
        'custom_permissions',
        'is_active',
        'email_verified_at',
        'last_login_at',
        'last_login_ip',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'custom_permissions' => 'array',
        'is_active' => 'boolean',
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
    ];

    /**
     * Get the vendor that owns the staff
     */
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    /**
     * Get the main user account
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if staff has specific permission
     */
    public function hasCustomPermission($permission): bool
    {
        // Admin has all permissions
        if ($this->role === 'admin') {
            return true;
        }

        $customPermissions = $this->custom_permissions ?? [];

        return in_array($permission, $customPermissions);
    }

    /**
     * Check if staff has any of the given roles
     */
    public function hasAnyRole($roles): bool
    {
        if (is_string($roles)) {
            $roles = [$roles];
        }

        return in_array($this->role, $roles);
    }

    /**
     * Get role badge class
     */
    public function getRoleBadgeClassAttribute(): string
    {
        return match ($this->role) {
            'admin' => 'danger',
            'manager' => 'warning',
            'inventory' => 'info',
            'fulfillment' => 'success',
            'support' => 'primary',
            default => 'secondary',
        };
    }

    /**
     * Get role label
     */
    public function getRoleLabelAttribute(): string
    {
        return match ($this->role) {
            'admin' => 'Administrator',
            'manager' => 'Store Manager',
            'inventory' => 'Inventory Manager',
            'fulfillment' => 'Fulfillment Executive',
            'support' => 'Customer Support',
            default => ucfirst($this->role),
        };
    }

    /**
     * Get role icon
     */
    public function getRoleIconAttribute(): string
    {
        return match ($this->role) {
            'admin' => 'ti ti-shield',
            'manager' => 'ti ti-briefcase',
            'inventory' => 'ti ti-package',
            'fulfillment' => 'ti ti-truck',
            'support' => 'ti ti-headphones',
            default => 'ti ti-user',
        };
    }

    /**
     * Get default permissions by role
     */
    public function getDefaultPermissionsByRole(): array
    {
        $permissions = [
            'admin' => [
                'view_dashboard',
                'view_profile',
                'update_profile',
                'change_password',
                'upload_avatar',
                'view_products',
                'create_products',
                'edit_products',
                'delete_products',
                'view_orders',
                'update_order_status',
                'cancel_orders',
                'view_reports',
                'view_analytics',
                'view_staff',
                'create_staff',
                'edit_staff',
                'delete_staff',
                'manage_staff',
                'manage_store_settings',
                'manage_payment_settings',
                'manage_shipping_settings',
            ],
            'manager' => [
                'view_dashboard',
                'view_profile',
                'update_profile',
                'change_password',
                'upload_avatar',
                'view_products',
                'create_products',
                'edit_products',
                'delete_products',
                'view_orders',
                'update_order_status',
                'cancel_orders',
                'view_reports',
                'view_analytics',
                'manage_store_settings',
            ],
            'inventory' => [
                'view_dashboard',
                'view_profile',
                'update_profile',
                'change_password',
                'upload_avatar',
                'view_products',
                'create_products',
                'edit_products',
                'delete_products',
                'view_orders',
            ],
            'fulfillment' => [
                'view_dashboard',
                'view_profile',
                'update_profile',
                'change_password',
                'upload_avatar',
                'view_orders',
                'update_order_status',
            ],
            'support' => [
                'view_dashboard',
                'view_profile',
                'update_profile',
                'change_password',
                'upload_avatar',
                'view_orders',
            ],
        ];

        return $permissions[$this->role] ?? [];
    }

    /**
     * Sync permissions based on role
     */
    public function syncPermissionsByRole(): void
    {
        $permissions = $this->getDefaultPermissionsByRole();

        // If custom permissions exist, merge with default
        if ($this->custom_permissions && $this->role !== 'admin') {
            $permissions = array_merge($permissions, $this->custom_permissions);
        }

        $this->syncPermissions($permissions);
    }
}
