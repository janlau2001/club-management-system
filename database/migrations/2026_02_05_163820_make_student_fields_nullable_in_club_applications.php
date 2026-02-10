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
        Schema::table('club_applications', function (Blueprint $table) {
            // Make student-specific fields nullable for adviser applications
            $table->string('student_id')->nullable()->change();
            $table->string('department')->nullable()->change();
            $table->string('year_level')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('club_applications', function (Blueprint $table) {
            // Revert to not nullable
            $table->string('student_id')->nullable(false)->change();
            $table->string('department')->nullable(false)->change();
            $table->string('year_level')->nullable(false)->change();
        });
    }
};
