<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('club_users', function (Blueprint $table) {
            $table->string('phone', 13)->nullable()->after('email'); // Format: +63XXXXXXXXXX
            $table->string('course')->nullable()->after('year_level');
        });
    }

    public function down(): void
    {
        Schema::table('club_users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'course']);
        });
    }
};
