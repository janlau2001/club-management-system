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
        Schema::table('clubs', function (Blueprint $table) {
            // Add nature field
            if (!Schema::hasColumn('clubs', 'nature')) {
                $table->enum('nature', ['Academic', 'Interest'])->after('department')->default('Academic');
            }
            
            // Add adviser field (unified name from adviser_name)
            if (!Schema::hasColumn('clubs', 'adviser')) {
                $table->string('adviser')->after('description')->nullable();
            }
            
            // Add registration_date field
            if (!Schema::hasColumn('clubs', 'registration_date')) {
                $table->timestamp('registration_date')->after('adviser')->default(now());
            }
            
            // Add officer_id field
            if (!Schema::hasColumn('clubs', 'officer_id')) {
                $table->unsignedBigInteger('officer_id')->after('registration_date')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clubs', function (Blueprint $table) {
            $table->dropColumn(['nature', 'adviser', 'registration_date', 'officer_id']);
        });
    }
};
