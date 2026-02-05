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
        Schema::create('tv_display_messages', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Bilgilendirme adı');
            $table->string('title')->comment('Ana başlık');
            $table->string('subtitle')->comment('Alt başlık');
            $table->text('message')->nullable()->comment('Ana mesaj');
            $table->string('verse')->nullable()->comment('Ayet kaynağı');
            $table->string('verse_source')->nullable()->comment('Kaynak');
            $table->string('footer_message')->comment('Alt mesaj');
            $table->string('footer_submessage')->comment('Alt alt mesaj');
            $table->json('display_pages')->nullable()->comment('Hangi sayfalarda gösterileceği');
            $table->boolean('is_active')->default(true)->comment('Aktif mi');
            $table->integer('sort_order')->default(0)->comment('Sıralama');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tv_display_messages');
    }
};
