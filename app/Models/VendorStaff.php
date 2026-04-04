<?php
// app/Models/VendorStaff.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorStaff extends Model
{
    protected $fillable = [
        'vendor_id',
        'user_id',
        'role',
        'custom_permissions',
        'is_active',
        'last_login_at'
    ];

    protected $casts = [
        'custom_permissions' => 'array',
        'is_active' => 'boolean',
        'last_login_at' => 'datetime',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
