<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Eski üyeler için privacy_consent'i true yap
        // Çünkü eskiden zaten onay vermiş sayılıyorlar
        \DB::table('members')
            ->where(function($query) {
                $query->where('privacy_consent', false)
                      ->orWhereNull('privacy_consent');
            })
            ->update([
                'privacy_consent' => true,
                'privacy_consent_date' => \DB::raw('COALESCE(privacy_consent_date, created_at)'), // Eğer tarih yoksa kayıt tarihini kullan
                'updated_at' => now(),
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Geri almak için eski üyeleri false yap (sadece privacy_consent_date null olanlar)
        // Not: Bu işlem geri alınamaz çünkü hangi üyelerin eski olduğunu tam olarak bilemeyiz
        // Bu yüzden down() metodunu boş bırakıyoruz veya sadece privacy_consent_date null olanları false yapıyoruz
        \DB::table('members')
            ->whereNull('privacy_consent_date')
            ->where('privacy_consent', true)
            ->update([
                'privacy_consent' => false,
                'privacy_consent_date' => null,
                'updated_at' => now(),
            ]);
    }
};
