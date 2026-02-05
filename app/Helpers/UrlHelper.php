<?php

namespace App\Helpers;

class UrlHelper
{
    /**
     * Get public URL for assets, replacing localhost with 127.0.0.1 for Facebook compatibility
     */
    public static function getPublicUrl($path)
    {
        $url = asset($path);
        
        // Replace localhost with 127.0.0.1 for better Facebook compatibility
        $url = str_replace('localhost', '127.0.0.1', $url);
        
        return $url;
    }
    
    /**
     * Get current URL with proper domain
     */
    public static function getCurrentUrl()
    {
        $url = url()->current();
        
        // Replace localhost with 127.0.0.1 for better Facebook compatibility
        $url = str_replace('localhost', '127.0.0.1', $url);
        
        return $url;
    }
}


