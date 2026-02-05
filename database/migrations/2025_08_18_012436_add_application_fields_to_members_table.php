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
            // Application status
            $table->enum('application_status', ['pending', 'approved', 'rejected'])->default('pending')->after('status');

            // Additional personal information
            $table->string('birth_place')->nullable()->after('birth_date');
            $table->string('nationality')->nullable()->after('birth_place');
            $table->integer('family_members_count')->nullable()->after('nationality');
            $table->boolean('funeral_fund_member')->default(false)->after('family_members_count');
            $table->boolean('community_register_member')->default(false)->after('funeral_fund_member');
            $table->string('occupation')->nullable()->after('community_register_member');
            $table->string('hometown')->nullable()->after('occupation');

            // Payment information
            $table->enum('payment_method', ['cash', 'direct_debit', 'standing_order'])->nullable()->after('monthly_dues');
            $table->string('mandate_number')->nullable()->after('payment_method');
            $table->string('account_holder')->nullable()->after('mandate_number');
            $table->string('bank_name')->nullable()->after('account_holder');
            $table->string('iban')->nullable()->after('bank_name');
            $table->string('bic')->nullable()->after('iban');
            $table->date('payment_due_date')->nullable()->after('bic');

            // Application tracking
            $table->timestamp('application_date')->nullable()->after('payment_due_date');
            $table->timestamp('approved_at')->nullable()->after('application_date');
            $table->string('approved_by')->nullable()->after('approved_at');
            $table->text('rejection_reason')->nullable()->after('approved_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn([
                'application_status',
                'birth_place',
                'nationality',
                'family_members_count',
                'funeral_fund_member',
                'community_register_member',
                'occupation',
                'hometown',
                'payment_method',
                'mandate_number',
                'account_holder',
                'bank_name',
                'iban',
                'bic',
                'payment_due_date',
                'application_date',
                'approved_at',
                'approved_by',
                'rejection_reason'
            ]);
        });
    }
};
