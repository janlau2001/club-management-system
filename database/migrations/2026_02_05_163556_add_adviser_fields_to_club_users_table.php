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
            // Add professor_id column for advisers
            $table->string('professor_id')->nullable()->after('student_id');
            
            // Add department_office column for advisers
            $table->string('department_office')->nullable()->after('department');
            
            // Make department nullable (advisers use department_office instead)
            $table->string('department')->nullable()->change();
            
            // Make year_level nullable (advisers don't need year_level)
            $table->string('year_level')->nullable()->change();
        });

        // Update role enum to include 'adviser'
        DB::statement("ALTER TABLE club_users MODIFY COLUMN role ENUM('member', 'officer', 'adviser') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert role enum to original values
        DB::statement("ALTER TABLE club_users MODIFY COLUMN role ENUM('member', 'officer') NOT NULL");

        Schema::table('club_users', function (Blueprint $table) {
            // Remove adviser-specific columns
            $table->dropColumn(['professor_id', 'department_office']);
            
            // Revert department to not nullable
            $table->string('department')->nullable(false)->change();
            
            // Revert year_level to not nullable
            $table->string('year_level')->nullable(false)->change();
        });
    }
};
