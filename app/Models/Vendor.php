<?php
// app/Models/Vendor.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vendor extends Authenticatable
{

    use HasFactory, Notifiable, HasRoles;

    protected $guard = 'vendor';

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'avatar',
        'shop_name',
        'shop_slug',
        'shop_description',
        'shop_logo',
        'shop_banner',
        'shop_email',
        'shop_phone',
        'shop_whatsapp',
        'shop_website',
        'shop_address',
        'shop_city',
        'shop_state',
        'shop_country',
        'shop_postal_code',
        'vendor_type',
        'business_type',
        'account_status',
        'profile_completed',
        'last_login_at',
        'verification_status',
        'verification_notes',
        'verified_by',
        'verified_at',
        'total_products',
        'total_orders',
        'total_revenue',
        'total_commission',
        'average_rating',
        'total_reviews',
        'accepts_cod',
        'is_featured',
        'commission_rate',
        'user_id'
    ];

    protected $casts = [
        'verified_at' => 'datetime',
        'suspended_at' => 'datetime',
        'accepts_cod' => 'boolean',
        'is_featured' => 'boolean',
        'total_revenue' => 'decimal:2',
        'total_commission' => 'decimal:2',
        'average_rating' => 'decimal:2',
        'commission_rate' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($vendor) {
            $vendor->shop_slug = Str::slug($vendor->shop_name);
        });
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function taxInfo()
    {
        return $this->hasOne(VendorTaxInfo::class);
    }

    public function bankInfo()
    {
        return $this->hasOne(VendorBankInfo::class);
    }

    public function staff()
    {
        return $this->hasMany(VendorStaff::class);
    }

    public function documents()
    {
        return $this->hasMany(VendorDocument::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'vendor_categories');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function commissionLogs()
    {
        return $this->hasMany(CommissionLog::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('account_status', 'active');
    }

    public function scopeVerified($query)
    {
        return $query->where('verification_status', 'verified');
    }

    public function scopeOwnStore($query)
    {
        return $query->where('vendor_type', 'own_store');
    }

    public function scopeThirdParty($query)
    {
        return $query->where('vendor_type', 'third_party');
    }

    // Helper Methods
    public function isOwnStore()
    {
        return $this->vendor_type === 'own_store';
    }

    public function isThirdParty()
    {
        return $this->vendor_type === 'third_party';
    }

    public function isVerified()
    {
        return $this->verification_status === 'verified';
    }

    public function isActive()
    {
        return $this->account_status === 'active';
    }

    public function getCommissionRateAttribute()
    {
        if ($this->isOwnStore()) {
            return 0; // Own store pays no commission
        }
        return $this->attributes['commission_rate'] ?? 10;
    }

    public function getLogoUrlAttribute()
    {
        return $this->shop_logo ? Storage::url($this->shop_logo) : null;
    }

    public function getBannerUrlAttribute()
    {
        return $this->shop_banner ? Storage::url($this->shop_banner) : null;
    }
}
