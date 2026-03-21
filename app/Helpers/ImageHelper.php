<?php
// app/Helpers/ImageHelper.php

use Illuminate\Support\Facades\Storage;

if (!function_exists('getImageSize')) {
    /**
     * Get human readable image size with error handling
     */
    function getImageSize($path)
    {
        if (!$path) {
            return 'No image';
        }

        try {
            // Check if the file exists in storage
            if (Storage::disk('public')->exists($path)) {
                $size = Storage::disk('public')->size($path);

                if ($size >= 1048576) {
                    return round($size / 1048576, 2) . ' MB';
                } elseif ($size >= 1024) {
                    return round($size / 1024, 2) . ' KB';
                }
                return $size . ' bytes';
            }
            return 'File not found';
        } catch (\Exception $e) {
            \Log::error('Error getting image size: ' . $e->getMessage());
            return 'Error';
        }
    }
}

if (!function_exists('getImageUrl')) {
    /**
     * Get image URL with fallback
     */
    function getImageUrl($path, $default = null)
    {
        if (!$path) {
            return $default ?? asset('images/placeholder.jpg');
        }

        try {
            if (Storage::disk('public')->exists($path)) {
                return Storage::disk('public')->url($path);
            }
            return $default ?? asset('images/placeholder.jpg');
        } catch (\Exception $e) {
            \Log::error('Error getting image URL: ' . $e->getMessage());
            return $default ?? asset('images/placeholder.jpg');
        }
    }
}

if (!function_exists('imageExists')) {
    /**
     * Check if image exists
     */
    function imageExists($path)
    {
        if (!$path) {
            return false;
        }

        try {
            return Storage::disk('public')->exists($path);
        } catch (\Exception $e) {
            return false;
        }
    }
}
