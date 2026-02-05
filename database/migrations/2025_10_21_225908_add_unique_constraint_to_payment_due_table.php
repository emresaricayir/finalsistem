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
        Schema::table('payment_due', function (Blueprint $table) {
            // Aynı due_id'ye birden fazla payment bağlanmasını engelle
            $table->unique('due_id', 'payment_due_due_id_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_due', function (Blueprint $table) {
            $table->dropUnique('payment_due_due_id_unique');
        });
    }
};
