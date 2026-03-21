<?php
// app/Console/Commands/OptimizeImages.php

namespace App\Console\Commands;

use App\Models\Category;
use App\Services\ImageCompressionService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class OptimizeImages extends Command
{
    protected $signature = 'images:optimize {--category} {--product}';
    protected $description = 'Optimize existing images';

    protected $compressor;

    public function __construct(ImageCompressionService $compressor)
    {
        parent::__construct();
        $this->compressor = $compressor;
    }

    public function handle()
    {
        $this->info('Starting image optimization...');

        if ($this->option('category')) {
            $this->optimizeCategoryImages();
        }

        if ($this->option('product')) {
            $this->optimizeProductImages();
        }

        $this->info('Image optimization completed!');
    }

    protected function optimizeCategoryImages()
    {
        $categories = Category::all();
        $bar = $this->output->createProgressBar(count($categories));

        foreach ($categories as $category) {
            if ($category->image) {
                $path = 'categories/' . $category->image;
                if (Storage::disk('public')->exists($path)) {
                    $file = Storage::disk('public')->path($path);
                    $optimized = $this->compressor->compress(
                        new \Illuminate\Http\UploadedFile($file, $category->image),
                        'categories',
                        800,
                        85
                    );

                    if ($optimized['success']) {
                        Storage::disk('public')->delete($path);
                        $category->image = $optimized['filename'];
                        $category->save();
                    }
                }
            }
            $bar->advance();
        }

        $bar->finish();
        $this->line('');
        $this->info('Category images optimized!');
    }

    protected function optimizeProductImages()
    {
        // Add product optimization logic here
    }
}
