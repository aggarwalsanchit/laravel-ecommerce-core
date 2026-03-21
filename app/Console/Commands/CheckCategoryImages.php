<?php
// app/Console/Commands/CheckCategoryImages.php

namespace App\Console\Commands;

use App\Models\Category;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CheckCategoryImages extends Command
{
    protected $signature = 'images:check';
    protected $description = 'Check category images and report missing files';

    public function handle()
    {
        $this->info('Checking category images...');
        $this->line('');

        $categories = Category::all();
        $missing = 0;
        $found = 0;

        foreach ($categories as $category) {
            // Check main image
            if ($category->image) {
                $path = 'categories/' . $category->image;
                if (Storage::disk('public')->exists($path)) {
                    $found++;
                    $this->info("✓ Main image found: {$category->name} - {$category->image}");
                } else {
                    $missing++;
                    $this->error("✗ Main image missing: {$category->name} - {$category->image}");
                }
            }

            // Check thumbnail
            if ($category->thumbnail_image) {
                $path = 'categories/thumbnails/' . $category->thumbnail_image;
                if (Storage::disk('public')->exists($path)) {
                    $found++;
                    $this->info("✓ Thumbnail found: {$category->name} - {$category->thumbnail_image}");
                } else {
                    $missing++;
                    $this->error("✗ Thumbnail missing: {$category->name} - {$category->thumbnail_image}");
                }
            }

            // Check banner
            if ($category->banner_image) {
                $path = 'categories/banners/' . $category->banner_image;
                if (Storage::disk('public')->exists($path)) {
                    $found++;
                    $this->info("✓ Banner found: {$category->name} - {$category->banner_image}");
                } else {
                    $missing++;
                    $this->error("✗ Banner missing: {$category->name} - {$category->banner_image}");
                }
            }
        }

        $this->line('');
        $this->info("Summary:");
        $this->line("Found: {$found} images");
        $this->line("Missing: {$missing} images");

        if ($missing > 0) {
            $this->warn('Run "php artisan images:cleanup" to remove missing image references from database?');
        }
    }
}
