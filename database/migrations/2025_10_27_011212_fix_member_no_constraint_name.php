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
        // Önce eski constraint'i silmeye çalış (hata alırsa devam et)
        try {
            DB::statement('ALTER TABLE members DROP INDEX members_tc_no_unique');
        } catch (\Exception $e) {
            // Constraint yoksa devam et
        }

        // Yeni constraint ekle (zaten varsa hata alabilir)
        try {
            Schema::table('members', function (Blueprint $table) {
                $table->unique('member_no', 'members_member_no_unique');
            });
        } catch (\Exception $e) {
            // Constraint zaten varsa devam et
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Yeni constraint'i sil
        try {
            DB::statement('ALTER TABLE members DROP INDEX members_member_no_unique');
        } catch (\Exception $e) {
            // Constraint yoksa devam et
        }

        // Eski constraint'i geri ekle
        try {
            Schema::table('members', function (Blueprint $table) {
                $table->unique('member_no', 'members_tc_no_unique');
            });
        } catch (\Exception $e) {
            // Constraint zaten varsa devam et
        }
    }
};
