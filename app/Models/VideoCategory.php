<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class VideoCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_tr',
        'name_de',
        'slug',
        'description_tr',
        'description_de',
        'cover_image',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Boot method to auto-generate slug
     */
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

    /**
     * Get the videos for the category
     */
    public function videos()
    {
        return $this->hasMany(VideoGallery::class);
    }

    /**
     * Get active videos for the category
     */
    public function activeVideos()
    {
        return $this->hasMany(VideoGallery::class)->where('is_active', true);
    }

    /**
     * Get videos count
     */
    public function getActiveVideosCountAttribute()
    {
        return $this->activeVideos()->count();
    }

    /**
     * Get all videos count (including inactive)
     */
    public function getVideosCountAttribute()
    {
        return $this->videos()->count();
    }

    /**
     * Scope for active categories
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordered categories
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name_tr');
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

    /**
     * Get the route key for the model
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
