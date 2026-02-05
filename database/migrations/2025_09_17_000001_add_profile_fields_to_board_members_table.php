<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('board_members', function (Blueprint $table) {
            $table->text('bio')->nullable()->after('image_path');
            $table->string('email')->nullable()->after('bio');
            $table->string('phone')->nullable()->after('email');
            $table->string('website')->nullable()->after('phone');
            $table->string('facebook')->nullable()->after('website');
            $table->string('twitter')->nullable()->after('facebook');
            $table->string('instagram')->nullable()->after('twitter');
            $table->string('linkedin')->nullable()->after('instagram');
        });
    }

    public function down(): void
    {
        Schema::table('board_members', function (Blueprint $table) {
            $table->dropColumn(['bio', 'email', 'phone', 'website', 'facebook', 'twitter', 'instagram', 'linkedin']);
        });
    }
};



