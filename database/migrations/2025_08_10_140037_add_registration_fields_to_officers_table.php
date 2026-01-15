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
            $table->string('first_name')->nullable()->after('name');
            $table->string('last_name')->nullable()->after('first_name');
            $table->string('course')->nullable()->after('year_level');
            $table->string('student_id')->nullable()->unique()->after('course');
            $table->string('phone')->nullable()->after('student_id');
            $table->string('position')->nullable()->after('phone');
            $table->string('registration_status')->default('pending')->after('position');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('officers', function (Blueprint $table) {
            $table->dropColumn([
                'first_name',
                'last_name',
                'course',
                'student_id',
                'phone',
                'position',
                'registration_status'
            ]);
        });
    }
};
