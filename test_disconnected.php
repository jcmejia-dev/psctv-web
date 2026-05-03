<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Subscription;

$subscription = Subscription::first();

if ($subscription) {
    $subscription->update(['service_status' => 'disconnected']);
    echo "Updated subscription #" . $subscription->id . " to disconnected status\n";
    echo "Service Status: " . $subscription->service_status . "\n";
} else {
    echo "No subscriptions found\n";
}
