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
        Schema::table('announcements', function (Blueprint $table) {
            $table->string('obituary_name')->nullable()->after('content');
            $table->date('obituary_date')->nullable()->after('obituary_name');
            $table->time('funeral_time')->nullable()->after('obituary_date');
            $table->string('funeral_place')->nullable()->after('funeral_time');
            $table->string('burial_place')->nullable()->after('funeral_place');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            $table->dropColumn([
                'obituary_name',
                'obituary_date',
                'funeral_time',
                'funeral_place',
                'burial_place'
            ]);
        });
    }
};
