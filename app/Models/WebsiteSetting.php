<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class WebsiteSetting extends Model
{
    protected $table = 'website_settings';

    protected $fillable = [
        // Basic Information
        'website_name',
        'website_tagline',
        'website_description',
        'website_keywords',

        // Logos & Favicon
        'logo_light',
        'logo_dark',
        'logo_favicon',
        'logo_small',

        // Contact Information
        'contact_email',
        'contact_phone',
        'contact_whatsapp',
        'contact_address',
        'contact_city',
        'contact_state',
        'contact_country',
        'contact_postal_code',
        'contact_map_url',

        // Social Media
        'social_facebook',
        'social_twitter',
        'social_instagram',
        'social_linkedin',
        'social_youtube',
        'social_pinterest',
        'social_tiktok',

        // Footer Settings
        'footer_copyright_text',
        'footer_description',
        'footer_show_social_icons',
        'footer_show_payment_icons',

        // Payment Settings
        'payment_methods',
        'currency_symbol',
        'currency_code',
        'currency_position',

        // SEO Settings
        'seo_meta_title',
        'seo_meta_description',
        'seo_meta_keywords',
        'seo_author',
        'google_analytics_id',
        'google_tag_manager_id',
        'facebook_pixel_id',

        // Theme Settings
        'primary_color',
        'secondary_color',
        'success_color',
        'danger_color',
        'warning_color',
        'info_color',
        'dark_color',
        'light_color',

        // Store Hours
        'business_hours',
        'is_always_open',

        // Other Settings
        'is_maintenance_mode',
        'maintenance_message',
        'timezone',
        'date_format',
        'time_format',
    ];

    protected $casts = [
        'payment_methods' => 'array',
        'business_hours' => 'array',
        'footer_show_social_icons' => 'boolean',
        'footer_show_payment_icons' => 'boolean',
        'is_always_open' => 'boolean',
        'is_maintenance_mode' => 'boolean',
    ];

    /**
     * Get website settings with caching
     */
    // public static function getSettings()
    // {
    //     return Cache::remember('website_settings', 3600, function () {
    //         return self::first() ?? self::createDefault();
    //     });
    // }

    /**
     * Create default settings if none exist
     */
    public static function createDefault()
    {
        return self::create([
            'website_name' => 'My Ecommerce Store',
            'website_tagline' => 'Best Online Shopping Store',
            'currency_symbol' => '$',
            'currency_code' => 'USD',
            'primary_color' => '#0d6efd',
            'footer_copyright_text' => '© ' . date('Y') . ' All rights reserved.',
            'footer_show_social_icons' => true,
            'footer_show_payment_icons' => true,
            'payment_methods' => ['cod', 'card', 'paypal'],
            'business_hours' => [
                'monday' => '9:00 AM - 6:00 PM',
                'tuesday' => '9:00 AM - 6:00 PM',
                'wednesday' => '9:00 AM - 6:00 PM',
                'thursday' => '9:00 AM - 6:00 PM',
                'friday' => '9:00 AM - 6:00 PM',
                'saturday' => '10:00 AM - 4:00 PM',
                'sunday' => 'Closed',
            ],
            'timezone' => 'UTC',
            'date_format' => 'Y-m-d',
            'time_format' => 'H:i',
        ]);
    }

    /**
     * Clear settings cache
     */
    public static function clearCache()
    {
        Cache::forget('website_settings');
    }

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::saved(function () {
            self::clearCache();
        });
    }
}
