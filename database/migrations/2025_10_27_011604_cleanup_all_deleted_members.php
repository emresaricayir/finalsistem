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
        // Tüm silinmiş üye kayıtlarını kalıcı olarak sil
        $deletedCount = DB::table('members')
            ->whereNotNull('deleted_at')
            ->delete();

        echo "Tüm silinmiş üye kayıtları kalıcı olarak silindi: {$deletedCount} kayıt\n";

        // Şimdi en yüksek üye numarasını kontrol et
        $lastMember = DB::table('members')
            ->where('member_no', 'LIKE', 'Mitglied%')
            ->orderByRaw('CAST(SUBSTRING(member_no, 9) AS UNSIGNED) DESC')
            ->first();

        if ($lastMember) {
            echo "En yüksek üye numarası: {$lastMember->member_no}\n";
        } else {
            echo "Hiç üye numarası bulunamadı\n";
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Bu migration geri alınamaz
    }
};
