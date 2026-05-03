<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\Package;
use App\Models\Subscription;
use App\Models\Invoice;
use Carbon\Carbon;

class OverdueBillingSeeder extends Seeder
{
    public function run(): void
    {
        // Get existing customers or create test customers
        $customers = Customer::all();

        if ($customers->isEmpty()) {
            // Create test customers for overdue billing
            $customers = collect([
                Customer::firstOrCreate(
                    ['account_number' => 'OVERDUE-001'],
                    [
                        'user_id' => 1,
                        'status' => 'active',
                        'address' => '123 Overdue St',
                        'city' => 'Test City',
                        'province' => 'Test Province',
                        'zip_code' => '12345',
                        'phone' => '09123456789',
                    ]
                ),
                Customer::firstOrCreate(
                    ['account_number' => 'OVERDUE-002'],
                    [
                        'user_id' => 1,
                        'status' => 'active',
                        'address' => '456 Past Due Ave',
                        'city' => 'Test City',
                        'province' => 'Test Province',
                        'zip_code' => '12345',
                        'phone' => '09123456790',
                    ]
                ),
            ]);
        }

        // Get or create a test package
        $package = Package::firstOrCreate(
            ['code' => 'OVERDUE-PLAN'],
            [
                'name' => 'Overdue Test Plan',
                'description' => '100 Mbps Internet Plan for Testing',
                'speed_mbps' => 100,
                'channels' => 0,
                'price' => 999,
                'billing_cycle' => 'monthly',
                'is_active' => true,
            ]
        );

        // Create overdue invoices for each customer
        foreach ($customers as $customer) {
        // Create or get subscription
            $subscription = Subscription::firstOrCreate(
                [
                    'customer_id' => $customer->id,
                    'package_id' => $package->id,
                ],
                [
                    'start_date' => Carbon::now()->subMonths(2),
                    'end_date' => null,
                    'status' => 'active',
                    'service_status' => 'active', // Will be updated to suspended when invoices become overdue
                    'next_billing_date' => Carbon::now()->addDays(5),
                ]
            );

            // Create multiple overdue invoices with different due dates
            $overdueDays = [5, 15, 30]; // Days past due

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
                        'status' => 'unpaid', // Will be marked as 'overdue' by the model
                        'description' => "Overdue Test Invoice - {$days} days past due",
                        'amount_paid' => 0,
                        'is_recurring' => true,
                    ]
                );
            }
        }

        $this->command->info('Overdue billing test data created successfully!');
        $this->command->info('Created ' . $customers->count() . ' customers with overdue invoices.');
    }
}
