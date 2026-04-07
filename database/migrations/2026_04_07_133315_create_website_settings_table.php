// database/migrations/xxxx_xx_xx_xxxxxx_create_website_settings_table.php

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('website_settings', function (Blueprint $table) {
            $table->id();

            // ========== BASIC INFORMATION ==========
            $table->string('website_name')->default('My Ecommerce Store');
            $table->string('website_tagline')->nullable();
            $table->string('website_description')->nullable();
            $table->string('website_keywords')->nullable();

            // ========== LOGOS & FAVICON ==========
            $table->string('logo_light')->nullable();      // Light mode logo
            $table->string('logo_dark')->nullable();       // Dark mode logo
            $table->string('logo_favicon')->nullable();    // Favicon icon
            $table->string('logo_small')->nullable();      // Small logo for mobile

            // ========== ALT TAG LOGOS ==========
            $table->string('logo_light_alt_tag')->nullable();      // Light mode logo
            $table->string('logo_dark_alt_tag')->nullable();       // Dark mode logo
            $table->string('logo_small_alt_tag')->nullable();      // Small logo for mobile

            // ========== CONTACT INFORMATION ==========
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('contact_whatsapp')->nullable();
            $table->string('contact_address')->nullable();
            $table->string('contact_city')->nullable();
            $table->string('contact_state')->nullable();
            $table->string('contact_country')->nullable();
            $table->string('contact_postal_code')->nullable();
            $table->string('contact_map_url')->nullable();   // Google Maps embed URL

            // ========== SOCIAL MEDIA LINKS ==========
            $table->string('social_facebook')->nullable();
            $table->string('social_twitter')->nullable();
            $table->string('social_instagram')->nullable();
            $table->string('social_linkedin')->nullable();
            $table->string('social_youtube')->nullable();
            $table->string('social_pinterest')->nullable();
            $table->string('social_tiktok')->nullable();

            // ========== FOOTER SETTINGS ==========
            $table->text('footer_copyright_text')->nullable();
            $table->text('footer_description')->nullable();
            $table->boolean('footer_show_social_icons')->default(true);
            $table->boolean('footer_show_payment_icons')->default(true);

            // ========== PAYMENT SETTINGS ==========
            $table->json('payment_methods')->nullable();     // ['cod', 'card', 'paypal', 'razorpay']
            $table->string('currency_symbol')->default('$');
            $table->string('currency_code')->default('USD');
            $table->integer('currency_position')->default(0); // 0=left, 1=right

            // ========== SEO SETTINGS ==========
            $table->string('seo_meta_title')->nullable();
            $table->text('seo_meta_description')->nullable();
            $table->text('seo_meta_keywords')->nullable();
            $table->string('seo_author')->nullable();
            $table->string('google_analytics_id')->nullable();
            $table->string('google_tag_manager_id')->nullable();
            $table->string('facebook_pixel_id')->nullable();

            // ========== THEME SETTINGS ==========
            $table->string('primary_color')->default('#0d6efd');
            $table->string('secondary_color')->default('#6c757d');
            $table->string('success_color')->default('#198754');
            $table->string('danger_color')->default('#dc3545');
            $table->string('warning_color')->default('#ffc107');
            $table->string('info_color')->default('#0dcaf0');
            $table->string('dark_color')->default('#212529');
            $table->string('light_color')->default('#f8f9fa');

            // ========== STORE HOURS ==========
            $table->json('business_hours')->nullable();      // JSON: {monday: '9am-5pm', tuesday: ...}
            $table->boolean('is_always_open')->default(false);

            // ========== OTHER SETTINGS ==========
            $table->boolean('is_maintenance_mode')->default(false);
            $table->text('maintenance_message')->nullable();
            $table->string('timezone')->default('UTC');
            $table->string('date_format')->default('Y-m-d');
            $table->string('time_format')->default('H:i');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('website_settings');
    }
};
