<?php
// app/Models/VendorTaxInfo.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorTaxInfo extends Model
{
    protected $table = 'vendor_tax_infos';

    protected $fillable = [
        'vendor_id',
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
        'verification_status',
        'verified_at',
        'verified_by',
        'verification_notes'
    ];

    protected $casts = [
        'gst_registration_date' => 'date',
        'business_registration_date' => 'date',
        'verified_at' => 'datetime',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}
