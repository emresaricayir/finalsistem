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
        // MySQL'de enum değerlerini güncellemek için önce sütunu string yapıp sonra tekrar enum yapıyoruz
        Schema::table('members', function (Blueprint $table) {
            $table->string('payment_method')->nullable()->change();
        });

        Schema::table('members', function (Blueprint $table) {
            $table->enum('payment_method', ['cash', 'bank_transfer', 'lastschrift'])->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->string('payment_method')->nullable()->change();
        });

        Schema::table('members', function (Blueprint $table) {
            $table->enum('payment_method', ['cash', 'bank_transfer'])->nullable()->change();
        });
    }
};
