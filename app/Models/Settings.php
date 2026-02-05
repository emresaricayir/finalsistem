<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'label',
        'description',
    ];

    /**
     * Ayar değerini al
     */
    public static function get($key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Ayar değerini kaydet
     */
    public static function set($key, $value)
    {
        $setting = static::where('key', $key)->first();

        if ($setting) {
            $setting->update(['value' => $value]);
        } else {
            static::create([
                'key' => $key,
                'value' => $value,
                'type' => 'text',
                'group' => 'general',
                'label' => ucfirst(str_replace('_', ' ', $key)),
            ]);
        }

        return $setting;
    }

    /**
     * Geçici e-posta domain'ini al
     */
    public static function getTemporaryEmailDomain()
    {
        return 'uye.com';
    }

    /**
     * E-posta adresinin geçici olup olmadığını kontrol et
     */
    public static function isTemporaryEmail($email)
    {
        return str_contains($email, '@uye.com');
    }

    /**
     * Logo URL'ini al
     */
    public static function getLogoUrl($default = null)
    {
        $logo = static::get('logo');
        if ($logo) {
            // Logo dosyasının var olup olmadığını kontrol et
            $logoPath = storage_path('app/public/' . $logo);
            if (file_exists($logoPath)) {
                return asset('storage/' . $logo);
            } else {
                // Dosya yoksa ayarı temizle
                static::set('logo', null);
                return $default;
            }
        }
        return $default;
    }

    /**
     * Logo var mı kontrol et
     */
    public static function hasLogo()
    {
        return !empty(static::get('logo'));
    }

    /**
     * Favicon URL'ini al
     */
    public static function getFaviconUrl($default = null)
    {
        $favicon = static::get('favicon');
        if ($favicon) {
            // Favicon dosyasının var olup olmadığını kontrol et
            $faviconPath = storage_path('app/public/' . $favicon);
            if (file_exists($faviconPath)) {
                // Cache busting için timestamp ekle
                $timestamp = filemtime($faviconPath);
                return asset('storage/' . $favicon) . '?v=' . $timestamp;
            } else {
                // Dosya yoksa ayarı temizle
                static::set('favicon', null);
                return $default;
            }
        }
        return $default;
    }

    /**
     * Favicon var mı kontrol et
     */
    public static function hasFavicon()
    {
        return !empty(static::get('favicon'));
    }

    /**
     * Minimum aylık aidat miktarını al
     */
    public static function getMinimumMonthlyDues()
    {
        return (int) static::get('minimum_monthly_dues', 5);
    }

    /**
     * Aidat seçeneklerini oluştur (minimum + 5'er artış)
     */
    public static function getDuesOptions($count = 4)
    {
        $minimum = static::getMinimumMonthlyDues();
        $options = [];

        for ($i = 0; $i < $count; $i++) {
            $options[] = $minimum + ($i * 5);
        }

        return $options;
    }

    /**
     * Adres bilgisinden şehir adını çıkar
     */
    public static function getCityFromAddress()
    {
        $address = static::get('organization_address', '');

        if (empty($address)) {
            return '';
        }

        // Posta kodu (5 haneli sayı) bul ve sonrasındaki kelimeyi al
        if (preg_match('/\b\d{5}\s+([A-Za-zäöüÄÖÜß\s]+)/', $address, $matches)) {
            return trim($matches[1]);
        }

        // Alternatif: Virgül ile ayrılmışsa son kısmı al
        if (strpos($address, ',') !== false) {
            $parts = explode(',', $address);
            $lastPart = trim(end($parts));
            return $lastPart;
        }

        return '';
    }
}
