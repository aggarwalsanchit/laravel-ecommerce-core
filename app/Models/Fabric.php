<?php
// app/Models/Fabric.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Fabric extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'code',
        'description',
        'image',
        'order',
        'status',
        'product_count',
    ];

    protected $casts = [
        'status' => 'boolean',
        'product_count' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($fabric) {
            $fabric->slug = Str::slug($fabric->name);
        });

        static::updating(function ($fabric) {
            if ($fabric->isDirty('name')) {
                $fabric->slug = Str::slug($fabric->name);
            }
        });
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
