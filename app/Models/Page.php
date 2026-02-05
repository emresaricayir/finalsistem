<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title_tr',
        'title_de',
        'slug',
        'content_tr',
        'content_de',
        'meta_description_tr',
        'meta_description_de',
        'meta_keywords_tr',
        'meta_keywords_de',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer'
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc');
    }

    /**
     * Locale'e göre başlık döndür
     * Eğer seçilen dilde içerik yoksa, Türkçe'yi göster (fallback)
     */
    public function getTitleAttribute()
    {
        $locale = app()->getLocale(); // 'tr' veya 'de'
        $field = "title_{$locale}";
        
        // Eğer seçilen dilde içerik yoksa, Türkçe'yi göster
        if (empty($this->attributes[$field] ?? null)) {
            return $this->attributes['title_tr'] ?? '';
        }
        
        return $this->attributes[$field];
    }

    /**
     * Locale'e göre içerik döndür
     * Eğer seçilen dilde içerik yoksa, Türkçe'yi göster (fallback)
     */
    public function getContentAttribute()
    {
        $locale = app()->getLocale(); // 'tr' veya 'de'
        $field = "content_{$locale}";
        
        // Eğer seçilen dilde içerik yoksa, Türkçe'yi göster
        if (empty($this->attributes[$field] ?? null)) {
            return $this->attributes['content_tr'] ?? '';
        }
        
        return $this->attributes[$field];
    }

    /**
     * Locale'e göre meta description döndür
     */
    public function getMetaDescriptionAttribute()
    {
        $locale = app()->getLocale();
        $field = "meta_description_{$locale}";
        
        if (empty($this->attributes[$field] ?? null)) {
            return $this->attributes['meta_description_tr'] ?? null;
        }
        
        return $this->attributes[$field];
    }

    /**
     * Locale'e göre meta keywords döndür
     */
    public function getMetaKeywordsAttribute()
    {
        $locale = app()->getLocale();
        $field = "meta_keywords_{$locale}";
        
        if (empty($this->attributes[$field] ?? null)) {
            return $this->attributes['meta_keywords_tr'] ?? null;
        }
        
        return $this->attributes[$field];
    }
}

