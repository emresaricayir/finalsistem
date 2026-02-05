<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, update existing records to new types
        DB::table('announcements')->where('type', 'info')->update(['type' => 'general']);
        DB::table('announcements')->where('type', 'success')->update(['type' => 'general']);
        DB::table('announcements')->where('type', 'warning')->update(['type' => 'general']);
        DB::table('announcements')->where('type', 'danger')->update(['type' => 'general']);

        // Now modify the column to only allow the new types
        Schema::table('announcements', function (Blueprint $table) {
            $table->enum('type', ['general', 'obituary'])->default('general')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            $table->enum('type', ['info', 'warning', 'success', 'danger'])->default('info')->change();
        });
    }
};
