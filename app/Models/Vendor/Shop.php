<?php

namespace App\Models\Vendor;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Category;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\Vendor;

class Shop extends Model
{
    protected $table = 'shops';

    protected $fillable = [
        'shop_name',
        'shop_slug',
        'shop_description',
        'shop_logo',
        'shop_banner',
        'shop_email',
        'shop_phone_code',
        'shop_phone',
        'shop_whatsapp',
        'shop_website',
        'shop_address',
        'shop_city',
        'shop_state',
        'shop_country',
        'shop_postal_code',
        'country_id',
        'state_id',
        'city_id',
        'phone_code',
        'vendor_type',
        'business_type',
        'account_status',
        'verified_at',
        'verification_notes',
        'verified_by',
        'total_products',
        'total_orders',
        'total_revenue',
        'total_commission',
        'average_rating',
        'total_reviews',
        'accepts_cod',
        'is_featured',
        'commission_rate',
        'profile_completed',
        'ready_for_approve',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
        'accepts_cod' => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(Vendor::class, 'shop_id');
    }

    public function owner(): HasOne
    {
        return $this->hasOne(Vendor::class, 'shop_id')->where('is_owner', true);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'shop_country');
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class, 'shop_state');
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function taxInfo(): HasOne
    {
        return $this->hasOne(ShopTaxInfo::class, 'shop_id');
    }

    public function bankInfo(): HasOne
    {
        return $this->hasOne(ShopBankInfo::class, 'shop_id');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(ShopDocument::class, 'shop_id');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'shop_categories');
    }

    public function shopCategories()
    {
        return $this->hasMany(ShopCategory::class);
    }

    public function getFormattedCategoriesAttribute()
    {
        $categories = $this->shopCategories()->with(['category', 'parentCategory', 'grandparentCategory'])->get();
        $formatted = [];

        foreach ($categories as $cat) {
            if ($cat->level == 1) {
                // Just main category
                $formatted[] = $cat->category->name;
            } elseif ($cat->level == 2) {
                // Main category > Sub category
                $formatted[] = $cat->parentCategory->name . ' → ' . $cat->category->name;
            } elseif ($cat->level == 3) {
                // Main category > Sub category > Sub Sub category
                $formatted[] = $cat->grandparentCategory->name . ' → ' . $cat->parentCategory->name . ' → ' . $cat->category->name;
            }
        }

        return $formatted;
    }

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function parentCategory()
    {
        return $this->belongsTo(Category::class, 'parent_category_id');
    }

    public function grandparentCategory()
    {
        return $this->belongsTo(Category::class, 'grandparent_category_id');
    }
}
