<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrayerTime extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'hijri_date',
        'day_name',
        'imsak',
        'gunes',
        'ogle',
        'ikindi',
        'aksam',
        'yatsi',
        'city',
        'region',
        'country',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    /**
     * Bugünün namaz vakitlerini getir
     */
    public function scopeToday($query, $city = null)
    {
        $today = \Carbon\Carbon::today()->format('Y-m-d');
        $query = $query->whereDate('date', $today);
        
        if ($city) {
            $query->where('city', $city);
        }
        
        return $query;
    }

    /**
     * Belirli bir tarihin namaz vakitlerini getir
     */
    public function scopeForDate($query, $date, $city = null)
    {
        $query = $query->whereDate('date', $date);
        
        if ($city) {
            $query->where('city', $city);
        }
        
        return $query;
    }

    /**
     * Şehre göre filtrele
     */
    public function scopeForCity($query, $city)
    {
        return $query->where('city', $city);
    }
}
