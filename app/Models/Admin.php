<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Laravel\Sanctum\HasApiTokens;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $guard_name = 'admin';

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_active',
        'phone',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token', // Make sure this column exists in your admins table
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    // Scope for active users
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Get avatar URL
    public function getAvatarUrlAttribute()
    {
        return $this->avatar
            ? asset('storage/avatars/' . $this->avatar)
            : 'https://ui-avatars.com/api/?name=' . urlencode($this->name);
    }
}
