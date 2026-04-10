<?php
// app/Models/Vendor/VendorTaxInfo.php

namespace App\Models\Vendor;

use Illuminate\Database\Eloquent\Model;

class ShopTaxInfo extends Model
{
    protected $table = 'shop_tax_infos';

    protected $fillable = [
        'shop_id',
        'gst_number',
        'gst_type',
        'gst_registration_date',
        'gst_certificate',
        'pan_number',
        'pan_card_document',
        'pan_holder_name',
        'vat_number',
        'ein_number',
        'tax_id',
        'business_registration_number',
        'business_license_number',
        'business_registration_date',
        'business_registration_certificate',
    ];

    protected $casts = [
        'gst_registration_date' => 'date',
        'business_registration_date' => 'date',
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
}
