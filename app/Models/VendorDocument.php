<?php
// app/Models/VendorDocument.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorDocument extends Model
{
    protected $fillable = [
        'vendor_id',
        'document_type',
        'document_name',
        'document_path',
        'document_number',
        'notes',
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

    public function getDocumentUrlAttribute()
    {
        return $this->document_path ? Storage::url($this->document_path) : null;
    }
}
