<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EducationPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'education_member_id',
        'amount',
        'payment_method',
        'payment_date',
        'notes',
        'recorded_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
    ];

    public function educationMember(): BelongsTo
    {
        return $this->belongsTo(EducationMember::class);
    }

    public function recordedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function dues(): HasMany
    {
        return $this->hasMany(EducationDue::class);
    }
}
