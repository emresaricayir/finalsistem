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
        Schema::table('tv_display_settings', function (Blueprint $table) {
            $table->json('display_pages')->nullable()->after('is_active')->comment('Hangi sayfalarda gösterileceği (örn: [5, 10, 15, 20])');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tv_display_settings', function (Blueprint $table) {
            $table->dropColumn('display_pages');
        });
    }
};
