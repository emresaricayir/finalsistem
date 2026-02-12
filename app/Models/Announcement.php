<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Announcement extends BaseModel
{
    protected $fillable = [
        'title_tr',
        'title_de',
        'content_tr',
        'content_de',
        'image_path',
        'image_path_de',
        'type',
        'is_active',
        'is_featured',
        'start_date',
        'end_date',
        'sort_order',
        'created_by',
        'obituary_name',
        'obituary_date',
        'funeral_time',
        'funeral_place',
        'burial_place',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
        'sort_order' => 'integer',
        'obituary_date' => 'date',
        'funeral_time' => 'datetime:H:i',
    ];

    public function getImageUrlAttribute(): ?string
    {
        $locale = app()->getLocale(); // 'tr' veya 'de'
        
        // Almanca için özel görsel varsa onu kullan
        if ($locale === 'de' && !empty($this->attributes['image_path_de'] ?? null)) {
            return asset('storage/' . $this->attributes['image_path_de']);
        }
        
        // Fallback: Türkçe görsel veya null
        $imagePath = $this->attributes['image_path'] ?? null;
        return $imagePath ? asset('storage/' . $imagePath) : null;
    }

    /**
     * Locale'e göre kapak görseli döndür
     * Eğer seçilen dilde görsel yoksa, Türkçe görseli göster (fallback)
     */
    public function getImagePathAttribute($value)
    {
        $locale = app()->getLocale(); // 'tr' veya 'de'
        
        // Almanca için özel görsel varsa onu kullan
        if ($locale === 'de' && !empty($this->attributes['image_path_de'] ?? null)) {
            return $this->attributes['image_path_de'];
        }
        
        // Fallback: Türkçe görsel veya null
        return $value ?? null;
    }

    /**
     * Get the user who created this announcement
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope for active announcements
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for current announcements (within date range)
     */
    public function scopeCurrent($query)
    {
        $now = Carbon::now()->toDateString();

        return $query->where(function ($q) use ($now) {
            $q->where(function ($q2) use ($now) {
                // Both dates are set and current date is within range
                $q2->whereNotNull('start_date')
                   ->whereNotNull('end_date')
                   ->where('start_date', '<=', $now)
                   ->where('end_date', '>=', $now);
            })->orWhere(function ($q2) use ($now) {
                // Only start date is set and current date is after start
                $q2->whereNotNull('start_date')
                   ->whereNull('end_date')
                   ->where('start_date', '<=', $now);
            })->orWhere(function ($q2) use ($now) {
                // Only end date is set and current date is before end
                $q2->whereNull('start_date')
                   ->whereNotNull('end_date')
                   ->where('end_date', '>=', $now);
            })->orWhere(function ($q2) {
                // No dates set, always show
                $q2->whereNull('start_date')
                   ->whereNull('end_date');
            });
        });
    }

    /**
     * Scope for featured announcements
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope for public display (active and current)
     */
    public function scopePublic($query)
    {
        return $query->active()->current()->orderBy('sort_order')->orderBy('created_at', 'desc');
    }

    /**
     * Get the type badge class for display
     */
    public function getTypeBadgeClassAttribute()
    {
        return match($this->type) {
            'general' => 'bg-blue-100 text-blue-800',
            'obituary' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Get the type display name
     */
    public function getTypeDisplayAttribute()
    {
        return match($this->type) {
            'general' => __('common.general'),
            'obituary' => __('common.obituary'),
            default => __('common.general'),
        };
    }

    /**
     * Check if announcement is currently active and within date range
     */
    public function isCurrentlyActive()
    {
        if (!$this->is_active) {
            return false;
        }

        $now = Carbon::now()->toDateString();

        // Check start date
        if ($this->start_date && $this->start_date > $now) {
            return false;
        }

        // Check end date
        if ($this->end_date && $this->end_date < $now) {
            return false;
        }

        return true;
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
}
