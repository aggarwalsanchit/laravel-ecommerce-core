<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            // Main Categories
            ['name' => 'Electronics', 'description' => 'Electronic devices and accessories', 'icon' => 'ti-device-laptop', 'order' => 1],
            ['name' => 'Fashion', 'description' => 'Clothing, shoes, and accessories', 'icon' => 'ti-shirt', 'order' => 2],
            ['name' => 'Home & Living', 'description' => 'Furniture, decor, and kitchen items', 'icon' => 'ti-home', 'order' => 3],
            ['name' => 'Beauty & Personal Care', 'description' => 'Cosmetics, skincare, and grooming', 'icon' => 'ti-heart', 'order' => 4],
            ['name' => 'Sports & Outdoors', 'description' => 'Sports equipment and outdoor gear', 'icon' => 'ti-trophy', 'order' => 5],
            ['name' => 'Toys & Games', 'description' => 'Toys, games, and hobbies', 'icon' => 'ti-puzzle', 'order' => 6],
            ['name' => 'Books & Stationery', 'description' => 'Books, notebooks, and office supplies', 'icon' => 'ti-book', 'order' => 7],
            ['name' => 'Health & Wellness', 'description' => 'Health products and wellness items', 'icon' => 'ti-activity', 'order' => 8],
            ['name' => 'Automotive', 'description' => 'Car accessories and parts', 'icon' => 'ti-car', 'order' => 9],
            ['name' => 'Pet Supplies', 'description' => 'Pet food, toys, and accessories', 'icon' => 'ti-paw', 'order' => 10],
            
            // Sub Categories for Electronics
            ['name' => 'Mobiles & Tablets', 'description' => 'Smartphones, tablets, and accessories', 'parent_name' => 'Electronics', 'icon' => 'ti-device-mobile', 'order' => 1],
            ['name' => 'Laptops & Computers', 'description' => 'Laptops, desktops, and computer accessories', 'parent_name' => 'Electronics', 'icon' => 'ti-device-laptop', 'order' => 2],
            ['name' => 'Audio & Headphones', 'description' => 'Headphones, speakers, and audio equipment', 'parent_name' => 'Electronics', 'icon' => 'ti-headphones', 'order' => 3],
            ['name' => 'Cameras & Photography', 'description' => 'Cameras, lenses, and photography gear', 'parent_name' => 'Electronics', 'icon' => 'ti-camera', 'order' => 4],
            
            // Sub Categories for Fashion
            ['name' => 'Men\'s Clothing', 'description' => 'Shirts, pants, suits, and more', 'parent_name' => 'Fashion', 'icon' => 'ti-man', 'order' => 1],
            ['name' => 'Women\'s Clothing', 'description' => 'Dresses, tops, skirts, and more', 'parent_name' => 'Fashion', 'icon' => 'ti-woman', 'order' => 2],
            ['name' => 'Footwear', 'description' => 'Shoes, sandals, and sneakers', 'parent_name' => 'Fashion', 'icon' => 'ti-shoe', 'order' => 3],
            ['name' => 'Accessories', 'description' => 'Bags, watches, jewelry, and more', 'parent_name' => 'Fashion', 'icon' => 'ti-bag', 'order' => 4],
            
            // Sub Categories for Home & Living
            ['name' => 'Furniture', 'description' => 'Sofas, beds, tables, and chairs', 'parent_name' => 'Home & Living', 'icon' => 'ti-sofa', 'order' => 1],
            ['name' => 'Home Decor', 'description' => 'Wall art, mirrors, and decorative items', 'parent_name' => 'Home & Living', 'icon' => 'ti-wallpaper', 'order' => 2],
            ['name' => 'Kitchen & Dining', 'description' => 'Cookware, utensils, and dinnerware', 'parent_name' => 'Home & Living', 'icon' => 'ti-kitchen', 'order' => 3],
            ['name' => 'Bedding & Bath', 'description' => 'Sheets, towels, and bathroom accessories', 'parent_name' => 'Home & Living', 'icon' => 'ti-bed', 'order' => 4],
        ];
        
        $categoriesById = [];
        
        foreach ($categories as $cat) {
            if (isset($cat['parent_name'])) {
                $parentId = $categoriesById[$cat['parent_name']] ?? null;
                if ($parentId) {
                    Category::create([
                        'name' => $cat['name'],
                        'slug' => Str::slug($cat['name']),
                        'description' => $cat['description'],
                        'parent_id' => $parentId,
                        'icon' => $cat['icon'],
                        'order' => $cat['order'],
                        'status' => true,
                        'show_in_menu' => true,
                    ]);
                }
            } else {
                $category = Category::create([
                    'name' => $cat['name'],
                    'slug' => Str::slug($cat['name']),
                    'description' => $cat['description'],
                    'parent_id' => null,
                    'icon' => $cat['icon'],
                    'order' => $cat['order'],
                    'status' => true,
                    'show_in_menu' => true,
                ]);
                $categoriesById[$cat['name']] = $category->id;
            }
        }
    }
}