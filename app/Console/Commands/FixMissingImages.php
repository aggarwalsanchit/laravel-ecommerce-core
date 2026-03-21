<?php
// app/Console/Commands/FixMissingImages.php

namespace App\Console\Commands;

use App\Models\Category;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class FixMissingImages extends Command
{
    protected $signature = 'images:fix';
    protected $description = 'Remove database references to missing images';

    public function handle()
    {
        $this->info('Fixing missing image references...');

        $categories = Category::all();
        $fixed = 0;

        foreach ($categories as $category) {
            $updated = false;

            // Fix main image
            if ($category->image) {
                $path = 'categories/' . $category->image;
                if (!Storage::disk('public')->exists($path)) {
                    $this->warn("Removing missing main image: {$category->name} - {$category->image}");
                    $category->image = null;
                    $updated = true;
                    $fixed++;
                }
            }

            // Fix thumbnail
            if ($category->thumbnail_image) {
                $path = 'categories/thumbnails/' . $category->thumbnail_image;
                if (!Storage::disk('public')->exists($path)) {
                    $this->warn("Removing missing thumbnail: {$category->name} - {$category->thumbnail_image}");
                    $category->thumbnail_image = null;
                    $updated = true;
                    $fixed++;
                }
            }

            // Fix banner
            if ($category->banner_image) {
                $path = 'categories/banners/' . $category->banner_image;
                if (!Storage::disk('public')->exists($path)) {
                    $this->warn("Removing missing banner: {$category->name} - {$category->banner_image}");
                    $category->banner_image = null;
                    $updated = true;
                    $fixed++;
                }
            }

            if ($updated) {
                $category->save();
            }
        }

        $this->info("Fixed {$fixed} missing image references.");
    }
}
