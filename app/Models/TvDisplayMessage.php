<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TvDisplayMessage extends Model
{
    protected $fillable = [
        'name',
        'title',
        'content',
        'image',
        'footer_text',
        'display_pages',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'display_pages' => 'array'
    ];

    // Aktif mesajları getir
    public static function getActiveMessages()
    {
        return self::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('created_at')
            ->get();
    }

    // Belirli sayfa için mesajları getir
    public static function getMessagesForPage($page)
    {
        return self::where('is_active', true)
            ->whereJsonContains('display_pages', $page)
            ->orderBy('sort_order')
            ->orderBy('created_at')
            ->get();
    }
}
