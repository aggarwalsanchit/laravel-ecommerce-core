<?php
// database/seeders/ColorSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Color;
use Illuminate\Support\Str;

class ColorSeeder extends Seeder
{
    public function run(): void
    {
        $colors = [
            // ==================== PRIMARY COLORS ====================
            [
                'name' => 'Red',
                'code' => '#FF0000',
                'rgb' => 'rgb(255, 0, 0)',
                'hsl' => 'hsl(0, 100%, 50%)',
                'description' => 'Bold and vibrant red color',
                'is_featured' => true,
                'is_popular' => true,
                'order' => 1,
                'status' => true,
                'approval_status' => 'approved'
            ],
            [
                'name' => 'Blue',
                'code' => '#0000FF',
                'rgb' => 'rgb(0, 0, 255)',
                'hsl' => 'hsl(240, 100%, 50%)',
                'description' => 'Classic and calm blue color',
                'is_featured' => true,
                'is_popular' => true,
                'order' => 2,
                'status' => true,
                'approval_status' => 'approved'
            ],
            [
                'name' => 'Green',
                'code' => '#00FF00',
                'rgb' => 'rgb(0, 255, 0)',
                'hsl' => 'hsl(120, 100%, 50%)',
                'description' => 'Fresh and natural green color',
                'is_featured' => true,
                'is_popular' => true,
                'order' => 3,
                'status' => true,
                'approval_status' => 'approved'
            ],
            [
                'name' => 'Yellow',
                'code' => '#FFFF00',
                'rgb' => 'rgb(255, 255, 0)',
                'hsl' => 'hsl(60, 100%, 50%)',
                'description' => 'Bright and cheerful yellow color',
                'is_featured' => true,
                'is_popular' => true,
                'order' => 4,
                'status' => true,
                'approval_status' => 'approved'
            ],

            // ==================== SECONDARY COLORS ====================
            [
                'name' => 'Orange',
                'code' => '#FFA500',
                'rgb' => 'rgb(255, 165, 0)',
                'hsl' => 'hsl(39, 100%, 50%)',
                'description' => 'Warm and energetic orange color',
                'is_featured' => true,
                'is_popular' => true,
                'order' => 5,
                'status' => true,
                'approval_status' => 'approved'
            ],
            [
                'name' => 'Purple',
                'code' => '#800080',
                'rgb' => 'rgb(128, 0, 128)',
                'hsl' => 'hsl(300, 100%, 25%)',
                'description' => 'Royal and luxurious purple color',
                'is_featured' => true,
                'is_popular' => true,
                'order' => 6,
                'status' => true,
                'approval_status' => 'approved'
            ],
            [
                'name' => 'Pink',
                'code' => '#FFC0CB',
                'rgb' => 'rgb(255, 192, 203)',
                'hsl' => 'hsl(350, 100%, 88%)',
                'description' => 'Soft and feminine pink color',
                'is_featured' => true,
                'is_popular' => true,
                'order' => 7,
                'status' => true,
                'approval_status' => 'approved'
            ],
            [
                'name' => 'Brown',
                'code' => '#8B4513',
                'rgb' => 'rgb(139, 69, 19)',
                'hsl' => 'hsl(25, 76%, 31%)',
                'description' => 'Earthy and natural brown color',
                'is_featured' => false,
                'is_popular' => true,
                'order' => 8,
                'status' => true,
                'approval_status' => 'approved'
            ],

            // ==================== NEUTRAL COLORS ====================
            [
                'name' => 'Black',
                'code' => '#000000',
                'rgb' => 'rgb(0, 0, 0)',
                'hsl' => 'hsl(0, 0%, 0%)',
                'description' => 'Classic and elegant black color',
                'is_featured' => true,
                'is_popular' => true,
                'order' => 9,
                'status' => true,
                'approval_status' => 'approved'
            ],
            [
                'name' => 'White',
                'code' => '#FFFFFF',
                'rgb' => 'rgb(255, 255, 255)',
                'hsl' => 'hsl(0, 0%, 100%)',
                'description' => 'Clean and pure white color',
                'is_featured' => true,
                'is_popular' => true,
                'order' => 10,
                'status' => true,
                'approval_status' => 'approved'
            ],
            [
                'name' => 'Gray',
                'code' => '#808080',
                'rgb' => 'rgb(128, 128, 128)',
                'hsl' => 'hsl(0, 0%, 50%)',
                'description' => 'Neutral and versatile gray color',
                'is_featured' => true,
                'is_popular' => true,
                'order' => 11,
                'status' => true,
                'approval_status' => 'approved'
            ],
            [
                'name' => 'Silver',
                'code' => '#C0C0C0',
                'rgb' => 'rgb(192, 192, 192)',
                'hsl' => 'hsl(0, 0%, 75%)',
                'description' => 'Metallic and shiny silver color',
                'is_featured' => false,
                'is_popular' => true,
                'order' => 12,
                'status' => true,
                'approval_status' => 'approved'
            ],

            // ==================== DARK SHADES ====================
            [
                'name' => 'Dark Red',
                'code' => '#8B0000',
                'rgb' => 'rgb(139, 0, 0)',
                'hsl' => 'hsl(0, 100%, 27%)',
                'description' => 'Deep and rich dark red color',
                'is_featured' => false,
                'is_popular' => false,
                'order' => 13,
                'status' => true,
                'approval_status' => 'approved'
            ],
            [
                'name' => 'Dark Blue',
                'code' => '#00008B',
                'rgb' => 'rgb(0, 0, 139)',
                'hsl' => 'hsl(240, 100%, 27%)',
                'description' => 'Deep and professional dark blue color',
                'is_featured' => false,
                'is_popular' => false,
                'order' => 14,
                'status' => true,
                'approval_status' => 'approved'
            ],
            [
                'name' => 'Dark Green',
                'code' => '#006400',
                'rgb' => 'rgb(0, 100, 0)',
                'hsl' => 'hsl(120, 100%, 20%)',
                'description' => 'Deep and rich dark green color',
                'is_featured' => false,
                'is_popular' => false,
                'order' => 15,
                'status' => true,
                'approval_status' => 'approved'
            ],
            [
                'name' => 'Navy Blue',
                'code' => '#000080',
                'rgb' => 'rgb(0, 0, 128)',
                'hsl' => 'hsl(240, 100%, 25%)',
                'description' => 'Classic navy blue color',
                'is_featured' => true,
                'is_popular' => true,
                'order' => 16,
                'status' => true,
                'approval_status' => 'approved'
            ],
            [
                'name' => 'Maroon',
                'code' => '#800000',
                'rgb' => 'rgb(128, 0, 0)',
                'hsl' => 'hsl(0, 100%, 25%)',
                'description' => 'Rich and elegant maroon color',
                'is_featured' => false,
                'is_popular' => false,
                'order' => 17,
                'status' => true,
                'approval_status' => 'approved'
            ],

            // ==================== LIGHT SHADES ====================
            [
                'name' => 'Light Red',
                'code' => '#FF6666',
                'rgb' => 'rgb(255, 102, 102)',
                'hsl' => 'hsl(0, 100%, 70%)',
                'description' => 'Soft and gentle light red color',
                'is_featured' => false,
                'is_popular' => false,
                'order' => 18,
                'status' => true,
                'approval_status' => 'approved'
            ],
            [
                'name' => 'Light Blue',
                'code' => '#ADD8E6',
                'rgb' => 'rgb(173, 216, 230)',
                'hsl' => 'hsl(195, 53%, 79%)',
                'description' => 'Soft and calming light blue color',
                'is_featured' => false,
                'is_popular' => true,
                'order' => 19,
                'status' => true,
                'approval_status' => 'approved'
            ],
            [
                'name' => 'Light Green',
                'code' => '#90EE90',
                'rgb' => 'rgb(144, 238, 144)',
                'hsl' => 'hsl(120, 73%, 75%)',
                'description' => 'Fresh and soft light green color',
                'is_featured' => false,
                'is_popular' => false,
                'order' => 20,
                'status' => true,
                'approval_status' => 'approved'
            ],
            [
                'name' => 'Light Pink',
                'code' => '#FFB6C1',
                'rgb' => 'rgb(255, 182, 193)',
                'hsl' => 'hsl(351, 100%, 86%)',
                'description' => 'Soft and delicate light pink color',
                'is_featured' => false,
                'is_popular' => true,
                'order' => 21,
                'status' => true,
                'approval_status' => 'approved'
            ],
            [
                'name' => 'Light Yellow',
                'code' => '#FFFFE0',
                'rgb' => 'rgb(255, 255, 224)',
                'hsl' => 'hsl(60, 100%, 94%)',
                'description' => 'Soft and warm light yellow color',
                'is_featured' => false,
                'is_popular' => false,
                'order' => 22,
                'status' => true,
                'approval_status' => 'approved'
            ],

            // ==================== PASTEL SHADES ====================
            [
                'name' => 'Pastel Pink',
                'code' => '#FFD1DC',
                'rgb' => 'rgb(255, 209, 220)',
                'hsl' => 'hsl(350, 100%, 91%)',
                'description' => 'Soft and romantic pastel pink color',
                'is_featured' => false,
                'is_popular' => true,
                'order' => 23,
                'status' => true,
                'approval_status' => 'approved'
            ],
            [
                'name' => 'Pastel Blue',
                'code' => '#AEC6CF',
                'rgb' => 'rgb(174, 198, 207)',
                'hsl' => 'hsl(196, 26%, 75%)',
                'description' => 'Soft and calming pastel blue color',
                'is_featured' => false,
                'is_popular' => true,
                'order' => 24,
                'status' => true,
                'approval_status' => 'approved'
            ],
            [
                'name' => 'Pastel Green',
                'code' => '#B2D8B2',
                'rgb' => 'rgb(178, 216, 178)',
                'hsl' => 'hsl(120, 33%, 77%)',
                'description' => 'Soft and natural pastel green color',
                'is_featured' => false,
                'is_popular' => false,
                'order' => 25,
                'status' => true,
                'approval_status' => 'approved'
            ],
            [
                'name' => 'Pastel Yellow',
                'code' => '#FDFD96',
                'rgb' => 'rgb(253, 253, 150)',
                'hsl' => 'hsl(60, 96%, 79%)',
                'description' => 'Soft and cheerful pastel yellow color',
                'is_featured' => false,
                'is_popular' => false,
                'order' => 26,
                'status' => true,
                'approval_status' => 'approved'
            ],
            [
                'name' => 'Pastel Purple',
                'code' => '#C3B1E1',
                'rgb' => 'rgb(195, 177, 225)',
                'hsl' => 'hsl(263, 44%, 79%)',
                'description' => 'Soft and dreamy pastel purple color',
                'is_featured' => false,
                'is_popular' => false,
                'order' => 27,
                'status' => true,
                'approval_status' => 'approved'
            ],

            // ==================== VIBRANT SHADES ====================
            [
                'name' => 'Cyan',
                'code' => '#00FFFF',
                'rgb' => 'rgb(0, 255, 255)',
                'hsl' => 'hsl(180, 100%, 50%)',
                'description' => 'Bright and vibrant cyan color',
                'is_featured' => false,
                'is_popular' => true,
                'order' => 28,
                'status' => true,
                'approval_status' => 'approved'
            ],
            [
                'name' => 'Magenta',
                'code' => '#FF00FF',
                'rgb' => 'rgb(255, 0, 255)',
                'hsl' => 'hsl(300, 100%, 50%)',
                'description' => 'Bold and vibrant magenta color',
                'is_featured' => false,
                'is_popular' => false,
                'order' => 29,
                'status' => true,
                'approval_status' => 'approved'
            ],
            [
                'name' => 'Lime',
                'code' => '#32CD32',
                'rgb' => 'rgb(50, 205, 50)',
                'hsl' => 'hsl(120, 61%, 50%)',
                'description' => 'Bright and fresh lime green color',
                'is_featured' => false,
                'is_popular' => false,
                'order' => 30,
                'status' => true,
                'approval_status' => 'approved'
            ],
            [
                'name' => 'Gold',
                'code' => '#FFD700',
                'rgb' => 'rgb(255, 215, 0)',
                'hsl' => 'hsl(51, 100%, 50%)',
                'description' => 'Rich and luxurious gold color',
                'is_featured' => true,
                'is_popular' => true,
                'order' => 31,
                'status' => true,
                'approval_status' => 'approved'
            ],

            // ==================== EARTH TONES ====================
            [
                'name' => 'Beige',
                'code' => '#F5F5DC',
                'rgb' => 'rgb(245, 245, 220)',
                'hsl' => 'hsl(60, 56%, 91%)',
                'description' => 'Neutral and warm beige color',
                'is_featured' => false,
                'is_popular' => true,
                'order' => 32,
                'status' => true,
                'approval_status' => 'approved'
            ],
            [
                'name' => 'Tan',
                'code' => '#D2B48C',
                'rgb' => 'rgb(210, 180, 140)',
                'hsl' => 'hsl(34, 44%, 69%)',
                'description' => 'Warm and earthy tan color',
                'is_featured' => false,
                'is_popular' => false,
                'order' => 33,
                'status' => true,
                'approval_status' => 'approved'
            ],
            [
                'name' => 'Olive',
                'code' => '#808000',
                'rgb' => 'rgb(128, 128, 0)',
                'hsl' => 'hsl(60, 100%, 25%)',
                'description' => 'Earthy and natural olive green color',
                'is_featured' => false,
                'is_popular' => false,
                'order' => 34,
                'status' => true,
                'approval_status' => 'approved'
            ],
            [
                'name' => 'Teal',
                'code' => '#008080',
                'rgb' => 'rgb(0, 128, 128)',
                'hsl' => 'hsl(180, 100%, 25%)',
                'description' => 'Deep and soothing teal color',
                'is_featured' => true,
                'is_popular' => true,
                'order' => 35,
                'status' => true,
                'approval_status' => 'approved'
            ],

            // ==================== JEWEL TONES ====================
            [
                'name' => 'Emerald',
                'code' => '#50C878',
                'rgb' => 'rgb(80, 200, 120)',
                'hsl' => 'hsl(140, 52%, 55%)',
                'description' => 'Rich and vibrant emerald green color',
                'is_featured' => false,
                'is_popular' => true,
                'order' => 36,
                'status' => true,
                'approval_status' => 'approved'
            ],
            [
                'name' => 'Ruby',
                'code' => '#E0115F',
                'rgb' => 'rgb(224, 17, 95)',
                'hsl' => 'hsl(337, 86%, 47%)',
                'description' => 'Rich and passionate ruby red color',
                'is_featured' => false,
                'is_popular' => false,
                'order' => 37,
                'status' => true,
                'approval_status' => 'approved'
            ],
            [
                'name' => 'Sapphire',
                'code' => '#0F52BA',
                'rgb' => 'rgb(15, 82, 186)',
                'hsl' => 'hsl(216, 85%, 39%)',
                'description' => 'Deep and royal sapphire blue color',
                'is_featured' => false,
                'is_popular' => false,
                'order' => 38,
                'status' => true,
                'approval_status' => 'approved'
            ],
            [
                'name' => 'Amethyst',
                'code' => '#9966CC',
                'rgb' => 'rgb(153, 102, 204)',
                'hsl' => 'hsl(270, 50%, 60%)',
                'description' => 'Rich and mystical amethyst purple color',
                'is_featured' => false,
                'is_popular' => false,
                'order' => 39,
                'status' => true,
                'approval_status' => 'approved'
            ],

            // ==================== METALLIC SHADES ====================
            [
                'name' => 'Bronze',
                'code' => '#CD7F32',
                'rgb' => 'rgb(205, 127, 50)',
                'hsl' => 'hsl(30, 61%, 50%)',
                'description' => 'Warm and earthy bronze metallic color',
                'is_featured' => false,
                'is_popular' => false,
                'order' => 40,
                'status' => true,
                'approval_status' => 'approved'
            ],
            [
                'name' => 'Copper',
                'code' => '#B87333',
                'rgb' => 'rgb(184, 115, 51)',
                'hsl' => 'hsl(29, 57%, 46%)',
                'description' => 'Warm and rustic copper color',
                'is_featured' => false,
                'is_popular' => false,
                'order' => 41,
                'status' => true,
                'approval_status' => 'approved'
            ],
            [
                'name' => 'Rose Gold',
                'code' => '#B76E79',
                'rgb' => 'rgb(183, 110, 121)',
                'hsl' => 'hsl(352, 34%, 57%)',
                'description' => 'Elegant and trendy rose gold color',
                'is_featured' => true,
                'is_popular' => true,
                'order' => 42,
                'status' => true,
                'approval_status' => 'approved'
            ],
        ];

        foreach ($colors as $color) {
            Color::create([
                'name' => $color['name'],
                'slug' => Str::slug($color['name']),
                'code' => $color['code'],
                'rgb' => $color['rgb'],
                'hsl' => $color['hsl'],
                'description' => $color['description'],
                'is_featured' => $color['is_featured'],
                'is_popular' => $color['is_popular'],
                'order' => $color['order'],
                'status' => $color['status'],
                'approval_status' => $color['approval_status'],
                'meta_title' => $color['name'] . ' Color - Shop Products',
                'meta_description' => 'Browse our collection of ' . strtolower($color['name']) . ' color products. Find the perfect ' . strtolower($color['name']) . ' items for your needs.',
                'meta_keywords' => strtolower($color['name']) . ', ' . strtolower($color['name']) . ' color, ' . strtolower($color['name']) . ' products',
                'focus_keyword' => strtolower($color['name']) . ' color',
                'og_title' => $color['name'] . ' Color Collection',
                'og_description' => 'Shop beautiful ' . strtolower($color['name']) . ' color products online.',
            ]);
        }
    }
}
