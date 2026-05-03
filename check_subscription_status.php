<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Subscription;

echo "Checking subscription statuses after seeder run:\n\n";

$subscriptions = Subscription::with('invoices')->get();

foreach ($subscriptions as $sub) {
    $overdueCount = $sub->invoices->where('status', 'overdue')->count();
    $hasOverdue = $sub->hasOverdueInvoices();

    echo "Subscription ID: {$sub->id}\n";
    echo "  Status: {$sub->status}\n";
    echo "  Service Status: {$sub->service_status}\n";
    echo "  Is Active: " . ($sub->is_active ? 'Yes' : 'No') . "\n";
    echo "  Overdue Invoices: {$overdueCount}\n";
    echo "  Has Overdue Invoices: " . ($hasOverdue ? 'Yes' : 'No') . "\n";
    echo "  Invoices:\n";

    foreach ($sub->invoices as $invoice) {
        echo "    - Invoice {$invoice->id}: {$invoice->status} (Due: {$invoice->due_date})\n";
    }

    echo "\n";
}
