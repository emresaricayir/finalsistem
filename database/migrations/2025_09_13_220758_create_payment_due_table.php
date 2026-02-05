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
        Schema::create('payment_due', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->constrained()->onDelete('cascade');
            $table->foreignId('due_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2); // Bu ödemenin bu aidata katkısı
            $table->timestamps();

            // Aynı ödeme ve aidat kombinasyonu sadece bir kez olabilir
            $table->unique(['payment_id', 'due_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_due');
    }
};
