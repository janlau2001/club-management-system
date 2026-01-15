<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('club_registration_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('officer_id')->constrained()->onDelete('cascade');
            $table->string('club_name');
            $table->string('department');
            $table->string('club_type');
            $table->text('description')->nullable();
            $table->string('adviser_name');
            $table->string('adviser_email');
            $table->integer('expected_member_count')->default(0);
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamps();
        });

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

    public function down(): void
    {
        Schema::dropIfExists('club_registration_members');
        Schema::dropIfExists('club_registration_officers');
        Schema::dropIfExists('club_registration_requests');
    }
};




