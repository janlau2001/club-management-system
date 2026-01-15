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
        Schema::create('violations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('club_id')->constrained()->onDelete('cascade');
            $table->string('violation_type'); // academic, behavioral, administrative, financial
            $table->enum('severity', ['minor', 'moderate', 'major', 'critical']);
            $table->string('title');
            $table->text('description');
            $table->text('evidence')->nullable();
            $table->integer('points')->default(0); // Points for scoring system
            $table->enum('status', ['pending', 'confirmed', 'dismissed', 'appealed'])->default('pending');
            $table->string('reported_by')->nullable();
            $table->date('violation_date');
            $table->text('resolution_notes')->nullable();
            $table->date('resolved_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('violations');
    }
};
