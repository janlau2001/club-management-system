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
            // email_verified_at and remember_token already added by previous migration
            $table->string('middle_initial', 10)->nullable()->after('first_name');
            $table->boolean('has_middle_initial')->default(true)->after('middle_initial');
            // Check if username column exists before dropping
            if (Schema::hasColumn('officers', 'username')) {
                $table->dropColumn('username');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('officers', function (Blueprint $table) {
            $table->dropColumn(['middle_initial', 'has_middle_initial']);
            $table->string('username')->nullable();
        });
    }
};
