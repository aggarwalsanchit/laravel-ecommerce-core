<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Storage;

class Vendor extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, CanResetPassword;

    protected $table = 'vendors';

    protected $guard_name = 'vendor';

    protected $fillable = [
        'shop_id',
        'user_id',
        'name',
        'email',
        'password',
        'phone_code',
        'phone',
        'avatar',
        'address',
        'city',
        'state_id',
        'country_id',
        'postal_code',
        'vendor_role',
        'custom_permissions',
        'is_active',
        'email_verified_at',
        'last_login_at',
        'last_login_ip',
        'birth_date'
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
        'birth_date' => 'date',
    ];

    /**
     * Get the vendor that owns the staff
     */
    public function shop(): BelongsTo
    {
        return $this->belongsTo(Vendor\Shop::class);
    }

    /**
     * Get the main user account
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    public function getFullPhoneAttribute()
    {
        if ($this->phone_code && $this->phone) {
            return $this->phone_code . $this->phone;
        }
        return $this->phone;
    }

    // Accessor for avatar URL
    public function getAvatarUrlAttribute()
    {
        if ($this->avatar && Storage::disk('public')->exists($this->avatar)) {
            return Storage::url($this->avatar);
        }
        return asset('dummy-avatar.jpg');
    }

    /**
     * Get permissions grouped by module.
     */
    public function getPermissionsByModule()
    {
        $permissions = $this->getAllPermissions()->pluck('name')->toArray();
        $grouped = [];

        foreach ($permissions as $permission) {
            $parts = explode(' ', $permission);
            $module = $parts[1] ?? 'general';
            if (!isset($grouped[$module])) {
                $grouped[$module] = [];
            }
            $grouped[$module][] = $permission;
        }

        return $grouped;
    }

    /**
     * Get formatted join date.
     */
    public function getFormattedJoinDate()
    {
        return $this->created_at->format('F d, Y');
    }

    /**
     * Get user status with badge HTML.
     */
    public function getStatusBadge()
    {
        if ($this->is_active) {
            return '<span class="badge bg-success-subtle text-success">
                    <i class="ti ti-circle-check me-1"></i>Active
                </span>';
        }

        return '<span class="badge bg-danger-subtle text-danger">
                <i class="ti ti-circle-x me-1"></i>Inactive
            </span>';
    }

    /**
     * Check if user has any permissions.
     */
    public function hasAnyPermission()
    {
        return $this->getAllPermissions()->count() > 0;
    }

    /**
     * Get permission count.
     */
    public function getPermissionCount()
    {
        return $this->getAllPermissions()->count();
    }

    public function getFullAddressAttribute()
    {
        $parts = [];
        if ($this->address) $parts[] = $this->address;
        if ($this->city) $parts[] = $this->city;
        if ($this->state) $parts[] = $this->state->name;
        if ($this->country) $parts[] = $this->country->name;
        if ($this->postal_code) $parts[] = $this->postal_code;

        return implode(', ', $parts);
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \App\Notifications\VendorResetPasswordNotification($token));
    }
}
