<?php
// app/Models/ProductColor.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductColor extends Model
{
    protected $table = 'product_colors';
    
    protected $fillable = [
        'product_id', 'color_id', 'color_image'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function color()
    {
        return $this->belongsTo(Color::class);
    }

    public function getColorImageUrlAttribute()
    {
        return $this->color_image ? Storage::disk('public')->url('products/colors/' . $this->color_image) : null;
    }
}