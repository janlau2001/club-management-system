<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('club_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('club_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('email');
            $table->string('password');
            $table->string('student_id')->nullable();
            $table->enum('role', ['member', 'officer']);
            $table->string('position')->nullable(); // For officers: President, Vice President, etc.
            $table->string('department');
            $table->string('year_level');
            $table->boolean('is_online')->default(false);
            $table->timestamp('last_activity')->nullable();
            $table->date('joined_date');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();

            // Allow same email for different clubs
            $table->unique(['club_id', 'email']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('club_users');
    }
};
