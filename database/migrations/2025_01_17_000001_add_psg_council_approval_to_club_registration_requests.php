<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('club_registration_requests', function (Blueprint $table) {
            // Add PSG Council approval fields
            $table->boolean('approved_by_psg_council')->default(false)->after('endorsed_by_dean_user');
            $table->timestamp('approved_by_psg_council_at')->nullable()->after('approved_by_psg_council');
            $table->string('approved_by_psg_council_user')->nullable()->after('approved_by_psg_council_at');
        });
    }

    public function down(): void
    {
        Schema::table('club_registration_requests', function (Blueprint $table) {
            $table->dropColumn([
                'approved_by_psg_council',
                'approved_by_psg_council_at',
                'approved_by_psg_council_user'
            ]);
        });
    }
};
