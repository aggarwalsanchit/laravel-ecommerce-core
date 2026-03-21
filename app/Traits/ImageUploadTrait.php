<?php
// app/Traits/ImageUploadTrait.php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

trait ImageUploadTrait
{
    /**
     * Upload and compress image
     *
     * @param UploadedFile $file
     * @param string $folder
     * @param int $width
     * @param int $height
     * @param int $quality
     * @return string|null
     */
    public function uploadImage($file, $folder = 'images', $width = null, $height = null, $quality = 80)
    {
        if (!$file) {
            return null;
        }

        try {
            // Create image manager with GD driver
            $manager = new ImageManager(new Driver());

            // Read image from uploaded file
            $image = $manager->read($file);

            // Get original dimensions
            $originalWidth = $image->width();
            $originalHeight = $image->height();

            // Resize if dimensions are specified
            if ($width && $height) {
                // Calculate aspect ratio
                $ratio = $originalWidth / $originalHeight;

                if ($width / $height > $ratio) {
                    $width = $height * $ratio;
                } else {
                    $height = $width / $ratio;
                }

                $image->resize($width, $height);
            } elseif ($width) {
                // Resize by width only, maintain aspect ratio
                $image->resize($width, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            } elseif ($height) {
                // Resize by height only, maintain aspect ratio
                $image->resize(null, $height, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            }

            // Encode with quality
            $encoded = $image->toJpeg($quality);

            // Generate unique filename
            $filename = time() . '_' . uniqid() . '.jpg';
            $path = $folder . '/' . $filename;

            // Store the image
            Storage::disk('public')->put($path, $encoded);

            // Get file size for logging
            $size = Storage::disk('public')->size($path);
            $sizeKB = round($size / 1024, 2);

            \Log::info('Image uploaded', [
                'path' => $path,
                'original_size' => $file->getSize() / 1024 . ' KB',
                'compressed_size' => $sizeKB . ' KB',
                'compression_ratio' => round(($sizeKB / ($file->getSize() / 1024)) * 100, 2) . '%'
            ]);

            return $filename;
        } catch (\Exception $e) {
            \Log::error('Image upload failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Upload multiple images
     *
     * @param array $files
     * @param string $folder
     * @param int $width
     * @param int $height
     * @param int $quality
     * @return array
     */
    public function uploadMultipleImages($files, $folder = 'images', $width = null, $height = null, $quality = 80)
    {
        $uploadedFiles = [];

        foreach ($files as $file) {
            $uploadedFiles[] = $this->uploadImage($file, $folder, $width, $height, $quality);
        }

        return array_filter($uploadedFiles);
    }

    /**
     * Upload category image with specific dimensions
     *
     * @param UploadedFile $file
     * @return string|null
     */
    public function uploadCategoryImage($file)
    {
        // Upload main category image (800x800)
        return $this->uploadImage($file, 'categories', 800, 800, 85);
    }

    /**
     * Upload category thumbnail
     *
     * @param UploadedFile $file
     * @return string|null
     */
    public function uploadCategoryThumbnail($file)
    {
        // Upload thumbnail (150x150)
        return $this->uploadImage($file, 'categories/thumbnails', 150, 150, 80);
    }

    /**
     * Upload category banner
     *
     * @param UploadedFile $file
     * @return string|null
     */
    public function uploadCategoryBanner($file)
    {
        // Upload banner (1920x400)
        return $this->uploadImage($file, 'categories/banners', 1920, 400, 90);
    }

    /**
     * Upload product image with multiple sizes
     *
     * @param UploadedFile $file
     * @return array
     */
    public function uploadProductImage($file)
    {
        $sizes = [
            'original' => $this->uploadImage($file, 'products/original', null, null, 90),
            'large' => $this->uploadImage($file, 'products/large', 800, 800, 85),
            'medium' => $this->uploadImage($file, 'products/medium', 400, 400, 80),
            'small' => $this->uploadImage($file, 'products/small', 150, 150, 75),
            'thumbnail' => $this->uploadImage($file, 'products/thumbnails', 100, 100, 70),
        ];

        return $sizes;
    }

    /**
     * Delete image
     *
     * @param string $path
     * @return bool
     */
    public function deleteImage($path)
    {
        if ($path && Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }

        return false;
    }

    /**
     * Delete multiple images
     *
     * @param array $paths
     * @return int
     */
    public function deleteMultipleImages($paths)
    {
        $deleted = 0;

        foreach ($paths as $path) {
            if ($this->deleteImage($path)) {
                $deleted++;
            }
        }

        return $deleted;
    }

    /**
     * Get image URL
     *
     * @param string $path
     * @param string $default
     * @return string
     */
    public function getImageUrl($path, $default = null)
    {
        if ($path && Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->url($path);
        }

        return $default ?? asset('images/placeholder.jpg');
    }

    /**
     * Get optimized image HTML
     *
     * @param string $path
     * @param string $alt
     * @param string $class
     * @param int $width
     * @param int $height
     * @return string
     */
    public function getOptimizedImageHtml($path, $alt = '', $class = '', $width = null, $height = null)
    {
        $url = $this->getImageUrl($path);

        if (!$url) {
            return '';
        }

        $attributes = [
            'src' => $url,
            'alt' => $alt,
            'class' => $class,
            'loading' => 'lazy',
        ];

        if ($width) {
            $attributes['width'] = $width;
        }

        if ($height) {
            $attributes['height'] = $height;
        }

        $html = '<img ';
        foreach ($attributes as $key => $value) {
            $html .= $key . '="' . $value . '" ';
        }
        $html .= '>';

        return $html;
    }
}
