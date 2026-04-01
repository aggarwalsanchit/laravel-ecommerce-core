<?php
// app/Models/AttributeCategory.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class AttributeCategory extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'parent_id',
        'depth',
        'path',
        'display_order',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($category) {
            $category->slug = Str::slug($category->name);
            $category->depth = $category->parent ? $category->parent->depth + 1 : 0;
            $category->path = $category->parent ? $category->parent->path . '/' . $category->slug : $category->slug;
        });
    }

    public function parent()
    {
        return $this->belongsTo(AttributeCategory::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(AttributeCategory::class, 'parent_id')->orderBy('display_order');
    }

    public function attributes()
    {
        return $this->hasMany(Attribute::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }
}
