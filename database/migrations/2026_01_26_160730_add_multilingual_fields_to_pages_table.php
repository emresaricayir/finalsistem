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
        Schema::table('pages', function (Blueprint $table) {
            $table->string('title_tr')->nullable()->after('title');
            $table->string('title_de')->nullable()->after('title_tr');
            $table->longText('content_tr')->nullable()->after('content');
            $table->longText('content_de')->nullable()->after('content_tr');
            $table->text('meta_description_tr')->nullable()->after('meta_description');
            $table->text('meta_description_de')->nullable()->after('meta_description_tr');
            $table->text('meta_keywords_tr')->nullable()->after('meta_keywords');
            $table->text('meta_keywords_de')->nullable()->after('meta_keywords_tr');
        });

        // Mevcut verileri yeni alanlara kopyala
        DB::statement('UPDATE pages SET title_tr = title WHERE title_tr IS NULL');
        DB::statement('UPDATE pages SET content_tr = content WHERE content_tr IS NULL');
        DB::statement('UPDATE pages SET meta_description_tr = meta_description WHERE meta_description_tr IS NULL AND meta_description IS NOT NULL');
        DB::statement('UPDATE pages SET meta_keywords_tr = meta_keywords WHERE meta_keywords_tr IS NULL AND meta_keywords IS NOT NULL');

        // title_tr'yi zorunlu yap, diğer alanları nullable bırak
        Schema::table('pages', function (Blueprint $table) {
            // title_tr zorunlu (Türkçe her zaman olmalı)
            $table->string('title_tr')->nullable(false)->change();
            // Diğer alanlar nullable kalabilir (admin panelinden girilecek)
            
            // Eski alanları sil
            if (Schema::hasColumn('pages', 'title')) {
                $table->dropColumn('title');
            }
            if (Schema::hasColumn('pages', 'content')) {
                $table->dropColumn('content');
            }
            if (Schema::hasColumn('pages', 'meta_description')) {
                $table->dropColumn('meta_description');
            }
            if (Schema::hasColumn('pages', 'meta_keywords')) {
                $table->dropColumn('meta_keywords');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            // Eski alanları geri ekle
            $table->string('title')->after('id');
            $table->longText('content')->after('slug');
            $table->text('meta_description')->nullable()->after('content');
            $table->text('meta_keywords')->nullable()->after('meta_description');
        });

        // Verileri geri kopyala
        DB::statement('UPDATE pages SET title = title_tr WHERE title IS NULL');
        DB::statement('UPDATE pages SET content = content_tr WHERE content IS NULL');
        DB::statement('UPDATE pages SET meta_description = meta_description_tr WHERE meta_description IS NULL AND meta_description_tr IS NOT NULL');
        DB::statement('UPDATE pages SET meta_keywords = meta_keywords_tr WHERE meta_keywords IS NULL AND meta_keywords_tr IS NOT NULL');

        // Yeni alanları sil
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn([
                'title_tr', 'title_de', 
                'content_tr', 'content_de',
                'meta_description_tr', 'meta_description_de',
                'meta_keywords_tr', 'meta_keywords_de'
            ]);
        });
    }
};
