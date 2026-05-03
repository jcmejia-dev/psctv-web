<?php

require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Contracts\Console\Kernel;

$app = require_once 'bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

echo "Checking subscriptions table columns...\n";

try {
    $columns = \Illuminate\Support\Facades\Schema::getColumnListing('subscriptions');
    echo "Columns in subscriptions table:\n";
    foreach ($columns as $column) {
        echo "- $column\n";
    }

    // Check if cancelled_at exists
    if (in_array('cancelled_at', $columns)) {
        echo "\n✅ cancelled_at column exists!\n";
    } else {
        echo "\n❌ cancelled_at column does NOT exist!\n";
    }

    if (in_array('cancelled_by', $columns)) {
        echo "✅ cancelled_by column exists!\n";
    } else {
        echo "❌ cancelled_by column does NOT exist!\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
