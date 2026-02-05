<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'message',
        'type',
        'icon',
        'is_read',
        'read_at',
        'data'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'data' => 'array'
    ];

    // Okunmamış bildirimleri getir
    public static function getUnreadCount()
    {
        return self::where('is_read', false)->count();
    }

    // Okunmamış bildirimleri getir
    public static function getUnread()
    {
        return self::where('is_read', false)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    // Bildirimi okundu olarak işaretle
    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now()
        ]);
    }

    // Tüm bildirimleri okundu olarak işaretle
    public static function markAllAsRead()
    {
        self::where('is_read', false)->update([
            'is_read' => true,
            'read_at' => now()
        ]);
    }
}
