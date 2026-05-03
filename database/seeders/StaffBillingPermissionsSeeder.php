<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\StaffModule;
use App\Models\StaffModulePermission;

class StaffBillingPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the billing module
        $billingModule = StaffModule::where('key', 'billing')->first();

        if (!$billingModule) {
            return; // Billing module not found
        }

        // Delete existing billing permissions to avoid duplicates
        StaffModulePermission::where('staff_module_id', $billingModule->id)->delete();

        // Create specific billing permissions for staff
        $billingPermissions = [
            [
                'action' => 'view_payments',
                'description' => 'View Payments - Staff MUST see all payments made by customers for verification and customer service',
            ],
            [
                'action' => 'record_payment',
                'description' => 'Add / Record Payment - Staff can mark invoices as paid and enter amount, method, reference number',
            ],
            [
                'action' => 'print_receipt',
                'description' => 'Print / Download Receipt - Staff can print a receipt for walk-in customers',
            ],
            [
                'action' => 'send_receipt_email',
                'description' => 'Send Payment Receipt Email - Staff can trigger sending the receipt to customer email',
            ],
            [
                'action' => 'view_invoice_details',
                'description' => 'View Invoice Details - View invoice amount due, previous payments, and subscription details',
            ],
        ];

        foreach ($billingPermissions as $permission) {
            StaffModulePermission::create([
                'staff_module_id' => $billingModule->id,
                'action' => $permission['action'],
                'description' => $permission['description'],
                'key' => "billing.{$permission['action']}",
            ]);
        }
    }
}
