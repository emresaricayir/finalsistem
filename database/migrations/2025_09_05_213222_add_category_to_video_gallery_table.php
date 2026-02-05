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
        Schema::table('video_gallery', function (Blueprint $table) {
            $table->foreignId('video_category_id')->nullable()->constrained('video_categories')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('video_gallery', function (Blueprint $table) {
            $table->dropForeign(['video_category_id']);
            $table->dropColumn('video_category_id');
        });
    }
};
