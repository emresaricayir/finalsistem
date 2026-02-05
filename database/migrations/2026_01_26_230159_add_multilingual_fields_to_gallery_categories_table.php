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
        Schema::table('gallery_categories', function (Blueprint $table) {
            // Rename existing columns to Turkish versions
            $table->renameColumn('name', 'name_tr');
            $table->renameColumn('description', 'description_tr');

            // Add German versions
            $table->string('name_de')->nullable()->after('name_tr');
            $table->text('description_de')->nullable()->after('description_tr');
        });

        // Copy existing Turkish data to German fields for initial setup
        DB::table('gallery_categories')->get()->each(function ($category) {
            DB::table('gallery_categories')
                ->where('id', $category->id)
                ->update([
                    'name_de' => $category->name_tr,
                    'description_de' => $category->description_tr,
                ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gallery_categories', function (Blueprint $table) {
            // Drop German columns first
            $table->dropColumn('name_de');
            $table->dropColumn('description_de');

            // Rename Turkish columns back to original
            $table->renameColumn('name_tr', 'name');
            $table->renameColumn('description_tr', 'description');
        });
    }
};
