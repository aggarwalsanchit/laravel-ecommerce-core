<?php
// app/Models/VendorBankInfo.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorBankInfo extends Model
{
    protected $table = 'vendor_bank_infos';

    protected $fillable = [
        'vendor_id',
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
        'verification_status',
        'verified_at',
        'verified_by',
        'verification_notes'
    ];

    protected $casts = [
        'verified_at' => 'datetime',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}
