<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Önce enum'u güncelle - yeni lastschrift türlerini ekle (eski 'lastschrift' de geçici olarak kalsın)
        DB::statement("ALTER TABLE payments MODIFY COLUMN payment_method ENUM(
            'cash',
            'bank_transfer',
            'credit_card',
            'other',
            'lastschrift',
            'lastschrift_monthly',
            'lastschrift_semi_annual',
            'lastschrift_annual'
        ) DEFAULT 'cash'");

        // Şimdi mevcut 'lastschrift' değerlerini 'lastschrift_monthly' yap
        DB::table('payments')
            ->where('payment_method', 'lastschrift')
            ->update(['payment_method' => 'lastschrift_monthly']);

        // Son olarak eski 'lastschrift'i enum'dan kaldır
        DB::statement("ALTER TABLE payments MODIFY COLUMN payment_method ENUM(
            'cash',
            'bank_transfer',
            'credit_card',
            'other',
            'lastschrift_monthly',
            'lastschrift_semi_annual',
            'lastschrift_annual'
        ) DEFAULT 'cash'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Yeni lastschrift türlerini eski 'lastschrift'e çevir
        DB::table('payments')
            ->whereIn('payment_method', ['lastschrift_monthly', 'lastschrift_semi_annual', 'lastschrift_annual'])
            ->update(['payment_method' => 'lastschrift']);

        // Enum'u eski haline döndür
        DB::statement("ALTER TABLE payments MODIFY COLUMN payment_method ENUM(
            'cash',
            'bank_transfer',
            'credit_card',
            'other',
            'lastschrift'
        ) DEFAULT 'cash'");
    }
};
