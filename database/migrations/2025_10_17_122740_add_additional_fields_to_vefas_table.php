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
        Schema::table('vefas', function (Blueprint $table) {
            $table->date('birth_date')->nullable()->after('description');
            $table->date('death_date')->nullable()->after('birth_date');
            $table->string('hometown')->nullable()->after('death_date');
            $table->string('burial_place')->nullable()->after('hometown');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vefas', function (Blueprint $table) {
            $table->dropColumn(['birth_date', 'death_date', 'hometown', 'burial_place']);
        });
    }
};
