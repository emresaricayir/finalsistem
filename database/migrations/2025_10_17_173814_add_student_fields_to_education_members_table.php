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
        Schema::table('education_members', function (Blueprint $table) {
            $table->string('student_name')->nullable()->after('surname');
            $table->string('student_surname')->nullable()->after('student_name');
            $table->dropColumn(['member_no', 'birth_date', 'birth_place', 'nationality', 'occupation', 'address']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('education_members', function (Blueprint $table) {
            $table->dropColumn(['student_name', 'student_surname']);
            $table->string('member_no')->unique()->after('surname');
            $table->date('birth_date')->nullable()->after('member_no');
            $table->string('birth_place')->nullable()->after('birth_date');
            $table->string('nationality')->nullable()->after('birth_place');
            $table->string('occupation')->nullable()->after('nationality');
            $table->text('address')->nullable()->after('occupation');
        });
    }
};
