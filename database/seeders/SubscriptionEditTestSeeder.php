<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\Package;
use App\Models\Subscription;
use App\Models\Invoice;
use App\Models\User;
use Carbon\Carbon;

class SubscriptionEditTestSeeder extends Seeder
{
    public function run(): void
    {
        // Create test packages with different billing cycles
        $packages = [
            [
                'code' => 'BASIC-MONTHLY',
                'name' => 'Basic Monthly Plan',
                'description' => '50 Mbps Basic Plan',
                'speed_mbps' => 50,
                'channels' => 0,
                'price' => 799,
                'billing_cycle' => 'monthly',
                'free_trial_days' => 7,
                'is_active' => true,
                'type' => 'internet',
            ],
            [
                'code' => 'PREMIUM-YEARLY',
                'name' => 'Premium Yearly Plan',
                'description' => '200 Mbps Premium Plan',
                'speed_mbps' => 200,
                'channels' => 0,
                'price' => 8999,
                'billing_cycle' => 'yearly',
                'free_trial_days' => 14,
                'is_active' => true,
                'type' => 'internet',
            ],
            [
                'code' => 'ULTIMATE-MONTHLY',
                'name' => 'Ultimate Monthly Plan',
                'description' => '500 Mbps Ultimate Plan',
                'speed_mbps' => 500,
                'channels' => 0,
                'price' => 1499,
                'billing_cycle' => 'monthly',
                'free_trial_days' => 30,
                'is_active' => true,
                'type' => 'internet',
            ],
        ];

        $createdPackages = [];
        foreach ($packages as $packageData) {
            $package = Package::firstOrCreate(
                ['code' => $packageData['code']],
                $packageData
            );
            $createdPackages[] = $package;
        }

        // Create test customers
        $customers = [
            [
                'account_number' => 'ACC-EDIT-001',
                'user_id' => 2,
                'status' => 'active',
                'address' => '123 Test Street, Barangay 1',
                'city' => 'Test City',
                'province' => 'Test Province',
                'zip_code' => '1234',
                'phone' => '09123456789',
            ],
            [
                'account_number' => 'ACC-EDIT-002',
                'user_id' => 3,
                'status' => 'active',
                'address' => '456 Sample Avenue, Barangay 2',
                'city' => 'Sample City',
                'province' => 'Sample Province',
                'zip_code' => '5678',
                'phone' => '09987654321',
            ],
        ];

        $createdCustomers = [];
        foreach ($customers as $customerData) {
            $customer = Customer::firstOrCreate(
                ['account_number' => $customerData['account_number']],
                $customerData
            );
            $createdCustomers[] = $customer;
        }

        // Create subscriptions with different scenarios for testing
        $subscriptions = [
            [
                'customer' => $createdCustomers[0],
                'package' => $createdPackages[0], // Basic Monthly
                'start_date' => Carbon::now()->subDays(15), // Started 15 days ago
                'status' => 'active',
                'service_status' => 'active',
                'trial_end_date' => Carbon::now()->subDays(8), // Trial ended 8 days ago
                'next_billing_date' => Carbon::now()->addDays(15), // Next billing in 15 days
                'description' => 'Active subscription - Basic Monthly Plan',
            ],
            [
                'customer' => $createdCustomers[0],
                'package' => $createdPackages[1], // Premium Yearly
                'start_date' => Carbon::now()->subDays(5), // Started 5 days ago (in trial)
                'status' => 'active',
                'service_status' => 'active',
                'trial_end_date' => Carbon::now()->addDays(9), // Trial ends in 9 days
                'next_billing_date' => Carbon::now()->addDays(9), // Next billing when trial ends
                'description' => 'Active subscription in trial - Premium Yearly Plan',
            ],
            [
                'customer' => $createdCustomers[1],
                'package' => $createdPackages[2], // Ultimate Monthly
                'start_date' => Carbon::now()->subDays(45), // Started 45 days ago
                'status' => 'active',
                'service_status' => 'suspended',
                'trial_end_date' => Carbon::now()->subDays(15), // Trial ended 15 days ago
                'next_billing_date' => Carbon::now()->subDays(15), // Overdue
                'description' => 'Suspended subscription - Ultimate Monthly Plan',
            ],
            [
                'customer' => $createdCustomers[1],
                'package' => $createdPackages[0], // Basic Monthly
                'start_date' => Carbon::now()->subDays(60), // Started 60 days ago
                'status' => 'cancelled',
                'service_status' => 'disconnected',
                'trial_end_date' => Carbon::now()->subDays(53), // Trial ended 53 days ago
                'cancelled_at' => Carbon::now()->subDays(5), // Cancelled 5 days ago
                'next_billing_date' => null,
                'description' => 'Cancelled subscription - Basic Monthly Plan',
            ],
        ];

        foreach ($subscriptions as $subData) {
            $subscription = Subscription::firstOrCreate(
                [
                    'customer_id' => $subData['customer']->id,
                    'package_id' => $subData['package']->id,
                ],
                [
                    'start_date' => $subData['start_date'],
                    'end_date' => $subData['status'] === 'cancelled' ? Carbon::now()->subDays(5) : null,
                    'status' => $subData['status'],
                    'service_status' => $subData['service_status'],
                    'trial_end_date' => $subData['trial_end_date'],
                    'cancelled_at' => $subData['cancelled_at'] ?? null,
                    'next_billing_date' => $subData['next_billing_date'],
                ]
            );

            // Create some invoices for active subscriptions
            if ($subData['status'] === 'active') {
                $invoiceCount = rand(1, 3);
                for ($i = 0; $i < $invoiceCount; $i++) {
                    Invoice::firstOrCreate(
                        [
                            'subscription_id' => $subscription->id,
                            'due_date' => Carbon::now()->subDays($i * 30),
                        ],
                        [
                            'amount_due' => $subData['package']->price,
                            'status' => $i === 0 ? 'unpaid' : ($i === 1 ? 'paid' : 'overdue'),
                            'description' => "Invoice for {$subData['package']->name} - " . Carbon::now()->subDays($i * 30)->format('M Y'),
                        ]
                    );
                }
            }

            $this->command->info("Created subscription: {$subData['description']}");
        }

        $this->command->info('SubscriptionEditTestSeeder completed successfully!');
        $this->command->info('Created:');
        $this->command->info('- 3 packages (Basic Monthly, Premium Yearly, Ultimate Monthly)');
        $this->command->info('- 2 customers');
        $this->command->info('- 4 subscriptions with different statuses and scenarios');
        $this->command->info('- Multiple invoices for testing billing history');
    }
}
