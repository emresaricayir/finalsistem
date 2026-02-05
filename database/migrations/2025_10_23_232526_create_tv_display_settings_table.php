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
        Schema::create('tv_display_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('page_transition_speed')->default(5)->comment('Sayfa geçiş hızı (saniye)');
            $table->boolean('show_dues')->default(true)->comment('Aidatları göster');
            $table->boolean('auto_refresh_enabled')->default(true)->comment('Otomatik yenileme aktif');
            $table->integer('auto_refresh_interval')->default(30)->comment('Otomatik yenileme aralığı (saniye)');
            $table->integer('member_display_limit')->default(16)->comment('Sayfa başına üye sayısı');
                   $table->integer('default_year')->default(2025)->comment('Varsayılan yıl');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tv_display_settings');
    }
};
