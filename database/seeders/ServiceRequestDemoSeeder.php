<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Technician;
use App\Models\ServiceRequest;

class ServiceRequestDemoSeeder extends Seeder
{
    public function run()
    {
        // create demo technicians if none exist
        if (Technician::count() == 0) {
            Technician::create(['technician_id'=>'TECH-001','full_name'=>'John Doe','email'=>'tech1@demo','status'=>'active']);
            Technician::create(['technician_id'=>'TECH-002','full_name'=>'Jane Smith','email'=>'tech2@demo','status'=>'active']);
        }

        ServiceRequest::create([
            'customer_name' => 'Maria Santos',
            'phone' => '09171234567',
            'address' => '123 Demo St',
            'service_type' => 'Installation',
            'notes' => 'Install at front door',
            'status' => 'pending',
        ]);
    }
}
