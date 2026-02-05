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
        Schema::table('board_members', function (Blueprint $table) {
            $table->string('title_tr')->nullable()->after('title');
            $table->string('title_de')->nullable()->after('title_tr');
            $table->text('bio_tr')->nullable()->after('bio');
            $table->text('bio_de')->nullable()->after('bio_tr');
        });

        // Mevcut verileri title_tr ve bio_tr'ye kopyala
        DB::statement('UPDATE board_members SET title_tr = title');
        DB::statement('UPDATE board_members SET bio_tr = bio');

        Schema::table('board_members', function (Blueprint $table) {
            $table->dropColumn(['title', 'bio']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('board_members', function (Blueprint $table) {
            $table->string('title')->nullable()->after('title_de');
            $table->text('bio')->nullable()->after('bio_de');
        });

        // Verileri geri kopyala
        DB::statement('UPDATE board_members SET title = title_tr');
        DB::statement('UPDATE board_members SET bio = bio_tr');

        Schema::table('board_members', function (Blueprint $table) {
            $table->dropColumn(['title_tr', 'title_de', 'bio_tr', 'bio_de']);
        });
    }
};
