<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            // Electronics & Gadgets
            ['name' => 'Electronics', 'slug' => 'electronics'],
            ['name' => 'Smartphones', 'slug' => 'smartphones'],
            ['name' => 'Laptops', 'slug' => 'laptops'],
            ['name' => 'Tablets', 'slug' => 'tablets'],
            ['name' => 'Accessories', 'slug' => 'accessories'],
            ['name' => 'Headphones', 'slug' => 'headphones'],
            ['name' => 'Speakers', 'slug' => 'speakers'],
            ['name' => 'Smartwatches', 'slug' => 'smartwatches'],
            ['name' => 'Cameras', 'slug' => 'cameras'],
            ['name' => 'Gaming', 'slug' => 'gaming'],

            // Fashion & Clothing
            ['name' => 'Fashion', 'slug' => 'fashion'],
            ['name' => 'Men Fashion', 'slug' => 'men-fashion'],
            ['name' => 'Women Fashion', 'slug' => 'women-fashion'],
            ['name' => 'Kids Fashion', 'slug' => 'kids-fashion'],
            ['name' => 'Shirts', 'slug' => 'shirts'],
            ['name' => 'T-Shirts', 'slug' => 't-shirts'],
            ['name' => 'Jeans', 'slug' => 'jeans'],
            ['name' => 'Shoes', 'slug' => 'shoes'],
            ['name' => 'Sneakers', 'slug' => 'sneakers'],
            ['name' => 'Sandals', 'slug' => 'sandals'],
            ['name' => 'Watches', 'slug' => 'watches'],
            ['name' => 'Sunglasses', 'slug' => 'sunglasses'],
            ['name' => 'Bags', 'slug' => 'bags'],
            ['name' => 'Jewelry', 'slug' => 'jewelry'],

            // Home & Living
            ['name' => 'Home & Living', 'slug' => 'home-living'],
            ['name' => 'Furniture', 'slug' => 'furniture'],
            ['name' => 'Sofas', 'slug' => 'sofas'],
            ['name' => 'Beds', 'slug' => 'beds'],
            ['name' => 'Tables', 'slug' => 'tables'],
            ['name' => 'Chairs', 'slug' => 'chairs'],
            ['name' => 'Home Decor', 'slug' => 'home-decor'],
            ['name' => 'Lighting', 'slug' => 'lighting'],
            ['name' => 'Kitchen', 'slug' => 'kitchen'],
            ['name' => 'Cookware', 'slug' => 'cookware'],
            ['name' => 'Appliances', 'slug' => 'appliances'],

            // Beauty & Health
            ['name' => 'Beauty', 'slug' => 'beauty'],
            ['name' => 'Skincare', 'slug' => 'skincare'],
            ['name' => 'Makeup', 'slug' => 'makeup'],
            ['name' => 'Hair Care', 'slug' => 'hair-care'],
            ['name' => 'Perfumes', 'slug' => 'perfumes'],
            ['name' => 'Health', 'slug' => 'health'],
            ['name' => 'Fitness', 'slug' => 'fitness'],
            ['name' => 'Supplements', 'slug' => 'supplements'],

            // Sports & Outdoors
            ['name' => 'Sports', 'slug' => 'sports'],
            ['name' => 'Outdoors', 'slug' => 'outdoors'],
            ['name' => 'Camping', 'slug' => 'camping'],
            ['name' => 'Hiking', 'slug' => 'hiking'],
            ['name' => 'Cycling', 'slug' => 'cycling'],
            ['name' => 'Fitness Equipment', 'slug' => 'fitness-equipment'],

            // Books & Media
            ['name' => 'Books', 'slug' => 'books'],
            ['name' => 'Best Sellers', 'slug' => 'best-sellers'],
            ['name' => 'New Arrivals', 'slug' => 'new-arrivals'],
            ['name' => 'Fiction', 'slug' => 'fiction'],
            ['name' => 'Non-Fiction', 'slug' => 'non-fiction'],
            ['name' => 'Educational', 'slug' => 'educational'],
            ['name' => 'Magazines', 'slug' => 'magazines'],

            // Automotive
            ['name' => 'Automotive', 'slug' => 'automotive'],
            ['name' => 'Car Accessories', 'slug' => 'car-accessories'],
            ['name' => 'Bike Accessories', 'slug' => 'bike-accessories'],
            ['name' => 'Spare Parts', 'slug' => 'spare-parts'],

            // Seasonal & Promotional
            ['name' => 'Summer Sale', 'slug' => 'summer-sale'],
            ['name' => 'Winter Sale', 'slug' => 'winter-sale'],
            ['name' => 'Black Friday', 'slug' => 'black-friday'],
            ['name' => 'Cyber Monday', 'slug' => 'cyber-monday'],
            ['name' => 'Christmas', 'slug' => 'christmas'],
            ['name' => 'New Year', 'slug' => 'new-year'],
            ['name' => 'Diwali Special', 'slug' => 'diwali-special'],
            ['name' => 'Eid Special', 'slug' => 'eid-special'],
            ['name' => 'Limited Edition', 'slug' => 'limited-edition'],

            // Product Features
            ['name' => 'Premium', 'slug' => 'premium'],
            ['name' => 'Luxury', 'slug' => 'luxury'],
            ['name' => 'Budget Friendly', 'slug' => 'budget-friendly'],
            ['name' => 'Eco Friendly', 'slug' => 'eco-friendly'],
            ['name' => 'Organic', 'slug' => 'organic'],
            ['name' => 'Handmade', 'slug' => 'handmade'],
            ['name' => 'Vintage', 'slug' => 'vintage'],
            ['name' => 'Modern', 'slug' => 'modern'],
            ['name' => 'Classic', 'slug' => 'classic'],

            // Discount & Offers
            ['name' => 'On Sale', 'slug' => 'on-sale'],
            ['name' => 'Clearance', 'slug' => 'clearance'],
            ['name' => 'Buy One Get One', 'slug' => 'buy-one-get-one'],
            ['name' => 'Free Shipping', 'slug' => 'free-shipping'],
            ['name' => 'Cashback', 'slug' => 'cashback'],

            // Customer Ratings
            ['name' => 'Top Rated', 'slug' => 'top-rated'],
            ['name' => 'Best Value', 'slug' => 'best-value'],
            ['name' => 'Customer Choice', 'slug' => 'customer-choice'],
            ['name' => 'Trending', 'slug' => 'trending'],
            ['name' => 'Popular', 'slug' => 'popular'],
        ];

        foreach ($tags as $tag) {
            Tag::create([
                'name' => $tag['name'],
                'slug' => $tag['slug'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
