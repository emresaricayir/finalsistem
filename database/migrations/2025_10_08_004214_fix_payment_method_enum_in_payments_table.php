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
        // First, update any existing 'lastschrift' values to 'bank_transfer'
        DB::table('payments')->where('payment_method', 'lastschrift')->update(['payment_method' => 'bank_transfer']);

        // Then modify the enum to include lastschrift
        DB::statement("ALTER TABLE payments MODIFY COLUMN payment_method ENUM('cash', 'bank_transfer', 'credit_card', 'other', 'lastschrift') DEFAULT 'cash'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Update any 'lastschrift' values back to 'bank_transfer'
        DB::table('payments')->where('payment_method', 'lastschrift')->update(['payment_method' => 'bank_transfer']);

        // Remove lastschrift from enum
        DB::statement("ALTER TABLE payments MODIFY COLUMN payment_method ENUM('cash', 'bank_transfer', 'credit_card', 'other') DEFAULT 'cash'");
    }
};
