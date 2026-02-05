<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventAdvertisement extends Model
{
    protected $fillable = [
        'name',
        'title',
        'content',
        'image',
        'footer_text',
        'display_positions',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'display_positions' => 'array'
    ];

    // Aktif reklamları getir
    public static function getActiveAds()
    {
        return self::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('created_at')
            ->get();
    }

    // Belirli pozisyon için reklamları getir
    public static function getAdsForPosition($position)
    {
        return self::where('is_active', true)
            ->whereJsonContains('display_positions', $position)
            ->orderBy('sort_order')
            ->orderBy('created_at')
            ->get();
    }
}
