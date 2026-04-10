<?php
// app/Models/Vendor/VendorBankInfo.php

namespace App\Models\Vendor;

use Illuminate\Database\Eloquent\Model;

class ShopBankInfo extends Model
{
    protected $table = 'shop_bank_infos';

    protected $fillable = [
        'shop_id',
        'account_holder_name',
        'account_number',
        'bank_name',
        'bank_branch',
        'ifsc_code',
        'swift_code',
        'routing_number',
        'iban_number',
        'bank_address',
        'upi_id',
        'paypal_email',
        'stripe_account_id',
        'razorpay_account_id',
        'cancelled_cheque',
        'bank_statement',
    ];

    protected $casts = [];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
}
