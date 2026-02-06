<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'member_id',
        'due_id',
        'amount',
        'payment_method',
        'receipt_no',
        'description',
        'payment_date',
        'recorded_by',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function due(): BelongsTo
    {
        return $this->belongsTo(Due::class);
    }

    public function recordedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function dues(): BelongsToMany
    {
        return $this->belongsToMany(Due::class, 'payment_due')
            ->withPivot('amount')
            ->withTimestamps();
    }

    protected static function booted(): void
    {
        static::deleting(function (Payment $payment) {
            // Sadece force delete durumunda pivot tablo ilişkilerini sil
            // Soft delete durumunda ilişkiler korunmalı ki restore edildiğinde geri gelsin
            if ($payment->isForceDeleting()) {
                $payment->dues()->detach();
            }
        });
    }

    public function logs(): HasMany
    {
        return $this->hasMany(PaymentLog::class);
    }

    public function getPaymentMethodTextAttribute(): string
    {
        return match($this->payment_method) {
            'cash' => 'Nakit',
            'bank_transfer' => 'Banka Transferi',
            'lastschrift' => 'Lastschrift (SEPA)', // Eski kayıtlar için
            'lastschrift_monthly' => 'Lastschrift (Aylık)',
            'lastschrift_semi_annual' => 'Lastschrift (6 Aylık)',
            'lastschrift_annual' => 'Lastschrift (Yıllık)',
            default => 'Bilinmiyor'
        };
    }

    /**
     * Check if a member has already paid for a specific month/year
     */
    public static function hasMemberPaidForMonth($memberId, $year, $month)
    {
        return static::withoutTrashed()
            ->where('member_id', $memberId)
            ->whereHas('dues', function($query) use ($year, $month) {
                $query->whereYear('due_date', $year)
                      ->whereMonth('due_date', $month);
            })
            ->exists();
    }

    /**
     * Check if a specific due is already paid
     */
    public static function isDueAlreadyPaid($dueId)
    {
        return static::withoutTrashed()
            ->whereHas('dues', function($query) use ($dueId) {
                $query->where('dues.id', $dueId);
            })
            ->exists();
    }

    /**
     * Get the due period for this payment
     */
    public function getDuePeriodAttribute(): string
    {
        $dueDate = null;

        // Yeni sistem: dues ilişkisi
        if ($this->dues->count() > 0) {
            $due = $this->dues->first();
            $dueDate = $due->due_date;
        }
        // Eski sistem: due ilişkisi
        elseif ($this->due) {
            $dueDate = $this->due->due_date;
        }

        if ($dueDate) {
            $turkishMonths = [
                1 => 'Ocak', 2 => 'Şubat', 3 => 'Mart', 4 => 'Nisan',
                5 => 'Mayıs', 6 => 'Haziran', 7 => 'Temmuz', 8 => 'Ağustos',
                9 => 'Eylül', 10 => 'Ekim', 11 => 'Kasım', 12 => 'Aralık'
            ];

            return $turkishMonths[$dueDate->month];
        }

        return 'Bilinmiyor';
    }
}
