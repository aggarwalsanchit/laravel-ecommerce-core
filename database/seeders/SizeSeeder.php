<?php
// database/seeders/SizeSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Size;
use App\Models\Category;
use Illuminate\Support\Str;

class SizeSeeder extends Seeder
{
    public function run(): void
    {
        // First, get all category IDs by their names/paths
        $categories = Category::all()->keyBy('name');

        // ==================== UNISEX CLOTHING SIZES ====================
        $unisexFashionIds = $this->getCategoryIds($categories, [
            'Fashion',
            'Clothing',
            'T-Shirts',
            'Hoodies',
            'Sweaters',
            'Jackets'
        ]);

        $unisexSizes = [
            ['name' => 'XXS', 'code' => 'UNI-XXS', 'slug' => 'uni-xxs', 'gender' => 'Unisex', 'us_size' => '00', 'eu_size' => '30', 'uk_size' => '2', 'int_size' => 'XXS', 'order' => 1, 'is_featured' => false, 'is_popular' => false],
            ['name' => 'XS', 'code' => 'UNI-XS', 'slug' => 'uni-xs', 'gender' => 'Unisex', 'us_size' => '0-2', 'eu_size' => '32-34', 'uk_size' => '4-6', 'int_size' => 'XS', 'order' => 2, 'is_featured' => true, 'is_popular' => true],
            ['name' => 'Small', 'code' => 'UNI-S', 'slug' => 'uni-s', 'gender' => 'Unisex', 'us_size' => '4-6', 'eu_size' => '36-38', 'uk_size' => '8-10', 'int_size' => 'S', 'order' => 3, 'is_featured' => true, 'is_popular' => true],
            ['name' => 'Medium', 'code' => 'UNI-M', 'slug' => 'uni-m', 'gender' => 'Unisex', 'us_size' => '8-10', 'eu_size' => '40-42', 'uk_size' => '12-14', 'int_size' => 'M', 'order' => 4, 'is_featured' => true, 'is_popular' => true],
            ['name' => 'Large', 'code' => 'UNI-L', 'slug' => 'uni-l', 'gender' => 'Unisex', 'us_size' => '12-14', 'eu_size' => '44-46', 'uk_size' => '16-18', 'int_size' => 'L', 'order' => 5, 'is_featured' => true, 'is_popular' => true],
            ['name' => 'XL', 'code' => 'UNI-XL', 'slug' => 'uni-xl', 'gender' => 'Unisex', 'us_size' => '16-18', 'eu_size' => '48-50', 'uk_size' => '20-22', 'int_size' => 'XL', 'order' => 6, 'is_featured' => true, 'is_popular' => true],
            ['name' => 'XXL', 'code' => 'UNI-XXL', 'slug' => 'uni-xxl', 'gender' => 'Unisex', 'us_size' => '20-22', 'eu_size' => '52-54', 'uk_size' => '24-26', 'int_size' => 'XXL', 'order' => 7, 'is_featured' => false, 'is_popular' => true],
            ['name' => 'XXXL', 'code' => 'UNI-XXXL', 'slug' => 'uni-xxxl', 'gender' => 'Unisex', 'us_size' => '24-26', 'eu_size' => '56-58', 'uk_size' => '28-30', 'int_size' => 'XXXL', 'order' => 8, 'is_featured' => false, 'is_popular' => false],
        ];

        foreach ($unisexSizes as $sizeData) {
            $size = Size::updateOrCreate(
                ['code' => $sizeData['code']],
                [
                    'name' => $sizeData['name'],
                    'slug' => $sizeData['slug'],
                    'code' => $sizeData['code'],
                    'gender' => $sizeData['gender'],
                    'us_size' => $sizeData['us_size'],
                    'eu_size' => $sizeData['eu_size'] ?? null,
                    'uk_size' => $sizeData['uk_size'] ?? null,
                    'int_size' => $sizeData['int_size'],
                    'order' => $sizeData['order'],
                    'is_featured' => $sizeData['is_featured'],
                    'is_popular' => $sizeData['is_popular'],
                    'status' => true,
                    'approval_status' => 'approved',
                    'description' => "{$sizeData['name']} unisex size for clothing",
                    'meta_title' => "{$sizeData['name']} Size - Unisex Clothing",
                    'meta_description' => "{$sizeData['name']} size measurement guide for unisex clothing",
                ]
            );

            $size->categories()->syncWithoutDetaching($unisexFashionIds);
        }

        // ==================== MEN'S CLOTHING SIZES ====================
        $mensFashionIds = $this->getCategoryIds($categories, [
            'Fashion',
            'Men\'s Fashion',
            'Shirts',
            'T-Shirts',
            'Jeans',
            'Trousers',
            'Jackets',
            'Suits'
        ]);

        $menSizes = [
            ['name' => 'Men XS', 'code' => 'MEN-XS', 'slug' => 'men-xs', 'gender' => 'Men', 'us_size' => 'XS', 'eu_size' => '44', 'uk_size' => '34', 'int_size' => 'XS', 'order' => 10, 'is_featured' => true, 'is_popular' => true],
            ['name' => 'Men S', 'code' => 'MEN-S', 'slug' => 'men-s', 'gender' => 'Men', 'us_size' => 'S', 'eu_size' => '46', 'uk_size' => '36', 'int_size' => 'S', 'order' => 11, 'is_featured' => true, 'is_popular' => true],
            ['name' => 'Men M', 'code' => 'MEN-M', 'slug' => 'men-m', 'gender' => 'Men', 'us_size' => 'M', 'eu_size' => '48', 'uk_size' => '38', 'int_size' => 'M', 'order' => 12, 'is_featured' => true, 'is_popular' => true],
            ['name' => 'Men L', 'code' => 'MEN-L', 'slug' => 'men-l', 'gender' => 'Men', 'us_size' => 'L', 'eu_size' => '50', 'uk_size' => '40', 'int_size' => 'L', 'order' => 13, 'is_featured' => true, 'is_popular' => true],
            ['name' => 'Men XL', 'code' => 'MEN-XL', 'slug' => 'men-xl', 'gender' => 'Men', 'us_size' => 'XL', 'eu_size' => '52', 'uk_size' => '42', 'int_size' => 'XL', 'order' => 14, 'is_featured' => true, 'is_popular' => true],
            ['name' => 'Men XXL', 'code' => 'MEN-XXL', 'slug' => 'men-xxl', 'gender' => 'Men', 'us_size' => 'XXL', 'eu_size' => '54', 'uk_size' => '44', 'int_size' => 'XXL', 'order' => 15, 'is_featured' => false, 'is_popular' => true],
            ['name' => 'Men XXXL', 'code' => 'MEN-XXXL', 'slug' => 'men-xxxl', 'gender' => 'Men', 'us_size' => 'XXXL', 'eu_size' => '56', 'uk_size' => '46', 'int_size' => 'XXXL', 'order' => 16, 'is_featured' => false, 'is_popular' => false],
        ];

        foreach ($menSizes as $sizeData) {
            $size = Size::updateOrCreate(
                ['code' => $sizeData['code']],
                [
                    'name' => $sizeData['name'],
                    'slug' => $sizeData['slug'],
                    'code' => $sizeData['code'],
                    'gender' => $sizeData['gender'],
                    'us_size' => $sizeData['us_size'],
                    'eu_size' => $sizeData['eu_size'] ?? null,
                    'uk_size' => $sizeData['uk_size'] ?? null,
                    'int_size' => $sizeData['int_size'],
                    'order' => $sizeData['order'],
                    'is_featured' => $sizeData['is_featured'],
                    'is_popular' => $sizeData['is_popular'],
                    'status' => true,
                    'approval_status' => 'approved',
                    'description' => "{$sizeData['name']} size for men's clothing",
                    'meta_title' => "{$sizeData['name']} Size - Men's Clothing",
                    'meta_description' => "{$sizeData['name']} size measurement guide for men's clothing",
                ]
            );

            $size->categories()->syncWithoutDetaching($mensFashionIds);
        }

        // ==================== WOMEN'S CLOTHING SIZES ====================
        $womensFashionIds = $this->getCategoryIds($categories, [
            'Fashion',
            'Women\'s Fashion',
            'Dresses',
            'Tops',
            'Jeans',
            'Skirts',
            'Kurtis',
            'Sarees'
        ]);

        $womenSizes = [
            ['name' => 'Women XXS', 'code' => 'WOMEN-XXS', 'slug' => 'women-xxs', 'gender' => 'Women', 'us_size' => '00', 'eu_size' => '30', 'uk_size' => '2', 'int_size' => 'XXS', 'order' => 20, 'is_featured' => false, 'is_popular' => false],
            ['name' => 'Women XS', 'code' => 'WOMEN-XS', 'slug' => 'women-xs', 'gender' => 'Women', 'us_size' => '0-2', 'eu_size' => '32-34', 'uk_size' => '4-6', 'int_size' => 'XS', 'order' => 21, 'is_featured' => true, 'is_popular' => true],
            ['name' => 'Women S', 'code' => 'WOMEN-S', 'slug' => 'women-s', 'gender' => 'Women', 'us_size' => '4-6', 'eu_size' => '36-38', 'uk_size' => '8-10', 'int_size' => 'S', 'order' => 22, 'is_featured' => true, 'is_popular' => true],
            ['name' => 'Women M', 'code' => 'WOMEN-M', 'slug' => 'women-m', 'gender' => 'Women', 'us_size' => '8-10', 'eu_size' => '40-42', 'uk_size' => '12-14', 'int_size' => 'M', 'order' => 23, 'is_featured' => true, 'is_popular' => true],
            ['name' => 'Women L', 'code' => 'WOMEN-L', 'slug' => 'women-l', 'gender' => 'Women', 'us_size' => '12-14', 'eu_size' => '44-46', 'uk_size' => '16-18', 'int_size' => 'L', 'order' => 24, 'is_featured' => true, 'is_popular' => true],
            ['name' => 'Women XL', 'code' => 'WOMEN-XL', 'slug' => 'women-xl', 'gender' => 'Women', 'us_size' => '16-18', 'eu_size' => '48-50', 'uk_size' => '20-22', 'int_size' => 'XL', 'order' => 25, 'is_featured' => true, 'is_popular' => true],
            ['name' => 'Women XXL', 'code' => 'WOMEN-XXL', 'slug' => 'women-xxl', 'gender' => 'Women', 'us_size' => '20-22', 'eu_size' => '52-54', 'uk_size' => '24-26', 'int_size' => 'XXL', 'order' => 26, 'is_featured' => false, 'is_popular' => false],
            ['name' => 'Women XXXL', 'code' => 'WOMEN-XXXL', 'slug' => 'women-xxxl', 'gender' => 'Women', 'us_size' => '24-26', 'eu_size' => '56-58', 'uk_size' => '28-30', 'int_size' => 'XXXL', 'order' => 27, 'is_featured' => false, 'is_popular' => false],
        ];

        foreach ($womenSizes as $sizeData) {
            $size = Size::updateOrCreate(
                ['code' => $sizeData['code']],
                [
                    'name' => $sizeData['name'],
                    'slug' => $sizeData['slug'],
                    'code' => $sizeData['code'],
                    'gender' => $sizeData['gender'],
                    'us_size' => $sizeData['us_size'],
                    'eu_size' => $sizeData['eu_size'] ?? null,
                    'uk_size' => $sizeData['uk_size'] ?? null,
                    'int_size' => $sizeData['int_size'],
                    'order' => $sizeData['order'],
                    'is_featured' => $sizeData['is_featured'],
                    'is_popular' => $sizeData['is_popular'],
                    'status' => true,
                    'approval_status' => 'approved',
                    'description' => "{$sizeData['name']} size for women's clothing",
                    'meta_title' => "{$sizeData['name']} Size - Women's Clothing",
                    'meta_description' => "{$sizeData['name']} size measurement guide for women's clothing",
                ]
            );

            $size->categories()->syncWithoutDetaching($womensFashionIds);
        }

        // ==================== KIDS CLOTHING SIZES ====================
        $kidsFashionIds = $this->getCategoryIds($categories, [
            'Fashion',
            'Kids\' Fashion',
            'Baby Products',
            'Clothing'
        ]);

        $kidsSizes = [
            ['name' => 'Newborn', 'code' => 'KIDS-NB', 'slug' => 'kids-nb', 'gender' => 'Unisex', 'us_size' => 'NB', 'int_size' => 'NB', 'order' => 30, 'is_featured' => true, 'is_popular' => true],
            ['name' => '0-3 Months', 'code' => 'KIDS-0-3M', 'slug' => 'kids-0-3m', 'gender' => 'Unisex', 'us_size' => '0-3M', 'int_size' => '0-3M', 'order' => 31, 'is_featured' => true, 'is_popular' => true],
            ['name' => '3-6 Months', 'code' => 'KIDS-3-6M', 'slug' => 'kids-3-6m', 'gender' => 'Unisex', 'us_size' => '3-6M', 'int_size' => '3-6M', 'order' => 32, 'is_featured' => true, 'is_popular' => true],
            ['name' => '6-9 Months', 'code' => 'KIDS-6-9M', 'slug' => 'kids-6-9m', 'gender' => 'Unisex', 'us_size' => '6-9M', 'int_size' => '6-9M', 'order' => 33, 'is_featured' => true, 'is_popular' => true],
            ['name' => '9-12 Months', 'code' => 'KIDS-9-12M', 'slug' => 'kids-9-12m', 'gender' => 'Unisex', 'us_size' => '9-12M', 'int_size' => '9-12M', 'order' => 34, 'is_featured' => false, 'is_popular' => true],
            ['name' => '12-18 Months', 'code' => 'KIDS-12-18M', 'slug' => 'kids-12-18m', 'gender' => 'Unisex', 'us_size' => '12-18M', 'int_size' => '12-18M', 'order' => 35, 'is_featured' => false, 'is_popular' => false],
            ['name' => '18-24 Months', 'code' => 'KIDS-18-24M', 'slug' => 'kids-18-24m', 'gender' => 'Unisex', 'us_size' => '18-24M', 'int_size' => '18-24M', 'order' => 36, 'is_featured' => false, 'is_popular' => false],
            ['name' => '2T', 'code' => 'KIDS-2T', 'slug' => 'kids-2t', 'gender' => 'Unisex', 'us_size' => '2T', 'int_size' => '2T', 'order' => 37, 'is_featured' => false, 'is_popular' => true],
            ['name' => '3T', 'code' => 'KIDS-3T', 'slug' => 'kids-3t', 'gender' => 'Unisex', 'us_size' => '3T', 'int_size' => '3T', 'order' => 38, 'is_featured' => false, 'is_popular' => true],
            ['name' => '4T', 'code' => 'KIDS-4T', 'slug' => 'kids-4t', 'gender' => 'Unisex', 'us_size' => '4T', 'int_size' => '4T', 'order' => 39, 'is_featured' => false, 'is_popular' => true],
            ['name' => '5', 'code' => 'KIDS-5', 'slug' => 'kids-5', 'gender' => 'Unisex', 'us_size' => '5', 'int_size' => '5', 'order' => 40, 'is_featured' => false, 'is_popular' => false],
            ['name' => '6', 'code' => 'KIDS-6', 'slug' => 'kids-6', 'gender' => 'Unisex', 'us_size' => '6', 'int_size' => '6', 'order' => 41, 'is_featured' => false, 'is_popular' => false],
            ['name' => '7', 'code' => 'KIDS-7', 'slug' => 'kids-7', 'gender' => 'Unisex', 'us_size' => '7', 'int_size' => '7', 'order' => 42, 'is_featured' => false, 'is_popular' => false],
            ['name' => '8', 'code' => 'KIDS-8', 'slug' => 'kids-8', 'gender' => 'Unisex', 'us_size' => '8', 'int_size' => '8', 'order' => 43, 'is_featured' => false, 'is_popular' => false],
            ['name' => '10', 'code' => 'KIDS-10', 'slug' => 'kids-10', 'gender' => 'Unisex', 'us_size' => '10', 'int_size' => '10', 'order' => 44, 'is_featured' => false, 'is_popular' => false],
            ['name' => '12', 'code' => 'KIDS-12', 'slug' => 'kids-12', 'gender' => 'Unisex', 'us_size' => '12', 'int_size' => '12', 'order' => 45, 'is_featured' => false, 'is_popular' => false],
            ['name' => '14', 'code' => 'KIDS-14', 'slug' => 'kids-14', 'gender' => 'Unisex', 'us_size' => '14', 'int_size' => '14', 'order' => 46, 'is_featured' => false, 'is_popular' => false],
        ];

        foreach ($kidsSizes as $sizeData) {
            $size = Size::updateOrCreate(
                ['code' => $sizeData['code']],
                [
                    'name' => $sizeData['name'],
                    'slug' => $sizeData['slug'],
                    'code' => $sizeData['code'],
                    'gender' => $sizeData['gender'],
                    'us_size' => $sizeData['us_size'],
                    'int_size' => $sizeData['int_size'] ?? null,
                    'order' => $sizeData['order'],
                    'is_featured' => $sizeData['is_featured'],
                    'is_popular' => $sizeData['is_popular'],
                    'status' => true,
                    'approval_status' => 'approved',
                    'description' => "{$sizeData['name']} size for kids clothing",
                    'meta_title' => "{$sizeData['name']} Size - Kids Clothing",
                    'meta_description' => "{$sizeData['name']} size measurement guide for kids clothing",
                ]
            );

            $size->categories()->syncWithoutDetaching($kidsFashionIds);
        }

        // ==================== UNISEX SHOE SIZES ====================
        $unisexShoeIds = $this->getCategoryIds($categories, [
            'Fashion',
            'Footwear',
            'Shoes',
            'Sports Shoes',
            'Casual Shoes',
            'Sandals'
        ]);

        $unisexShoeSizes = [
            ['name' => 'US 4', 'code' => 'SHOE-US4', 'slug' => 'shoe-us-4', 'gender' => 'Unisex', 'us_size' => '4', 'uk_size' => '2', 'eu_size' => '34', 'order' => 50],
            ['name' => 'US 5', 'code' => 'SHOE-US5', 'slug' => 'shoe-us-5', 'gender' => 'Unisex', 'us_size' => '5', 'uk_size' => '3', 'eu_size' => '35', 'order' => 51],
            ['name' => 'US 6', 'code' => 'SHOE-US6', 'slug' => 'shoe-us-6', 'gender' => 'Unisex', 'us_size' => '6', 'uk_size' => '4', 'eu_size' => '36', 'order' => 52, 'is_featured' => true, 'is_popular' => true],
            ['name' => 'US 7', 'code' => 'SHOE-US7', 'slug' => 'shoe-us-7', 'gender' => 'Unisex', 'us_size' => '7', 'uk_size' => '5', 'eu_size' => '37', 'order' => 53, 'is_featured' => true, 'is_popular' => true],
            ['name' => 'US 8', 'code' => 'SHOE-US8', 'slug' => 'shoe-us-8', 'gender' => 'Unisex', 'us_size' => '8', 'uk_size' => '6', 'eu_size' => '38', 'order' => 54, 'is_featured' => true, 'is_popular' => true],
            ['name' => 'US 9', 'code' => 'SHOE-US9', 'slug' => 'shoe-us-9', 'gender' => 'Unisex', 'us_size' => '9', 'uk_size' => '7', 'eu_size' => '39', 'order' => 55, 'is_featured' => true, 'is_popular' => true],
            ['name' => 'US 10', 'code' => 'SHOE-US10', 'slug' => 'shoe-us-10', 'gender' => 'Unisex', 'us_size' => '10', 'uk_size' => '8', 'eu_size' => '40', 'order' => 56, 'is_featured' => true, 'is_popular' => true],
            ['name' => 'US 11', 'code' => 'SHOE-US11', 'slug' => 'shoe-us-11', 'gender' => 'Unisex', 'us_size' => '11', 'uk_size' => '9', 'eu_size' => '41', 'order' => 57],
            ['name' => 'US 12', 'code' => 'SHOE-US12', 'slug' => 'shoe-us-12', 'gender' => 'Unisex', 'us_size' => '12', 'uk_size' => '10', 'eu_size' => '42', 'order' => 58],
            ['name' => 'US 13', 'code' => 'SHOE-US13', 'slug' => 'shoe-us-13', 'gender' => 'Unisex', 'us_size' => '13', 'uk_size' => '11', 'eu_size' => '43', 'order' => 59],
            ['name' => 'US 14', 'code' => 'SHOE-US14', 'slug' => 'shoe-us-14', 'gender' => 'Unisex', 'us_size' => '14', 'uk_size' => '12', 'eu_size' => '44', 'order' => 60],
        ];

        foreach ($unisexShoeSizes as $sizeData) {
            $size = Size::updateOrCreate(
                ['code' => $sizeData['code']],
                [
                    'name' => $sizeData['name'],
                    'slug' => $sizeData['slug'],
                    'code' => $sizeData['code'],
                    'gender' => $sizeData['gender'],
                    'us_size' => $sizeData['us_size'],
                    'uk_size' => $sizeData['uk_size'] ?? null,
                    'eu_size' => $sizeData['eu_size'] ?? null,
                    'order' => $sizeData['order'],
                    'is_featured' => $sizeData['is_featured'] ?? false,
                    'is_popular' => $sizeData['is_popular'] ?? true,
                    'status' => true,
                    'approval_status' => 'approved',
                    'description' => "{$sizeData['name']} unisex shoe size",
                    'meta_title' => "{$sizeData['name']} - Shoe Size",
                    'meta_description' => "{$sizeData['name']} shoe size conversion chart",
                ]
            );

            $size->categories()->syncWithoutDetaching($unisexShoeIds);
        }

        // ==================== MEN'S SHOE SIZES (Additional larger sizes) ====================
        $mensShoeIds = $this->getCategoryIds($categories, [
            'Fashion',
            'Men\'s Fashion',
            'Footwear',
            'Shoes',
            'Sports Shoes'
        ]);

        $menShoeSizes = [
            ['name' => 'US 15', 'code' => 'SHOE-MEN-US15', 'slug' => 'shoe-men-us-15', 'gender' => 'Men', 'us_size' => '15', 'uk_size' => '14', 'eu_size' => '49', 'order' => 70],
            ['name' => 'US 16', 'code' => 'SHOE-MEN-US16', 'slug' => 'shoe-men-us-16', 'gender' => 'Men', 'us_size' => '16', 'uk_size' => '15', 'eu_size' => '50', 'order' => 71],
        ];

        foreach ($menShoeSizes as $sizeData) {
            $size = Size::updateOrCreate(
                ['code' => $sizeData['code']],
                [
                    'name' => $sizeData['name'],
                    'slug' => $sizeData['slug'],
                    'code' => $sizeData['code'],
                    'gender' => $sizeData['gender'],
                    'us_size' => $sizeData['us_size'],
                    'uk_size' => $sizeData['uk_size'] ?? null,
                    'eu_size' => $sizeData['eu_size'] ?? null,
                    'order' => $sizeData['order'],
                    'is_featured' => false,
                    'is_popular' => false,
                    'status' => true,
                    'approval_status' => 'approved',
                    'description' => "{$sizeData['name']} size for men's shoes",
                ]
            );

            $size->categories()->syncWithoutDetaching($mensShoeIds);
        }

        // ==================== WOMEN'S SHOE SIZES (Additional smaller sizes) ====================
        $womensShoeIds = $this->getCategoryIds($categories, [
            'Fashion',
            'Women\'s Fashion',
            'Footwear',
            'Shoes',
            'Heels',
            'Flats'
        ]);

        $womenShoeSizes = [
            ['name' => 'US 3', 'code' => 'SHOE-WOMEN-US3', 'slug' => 'shoe-women-us-3', 'gender' => 'Women', 'us_size' => '3', 'uk_size' => '1', 'eu_size' => '33', 'order' => 80],
            ['name' => 'US 4', 'code' => 'SHOE-WOMEN-US4', 'slug' => 'shoe-women-us-4', 'gender' => 'Women', 'us_size' => '4', 'uk_size' => '2', 'eu_size' => '34', 'order' => 81],
        ];

        foreach ($womenShoeSizes as $sizeData) {
            $size = Size::updateOrCreate(
                ['code' => $sizeData['code']],
                [
                    'name' => $sizeData['name'],
                    'slug' => $sizeData['slug'],
                    'code' => $sizeData['code'],
                    'gender' => $sizeData['gender'],
                    'us_size' => $sizeData['us_size'],
                    'uk_size' => $sizeData['uk_size'] ?? null,
                    'eu_size' => $sizeData['eu_size'] ?? null,
                    'order' => $sizeData['order'],
                    'is_featured' => false,
                    'is_popular' => false,
                    'status' => true,
                    'approval_status' => 'approved',
                    'description' => "{$sizeData['name']} size for women's shoes",
                ]
            );

            $size->categories()->syncWithoutDetaching($womensShoeIds);
        }

        // ==================== PLUS SIZE (Unisex) ====================
        $plusSizeIds = $this->getCategoryIds($categories, [
            'Fashion',
            'Plus Size',
            'Clothing'
        ]);

        $plusSizes = [
            ['name' => '1X', 'code' => 'PLUS-1X', 'slug' => 'plus-1x', 'gender' => 'Unisex', 'us_size' => '1X', 'int_size' => '1X', 'order' => 90, 'is_featured' => true, 'is_popular' => true],
            ['name' => '2X', 'code' => 'PLUS-2X', 'slug' => 'plus-2x', 'gender' => 'Unisex', 'us_size' => '2X', 'int_size' => '2X', 'order' => 91, 'is_featured' => true, 'is_popular' => true],
            ['name' => '3X', 'code' => 'PLUS-3X', 'slug' => 'plus-3x', 'gender' => 'Unisex', 'us_size' => '3X', 'int_size' => '3X', 'order' => 92, 'is_featured' => true, 'is_popular' => true],
            ['name' => '4X', 'code' => 'PLUS-4X', 'slug' => 'plus-4x', 'gender' => 'Unisex', 'us_size' => '4X', 'int_size' => '4X', 'order' => 93, 'is_featured' => false, 'is_popular' => true],
            ['name' => '5X', 'code' => 'PLUS-5X', 'slug' => 'plus-5x', 'gender' => 'Unisex', 'us_size' => '5X', 'int_size' => '5X', 'order' => 94, 'is_featured' => false, 'is_popular' => false],
        ];

        foreach ($plusSizes as $sizeData) {
            $size = Size::updateOrCreate(
                ['code' => $sizeData['code']],
                [
                    'name' => $sizeData['name'],
                    'slug' => $sizeData['slug'],
                    'code' => $sizeData['code'],
                    'gender' => $sizeData['gender'],
                    'us_size' => $sizeData['us_size'],
                    'int_size' => $sizeData['int_size'] ?? null,
                    'order' => $sizeData['order'],
                    'is_featured' => $sizeData['is_featured'],
                    'is_popular' => $sizeData['is_popular'],
                    'status' => true,
                    'approval_status' => 'approved',
                    'description' => "{$sizeData['name']} plus size for clothing",
                    'meta_title' => "{$sizeData['name']} Size - Plus Size Clothing",
                    'meta_description' => "{$sizeData['name']} plus size measurement guide",
                ]
            );

            $size->categories()->syncWithoutDetaching($plusSizeIds);
        }

        $this->command->info('Sizes seeded successfully with unique codes and slugs!');
        $this->command->info('Total sizes created: ' . Size::count());
    }

    /**
     * Get category IDs by their names/paths
     */
    private function getCategoryIds($categories, $categoryNames)
    {
        $ids = [];
        foreach ($categoryNames as $name) {
            if (isset($categories[$name])) {
                $ids[] = $categories[$name]->id;
                // Also get all child categories
                $childIds = $this->getChildCategoryIds($categories[$name]);
                $ids = array_merge($ids, $childIds);
            }
        }
        return array_unique($ids);
    }

    /**
     * Get all child category IDs recursively
     */
    private function getChildCategoryIds($category)
    {
        $ids = [];
        if ($category->relationLoaded('children')) {
            foreach ($category->children as $child) {
                $ids[] = $child->id;
                $ids = array_merge($ids, $this->getChildCategoryIds($child));
            }
        }
        return $ids;
    }
}
