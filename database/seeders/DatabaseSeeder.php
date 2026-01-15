<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Only seed admin accounts - required for system access
        $this->call([
            AdminSeeder::class,
            // ClubSeeder::class, // DISABLED - Sample clubs only
            // ClubUserSeeder::class, // DISABLED - Sample club users only
            // SampleDecisionSupportSeeder::class, // DISABLED - Sample violations only
        ]);
    }
}

