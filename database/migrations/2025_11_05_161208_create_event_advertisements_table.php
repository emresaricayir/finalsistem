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
        Schema::create('event_advertisements', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('title');
            $table->text('content')->nullable();
            $table->string('image')->nullable();
            $table->string('footer_text')->default('YÖNETİM KURULU');
            $table->json('display_positions')->nullable(); // Hangi etkinlik pozisyonlarında gösterileceği
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_advertisements');
    }
};
