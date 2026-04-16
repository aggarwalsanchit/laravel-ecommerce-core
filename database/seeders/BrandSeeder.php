<?php
// database/seeders/BrandSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        // Get all categories with their IDs and names for mapping
        $categories = Category::all();
        $categoryMap = [];
        foreach ($categories as $category) {
            $categoryMap[$category->name] = $category->id;
        }

        $brands = [
            // ==================== ELECTRONICS BRANDS ====================
            [
                'name' => 'Apple',
                'code' => 'APL',
                'description' => 'Premium electronics including iPhone, Mac, iPad, and more',
                'is_featured' => true,
                'order' => 1,
                'category_names' => ['Electronics', 'Smartphones & Tablets', 'Computers & Laptops'],
            ],
            [
                'name' => 'Samsung',
                'code' => 'SSG',
                'description' => 'Smartphones, TVs, home appliances, and electronics',
                'is_featured' => true,
                'order' => 2,
                'category_names' => ['Electronics', 'Smartphones & Tablets', 'Smart Home'],
            ],
            [
                'name' => 'Sony',
                'code' => 'SNY',
                'description' => 'Cameras, audio equipment, gaming consoles, and TVs',
                'is_featured' => true,
                'order' => 3,
                'category_names' => ['Electronics', 'Cameras & Photography', 'Audio & Headphones', 'Gaming'],
            ],
            [
                'name' => 'Dell',
                'code' => 'DEL',
                'description' => 'Laptops, desktops, monitors, and computer accessories',
                'is_featured' => false,
                'order' => 4,
                'category_names' => ['Electronics', 'Computers & Laptops'],
            ],
            [
                'name' => 'HP',
                'code' => 'HWP',
                'description' => 'Laptops, printers, and computer accessories',
                'is_featured' => false,
                'order' => 5,
                'category_names' => ['Electronics', 'Computers & Laptops'],
            ],
            [
                'name' => 'Lenovo',
                'code' => 'LNV',
                'description' => 'Laptops, tablets, and computer accessories',
                'is_featured' => false,
                'order' => 6,
                'category_names' => ['Electronics', 'Computers & Laptops'],
            ],
            [
                'name' => 'OnePlus',
                'code' => 'ONP',
                'description' => 'Smartphones, smartwatches, and audio products',
                'is_featured' => false,
                'order' => 7,
                'category_names' => ['Electronics', 'Smartphones & Tablets'],
            ],
            [
                'name' => 'Xiaomi',
                'code' => 'XIA',
                'description' => 'Smartphones, smart home devices, and accessories',
                'is_featured' => false,
                'order' => 8,
                'category_names' => ['Electronics', 'Smartphones & Tablets', 'Smart Home'],
            ],
            [
                'name' => 'Google',
                'code' => 'GGL',
                'description' => 'Pixel smartphones, smart home devices, and accessories',
                'is_featured' => true,
                'order' => 9,
                'category_names' => ['Electronics', 'Smartphones & Tablets', 'Smart Home'],
            ],
            [
                'name' => 'Bose',
                'code' => 'BOS',
                'description' => 'Premium audio equipment, headphones, and speakers',
                'is_featured' => false,
                'order' => 10,
                'category_names' => ['Electronics', 'Audio & Headphones'],
            ],
            [
                'name' => 'JBL',
                'code' => 'JBL',
                'description' => 'Speakers, headphones, and audio accessories',
                'is_featured' => false,
                'order' => 11,
                'category_names' => ['Electronics', 'Audio & Headphones'],
            ],
            [
                'name' => 'Canon',
                'code' => 'CAN',
                'description' => 'Cameras, lenses, and photography equipment',
                'is_featured' => true,
                'order' => 12,
                'category_names' => ['Electronics', 'Cameras & Photography'],
            ],
            [
                'name' => 'Nikon',
                'code' => 'NIK',
                'description' => 'Cameras, lenses, and photography accessories',
                'is_featured' => false,
                'order' => 13,
                'category_names' => ['Electronics', 'Cameras & Photography'],
            ],
            [
                'name' => 'GoPro',
                'code' => 'GPR',
                'description' => 'Action cameras and accessories',
                'is_featured' => false,
                'order' => 14,
                'category_names' => ['Electronics', 'Cameras & Photography'],
            ],
            [
                'name' => 'Microsoft',
                'code' => 'MSFT',
                'description' => 'Surface devices, Xbox gaming, and accessories',
                'is_featured' => true,
                'order' => 15,
                'category_names' => ['Electronics', 'Computers & Laptops', 'Gaming'],
            ],
            [
                'name' => 'Razer',
                'code' => 'RZR',
                'description' => 'Gaming peripherals, laptops, and accessories',
                'is_featured' => false,
                'order' => 16,
                'category_names' => ['Electronics', 'Gaming'],
            ],

            // ==================== FASHION BRANDS ====================
            [
                'name' => 'Nike',
                'code' => 'NKE',
                'description' => 'Sportswear, footwear, and accessories',
                'is_featured' => true,
                'order' => 17,
                'category_names' => ['Fashion', 'Sports & Outdoors', 'Footwear'],
            ],
            [
                'name' => 'Adidas',
                'code' => 'ADD',
                'description' => 'Sportswear, footwear, and lifestyle products',
                'is_featured' => true,
                'order' => 18,
                'category_names' => ['Fashion', 'Sports & Outdoors', 'Footwear'],
            ],
            [
                'name' => 'Puma',
                'code' => 'PUM',
                'description' => 'Sportswear, footwear, and accessories',
                'is_featured' => false,
                'order' => 19,
                'category_names' => ['Fashion', 'Sports & Outdoors'],
            ],
            [
                'name' => 'Zara',
                'code' => 'ZAR',
                'description' => 'Fast fashion clothing and accessories',
                'is_featured' => true,
                'order' => 20,
                'category_names' => ['Fashion', "Women's Fashion", "Men's Fashion"],
            ],
            [
                'name' => 'H&M',
                'code' => 'HNM',
                'description' => 'Affordable fashion for men, women, and kids',
                'is_featured' => false,
                'order' => 21,
                'category_names' => ['Fashion', "Women's Fashion", "Men's Fashion", "Kids' Fashion"],
            ],
            [
                'name' => "Levi's",
                'code' => 'LEV',
                'description' => 'Denim jeans and casual wear',
                'is_featured' => false,
                'order' => 22,
                'category_names' => ['Fashion', "Men's Fashion", "Women's Fashion"],
            ],
            [
                'name' => 'Louis Vuitton',
                'code' => 'LV',
                'description' => 'Luxury bags, accessories, and clothing',
                'is_featured' => true,
                'order' => 23,
                'category_names' => ['Fashion', 'Bags & Luggage'],
            ],
            [
                'name' => 'Gucci',
                'code' => 'GUC',
                'description' => 'Luxury fashion, bags, and accessories',
                'is_featured' => true,
                'order' => 24,
                'category_names' => ['Fashion', 'Bags & Luggage'],
            ],
            [
                'name' => 'Tommy Hilfiger',
                'code' => 'TH',
                'description' => 'American casual wear and accessories',
                'is_featured' => false,
                'order' => 25,
                'category_names' => ['Fashion', "Men's Fashion", "Women's Fashion"],
            ],
            [
                'name' => 'Calvin Klein',
                'code' => 'CK',
                'description' => 'Clothing, accessories, and fragrances',
                'is_featured' => false,
                'order' => 26,
                'category_names' => ['Fashion', "Men's Fashion", "Women's Fashion"],
            ],

            // ==================== HOME & LIVING BRANDS ====================
            [
                'name' => 'IKEA',
                'code' => 'IKA',
                'description' => 'Furniture, home decor, and kitchenware',
                'is_featured' => true,
                'order' => 27,
                'category_names' => ['Home & Living', 'Furniture', 'Home Decor'],
            ],
            [
                'name' => 'Ashley Furniture',
                'code' => 'ASH',
                'description' => 'Home furniture and decor',
                'is_featured' => false,
                'order' => 28,
                'category_names' => ['Home & Living', 'Furniture'],
            ],
            [
                'name' => 'Pepperfry',
                'code' => 'PEP',
                'description' => 'Furniture and home decor',
                'is_featured' => false,
                'order' => 29,
                'category_names' => ['Home & Living', 'Furniture', 'Home Decor'],
            ],
            [
                'name' => 'Philips',
                'code' => 'PHL',
                'description' => 'Home appliances, lighting, and personal care',
                'is_featured' => true,
                'order' => 30,
                'category_names' => ['Home & Living', 'Kitchen & Dining', 'Home Improvement'],
            ],
            [
                'name' => 'KitchenAid',
                'code' => 'KTA',
                'description' => 'Premium kitchen appliances and cookware',
                'is_featured' => false,
                'order' => 31,
                'category_names' => ['Home & Living', 'Kitchen & Dining'],
            ],
            [
                'name' => 'Tupperware',
                'code' => 'TUP',
                'description' => 'Kitchen storage and food containers',
                'is_featured' => false,
                'order' => 32,
                'category_names' => ['Home & Living', 'Kitchen & Dining'],
            ],

            // ==================== BEAUTY BRANDS ====================
            [
                'name' => "L'Oreal",
                'code' => 'LOR',
                'description' => 'Cosmetics, skincare, and haircare products',
                'is_featured' => true,
                'order' => 33,
                'category_names' => ['Beauty & Personal Care', 'Makeup', 'Skincare', 'Hair Care'],
            ],
            [
                'name' => 'Maybelline',
                'code' => 'MAY',
                'description' => 'Makeup and cosmetics',
                'is_featured' => false,
                'order' => 34,
                'category_names' => ['Beauty & Personal Care', 'Makeup'],
            ],
            [
                'name' => 'Nivea',
                'code' => 'NIV',
                'description' => 'Skincare and personal care products',
                'is_featured' => false,
                'order' => 35,
                'category_names' => ['Beauty & Personal Care', 'Skincare', 'Personal Care'],
            ],
            [
                'name' => 'Dove',
                'code' => 'DOV',
                'description' => 'Personal care and beauty products',
                'is_featured' => false,
                'order' => 36,
                'category_names' => ['Beauty & Personal Care', 'Personal Care'],
            ],

            // ==================== SPORTS BRANDS ====================
            [
                'name' => 'Under Armour',
                'code' => 'UAR',
                'description' => 'Sports apparel and accessories',
                'is_featured' => false,
                'order' => 37,
                'category_names' => ['Sports & Outdoors', 'Fitness Equipment'],
            ],
            [
                'name' => 'Reebok',
                'code' => 'REB',
                'description' => 'Fitness and sports equipment',
                'is_featured' => false,
                'order' => 38,
                'category_names' => ['Sports & Outdoors', 'Fitness Equipment', 'Footwear'],
            ],

            // ==================== BOOKS & STATIONERY BRANDS ====================
            [
                'name' => 'Penguin Random House',
                'code' => 'PRH',
                'description' => 'Books and publications',
                'is_featured' => true,
                'order' => 39,
                'category_names' => ['Books & Stationery', 'Fiction', 'Non-Fiction'],
            ],
            [
                'name' => 'HarperCollins',
                'code' => 'HPC',
                'description' => 'Books and ebooks',
                'is_featured' => false,
                'order' => 40,
                'category_names' => ['Books & Stationery', 'Fiction'],
            ],
            [
                'name' => 'Moleskine',
                'code' => 'MOL',
                'description' => 'Notebooks and journals',
                'is_featured' => false,
                'order' => 41,
                'category_names' => ['Books & Stationery', 'Stationery'],
            ],

            // ==================== AUTOMOTIVE BRANDS ====================
            [
                'name' => 'Bosch',
                'code' => 'BOSCH',
                'description' => 'Auto parts and accessories',
                'is_featured' => true,
                'order' => 42,
                'category_names' => ['Automotive', 'Car Care'],
            ],
            [
                'name' => '3M',
                'code' => 'THM',
                'description' => 'Car care and accessories',
                'is_featured' => false,
                'order' => 43,
                'category_names' => ['Automotive', 'Car Care'],
            ],

            // ==================== HEALTH & WELLNESS BRANDS ====================
            [
                'name' => 'GNC',
                'code' => 'GNC',
                'description' => 'Vitamins and supplements',
                'is_featured' => true,
                'order' => 44,
                'category_names' => ['Health & Wellness', 'Vitamins & Supplements'],
            ],
            [
                'name' => 'Fitbit',
                'code' => 'FIT',
                'description' => 'Fitness trackers and smartwatches',
                'is_featured' => true,
                'order' => 45,
                'category_names' => ['Health & Wellness', 'Fitness Trackers'],
            ],

            // ==================== BABY PRODUCTS BRANDS ====================
            [
                'name' => 'Pampers',
                'code' => 'PAM',
                'description' => 'Diapers and baby care products',
                'is_featured' => true,
                'order' => 46,
                'category_names' => ['Baby Products', 'Diapering'],
            ],
            [
                'name' => 'Graco',
                'code' => 'GRC',
                'description' => 'Baby gear and strollers',
                'is_featured' => false,
                'order' => 47,
                'category_names' => ['Baby Products', 'Baby Gear'],
            ],
            [
                'name' => 'Fisher-Price',
                'code' => 'FSP',
                'description' => 'Baby toys and play equipment',
                'is_featured' => false,
                'order' => 48,
                'category_names' => ['Baby Products', 'Toys & Games'],
            ],
        ];

        foreach ($brands as $brandData) {
            $categoryNames = $brandData['category_names'];
            unset($brandData['category_names']);

            // Generate slug from name
            $brandData['slug'] = Str::slug($brandData['name']);

            // Create brand
            $brand = Brand::create($brandData);

            // Get category IDs from category names
            $categoryIds = [];
            foreach ($categoryNames as $categoryName) {
                if (isset($categoryMap[$categoryName])) {
                    $categoryIds[] = $categoryMap[$categoryName];
                }
            }

            // Attach categories to brand_category pivot table
            if (!empty($categoryIds)) {
                foreach ($categoryIds as $categoryId) {
                    $brand->categories()->attach($categoryId, [
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            $this->command->info("Brand '{$brandData['name']}' created with " . count($categoryIds) . " categories.");
        }
    }
}
