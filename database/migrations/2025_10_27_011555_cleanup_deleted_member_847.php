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
        // Silinmiş Mitglied847 kaydını kalıcı olarak sil
        $deletedCount = DB::table('members')
            ->where('member_no', 'Mitglied847')
            ->whereNotNull('deleted_at')
            ->delete();

        echo "Silinmiş Mitglied847 kayıtları kalıcı olarak silindi: {$deletedCount} kayıt\n";

        // Diğer silinmiş üye numaralarını da kontrol et
        $deletedMembers = DB::table('members')
            ->whereNotNull('deleted_at')
            ->where('member_no', 'LIKE', 'Mitglied%')
            ->get(['id', 'member_no', 'name', 'surname']);

        echo "Toplam silinmiş Mitglied kayıtları: " . $deletedMembers->count() . "\n";

        foreach ($deletedMembers as $member) {
            echo "Silinmiş: {$member->member_no} - {$member->name} {$member->surname}\n";
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
