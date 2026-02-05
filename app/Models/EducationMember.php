<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class EducationMember extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'surname',
        'student_name',
        'student_surname',
        'email',
        'phone',
        'status',
        'membership_date',
        'monthly_dues',
        'notes',
    ];

    protected $casts = [
        'membership_date' => 'date',
        'monthly_dues' => 'decimal:2',
    ];

    public function dues(): HasMany
    {
        return $this->hasMany(EducationDue::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(EducationPayment::class);
    }

    public function getFullNameAttribute(): string
    {
        return $this->name . ' ' . $this->surname;
    }

    public function getStudentFullNameAttribute(): string
    {
        return $this->student_name . ' ' . $this->student_surname;
    }

    public function getPaymentStatusAttribute(): string
    {
        $latestDue = $this->dues()->latest('due_date')->first();

        if (!$latestDue) {
            return 'no_dues';
        }

        return $latestDue->status;
    }

    public function getLatestDueAttribute()
    {
        return $this->dues()->latest('due_date')->first();
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    public function scopeSuspended($query)
    {
        return $query->where('status', 'suspended');
    }
}
