<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\Package;
use App\Models\Subscription;
use App\Models\Invoice;
use Carbon\Carbon;

class OverdueDisconnectedSeeder extends Seeder
{
    public function run(): void
    {
        // Get existing customers or create test customers
        $customers = Customer::where('account_number', 'like', 'OVERDUE-%')->get();

        if ($customers->isEmpty()) {
            // Create test customers for overdue billing
            $customers = collect([
                Customer::firstOrCreate(
                    ['account_number' => 'OVERDUE-DISCONNECTED-001'],
                    [
                        'user_id' => 1,
                        'status' => 'active',
                        'address' => '123 Overdue Disconnected St',
                        'city' => 'Test City',
                        'province' => 'Test Province',
                        'zip_code' => '12345',
                        'phone' => '09123456789',
                        'latitude' => 14.5995,
                        'longitude' => 120.9842,
                    ]
                ),
                Customer::firstOrCreate(
                    ['account_number' => 'OVERDUE-DISCONNECTED-002'],
                    [
                        'user_id' => 1,
                        'status' => 'active',
                        'address' => '456 Past Due Disconnected Ave',
                        'city' => 'Test City',
                        'province' => 'Test Province',
                        'zip_code' => '12345',
                        'phone' => '09123456790',
                        'latitude' => 14.5995,
                        'longitude' => 120.9842,
                    ]
                ),
            ]);
        }

        // Get or create a test package
        $package = Package::firstOrCreate(
            ['code' => 'OVERDUE-DISCONNECTED-PLAN'],
            [
                'name' => 'Overdue Disconnected Test Plan',
                'description' => '100 Mbps Internet Plan for Testing Overdue + Disconnected',
                'speed_mbps' => 100,
                'channels' => 0,
                'price' => 999,
                'billing_cycle' => 'monthly',
                'is_active' => true,
                'free_trial_days' => 0,
                'reconnection_fee' => 500,
            ]
        );

        // Create overdue invoices for each customer with disconnected service
        foreach ($customers as $customer) {
            // Create or get subscription with disconnected status
            $subscription = Subscription::firstOrCreate(
                [
                    'customer_id' => $customer->id,
                    'package_id' => $package->id,
                ],
                [
                    'start_date' => Carbon::now()->subMonths(3),
                    'end_date' => null,
                    'status' => 'active', // Subscription is still active
                    'service_status' => 'disconnected', // Service is disconnected due to overdue payment
                    'next_billing_date' => Carbon::now()->addDays(5),
                    'trial_end_date' => Carbon::now()->subDays(10),
                ]
            );

            // Ensure service status is disconnected
            $subscription->update(['service_status' => 'disconnected']);

            // Create multiple overdue invoices with different due dates
            $overdueDays = [30, 60, 90]; // Days past due - severe overdue

            foreach ($overdueDays as $days) {
                Invoice::firstOrCreate(
                    [
                        'subscription_id' => $subscription->id,
                        'due_date' => Carbon::now()->subDays($days),
                        'amount_due' => $package->price,
                    ],
                    [
                        'customer_id' => $customer->id,
                        'customer_name' => $customer->user->name ?? 'Test Customer',
                        'billing_date' => Carbon::now()->subDays($days + 5),
                        'status' => 'overdue', // Explicitly set as overdue
                        'description' => "Overdue Invoice - {$days} days past due - Service Disconnected",
                        'amount_paid' => 0,
                        'is_recurring' => true,
                        'invoice_no' => 'OVD-' . $subscription->id . '-' . $days,
                    ]
                );
            }

            // Create one recent unpaid invoice
            Invoice::firstOrCreate(
                [
                    'subscription_id' => $subscription->id,
                    'due_date' => Carbon::now()->subDays(5),
                    'amount_due' => $package->price,
                ],
                [
                    'customer_id' => $customer->id,
                    'customer_name' => $customer->user->name ?? 'Test Customer',
                    'billing_date' => Carbon::now()->subDays(10),
                    'status' => 'overdue',
                    'description' => 'Current Month Bill - Overdue - Service Disconnected',
                    'amount_paid' => 0,
                    'is_recurring' => true,
                    'invoice_no' => 'OVD-' . $subscription->id . '-CURRENT',
                ]
            );
        }

        $this->command->info('Overdue + Disconnected billing test data created successfully!');
        $this->command->info('Created ' . $customers->count() . ' customers with overdue invoices and disconnected services.');
        $this->command->info('Each customer has multiple overdue invoices and service_status = "disconnected".');
        $this->command->info('You should now see "Request Reconnection" buttons on their subscription pages.');
    }
}
