<?php
// app/Models/AttributeGroup.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class AttributeGroup extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'color',
        'display_order',
        'is_collapsible',
        'is_collapsed_by_default',
        'show_in_sidebar',
        'show_in_compare',
        'status'
    ];

    protected $casts = [
        'is_collapsible' => 'boolean',
        'is_collapsed_by_default' => 'boolean',
        'show_in_sidebar' => 'boolean',
        'show_in_compare' => 'boolean',
        'status' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($group) {
            $group->slug = Str::slug($group->name);
        });
    }

    public function attributes()
    {
        return $this->hasMany(Attribute::class)->orderBy('display_order');
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }
}
