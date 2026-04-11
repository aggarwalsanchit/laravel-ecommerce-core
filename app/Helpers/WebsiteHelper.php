<?php

namespace App\Helpers;

use App\Models\WebsiteSetting;

class WebsiteHelper
{
    public static function getSetting($key, $default = null)
    {
        // $settings = WebsiteSetting::getSettings();
        return $settings->$key ?? $default;
    }

    public static function getLogo($mode = 'light')
    {
        $logo = $mode === 'light' ? 'logo_light' : 'logo_dark';
        $logoPath = self::getSetting($logo);

        if ($logoPath && file_exists(storage_path('app/public/' . $logoPath))) {
            return asset('storage/' . $logoPath);
        }

        return asset('dummy-admin-logo.webp');
    }

    public static function getFavicon()
    {
        $favicon = self::getSetting('logo_favicon');

        if ($favicon && file_exists(storage_path('app/public/' . $favicon))) {
            return asset('storage/' . $favicon);
        }

        return asset('favicon.ico');
    }

    public static function getCurrencySymbol()
    {
        return self::getSetting('currency_symbol', '$');
    }

    public static function formatPrice($price)
    {
        $symbol = self::getCurrencySymbol();
        $position = self::getSetting('currency_position', 0);

        if ($position == 0) {
            return $symbol . number_format($price, 2);
        }

        return number_format($price, 2) . $symbol;
    }
}
