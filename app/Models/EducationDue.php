<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EducationDue extends Model
{
    use HasFactory;

    protected $fillable = [
        'education_member_id',
        'amount',
        'due_date',
        'status',
        'paid_date',
        'notes',
        'payment_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'due_date' => 'date',
        'paid_date' => 'date',
    ];

    public function educationMember(): BelongsTo
    {
        return $this->belongsTo(EducationMember::class);
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(EducationPayment::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'overdue');
    }

    public function scopeCurrentYear($query)
    {
        return $query->whereYear('due_date', now()->year);
    }
}
