<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PersonnelCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_tr',
        'name_de',
        'description_tr',
        'description_de',
        'color',
        'sort_order',
        'is_active'
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

    public function personnel()
    {
        return $this->hasMany(BoardMember::class, 'category_id');
    }

    /**
     * Get the name attribute based on current locale
     */
    public function getNameAttribute()
    {
        $locale = app()->getLocale();
        return $locale === 'de' ? ($this->name_de ?? $this->name_tr) : $this->name_tr;
    }

    /**
     * Get the description attribute based on current locale
     */
    public function getDescriptionAttribute()
    {
        $locale = app()->getLocale();
        return $locale === 'de' ? ($this->description_de ?? $this->description_tr) : $this->description_tr;
    }

    /**
     * Get raw name for admin panel (always Turkish)
     */
    public function getRawNameAttribute()
    {
        return $this->getRawOriginal('name_tr');
    }

    /**
     * Get raw description for admin panel (always Turkish)
     */
    public function getRawDescriptionAttribute()
    {
        return $this->getRawOriginal('description_tr');
    }
}
