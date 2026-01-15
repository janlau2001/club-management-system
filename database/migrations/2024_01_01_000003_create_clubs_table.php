<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clubs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('department');
            $table->string('club_type');
            $table->enum('status', ['active', 'pending_renewal', 'suspended'])->default('active');
            $table->text('description')->nullable();
            $table->string('adviser_name');
            $table->string('adviser_email');
            $table->date('date_registered');
            $table->integer('member_count')->default(0);
            $table->timestamps();
        });

        Schema::create('club_officers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('club_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('position'); // President, Vice President, Secretary, etc.
            $table->string('email');
            $table->timestamps();
        });

        Schema::create('club_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('club_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('student_id');
            $table->string('email');
            $table->string('department');
            $table->string('year_level');
            $table->date('joined_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('club_members');
        Schema::dropIfExists('club_officers');
        Schema::dropIfExists('clubs');
    }
};

