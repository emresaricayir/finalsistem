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
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Türkçe başlık
            $table->string('title_de')->nullable(); // Almanca başlık
            $table->string('url')->nullable(); // Harici URL
            $table->string('route_name')->nullable(); // Laravel route adı
            $table->foreignId('parent_id')->nullable()->constrained('menus')->onDelete('cascade'); // Alt menü için
            $table->integer('sort_order')->default(0); // Sıralama
            $table->boolean('is_active')->default(true); // Aktif/Pasif
            $table->boolean('has_dropdown')->default(false); // Dropdown menü mü?
            $table->string('icon')->nullable(); // İkon (opsiyonel)
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
