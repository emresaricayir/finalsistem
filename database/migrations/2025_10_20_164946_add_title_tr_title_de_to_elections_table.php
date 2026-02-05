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
        // Check if columns already exist
        if (!Schema::hasColumn('elections', 'title_tr')) {
            Schema::table('elections', function (Blueprint $table) {
                $table->string('title_tr')->nullable()->after('title');
                $table->string('title_de')->nullable()->after('title_tr');
            });

            // Migrate existing data: copy title to title_tr
            DB::statement('UPDATE elections SET title_tr = title WHERE title_tr IS NULL');
        }

        // Make columns required and drop old title column
        Schema::table('elections', function (Blueprint $table) {
            if (Schema::hasColumn('elections', 'title_tr')) {
                // First, ensure all records have values for the new columns
                DB::statement('UPDATE elections SET title_tr = COALESCE(title_tr, title, "Default Title") WHERE title_tr IS NULL');
                DB::statement('UPDATE elections SET title_de = COALESCE(title_de, title, "Default Title") WHERE title_de IS NULL');

                // Then make them required
                $table->string('title_tr')->nullable(false)->change();
                $table->string('title_de')->nullable(false)->change();
            }
            if (Schema::hasColumn('elections', 'title')) {
                $table->dropColumn('title');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('elections', function (Blueprint $table) {
            $table->dropColumn(['title_tr', 'title_de']);
        });
    }
};
