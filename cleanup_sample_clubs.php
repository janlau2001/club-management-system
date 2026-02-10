<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Club;
use App\Models\ClubUser;
use App\Models\Officer;
use App\Models\Violation;
use Illuminate\Support\Facades\DB;

echo "=== CLEANING UP OLD SAMPLE DATA ===\n\n";

// Delete old violations for sample clubs
DB::table('violations')->whereIn('club_id', function($query) {
    $query->select('id')->from('clubs')->whereIn('name', ['Marketing Club', 'Photography Club']);
})->delete();
echo "✓ Deleted old violations\n";

// Delete old club users for sample clubs
DB::table('club_users')->whereIn('club_id', function($query) {
    $query->select('id')->from('clubs')->whereIn('name', ['Marketing Club', 'Photography Club']);
})->delete();
echo "✓ Deleted old club users\n";

// Delete old officers
DB::table('officers')->whereIn('email', ['sample1@gmail.com', 'sample2@gmail.com'])->delete();
echo "✓ Deleted old officers\n";

// Delete old clubs
DB::table('clubs')->whereIn('name', ['Marketing Club', 'Photography Club'])->delete();
echo "✓ Deleted old clubs\n";

echo "\n=== DONE ===\n";
