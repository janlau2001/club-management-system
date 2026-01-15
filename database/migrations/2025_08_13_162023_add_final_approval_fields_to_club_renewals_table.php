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
        Schema::table('club_renewals', function (Blueprint $table) {
            $table->string('approved_by')->nullable()->after('approved_at');
            $table->string('final_approved_by')->nullable()->after('approved_by');
            $table->timestamp('final_approved_at')->nullable()->after('final_approved_by');
            $table->string('rejected_by')->nullable()->after('rejected_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('club_renewals', function (Blueprint $table) {
            $table->dropColumn(['approved_by', 'final_approved_by', 'final_approved_at', 'rejected_by']);
        });
    }
};
