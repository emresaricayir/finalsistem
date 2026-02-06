<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    /**
     * Run the migrations.
     * DSGVO uyumluluğu için gizlilik politikası onayı alanları
     */
    public function up(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->boolean('privacy_consent')->default(false)->after('sepa_agreement');
            $table->timestamp('privacy_consent_date')->nullable()->after('privacy_consent');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn(['privacy_consent', 'privacy_consent_date']);
        });
    }
};
