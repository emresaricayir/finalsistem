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
        Schema::table('quick_accesses', function (Blueprint $table) {
            $table->string('title_tr')->nullable()->after('title');
            $table->string('title_de')->nullable()->after('title_tr');
            $table->text('description_tr')->nullable()->after('description');
            $table->text('description_de')->nullable()->after('description_tr');
        });

        // Mevcut verileri title_tr ve description_tr'ye kopyala
        DB::statement('UPDATE quick_accesses SET title_tr = title');
        DB::statement('UPDATE quick_accesses SET description_tr = description');

        Schema::table('quick_accesses', function (Blueprint $table) {
            $table->dropColumn(['title', 'description']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quick_accesses', function (Blueprint $table) {
            $table->string('title')->nullable()->after('title_de');
            $table->text('description')->nullable()->after('description_de');
        });

        // Verileri geri kopyala
        DB::statement('UPDATE quick_accesses SET title = title_tr');
        DB::statement('UPDATE quick_accesses SET description = description_tr');

        Schema::table('quick_accesses', function (Blueprint $table) {
            $table->dropColumn(['title_tr', 'title_de', 'description_tr', 'description_de']);
        });
    }
};
