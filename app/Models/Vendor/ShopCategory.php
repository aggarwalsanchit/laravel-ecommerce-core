<?php

namespace App\Models\Vendor;

use Illuminate\Database\Eloquent\Model;

class ShopCategory extends Model
{
    protected $fillable = [
        'shop_id',
        'category_id',
        'parent_category_id',
        'grandparent_category_id',
        'level'
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function parentCategory()
    {
        return $this->belongsTo(Category::class, 'parent_category_id');
    }

    public function grandparentCategory()
    {
        return $this->belongsTo(Category::class, 'grandparent_category_id');
    }

    // Get full path as string
    public function getFullPathAttribute()
    {
        $path = [];

        if ($this->level == 1) {
            $path[] = $this->category->name;
        } elseif ($this->level == 2) {
            $path[] = $this->parentCategory->name;
            $path[] = $this->category->name;
        } elseif ($this->level == 3) {
            $path[] = $this->grandparentCategory->name;
            $path[] = $this->parentCategory->name;
            $path[] = $this->category->name;
        }

        return implode(' → ', $path);
    }
}
