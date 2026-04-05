<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VendorCategory extends Model
{
    protected $table = 'vendor_categories';
    
    protected $fillable = [
        'vendor_id',
        'category_id',
    ];
    
    public $timestamps = true;
    
    /**
     * Get the vendor that owns this category relationship
     */
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }
    
    /**
     * Get the category that belongs to this vendor
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}