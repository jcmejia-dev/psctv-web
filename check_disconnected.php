<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Subscription;
use App\Models\Invoice;

echo "=== DISCONNECTED SUBSCRIPTIONS ===\n\n";

$disconnectedSubscriptions = Subscription::where('service_status', 'disconnected')
    ->with(['customer.user', 'package', 'invoices'])
    ->get();

if ($disconnectedSubscriptions->isEmpty()) {
    echo "No disconnected subscriptions found.\n";
    exit;
}

foreach ($disconnectedSubscriptions as $subscription) {
    echo "Subscription ID: {$subscription->id}\n";
    echo "Customer: {$subscription->customer->user->name}\n";
    echo "Package: {$subscription->package->name}\n";
    echo "Subscription Status: {$subscription->status}\n";
    echo "Service Status: {$subscription->service_status}\n";

    $overdueInvoices = $subscription->invoices->where('status', 'overdue');
    echo "Overdue Invoices: {$overdueInvoices->count()}\n";

    if ($overdueInvoices->count() > 0) {
        echo "Overdue Invoice Details:\n";
        foreach ($overdueInvoices as $invoice) {
            echo "  - Invoice #{$invoice->invoice_no}: ₱" . number_format($invoice->amount_due, 2) . " (Due: {$invoice->due_date->format('M d, Y')})\n";
        }
    }

    echo "\n" . str_repeat("-", 50) . "\n\n";
}

echo "Total disconnected subscriptions: {$disconnectedSubscriptions->count()}\n";
