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
        Schema::table('personnel_categories', function (Blueprint $table) {
            // Add new multilingual columns
            $table->string('name_tr')->nullable()->after('id');
            $table->string('name_de')->nullable()->after('name_tr');
            $table->text('description_tr')->nullable()->after('name_de');
            $table->text('description_de')->nullable()->after('description_tr');
        });

        // Copy existing data to Turkish columns
        DB::table('personnel_categories')->get()->each(function ($category) {
            DB::table('personnel_categories')
                ->where('id', $category->id)
                ->update([
                    'name_tr' => $category->name ?? '',
                    'description_tr' => $category->description ?? null,
                ]);
        });

        // Copy Turkish data to German columns for initial setup
        DB::table('personnel_categories')->get()->each(function ($category) {
            DB::table('personnel_categories')
                ->where('id', $category->id)
                ->update([
                    'name_de' => $category->name_tr,
                    'description_de' => $category->description_tr,
                ]);
        });

        // Make name_tr required after data migration
        Schema::table('personnel_categories', function (Blueprint $table) {
            $table->string('name_tr')->nullable(false)->change();
        });

        // Drop old columns
        Schema::table('personnel_categories', function (Blueprint $table) {
            $table->dropColumn(['name', 'description']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('personnel_categories', function (Blueprint $table) {
            // Add back original columns
            $table->string('name')->after('id');
            $table->text('description')->nullable()->after('name');
        });

        // Copy Turkish data back to original columns
        DB::table('personnel_categories')->get()->each(function ($category) {
            DB::table('personnel_categories')
                ->where('id', $category->id)
                ->update([
                    'name' => $category->name_tr ?? '',
                    'description' => $category->description_tr ?? null,
                ]);
        });

        // Drop multilingual columns
        Schema::table('personnel_categories', function (Blueprint $table) {
            $table->dropColumn(['name_tr', 'name_de', 'description_tr', 'description_de']);
        });
    }
};
