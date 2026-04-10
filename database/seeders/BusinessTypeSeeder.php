<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vendor\BusinessType;

class BusinessTypeSeeder extends Seeder
{
    public function run()
    {
        $businessTypes = [
            ['name' => 'Sole Proprietorship', 'slug' => 'sole_proprietorship', 'sort_order' => 1],
            ['name' => 'Partnership', 'slug' => 'partnership', 'sort_order' => 2],
            ['name' => 'Limited Liability Company (LLC)', 'slug' => 'llc', 'sort_order' => 3],
            ['name' => 'Private Limited Company', 'slug' => 'private_limited', 'sort_order' => 4],
            ['name' => 'Public Limited Company', 'slug' => 'public_limited', 'sort_order' => 5],
            ['name' => 'Trust / NGO', 'slug' => 'trust', 'sort_order' => 6],
            ['name' => 'Other', 'slug' => 'other', 'sort_order' => 7],
        ];

        foreach ($businessTypes as $type) {
            BusinessType::create($type);
        }
    }
}
