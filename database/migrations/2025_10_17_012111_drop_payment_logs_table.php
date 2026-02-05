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
        Schema::dropIfExists('payment_logs');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Payment logs table will not be recreated
        // This is a permanent removal
    }
};
