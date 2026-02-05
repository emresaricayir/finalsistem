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
        // Eğer title_tr yoksa ekle ve mevcut title verilerini kopyala
        if (!Schema::hasColumn('menus', 'title_tr')) {
            Schema::table('menus', function (Blueprint $table) {
                $table->string('title_tr')->nullable()->after('title');
            });

            // Mevcut title verilerini title_tr'ye kopyala
            DB::statement('UPDATE menus SET title_tr = title WHERE title_tr IS NULL');
        }

        // title_tr'yi zorunlu yap
        Schema::table('menus', function (Blueprint $table) {
            if (Schema::hasColumn('menus', 'title_tr')) {
                $table->string('title_tr')->nullable(false)->change();
            }
        });

        // Eğer title_de yoksa ekle
        if (!Schema::hasColumn('menus', 'title_de')) {
            Schema::table('menus', function (Blueprint $table) {
                $table->string('title_de')->nullable()->after('title_tr');
            });
        }

        // Eski title sütununu sil (eğer varsa ve title_tr ile aynı değilse)
        // Önce kontrol et, sonra sil
        if (Schema::hasColumn('menus', 'title')) {
            // Verilerin kopyalandığından emin ol
            DB::statement('UPDATE menus SET title_tr = COALESCE(title_tr, title) WHERE title_tr IS NULL');
            
            Schema::table('menus', function (Blueprint $table) {
                $table->dropColumn('title');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // title sütununu geri ekle
        if (!Schema::hasColumn('menus', 'title')) {
            Schema::table('menus', function (Blueprint $table) {
                $table->string('title')->after('id');
            });

            // title_tr verilerini title'a kopyala
            DB::statement('UPDATE menus SET title = title_tr WHERE title IS NULL');
        }

        // title_tr ve title_de sütunlarını sil
        Schema::table('menus', function (Blueprint $table) {
            if (Schema::hasColumn('menus', 'title_tr')) {
                $table->dropColumn('title_tr');
            }
            if (Schema::hasColumn('menus', 'title_de')) {
                $table->dropColumn('title_de');
            }
        });
    }
};
