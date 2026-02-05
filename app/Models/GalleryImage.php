<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GalleryImage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'gallery_category_id',
        'title_tr',
        'title_de',
        'description_tr',
        'description_de',
        'image_path',
        'alt_text',
        'sort_order',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'gallery_category_id' => 'integer'
    ];

    public function category()
    {
        return $this->belongsTo(GalleryCategory::class, 'gallery_category_id');
    }

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
