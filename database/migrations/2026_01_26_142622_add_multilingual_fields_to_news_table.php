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
        // Önce yeni çok dilli alanları ekle
        Schema::table('news', function (Blueprint $table) {
            $table->string('title_tr')->nullable()->after('title');
            $table->string('title_de')->nullable()->after('title_tr');
            $table->text('content_tr')->nullable()->after('content');
            $table->text('content_de')->nullable()->after('content_tr');
        });

        // Mevcut verileri yeni alanlara kopyala
        DB::statement('UPDATE news SET title_tr = title WHERE title_tr IS NULL');
        DB::statement('UPDATE news SET content_tr = content WHERE content_tr IS NULL');
        
        // Almanca alanları için varsayılan değer (opsiyonel - boş bırakılabilir)
        // DB::statement('UPDATE news SET title_de = title WHERE title_de IS NULL');
        // DB::statement('UPDATE news SET content_de = content WHERE content_de IS NULL');

        // title_tr'yi zorunlu yap, title_de ve content alanlarını nullable bırak
        Schema::table('news', function (Blueprint $table) {
            // title_tr zorunlu (Türkçe her zaman olmalı)
            $table->string('title_tr')->nullable(false)->change();
            // title_de ve content_de nullable kalabilir (admin panelinden girilecek)
            
            // Eski alanları sil
            if (Schema::hasColumn('news', 'title')) {
                $table->dropColumn('title');
            }
            if (Schema::hasColumn('news', 'content')) {
                $table->dropColumn('content');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            // Eski alanları geri ekle
            $table->string('title')->after('id');
            $table->text('content')->nullable()->after('title');
        });

        // Verileri geri kopyala
        DB::statement('UPDATE news SET title = title_tr WHERE title IS NULL');
        DB::statement('UPDATE news SET content = content_tr WHERE content IS NULL');

        // Yeni alanları sil
        Schema::table('news', function (Blueprint $table) {
            $table->dropColumn(['title_tr', 'title_de', 'content_tr', 'content_de']);
        });
    }
};
