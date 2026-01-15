<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('club_registration_requests', function (Blueprint $table) {
            // Check if column exists
            if (!Schema::hasColumn('club_registration_requests', 'current_approval_step')) {
                // Create the column if it doesn't exist
                $table->string('current_approval_step', 50)->nullable()->after('status');
            } else {
                // Modify existing column to be longer
                $table->string('current_approval_step', 50)->nullable()->change();
            }
        });
    }

    public function down(): void
    {
        Schema::table('club_registration_requests', function (Blueprint $table) {
            if (Schema::hasColumn('club_registration_requests', 'current_approval_step')) {
                $table->dropColumn('current_approval_step');
            }
        });
    }
};
