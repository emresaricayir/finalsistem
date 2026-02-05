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
        Schema::table('quick_accesses', function (Blueprint $table) {
            $table->string('icon_color')->default('#14b8a6')->after('icon');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quick_accesses', function (Blueprint $table) {
            $table->dropColumn('icon_color');
        });
    }
};
