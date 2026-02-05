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
        // Eğer credit_card kayıtları varsa onları 'other'a çevir
        DB::table('payments')
            ->where('payment_method', 'credit_card')
            ->update(['payment_method' => 'other']);

        // Payments tablosundan credit_card'ı kaldır
        DB::statement("ALTER TABLE payments MODIFY COLUMN payment_method ENUM(
            'cash',
            'bank_transfer',
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
        // Geri dönüş: credit_card'ı tekrar ekle
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
};
