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
        // Duplicate due_id kayıtlarını temizle
        // Aynı due_id'ye sahip kayıtlar arasından en son oluşturulanı tut, diğerlerini sil
        DB::statement("
            DELETE pd1 FROM payment_due pd1
            INNER JOIN payment_due pd2
            WHERE pd1.due_id = pd2.due_id
            AND pd1.id < pd2.id
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
