<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'title_tr',
        'title_de',
        'url',
        'route_name',
        'slug',
        'parent_id',
        'sort_order',
        'is_active',
        'has_dropdown',
        'open_new_tab',
        'category_id',
        'created_by'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'has_dropdown' => 'boolean',
        'open_new_tab' => 'boolean',
        'sort_order' => 'integer',
        'parent_id' => 'integer',
        'category_id' => 'integer',
    ];


    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id')->orderBy('sort_order');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function category()
    {
        return $this->belongsTo(PersonnelCategory::class, 'category_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    public function scopeParents($query)
    {
        return $query->whereNull('parent_id');
    }

    public function getDisplayTitleAttribute()
    {
        return $this->title;
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
}
