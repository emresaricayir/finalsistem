<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'monthly_dues_change_date',
        'previous_monthly_dues',
        'name',
        'surname',
        'gender',
        'email',
        'phone',
        'password',
        'member_no',
        'birth_date',
        'birth_place',
        'nationality',
        'family_members_count',
        'funeral_fund_member',
        'community_register_member',
        'occupation',
        'address',
        'status',
        'application_status',
        'membership_date',
        'monthly_dues',
        'payment_method',
        'payment_frequency',
        'mandate_number',
        'account_holder',
        'bank_name',
        'iban',
        'bic',
        'payment_due_date',
        'application_date',
        'approved_at',
        'approved_by',
        'rejection_reason',
        'notes',
        'signature',
        'signature_date',
        'sepa_agreement',
        'deletion_reason',
        'deleted_by',
        'activation_token',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'monthly_dues_change_date' => 'datetime',
        'birth_date' => 'date',
        'membership_date' => 'date',
        'monthly_dues' => 'decimal:2',
        'funeral_fund_member' => 'boolean',
        'community_register_member' => 'boolean',
        'payment_due_date' => 'date',
        'application_date' => 'datetime',
        'approved_at' => 'datetime',
        'signature_date' => 'datetime',
        'sepa_agreement' => 'boolean',
    ];

    public function dues(): HasMany
    {
        return $this->hasMany(Due::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function getFullNameAttribute(): string
    {
        return $this->name . ' ' . $this->surname;
    }

    public function getTotalDuesAttribute(): float
    {
        return $this->dues()->where('status', '!=', 'paid')->sum('amount');
    }

    public function getPaidDuesAttribute(): float
    {
        return $this->dues()->where('status', 'paid')->sum('amount');
    }

    public function getStatusTextAttribute(): string
    {
        return match($this->status) {
            'active' => 'Aktif',
            'inactive' => 'Pasif',
            'suspended' => 'Askıya Alınmış',
            'pending' => 'Beklemede',
            default => ucfirst($this->status)
        };
    }

    public function getMostRecentUnpaidDueAttribute()
    {
        return $this->dues()
            ->where('status', 'overdue')
            ->orWhere(function($query) {
                $query->where('status', 'pending')
                      ->where('due_date', '<', now());
            })
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->first();
    }

    public function getMostRecentPaidDueAttribute()
    {
        return $this->dues()
            ->where('status', 'paid')
            ->orderBy('paid_date', 'desc')
            ->first();
    }

    public function getOverdueCountAttribute(): int
    {
        return $this->dues()
            ->where(function($query) {
                $query->where('status', 'overdue')
                      ->orWhere(function($subQuery) {
                          $subQuery->where('status', 'pending')
                                   ->where('due_date', '<', now());
                      });
            })
            ->count();
    }

    public function getPaymentMethodText()
    {
        switch ($this->payment_method) {
            case 'cash':
                return 'Nakit / Barzahlung';
            case 'bank_transfer':
                return 'Banka Transferi / Überweisung';
            case 'lastschrift_monthly':
                return 'Aylık / Monatlich';
            case 'lastschrift_semi_annual':
                return '6 Aylık / Halbjährlich';
            case 'lastschrift_annual':
                return 'Yıllık / Jährlich';
            default:
                return 'Belirtilmemiş';
        }
    }

    public function getPaymentFrequencyText()
    {
        switch ($this->payment_method) {
            case 'lastschrift_monthly':
                return 'Aylık / Monatlich';
            case 'lastschrift_semi_annual':
                return '6 Aylık / Halbjährlich';
            case 'lastschrift_annual':
                return 'Yıllık / Jährlich';
            default:
                return '';
        }
    }
}
