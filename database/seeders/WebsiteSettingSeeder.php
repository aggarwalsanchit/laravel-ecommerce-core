<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WebsiteSetting;

class WebsiteSettingSeeder extends Seeder
{
    public function run()
    {
        WebsiteSetting::updateOrCreate(
            ['id' => 1],
            [
                // Basic Information
                'website_name' => 'Boron Ecommerce',
                'website_tagline' => 'Your Trusted Online Shopping Destination',
                'website_description' => 'Best online shopping store for quality products',
                'website_keywords' => 'ecommerce, shopping, online store, buy products',

                // Logos (store in storage)
                'logo_light' => 'logos/logo-light.png',
                'logo_dark' => 'logos/logo-dark.png',
                'logo_favicon' => 'logos/favicon.ico',
                'logo_small' => 'logos/logo-small.png',

                // Contact Information
                'contact_email' => 'support@boron.com',
                'contact_phone' => '+1 234 567 8900',
                'contact_whatsapp' => '+1 234 567 8900',
                'contact_address' => '123 Business Street, Downtown',
                'contact_city' => 'New York',
                'contact_state' => 'NY',
                'contact_country' => 'USA',
                'contact_postal_code' => '10001',
                'contact_map_url' => 'https://www.google.com/maps/embed?pb=...',

                // Social Media
                'social_facebook' => 'https://facebook.com/boron',
                'social_twitter' => 'https://twitter.com/boron',
                'social_instagram' => 'https://instagram.com/boron',
                'social_linkedin' => 'https://linkedin.com/company/boron',
                'social_youtube' => 'https://youtube.com/boron',
                'social_pinterest' => 'https://pinterest.com/boron',
                'social_tiktok' => 'https://tiktok.com/@boron',

                // Footer Settings
                'footer_copyright_text' => '© ' . date('Y') . ' Boron Ecommerce. All rights reserved.',
                'footer_description' => 'Your trusted online shopping destination for quality products.',
                'footer_show_social_icons' => true,
                'footer_show_payment_icons' => true,

                // Payment Settings
                'payment_methods' => ['cod', 'card', 'paypal', 'razorpay'],
                'currency_symbol' => '$',
                'currency_code' => 'USD',
                'currency_position' => 0,

                // SEO Settings
                'seo_meta_title' => 'Boron Ecommerce - Best Online Shopping Store',
                'seo_meta_description' => 'Shop quality products at best prices. Fast delivery and secure payments.',
                'seo_meta_keywords' => 'ecommerce, shopping, online store, buy products, best deals',
                'seo_author' => 'Boron Team',
                'google_analytics_id' => 'UA-XXXXXXXXX-X',
                'google_tag_manager_id' => 'GTM-XXXXXX',
                'facebook_pixel_id' => 'XXXXXXXXXXXXXXX',

                // Theme Colors
                'primary_color' => '#0d6efd',
                'secondary_color' => '#6c757d',
                'success_color' => '#198754',
                'danger_color' => '#dc3545',
                'warning_color' => '#ffc107',
                'info_color' => '#0dcaf0',
                'dark_color' => '#212529',
                'light_color' => '#f8f9fa',

                // Business Hours
                'business_hours' => [
                    'monday' => '9:00 AM - 6:00 PM',
                    'tuesday' => '9:00 AM - 6:00 PM',
                    'wednesday' => '9:00 AM - 6:00 PM',
                    'thursday' => '9:00 AM - 6:00 PM',
                    'friday' => '9:00 AM - 6:00 PM',
                    'saturday' => '10:00 AM - 4:00 PM',
                    'sunday' => 'Closed',
                ],
                'is_always_open' => false,

                // Other Settings
                'is_maintenance_mode' => false,
                'maintenance_message' => 'We are currently under maintenance. Please check back soon!',
                'timezone' => 'America/New_York',
                'date_format' => 'Y-m-d',
                'time_format' => 'h:i A',
            ]
        );

        $this->command->info('Website settings created successfully!');
    }
}
