<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Club;
use App\Models\ClubUser;
use App\Models\Officer;
use App\Models\Violation;
use Illuminate\Support\Facades\Hash;

echo "=== CREATING SAMPLE CLUBS WITH VIOLATIONS ===\n\n";

// Create Club 1 - Marketing Club (1st Offense)
echo "Creating Marketing Club...\n";
$club1 = Club::create([
    'name' => 'Marketing Club',
    'department' => 'SBAHM',
    'club_type' => 'Academic',
    'status' => 'active',
    'adviser_name' => 'Prof. Maria Santos',
    'adviser_email' => 'maria.santos@spup.edu.ph',
    'date_registered' => now()->subMonths(8),
    'description' => 'Dedicated to promoting marketing excellence and innovation among students.',
]);
echo "✓ Marketing Club created (ID: {$club1->id})\n";

// Create Officer 1 for Club 1
echo "Creating officer for Marketing Club...\n";
$officer1 = Officer::create([
    'name' => 'John Marketing',
    'email' => 'sample1@gmail.com',
    'password' => Hash::make('sample1pass'),
    'club_id' => $club1->id,
    'is_active' => true,
]);
echo "✓ Officer created: {$officer1->name} (sample1@gmail.com / sample1pass)\n";

// Create ClubUser entry for Officer 1
$clubUser1 = ClubUser::create([
    'club_id' => $club1->id,
    'name' => 'John Marketing',
    'email' => 'sample1@gmail.com',
    'password' => Hash::make('sample1pass'),
    'role' => 'officer',
    'position' => 'President',
    'student_id' => 'G2023-00001',
    'department' => 'SBAHM',
    'year_level' => '3rd Year',
    'status' => 'active',
    'joined_date' => now()->subMonths(8),
]);
echo "✓ Club user entry created\n";

// Create 1 confirmed violation for Club 1 (1st Offense)
echo "Creating 1st offense violation for Marketing Club...\n";
$violation1 = Violation::create([
    'club_id' => $club1->id,
    'title' => 'Missing Event Documentation',
    'violation_type' => 'Policy Violation',
    'description' => 'Failed to submit required event documentation within the specified deadline.',
    'violation_date' => now()->subMonths(2),
    'reported_by' => 'Head of Student Affairs',
    'severity' => 'moderate',
    'points' => 15,
    'status' => 'confirmed',
    'created_at' => now()->subMonths(2),
]);
echo "✓ 1st offense violation created\n";

echo "\n";

// Create Club 2 - Photography Club (2nd Offense)
echo "Creating Photography Club...\n";
$club2 = Club::create([
    'name' => 'Photography Club',
    'department' => 'SASTE',
    'club_type' => 'Interest',
    'status' => 'active',
    'adviser_name' => 'Prof. Robert Cruz',
    'adviser_email' => 'robert.cruz@spup.edu.ph',
    'date_registered' => now()->subMonths(12),
    'description' => 'Capturing moments and developing photography skills through creative expression.',
]);
echo "✓ Photography Club created (ID: {$club2->id})\n";

// Create Officer 2 for Club 2
echo "Creating officer for Photography Club...\n";
$officer2 = Officer::create([
    'name' => 'Sarah Photography',
    'email' => 'sample2@gmail.com',
    'password' => Hash::make('sample2pass'),
    'club_id' => $club2->id,
    'is_active' => true,
]);
echo "✓ Officer created: {$officer2->name} (sample2@gmail.com / sample2pass)\n";

// Create ClubUser entry for Officer 2
$clubUser2 = ClubUser::create([
    'club_id' => $club2->id,
    'name' => 'Sarah Photography',
    'email' => 'sample2@gmail.com',
    'password' => Hash::make('sample2pass'),
    'role' => 'officer',
    'position' => 'President',
    'student_id' => 'G2023-00002',
    'department' => 'SASTE',
    'year_level' => '4th Year',
    'status' => 'active',
    'joined_date' => now()->subMonths(12),
]);
echo "✓ Club user entry created\n";

// Create 2 confirmed violations for Club 2 (2nd Offense)
echo "Creating violations for Photography Club...\n";
$violation2_1 = Violation::create([
    'club_id' => $club2->id,
    'title' => 'Financial Misconduct',
    'violation_type' => 'Financial Misconduct',
    'description' => 'Improper handling of club funds and missing financial reports for two consecutive quarters.',
    'violation_date' => now()->subMonths(6),
    'reported_by' => 'Head of Student Affairs',
    'severity' => 'major',
    'points' => 30,
    'status' => 'confirmed',
    'created_at' => now()->subMonths(6),
]);
echo "✓ 1st violation created (6 months ago)\n";

$violation2_2 = Violation::create([
    'club_id' => $club2->id,
    'title' => 'Unauthorized Activity',
    'violation_type' => 'Code of Conduct',
    'description' => 'Conducted unauthorized off-campus activity without proper permits and supervision.',
    'violation_date' => now()->subMonths(1),
    'reported_by' => 'Head of Student Affairs',
    'severity' => 'moderate',
    'points' => 20,
    'status' => 'confirmed',
    'created_at' => now()->subMonths(1),
]);
echo "✓ 2nd violation created (1 month ago)\n";

echo "\n=== SUMMARY ===\n";
echo "Marketing Club (1st Offense):\n";
echo "  - President: John Marketing (sample1@gmail.com / sample1pass)\n";
echo "  - Violations: 1 confirmed\n";
echo "  - Expected Risk: LOW RISK (1st Offense - Official Warning)\n\n";

echo "Photography Club (2nd Offense):\n";
echo "  - President: Sarah Photography (sample2@gmail.com / sample2pass)\n";
echo "  - Violations: 2 confirmed\n";
echo "  - Expected Risk: MEDIUM RISK (2nd Offense - Temporary Suspension)\n\n";

echo "=== DONE ===\n";
echo "You can now:\n";
echo "1. Login as sample1@gmail.com (sample1pass) to access Marketing Club\n";
echo "2. Login as sample2@gmail.com (sample2pass) to access Photography Club\n";
echo "3. Check Decision Support System in Head Office to see violation analysis\n";
