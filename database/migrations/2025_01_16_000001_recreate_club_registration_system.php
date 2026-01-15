<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop existing tables
        Schema::dropIfExists('club_registration_members');
        Schema::dropIfExists('club_registration_officers');
        Schema::dropIfExists('club_registration_requests');

        // Create new simplified table
        Schema::create('club_registration_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('officer_id')->constrained()->onDelete('cascade');
            $table->string('club_name');
            $table->string('department');
            $table->enum('nature', ['Academic', 'Interest']);
            $table->text('rationale');
            $table->string('recommended_adviser');
            $table->string('constitution_file')->nullable();
            $table->string('officers_list_file')->nullable();
            $table->string('activities_plan_file')->nullable();
            $table->string('budget_proposal_file')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamp('submitted_at')->default(now());
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('club_registration_requests');
    }
};