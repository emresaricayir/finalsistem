<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prayer_times', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('hijri_date')->nullable();
            $table->string('day_name')->nullable();
            $table->time('imsak');
            $table->time('gunes');
            $table->time('ogle');
            $table->time('ikindi');
            $table->time('aksam');
            $table->time('yatsi');
            $table->string('city')->nullable();
            $table->string('region')->nullable();
            $table->string('country')->default('ALMANYA');
            $table->timestamps();
            
            $table->index('date');
            $table->index(['city', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prayer_times');
    }
};