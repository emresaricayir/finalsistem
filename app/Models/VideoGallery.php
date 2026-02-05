<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoGallery extends Model
{
    use HasFactory;

    protected $table = 'video_gallery';

    protected $fillable = [
        'title_tr',
        'title_de',
        'description_tr',
        'description_de',
        'youtube_url',
        'youtube_id',
        'thumbnail_url',
        'video_category_id',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Extract YouTube video ID from URL
     */
    public static function extractYouTubeId($url)
    {
        $pattern = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/';
        preg_match($pattern, $url, $matches);
        return isset($matches[1]) ? $matches[1] : null;
    }

    /**
     * Get YouTube thumbnail URL
     */
    public function getThumbnailUrlAttribute($value)
    {
        if ($value) {
            return $value;
        }

        if ($this->youtube_id) {
            // Try different thumbnail qualities
            return "https://img.youtube.com/vi/{$this->youtube_id}/hqdefault.jpg";
        }

        return null;
    }

    /**
     * Get multiple thumbnail URLs for fallback
     */
    public function getThumbnailUrlsAttribute()
    {
        if (!$this->youtube_id) {
            return [];
        }

        return [
            'maxres' => "https://img.youtube.com/vi/{$this->youtube_id}/maxresdefault.jpg",
            'hq' => "https://img.youtube.com/vi/{$this->youtube_id}/hqdefault.jpg",
            'mq' => "https://img.youtube.com/vi/{$this->youtube_id}/mqdefault.jpg",
            'default' => "https://img.youtube.com/vi/{$this->youtube_id}/default.jpg"
        ];
    }

    /**
     * Get YouTube embed URL
     */
    public function getEmbedUrlAttribute()
    {
        if ($this->youtube_id) {
            return "https://www.youtube.com/embed/{$this->youtube_id}";
        }

        return null;
    }

    /**
     * Scope for active videos
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordered videos
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('created_at', 'desc');
    }

    /**
     * Get the category that owns the video
     */
    public function category()
    {
        return $this->belongsTo(VideoCategory::class);
    }

    /**
     * Get title based on current locale
     */
    public function getTitleAttribute()
    {
        $locale = app()->getLocale();
        return $locale === 'de' ? ($this->title_de ?? $this->title_tr) : ($this->title_tr ?? $this->title_de);
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
     * Get raw title for admin panel
     */
    public function getRawTitleAttribute()
    {
        return $this->title_tr;
    }

    /**
     * Get raw description for admin panel
     */
    public function getRawDescriptionAttribute()
    {
        return $this->description_tr;
    }
}
