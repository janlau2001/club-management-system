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
        Schema::create('club_renewals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('club_id')->constrained()->onDelete('cascade');
            $table->string('academic_year');
            $table->date('last_renewal_date')->nullable();
            $table->string('department');
            $table->enum('nature', ['Academic', 'Interest']);
            $table->string('faculty_adviser');
            $table->text('rationale');

            // Document files
            $table->string('officers_list_file')->nullable();
            $table->string('activities_plan_file')->nullable();
            $table->string('budget_proposal_file')->nullable();
            $table->string('constitution_file')->nullable();

            // Internal approvals (club level)
            $table->boolean('prepared_by_president')->default(false);
            $table->timestamp('prepared_by_president_at')->nullable();
            $table->string('prepared_by_president_user')->nullable();

            $table->boolean('certified_by_adviser')->default(false);
            $table->timestamp('certified_by_adviser_at')->nullable();
            $table->string('certified_by_adviser_user')->nullable();

            // Admin approvals
            $table->boolean('noted_by_dean')->default(false);
            $table->timestamp('noted_by_dean_at')->nullable();
            $table->string('noted_by_dean_user')->nullable();

            $table->boolean('reviewed_by_psg')->default(false);
            $table->timestamp('reviewed_by_psg_at')->nullable();
            $table->string('reviewed_by_psg_user')->nullable();

            $table->boolean('endorsed_by_osa')->default(false);
            $table->timestamp('endorsed_by_osa_at')->nullable();
            $table->string('endorsed_by_osa_user')->nullable();

            $table->boolean('approved_by_vp')->default(false);
            $table->timestamp('approved_by_vp_at')->nullable();
            $table->string('approved_by_vp_user')->nullable();

            // Status and dates
            $table->enum('status', ['draft', 'pending_internal', 'pending_admin', 'approved', 'rejected'])->default('draft');
            $table->text('rejection_reason')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('club_renewals');
    }
};
