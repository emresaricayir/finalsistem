<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vefa extends Model
{
    protected $fillable = [
        'title',
        'description',
        'image_path',
        'image_alt',
        'is_active',
        'display_duration',
        'sort_order',
        'birth_date',
        'death_date',
        'hometown',
        'burial_place'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'birth_date' => 'date',
        'death_date' => 'date',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrderBySort($query)
    {
        return $query->orderBy('sort_order', 'asc')->orderBy('created_at', 'desc');
    }

    public function getImageUrlAttribute()
    {
        if (!$this->image_path) {
            return null;
        }

        // Dosyanın gerçekten var olup olmadığını kontrol et
        $fullPath = storage_path('app/public/' . $this->image_path);

        if (!file_exists($fullPath)) {
            // Dosya yoksa null döndür
            return null;
        }

        return url('storage/' . $this->image_path);
    }
}
