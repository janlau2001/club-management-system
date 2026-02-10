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
        Schema::table('officers', function (Blueprint $table) {
            // Make fields nullable since they are collected in Step 2 (Personal Info)
            $table->string('name')->nullable()->change();
            $table->string('department')->nullable()->change();
            $table->string('club_status')->nullable()->change();
            $table->string('year_level')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('officers', function (Blueprint $table) {
            // Revert back to not nullable (if needed)
            $table->string('name')->nullable(false)->change();
            $table->string('department')->nullable(false)->change();
            $table->string('club_status')->nullable(false)->change();
            $table->string('year_level')->nullable(false)->change();
        });
    }
};
