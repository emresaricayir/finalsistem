<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccessLog extends Model
{
    protected $fillable = [
        'member_id', // Nullable: Üye tamamen silinse bile log kaydı tutulur (DSGVO)
        'user_id',
        'action',
        'ip_address',
        'user_agent',
        'details', // Üye silinmeden önce snapshot bilgileri buraya kaydedilir
    ];

    protected $casts = [
        'details' => 'array',
    ];

    /**
     * Get the member that was accessed
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class)->withTrashed();
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
            'force_delete' => 'Kalıcı Silme',
            'restore' => 'Geri Getirme',
            'payment_create' => 'Ödeme Alındı',
            'payment_delete' => 'Ödeme Silindi',
            'due_create' => 'Aidat Oluşturuldu',
            'due_delete' => 'Aidat Silindi',
            'deletion_request' => 'Silme Talebi Gönderildi',
            'consent_withdrawal' => 'Rıza Geri Çekildi',
            'consent_given' => 'Rıza Verildi',
            'member_no_changed' => 'Üye Numarası Değiştirildi',
            default => ucfirst($this->action),
        };
    }
}
