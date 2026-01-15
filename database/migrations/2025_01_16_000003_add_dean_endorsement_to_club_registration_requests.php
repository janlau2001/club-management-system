<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('club_registration_requests', function (Blueprint $table) {
            $table->boolean('endorsed_by_dean')->default(false);
            $table->timestamp('endorsed_by_dean_at')->nullable();
            $table->string('endorsed_by_dean_user')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('club_registration_requests', function (Blueprint $table) {
            $table->dropColumn(['endorsed_by_dean', 'endorsed_by_dean_at', 'endorsed_by_dean_user']);
        });
    }
};