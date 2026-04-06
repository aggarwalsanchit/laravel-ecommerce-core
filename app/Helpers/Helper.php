// app/Helpers/Helper.php
<?php

if (!function_exists('getAvatar')) {
    function getAvatar($avatar)
    {
        if ($avatar && Storage::disk('public')->exists($avatar)) {
            return Storage::url($avatar);
        }
        return asset('dummy-avatar.jpg');
    }
}