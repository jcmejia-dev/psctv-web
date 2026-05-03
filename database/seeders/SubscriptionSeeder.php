<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\Package;
use App\Models\Subscription;
use App\Models\Invoice;
use Carbon\Carbon;

class SubscriptionSeeder extends Seeder
{
    public function run(): void
    {
        // Get or create a test package
        $package = Package::firstOrCreate(
            ['code' => 'TEST-PLAN'],
            [
                'name' => 'Test Internet Plan',
                'description' => '100 Mbps Fiber Plan',
                'speed_mbps' => 100,
                'channels' => 0,
                'price' => 999,
                'billing_cycle' => 'monthly',
                'is_active' => true,
            ]
        );

        // Get or create a test customer
        $customer = Customer::firstOrCreate(
            ['account_number' => 'ACC-TEST-001'],
            [
                'user_id' => 2, // Use existing user_id=2
                'status' => 'active',
                'address' => '123 Test St',
                'city' => 'Test City',
                'province' => 'Test Province',
                'zip_code' => '1234',
                'phone' => '09123456789',
            ]
        );

        // Create a subscription that started 1 month ago
        $subscription = Subscription::firstOrCreate(
            [
                'customer_id' => $customer->id,
                'package_id' => $package->id,
            ],
            [
                'start_date' => Carbon::now()->subMonth(),
                'end_date' => null, // ongoing
                'status' => 'active',
                'next_billing_date' => Carbon::now()->addDays(3), // Due in 3 days
            ]
        );

        // Add an unpaid invoice
        Invoice::firstOrCreate(
            [
                'subscription_id' => $subscription->id,
                'due_date' => Carbon::now()->addDays(3),
            ],
            [
                'amount_due' => $package->price,
                'status' => 'unpaid',
                'description' => 'First Test Invoice',
            ]
        );
    }
}
