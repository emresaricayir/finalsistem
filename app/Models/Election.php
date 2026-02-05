<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Election extends Model
{
    protected $fillable = [
        'title_tr',
        'title_de',
        'content_tr',
        'content_de',
        'is_active',
        'signature_image',
        'president_signature',
        'secretary_signature'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    // Aktif seçimleri getir
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Oluşturulma tarihine göre sırala
    public function scopeOrderByDate($query, $direction = 'desc')
    {
        return $query->orderBy('created_at', $direction);
    }

    // İmza resmi var mı?
    public function hasSignature()
    {
        // Yeni sistem: başkan veya sekreter imzası var mı?
        if (!empty($this->president_signature) && file_exists(public_path('storage/elections/' . $this->president_signature))) {
            return true;
        }
        if (!empty($this->secretary_signature) && file_exists(public_path('storage/elections/' . $this->secretary_signature))) {
            return true;
        }
        // Eski sistem: genel imza var mı?
        return !empty($this->signature_image) && file_exists(public_path('storage/elections/' . $this->signature_image));
    }

    // İmza resmi URL'i (öncelik sırasına göre)
    public function getSignatureUrl()
    {
        // Önce başkan imzasını kontrol et
        if (!empty($this->president_signature) && file_exists(public_path('storage/elections/' . $this->president_signature))) {
            return asset('storage/elections/' . $this->president_signature);
        }
        // Sonra sekreter imzasını kontrol et
        if (!empty($this->secretary_signature) && file_exists(public_path('storage/elections/' . $this->secretary_signature))) {
            return asset('storage/elections/' . $this->secretary_signature);
        }
        // Son olarak eski sistem imzasını kontrol et
        if (!empty($this->signature_image) && file_exists(public_path('storage/elections/' . $this->signature_image))) {
            return asset('storage/elections/' . $this->signature_image);
        }
        return null;
    }
}
