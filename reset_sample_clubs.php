<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Club;
use App\Models\Violation;
use App\Models\ViolationAppeal;

echo "Resetting sample clubs to initial violation state...\n\n";

// Find sample clubs by name
$marketingClub = Club::where('name', 'like', '%Marketing%')->first();
$photographyClub = Club::where('name', 'like', '%Photography%')->first();

if (!$marketingClub) {
    echo "❌ Marketing Club not found\n";
    echo "Searching for available clubs:\n";
    $clubs = Club::all();
    foreach ($clubs as $club) {
        echo "   - ID: {$club->id}, Name: {$club->name}, Dept: {$club->department}\n";
    }
    exit;
} else {
    echo "📋 Marketing Club (ID: {$marketingClub->id})\n";
    
    // Delete all appeals for this club's violations
    $appeals = ViolationAppeal::where('club_id', $marketingClub->id)->get();
    foreach ($appeals as $appeal) {
        echo "   - Deleting appeal ID: {$appeal->id}\n";
        $appeal->delete();
    }
    
    // Reset all violations to confirmed status
    $violations = Violation::where('club_id', $marketingClub->id)->get();
    foreach ($violations as $violation) {
        echo "   - Resetting violation ID: {$violation->id} to 'confirmed'\n";
        $violation->update(['status' => 'confirmed']);
    }
    
    // Set club status based on violation count
    $violationCount = $violations->count();
    if ($violationCount >= 2) {
        echo "   - Setting club status to 'suspended' (has {$violationCount} violations)\n";
        $marketingClub->update(['status' => 'suspended']);
    } else {
        echo "   - Setting club status to 'active' (has {$violationCount} violation)\n";
        $marketingClub->update(['status' => 'active']);
    }
    
    echo "   ✅ Marketing Club reset complete\n\n";
}

if (!$photographyClub) {
    echo "❌ Photography Club not found\n";
} else {
    echo "📋 Photography Club (ID: {$photographyClub->id})\n";
    
    // Delete all appeals for this club's violations
    $appeals = ViolationAppeal::where('club_id', $photographyClub->id)->get();
    foreach ($appeals as $appeal) {
        echo "   - Deleting appeal ID: {$appeal->id}\n";
        $appeal->delete();
    }
    
    // Reset all violations to confirmed status
    $violations = Violation::where('club_id', $photographyClub->id)->get();
    foreach ($violations as $violation) {
        echo "   - Resetting violation ID: {$violation->id} to 'confirmed'\n";
        $violation->update(['status' => 'confirmed']);
    }
    
    // Set club status based on violation count
    $violationCount = $violations->count();
    if ($violationCount >= 2) {
        echo "   - Setting club status to 'suspended' (has {$violationCount} violations)\n";
        $photographyClub->update(['status' => 'suspended']);
    } else {
        echo "   - Setting club status to 'active' (has {$violationCount} violation)\n";
        $photographyClub->update(['status' => 'active']);
    }
    
    echo "   ✅ Photography Club reset complete\n\n";
}

echo "🎉 Reset complete!\n\n";
echo "Summary:\n";
echo "- All appeals deleted\n";
echo "- All violations reset to 'confirmed' status\n";
echo "- Club suspension status updated based on violation count\n";
echo "- Clubs with 2+ violations are now 'suspended'\n";
echo "- Clubs with 1 violation are now 'active'\n\n";
echo "You can now test the appeal system from the beginning!\n";
