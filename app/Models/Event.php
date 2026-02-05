<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Event extends Model
{
    protected $fillable = [
        'title_tr',
        'title_de',
        'description_tr',
        'description_de',
        'event_date',
        'event_type',
        'location',
        'image_path',
        'is_active',
        'display_duration'
    ];

    protected $casts = [
        'event_date' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function getEventTypeLabelAttribute()
    {
        $types = [
            'wedding' => 'Düğün',
            'meeting' => 'Dernek Toplantısı',
            'mevlid' => 'Mevlid',
            'other' => 'Diğer'
        ];

        return $types[$this->event_type] ?? 'Bilinmeyen';
    }

    public function getEventTypeIconAttribute()
    {
        $icons = [
            'wedding' => '💍',
            'meeting' => '🏛️',
            'mevlid' => '📖',
            'other' => '📅'
        ];

        return $icons[$this->event_type] ?? '📅';
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('event_date', '>=', now());
    }

    public function scopePast($query)
    {
        return $query->where('event_date', '<', now());
    }

    public function scopeOrderByEventDate($query)
    {
        return $query->orderBy('event_date', 'asc');
    }

    /**
     * Get title based on current locale
     */
    public function getTitleAttribute()
    {
        $locale = app()->getLocale();
        return $this->getAttribute("title_{$locale}") ?: $this->getAttribute('title_tr') ?: $this->getAttribute('title_de');
    }

    /**
     * Get description based on current locale
     */
    public function getDescriptionAttribute()
    {
        $locale = app()->getLocale();
        return $this->getAttribute("description_{$locale}") ?: $this->getAttribute('description_tr') ?: $this->getAttribute('description_de');
    }

    /**
     * Get raw title for admin panel
     */
    public function getRawTitleAttribute()
    {
        return $this->getRawOriginal('title_tr');
    }

    /**
     * Get raw description for admin panel
     */
    public function getRawDescriptionAttribute()
    {
        return $this->getRawOriginal('description_tr');
    }
}
