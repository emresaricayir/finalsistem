<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tv_display_messages', function (Blueprint $table) {
            // Gereksiz alanları kaldır
            $table->dropColumn(['subtitle', 'message', 'verse', 'verse_source', 'footer_message', 'footer_submessage']);

            // Yeni basit alanlar ekle
            $table->string('title')->change(); // Ana başlık
            $table->text('content')->nullable()->after('title'); // Ana içerik
            $table->string('image')->nullable()->after('content'); // Resim yolu
            $table->string('footer_text')->default('YÖNETİM KURULU')->after('image'); // Alt yazı
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tv_display_messages', function (Blueprint $table) {
            // Geri al
            $table->string('subtitle')->after('title');
            $table->text('message')->nullable()->after('subtitle');
            $table->string('verse')->nullable()->after('message');
            $table->string('verse_source')->nullable()->after('verse');
            $table->string('footer_message')->after('verse_source');
            $table->string('footer_submessage')->after('footer_message');

            $table->dropColumn(['content', 'image', 'footer_text']);
        });
    }
};
