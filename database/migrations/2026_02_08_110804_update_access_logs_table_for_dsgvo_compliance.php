<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * DSGVO uyumluluğu için: Üye tamamen silinse bile veri erişim logları tutulmalı
     */
    public function up(): void
    {
        Schema::table('access_logs', function (Blueprint $table) {
            // Önce mevcut foreign key constraint'i kaldır
            $table->dropForeign(['member_id']);
            
            // member_id'yi nullable yap
            $table->unsignedBigInteger('member_id')->nullable()->change();
            
            // Yeni foreign key constraint ekle (onDelete('set null') ile)
            $table->foreign('member_id')
                  ->references('id')
                  ->on('members')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('access_logs', function (Blueprint $table) {
            // Foreign key constraint'i kaldır
            $table->dropForeign(['member_id']);
            
            // member_id'yi tekrar nullable olmayan yap
            // NOT: Eğer null değerler varsa bu hata verebilir
            $table->unsignedBigInteger('member_id')->nullable(false)->change();
            
            // Eski foreign key constraint'i geri ekle (cascade ile)
            $table->foreign('member_id')
                  ->references('id')
                  ->on('members')
                  ->onDelete('cascade');
        });
    }
};
