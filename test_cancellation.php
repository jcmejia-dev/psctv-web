<?php

require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Contracts\Console\Kernel;
use App\Models\Subscription;
use App\Http\Controllers\Admin\SubscriptionController;

$app = require_once 'bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

echo "Testing subscription cancellation...\n";

// Find an active subscription
$subscription = Subscription::where('status', 'active')->first();

if (!$subscription) {
    echo "No active subscription found to test with.\n";
    exit(1);
}

echo "Found subscription ID: {$subscription->id}\n";
echo "Current status: {$subscription->status}\n";
echo "Current service status: {$subscription->service_status}\n";

// Create a mock request to simulate AJAX call
$request = new \Illuminate\Http\Request();
$request->headers->set('Accept', 'application/json');
$request->headers->set('X-Requested-With', 'XMLHttpRequest');

// Bind the request to the container
app()->instance('request', $request);

// Create controller instance and call destroy method
$controller = new SubscriptionController();
try {
    echo "Calling controller destroy method...\n";
    $response = $controller->destroy($subscription->id);

    if ($response instanceof \Illuminate\Http\JsonResponse) {
        $data = $response->getData(true);
        echo "Controller returned JSON: " . json_encode($data) . "\n";
    } else {
        echo "Controller returned: " . gettype($response) . "\n";
    }

    echo "Cancellation executed successfully!\n";

    // Refresh the subscription from database
    $subscription->refresh();
    echo "Updated status: {$subscription->status}\n";
    echo "Updated service status: {$subscription->service_status}\n";
    echo "Cancelled at: {$subscription->cancelled_at}\n";

    // Check if pending invoices were cancelled
    $pendingInvoices = $subscription->invoices()->where('status', 'pending')->count();
    echo "Pending invoices remaining: {$pendingInvoices}\n";

    // Check invoice logs
    $logs = \App\Models\InvoiceLog::where('subscription_id', $subscription->id)->latest()->first();
    if ($logs) {
        echo "Invoice log created: {$logs->message}\n";
    }

    echo "✅ Test completed successfully!\n";

} catch (Exception $e) {
    echo "❌ Error during cancellation: " . $e->getMessage() . "\n";
    exit(1);
}
