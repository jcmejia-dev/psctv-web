<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Subscription;
use Illuminate\Support\Facades\DB;

echo "Testing database connection...\n";
try {
    DB::select('SELECT 1');
    echo "Connection OK\n";
} catch (Exception $e) {
    echo "Database connection failed: " . $e->getMessage() . "\n";
    exit(1);
}

echo "Looking for subscription ID 16...\n";
$sub = Subscription::find(16);

if ($sub) {
    echo "Subscription found: " . $sub->status . "\n";
    echo "Current cancelled_at: " . ($sub->cancelled_at ?? 'null') . "\n";
    echo "Current cancelled_by: " . ($sub->cancelled_by ?? 'null') . "\n";

    echo "Attempting to update...\n";
    $result = $sub->update([
        'status' => 'cancelled',
        'cancelled_at' => now(),
        'cancelled_by' => 1
    ]);

    echo "Update result: " . ($result ? 'true' : 'false') . "\n";

    $sub->refresh();
    echo "New status: " . $sub->status . "\n";
    echo "New cancelled_at: " . ($sub->cancelled_at ?? 'null') . "\n";
    echo "New cancelled_by: " . ($sub->cancelled_by ?? 'null') . "\n";
} else {
    echo "Subscription not found\n";
}
