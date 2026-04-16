<?php
// app/Models/ProductQuestion.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ProductQuestion extends Model
{
    protected $fillable = [
        'product_id', 'user_id', 'guest_name', 'guest_email',
        'question', 'is_answered', 'is_approved'
    ];

    protected $casts = [
        'is_answered' => 'boolean',
        'is_approved' => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function answer(): HasOne
    {
        return $this->hasOne(ProductAnswer::class, 'question_id');
    }
}