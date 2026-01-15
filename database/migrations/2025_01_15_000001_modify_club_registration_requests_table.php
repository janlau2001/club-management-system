<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('club_registration_requests', function (Blueprint $table) {
            // Drop old columns that are no longer needed
            $table->dropColumn([
                'club_type',
                'adviser_name', 
                'adviser_email',
                'expected_member_count'
            ]);
            
            // Add new columns for the application
            $table->enum('nature', ['Academic', 'Interest'])->after('department');
            $table->text('rationale')->after('nature');
            $table->string('recommended_adviser')->after('rationale');
            $table->string('constitution_file')->nullable()->after('recommended_adviser');
            $table->string('officers_list_file')->nullable()->after('constitution_file');
            $table->string('activities_plan_file')->nullable()->after('officers_list_file');
            $table->string('budget_proposal_file')->nullable()->after('activities_plan_file');
            $table->timestamp('submitted_at')->default(now())->after('budget_proposal_file');
        });

        // Drop the related tables since we're changing the structure
        Schema::dropIfExists('club_registration_members');
        Schema::dropIfExists('club_registration_officers');
    }

    public function down(): void
    {
        Schema::table('club_registration_requests', function (Blueprint $table) {
            // Restore old columns
            $table->string('club_type')->after('department');
            $table->string('adviser_name')->after('rationale');
            $table->string('adviser_email')->after('adviser_name');
            $table->integer('expected_member_count')->default(0)->after('adviser_email');
            
            // Drop new columns
            $table->dropColumn([
                'nature',
                'rationale',
                'recommended_adviser',
                'constitution_file',
                'officers_list_file',
                'activities_plan_file',
                'budget_proposal_file',
                'submitted_at'
            ]);
        });

        // Recreate the dropped tables
        Schema::create('club_registration_officers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->constrained('club_registration_requests')->onDelete('cascade');
            $table->string('name');
            $table->string('position');
            $table->string('email');
            $table->timestamps();
        });

        Schema::create('club_registration_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->constrained('club_registration_requests')->onDelete('cascade');
            $table->string('name');
            $table->string('student_id');
            $table->string('email');
            $table->string('department');
            $table->string('year_level');
            $table->timestamps();
        });
    }
};