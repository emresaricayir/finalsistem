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
        Schema::table('members', function (Blueprint $table) {
            // tc_no alanını member_no olarak değiştir
            $table->renameColumn('tc_no', 'member_no');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            // member_no alanını tc_no olarak geri değiştir
            $table->renameColumn('member_no', 'tc_no');
        });
    }
};
