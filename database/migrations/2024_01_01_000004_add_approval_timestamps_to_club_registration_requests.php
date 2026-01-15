<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // This migration is now handled by newer migration files
        // Keeping this empty to avoid conflicts during migration order
    }

    public function down(): void
    {
        // Nothing to rollback since up() is empty
    }
};
