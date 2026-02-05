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
        // Tüm constraint'leri kontrol et ve gerekirse düzelt
        try {
            // Eski constraint'i sil
            DB::statement('ALTER TABLE members DROP INDEX members_tc_no_unique');
        } catch (\Exception $e) {
            echo "Eski constraint zaten yok: " . $e->getMessage() . "\n";
        }

        try {
            // Yeni constraint'i ekle
            DB::statement('ALTER TABLE members ADD UNIQUE KEY members_member_no_unique (member_no)');
        } catch (\Exception $e) {
            echo "Yeni constraint zaten var: " . $e->getMessage() . "\n";
        }

        // Constraint'leri listele
        $constraints = DB::select("SHOW INDEX FROM members WHERE Key_name LIKE '%member_no%' OR Key_name LIKE '%tc_no%'");
        foreach ($constraints as $constraint) {
            echo "Constraint: " . $constraint->Key_name . " - Column: " . $constraint->Column_name . "\n";
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
