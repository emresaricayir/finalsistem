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
        Schema::table('gallery_images', function (Blueprint $table) {
            // Rename existing columns to Turkish versions
            $table->renameColumn('title', 'title_tr');
            $table->renameColumn('description', 'description_tr');

            // Add German versions
            $table->string('title_de')->nullable()->after('title_tr');
            $table->text('description_de')->nullable()->after('description_tr');
        });

        // Copy existing Turkish data to German fields for initial setup
        DB::table('gallery_images')->get()->each(function ($image) {
            DB::table('gallery_images')
                ->where('id', $image->id)
                ->update([
                    'title_de' => $image->title_tr,
                    'description_de' => $image->description_tr,
                ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gallery_images', function (Blueprint $table) {
            // Drop German columns first
            $table->dropColumn('title_de');
            $table->dropColumn('description_de');

            // Rename Turkish columns back to original
            $table->renameColumn('title_tr', 'title');
            $table->renameColumn('description_tr', 'description');
        });
    }
};
