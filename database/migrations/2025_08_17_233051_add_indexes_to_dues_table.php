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
        Schema::table('dues', function (Blueprint $table) {
            // Performans için indeksler
            $table->index(['member_id', 'status']);
            $table->index(['year', 'month']);
            $table->index('due_date');
            $table->index('status');
            $table->index('amount');
            $table->index(['member_id', 'year', 'month']); // Unique constraint zaten var ama ek indeks
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dues', function (Blueprint $table) {
            $table->dropIndex(['member_id', 'status']);
            $table->dropIndex(['year', 'month']);
            $table->dropIndex(['due_date']);
            $table->dropIndex(['status']);
            $table->dropIndex(['amount']);
            $table->dropIndex(['member_id', 'year', 'month']);
        });
    }
};
