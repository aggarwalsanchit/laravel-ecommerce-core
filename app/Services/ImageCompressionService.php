<?php
// app/Services/ImageCompressionService.php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageCompressionService
{
    protected $manager;

    public function __construct()
    {
        $this->manager = new ImageManager(new Driver());
    }

    /**
     * Compress image with intelligent quality
     */
    public function compress($file, $folder = 'images', $maxWidth = 1920, $quality = 85)
    {
        try {
            $image = $this->manager->read($file);

            // Get original size
            $originalSize = $file->getSize();
            $originalWidth = $image->width();
            $originalHeight = $image->height();

            // Calculate optimal dimensions
            $dimensions = $this->calculateOptimalDimensions($originalWidth, $originalHeight, $maxWidth);

            // Resize if needed
            if ($dimensions['width'] < $originalWidth) {
                $image->resize($dimensions['width'], $dimensions['height']);
            }

            // Adjust quality based on file size
            $optimalQuality = $this->calculateOptimalQuality($originalSize, $quality);

            // Encode with optimal quality
            $encoded = $image->toJpeg($optimalQuality);

            // Generate filename
            $filename = time() . '_' . uniqid() . '.jpg';
            $path = $folder . '/' . $filename;

            // Store compressed image
            Storage::disk('public')->put($path, $encoded);

            // Get compressed size
            $compressedSize = Storage::disk('public')->size($path);
            $compressionRatio = round((1 - ($compressedSize / $originalSize)) * 100, 2);

            return [
                'success' => true,
                'path' => $path,
                'filename' => $filename,
                'original_size' => $this->formatBytes($originalSize),
                'compressed_size' => $this->formatBytes($compressedSize),
                'compression_ratio' => $compressionRatio,
                'dimensions' => [
                    'original' => "{$originalWidth}x{$originalHeight}",
                    'compressed' => "{$dimensions['width']}x{$dimensions['height']}"
                ]
            ];
        } catch (\Exception $e) {
            \Log::error('Image compression failed: ' . $e->getMessage());

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Calculate optimal dimensions
     */
    protected function calculateOptimalDimensions($width, $height, $maxWidth)
    {
        if ($width <= $maxWidth) {
            return ['width' => $width, 'height' => $height];
        }

        $ratio = $width / $height;
        $newWidth = $maxWidth;
        $newHeight = $maxWidth / $ratio;

        return [
            'width' => round($newWidth),
            'height' => round($newHeight)
        ];
    }

    /**
     * Calculate optimal quality based on file size
     */
    protected function calculateOptimalQuality($fileSize, $defaultQuality = 85)
    {
        $sizeMB = $fileSize / 1048576;

        if ($sizeMB > 5) {
            return 70; // Very large files
        } elseif ($sizeMB > 2) {
            return 75; // Large files
        } elseif ($sizeMB > 1) {
            return 80; // Medium files
        }

        return $defaultQuality; // Small files
    }

    /**
     * Format bytes to human readable
     */
    protected function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    /**
     * Create WebP version for better compression
     */
    public function createWebP($file, $folder = 'images', $quality = 80)
    {
        try {
            $image = $this->manager->read($file);

            $filename = time() . '_' . uniqid() . '.webp';
            $path = $folder . '/webp/' . $filename;

            $encoded = $image->toWebp($quality);
            Storage::disk('public')->put($path, $encoded);

            return [
                'success' => true,
                'path' => $path,
                'filename' => $filename
            ];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Batch compress images
     */
    public function batchCompress($files, $folder = 'images', $maxWidth = 1920, $quality = 85)
    {
        $results = [];

        foreach ($files as $file) {
            $results[] = $this->compress($file, $folder, $maxWidth, $quality);
        }

        return $results;
    }
}
