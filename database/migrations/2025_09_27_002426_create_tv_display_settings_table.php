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
            $table->string('title')->default('Teşekkürler');
            $table->string('subtitle')->default('İslami Bağışlarınız İçin');
            $table->text('message')->nullable();
            $table->string('verse')->nullable();
            $table->string('verse_source')->nullable();
            $table->string('footer_message')->default('Hayırseverliğiniz için teşekkür ederiz');
            $table->string('footer_submessage')->default('Allah razı olsun');
            $table->boolean('is_active')->default(true);
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
