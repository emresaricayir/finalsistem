<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DonationCertificate extends Model
{
    protected $fillable = [
        'member_id',
        'date_from',
        'date_to',
        'total_amount',
        'created_by',
    ];

    /**
     * Member relation.
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Admin user who created the certificate.
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

