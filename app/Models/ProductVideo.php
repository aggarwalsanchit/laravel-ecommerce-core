<?php
// app/Models/ProductVideo.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVideo extends Model
{
    protected $fillable = [
        'product_id', 'url', 'title', 'description', 'thumbnail', 'is_main', 'sort_order'
    ];

    protected $casts = [
        'is_main' => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getEmbedUrlAttribute(): string
    {
        // Convert YouTube URL to embed format
        if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([\w-]+)/', $this->url, $matches)) {
            return 'https://www.youtube.com/embed/' . $matches[1];
        }
        return $this->url;
    }
}