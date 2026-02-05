<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BoardMember extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'title_tr',
        'title_de',
        'image_path',
        'bio_tr',
        'bio_de',
        'email',
        'phone',
        'website',
        'facebook',
        'twitter',
        'instagram',
        'linkedin',
        'sort_order',
        'is_active',
        'category_id'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer'
    ];


    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc');
    }

    public function getImageUrlAttribute()
    {
        if ($this->image_path) {
            return asset('storage/' . $this->image_path);
        }
        return null;
    }

    public function category()
    {
        return $this->belongsTo(PersonnelCategory::class, 'category_id');
    }

    /**
     * Locale'e göre görev (title) döndür
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
     * Locale'e göre özgeçmiş (bio) döndür
     * Eğer seçilen dilde içerik yoksa, Türkçe'yi göster (fallback)
     */
    public function getBioAttribute()
    {
        $locale = app()->getLocale(); // 'tr' veya 'de'
        $field = "bio_{$locale}";
        
        // Eğer seçilen dilde içerik yoksa, Türkçe'yi göster
        if (empty($this->attributes[$field] ?? null)) {
            return $this->attributes['bio_tr'] ?? '';
        }
        
        return $this->attributes[$field];
    }
}

