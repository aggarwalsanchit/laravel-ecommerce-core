<?php
// app/Models/Admin.php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_code',
        'phone',
        'address',
        'city',
        'state_id',
        'country_id',
        'postal_code',
        'birth_date',
        'is_active',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
        'birth_date' => 'date',
    ];

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
}
