<?php

namespace App\Models\Vendor;

use Illuminate\Database\Eloquent\Model;

class BusinessType extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'is_active', 'sort_order'];
}
