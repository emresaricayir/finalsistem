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
        Schema::create('elections', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Seçim başlığı
            $table->text('content_tr'); // Türkçe içerik
            $table->text('content_de'); // Almanca içerik
            $table->date('election_date'); // Seçim tarihi
            $table->boolean('is_active')->default(true); // Aktif mi?
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('elections');
    }
};
