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
        Schema::create('email_templates', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // Template identifier (member-welcome, due-reminder, etc.)
            $table->string('name'); // Human readable name
            $table->string('subject'); // Email subject
            $table->longText('html_content'); // HTML content
            $table->longText('text_content')->nullable(); // Plain text content (optional)
            $table->text('description')->nullable(); // Description of when this template is used
            $table->json('variables')->nullable(); // Available variables for this template
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_templates');
    }
};
