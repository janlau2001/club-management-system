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
        Schema::create('violation_appeals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('violation_id')->constrained()->onDelete('cascade');
            $table->foreignId('club_id')->constrained()->onDelete('cascade');
            $table->string('submitted_by'); // Officer name and position
            $table->text('appeal_reason'); // Detailed explanation
            $table->json('supporting_documents')->nullable(); // Array of document paths
            $table->enum('status', ['pending', 'under_review', 'approved', 'rejected'])->default('pending');
            $table->text('review_notes')->nullable(); // Admin notes during review
            $table->string('reviewed_by')->nullable(); // Admin who reviewed
            $table->timestamp('submitted_at');
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('violation_appeals');
    }
};
