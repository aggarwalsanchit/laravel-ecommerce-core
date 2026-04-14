<?php
// app/Models/CategorySize.php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CategorySize extends Pivot
{
    protected $table = 'category_size';

    protected $fillable = [
        'category_id',
        'size_id',
        'order'
    ];

    protected $casts = [
        'order' => 'integer'
    ];

    /**
     * Get the category
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the size
     */
    public function size()
    {
        return $this->belongsTo(Size::class);
    }
}
