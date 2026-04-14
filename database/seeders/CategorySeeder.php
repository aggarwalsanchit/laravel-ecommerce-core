<?php
// database/seeders/CategorySeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Track used slugs to avoid duplicates
     */
    private $usedSlugs = [];

    /**
     * Generate unique slug
     */
    private function generateUniqueSlug($name, $parentId = null)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        // Add parent context to slug if parent exists
        if ($parentId) {
            $parent = Category::find($parentId);
            if ($parent) {
                $slug = $parent->slug . '-' . $slug;
            }
        }

        // Check for duplicates and add counter if needed
        while (in_array($slug, $this->usedSlugs)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        $this->usedSlugs[] = $slug;
        return $slug;
    }

    public function run(): void
    {
        // Reset used slugs
        $this->usedSlugs = [];

        // ==================== LEVEL 1: MAIN CATEGORIES ====================
        $mainCategories = [
            [
                'name' => 'Electronics',
                'description' => 'Latest electronics, gadgets, and tech accessories',
                'short_description' => 'Shop the best electronics from top brands',
                'icon' => 'ti-devices',
                'image' => 'categories/electronics/main.jpg',
                'image_alt' => 'Electronics category banner',
                'banner_image' => 'categories/electronics/banner.jpg',
                'banner_alt' => 'Electronics deals banner',
                'thumbnail_image' => 'categories/electronics/thumb.jpg',
                'thumbnail_alt' => 'Electronics thumbnail',
                'meta_title' => 'Electronics - Best Deals on Gadgets & Accessories',
                'meta_description' => 'Shop the latest electronics including smartphones, laptops, cameras, and audio equipment.',
                'meta_keywords' => 'electronics, gadgets, smartphones, laptops, cameras, audio',
                'focus_keyword' => 'electronics',
                'order' => 1,
                'is_featured' => true,
                'is_popular' => true,
                'show_in_menu' => true,
                'level' => 0,
            ],
            [
                'name' => 'Fashion',
                'description' => 'Trendy clothing, footwear, and accessories',
                'short_description' => 'Discover the latest fashion trends',
                'icon' => 'ti-shopping-bag',
                'image' => 'categories/fashion/main.jpg',
                'image_alt' => 'Fashion category banner',
                'banner_image' => 'categories/fashion/banner.jpg',
                'banner_alt' => 'Fashion deals banner',
                'thumbnail_image' => 'categories/fashion/thumb.jpg',
                'thumbnail_alt' => 'Fashion thumbnail',
                'meta_title' => 'Fashion - Latest Trends in Clothing & Accessories',
                'meta_description' => 'Shop the latest fashion for men, women, and kids.',
                'meta_keywords' => 'fashion, clothing, shoes, accessories',
                'focus_keyword' => 'fashion',
                'order' => 2,
                'is_featured' => true,
                'is_popular' => true,
                'show_in_menu' => true,
                'level' => 0,
            ],
            [
                'name' => 'Home & Living',
                'description' => 'Furniture, decor, kitchenware, and home improvement',
                'short_description' => 'Transform your home with our collection',
                'icon' => 'ti-home',
                'image' => 'categories/home/main.jpg',
                'image_alt' => 'Home & Living category banner',
                'banner_image' => 'categories/home/banner.jpg',
                'banner_alt' => 'Home decor banner',
                'thumbnail_image' => 'categories/home/thumb.jpg',
                'thumbnail_alt' => 'Home & Living thumbnail',
                'meta_title' => 'Home & Living - Furniture & Home Decor',
                'meta_description' => 'Shop furniture, home decor, kitchenware, and home improvement products.',
                'meta_keywords' => 'home, furniture, decor, kitchen, home improvement',
                'focus_keyword' => 'home and living',
                'order' => 3,
                'is_featured' => true,
                'is_popular' => false,
                'show_in_menu' => true,
                'level' => 0,
            ],
            [
                'name' => 'Sports & Outdoors',
                'description' => 'Sports equipment, fitness gear, and outdoor adventure',
                'short_description' => 'Gear up for your next adventure',
                'icon' => 'ti-sports',
                'image' => 'categories/sports/main.jpg',
                'image_alt' => 'Sports category banner',
                'banner_image' => 'categories/sports/banner.jpg',
                'banner_alt' => 'Sports equipment banner',
                'thumbnail_image' => 'categories/sports/thumb.jpg',
                'thumbnail_alt' => 'Sports thumbnail',
                'meta_title' => 'Sports & Outdoors - Fitness & Adventure Gear',
                'meta_description' => 'Shop sports equipment, fitness gear, camping gear, and outdoor accessories.',
                'meta_keywords' => 'sports, fitness, camping, outdoor, gym equipment',
                'focus_keyword' => 'sports and outdoors',
                'order' => 4,
                'is_featured' => false,
                'is_popular' => true,
                'show_in_menu' => true,
                'level' => 0,
            ],
            [
                'name' => 'Books & Stationery',
                'description' => 'Books, ebooks, stationery, and office supplies',
                'short_description' => 'Feed your mind with knowledge',
                'icon' => 'ti-book',
                'image' => 'categories/books/main.jpg',
                'image_alt' => 'Books category banner',
                'banner_image' => 'categories/books/banner.jpg',
                'banner_alt' => 'Books sale banner',
                'thumbnail_image' => 'categories/books/thumb.jpg',
                'thumbnail_alt' => 'Books thumbnail',
                'meta_title' => 'Books & Stationery - Best Sellers & New Releases',
                'meta_description' => 'Shop books, ebooks, stationery, and office supplies.',
                'meta_keywords' => 'books, stationery, notebooks, pens, office supplies',
                'focus_keyword' => 'books and stationery',
                'order' => 5,
                'is_featured' => false,
                'is_popular' => true,
                'show_in_menu' => true,
                'level' => 0,
            ],
            [
                'name' => 'Beauty & Personal Care',
                'description' => 'Cosmetics, skincare, haircare, and personal hygiene',
                'short_description' => 'Look and feel your best',
                'icon' => 'ti-heart',
                'image' => 'categories/beauty/main.jpg',
                'image_alt' => 'Beauty category banner',
                'banner_image' => 'categories/beauty/banner.jpg',
                'banner_alt' => 'Beauty products banner',
                'thumbnail_image' => 'categories/beauty/thumb.jpg',
                'thumbnail_alt' => 'Beauty thumbnail',
                'meta_title' => 'Beauty & Personal Care - Skincare, Makeup & Haircare',
                'meta_description' => 'Shop beauty products, skincare, makeup, haircare, and personal care items.',
                'meta_keywords' => 'beauty, skincare, makeup, haircare, personal care',
                'focus_keyword' => 'beauty and personal care',
                'order' => 6,
                'is_featured' => true,
                'is_popular' => true,
                'show_in_menu' => true,
                'level' => 0,
            ],
            [
                'name' => 'Toys & Games',
                'description' => 'Toys, games, puzzles, and educational materials for kids',
                'short_description' => 'Fun and learning for all ages',
                'icon' => 'ti-game',
                'image' => 'categories/toys/main.jpg',
                'image_alt' => 'Toys category banner',
                'banner_image' => 'categories/toys/banner.jpg',
                'banner_alt' => 'Toys sale banner',
                'thumbnail_image' => 'categories/toys/thumb.jpg',
                'thumbnail_alt' => 'Toys thumbnail',
                'meta_title' => 'Toys & Games - Fun for Kids of All Ages',
                'meta_description' => 'Shop toys, games, puzzles, and educational toys for children.',
                'meta_keywords' => 'toys, games, puzzles, educational toys, kids gifts',
                'focus_keyword' => 'toys and games',
                'order' => 7,
                'is_featured' => false,
                'is_popular' => false,
                'show_in_menu' => true,
                'level' => 0,
            ],
            [
                'name' => 'Automotive',
                'description' => 'Car parts, accessories, tools, and maintenance',
                'short_description' => 'Keep your car running smoothly',
                'icon' => 'ti-car',
                'image' => 'categories/automotive/main.jpg',
                'image_alt' => 'Automotive category banner',
                'banner_image' => 'categories/automotive/banner.jpg',
                'banner_alt' => 'Auto parts banner',
                'thumbnail_image' => 'categories/automotive/thumb.jpg',
                'thumbnail_alt' => 'Automotive thumbnail',
                'meta_title' => 'Automotive - Car Parts, Accessories & Tools',
                'meta_description' => 'Shop car parts, accessories, tools, and maintenance products.',
                'meta_keywords' => 'automotive, car parts, auto accessories, tools, car maintenance',
                'focus_keyword' => 'automotive',
                'order' => 8,
                'is_featured' => false,
                'is_popular' => false,
                'show_in_menu' => true,
                'level' => 0,
            ],
            [
                'name' => 'Health & Wellness',
                'description' => 'Vitamins, supplements, fitness trackers, and health products',
                'short_description' => 'Your health is our priority',
                'icon' => 'ti-heartbeat',
                'image' => 'categories/health/main.jpg',
                'image_alt' => 'Health category banner',
                'banner_image' => 'categories/health/banner.jpg',
                'banner_alt' => 'Health products banner',
                'thumbnail_image' => 'categories/health/thumb.jpg',
                'thumbnail_alt' => 'Health thumbnail',
                'meta_title' => 'Health & Wellness - Vitamins, Supplements & Fitness',
                'meta_description' => 'Shop health products, vitamins, supplements, fitness trackers, and wellness items.',
                'meta_keywords' => 'health, wellness, vitamins, supplements, fitness',
                'focus_keyword' => 'health and wellness',
                'order' => 9,
                'is_featured' => false,
                'is_popular' => true,
                'show_in_menu' => true,
                'level' => 0,
            ],
            [
                'name' => 'Baby Products',
                'description' => 'Baby gear, clothing, diapers, and nursery items',
                'short_description' => 'Everything for your little one',
                'icon' => 'ti-baby',
                'image' => 'categories/baby/main.jpg',
                'image_alt' => 'Baby products banner',
                'banner_image' => 'categories/baby/banner.jpg',
                'banner_alt' => 'Baby sale banner',
                'thumbnail_image' => 'categories/baby/thumb.jpg',
                'thumbnail_alt' => 'Baby products thumbnail',
                'meta_title' => 'Baby Products - Gear, Clothing & Nursery Items',
                'meta_description' => 'Shop baby products including gear, clothing, diapers, feeding supplies, and nursery furniture.',
                'meta_keywords' => 'baby, baby products, diapers, baby clothing, nursery',
                'focus_keyword' => 'baby products',
                'order' => 10,
                'is_featured' => false,
                'is_popular' => false,
                'show_in_menu' => true,
                'level' => 0,
            ],
        ];

        foreach ($mainCategories as $catData) {
            $slug = $this->generateUniqueSlug($catData['name']);

            $category = Category::create(array_merge($catData, [
                'slug' => $slug,
                'status' => true,
                'approval_status' => 'approved'
            ]));

            // Update path after creation
            $category->path = (string) $category->id;
            $category->save();

            // Create subcategories based on main category
            switch ($category->name) {
                case 'Electronics':
                    $this->createElectronicsSubcategories($category);
                    break;
                case 'Fashion':
                    $this->createFashionSubcategories($category);
                    break;
                case 'Home & Living':
                    $this->createHomeSubcategories($category);
                    break;
                case 'Sports & Outdoors':
                    $this->createSportsSubcategories($category);
                    break;
                case 'Books & Stationery':
                    $this->createBooksSubcategories($category);
                    break;
                case 'Beauty & Personal Care':
                    $this->createBeautySubcategories($category);
                    break;
                case 'Toys & Games':
                    $this->createToysSubcategories($category);
                    break;
                case 'Automotive':
                    $this->createAutomotiveSubcategories($category);
                    break;
                case 'Health & Wellness':
                    $this->createHealthSubcategories($category);
                    break;
                case 'Baby Products':
                    $this->createBabySubcategories($category);
                    break;
            }
        }
    }

    // ==================== ELECTRONICS CATEGORIES (No Brands) ====================
    private function createElectronicsSubcategories($parent)
    {
        $subcategories = [
            'Smartphones & Tablets' => [
                'description' => 'Smartphones, tablets, and mobile accessories',
                'icon' => 'ti-mobile',
                'order' => 1,
                'children' => [
                    'Smartphones',
                    'Tablets',
                    'Wearables',
                    'Phone Cases & Covers',
                    'Screen Protectors',
                    'Chargers & Cables',
                    'Power Banks',
                    'Mobile Accessories'
                ]
            ],
            'Computers & Laptops' => [
                'description' => 'Laptops, desktops, and computer accessories',
                'icon' => 'ti-laptop',
                'order' => 2,
                'children' => [
                    'Laptops',
                    'Desktops',
                    'Monitors',
                    'Keyboards & Mice',
                    'Computer Components',
                    'Printers & Scanners',
                    'Networking Devices',
                    'Storage Devices',
                    'Computer Accessories'
                ]
            ],
            'Audio & Headphones' => [
                'description' => 'Headphones, speakers, and audio equipment',
                'icon' => 'ti-headphone',
                'order' => 3,
                'children' => [
                    'Headphones',
                    'Earbuds',
                    'Speakers',
                    'Soundbars',
                    'Microphones',
                    'Amplifiers & Receivers',
                    'Home Theater Systems'
                ]
            ],
            'Cameras & Photography' => [
                'description' => 'Cameras, lenses, and photography gear',
                'icon' => 'ti-camera',
                'order' => 4,
                'children' => [
                    'DSLR Cameras',
                    'Mirrorless Cameras',
                    'Action Cameras',
                    'Lenses',
                    'Camera Accessories'
                ]
            ],
            'Gaming' => [
                'description' => 'Gaming consoles, accessories, and gear',
                'icon' => 'ti-gamepad',
                'order' => 5,
                'children' => [
                    'Gaming Consoles',
                    'Gaming Accessories',
                    'Gaming Chairs',
                    'Gaming Keyboards',
                    'Gaming Mice',
                    'Gaming Monitors'
                ]
            ],
            'Smart Home' => [
                'description' => 'Smart home devices and automation',
                'icon' => 'ti-home',
                'order' => 6,
                'children' => [
                    'Smart Speakers',
                    'Smart Lights',
                    'Smart Plugs & Switches',
                    'Security Cameras',
                    'Video Doorbells',
                    'Thermostats',
                    'Smart Locks'
                ]
            ]
        ];

        $this->createLevel2Categories($parent, $subcategories);
    }

    // ==================== FASHION CATEGORIES ====================
    private function createFashionSubcategories($parent)
    {
        $subcategories = [
            'Men\'s Fashion' => [
                'description' => 'Clothing, footwear, and accessories for men',
                'icon' => 'ti-man',
                'order' => 1,
                'children' => [
                    'Clothing' => ['T-Shirts', 'Shirts', 'Jeans', 'Trousers', 'Shorts', 'Jackets', 'Suits & Blazers', 'Sweaters & Hoodies'],
                    'Footwear' => ['Sports Shoes', 'Casual Shoes', 'Formal Shoes', 'Sandals & Floaters', 'Loafers', 'Boots'],
                    'Accessories' => ['Watches', 'Belts', 'Wallets', 'Bags & Backpacks', 'Sunglasses', 'Caps & Hats'],
                    'Traditional Wear' => ['Kurtas', 'Sherwanis', 'Nehru Jackets']
                ]
            ],
            'Women\'s Fashion' => [
                'description' => 'Clothing, footwear, and accessories for women',
                'icon' => 'ti-woman',
                'order' => 2,
                'children' => [
                    'Clothing' => ['Dresses', 'Tops & Tunics', 'Jeans', 'Trousers', 'Skirts', 'Shorts', 'Jackets & Coats', 'Sweaters & Cardigans'],
                    'Footwear' => ['Heels', 'Flats', 'Sports Shoes', 'Casual Shoes', 'Boots', 'Sandals'],
                    'Accessories' => ['Handbags', 'Wallets', 'Jewelry', 'Watches', 'Sunglasses', 'Belts', 'Hair Accessories'],
                    'Traditional Wear' => ['Sarees', 'Salwar Suits', 'Lehengas', 'Kurtis', 'Anarkalis'],
                    'Western Wear' => ['Jeans', 'Tops', 'Dresses', 'Jumpsuits', 'Skirts']
                ]
            ],
            'Kids\' Fashion' => [
                'description' => 'Clothing and accessories for boys and girls',
                'icon' => 'ti-child',
                'order' => 3,
                'children' => [
                    'Boys Clothing' => ['T-Shirts', 'Shirts', 'Jeans', 'Shorts', 'Track Pants', 'Jackets', 'Ethnic Wear'],
                    'Girls Clothing' => ['Dresses', 'Tops', 'Jeans', 'Skirts', 'Shorts', 'Leggings', 'Ethnic Wear'],
                    'Kids Footwear' => ['Sports Shoes', 'Casual Shoes', 'School Shoes', 'Sandals', 'Boots'],
                    'Baby Clothing (0-24 months)' => ['Onesies', 'Rompers', 'Sleepsuits', 'Bodysuits'],
                    'Kids Accessories' => ['Bags', 'Hats', 'Socks', 'Watches']
                ]
            ],
            'Footwear' => [
                'description' => 'All types of footwear for every occasion',
                'icon' => 'ti-shoe',
                'order' => 4,
                'children' => [
                    'Men\'s Footwear',
                    'Women\'s Footwear',
                    'Kids\' Footwear',
                    'Sports Footwear'
                ]
            ],
            'Bags & Luggage' => [
                'description' => 'Bags, backpacks, and travel luggage',
                'icon' => 'ti-bag',
                'order' => 5,
                'children' => [
                    'Backpacks',
                    'Handbags',
                    'Tote Bags',
                    'Sling Bags',
                    'Laptop Bags',
                    'Travel Luggage',
                    'Wallets & Purses'
                ]
            ]
        ];

        $this->createLevel2Categories($parent, $subcategories);
    }

    // ==================== HOME & LIVING CATEGORIES ====================
    private function createHomeSubcategories($parent)
    {
        $subcategories = [
            'Furniture' => [
                'description' => 'Sofas, beds, tables, and chairs',
                'icon' => 'ti-sofa',
                'order' => 1,
                'children' => [
                    'Living Room Furniture' => ['Sofas', 'Recliners', 'Coffee Tables', 'TV Stands', 'Ottomans'],
                    'Bedroom Furniture' => ['Beds', 'Mattresses', 'Wardrobes', 'Dressers', 'Nightstands'],
                    'Dining Room Furniture' => ['Dining Tables', 'Dining Chairs', 'Bar Stools'],
                    'Office Furniture' => ['Office Chairs', 'Office Desks', 'Bookshelves', 'Filing Cabinets'],
                    'Kids Furniture' => ['Beds', 'Study Tables', 'Chairs', 'Bookcases'],
                    'Outdoor Furniture' => ['Patio Sets', 'Garden Chairs', 'Outdoor Tables', 'Hammocks']
                ]
            ],
            'Home Decor' => [
                'description' => 'Decor items, wall art, and lighting',
                'icon' => 'ti-lamp',
                'order' => 2,
                'children' => [
                    'Wall Art' => ['Paintings', 'Posters', 'Wall Clocks', 'Mirrors', 'Frames'],
                    'Lighting' => ['Ceiling Lights', 'Floor Lamps', 'Table Lamps', 'Wall Sconces', 'Chandeliers'],
                    'Decorative Accents' => ['Vases', 'Candles', 'Figurines', 'Artificial Plants'],
                    'Rugs & Carpets' => ['Area Rugs', 'Runner Rugs', 'Doormats'],
                    'Curtains & Drapes' => ['Curtains', 'Blinds', 'Shades']
                ]
            ],
            'Kitchen & Dining' => [
                'description' => 'Cookware, utensils, and dinnerware',
                'icon' => 'ti-kitchen',
                'order' => 3,
                'children' => [
                    'Cookware' => ['Frying Pans', 'Saucepans', 'Stock Pots', 'Dutch Ovens'],
                    'Bakeware' => ['Baking Sheets', 'Muffin Pans', 'Cake Pans', 'Pie Plates'],
                    'Kitchen Tools' => ['Knives', 'Cutting Boards', 'Measuring Cups', 'Spatulas'],
                    'Dinnerware' => ['Plates', 'Bowls', 'Cups & Mugs', 'Serving Platters'],
                    'Glassware' => ['Drinking Glasses', 'Wine Glasses', 'Mason Jars'],
                    'Kitchen Storage' => ['Food Containers', 'Canisters', 'Spice Racks'],
                    'Appliances' => ['Microwaves', 'Blenders', 'Mixers', 'Toasters', 'Coffee Makers']
                ]
            ],
            'Bedding & Bath' => [
                'description' => 'Bed sheets, towels, and bathroom accessories',
                'icon' => 'ti-bed',
                'order' => 4,
                'children' => [
                    'Bedding' => ['Bed Sheets', 'Comforters', 'Pillows', 'Blankets', 'Mattress Protectors'],
                    'Bath' => ['Towels', 'Bathrobes', 'Shower Curtains', 'Bath Mats'],
                    'Bathroom Accessories' => ['Towel Racks', 'Shelves', 'Mirrors']
                ]
            ],
            'Home Improvement' => [
                'description' => 'Tools, hardware, and DIY supplies',
                'icon' => 'ti-tool',
                'order' => 5,
                'children' => [
                    'Power Tools' => ['Drills', 'Saws', 'Sanders', 'Grinders'],
                    'Hand Tools' => ['Hammers', 'Screwdrivers', 'Wrenches', 'Pliers'],
                    'Hardware' => ['Nails', 'Screws', 'Bolts', 'Hinges'],
                    'Paint & Supplies' => ['Paint', 'Brushes', 'Rollers', 'Tape'],
                    'Storage & Organization' => ['Shelving', 'Tool Boxes', 'Storage Bins']
                ]
            ]
        ];

        $this->createLevel2Categories($parent, $subcategories);
    }

    // ==================== SPORTS CATEGORIES ====================
    private function createSportsSubcategories($parent)
    {
        $subcategories = [
            'Fitness Equipment' => [
                'description' => 'Gym equipment, weights, and fitness accessories',
                'icon' => 'ti-dumbbell',
                'order' => 1,
                'children' => [
                    'Cardio Equipment' => ['Treadmills', 'Exercise Bikes', 'Ellipticals', 'Rowing Machines'],
                    'Strength Training' => ['Dumbbells', 'Barbells', 'Weight Plates', 'Kettlebells', 'Resistance Bands'],
                    'Yoga & Pilates' => ['Yoga Mats', 'Blocks', 'Straps', 'Exercise Balls'],
                    'Fitness Accessories' => ['Gym Gloves', 'Jump Ropes', 'Foam Rollers', 'Pull Up Bars']
                ]
            ],
            'Team Sports' => [
                'description' => 'Equipment for football, basketball, cricket, and more',
                'icon' => 'ti-football',
                'order' => 2,
                'children' => [
                    'Football' => ['Balls', 'Boots', 'Shin Guards', 'Goal Keeper Gloves'],
                    'Basketball' => ['Balls', 'Shoes', 'Hoops', 'Backboards'],
                    'Cricket' => ['Bats', 'Balls', 'Pads', 'Gloves', 'Helmets'],
                    'Tennis' => ['Rackets', 'Balls', 'Strings', 'Grips'],
                    'Volleyball' => ['Balls', 'Nets', 'Knee Pads']
                ]
            ],
            'Outdoor Sports' => [
                'description' => 'Camping, hiking, and adventure gear',
                'icon' => 'ti-camp',
                'order' => 3,
                'children' => [
                    'Camping' => ['Tents', 'Sleeping Bags', 'Camping Stoves', 'Lanterns', 'Backpacks'],
                    'Hiking' => ['Hiking Boots', 'Backpacks', 'Trekking Poles', 'Hydration Packs'],
                    'Climbing' => ['Climbing Shoes', 'Harnesses', 'Ropes', 'Carabiners'],
                    'Fishing' => ['Fishing Rods', 'Reels', 'Tackle Boxes', 'Baits']
                ]
            ],
            'Cycling' => [
                'description' => 'Bicycles, helmets, and cycling gear',
                'icon' => 'ti-bike',
                'order' => 4,
                'children' => [
                    'Bicycles' => ['Mountain Bikes', 'Road Bikes', 'Hybrid Bikes', 'Electric Bikes', 'Kids Bikes'],
                    'Cycling Accessories' => ['Helmets', 'Lights', 'Locks', 'Pumps', 'Bags'],
                    'Cycling Apparel' => ['Jerseys', 'Shorts', 'Gloves', 'Shoes']
                ]
            ]
        ];

        $this->createLevel2Categories($parent, $subcategories);
    }

    // ==================== BOOKS CATEGORIES ====================
    private function createBooksSubcategories($parent)
    {
        $subcategories = [
            'Fiction' => [
                'description' => 'Novels, thrillers, and literary fiction',
                'icon' => 'ti-book',
                'order' => 1,
                'children' => [
                    'Action & Adventure',
                    'Romance',
                    'Thriller & Suspense',
                    'Science Fiction',
                    'Fantasy',
                    'Horror',
                    'Historical Fiction',
                    'Literary Fiction',
                    'Classics'
                ]
            ],
            'Non-Fiction' => [
                'description' => 'Biographies, history, and self-help',
                'icon' => 'ti-file',
                'order' => 2,
                'children' => [
                    'Biographies & Memoirs',
                    'History',
                    'Self-Help',
                    'Business & Economics',
                    'Philosophy',
                    'Religion & Spirituality',
                    'True Crime'
                ]
            ],
            'Academic & Textbooks' => [
                'description' => 'School and college textbooks',
                'icon' => 'ti-school',
                'order' => 3,
                'children' => [
                    'Mathematics',
                    'Science' => ['Physics', 'Chemistry', 'Biology'],
                    'Engineering' => ['Computer Science', 'Mechanical', 'Electrical'],
                    'Medicine',
                    'Law',
                    'Business' => ['Marketing', 'Finance', 'Management']
                ]
            ],
            'Children\'s Books' => [
                'description' => 'Books for kids of all ages',
                'icon' => 'ti-child',
                'order' => 4,
                'children' => [
                    'Picture Books',
                    'Early Readers',
                    'Chapter Books',
                    'Young Adult',
                    'Educational',
                    'Activity Books'
                ]
            ],
            'Stationery' => [
                'description' => 'Notebooks, pens, and office supplies',
                'icon' => 'ti-pencil',
                'order' => 5,
                'children' => [
                    'Notebooks & Journals',
                    'Pens & Pencils',
                    'Markers & Highlighters',
                    'Art Supplies',
                    'Office Supplies',
                    'Planners & Calendars'
                ]
            ]
        ];

        $this->createLevel2Categories($parent, $subcategories);
    }

    // ==================== BEAUTY CATEGORIES ====================
    private function createBeautySubcategories($parent)
    {
        $subcategories = [
            'Makeup' => [
                'description' => 'Cosmetics and makeup products',
                'icon' => 'ti-makeup',
                'order' => 1,
                'children' => [
                    'Face Makeup' => ['Foundation', 'Concealer', 'Powder', 'Blush', 'Bronzer'],
                    'Eye Makeup' => ['Eyeshadow', 'Eyeliner', 'Mascara', 'Eyebrow Products'],
                    'Lip Makeup' => ['Lipstick', 'Lip Gloss', 'Lip Liner', 'Lip Balm'],
                    'Makeup Tools' => ['Brushes', 'Sponges', 'Makeup Bags']
                ]
            ],
            'Skincare' => [
                'description' => 'Facial care, creams, and treatments',
                'icon' => 'ti-face',
                'order' => 2,
                'children' => [
                    'Cleansers' => ['Face Wash', 'Cleansing Oils', 'Makeup Removers'],
                    'Moisturizers' => ['Day Creams', 'Night Creams', 'Face Oils'],
                    'Serums & Treatments' => ['Vitamin C', 'Hyaluronic Acid', 'Retinol'],
                    'Face Masks' => ['Sheet Masks', 'Clay Masks', 'Peel Off Masks'],
                    'Sunscreen',
                    'Eye Care' => ['Eye Creams', 'Eye Serums']
                ]
            ],
            'Hair Care' => [
                'description' => 'Shampoos, conditioners, and hair treatments',
                'icon' => 'ti-hair',
                'order' => 3,
                'children' => [
                    'Shampoos',
                    'Conditioners',
                    'Hair Oils',
                    'Hair Masks & Treatments',
                    'Styling Products' => ['Hair Sprays', 'Gels', 'Serums'],
                    'Hair Tools' => ['Hair Dryers', 'Straighteners', 'Curling Irons']
                ]
            ],
            'Fragrances' => [
                'description' => 'Perfumes, colognes, and body sprays',
                'icon' => 'ti-perfume',
                'order' => 4,
                'children' => [
                    'Women\'s Perfumes',
                    'Men\'s Colognes',
                    'Unisex Fragrances',
                    'Body Sprays',
                    'Perfume Sets'
                ]
            ],
            'Personal Care' => [
                'description' => 'Bath, body, and hygiene products',
                'icon' => 'ti-bath',
                'order' => 5,
                'children' => [
                    'Bath & Shower' => ['Body Wash', 'Bath Bombs', 'Bath Salts'],
                    'Body Care' => ['Body Lotions', 'Body Butters', 'Body Scrubs'],
                    'Deodorants & Antiperspirants',
                    'Oral Care' => ['Toothpaste', 'Toothbrushes', 'Mouthwash'],
                    'Shaving & Hair Removal' => ['Razors', 'Shaving Creams', 'Trimmers']
                ]
            ]
        ];

        $this->createLevel2Categories($parent, $subcategories);
    }

    // ==================== TOYS & GAMES CATEGORIES ====================
    private function createToysSubcategories($parent)
    {
        $subcategories = [
            'Action Figures & Statues' => [
                'description' => 'Collectible action figures and statues',
                'order' => 1,
                'children' => [
                    'Superheroes',
                    'Movie Characters',
                    'Anime Figures',
                    'Collectible Statues'
                ]
            ],
            'Board Games' => [
                'description' => 'Family and strategy board games',
                'order' => 2,
                'children' => [
                    'Strategy Games' => ['Chess', 'Checkers', 'Risk'],
                    'Family Games' => ['Monopoly', 'Scrabble', 'Clue', 'Uno'],
                    'Card Games' => ['Poker', 'Uno', 'Exploding Kittens'],
                    'Educational Games'
                ]
            ],
            'Educational Toys' => [
                'description' => 'Learning and development toys',
                'order' => 3,
                'children' => [
                    'STEM Toys' => ['Science Kits', 'Robotics', 'Coding Toys'],
                    'Puzzles' => ['Jigsaw Puzzles', '3D Puzzles', 'Brain Teasers'],
                    'Building Sets' => ['LEGO', 'Magnetic Tiles', 'Wooden Blocks'],
                    'Learning Tablets'
                ]
            ],
            'Outdoor Play' => [
                'description' => 'Toys for outdoor fun',
                'order' => 4,
                'children' => [
                    'Bikes & Scooters',
                    'Sports Toys' => ['Balls', 'Frisbees', 'Badminton Sets'],
                    'Water Toys' => ['Water Guns', 'Pool Toys', 'Sprinklers'],
                    'Playground Equipment' => ['Swings', 'Slides', 'Trampolines']
                ]
            ]
        ];

        $this->createLevel2Categories($parent, $subcategories);
    }

    // ==================== AUTOMOTIVE CATEGORIES ====================
    private function createAutomotiveSubcategories($parent)
    {
        $subcategories = [
            'Car Care' => [
                'description' => 'Cleaning and maintenance products',
                'order' => 1,
                'children' => [
                    'Car Wash' => ['Soaps', 'Shampoos', 'Pressure Washers'],
                    'Waxes & Polishes',
                    'Interior Care' => ['Cleaners', 'Protectants', 'Fresheners'],
                    'Glass Care' => ['Windshield Cleaners', 'Rain Repellents']
                ]
            ],
            'Car Electronics' => [
                'description' => 'GPS, dash cams, and audio systems',
                'order' => 2,
                'children' => [
                    'GPS & Navigation',
                    'Dash Cams',
                    'Car Audio' => ['Head Units', 'Speakers', 'Subwoofers'],
                    'Radar Detectors'
                ]
            ],
            'Tires & Wheels' => [
                'description' => 'Tires, rims, and accessories',
                'order' => 3,
                'children' => [
                    'Tires' => ['All-Season', 'Winter', 'Summer', 'Performance'],
                    'Wheels' => ['Alloy Rims', 'Steel Rims', 'Hubcaps'],
                    'Tire Accessories' => ['Tire Inflators', 'Pressure Gauges', 'Repair Kits']
                ]
            ],
            'Interior Accessories' => [
                'description' => 'Seat covers, mats, and organizers',
                'order' => 4,
                'children' => [
                    'Seat Covers',
                    'Floor Mats',
                    'Steering Wheels' => ['Covers', 'Wraps'],
                    'Organizers' => ['Back Seat Organizers', 'Trunk Organizers']
                ]
            ]
        ];

        $this->createLevel2Categories($parent, $subcategories);
    }

    // ==================== HEALTH CATEGORIES ====================
    private function createHealthSubcategories($parent)
    {
        $subcategories = [
            'Vitamins & Supplements' => [
                'description' => 'Dietary supplements and vitamins',
                'order' => 1,
                'children' => [
                    'Multivitamins',
                    'Protein Supplements' => ['Whey Protein', 'Plant Protein'],
                    'Minerals' => ['Calcium', 'Magnesium', 'Zinc'],
                    'Herbal Supplements',
                    'Omega & Fish Oil'
                ]
            ],
            'Fitness Trackers' => [
                'description' => 'Wearable fitness technology',
                'order' => 2,
                'children' => [
                    'Smart Watches',
                    'Fitness Bands',
                    'Heart Rate Monitors',
                    'GPS Trackers'
                ]
            ],
            'Medical Supplies' => [
                'description' => 'First aid and medical equipment',
                'order' => 3,
                'children' => [
                    'First Aid Kits',
                    'Blood Pressure Monitors',
                    'Thermometers',
                    'Pulse Oximeters',
                    'Supports & Braces'
                ]
            ]
        ];

        $this->createLevel2Categories($parent, $subcategories);
    }

    // ==================== BABY CATEGORIES ====================
    private function createBabySubcategories($parent)
    {
        $subcategories = [
            'Nursery Furniture' => [
                'description' => 'Cribs, changing tables, and dressers',
                'order' => 1,
                'children' => [
                    'Cribs & Bassinets',
                    'Changing Tables',
                    'Dressers & Wardrobes',
                    'Rocking Chairs'
                ]
            ],
            'Baby Gear' => [
                'description' => 'Strollers, car seats, and carriers',
                'order' => 2,
                'children' => [
                    'Strollers' => ['Standard', 'Travel Systems', 'Joggers'],
                    'Car Seats' => ['Infant', 'Convertible', 'Booster'],
                    'Baby Carriers' => ['Wraps', 'Slings', 'Soft Structured'],
                    'High Chairs',
                    'Playards'
                ]
            ],
            'Diapering' => [
                'description' => 'Diapers, wipes, and changing supplies',
                'order' => 3,
                'children' => [
                    'Diapers' => ['Disposable', 'Cloth', 'Swim Diapers'],
                    'Baby Wipes',
                    'Diaper Creams',
                    'Diaper Bags',
                    'Changing Pads'
                ]
            ],
            'Feeding' => [
                'description' => 'Bottles, breast pumps, and feeding accessories',
                'order' => 4,
                'children' => [
                    'Baby Bottles',
                    'Breast Pumps',
                    'Nipples & Teats',
                    'Bottle Sterilizers',
                    'Sippy Cups'
                ]
            ]
        ];

        $this->createLevel2Categories($parent, $subcategories);
    }
    
    // ==================== HELPER METHODS ====================

    /**
     * Create Level 2 categories with their children
     */
    private function createLevel2Categories($parent, $subcategories)
    {
        foreach ($subcategories as $name => $data) {
            // If data is not an array (just string), convert to array format
            if (!is_array($data)) {
                $data = ['children' => []];
            }

            // Handle case where data is array but children not set
            if (!isset($data['children'])) {
                $data['children'] = [];
            }

            $slug = $this->generateUniqueSlug($name, $parent->id);

            $category = Category::create([
                'name' => $name,
                'slug' => $slug,
                'description' => $data['description'] ?? "Shop {$name} category",
                'parent_id' => $parent->id,
                'icon' => $data['icon'] ?? null,
                'order' => $data['order'] ?? 0,
                'level' => $parent->level + 1,
                'path' => $parent->path . '/' . $parent->id,
                'show_in_menu' => true,
                'status' => true,
                'approval_status' => 'approved'
            ]);

            // Create Level 3 categories
            if (!empty($data['children'])) {
                $this->createLevel3Categories($category, $data['children']);
            }
        }
    }

    /**
     * Create Level 3 categories
     */
    private function createLevel3Categories($parent, $children)
    {
        foreach ($children as $name => $childData) {
            // Handle both array and string values
            if (is_array($childData)) {
                $subchildren = $childData;
                $categoryName = $name;
            } else {
                $subchildren = [];
                $categoryName = $childData;
            }

            $slug = $this->generateUniqueSlug($categoryName, $parent->id);

            $category = Category::create([
                'name' => $categoryName,
                'slug' => $slug,
                'description' => "Shop {$categoryName} in {$parent->name}",
                'parent_id' => $parent->id,
                'level' => $parent->level + 1,
                'path' => $parent->path . '/' . $parent->id,
                'show_in_menu' => true,
                'status' => true,
                'approval_status' => 'approved'
            ]);

            // Create Level 4 categories if any
            if (!empty($subchildren) && is_array($subchildren)) {
                foreach ($subchildren as $subchild) {
                    $slug4 = $this->generateUniqueSlug($subchild, $category->id);

                    Category::create([
                        'name' => $subchild,
                        'slug' => $slug4,
                        'description' => "Shop {$subchild} in {$category->name}",
                        'parent_id' => $category->id,
                        'level' => $category->level + 1,
                        'path' => $category->path . '/' . $category->id,
                        'show_in_menu' => false,
                        'status' => true,
                        'approval_status' => 'approved'
                    ]);
                }
            }
        }
    }
}
