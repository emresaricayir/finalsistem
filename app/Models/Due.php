<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Due extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'member_id',
        'year',
        'month',
        'amount',
        'status',
        'due_date',
        'paid_date',
        'notes',
    ];

    protected $casts = [
        'due_date' => 'date',
        'paid_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function paymentDues(): BelongsToMany
    {
        return $this->belongsToMany(Payment::class, 'payment_due')
            ->withPivot('amount')
            ->withTimestamps();
    }

    public function getMonthNameAttribute(): string
    {
        $monthNames = [
            1 => 'Ocak',
            2 => 'Şubat',
            3 => 'Mart',
            4 => 'Nisan',
            5 => 'Mayıs',
            6 => 'Haziran',
            7 => 'Temmuz',
            8 => 'Ağustos',
            9 => 'Eylül',
            10 => 'Ekim',
            11 => 'Kasım',
            12 => 'Aralık'
        ];

        return $monthNames[$this->month] ?? 'Bilinmeyen';
    }

    public function getIsOverdueAttribute(): bool
    {
        return $this->status === 'overdue' || ($this->due_date < now() && $this->status !== 'paid');
    }

    public function getStatusTextAttribute(): string
    {
        return match($this->status) {
            'pending' => 'Bekliyor',
            'paid' => 'Ödendi',
            'overdue' => 'Gecikmiş',
            default => 'Bilinmiyor'
        };
    }

    // Scope for overdue dues
    public function scopeOverdue($query)
    {
        return $query->where(function($q) {
            $q->where('status', 'overdue')
              ->orWhere(function($subQuery) {
                  $subQuery->where('due_date', '<', now())
                           ->where('status', '!=', 'paid');
              });
        });
    }
}
