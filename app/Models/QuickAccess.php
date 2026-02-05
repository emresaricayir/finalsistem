<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuickAccess extends Model
{
    use HasFactory;

    protected $fillable = [
        'title_tr',
        'title_de',
        'description_tr',
        'description_de',
        'icon',
        'icon_color',
        'url',
        'is_active',
        'sort_order',
        'created_by'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
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
     * Locale'e göre açıklama döndür
     * Eğer seçilen dilde içerik yoksa, Türkçe'yi göster (fallback)
     */
    public function getDescriptionAttribute()
    {
        $locale = app()->getLocale(); // 'tr' veya 'de'
        $field = "description_{$locale}";
        
        // Eğer seçilen dilde içerik yoksa, Türkçe'yi göster
        if (empty($this->attributes[$field] ?? null)) {
            return $this->attributes['description_tr'] ?? '';
        }
        
        return $this->attributes[$field];
    }
}
