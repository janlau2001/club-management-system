<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Club;
use App\Models\ClubUser;

echo "=== FIXING ADVISER DATA ===\n\n";

// Find Computer Club
$club = Club::where('name', 'Computer Club')->first();

if (!$club) {
    echo "Computer Club not found!\n";
    exit;
}

echo "Club: {$club->name} (ID: {$club->id})\n\n";

// Find Renante Marzan who has student_id but should have professor_id
$renante = ClubUser::where('club_id', $club->id)
    ->where('name', 'Renante Marzan')
    ->first();

if ($renante) {
    echo "Found Renante Marzan:\n";
    echo "  Current student_id: {$renante->student_id}\n";
    echo "  Current professor_id: {$renante->professor_id}\n";
    echo "  Current department: {$renante->department}\n";
    echo "  Current department_office: {$renante->department_office}\n";
    echo "  Role: {$renante->role}\n\n";
    
    // Fix the data
    echo "Fixing Renante's data...\n";
    $renante->professor_id = 'P12349-23';  // Move student_id to professor_id
    $renante->student_id = null;           // Clear student_id
    $renante->department_office = 'BSIT';  // Move department to department_office
    $renante->department = null;           // Clear department
    $renante->year_level = null;           // Clear year_level
    $renante->save();
    
    echo "Updated Renante:\n";
    echo "  New student_id: " . ($renante->student_id ?: 'null') . "\n";
    echo "  New professor_id: {$renante->professor_id}\n";
    echo "  New department: " . ($renante->department ?: 'null') . "\n";
    echo "  New department_office: {$renante->department_office}\n";
    echo "  New year_level: " . ($renante->year_level ?: 'null') . "\n";
}

echo "\n=== DONE ===\n";
