<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Bu migration, kullanıcı silindiğinde içeriklerin silinmesini önlemek için
     * foreign key constraint'lerini cascade'den nullOnDelete'e çevirir.
     */
    public function up(): void
    {
        // Helper function to safely drop foreign key
        $dropForeignKey = function($tableName, $columnName) {
            try {
                // Try to get constraint name from information_schema
                $constraints = DB::select("
                    SELECT CONSTRAINT_NAME 
                    FROM information_schema.KEY_COLUMN_USAGE 
                    WHERE TABLE_SCHEMA = DATABASE() 
                    AND TABLE_NAME = ? 
                    AND COLUMN_NAME = ? 
                    AND REFERENCED_TABLE_NAME = 'users'
                ", [$tableName, $columnName]);
                
                if (!empty($constraints)) {
                    $constraintName = $constraints[0]->CONSTRAINT_NAME;
                    DB::statement("ALTER TABLE `{$tableName}` DROP FOREIGN KEY `{$constraintName}`");
                } else {
                    // Try Laravel's default naming convention
                    try {
                        Schema::table($tableName, function (Blueprint $table) use ($columnName) {
                            $table->dropForeign([$columnName]);
                        });
                    } catch (\Exception $e) {
                        // Constraint might not exist, continue
                    }
                }
            } catch (\Exception $e) {
                // Constraint might not exist, continue
            }
        };

        // menus tablosu - created_by
        $dropForeignKey('menus', 'created_by');
        DB::statement('ALTER TABLE menus MODIFY created_by BIGINT UNSIGNED NULL');
        Schema::table('menus', function (Blueprint $table) {
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
        });

        // announcements tablosu - created_by
        $dropForeignKey('announcements', 'created_by');
        DB::statement('ALTER TABLE announcements MODIFY created_by BIGINT UNSIGNED NULL');
        Schema::table('announcements', function (Blueprint $table) {
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
        });

        // quick_accesses tablosu - created_by (sadece constraint varsa)
        try {
            $dropForeignKey('quick_accesses', 'created_by');
            DB::statement('ALTER TABLE quick_accesses MODIFY created_by BIGINT UNSIGNED NULL');
            Schema::table('quick_accesses', function (Blueprint $table) {
                $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
            });
        } catch (\Exception $e) {
            // quick_accesses tablosunda created_by kolonu yoksa veya constraint yoksa atla
        }

        // payments tablosu - recorded_by
        $dropForeignKey('payments', 'recorded_by');
        DB::statement('ALTER TABLE payments MODIFY recorded_by BIGINT UNSIGNED NULL');
        Schema::table('payments', function (Blueprint $table) {
            $table->foreign('recorded_by')->references('id')->on('users')->nullOnDelete();
        });

        // education_payments tablosu - recorded_by
        $dropForeignKey('education_payments', 'recorded_by');
        DB::statement('ALTER TABLE education_payments MODIFY recorded_by BIGINT UNSIGNED NULL');
        Schema::table('education_payments', function (Blueprint $table) {
            $table->foreign('recorded_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Geri alma işlemi - cascade'e geri döndür
        Schema::table('menus', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
        });
        DB::statement('ALTER TABLE menus MODIFY created_by BIGINT UNSIGNED NOT NULL');
        Schema::table('menus', function (Blueprint $table) {
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('announcements', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
        });
        DB::statement('ALTER TABLE announcements MODIFY created_by BIGINT UNSIGNED NOT NULL');
        Schema::table('announcements', function (Blueprint $table) {
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('quick_accesses', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
        });
        DB::statement('ALTER TABLE quick_accesses MODIFY created_by BIGINT UNSIGNED NOT NULL');
        Schema::table('quick_accesses', function (Blueprint $table) {
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['recorded_by']);
        });
        DB::statement('ALTER TABLE payments MODIFY recorded_by BIGINT UNSIGNED NOT NULL');
        Schema::table('payments', function (Blueprint $table) {
            $table->foreign('recorded_by')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('education_payments', function (Blueprint $table) {
            $table->dropForeign(['recorded_by']);
        });
        DB::statement('ALTER TABLE education_payments MODIFY recorded_by BIGINT UNSIGNED NOT NULL');
        Schema::table('education_payments', function (Blueprint $table) {
            $table->foreign('recorded_by')->references('id')->on('users')->onDelete('cascade');
        });
    }
};
