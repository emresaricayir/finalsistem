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
        // First, update existing values to match new enum
        DB::table('members')->where('payment_method', 'direct_debit')->update(['payment_method' => 'lastschrift']);
        DB::table('members')->where('payment_method', 'standing_order')->update(['payment_method' => 'bank_transfer']);

        // Then modify the enum to match system values
        DB::statement("ALTER TABLE members MODIFY COLUMN payment_method ENUM('cash', 'bank_transfer', 'lastschrift') NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Update values back to old enum
        DB::table('members')->where('payment_method', 'lastschrift')->update(['payment_method' => 'direct_debit']);
        DB::table('members')->where('payment_method', 'bank_transfer')->update(['payment_method' => 'standing_order']);

        // Restore old enum
        DB::statement("ALTER TABLE members MODIFY COLUMN payment_method ENUM('cash', 'direct_debit', 'standing_order') NULL");
    }
};






