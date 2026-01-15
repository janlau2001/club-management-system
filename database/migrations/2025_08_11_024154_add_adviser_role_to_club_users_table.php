<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if we're using SQLite or MySQL
        $driver = DB::getDriverName();
        
        if ($driver === 'sqlite') {
            // SQLite doesn't support MODIFY COLUMN, so we need to be more careful
            // For now, we'll just leave the role column as is since it's mainly used for display
            // The actual logic is handled by the position column
        } else {
            // MySQL/MariaDB
            DB::statement("ALTER TABLE club_users MODIFY COLUMN role ENUM('member', 'officer', 'adviser') NOT NULL DEFAULT 'member'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = DB::getDriverName();
        
        if ($driver === 'sqlite') {
            // SQLite: No changes needed since we didn't modify anything in up()
        } else {
            // MySQL/MariaDB
            DB::statement("ALTER TABLE club_users MODIFY COLUMN role ENUM('member', 'officer') NOT NULL DEFAULT 'member'");
        }
    }
};
