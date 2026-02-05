<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class EmailLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'template_key',
        'template_name',
        'recipient_email',
        'recipient_name',
        'subject',
        'status',
        'error_message',
        'variables',
        'sent_by',
        'batch_id',
        'sent_at',
    ];

    protected $casts = [
        'variables' => 'array',
        'sent_at' => 'datetime',
    ];

    /**
     * Scope for filtering by template
     */
    public function scopeByTemplate($query, $templateKey)
    {
        return $query->where('template_key', $templateKey);
    }

    /**
     * Scope for filtering by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for filtering by batch
     */
    public function scopeByBatch($query, $batchId)
    {
        return $query->where('batch_id', $batchId);
    }

    /**
     * Scope for filtering by date range
     */
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClassAttribute()
    {
        return match($this->status) {
            'sent' => 'bg-green-100 text-green-800',
            'failed' => 'bg-red-100 text-red-800',
            'pending' => 'bg-yellow-100 text-yellow-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Get status text in Turkish
     */
    public function getStatusTextAttribute()
    {
        return match($this->status) {
            'sent' => 'Gönderildi',
            'failed' => 'Başarısız',
            'pending' => 'Bekliyor',
            default => 'Bilinmiyor',
        };
    }
}
