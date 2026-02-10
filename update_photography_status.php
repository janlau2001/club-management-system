<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Club;

$club = Club::find(6);
if ($club) {
    $club->update(['status' => 'active']);
    echo "✅ Photography Club status updated to: active\n";
    echo "   Now the Suspend button will appear on the Violation Analysis page\n";
} else {
    echo "❌ Photography Club not found\n";
}
