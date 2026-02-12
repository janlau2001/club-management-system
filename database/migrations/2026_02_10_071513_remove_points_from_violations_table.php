<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('violations', function (Blueprint $table) {
            $table->dropColumn('points');
        });

        // Update severity enum: remove 'critical', keep minor/moderate/major
        // Change any existing 'critical' records to 'major'
        DB::table('violations')->where('severity', 'critical')->update(['severity' => 'major']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('violations', function (Blueprint $table) {
            $table->integer('points')->default(0)->after('evidence');
        });
    }
};
