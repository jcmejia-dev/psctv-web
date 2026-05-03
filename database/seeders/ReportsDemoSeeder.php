<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Technician;
use App\Models\ServiceRequest;
use App\Models\TechnicianJob;
use App\Models\Payment;
use App\Models\Customer;
use App\Models\User;
use Carbon\Carbon;

class ReportsDemoSeeder extends Seeder
{
    public function run()
    {
        // Create demo technicians if none exist
        if (Technician::count() == 0) {
            $technicians = [
                ['technician_id' => 'TECH-001', 'full_name' => 'John Doe', 'email' => 'john.doe@pctvs.com', 'status' => 'active'],
                ['technician_id' => 'TECH-002', 'full_name' => 'Jane Smith', 'email' => 'jane.smith@pctvs.com', 'status' => 'active'],
                ['technician_id' => 'TECH-003', 'full_name' => 'Mike Johnson', 'email' => 'mike.johnson@pctvs.com', 'status' => 'active'],
                ['technician_id' => 'TECH-004', 'full_name' => 'Sarah Wilson', 'email' => 'sarah.wilson@pctvs.com', 'status' => 'active'],
            ];

            foreach ($technicians as $tech) {
                Technician::create($tech);
            }
        }

        // Create demo customers (always create fresh ones for demo data)
        // Remove old demo customers by account number pattern
        Customer::where('account_number', 'like', 'DEMO-%')->delete();
        // Remove old demo users by email pattern
        User::where('email', 'like', '%@demo.customer')->delete();

        $customerData = [
            ['account_number' => 'DEMO-001', 'phone' => '09171234567', 'address' => '123 Rizal St, Davao City', 'name' => 'Maria Santos', 'email' => 'maria.santos@demo.customer'],
            ['account_number' => 'DEMO-002', 'phone' => '09181234567', 'address' => '456 Bonifacio Ave, Davao City', 'name' => 'Juan dela Cruz', 'email' => 'juan.delacruz@demo.customer'],
            ['account_number' => 'DEMO-003', 'phone' => '09191234567', 'address' => '789 Mabini St, Davao City', 'name' => 'Ana Reyes', 'email' => 'ana.reyes@demo.customer'],
            ['account_number' => 'DEMO-004', 'phone' => '09201234567', 'address' => '321 Aguinaldo Blvd, Davao City', 'name' => 'Pedro Garcia', 'email' => 'pedro.garcia@demo.customer'],
            ['account_number' => 'DEMO-005', 'phone' => '09211234567', 'address' => '654 Quezon Ave, Davao City', 'name' => 'Rosa Mendoza', 'email' => 'rosa.mendoza@demo.customer'],
        ];

        $customers = [];
        foreach ($customerData as $data) {
            // Create user first
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make('password123'),
                'role' => 'customer',
            ]);

            // Create customer linked to user
            $customer = Customer::create([
                'user_id' => $user->id,
                'account_number' => $data['account_number'],
                'phone' => $data['phone'],
                'address' => $data['address'],
                'status' => 'active',
            ]);

            // Store name and email for ServiceRequest creation
            $customer->demo_name = $data['name'];
            $customer->demo_email = $data['email'];
            $customers[] = $customer;
        }

        $technicians = Technician::all();
        $customers = Customer::all();

        // Service types and addresses for variety
        $serviceTypes = ['Installation', 'Repair', 'Maintenance', 'Upgrade', 'Troubleshooting'];
        $addresses = [
            '123 Rizal Street, Davao City',
            '456 Bonifacio Avenue, Davao City',
            '789 Mabini Street, Davao City',
            '321 Aguinaldo Boulevard, Davao City',
            '654 Quezon Avenue, Davao City',
            '987 Roxas Street, Davao City',
            '147 Burgos Avenue, Davao City',
            '258 Luna Street, Davao City',
            '369 Paterno Boulevard, Davao City',
            '741 Gomez Avenue, Davao City',
        ];

        // Generate service requests for the past 90 days
        $startDate = Carbon::now()->subDays(90);
        $endDate = Carbon::now();

        $totalRequests = 150; // Generate 150 service requests

        for ($i = 0; $i < $totalRequests; $i++) {
            $createdAt = Carbon::createFromTimestamp(rand($startDate->timestamp, $endDate->timestamp));

            // Determine status based on creation date
            $daysSinceCreated = $createdAt->diffInDays(Carbon::now());
            $status = $this->getStatusBasedOnDays($daysSinceCreated);

            $customer = $customers->random();
            $serviceRequest = ServiceRequest::create([
                'customer_id' => $customer->id,
                'customer_name' => $customer->user->name,
                'phone' => $customer->phone ?? '09' . rand(100000000, 999999999),
                'email' => $customer->user->email,
                'address' => $addresses[array_rand($addresses)],
                'service_type' => $serviceTypes[array_rand($serviceTypes)],
                'notes' => 'Demo service request generated for reports',
                'status' => $status,
                'technician_id' => $status !== 'pending' ? $technicians->random()->id : null,
                'assigned_at' => $status !== 'pending' ? $createdAt->copy()->addHours(rand(1, 24)) : null,
                'started_at' => in_array($status, ['in-progress', 'completed']) ? $createdAt->copy()->addHours(rand(2, 48)) : null,
                'completed_at' => $status === 'completed' ? $createdAt->copy()->addHours(rand(3, 72)) : null,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);

            // Create technician job if assigned
            if ($serviceRequest->technician_id) {
                TechnicianJob::create([
                    'service_request_id' => $serviceRequest->id,
                    'technician_id' => $serviceRequest->technician_id,
                    'status' => $status,
                    'assigned_at' => $serviceRequest->assigned_at,
                    'start_time' => $serviceRequest->started_at,
                    'end_time' => $serviceRequest->completed_at,
                    'notes' => 'Demo technician job for reports',
                ]);
            }

            // Create payment for completed services
            if ($status === 'completed') {
                $amount = rand(500, 5000); // Random amount between 500-5000 pesos

                Payment::create([
                    'amount_paid' => $amount,
                    'method' => ['cash', 'gcash', 'bank_transfer', 'credit_card'][array_rand(['cash', 'gcash', 'bank_transfer', 'credit_card'])],
                    'status' => 'completed',
                    'transaction_id' => 'TXN-' . str_pad($serviceRequest->id, 6, '0', STR_PAD_LEFT),
                    'payment_date' => $serviceRequest->completed_at,
                    'created_at' => $serviceRequest->completed_at,
                    'updated_at' => $serviceRequest->completed_at,
                ]);
            }
        }

        $this->command->info('Reports demo data seeded successfully!');
        $this->command->info('Created ' . $totalRequests . ' service requests with associated jobs and payments.');
    }

    private function getStatusBasedOnDays($daysSinceCreated)
    {
        // Probability distribution for status based on days since creation
        if ($daysSinceCreated < 1) {
            return 'pending'; // Very recent requests are likely pending
        } elseif ($daysSinceCreated < 3) {
            $statuses = ['pending', 'assigned', 'in-progress'];
            return $statuses[array_rand($statuses)];
        } elseif ($daysSinceCreated < 7) {
            $statuses = ['assigned', 'in-progress', 'completed'];
            return $statuses[array_rand($statuses)];
        } else {
            // Older requests are mostly completed
            $rand = rand(1, 100);
            if ($rand <= 85) return 'completed';
            if ($rand <= 95) return 'in-progress';
            return 'assigned';
        }
    }
}
