<?php

use Illuminate\Support\Facades\Artisan;

// Example custom command
Artisan::command('subscriptions:expire', function () {
    $subscriptions = \App\Models\Subscription::where('status', 'active')
        ->whereNotNull('end_date')
        ->where('end_date', '<', now())
        ->get();

    foreach ($subscriptions as $sub) {
        $sub->update(['status' => 'expired']);
    }

    $this->info('Expired subscriptions updated!');
});
