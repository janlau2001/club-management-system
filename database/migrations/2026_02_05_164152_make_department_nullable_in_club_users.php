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
        Schema::table('club_users', function (Blueprint $table) {
            // Make department nullable for advisers (they use department_office instead)
            $table->string('department')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('club_users', function (Blueprint $table) {
            // Revert department to not nullable
            $table->string('department')->nullable(false)->change();
        });
    }
};
