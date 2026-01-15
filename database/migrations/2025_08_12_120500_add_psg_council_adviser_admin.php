<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

return new class extends Migration
{
    public function up(): void
    {
        // Insert PSG Council Adviser admin
        Admin::create([
            'name' => 'PSG Council Adviser',
            'email' => 'psg.council@university.edu',
            'password' => Hash::make('password123'),
            'role' => 'psg_council_adviser',
        ]);
    }

    public function down(): void
    {
        // Remove PSG Council Adviser admin
        Admin::where('role', 'psg_council_adviser')->delete();
    }
};
