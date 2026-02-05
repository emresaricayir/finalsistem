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
        Schema::create('email_logs', function (Blueprint $table) {
            $table->id();
            $table->string('template_key'); // Email template key (member-welcome, due-reminder, etc.)
            $table->string('template_name'); // Human readable template name
            $table->string('recipient_email'); // Email address
            $table->string('recipient_name')->nullable(); // Recipient name
            $table->string('subject'); // Email subject
            $table->enum('status', ['sent', 'failed', 'pending'])->default('pending'); // Email status
            $table->text('error_message')->nullable(); // Error message if failed
            $table->json('variables')->nullable(); // Variables used in template
            $table->string('sent_by')->nullable(); // Who sent the email (admin user)
            $table->string('batch_id')->nullable(); // For bulk operations (like overdue reminders)
            $table->timestamp('sent_at')->nullable(); // When email was actually sent
            $table->timestamps();

            // Indexes
            $table->index(['template_key', 'status']);
            $table->index(['recipient_email']);
            $table->index(['batch_id']);
            $table->index(['sent_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_logs');
    }
};
