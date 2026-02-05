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
        Schema::table('events', function (Blueprint $table) {
            // Add multilingual fields
            $table->string('title_tr')->nullable()->after('title');
            $table->string('title_de')->nullable()->after('title_tr');
            $table->text('description_tr')->nullable()->after('description');
            $table->text('description_de')->nullable()->after('description_tr');
        });

        // Migrate existing data
        \DB::statement('UPDATE events SET title_tr = title, title_de = title WHERE title_tr IS NULL');
        \DB::statement('UPDATE events SET description_tr = description, description_de = description WHERE description IS NOT NULL AND description_tr IS NULL');

        Schema::table('events', function (Blueprint $table) {
            // Drop old columns
            $table->dropColumn(['title', 'description']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            // Add back old columns
            $table->string('title')->after('id');
            $table->text('description')->nullable()->after('title');
        });

        // Migrate data back
        \DB::statement('UPDATE events SET title = title_tr WHERE title IS NULL');
        \DB::statement('UPDATE events SET description = description_tr WHERE description IS NULL AND description_tr IS NOT NULL');

        Schema::table('events', function (Blueprint $table) {
            // Drop multilingual columns
            $table->dropColumn(['title_tr', 'title_de', 'description_tr', 'description_de']);
        });
    }
};
