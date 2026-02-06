<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccessLog extends Model
{
    protected $fillable = [
        'member_id',
        'user_id',
        'action',
        'ip_address',
        'user_agent',
        'details',
    ];

    protected $casts = [
        'details' => 'array',
    ];

    /**
     * Get the member that was accessed
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Get the user who accessed the data
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    /**
     * Get action label in Turkish
     */
    public function getActionLabelAttribute(): string
    {
        return match($this->action) {
            'view' => 'Görüntüleme',
            'edit' => 'Düzenleme',
            'export' => 'Veri İndirme',
            'delete' => 'Silme',
            'restore' => 'Geri Getirme',
            'payment_create' => 'Ödeme Alındı',
            'payment_delete' => 'Ödeme Silindi',
            'due_create' => 'Aidat Oluşturuldu',
            'due_delete' => 'Aidat Silindi',
            default => ucfirst($this->action),
        };
    }
}
