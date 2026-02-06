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
        Schema::create('access_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('members')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade'); // Nullable: Üye kendi verisini indirdiğinde null olabilir
            $table->string('action'); // 'view', 'edit', 'export', 'delete', 'restore', 'payment_create', 'payment_delete', 'due_create', 'due_delete'
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->text('details')->nullable(); // JSON formatında ek bilgiler
            $table->timestamps();

            // Indexes for performance
            $table->index(['member_id', 'created_at']);
            $table->index(['user_id', 'created_at']);
            $table->index('action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('access_logs');
    }
};
