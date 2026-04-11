<?php
// app/Models/Vendor/VendorDocument.php

namespace App\Models\Vendor;

use Illuminate\Database\Eloquent\Model;

class ShopDocument extends Model
{
    protected $table = 'shop_documents';

    protected $fillable = [
        'shop_id',
        'document_type',
        'document_name',
        'document_path',
        'document_number',
        'notes',
    ];

    protected $casts = [];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function getDocumentUrlAttribute()
    {
        return $this->document_path ? Storage::url($this->document_path) : null;
    }
}
