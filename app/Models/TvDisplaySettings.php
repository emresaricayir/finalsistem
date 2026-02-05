<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TvDisplaySettings extends Model
{
    protected $fillable = [
        'page_transition_speed',
        'show_dues',
        'auto_refresh_enabled',
        'auto_refresh_interval',
        'member_display_limit',
        'default_year'
    ];

    protected $casts = [
        'show_dues' => 'boolean',
        'auto_refresh_enabled' => 'boolean',
        'page_transition_speed' => 'integer',
        'auto_refresh_interval' => 'integer',
        'member_display_limit' => 'integer',
        'default_year' => 'integer'
    ];

    // Varsayılan ayarları getir
    public static function getDefaultSettings()
    {
        return [
            'page_transition_speed' => 5, // saniye
            'show_dues' => true,
            'auto_refresh_enabled' => true,
            'auto_refresh_interval' => 30, // saniye
            'member_display_limit' => 16,
            'default_year' => 2026
        ];
    }

    // Mevcut ayarları getir veya varsayılanları oluştur
    public static function getCurrentSettings()
    {
        $settings = self::first();

        if (!$settings) {
            $settings = self::create(self::getDefaultSettings());
        } else {
            // Eğer default_year 2025 veya daha küçükse, 2026'ya güncelle
            if ($settings->default_year < 2026) {
                $settings->default_year = 2026;
                $settings->save();
            }
        }

        return $settings;
    }
}
