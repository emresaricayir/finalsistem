<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class GalleryCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name_tr',
        'name_de',
        'slug',
        'description_tr',
        'description_de',
        'cover_image',
        'sort_order',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer'
    ];

    public function images()
    {
        return $this->hasMany(GalleryImage::class);
    }

    public function activeImages()
    {
        return $this->hasMany(GalleryImage::class)->where('is_active', true)->orderBy('sort_order');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc');
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name_tr ?? $category->name_de ?? 'category');
            }
        });
        
        static::updating(function ($category) {
            if (($category->isDirty('name_tr') || $category->isDirty('name_de')) && empty($category->slug)) {
                $category->slug = Str::slug($category->name_tr ?? $category->name_de ?? 'category');
            }
        });
    }

    public function getCoverImageUrlAttribute()
    {
        if ($this->cover_image) {
            return asset('storage/' . $this->cover_image);
        }
        return null;
    }

    /**
     * Get name based on current locale
     */
    public function getNameAttribute()
    {
        $locale = app()->getLocale();
        return $locale === 'de' ? ($this->name_de ?? $this->name_tr) : ($this->name_tr ?? $this->name_de);
    }

    /**
     * Get description based on current locale
     */
    public function getDescriptionAttribute()
    {
        $locale = app()->getLocale();
        return $locale === 'de' ? ($this->description_de ?? $this->description_tr) : ($this->description_tr ?? $this->description_de);
    }

    /**
     * Get raw name for admin panel
     */
    public function getRawNameAttribute()
    {
        return $this->name_tr;
    }

    /**
     * Get raw description for admin panel
     */
    public function getRawDescriptionAttribute()
    {
        return $this->description_tr;
    }
}
