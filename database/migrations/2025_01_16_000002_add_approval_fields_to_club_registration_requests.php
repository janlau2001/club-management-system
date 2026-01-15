<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('club_registration_requests', function (Blueprint $table) {
            // Add approval fields
            $table->boolean('verified_by_osa')->default(false)->after('status');
            $table->timestamp('verified_by_osa_at')->nullable()->after('verified_by_osa');
            $table->string('verified_by_osa_user')->nullable()->after('verified_by_osa_at');
            
            $table->boolean('noted_by_director')->default(false)->after('verified_by_osa_user');
            $table->timestamp('noted_by_director_at')->nullable()->after('noted_by_director');
            $table->string('noted_by_director_user')->nullable()->after('noted_by_director_at');
            
            $table->boolean('approved_by_vp')->default(false)->after('noted_by_director_user');
            $table->timestamp('approved_by_vp_at')->nullable()->after('approved_by_vp');
            $table->string('approved_by_vp_user')->nullable()->after('approved_by_vp_at');
        });
    }

    public function down(): void
    {
        Schema::table('club_registration_requests', function (Blueprint $table) {
            $table->dropColumn([
                'verified_by_osa',
                'verified_by_osa_at',
                'verified_by_osa_user',
                'noted_by_director',
                'noted_by_director_at',
                'noted_by_director_user',
                'approved_by_vp',
                'approved_by_vp_at',
                'approved_by_vp_user'
            ]);
        });
    }
};