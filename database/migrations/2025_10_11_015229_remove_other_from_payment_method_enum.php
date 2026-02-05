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
        // Eğer 'other' kayıtları varsa onları 'cash'e çevir
        DB::table('payments')
            ->where('payment_method', 'other')
            ->update(['payment_method' => 'cash']);

        // Payments tablosundan 'other'ı kaldır
        DB::statement("ALTER TABLE payments MODIFY COLUMN payment_method ENUM(
            'cash',
            'bank_transfer',
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
        // Geri dönüş: 'other'ı tekrar ekle
        DB::statement("ALTER TABLE payments MODIFY COLUMN payment_method ENUM(
            'cash',
            'bank_transfer',
            'other',
            'lastschrift_monthly',
            'lastschrift_semi_annual',
            'lastschrift_annual'
        ) DEFAULT 'cash'");
    }
};
