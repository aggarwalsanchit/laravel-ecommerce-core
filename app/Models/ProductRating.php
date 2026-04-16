<?php
// app/Models/ProductRating.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductRating extends Model
{
    protected $fillable = [
        'product_id', 'user_id', 'guest_name', 'guest_email',
        'rating', 'comment', 'admin_reply', 'replied_by', 'replied_at',
        'is_approved', 'images'
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_approved' => 'boolean',
        'images' => 'array',
        'replied_at' => 'datetime',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function repliedBy(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'replied_by');
    }
}