<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrivacyConsentWithdrawal extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'withdrawn_at',
        'notes',
        'notified',
    ];

    protected $casts = [
        'withdrawn_at' => 'datetime',
        'notified' => 'boolean',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }
}
